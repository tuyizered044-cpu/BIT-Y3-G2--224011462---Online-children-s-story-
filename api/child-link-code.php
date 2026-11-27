<?php
// api/child-link-code.php
require_once __DIR__ . '/../app/models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    setFlash('error', 'Invalid request. Please try again.');
    redirect(BASE_URL . '/index.php?page=dashboard');
}

$code = bin2hex(random_bytes(16));
$expiresAt = date('Y-m-d H:i:s', time() + 900);

try {
    if ((new User())->setChildLinkCode($_SESSION['user_id'], hash('sha256', $code), $expiresAt)) {
        setFlash('success', 'Share this one-time code with your parent within 15 minutes: ' . $code);
    } else {
        setFlash('error', 'A link code could not be created.');
    }
} catch (PDOException $exception) {
    error_log('Child link code generation failed: ' . $exception->getMessage());
    setFlash('error', 'A link code could not be created. Please try again.');
}

redirect(BASE_URL . '/index.php?page=dashboard');
