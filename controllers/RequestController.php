<?php
require_once 'models/AidRequest.php';

class RequestController {
    private $db;
    private $requestModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->requestModel = new AidRequest($pdo);
    }

   public function dashboard() {
    $userId = $_SESSION['user_id'];

    // جلب عدد الطلبات
    $requestModel = new AidRequest($this->db);
    $allRequests = $requestModel->getByUser($userId);
    $totalRequests = count($allRequests);
    $pendingRequests = count(array_filter($allRequests, function($r) {
        return $r['status'] == 'pending';
    }));
    $completedRequests = count(array_filter($allRequests, function($r) {
        return $r['status'] == 'completed';
    }));

    // جلب بيانات خارطة الطريق (نسبة الإنجاز)
    require_once 'models/Roadmap.php';
    $roadmap = new Roadmap($this->db);
    $percentage = $roadmap->getCompletionPercentage($userId);
    $tasks = $roadmap->getUserTasks($userId);
    $totalTasks = count($tasks);
    $completedTasks = 0;
    foreach ($tasks as $task) {
        if ($task['status'] == 'completed') $completedTasks++;
    }

    // جلب آخر 3 طلبات حديثة
    $recentRequests = array_slice($allRequests, 0, 3);

    // تمرير البيانات إلى الـ view
    include 'views/dashboard/family.php';
}

   public function create() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $contactPhone = $_POST['contact_phone'] ?? '';
        $userId = $_SESSION['user_id'];

        $this->requestModel->create($userId, $type, $description, $contactPhone);
        header('Location: index.php?page=my_requests');
        exit;
    }
    include 'views/requests/create.php';
}

    public function myRequests() {
        $userId = $_SESSION['user_id'];
        $requests = $this->requestModel->getByUser($userId);
        include 'views/requests/my_requests.php';
    }
}
?>
