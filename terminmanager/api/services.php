<?php
require_once 'config.php';

$conn = getDBConnection();

try {
    $stmt = $conn->prepare("
        SELECT id, name, duration_slots, price
        FROM services
        WHERE active = 1 AND is_public = 1
        ORDER BY sort_order, name
    ");

    $stmt->execute();
    $services = $stmt->fetchAll();

    sendJSON($services);
} catch(PDOException $e) {
    sendError('Failed to fetch services: ' . $e->getMessage(), 500);
}
?>
