<?php
class AdminController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function index() {
        // تحديد التاب النشط من رابط الصفحة
        $tab = $_GET['tab'] ?? 'dashboard';

        switch($tab) {
            case 'users':
                $users = $this->getAllUsers();
                $content = 'views/admin/users.php';
                break;
            case 'requests':
                $requests = $this->getAllRequests();
                $content = 'views/admin/requests.php';
                break;
            case 'documents':
                $documents = $this->getAllDocuments();
                $content = 'views/admin/documents.php';
                break;
            case 'notifications':
                $notifications = $this->getAllNotifications();
                $content = 'views/admin/notifications.php';
                break;
            case 'settings':
                $content = 'views/admin/settings.php';
                break;
                case 'volunteer_requests':
    $volunteerRequests = $this->getVolunteerRequests();
    $content = 'views/admin/volunteer_requests.php';
    break;
            default:
                $stats = $this->getStats();
                $content = 'views/admin/dashboard_stats.php';
        }

        // معالجة POST لتحديث الحالة أو حذف المستخدمين إلخ
        $this->handlePostActions();

        include 'views/admin/layout.php';
    }

    private function handlePostActions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_user'])) {
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
                $stmt->execute([$_POST['user_id']]);
            }
            if (isset($_POST['update_request_status'])) {
                $stmt = $this->db->prepare("UPDATE aid_requests SET status = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['request_id']]);
            }
            if (isset($_POST['delete_notification'])) {
                $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = ?");
                $stmt->execute([$_POST['notif_id']]);
            }


              if (isset($_POST['delete_volunteer_request'])) {
            $this->deleteVolunteerRequest($_POST['request_id']);
        }


            // إعادة التوجيه لتجنب إعادة إرسال POST
            header("Location: index.php?page=dashboard&tab=" . ($_GET['tab'] ?? 'dashboard'));
            exit;
        }
    }

    private function getAllUsers() {
        $stmt = $this->db->query("SELECT id, full_name, email, phone, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    private function getAllRequests() {
        $stmt = $this->db->query("
            SELECT r.*, u.full_name, u.email
            FROM aid_requests r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getAllDocuments() {
    // مستندات طلبات المساعدة (النظام القديم)
    $stmt1 = $this->db->query("
        SELECT d.*, 'aid_request' as source, r.request_type, u.full_name
        FROM documents d
        JOIN aid_requests r ON d.request_id = r.id
        JOIN users u ON r.user_id = u.id
        ORDER BY d.uploaded_at DESC
    ");
    $aidDocs = $stmt1->fetchAll();

    // مستندات توثيق الخسائر (النظام الجديد)
    $stmt2 = $this->db->query("
        SELECT l.id, l.user_id, l.document_type as request_type, l.title as description,
               l.file_path, l.created_at as uploaded_at, 'loss' as source, u.full_name,
               l.title
        FROM losses l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.created_at DESC
    ");
    $lossDocs = $stmt2->fetchAll();

    // دمج المستندات من المصدرين
    return array_merge($aidDocs, $lossDocs);
}

    private function getAllNotifications() {
        $stmt = $this->db->query("
            SELECT n.*, u.full_name
            FROM notifications n
            JOIN users u ON n.user_id = u.id
            ORDER BY n.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getStats() {
        $stats = [];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE role='family'");
        $stats['families'] = $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM aid_requests");
        $stats['requests'] = $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM aid_requests WHERE status='pending'");
        $stats['pending'] = $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM documents");
        $stats['documents'] = $stmt->fetch()['total'];

          // إضافة إحصائية طلبات التطوع
    $stmt = $this->db->query("SELECT COUNT(*) as total FROM volunteer_requests");
    $stats['volunteer_requests'] = $stmt->fetch()['total'];

        return $stats;
    }


    private function getVolunteerRequests() {
    $stmt = $this->db->query("
        SELECT vr.*, u.full_name, u.email
        FROM volunteer_requests vr
        LEFT JOIN users u ON vr.user_id = u.id
        ORDER BY vr.created_at DESC
    ");
    return $stmt->fetchAll();
}

private function deleteVolunteerRequest($id) {
    $stmt = $this->db->prepare("DELETE FROM volunteer_requests WHERE id = ?");
    return $stmt->execute([$id]);
}
}
?>
