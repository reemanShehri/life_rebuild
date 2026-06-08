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




//   public function request() {
//     $error = '';
//     $success = '';
//     $myRequests = [];

//     $userId = $_SESSION['user_id'];

//     // جلب طلبات المستخدم السابقة
//     $myRequests = $this->volModel->getUserRequests($userId);

//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//         $type = $_POST['request_type'];
//         $details = trim($_POST['details']);

//         // التعامل مع حقل الاتصال الجديد (contact_phone)
//         $contactMethod = 'phone'; // أو يمكن جعله اختياريًا
//         $contactValue = trim($_POST['contact_phone'] ?? '');

//         if (empty($contactValue)) {
//             $error = "رقم التواصل مطلوب";
//         } else {
//             if ($this->volModel->addRequest($userId, $type, $details, $contactMethod, $contactValue)) {
//                 $success = "تم إرسال طلب المساعدة بنجاح. ستقوم المنظمات بالتواصل معك قريبًا.";
//                 // تحديث القائمة بعد الإضافة
//                 $myRequests = $this->volModel->getUserRequests($userId);
//             } else {
//                 $error = "حدث خطأ، حاول مرة أخرى.";
//             }
//         }
//     }

//     $organizations = $this->volModel->getAllOrganizations();
//     include 'views/volunteer/request.php';
// }


public function request() {
    $error = '';
    $success = '';
    $myRequests = [];

    $userId = $_SESSION['user_id'];
    $myRequests = $this->volModel->getUserRequests($userId);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $type = $_POST['request_type'];
        $details = trim($_POST['details']);
        $contactMethod = $_POST['preferred_contact'] ?? 'phone';
        $contactValue = trim($_POST['contact_phone'] ?? ''); // هنا التغيير

        if (empty($contactValue)) {
            $error = "رقم التواصل مطلوب";
        } else {
            if ($this->volModel->addRequest($userId, $type, $details, $contactMethod, $contactValue)) {
                $success = "تم إرسال طلب المساعدة بنجاح.";
                $myRequests = $this->volModel->getUserRequests($userId);
            } else {
                $error = "حدث خطأ، حاول مرة أخرى.";
            }
        }
    }

    $organizations = $this->volModel->getAllOrganizations();
    include 'views/volunteer/request.php';
}


public function cancelRequest() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id'])) {
        $requestId = (int)$_POST['request_id'];
        $userId = $_SESSION['user_id'];

        // التحقق أن الطلب يخص المستخدم الحالي
        $stmt = $this->db->prepare("SELECT id FROM volunteer_requests WHERE id = ? AND user_id = ?");
        $stmt->execute([$requestId, $userId]);
        if ($stmt->fetch()) {
            // حذف الطلب أو تحديث حالته (يفضل تحديث الحالة بدلاً من الحذف)
            $update = $this->db->prepare("UPDATE volunteer_requests SET status = 'cancelled' WHERE id = ?");
            $update->execute([$requestId]);
            $_SESSION['success_msg'] = "تم إلغاء الطلب بنجاح";
        } else {
            $_SESSION['error_msg'] = "لا يمكنك إلغاء هذا الطلب";
        }
    }
    header('Location: index.php?page=volunteer_request');
    exit;
}



    // public function request() {
    //     $error = '';
    //     $success = '';
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $type = $_POST['request_type'];
    //         $details = trim($_POST['details']);
    //         $contactMethod = $_POST['preferred_contact'];
    //         $contactValue = trim($_POST['contact_info']);
    //         $userId = $_SESSION['user_id'];
    //         if ($this->volModel->addRequest($userId, $type, $details, $contactMethod, $contactValue)) {
    //             $success = "تم إرسال طلب المساعدة بنجاح. ستقوم المنظمات بالتواصل معك قريبًا.";
    //         } else {
    //             $error = "حدث خطأ، حاول مرة أخرى.";
    //         }
    //     }
    //     $organizations = $this->volModel->getAllOrganizations();
    //     include 'views/volunteer/request.php';
    // }
}
?>
