<?php
require_once 'config.php';

$conn = getDBConnection();
$type = $_GET['type'] ?? '';

try {
    if ($type === 'month') {
        $year = intval($_GET['year'] ?? date('Y'));
        $month = intval($_GET['month'] ?? date('m'));

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $stmt = $conn->prepare("
            SELECT
                DATE(event_date) as date,
                COUNT(*) > 0 as has_free_slots
            FROM v_available_slots
            WHERE event_date BETWEEN :start_date AND :end_date
            GROUP BY DATE(event_date)
        ");

        $stmt->execute([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        $availability = $stmt->fetchAll();

        foreach ($availability as &$day) {
            $day['has_free_slots'] = (bool) $day['has_free_slots'];
        }

        sendJSON($availability);

    } elseif ($type === 'day') {
        $date = $_GET['date'] ?? date('Y-m-d');
        $slotsNeeded = intval($_GET['slots'] ?? 1);

        // Get all available slots from the view for this date
        $stmt = $conn->prepare("
            SELECT start_slot, end_slot, time_display
            FROM v_available_slots
            WHERE event_date = :date
            ORDER BY start_slot
        ");
        $stmt->execute(['date' => $date]);
        $availableSlots = $stmt->fetchAll();

        // Build a set of available start hours
        $availableHours = [];
        foreach ($availableSlots as $slot) {
            $availableHours[$slot['start_slot']] = true;
        }

        // Generate slots based on duration needed
        $slots = [];
        for ($hour = 8; $hour <= 21; $hour++) {
            $endHour = $hour + $slotsNeeded;

            // Check if all required consecutive hours are available
            $isAvailable = true;
            for ($h = $hour; $h < $endHour; $h++) {
                if (!isset($availableHours[$h])) {
                    $isAvailable = false;
                    break;
                }
            }

            $slots[] = [
                'start_slot' => $hour,
                'end_slot' => $endHour,
                'status' => $isAvailable ? 'free' : 'occupied'
            ];
        }

        sendJSON($slots);

    } else {
        sendError('Invalid type parameter. Use "month" or "day"');
    }
} catch(PDOException $e) {
    sendError('Failed to fetch availability: ' . $e->getMessage(), 500);
}
?>
