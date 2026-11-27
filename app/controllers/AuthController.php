<?php
// app/controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=register');
            }

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? '';

            $errors = [];

            if (empty($username)) $errors[] = "Username is required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
            if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
            if ($password !== $confirm) $errors[] = "Passwords do not match.";
            if (!in_array($role, ['writer', 'child', 'parent'])) $errors[] = "Invalid role.";

            if ($this->userModel->findByEmail($email)) {
                $errors[] = "Email is already registered.";
            }

            if (empty($errors)) {
                if ($this->userModel->create($username, $email, $password, $role)) {
                    setFlash('success', 'Registration successful! Please log in.');
                    redirect(BASE_URL . '/index.php?page=login');
                } else {
                    setFlash('error', 'Registration failed. Please try again.');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function login() {
        if (isLoggedIn()) {
            redirect(BASE_URL . '/index.php?page=dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=login');
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                setFlash('success', 'Welcome back, ' . $user['username'] . '!');
                redirect(BASE_URL . '/index.php?page=dashboard');
            } else {
                setFlash('error', 'Invalid email or password.');
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

        public function logout() {
        // Clear all session data
        $_SESSION = array();

        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Start a new session just to hold the flash message
        session_start();
        setFlash('success', 'You have been logged out successfully.');

        // Redirect to HOMEPAGE instead of login
        redirect(BASE_URL . '/index.php'); 
    }
}
?>