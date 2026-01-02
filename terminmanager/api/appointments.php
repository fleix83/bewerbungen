<?php
require_once 'config.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            sendError('Appointment ID required');
        }

        $stmt = $conn->prepare("
            SELECT * FROM v_booking_details
            WHERE event_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $appointment = $stmt->fetch();

        if (!$appointment) {
            sendError('Appointment not found', 404);
        }

        sendJSON($appointment);

    } elseif ($method === 'PUT') {
        $id = $_GET['id'] ?? null;
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$id) {
            sendError('Appointment ID required');
        }

        if (!isset($input['status'])) {
            sendError('Status is required');
        }

        $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($input['status'], $allowedStatuses)) {
            sendError('Invalid status value');
        }

        $stmt = $conn->prepare("
            UPDATE events
            SET status = :status
            WHERE id = :id
        ");

        $stmt->execute([
            'status' => $input['status'],
            'id' => $id
        ]);

        sendJSON([
            'success' => true,
            'message' => 'Appointment status updated successfully'
        ]);

    } else {
        sendError('Method not allowed', 405);
    }
} catch(PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
?>
