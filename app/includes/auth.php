<?php
// app/includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    $currentRole = $_SESSION['role'] ?? '';
    if (!is_string($currentRole) || strtolower(trim($currentRole)) !== $role) {
        header("HTTP/1.1 403 Forbidden");
        echo "Access Denied.";
        exit;
    }
}
?>