<?php
require_once 'models/Volunteer.php';

class VolunteerController {
    private $db;
    private $volModel;
    public function __construct($pdo) {
        $this->db = $pdo;
        $this->volModel = new Volunteer($pdo);
    }
    public function index() {
        $organizations = $this->volModel->getAllOrganizations();
        $requests = []; // يمكن عرض طلبات المستخدم هنا لاحقاً
        include 'views/volunteer/index.php';
    }

    public function myRequests() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $requests = $this->volModel->getUserRequests($userId);

        include 'views/volunteer/my_requests.php';
    }



    public function request() {
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = $_POST['request_type'];
            $details = trim($_POST['details']);
            $contactMethod = $_POST['preferred_contact'];
            $contactValue = trim($_POST['contact_info']);
            $userId = $_SESSION['user_id'];
            if ($this->volModel->addRequest($userId, $type, $details, $contactMethod, $contactValue)) {
                $success = "تم إرسال طلب المساعدة بنجاح. ستقوم المنظمات بالتواصل معك قريبًا.";
            } else {
                $error = "حدث خطأ، حاول مرة أخرى.";
            }
        }
        $organizations = $this->volModel->getAllOrganizations();
        include 'views/volunteer/request.php';
    }
}
?>
