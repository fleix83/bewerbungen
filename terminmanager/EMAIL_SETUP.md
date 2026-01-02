# Email Setup Instructions

## Configuration

The email system is configured to send confirmation emails when customers book appointments.

### Step 1: Set SMTP Password

Edit `api/email.php` and set the SMTP password:

```php
define('SMTP_PASSWORD', 'YOUR_PASSWORD_HERE');
```

Replace `YOUR_PASSWORD_HERE` with the actual password for service@bewerbungenundmehr.ch

### Step 2: Install PHPMailer (Recommended)

For reliable email sending with SMTP authentication, install PHPMailer:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/bewerbungen/terminmanager/api
composer require phpmailer/phpmailer
```

If you don't have Composer installed, you can download PHPMailer manually:
1. Download from https://github.com/PHPMailer/PHPMailer/releases
2. Extract to `api/vendor/phpmailer/phpmailer`

### Alternative: Using PHP's mail() function

If PHPMailer is not available, the system will fall back to PHP's native `mail()` function.

For XAMPP on macOS, you need to configure `php.ini`:

1. Open `/Applications/XAMPP/xamppfiles/etc/php.ini`
2. Find the `[mail function]` section
3. Configure SMTP settings:

```ini
[mail function]
SMTP = bewerbungenundmehr.ch
smtp_port = 465
sendmail_from = service@bewerbungenundmehr.ch
```

Note: PHP's mail() function has limited SMTP authentication support. PHPMailer is strongly recommended for production use.

### Step 3: Test Email Sending

After configuration, test by creating a booking through the application. Check:
1. Customer receives confirmation email
2. Email is properly formatted in German
3. All booking details are included

## Email Content

The confirmation email includes:
- Customer name
- Appointment date and time
- Address (Luftgässlein 3, Basel)
- Booked services with prices
- Total amount
- Customer's notes (if any)
- Important information about what to bring
- Contact information (WhatsApp & phone)

## Troubleshooting

### Email not sending
- Check SMTP password is correct
- Verify SMTP server settings
- Check PHP error logs: `/Applications/XAMPP/xamppfiles/logs/php_error_log`
- Ensure port 465 is not blocked by firewall

### Wrong character encoding
- Verify email.php has `$mail->CharSet = 'UTF-8';` (PHPMailer)
- Check that German characters display correctly

### Server configuration
- Server: bewerbungenundmehr.ch
- Port: 465 (SMTP with SSL)
- Username: service@bewerbungenundmehr.ch
- Authentication: Required
