<?php
require_once 'config.php';
require_once 'email.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method !== 'POST') {
        sendError('Method not allowed', 405);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        sendError('Invalid JSON input');
    }

    $token = isset($input['cancellation_token']) ? trim($input['cancellation_token']) : '';
    $eventId = isset($input['event_id']) ? intval($input['event_id']) : 0;
    $email = isset($input['customer_email']) ? trim($input['customer_email']) : '';

    if ($token === '' && ($eventId <= 0 || $email === '')) {
        sendError('cancellation_token oder event_id + customer_email sind erforderlich');
    }

    // Look up the event by token (preferred) or by event_id + email match.
    if ($token !== '') {
        $stmt = $conn->prepare("
            SELECT e.id, e.event_date, e.start_slot, e.end_slot, e.status,
                   c.first_name, c.last_name, c.email AS customer_email
              FROM events e
              LEFT JOIN customers c ON c.id = e.customer_id
             WHERE e.cancellation_token = :token
             LIMIT 1
        ");
        $stmt->execute(['token' => $token]);
        $event = $stmt->fetch();

        if (!$event) {
            sendError('Termin nicht gefunden oder bereits storniert', 404);
        }
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendError('Ungültige E-Mail-Adresse');
        }
        $stmt = $conn->prepare("
            SELECT e.id, e.event_date, e.start_slot, e.end_slot, e.status,
                   c.first_name, c.last_name, c.email AS customer_email
              FROM events e
              LEFT JOIN customers c ON c.id = e.customer_id
             WHERE e.id = :id
             LIMIT 1
        ");
        $stmt->execute(['id' => $eventId]);
        $event = $stmt->fetch();

        // Generic message for both 404 and email-mismatch so attackers can't probe IDs.
        if (!$event || !$event['customer_email'] ||
            strcasecmp(trim($event['customer_email']), $email) !== 0) {
            sendError('Termin nicht gefunden', 404);
        }
    }

    // 24h cutoff. start_slot is the hour-of-day (see availability.php).
    $startSlot = intval($event['start_slot']);
    $startStr = $event['event_date'] . ' ' . sprintf('%02d:00:00', $startSlot);
    $startTs = strtotime($startStr);
    if ($startTs === false) {
        sendError('Termin-Daten ungültig', 500);
    }
    if (($startTs - time()) < 24 * 3600) {
        sendError('Stornierung nicht mehr möglich. Bitte telefonisch absagen.', 409);
    }

    // Snapshot the customer/time data BEFORE deleting so we can send emails.
    $dateObj = new DateTime($event['event_date']);
    $germanDays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
    $germanMonths = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
    $formattedDate = $germanDays[(int)$dateObj->format('w')] . ', ' . $dateObj->format('d') . '. '
        . $germanMonths[(int)$dateObj->format('n')] . ' ' . $dateObj->format('Y');
    $formattedTime = sprintf('%02d:00 - %02d:00', $startSlot, intval($event['end_slot']));

    $emailData = [
        'customer_email' => $event['customer_email'],
        'customer_name' => trim($event['first_name'] . ' ' . $event['last_name']),
        'date' => $formattedDate,
        'time' => $formattedTime
    ];

    // Hard delete the event. bookings rows cascade-delete via FK.
    $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
    $stmt->execute(['id' => intval($event['id'])]);

    $emailSent = false;
    $adminEmailSent = false;
    if (!empty($emailData['customer_email'])) {
        $emailSent = sendCancellationConfirmationCustomer($emailData);
    }
    $adminEmailSent = sendCancellationNotificationAdmin($emailData);

    sendJSON([
        'success' => true,
        'message' => 'Termin storniert',
        'email_sent' => $emailSent,
        'admin_email_sent' => $adminEmailSent
    ]);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
?>
