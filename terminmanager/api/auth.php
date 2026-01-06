<?php
require_once 'config.php';

// Start session
session_start();

// Admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Basel2026');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        // Login
        $input = json_decode(file_get_contents('php://input'), true);

        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            $_SESSION['admin_authenticated'] = true;
            $_SESSION['admin_username'] = $username;

            sendJSON([
                'success' => true,
                'message' => 'Login successful'
            ]);
        } else {
            sendError('Invalid credentials', 401);
        }

    } elseif ($method === 'GET') {
        // Check auth status
        $isAuthenticated = isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;

        sendJSON([
            'authenticated' => $isAuthenticated,
            'username' => $isAuthenticated ? $_SESSION['admin_username'] : null
        ]);

    } elseif ($method === 'DELETE') {
        // Logout
        session_destroy();

        sendJSON([
            'success' => true,
            'message' => 'Logged out'
        ]);

    } else {
        sendError('Method not allowed', 405);
    }
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
?>
