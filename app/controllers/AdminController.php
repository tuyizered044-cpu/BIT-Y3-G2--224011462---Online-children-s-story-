<?php
// app/controllers/AdminController.php
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Story.php';
require_once BASE_PATH . '/app/models/Category.php';

class AdminController {
    private $userModel;
    private $storyModel;
    private $categoryModel;

    public function __construct() {
        $this->userModel = new User();
        $this->storyModel = new Story();
        $this->categoryModel = new Category();
    }

    public function dashboard() {
        $users = $this->userModel->getAll();
        $stories = $this->storyModel->getAllForAdmin();
        $categories = $this->categoryModel->getAll();
        
        $stats = [
            'users' => $this->userModel->countAll(),
            'stories' => $this->storyModel->countAll(),
            'published' => $this->storyModel->countByStatusAdmin('published'),
            'categories' => count($categories),
        ];
        
        require_once BASE_PATH . '/app/views/admin/dashboard.php';
    }

    private function validPost() {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '');
    }

    private function validRole($role) {
        return in_array($role, ['admin', 'writer', 'child', 'parent'], true);
    }

    private function storyInputIsValid($input) {
        $categoryId = filter_var($input['category_id'] ?? null, FILTER_VALIDATE_INT);
        return is_string($input['title'] ?? null)
            && trim($input['title']) !== ''
            && strlen(trim($input['title'])) <= 150
            && is_string($input['body'] ?? null)
            && trim($input['body']) !== ''
            && $categoryId !== false
            && $categoryId > 0
            && $this->categoryModel->findById($categoryId)
            && in_array($input['age_group'] ?? '', ['3-5', '6-8', '9-12', '13+'], true)
            && in_array($input['status'] ?? '', ['draft', 'published'], true);
    }

    private function uploadedThumbnail() {
        if (!isset($_FILES['thumbnail'])) {
            return null;
        }
        if (($_FILES['thumbnail']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($_FILES['thumbnail']['error'] ?? null) !== UPLOAD_ERR_OK) {
            return false;
        }
        return $this->storyModel->uploadThumbnail($_FILES['thumbnail']);
    }

    public function updateUserRole() {
        if (!$this->validPost()) {
            setFlash('error', 'Invalid request.');
        } else {
            $id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT) ?: 0;
            $role = $_POST['role'] ?? '';
            $user = $id > 0 ? $this->userModel->findById($id) : false;
            if (!$user || !$this->validRole($role)) {
                setFlash('error', 'Invalid user or role.');
            } elseif ($id === (int) $_SESSION['user_id']) {
                setFlash('error', 'You cannot change your own role.');
            } elseif ($user['role'] === 'admin' && $role !== 'admin' && (int) $this->userModel->countByRole('admin') <= 1) {
                setFlash('error', 'You cannot remove the last administrator.');
            } elseif ($this->userModel->updateRole($id, $role)) {
                setFlash('success', 'User role updated successfully.');
            } else {
                setFlash('error', 'Could not update the user role.');
            }
        }
        redirect(BASE_URL . '/index.php?page=admin-dashboard');
    }

    public function deleteUser() {
        if (!$this->validPost()) {
            setFlash('error', 'Invalid request.');
        } else {
            $id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT) ?: 0;
            $user = $id > 0 ? $this->userModel->findById($id) : false;
            if ($id === (int) $_SESSION['user_id']) {
                setFlash('error', 'You cannot delete your own account.');
            } elseif (!$user) {
                setFlash('error', 'User not found.');
            } elseif ($user['role'] === 'admin' && (int) $this->userModel->countByRole('admin') <= 1) {
                setFlash('error', 'You cannot delete the last administrator.');
            } elseif ($this->userModel->delete($id)) {
                setFlash('success', 'User deleted successfully.');
            } else {
                setFlash('error', 'Could not delete the user.');
            }
        }
        redirect(BASE_URL . '/index.php?page=admin-dashboard');
    }

    public function createUser() {
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
            $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $role = $_POST['role'] ?? '';

            if (!$this->validPost()) {
                setFlash('error', 'Invalid request.');
            } elseif ($username === '' || strlen($username) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 || strlen($password) < 6 || !$this->validRole($role)) {
                setFlash('error', 'Enter a valid username, email, password (at least 6 characters), and role.');
            } elseif ($this->userModel->emailExists($email)) {
                setFlash('error', 'That email address is already in use.');
            } elseif ($this->userModel->create($username, $email, $password, $role)) {
                setFlash('success', 'User created successfully.');
                redirect(BASE_URL . '/index.php?page=admin-dashboard#users');
            } else {
                setFlash('error', 'Could not create the user.');
            }
        }

        $user = null;
        require BASE_PATH . '/app/views/admin/user-form.php';
    }

    public function editUser() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
        $user = $id > 0 ? $this->userModel->findById($id) : false;
        if (!$user) {
            http_response_code(404);
            echo 'User not found.';
            return;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
            $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $role = $id === (int) $_SESSION['user_id'] ? $user['role'] : ($_POST['role'] ?? '');

            if (!$this->validPost()) {
                setFlash('error', 'Invalid request.');
            } elseif ($username === '' || strlen($username) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 || ($password !== '' && strlen($password) < 6) || !$this->validRole($role)) {
                setFlash('error', 'Enter a valid username and email. A new password must be at least 6 characters.');
            } elseif ($this->userModel->emailExists($email, $id)) {
                setFlash('error', 'That email address is already in use.');
            } elseif ($user['role'] === 'admin' && $role !== 'admin' && (int) $this->userModel->countByRole('admin') <= 1) {
                setFlash('error', 'You cannot remove the last administrator.');
            } elseif ($this->userModel->updateAdminUser($id, $username, $email, $role, $password)) {
                setFlash('success', 'User updated successfully.');
                redirect(BASE_URL . '/index.php?page=admin-dashboard#users');
            } else {
                setFlash('error', 'Could not update the user.');
            }
        }

        require BASE_PATH . '/app/views/admin/user-form.php';
    }

    public function createStory() {
        $categories = $this->categoryModel->getAll();
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            if (!$this->validPost()) {
                setFlash('error', 'Invalid request.');
            } elseif (!$this->storyInputIsValid($_POST)) {
                setFlash('error', 'Enter a title, content, valid category, age group, and status.');
            } else {
                $thumbnail = $this->uploadedThumbnail();
                if ($thumbnail === false) {
                    setFlash('error', 'Invalid image upload. Use JPG, PNG, or WEBP up to 5MB.');
                } elseif ($this->storyModel->create(
                    (int) $_SESSION['user_id'],
                    (int) $_POST['category_id'],
                    trim($_POST['title']),
                    $thumbnail,
                    trim($_POST['body']),
                    $_POST['age_group'],
                    $_POST['status']
                )) {
                    setFlash('success', 'Story created successfully.');
                    redirect(BASE_URL . '/index.php?page=admin-dashboard#stories');
                } else {
                    setFlash('error', 'Could not create the story.');
                }
            }
        }

        $story = null;
        require BASE_PATH . '/app/views/admin/story-form.php';
    }

    public function editStory() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
        $story = $id > 0 ? $this->storyModel->findById($id) : false;
        if (!$story) {
            http_response_code(404);
            echo 'Story not found.';
            return;
        }

        $categories = $this->categoryModel->getAll();
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            if (!$this->validPost()) {
                setFlash('error', 'Invalid request.');
            } elseif (!$this->storyInputIsValid($_POST)) {
                setFlash('error', 'Enter a title, content, valid category, age group, and status.');
            } else {
                $thumbnail = $this->uploadedThumbnail();
                if ($thumbnail === false) {
                    setFlash('error', 'Invalid image upload. Use JPG, PNG, or WEBP up to 5MB.');
                } elseif ($this->storyModel->updateByAdmin(
                    $id,
                    (int) $_POST['category_id'],
                    trim($_POST['title']),
                    $thumbnail,
                    trim($_POST['body']),
                    $_POST['age_group'],
                    $_POST['status']
                )) {
                    setFlash('success', 'Story updated successfully.');
                    redirect(BASE_URL . '/index.php?page=admin-dashboard#stories');
                } else {
                    setFlash('error', 'Could not update the story.');
                }
            }
        }

        require BASE_PATH . '/app/views/admin/story-form.php';
    }

    public function updateStoryStatus() {
        if (!$this->validPost()) {
            setFlash('error', 'Invalid request.');
        } else {
            $id = filter_input(INPUT_POST, 'story_id', FILTER_VALIDATE_INT) ?: 0;
            $status = $_POST['status'] ?? '';
            if ($id > 0 && $this->storyModel->setStatusByAdmin($id, $status)) {
                setFlash('success', 'Story status updated successfully.');
            } else {
                setFlash('error', 'Could not update the story status.');
            }
        }
        redirect(BASE_URL . '/index.php?page=admin-dashboard');
    }

    public function deleteStory() {
        if (!$this->validPost()) {
            setFlash('error', 'Invalid request.');
        } else {
            $id = filter_input(INPUT_POST, 'story_id', FILTER_VALIDATE_INT) ?: 0;
            if ($id > 0 && $this->storyModel->findById($id) && $this->storyModel->deleteByAdmin($id)) {
                setFlash('success', 'Story deleted successfully.');
            } else {
                setFlash('error', 'Could not delete the story.');
            }
        }
        redirect(BASE_URL . '/index.php?page=admin-dashboard');
    }
}
?>