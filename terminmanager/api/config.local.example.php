<?php
// Copy this file to config.local.php and fill in real credentials.
// config.local.php is gitignored — do NOT commit it.

define('DB_HOST', 'localhost');
define('DB_USER', 'luftgaessli');
define('DB_PASS', 'your-password-here');
define('DB_NAME', 'luftgaessli');

// Virtual doorbell (ntfy push). Keep NTFY_TOPIC SECRET — anyone who knows it
// can publish to / subscribe to your alerts. Generate one with:
//   echo "klingel-$(openssl rand -hex 16)"
define('NTFY_TOPIC', 'klingel-CHANGE-ME-to-a-long-random-string');
define('NTFY_SERVER', 'https://ntfy.sh'); // optional; default is https://ntfy.sh
