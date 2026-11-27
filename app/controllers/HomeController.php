<?php
// app/controllers/HomeController.php
require_once __DIR__ . '/../models/Story.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController {
    public function index() {
        $storyModel = new Story();
        $categoryModel = new Category();
        $featuredStories = $storyModel->getFeatured(3);
        $categories = array_slice($categoryModel->getAll(), 0, 4);

        require_once __DIR__ . '/../views/home.php';
    }
}
?>
