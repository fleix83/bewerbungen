<?php
// Virtual doorbell relay: forwards a "someone is ringing" push to ntfy.
// config.php sets JSON + CORS headers, short-circuits OPTIONS, and defines
// sendJSON()/sendError(). It also loads config.local.php (NTFY_TOPIC).
require_once 'config.php';

// --- Method guard -----------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    sendError('Method not allowed', 405);
}

// --- Server config ----------------------------------------------------------
$placeholder = 'klingel-CHANGE-ME-to-a-long-random-string';
if (!defined('NTFY_TOPIC') || NTFY_TOPIC === '' || NTFY_TOPIC === $placeholder) {
    sendError('Doorbell not configured (NTFY_TOPIC missing).', 500);
}
$server = defined('NTFY_SERVER') && NTFY_SERVER !== '' ? rtrim(NTFY_SERVER, '/') : 'https://ntfy.sh';

// --- Rate limiting (file-based, best-effort) --------------------------------
// Use an app-local cache dir: on some hosts (e.g. XAMPP on macOS, where Apache
// runs as the 'daemon' user) sys_get_temp_dir() is NOT writable by the web
// server user, which would silently disable throttling. Keeping the cache
// beside this script keeps it writable wherever PHP can write its own folder.
$dir = __DIR__ . '/doorbell-cache';
if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
}
$ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$now = time();

// per-IP: reject if the same IP successfully rang within the last 10 seconds
$ipFile = $dir . '/doorbell_ip_' . md5($ip);
$last   = @file_get_contents($ipFile);
if ($last !== false && is_numeric($last) && ($now - (int)$last) < 10) {
    sendError('Bitte einen Moment warten.', 429);
}

// global: reject if more than 60 rings happened in the last rolling hour
$globalFile = $dir . '/doorbell_global.json';
$window     = [];
$raw        = @file_get_contents($globalFile);
if ($raw !== false) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $window = array_values(array_filter($decoded, fn($t) => is_numeric($t) && ($now - (int)$t) < 3600));
    }
}
if (count($window) >= 60) {
    sendError('Zu viele Klingelversuche. Bitte später erneut.', 429);
}

// --- Build + send the ntfy notification -------------------------------------
$body    = 'Jemand klingelt an der Tür (' . date('H:i') . ')';
$headers = ['Title: Klingel', 'Priority: 5', 'Tags: bell'];
$url     = $server . '/' . rawurlencode(NTFY_TOPIC);

$ok = false;
if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
    ]);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $ok = ($resp !== false && $code >= 200 && $code < 300);
} else {
    $ctx = stream_context_create(['http' => [
        'method'        => 'POST',
        'header'        => implode("\r\n", $headers),
        'content'       => $body,
        'timeout'       => 5,
        'ignore_errors' => true,
    ]]);
    $resp = @file_get_contents($url, false, $ctx);
    $ok   = ($resp !== false);
}

if (!$ok) {
    sendError('Benachrichtigung fehlgeschlagen.', 502);
}

// --- Record success for rate limiting ---------------------------------------
@file_put_contents($ipFile, (string) $now, LOCK_EX);
$window[] = $now;
@file_put_contents($globalFile, json_encode($window), LOCK_EX);

sendJSON(['status' => 'ok']);
