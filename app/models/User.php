<?php
// app/models/User.php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($username, $email, $password, $role) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hash, $role]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateProfile($id, $username, $email) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        return $stmt->execute([$username, $email, $id]);
    }

    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function getAllChildren() {
        $stmt = $this->db->query("SELECT * FROM users WHERE role = 'child'");
        return $stmt->fetchAll();
    }

    // ===== ADMIN METHODS =====
    
    // Your controller calls $this->userModel->getAll();
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Your controller calls $this->userModel->countAll();
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    // Your controller calls $this->userModel->updateRole();
    public function updateRole($userId, $role) {
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        return $stmt->execute([$role, $userId]);
    }

    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeUserId !== null) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function updateAdminUser($userId, $username, $email, $role, $password = '') {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, role = ?, password_hash = ? WHERE user_id = ?");
            return $stmt->execute([$username, $email, $role, $hash, $userId]);
        }

        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
        return $stmt->execute([$username, $email, $role, $userId]);
    }

    // Your controller calls $this->userModel->delete();
    public function delete($userId) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function countByRole($role) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchColumn();
    }
}
?>