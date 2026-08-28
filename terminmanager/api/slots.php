<?php
require_once 'config.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // GET /slots.php?date=YYYY-MM-DD - Get all slots for a date with status
        // Slots inside the weekly schedule (availability_settings) are free by
        // default; the admin blocks single slots via blocked_slots.
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
                    WHEN av.id IS NULL THEN 'outside_schedule'
                    WHEN bs.id IS NOT NULL THEN 'blocked'
                    ELSE 'free'
                END AS status,
                e.id AS event_id,
                CASE WHEN c.id IS NOT NULL THEN CONCAT(c.first_name, ' ', c.last_name) ELSE NULL END AS customer_name
            FROM (
                SELECT 8 AS slot_hour UNION SELECT 9 UNION SELECT 10 UNION SELECT 11
                UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16
            ) s
            LEFT JOIN blocked_dates bd ON bd.blocked_date = :date1
                AND (bd.user_id = 1 OR bd.user_id IS NULL)
            LEFT JOIN availability_settings av ON av.user_id = 1
                AND av.active = TRUE
                AND av.day_of_week = DAYOFWEEK(:date2) - 1
                AND s.slot_hour >= av.start_slot
                AND s.slot_hour < av.end_slot
            LEFT JOIN blocked_slots bs ON bs.slot_date = :date3
                AND bs.slot_hour = s.slot_hour
                AND bs.user_id = 1
            LEFT JOIN events e ON e.event_date = :date4
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
            'date3' => $date,
            'date4' => $date
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
            // Toggle a single slot between free and blocked
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

            // Only slots inside the weekly schedule can be toggled
            $stmt = $conn->prepare("
                SELECT id FROM availability_settings
                WHERE user_id = 1
                AND active = TRUE
                AND day_of_week = DAYOFWEEK(:date) - 1
                AND :slot_hour1 >= start_slot
                AND :slot_hour2 < end_slot
            ");
            $stmt->execute(['date' => $date, 'slot_hour1' => $slotHour, 'slot_hour2' => $slotHour]);

            if (!$stmt->fetch()) {
                sendError('Slot is outside the weekly schedule', 400);
            }

            // Check if slot is already blocked
            $stmt = $conn->prepare("
                SELECT id FROM blocked_slots
                WHERE user_id = 1 AND slot_date = :date AND slot_hour = :hour
            ");
            $stmt->execute(['date' => $date, 'hour' => $slotHour]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Remove block (free the slot again)
                $stmt = $conn->prepare("DELETE FROM blocked_slots WHERE id = :id");
                $stmt->execute(['id' => $existing['id']]);
                sendJSON(['success' => true, 'action' => 'freed', 'new_status' => 'free']);
            } else {
                // Block the slot
                $stmt = $conn->prepare("
                    INSERT INTO blocked_slots (user_id, slot_date, slot_hour, created_by)
                    VALUES (1, :date, :hour, 'admin')
                ");
                $stmt->execute(['date' => $date, 'hour' => $slotHour]);
                sendJSON(['success' => true, 'action' => 'blocked', 'new_status' => 'blocked']);
            }

        } else {
            sendError('Invalid action. Use "toggle"');
        }

    } else {
        sendError('Method not allowed', 405);
    }

} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
?>
