<?php
// app/models/Category.php
require_once __DIR__ . '/../../config/database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    public function update($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ? WHERE category_id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = ?");
        return $stmt->execute([$id]);
    }

    public function hasStories($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM stories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>