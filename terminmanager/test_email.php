<?php
require_once 'api/email.php';

// Test email data
$testBookingData = [
    'customer_email' => 'YOUR_TEST_EMAIL@example.com', // Change this to your email
    'customer_name' => 'Test Customer',
    'date' => 'Montag, 30. Dezember 2024',
    'time' => '14:00 - 15:00',
    'services' => [
        ['name' => 'Lebenslauf', 'price' => 30.00],
        ['name' => 'Brief', 'price' => 30.00]
    ],
    'total' => 60.00,
    'notes' => 'Dies ist eine Test-Anmerkung.'
];

echo "Testing email configuration...\n";
echo "Sending to: " . $testBookingData['customer_email'] . "\n\n";

$result = sendBookingConfirmation($testBookingData);

if ($result) {
    echo "✓ Email sent successfully!\n";
    echo "Check your inbox at: " . $testBookingData['customer_email'] . "\n";
} else {
    echo "✗ Email sending failed!\n";
    echo "Check the error log for details.\n";
    echo "Error log location: /Applications/XAMPP/xamppfiles/logs/php_error_log\n";
}

echo "\nIf using PHPMailer, check for detailed error messages above.\n";
?>
