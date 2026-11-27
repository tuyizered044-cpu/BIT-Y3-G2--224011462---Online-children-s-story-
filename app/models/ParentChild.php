<?php
// app/models/ParentChild.php
require_once __DIR__ . '/../../config/database.php';

class ParentChild {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function link($parentId, $childId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO parent_child (parent_id, child_id) VALUES (?, ?)");
        return $stmt->execute([$parentId, $childId]);
    }

    public function getChildren($parentId) {
        $stmt = $this->db->prepare("SELECT u.* FROM users u JOIN parent_child pc ON u.user_id = pc.child_id WHERE pc.parent_id = ?");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    public function isLinked($parentId, $childId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM parent_child WHERE parent_id = ? AND child_id = ?");
        $stmt->execute([$parentId, $childId]);
        return $stmt->fetchColumn() > 0;
    }
}
?>