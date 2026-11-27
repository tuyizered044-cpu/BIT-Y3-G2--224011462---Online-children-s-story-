<?php
// app/models/Story.php
require_once __DIR__ . '/../../config/database.php';

class Story {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Handles secure file upload
    public function uploadThumbnail($file) {
        if (!isset($file['error']) || is_array($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return false;
        }

        $uploadDir = BASE_PATH . '/public/uploads/stories/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('story_') . '.' . strtolower($ext);
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }
        return false;
    }

    public function create($authorId, $categoryId, $title, $thumbnail, $body, $ageGroup, $status) {
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->db->prepare("INSERT INTO stories (author_id, category_id, title, thumbnail, body, age_group, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$authorId, $categoryId, $title, $thumbnail, $body, $ageGroup, $status, $publishedAt]);
    }

    public function getByAuthor($authorId) {
        $stmt = $this->db->prepare("SELECT s.*, c.name as category FROM stories s JOIN categories c ON s.category_id = c.category_id WHERE s.author_id = ? ORDER BY s.created_at DESC");
        $stmt->execute([$authorId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT s.*, u.username as author, c.name as category FROM stories s JOIN users u ON s.author_id = u.user_id JOIN categories c ON s.category_id = c.category_id WHERE s.story_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $authorId, $categoryId, $title, $thumbnail, $body, $ageGroup, $status) {
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;
        
        if ($thumbnail) {
            $stmt = $this->db->prepare("UPDATE stories SET category_id = ?, title = ?, thumbnail = ?, body = ?, age_group = ?, status = ?, published_at = COALESCE(published_at, ?) WHERE story_id = ? AND author_id = ?");
            return $stmt->execute([$categoryId, $title, $thumbnail, $body, $ageGroup, $status, $publishedAt, $id, $authorId]);
        } else {
            $stmt = $this->db->prepare("UPDATE stories SET category_id = ?, title = ?, body = ?, age_group = ?, status = ?, published_at = COALESCE(published_at, ?) WHERE story_id = ? AND author_id = ?");
            return $stmt->execute([$categoryId, $title, $body, $ageGroup, $status, $publishedAt, $id, $authorId]);
        }
    }

    public function delete($id, $authorId) {
        $stmt = $this->db->prepare("DELETE FROM stories WHERE story_id = ? AND author_id = ?");
        return $stmt->execute([$id, $authorId]);
    }

    public function publish($id, $authorId) {
        $stmt = $this->db->prepare("UPDATE stories SET status = 'published', published_at = NOW() WHERE story_id = ? AND author_id = ?");
        return $stmt->execute([$id, $authorId]);
    }

    public function getPublished($filters = []) {
        $sql = "SELECT s.*, u.username as author, c.name as category FROM stories s JOIN users u ON s.author_id = u.user_id JOIN categories c ON s.category_id = c.category_id WHERE s.status = 'published'";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND s.category_id = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['age_group'])) {
            $sql .= " AND s.age_group = ?";
            $params[] = $filters['age_group'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND s.title LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY s.published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countByStatus($authorId, $status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM stories WHERE author_id = ? AND status = ?");
        $stmt->execute([$authorId, $status]);
        return $stmt->fetchColumn();
    }

    // ===== ADMIN METHODS =====

    // Your controller calls $this->storyModel->getAllForAdmin();
    public function getAllForAdmin() {
        $stmt = $this->db->query("SELECT s.*, u.username as author, c.name as category FROM stories s JOIN users u ON s.author_id = u.user_id JOIN categories c ON s.category_id = c.category_id ORDER BY s.created_at DESC");
        return $stmt->fetchAll();
    }

    // Your controller calls $this->storyModel->countAll();
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM stories");
        return $stmt->fetchColumn();
    }

    // Your controller calls $this->storyModel->countByStatusAdmin('published');
    public function countByStatusAdmin($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM stories WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }

    // Your controller calls $this->storyModel->setStatusByAdmin($id, $status);
    public function setStatusByAdmin($id, $status) {
        if (!in_array($status, ['draft', 'published'], true)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE stories SET status = ?, published_at = (CASE WHEN ? = 'published' THEN COALESCE(published_at, NOW()) ELSE NULL END) WHERE story_id = ?");
        return $stmt->execute([$status, $status, $id]);
    }

    public function updateByAdmin($id, $categoryId, $title, $thumbnail, $body, $ageGroup, $status) {
        $publishedAt = $status === 'published' ? date('Y-m-d H:i:s') : null;

        if ($thumbnail !== null) {
            $stmt = $this->db->prepare("UPDATE stories SET category_id = ?, title = ?, thumbnail = ?, body = ?, age_group = ?, status = ?, published_at = (CASE WHEN ? = 'published' THEN COALESCE(published_at, ?) ELSE NULL END) WHERE story_id = ?");
            return $stmt->execute([$categoryId, $title, $thumbnail, $body, $ageGroup, $status, $status, $publishedAt, $id]);
        }

        $stmt = $this->db->prepare("UPDATE stories SET category_id = ?, title = ?, body = ?, age_group = ?, status = ?, published_at = (CASE WHEN ? = 'published' THEN COALESCE(published_at, ?) ELSE NULL END) WHERE story_id = ?");
        return $stmt->execute([$categoryId, $title, $body, $ageGroup, $status, $status, $publishedAt, $id]);
    }

    // Your controller calls $this->storyModel->deleteByAdmin($id);
    public function deleteByAdmin($id) {
        $stmt = $this->db->prepare("DELETE FROM stories WHERE story_id = ?");
        return $stmt->execute([$id]);
    }
}
?>