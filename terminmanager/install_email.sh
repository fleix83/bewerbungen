#!/bin/bash

echo "Installing PHPMailer for email functionality..."
echo ""

cd api

# Check if composer is installed
if ! command -v composer &> /dev/null
then
    echo "Composer not found. Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar require phpmailer/phpmailer
else
    echo "Composer found. Installing PHPMailer..."
    composer require phpmailer/phpmailer
fi

echo ""
echo "✓ PHPMailer installed successfully!"
echo ""
echo "Next steps:"
echo "1. Edit api/email.php and set your SMTP password"
echo "2. Test by creating a booking in the application"
echo ""
echo "See EMAIL_SETUP.md for detailed instructions."
