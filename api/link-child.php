<?php
// api/link-child.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/includes/auth.php';
require_once __DIR__ . '/../app/includes/csrf.php';
require_once __DIR__ . '/../app/includes/functions.php';
require_once __DIR__ . '/../app/models/ParentChild.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'parent') {
    setFlash('error', 'Unauthorized access.');
    redirect(BASE_URL . '/index.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Invalid request method.');
    redirect(BASE_URL . '/index.php?page=dashboard');
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    setFlash('error', 'Invalid CSRF token.');
    redirect(BASE_URL . '/index.php?page=dashboard');
}

$childId = (int)($_POST['child_id'] ?? 0);
if ($childId <= 0) {
    setFlash('error', 'Invalid child selection.');
    redirect(BASE_URL . '/index.php?page=dashboard');
}

$pcModel = new ParentChild();
$pcModel->link($_SESSION['user_id'], $childId);

setFlash('success', 'Child account linked successfully.');
redirect(BASE_URL . '/index.php?page=dashboard');
?>