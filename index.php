<?php
// index.php (Root level)

// Define the absolute base path of the project
define('BASE_PATH', __DIR__);

// Load core dependencies using absolute paths
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/includes/csrf.php';
require_once BASE_PATH . '/app/includes/auth.php';
require_once BASE_PATH . '/app/includes/functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        require_once BASE_PATH . '/app/views/home.php';
        break;

    case 'login':
        require_once BASE_PATH . '/app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->login();
        break;

    case 'register':
        require_once BASE_PATH . '/app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->register();
        break;

    case 'logout':
        require_once BASE_PATH . '/app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        break;

    case 'stories':
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->index();
        break;

    case 'story':
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->show();
        break;

    case 'dashboard':
        requireLogin();
        if ($_SESSION['role'] === 'admin') {
            redirect(BASE_URL . '/index.php?page=admin-dashboard');
        } elseif ($_SESSION['role'] === 'writer') {
            require_once BASE_PATH . '/app/controllers/StoryController.php';
            $story = new StoryController();
            $story->writerDashboard();
        } elseif ($_SESSION['role'] === 'child') {
            require_once BASE_PATH . '/app/controllers/ReadingController.php';
            $reading = new ReadingController();
            $reading->childDashboard();
        } elseif ($_SESSION['role'] === 'parent') {
            require_once BASE_PATH . '/app/controllers/ParentController.php';
            $parent = new ParentController();
            $parent->dashboard();
        }
        break;

    case 'create-story':
        requireRole('writer');
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->create();
        break;

    case 'edit-story':
        requireRole('writer');
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->edit();
        break;

    case 'delete-story':
        requireRole('writer');
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->delete();
        break;

    case 'publish-story':
        requireRole('writer');
        require_once BASE_PATH . '/app/controllers/StoryController.php';
        $story = new StoryController();
        $story->publish();
        break;

    case 'categories':
        require_once BASE_PATH . '/app/controllers/CategoryController.php';
        $cat = new CategoryController();
        $cat->index();
        break;

    case 'manage-categories':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/CategoryController.php';
        $cat = new CategoryController();
        $cat->manage();
        break;

    case 'create-category':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/CategoryController.php';
        $cat = new CategoryController();
        $cat->create();
        break;

    case 'edit-category':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/CategoryController.php';
        $cat = new CategoryController();
        $cat->edit();
        break;

    case 'delete-category':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/CategoryController.php';
        $cat = new CategoryController();
        $cat->delete();
        break;

    case 'profile':
        requireLogin();
        require_once BASE_PATH . '/app/controllers/UserController.php';
        $user = new UserController();
        $user->profile();
        break;

    case 'about':
        require_once BASE_PATH . '/app/views/about.php';
        break;

    // ===== ADMIN ROUTES =====
    case 'admin-dashboard':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->dashboard();
        break;

    case 'admin-user-role':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->updateRole();
        break;

    case 'admin-user-delete':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->deleteUser();
        break;

    case 'admin-story-status':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->updateStoryStatus();
        break;

    case 'admin-story-delete':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->deleteStory();
        break;

    default:
        header("HTTP/1.1 404 Not Found");
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
?>