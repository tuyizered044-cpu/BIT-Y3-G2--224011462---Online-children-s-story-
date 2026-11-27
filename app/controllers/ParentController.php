<?php
// app/controllers/ParentController.php
require_once BASE_PATH . '/app/models/ParentChild.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Reading.php';

class ParentController {
    private $parentChildModel;
    private $userModel;
    private $readingModel;

    public function __construct() {
        $this->parentChildModel = new ParentChild();
        $this->userModel = new User();
        $this->readingModel = new Reading();
    }

        public function dashboard() {
        $parentId = $_SESSION['user_id'];
        $children = $this->parentChildModel->getChildren($parentId);
        
        $selectedChildId = $_GET['child_id'] ?? ($children[0]['user_id'] ?? null);
        $readings = [];
        $completed = 0;

        if ($selectedChildId && $this->parentChildModel->isLinked($parentId, $selectedChildId)) {
            $readings = $this->readingModel->getByChild($selectedChildId);
            $completed = $this->readingModel->countCompleted($selectedChildId);
        }

        $allChildren = $this->userModel->getAllChildren();
        
        // MUST USE BASE_PATH HERE
        require_once BASE_PATH . '/app/views/parent/dashboard.php';
    }
}
?>