<?php
require_once 'config.php';
require_once 'email.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            sendError('Invalid JSON input');
        }

        $customer = $input['customer'] ?? [];
        $eventDate = $input['event_date'] ?? '';
        $startSlot = intval($input['start_slot'] ?? 0);
        $endSlot = intval($input['end_slot'] ?? 0);
        $serviceIds = $input['service_ids'] ?? [];
        $notes = $input['notes'] ?? '';
        $serviceType = $input['service_type'] ?? '';

        if (empty($customer['first_name']) || empty($customer['last_name']) || empty($customer['email'])) {
            sendError('Missing required customer fields');
        }

        // Allow empty serviceIds only for "Etwas anderes" (notes-only booking)
        $isEtwasAnderes = $serviceType === 'Etwas anderes';
        if (empty($eventDate) || (empty($serviceIds) && !$isEtwasAnderes)) {
            sendError('Missing required booking fields');
        }

        $conn->beginTransaction();

        try {
            // Check if customer exists by email
            $stmt = $conn->prepare("SELECT id FROM customers WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $customer['email']]);
            $existingCustomer = $stmt->fetch();

            if ($existingCustomer) {
                $customerId = $existingCustomer['id'];
            } else {
                // Create new customer
                $customerNumber = 'CUST' . date('Ymd') . rand(1000, 9999);
                $stmt = $conn->prepare("
                    INSERT INTO customers (customer_number, first_name, last_name, email, phone)
                    VALUES (:customer_number, :first_name, :last_name, :email, :phone)
                ");
                $stmt->execute([
                    'customer_number' => $customerNumber,
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'email' => $customer['email'],
                    'phone' => $customer['phone'] ?? ''
                ]);
                $customerId = $conn->lastInsertId();
            }

            // Create event
            $stmt = $conn->prepare("
                INSERT INTO events (
                    user_id,
                    customer_id,
                    event_type_id,
                    event_date,
                    start_slot,
                    end_slot,
                    status,
                    notes
                ) VALUES (
                    1,
                    :customer_id,
                    1,
                    :event_date,
                    :start_slot,
                    :end_slot,
                    'pending',
                    :notes
                )
            ");
            $stmt->execute([
                'customer_id' => $customerId,
                'event_date' => $eventDate,
                'start_slot' => $startSlot,
                'end_slot' => $endSlot,
                'notes' => $notes
            ]);
            $eventId = $conn->lastInsertId();

            // Create bookings for each service
            foreach ($serviceIds as $serviceId) {
                // Get service price
                $stmt = $conn->prepare("SELECT price FROM services WHERE id = :service_id");
                $stmt->execute(['service_id' => $serviceId]);
                $service = $stmt->fetch();

                if (!$service) {
                    throw new Exception("Service not found: $serviceId");
                }

                $stmt = $conn->prepare("
                    INSERT INTO bookings (event_id, service_id, price_at_booking)
                    VALUES (:event_id, :service_id, :price_at_booking)
                ");
                $stmt->execute([
                    'event_id' => $eventId,
                    'service_id' => $serviceId,
                    'price_at_booking' => $service['price']
                ]);
            }

            $conn->commit();

            // Prepare email data
            $emailData = prepareBookingEmail($conn, $eventId, $customer, $eventDate, $startSlot, $endSlot, $serviceIds, $notes, $serviceType);

            // Send confirmation email
            $emailSent = sendBookingConfirmation($emailData);

            sendJSON([
                'success' => true,
                'event_id' => $eventId,
                'message' => 'Booking created successfully',
                'email_sent' => $emailSent
            ]);
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }

    } elseif ($method === 'GET') {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            sendError('Booking ID required');
        }

        $stmt = $conn->prepare("
            SELECT * FROM v_booking_details
            WHERE event_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            sendError('Booking not found', 404);
        }

        sendJSON($booking);

    } else {
        sendError('Method not allowed', 405);
    }
} catch(PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}

function prepareBookingEmail($conn, $eventId, $customer, $eventDate, $startSlot, $endSlot, $serviceIds, $notes, $serviceType) {
    // Get service details
    $services = [];
    $total = 0;

    // Use serviceType directly - if "Etwas anderes" or empty, show notes content
    $serviceTypeName = $serviceType ?: 'Etwas anderes';
    if ($serviceTypeName === 'Etwas anderes' && !empty($notes)) {
        $serviceTypeName = strlen($notes) > 50 ? substr($notes, 0, 50) . '...' : $notes;
    }

    foreach ($serviceIds as $serviceId) {
        $stmt = $conn->prepare("SELECT name, price FROM services WHERE id = :id");
        $stmt->execute(['id' => $serviceId]);
        $service = $stmt->fetch();

        if ($service) {
            // If service is "Etwas anderes" (ID 3), use the actual selection from dropdown
            $serviceName = $service['name'];
            if ($serviceId == 3) {
                $serviceName = $serviceTypeName;
            }

            $services[] = [
                'name' => $serviceName,
                'price' => floatval($service['price'])
            ];
            $total += floatval($service['price']);
        }
    }

    // Format date in German
    $dateObj = new DateTime($eventDate);
    $dayOfWeek = (int)$dateObj->format('w');
    $month = (int)$dateObj->format('n');

    $germanDays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
    $germanMonths = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

    $formattedDate = $germanDays[$dayOfWeek] . ', ' . $dateObj->format('d') . '. ' . $germanMonths[$month] . ' ' . $dateObj->format('Y');

    // Format time
    $timeString = sprintf('%02d:00 - %02d:00', $startSlot, $endSlot);

    return [
        'customer_email' => $customer['email'],
        'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
        'date' => $formattedDate,
        'time' => $timeString,
        'services' => $services,
        'total' => $total,
        'notes' => $notes
    ];
}
?>
