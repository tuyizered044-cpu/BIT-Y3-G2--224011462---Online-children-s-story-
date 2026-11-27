<?php
// app/controllers/StoryController.php
require_once BASE_PATH . '/app/models/Story.php';
require_once BASE_PATH . '/app/models/Category.php';

class StoryController {
    private $storyModel;
    private $categoryModel;

    public function __construct() {
        $this->storyModel = new Story();
        $this->categoryModel = new Category();
    }

    public function index() {
        // Parents ARE allowed to view the library catalog. 
        // No blocking here.
        
        $filters = [
            'category' => $_GET['category'] ?? '',
            'age_group' => $_GET['age_group'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $stories = $this->storyModel->getPublished($filters);
        $categories = $this->categoryModel->getAll();
        
        require_once BASE_PATH . '/app/views/stories/index.php';
    }

    public function show() {
        // Parents are NOT allowed to read the actual story.
        if (isLoggedIn() && $_SESSION['role'] === 'parent') {
            setFlash('error', 'Parents cannot read stories. Please log in as a child to read.');
            redirect(BASE_URL . '/index.php?page=stories');
        }

        $id = $_GET['id'] ?? 0;
        $story = $this->storyModel->findById($id);
        
        $isAdmin = isLoggedIn() && strtolower(trim((string) ($_SESSION['role'] ?? ''))) === 'admin';
        if (!$story || ($story['status'] !== 'published' && !($isAdmin || (isLoggedIn() && (int) $_SESSION['user_id'] === (int) $story['author_id'])))) {
            setFlash('error', 'Story not found or not published.');
            redirect(BASE_URL . '/index.php?page=stories');
        }
        
        require_once BASE_PATH . '/app/views/stories/show.php';
    }

    public function writerDashboard() {
        $authorId = $_SESSION['user_id'];
        $stories = $this->storyModel->getByAuthor($authorId);
        $total = count($stories);
        $published = $this->storyModel->countByStatus($authorId, 'published');
        $drafts = $this->storyModel->countByStatus($authorId, 'draft');
        
        require_once BASE_PATH . '/app/views/writer/dashboard.php';
    }

        public function create() {
        $categories = $this->categoryModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=create-story');
            }
            
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $categoryId = $_POST['category_id'] ?? '';
            $ageGroup = $_POST['age_group'] ?? '';
            $status = $_POST['status'] ?? 'draft';
            
            // Handle Thumbnail Upload
            $thumbnail = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->storyModel->uploadThumbnail($_FILES['thumbnail']);
                if ($uploadResult === false) {
                    setFlash('error', 'Invalid image. Max size 5MB, JPG/PNG/WEBP only.');
                    require_once BASE_PATH . '/app/views/stories/create.php';
                    return;
                }
                $thumbnail = $uploadResult;
            }

            if (empty($title) || empty($body) || empty($categoryId) || empty($ageGroup)) {
                setFlash('error', 'All fields are required.');
            } else {
                if ($this->storyModel->create($_SESSION['user_id'], $categoryId, $title, $thumbnail, $body, $ageGroup, $status)) {
                    setFlash('success', 'Story created successfully.');
                    redirect(BASE_URL . '/index.php?page=dashboard');
                } else {
                    setFlash('error', 'Failed to create story.');
                }
            }
        }
        
        require_once BASE_PATH . '/app/views/stories/create.php';
    }

    // Apply similar logic inside edit()

     public function edit() {
        $id = $_GET['id'] ?? 0;
        $story = $this->storyModel->findById($id);
        
        // Security check: Does the story exist, and does the writer own it?
        if (!$story || $story['author_id'] != $_SESSION['user_id']) {
            setFlash('error', 'You do not have permission to edit this story.');
            redirect(BASE_URL . '/index.php?page=dashboard');
        }
        
        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=edit-story&id=' . $id);
            }
            
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $categoryId = $_POST['category_id'] ?? '';
            $ageGroup = $_POST['age_group'] ?? '';
            $status = $_POST['status'] ?? 'draft';
            
            // Handle new thumbnail upload
            $thumbnail = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->storyModel->uploadThumbnail($_FILES['thumbnail']);
                if ($uploadResult === false) {
                    setFlash('error', 'Invalid image. Max size 5MB, JPG/PNG/WEBP only.');
                    require_once BASE_PATH . '/app/views/stories/edit.php';
                    return;
                }
                $thumbnail = $uploadResult;
            }

            if (empty($title) || empty($body) || empty($categoryId) || empty($ageGroup)) {
                setFlash('error', 'All fields are required.');
            } else {
                // The model handles keeping the old thumbnail if a new one wasn't uploaded
                if ($this->storyModel->update($id, $_SESSION['user_id'], $categoryId, $title, $thumbnail, $body, $ageGroup, $status)) {
                    setFlash('success', 'Story updated successfully.');
                    redirect(BASE_URL . '/index.php?page=dashboard');
                } else {
                    setFlash('error', 'Failed to update story.');
                }
            }
        }
        
        require_once BASE_PATH . '/app/views/stories/edit.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        // The Model securely checks that author_id matches $_SESSION['user_id']
        if ($this->storyModel->delete($id, $_SESSION['user_id'])) {
            setFlash('success', 'Story deleted successfully.');
        } else {
            setFlash('error', 'Failed to delete story or unauthorized.');
        }
        redirect(BASE_URL . '/index.php?page=dashboard');
    }

    public function publish() {
        $id = $_GET['id'] ?? 0;
        if ($this->storyModel->publish($id, $_SESSION['user_id'])) {
            setFlash('success', 'Story published successfully.');
        } else {
            setFlash('error', 'Failed to publish story or unauthorized.');
        }
        redirect(BASE_URL . '/index.php?page=dashboard');
    }
}
?>