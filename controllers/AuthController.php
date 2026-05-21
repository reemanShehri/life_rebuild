<?php
require_once 'models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->ensureUsersTable(); // إنشاء الجدول إذا لم يكن موجودًا
        $this->userModel = new User($pdo);
    }

      // دوال مساعدة لجلب البيانات
    private function getGovernorates() {
        require_once 'models/Area.php';
        $areaModel = new Area($this->db);
        return $areaModel->getGovernorates();
    }

    private function getAreasByGovernorate($govId) {
        require_once 'models/Area.php';
        $areaModel = new Area($this->db);
        return $areaModel->getAreasByGovernorate($govId);
    }




    private function ensureUsersTable() {
        // إنشاء الجدول إذا لم يكن موجودًا
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            phone VARCHAR(20),
            password VARCHAR(255) NOT NULL,
            role ENUM('family', 'admin', 'organization') DEFAULT 'family',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);

        // التحقق من وجود عمود full_name (للتأكد)
        try {
            $stmt = $this->db->prepare("SELECT full_name FROM users LIMIT 1");
            $stmt->execute();
        } catch (PDOException $e) {
            // إذا كان الخطأ بسبب عدم وجود العمود، نضيفه
            if (strpos($e->getMessage(), 'full_name') !== false) {
                $this->db->exec("ALTER TABLE users ADD COLUMN full_name VARCHAR(100) NOT NULL AFTER id");
            }
        }
    }



     public function register() {
        $error = '';
        $success = '';

        // جلب المحافظات لعرضها في القائمة
        $governorates = $this->getGovernorates();
        $areas = []; // سيتم ملؤها إذا تم اختيار محافظة

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $governorate_id = $_POST['governorate'] ?? '';
            $area = trim($_POST['area'] ?? '');
            $street = trim($_POST['street_address'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            // إذا تم اختيار محافظة، نجلب مناطقها لعرضها بعد إعادة التحميل
            if (!empty($governorate_id)) {
                $areas = $this->getAreasByGovernorate($governorate_id);
            }

            $errors = [];
            if (empty($name)) $errors[] = "الاسم الكامل مطلوب";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "البريد الإلكتروني غير صحيح";
            if (strlen($password) < 6) $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل";
            if ($password !== $confirm) $errors[] = "كلمة المرور غير متطابقة";
            if (empty($governorate_id)) $errors[] = "اختر المحافظة";
            if (empty($area)) $errors[] = "اختر المنطقة";
            if (empty($street)) $errors[] = "أدخل عنوان الشارع";

            if (empty($errors)) {
                // التحقق من عدم وجود البريد
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $errors[] = "البريد الإلكتروني موجود مسبقًا";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $this->db->prepare("INSERT INTO users (full_name, email, phone, governorate, area, street_address, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'family')");
                    if ($stmt->execute([$name, $email, $phone, $governorate_id, $area, $street, $hashed])) {
                        // إنشاء مهام خارطة الطريق للمستخدم الجديد
                        require_once 'models/Roadmap.php';
                        $roadmap = new Roadmap($this->db);
                        $userId = $this->db->lastInsertId();
                        $roadmap->initDefaultTasks($userId);

                        $success = "تم إنشاء الحساب بنجاح، يمكنك <a href='index.php?page=login'>تسجيل الدخول</a>";
                    } else {
                        $errors[] = "حدث خطأ أثناء إنشاء الحساب";
                    }
                }
            }
            if (!empty($errors)) $error = implode("<br>", $errors);
        }

        // تمرير المتغيرات إلى view
        include 'views/auth/register.php';
    }

    

//  public function register() {
//     $error = '';
//     $success = '';

//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//         $name = trim($_POST['name'] ?? '');
//         $email = trim($_POST['email'] ?? '');
//         $phone = trim($_POST['phone'] ?? '');
//         $governorate = trim($_POST['governorate'] ?? '');
//         $area = trim($_POST['area'] ?? '');
//         $street = trim($_POST['street_address'] ?? '');
//         $password = $_POST['password'] ?? '';
//         $confirm = $_POST['confirm_password'] ?? '';

//         $errors = [];
//         if (empty($name)) $errors[] = "الاسم الكامل مطلوب";
//         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "البريد الإلكتروني غير صحيح";
//         if (strlen($password) < 6) $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل";
//         if ($password !== $confirm) $errors[] = "كلمة المرور غير متطابقة";
//         if (empty($governorate)) $errors[] = "يرجى اختيار المحافظة";
//         if (empty($area)) $errors[] = "يرجى اختيار المنطقة";
//         if (empty($street)) $errors[] = "يرجى إدخال عنوان الشارع";

//         if (empty($errors)) {
//             $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
//             $stmt->execute([$email]);
//             if ($stmt->fetch()) {
//                 $errors[] = "البريد الإلكتروني موجود مسبقًا";
//             } else {
//                 if ($this->userModel->register($name, $email, $phone, $governorate, $area, $street, $password)) {
//                     // إنشاء مهام خارطة الطريق للمستخدم الجديد
//                     require_once 'models/Roadmap.php';
//                     $roadmap = new Roadmap($this->db);
//                     $userId = $this->db->lastInsertId();
//                     $roadmap->initDefaultTasks($userId);

//                     $success = "تم إنشاء الحساب بنجاح، يمكنك <a href='index.php?page=login'>تسجيل الدخول</a>";
//                 } else {
//                     $errors[] = "حدث خطأ أثناء إنشاء الحساب";
//                 }
//             }
//         }
//         if (!empty($errors)) $error = implode("<br>", $errors);
//     }

//     include 'views/auth/register.php';
// }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['full_name'];
                header('Location: index.php?page=dashboard');
                exit;
            } else {
                $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة";
            }
        }
        include 'views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>
