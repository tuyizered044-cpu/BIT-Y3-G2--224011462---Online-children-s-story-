<?php
// app/models/Reading.php
require_once __DIR__ . '/../../config/database.php';

class Reading {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getByUserAndStory($userId, $storyId) {
        $stmt = $this->db->prepare("SELECT * FROM readings WHERE user_id = ? AND story_id = ?");
        $stmt->execute([$userId, $storyId]);
        return $stmt->fetch();
    }

    public function saveProgress($userId, $storyId, $progress) {
        $stmt = $this->db->prepare(
            "INSERT INTO readings (user_id, story_id, progress_pct, completed_at)
             VALUES (?, ?, ?, IF(? >= 100, CURRENT_TIMESTAMP, NULL))
             ON DUPLICATE KEY UPDATE
                 progress_pct = GREATEST(progress_pct, VALUES(progress_pct)),
                 completed_at = IF(VALUES(progress_pct) >= 100, COALESCE(completed_at, CURRENT_TIMESTAMP), completed_at)"
        );
        return $stmt->execute([$userId, $storyId, $progress, $progress]);
    }

    public function getByUser($userId) {
        $stmt = $this->db->prepare("SELECT r.*, s.title, s.age_group, c.name as category FROM readings r JOIN stories s ON r.story_id = s.story_id JOIN categories c ON s.category_id = c.category_id WHERE r.user_id = ? ORDER BY r.read_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getByChild($childId) {
        return $this->getByUser($childId);
    }

    public function countCompleted($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM readings WHERE user_id = ? AND progress_pct >= 100");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
?>