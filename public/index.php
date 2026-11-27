<?php
// public/index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Define the base path
define('BASE_PATH', dirname(__DIR__));

// 2. Load core dependencies
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/includes/csrf.php';
require_once BASE_PATH . '/app/includes/auth.php';
require_once BASE_PATH . '/app/includes/functions.php';

// 3. Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 4. Route to the appropriate controller
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
        $role = strtolower(trim((string) ($_SESSION['role'] ?? '')));
        if ($role === 'admin') {
            require_once BASE_PATH . '/app/controllers/AdminController.php';
            $admin = new AdminController();
            $admin->dashboard();
        } elseif ($role === 'writer') {
            require_once BASE_PATH . '/app/controllers/StoryController.php';
            $story = new StoryController();
            $story->writerDashboard();
        } elseif ($role === 'child') {
            require_once BASE_PATH . '/app/controllers/ReadingController.php';
            $reading = new ReadingController();
            $reading->childDashboard();
        } elseif ($role === 'parent') {
            require_once BASE_PATH . '/app/controllers/ParentController.php';
            $parent = new ParentController();
            $parent->dashboard();
        } else {
            http_response_code(403);
            echo 'Access Denied: account role is not recognized.';
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

    case 'admin-users':
        requireRole('admin');
        redirect(BASE_URL . '/index.php?page=admin-dashboard#users');

    case 'admin-stories':
        requireRole('admin');
        redirect(BASE_URL . '/index.php?page=admin-dashboard#stories');

    case 'admin-categories':
        requireRole('admin');
        redirect(BASE_URL . '/index.php?page=manage-categories');

    case 'admin-user-role':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->updateUserRole();
        break;

    case 'admin-user-create':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->createUser();
        break;

    case 'admin-user-edit':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->editUser();
        break;

    case 'admin-user-delete':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->deleteUser();
        break;

    case 'admin-story-create':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->createStory();
        break;

    case 'admin-story-edit':
        requireRole('admin');
        require_once BASE_PATH . '/app/controllers/AdminController.php';
        $admin = new AdminController();
        $admin->editStory();
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

    // ===== API ROUTES =====
    case 'api-link-child':
        require_once BASE_PATH . '/api/link-child.php';
        break;

    case 'api-stories-search':
        require_once BASE_PATH . '/api/stories-search.php';
        break;

    case 'api-reading-progress':
        require_once BASE_PATH . '/api/reading-progress.php';
        break;

    // ===== SUPPORT & INFO ROUTES =====
    case 'help':
        require_once BASE_PATH . '/app/views/help.php';
        break;
        
    case 'contact':
        require_once BASE_PATH . '/app/views/contact.php';
        break;
        
    case 'privacy':
        require_once BASE_PATH . '/app/views/privacy.php';
        break;
        
    case 'terms':
        require_once BASE_PATH . '/app/views/terms.php';
        break;
        
    case 'faq':
        require_once BASE_PATH . '/app/views/faq.php';
        break;

    default:
        header("HTTP/1.1 404 Not Found");
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
?>