<?php
// api/stories-search.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/includes/auth.php';

// Block Parents from searching (Optional, based on previous requirement)
if (isLoggedIn() && $_SESSION['role'] === 'parent') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Parents do not have access to search stories.']);
    exit;
}

try {
    $db = getDB();
    $search = $_GET['search'] ?? '';
    $ageGroup = $_GET['age_group'] ?? '';
    $categoryId = $_GET['category'] ?? '';

    $sql = "SELECT s.story_id, s.title, u.username as author, c.name as category, s.age_group 
            FROM stories s 
            JOIN users u ON s.author_id = u.user_id 
            JOIN categories c ON s.category_id = c.category_id 
            WHERE s.status = 'published'";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND s.title LIKE ?";
        $params[] = '%' . $search . '%';
    }
    if (!empty($ageGroup)) {
        $sql .= " AND s.age_group = ?";
        $params[] = $ageGroup;
    }
    if (!empty($categoryId)) {
        $sql .= " AND s.category_id = ?";
        $params[] = $categoryId;
    }

    $sql .= " ORDER BY s.published_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $stories = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $stories]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>