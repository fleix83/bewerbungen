<?php
require_once 'config.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $stmt = $conn->prepare("
                SELECT
                    c.id,
                    c.customer_number,
                    c.first_name,
                    c.last_name,
                    c.email,
                    c.phone,
                    c.created_at
                FROM customers c
                WHERE c.id = :id
            ");
            $stmt->execute(['id' => $id]);
            $customer = $stmt->fetch();

            if (!$customer) {
                sendError('Customer not found', 404);
            }

            $stmt = $conn->prepare("
                SELECT
                    e.id as event_id,
                    e.event_date,
                    e.start_slot,
                    e.end_slot,
                    e.status,
                    GROUP_CONCAT(s.name SEPARATOR ', ') as services
                FROM events e
                LEFT JOIN bookings b ON e.id = b.event_id
                LEFT JOIN services s ON b.service_id = s.id
                WHERE e.customer_id = :customer_id
                GROUP BY e.id
                ORDER BY e.event_date DESC
            ");
            $stmt->execute(['customer_id' => $id]);
            $customer['bookings'] = $stmt->fetchAll();

            sendJSON($customer);

        } else {
            $sortBy = $_GET['sort_by'] ?? 'created_at';
            $sortOrder = $_GET['sort_order'] ?? 'DESC';

            $allowedSortBy = ['last_name', 'first_name', 'created_at'];
            $allowedSortOrder = ['ASC', 'DESC'];

            if (!in_array($sortBy, $allowedSortBy)) {
                $sortBy = 'created_at';
            }
            if (!in_array($sortOrder, $allowedSortOrder)) {
                $sortOrder = 'DESC';
            }

            $stmt = $conn->prepare("
                SELECT
                    id,
                    customer_number,
                    first_name,
                    last_name,
                    email,
                    created_at
                FROM customers
                ORDER BY {$sortBy} {$sortOrder}
            ");
            $stmt->execute();
            $customers = $stmt->fetchAll();

            sendJSON($customers);
        }
    } else {
        sendError('Method not allowed', 405);
    }
} catch(PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
?>
