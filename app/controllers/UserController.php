<?php
// app/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION = [];
            session_regenerate_id(true);
            setFlash('error', 'Your account could not be found. Please sign in again.');
            redirect(BASE_URL . '/index.php?page=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=profile');
            }

            if (isset($_POST['update_profile'])) {
                $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
                $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
                $existingUser = $this->userModel->findByEmail($email);

                if ($username === '' || strlen($username) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
                    setFlash('error', 'Enter a username and valid email within the allowed lengths.');
                } elseif ($existingUser && (int) $existingUser['user_id'] !== (int) $userId) {
                    setFlash('error', 'That email address is already in use.');
                } elseif ($this->userModel->updateProfile($userId, $username, $email)) {
                    $_SESSION['username'] = $username;
                    setFlash('success', 'Profile updated successfully.');
                } else {
                    setFlash('error', 'Profile could not be updated.');
                }
            } elseif (isset($_POST['update_password'])) {
                $current = is_string($_POST['current_password'] ?? null) ? $_POST['current_password'] : '';
                $new = is_string($_POST['new_password'] ?? null) ? $_POST['new_password'] : '';
                $confirm = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';

                if (!password_verify($current, $user['password_hash'])) {
                    setFlash('error', 'Current password is incorrect.');
                } elseif (strlen($new) < 6) {
                    setFlash('error', 'New password must be at least 6 characters.');
                } elseif ($new !== $confirm) {
                    setFlash('error', 'New passwords do not match.');
                } elseif ($this->userModel->updatePassword($userId, $new)) {
                    setFlash('success', 'Password updated successfully.');
                } else {
                    setFlash('error', 'Password could not be updated.');
                }
            } else {
                setFlash('error', 'Invalid profile request.');
            }
            redirect(BASE_URL . '/index.php?page=profile');
        }

        require_once __DIR__ . '/../views/users/profile.php';
    }
}
?>