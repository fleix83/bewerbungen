<?php
require_once 'config.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // GET /slots.php?date=YYYY-MM-DD - Get all slots for a date with status
        $date = $_GET['date'] ?? date('Y-m-d');

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            sendError('Invalid date format. Use YYYY-MM-DD');
        }

        // Get slot status for all hours 8-16 (last slot 16:00-17:00)
        $stmt = $conn->prepare("
            SELECT
                s.slot_hour,
                CONCAT(LPAD(s.slot_hour, 2, '0'), ':00 - ', LPAD(s.slot_hour + 1, 2, '0'), ':00') AS time_display,
                CASE
                    WHEN bd.id IS NOT NULL THEN 'blocked_holiday'
                    WHEN e.id IS NOT NULL THEN 'booked'
                    WHEN fs.id IS NOT NULL THEN 'free'
                    ELSE 'not_released'
                END AS status,
                fs.id AS free_slot_id,
                e.id AS event_id,
                CASE WHEN c.id IS NOT NULL THEN CONCAT(c.first_name, ' ', c.last_name) ELSE NULL END AS customer_name
            FROM (
                SELECT 8 AS slot_hour UNION SELECT 9 UNION SELECT 10 UNION SELECT 11
                UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16
            ) s
            LEFT JOIN blocked_dates bd ON bd.blocked_date = :date1
                AND (bd.user_id = 1 OR bd.user_id IS NULL)
            LEFT JOIN free_slots fs ON fs.slot_date = :date2
                AND fs.slot_hour = s.slot_hour
                AND fs.user_id = 1
            LEFT JOIN events e ON e.event_date = :date3
                AND e.user_id = 1
                AND e.status != 'cancelled'
                AND s.slot_hour >= e.start_slot
                AND s.slot_hour < e.end_slot
                AND e.event_type_id IN (SELECT id FROM event_types WHERE blocks_availability = TRUE)
            LEFT JOIN customers c ON e.customer_id = c.id
            ORDER BY s.slot_hour
        ");

        $stmt->execute([
            'date1' => $date,
            'date2' => $date,
            'date3' => $date
        ]);

        $slots = $stmt->fetchAll();

        // Format date in German
        $dateObj = new DateTime($date);
        $days = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        $months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        $formattedDate = $days[$dateObj->format('w')] . "\n" .
                         $dateObj->format('j') . '. ' .
                         $months[$dateObj->format('n') - 1] . ' ' .
                         $dateObj->format('Y');

        sendJSON([
            'date' => $date,
            'formatted_date' => $formattedDate,
            'slots' => $slots
        ]);

    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';

        if ($action === 'toggle') {
            // Toggle a single slot between free and not_released
            $date = $input['date'] ?? '';
            $slotHour = intval($input['slot_hour'] ?? -1);

            if (!$date || $slotHour < 8 || $slotHour > 16) {
                sendError('Invalid date or slot_hour (must be 8-16)');
            }

            // Check if slot is booked - cannot toggle
            $stmt = $conn->prepare("
                SELECT e.id FROM events e
                JOIN event_types et ON e.event_type_id = et.id
                WHERE e.user_id = 1
                AND e.event_date = :date
                AND et.blocks_availability = TRUE
                AND e.status != 'cancelled'
                AND :slot_hour1 >= e.start_slot
                AND :slot_hour2 < e.end_slot
            ");
            $stmt->execute(['date' => $date, 'slot_hour1' => $slotHour, 'slot_hour2' => $slotHour]);

            if ($stmt->fetch()) {
                sendError('Cannot modify a booked slot', 400);
            }

            // Check if free_slot exists
            $stmt = $conn->prepare("
                SELECT id FROM free_slots
                WHERE user_id = 1 AND slot_date = :date AND slot_hour = :hour
            ");
            $stmt->execute(['date' => $date, 'hour' => $slotHour]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Delete (block the slot)
                $stmt = $conn->prepare("DELETE FROM free_slots WHERE id = :id");
                $stmt->execute(['id' => $existing['id']]);
                sendJSON(['success' => true, 'action' => 'blocked', 'new_status' => 'not_released']);
            } else {
                // Insert (free the slot)
                $stmt = $conn->prepare("
                    INSERT INTO free_slots (user_id, slot_date, slot_hour, created_by)
                    VALUES (1, :date, :hour, 'admin')
                ");
                $stmt->execute(['date' => $date, 'hour' => $slotHour]);
                sendJSON(['success' => true, 'action' => 'freed', 'new_status' => 'free']);
            }

        } elseif ($action === 'generate') {
            // Generate random free slots for multiple months
            $monthsToGenerate = intval($input['months'] ?? 3);
            $slotsCreated = 0;
            $daysProcessed = 0;
            $daysSkipped = 0;

            $conn->beginTransaction();

            try {
                // Get date range: first day of current month to last day of target month
                $startDate = new DateTime('first day of this month');
                $endDate = clone $startDate;
                $endDate->modify("+{$monthsToGenerate} months");
                $endDate->modify('last day of previous month');

                $currentDate = clone $startDate;

                while ($currentDate <= $endDate) {
                    $dateStr = $currentDate->format('Y-m-d');
                    $dayOfWeek = (int)$currentDate->format('w'); // 0=Sun, 6=Sat

                    // Skip Sundays
                    if ($dayOfWeek === 0) {
                        $currentDate->modify('+1 day');
                        continue;
                    }

                    // Check if date is blocked (holiday)
                    $stmt = $conn->prepare("
                        SELECT 1 FROM blocked_dates
                        WHERE blocked_date = :date AND (user_id = 1 OR user_id IS NULL)
                    ");
                    $stmt->execute(['date' => $dateStr]);

                    if ($stmt->fetch()) {
                        // Skip blocked dates
                        $daysSkipped++;
                        $currentDate->modify('+1 day');
                        continue;
                    }

                    // Generate slots for this day (INSERT IGNORE preserves existing slots)
                    $newSlots = generateSlotsForDay($dayOfWeek);

                    foreach ($newSlots as $hour) {
                        $stmt = $conn->prepare("
                            INSERT IGNORE INTO free_slots (user_id, slot_date, slot_hour, created_by)
                            VALUES (1, :date, :hour, 'generator')
                        ");
                        $stmt->execute(['date' => $dateStr, 'hour' => $hour]);
                        if ($stmt->rowCount() > 0) {
                            $slotsCreated++;
                        }
                    }
                    $daysProcessed++;

                    $currentDate->modify('+1 day');
                }

                $conn->commit();
                sendJSON([
                    'success' => true,
                    'slots_created' => $slotsCreated,
                    'days_processed' => $daysProcessed,
                    'days_skipped' => $daysSkipped,
                    'date_range' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => $endDate->format('Y-m-d')
                    ]
                ]);

            } catch (Exception $e) {
                $conn->rollBack();
                sendError('Generation failed: ' . $e->getMessage(), 500);
            }

        } else {
            sendError('Invalid action. Use "toggle" or "generate"');
        }

    } else {
        sendError('Method not allowed', 405);
    }

} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}

/**
 * Generate double slots (2h each) for a given day of week
 * - Weekdays (Mon-Fri): 3 double slots (morning, midday, afternoon)
 * - Saturday: 1 double slot at 10:00
 * - Sunday: Should be skipped before calling this function
 *
 * @param int $dayOfWeek 0=Sun, 1=Mon, ..., 6=Sat
 * @return array Array of hour integers to free
 */
function generateSlotsForDay($dayOfWeek) {
    // Saturday: single double slot at 10:00
    if ($dayOfWeek === 6) {
        return [10, 11]; // 10:00-12:00
    }

    // Weekdays (Mon-Fri): 3 double slots in non-overlapping windows
    $slots = [];

    // Morning slot: 9:00-11:00 (fixed)
    $slots[] = 9;
    $slots[] = 10;

    // Midday slot: 12:00-14:00 or 13:00-15:00 (random)
    $middayOptions = [12, 13];
    $middayStart = $middayOptions[array_rand($middayOptions)];
    $slots[] = $middayStart;
    $slots[] = $middayStart + 1;

    // Afternoon slot: 15:00-17:00 (fixed)
    $slots[] = 15;
    $slots[] = 16;

    sort($slots);
    return $slots;
}
?>
