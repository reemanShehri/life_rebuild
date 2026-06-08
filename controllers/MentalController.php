<?php
require_once 'models/Mental.php';

class MentalController {
    private $db;
    private $mentalModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->mentalModel = new Mental($pdo);
    }


    public function index() {
    $centers = $this->mentalModel->getAllCenters();
    $specialists = $this->mentalModel->getAllSpecialists();
    $articles = $this->mentalModel->getAllArticles(4);
    $quickServices = $this->mentalModel->getQuickServices();
    $dailyTip = $this->mentalModel->getDailyTip();

    $upcomingAppointments = [];
    $allAppointments = []; // <-- جديدة
    if (isset($_SESSION['user_id'])) {
        $upcomingAppointments = $this->mentalModel->getUpcomingAppointments($_SESSION['user_id']);
        $allAppointments = $this->mentalModel->getUserAppointments($_SESSION['user_id']); // <-- كل المواعيد
    }

    include 'views/mental/index.php';
}



public function cancelAppointment() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'غير مسجل دخول']);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $appointmentId = $_POST['appointment_id'] ?? 0;
        if (!$appointmentId) {
            echo json_encode(['error' => 'معرّف الموعد غير صالح']);
            return;
        }

        $result = $this->mentalModel->cancelAppointment($appointmentId, $_SESSION['user_id']);
        echo json_encode(['success' => $result]);
    }
}



    // public function index() {
    //     // استدعاء جميع الدوال اللازمة
    //     $centers = $this->mentalModel->getAllCenters();
    //     $specialists = $this->mentalModel->getAllSpecialists();
    //     $articles = $this->mentalModel->getAllArticles(4);
    //     $quickServices = $this->mentalModel->getQuickServices();
    //     $dailyTip = $this->mentalModel->getDailyTip();

    //     $upcomingAppointments = [];
    //     if (isset($_SESSION['user_id'])) {
    //         $upcomingAppointments = $this->mentalModel->getUpcomingAppointments($_SESSION['user_id']);
    //     }

    //     // تمرير جميع المتغيرات إلى view
    //     include 'views/mental/index.php';
    // }

    // معالجة حجز جديد (عبر AJAX)
    public function bookAppointment() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'يجب تسجيل الدخول أولاً']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $specialistId = $_POST['specialist_id'] ?? 0;
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $method = $_POST['method'] ?? 'video';

            $result = $this->mentalModel->addAppointment($_SESSION['user_id'], $specialistId, $date, $time, $method);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'حدث خطأ أثناء الحجز']);
            }
        }
    }

    // معالجة إلغاء حجز

}
?>
