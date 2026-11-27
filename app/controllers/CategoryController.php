<?php
// app/controllers/CategoryController.php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/categories/index.php';
    }

    public function manage() {
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/categories/create.php'; // View contains both list and create form
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=manage-categories');
            }
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                setFlash('error', 'Category name is required.');
            } else {
                if ($this->categoryModel->create($name, $description)) {
                    setFlash('success', 'Category created successfully.');
                } else {
                    setFlash('error', 'Failed to create category. It might already exist.');
                }
            }
        }
        redirect(BASE_URL . '/index.php?page=manage-categories');
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            setFlash('error', 'Category not found.');
            redirect(BASE_URL . '/index.php?page=manage-categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token.');
                redirect(BASE_URL . '/index.php?page=edit-category&id=' . $id);
            }
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                setFlash('error', 'Category name is required.');
            } else {
                if ($this->categoryModel->update($id, $name, $description)) {
                    setFlash('success', 'Category updated successfully.');
                    redirect(BASE_URL . '/index.php?page=manage-categories');
                } else {
                    setFlash('error', 'Failed to update category.');
                }
            }
        }
        require_once __DIR__ . '/../views/categories/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Invalid request.');
            redirect(BASE_URL . '/index.php?page=manage-categories');
        }

        $id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT) ?: 0;
        if ($id < 1) {
            setFlash('error', 'Category not found.');
        } elseif ($this->categoryModel->hasStories($id)) {
            setFlash('error', 'Cannot delete category because it contains stories.');
        } else {
            if ($this->categoryModel->findById($id) && $this->categoryModel->delete($id)) {
                setFlash('success', 'Category deleted successfully.');
            } else {
                setFlash('error', 'Category not found.');
            }
        }
        redirect(BASE_URL . '/index.php?page=manage-categories');
    }
}
?>