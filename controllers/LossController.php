<?php
require_once 'models/Loss.php';

class LossController {
    private $db;
    private $lossModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->lossModel = new Loss($pdo);
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $losses = $this->lossModel->getUserLosses($userId);
        include 'views/losses/index.php';
    }

    public function add() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = $_POST['document_type'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $lossDate = !empty($_POST['loss_date']) ? $_POST['loss_date'] : null;
            $userId = $_SESSION['user_id'];

            // رفع الملف
            $targetDir = "uploads/losses/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['document_file']['name']);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

            if (!in_array($fileType, $allowedTypes)) {
                $error = "الملف غير مدعوم. يرجى رفع صورة أو PDF.";
            } elseif ($_FILES['document_file']['size'] > 5 * 1024 * 1024) {
                $error = "حجم الملف كبير جدًا (حد أقصى 5 ميجابايت).";
            } elseif (move_uploaded_file($_FILES['document_file']['tmp_name'], $targetFile)) {
                if ($this->lossModel->addLoss($userId, $type, $title, $description, $targetFile, $lossDate)) {
                    $success = "تم توثيق الخسارة بنجاح.";
                } else {
                    $error = "حدث خطأ في حفظ البيانات.";
                }
            } else {
                $error = "فشل رفع الملف.";
            }
        }

        include 'views/losses/add.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->lossModel->deleteLoss($_GET['id'], $_SESSION['user_id']);
        }
        header('Location: index.php?page=losses');
        exit;
    }
}
?>
