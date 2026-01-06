<?php
// Email configuration
define('SMTP_HOST', 'bewerbungenundmehr.ch');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'service@bewerbungenundmehr.ch');
define('SMTP_PASSWORD', 'Basel2026!!!');
define('SMTP_FROM', 'service@bewerbungenundmehr.ch');
define('SMTP_FROM_NAME', 'Bewerbungen & Mehr');

// Load PHPMailer autoloader
$autoloaderPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloaderPath)) {
    require_once $autoloaderPath;
}

function sendBookingConfirmation($bookingData) {
    $to = $bookingData['customer_email'];
    $customerName = $bookingData['customer_name'];
    $date = $bookingData['date'];
    $time = $bookingData['time'];
    $services = $bookingData['services'];
    $total = $bookingData['total'];
    $notes = $bookingData['notes'] ?? '';

    $subject = 'Terminbestätigung - Bewerbungen & Mehr';

    // Build services list
    $servicesList = '';
    foreach ($services as $service) {
        $servicesList .= $service['name'] . ' - CHF ' . number_format($service['price'], 2) . "\n";
    }

    // Email body
    $message = "Guten Tag " . $customerName . ",\n\n";
    $message .= "Vielen Dank für Ihre Buchung. Hiermit bestätigen wir Ihren Termin:\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "TERMIN\n";
    $message .= $date . "\n";
    $message .= $time . " Uhr\n\n";
    $message .= "ADRESSE\n";
    $message .= "Luftgässlein 3\n";
    $message .= "Basel\n";
    $message .= "1. Stock\n\n";
    $message .= "IHRE BUCHUNG\n";
    $message .= $servicesList;
    $message .= "\nTotal: CHF " . number_format($total, 2) . "\n\n";

    if (!empty($notes)) {
        $message .= "IHRE ANMERKUNG\n";
        $message .= $notes . "\n\n";
    }

    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "WICHTIG\n";
    $message .= "Bitte bringen Sie alle vorhandenen Unterlagen mit: Zeugnisse, Lebenslauf (falls vorhanden), bereits vorhandene Dokumente...\n\n";
    $message .= "Bei Fragen kontaktieren Sie mich. Ich freue mich auf Sie.\n\n";
    $message .= "KONTAKT\n";
    $message .= "WhatsApp: https://wa.me/41767576052\n";
    $message .= "Telefon: +41 76 757 60 52\n\n";
    $message .= "Mit freundlichen Grüssen\n";
    $message .= "Bewerbungen & Mehr\n";

    // Use PHPMailer if available, otherwise use mail()
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        error_log("Email: Using PHPMailer to send to: " . $to);
        return sendWithPHPMailer($to, $subject, $message);
    } else {
        error_log("Email: PHPMailer not found, using mail() to send to: " . $to);
        return sendWithMailFunction($to, $subject, $message);
    }
}

function sendWithPHPMailer($to, $subject, $message) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        error_log("Email: Successfully sent to " . $to);
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        error_log("Email error details: " . $e->getMessage());
        return false;
    }
}

function sendWithMailFunction($to, $subject, $message) {
    $headers = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">\r\n";
    $headers .= "Reply-To: " . SMTP_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return mail($to, $subject, $message, $headers);
}
