<?php
require_once 'models/Roadmap.php';
require_once 'models/User.php';
require_once 'models/Area.php';

class RoadmapController {
    private $db;
    private $roadmapModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->roadmapModel = new Roadmap($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // جلب بيانات المستخدم
        $userModel = new User($this->db);
        $user = $userModel->getUserById($userId);  // لازم تتأكد من وجود هذي الدالة في User.php

        // جلب تفاصيل المنطقة
        // $areaDetails = null;
        // if ($user && !empty($user['governorate']) && !empty($user['area'])) {
        //     $areaModel = new Area($this->db);
        //     $areaDetails = $areaModel->getAreaDetailsByName($user['area'], $user['governorate']);
        // }

        $areaDetails = null;
if ($user && !empty($user['area']) && !empty($user['governorate'])) {
    $areaModel = new Area($this->db);
    $areaDetails = $areaModel->getAreaDetailsByGovId($user['area'], $user['governorate']);
}

        // جلب مهام الخارطة
        $tasks = $this->roadmapModel->getUserTasks($userId);
        $percentage = $this->roadmapModel->getCompletionPercentage($userId);
        $categoryStats = $this->roadmapModel->getStatsByCategory($userId);

        // تحديث حالة المهمة
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['status'])) {
            $this->roadmapModel->updateTaskStatus($_POST['task_id'], $_POST['status']);
            header('Location: index.php?page=roadmap');
            exit;
        }

        include 'views/roadmap/index.php';
    }
}
?>
