<?php
// app/controllers/ReadingController.php
require_once BASE_PATH . '/app/models/Reading.php';
require_once BASE_PATH . '/app/models/Story.php';

class ReadingController {
    private $readingModel;
    private $storyModel;

    public function __construct() {
        $this->readingModel = new Reading();
        $this->storyModel = new Story();
    }

        public function childDashboard() {
        $userId = $_SESSION['user_id'];
        $readings = $this->readingModel->getByUser($userId);
        $completed = $this->readingModel->countCompleted($userId);
        $recommended = $this->storyModel->getPublished();
        
        // MUST USE BASE_PATH HERE
        require_once BASE_PATH . '/app/views/child/dashboard.php'; 
    }
}
?>