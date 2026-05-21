<?php
require_once 'models/User.php';
require_once 'models/Area.php';

class ProfileController {
    private $db;
    private $userModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->userModel = new User($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        $areaModel = new Area($this->db);
        $governorates = $areaModel->getGovernorates();
        $areas = [];
        if ($user && !empty($user['governorate'])) {
            $areas = $areaModel->getAreasByGovernorate($user['governorate']);
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['update_profile'])) {
                $full_name = trim($_POST['full_name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $governorate = $_POST['governorate'];
                $area = trim($_POST['area']);
                $street = trim($_POST['street_address']);

                if (empty($full_name) || empty($email)) {
                    $error = "الاسم والبريد الإلكتروني مطلوبان";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "البريد الإلكتروني غير صحيح";
                } else {
                    if ($this->userModel->updateProfile($userId, $full_name, $email, $phone, $governorate, $area, $street)) {
                        $success = "تم تحديث البيانات بنجاح";
                        $_SESSION['name'] = $full_name;
                        $user = $this->userModel->getUserById($userId);
                    } else {
                        $error = "حدث خطأ أثناء التحديث";
                    }
                }
            } elseif (isset($_POST['update_password'])) {
                $current = $_POST['current_password'];
                $new = $_POST['new_password'];
                $confirm = $_POST['confirm_password'];

                $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $row = $stmt->fetch();
                if (!password_verify($current, $row['password'])) {
                    $error = "كلمة المرور الحالية غير صحيحة";
                } elseif (strlen($new) < 6) {
                    $error = "كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل";
                } elseif ($new !== $confirm) {
                    $error = "كلمة المرور الجديدة غير متطابقة";
                } else {
                    if ($this->userModel->updatePassword($userId, $new)) {
                        $success = "تم تحديث كلمة المرور بنجاح";
                    } else {
                        $error = "حدث خطأ أثناء تحديث كلمة المرور";
                    }
                }
            }
        }

        include 'views/profile/index.php';
    }
}
?>
