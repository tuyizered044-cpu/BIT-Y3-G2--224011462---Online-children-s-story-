<?php
// api/reading-progress.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/includes/auth.php';
require_once __DIR__ . '/../app/includes/csrf.php';
require_once __DIR__ . '/../app/models/Reading.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'child') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!verifyCSRFToken($input['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$storyId = (int)($input['story_id'] ?? 0);
$progress = (int)($input['progress'] ?? 0);

if ($storyId <= 0 || $progress < 0 || $progress > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$readingModel = new Reading();
$readingModel->saveProgress($_SESSION['user_id'], $storyId, $progress);

echo json_encode(['success' => true]);
?>