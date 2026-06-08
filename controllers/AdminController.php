<?php

require_once 'models/Area.php';
// optional: require other models if needed, but we'll use direct DB queries for simplicity

class AdminController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function index() {
        $tab = $_GET['tab'] ?? 'dashboard';
        $action = $_GET['action'] ?? 'list';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Handle delete action first (by redirect)
        if ($action === 'delete' && $id > 0 && $tab !== 'dashboard') {
            $this->deleteRecord($tab, $id);
            header("Location: index.php?page=admin&tab=" . urlencode($tab));
            exit;
        }

        // Handle POST (add / edit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_record'])) {
            $this->saveRecord($tab, $id, $_POST);
            header("Location: index.php?page=admin&tab=" . urlencode($tab));
            exit;
        }

        //

        // في بداية الدالة index() بعد تعريف المتغيرات الأولية
$export = isset($_GET['export']) && $_GET['export'] == 'csv';
if ($export && $tab == 'dashboard') {
    $this->exportRequestsToCSV();
    exit; // لا نكمل عرض الصفحة
}



        // For backward compatibility, also handle existing POST actions (update_area, delete_area, etc.)
        $this->handleLegacyPostActions();

        // Variables for the view
        $stats = [];
        $users = [];
        $requests = [];
        $documents = [];
        $notifications = [];
        $volunteerRequests = [];
        $areas = [];
        $governorates = [];
        $content = 'views/admin/dashboard_stats.php';
        $rows = [];
        $columns = [];
        $tableName = '';
        $record = null; // for edit form
        $isEditMode = ($action === 'edit' && $id > 0);
        $isAddMode = ($action === 'add');

        // Prepare data based on current tab
        switch ($tab) {
            case 'dashboard':
                $stats = $this->getStats();
                $content = 'views/admin/dashboard_stats.php';
                break;

            // ========== STANDARD TABLES (with existing views) ==========
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
           case 'volunteer_requests':
    $volunteerRequests = $this->getVolunteerRequests();
    $content = 'views/admin/volunteer_requests.php';
    break;
            case 'settings':
                $content = 'views/admin/settings.php';
                break;




            // ========== DYNAMIC TABLES (auto-generated CRUD) ==========
          case 'areas':
        // جلب بيانات المناطق مع pagination والبحث باستخدام Area Model
        $limit = 10;
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $offset = ($page - 1) * $limit;

        $areaModel = new Area($this->db);
        if (!empty($search)) {
            $areas = $areaModel->searchAreas($search, $limit, $offset);
            $total = $areaModel->countSearchAreas($search);
        } else {
            $areas = $areaModel->getAreasPaginated($limit, $offset);
            $total = $areaModel->getAreasCount();
        }
        $totalPages = ceil($total / $limit);
        $governorates = $areaModel->getGovernorates();

        $content = 'views/admin/areas.php';
        break;


            case 'governorates':
            case 'aid_requests':
            case 'losses':
            case 'volunteer_organizations':
            case 'support_team':
            case 'aid_packages':
            case 'roadmap_tasks':
            case 'mental_specialists':
            case 'mental_centers':
            case 'mental_articles':
            case 'mental_crisis_lines':
            case 'mental_daily_tips':
            case 'mental_appointments':
            case 'volunteer_requests_list':
                // For these tables, we dynamically generate the view
                $tableName = $this->getTableDisplayName($tab);
                $columns = $this->getTableColumns($tab);
                if ($isEditMode) {
                    $record = $this->getRecord($tab, $id);
                }
                if ($isAddMode) {
                    $record = null; // empty form
                }
                // Get paginated rows for listing (when action=list)
                if ($action === 'list') {
                    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
                    $limit = 20;
                    $offset = ($page - 1) * $limit;
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                    $rows = $this->getTableRows($tab, $limit, $offset, $search);
                    $totalRows = $this->countTableRows($tab, $search);
                    $totalPages = ceil($totalRows / $limit);
                }
                // Use a generic CRUD view template (we'll create it)
                $content = 'views/admin/dynamic_table.php';
                break;

            default:
                $stats = $this->getStats();
                $content = 'views/admin/dashboard_stats.php';
        }

        // Pass all needed variables to layout
        include 'views/admin/layout.php';
    }

    // ==================== LEGACY POST HANDLING (keep existing functionality) ====================
    private function handleLegacyPostActions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $redirectTab = $_GET['tab'] ?? 'dashboard';
            $redirect = true;

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
            if (isset($_POST['add_area'])) {
                $areaModel = new Area($this->db);
                $areaModel->addArea(
                    $_POST['governorate_id'],
                    $_POST['area_name'],
                    isset($_POST['is_safe']) ? 1 : 0,
                    isset($_POST['has_water']) ? 1 : 0,
                    isset($_POST['has_electricity']) ? 1 : 0,
                    isset($_POST['has_health_center']) ? 1 : 0,
                    isset($_POST['has_school']) ? 1 : 0,
                    isset($_POST['needs_reconstruction']) ? 1 : 0,
                    $_POST['safety_level']
                );
            }
            if (isset($_POST['update_area'])) {
                $this->updateArea($_POST['area_id'], [
                    'governorate_id' => $_POST['governorate_id'],
                    'area_name' => $_POST['area_name'],
                    'is_safe' => $_POST['is_safe'] ?? 0,
                    'has_water' => $_POST['has_water'] ?? 0,
                    'has_electricity' => $_POST['has_electricity'] ?? 0,
                    'has_health_center' => $_POST['has_health_center'] ?? 0,
                    'has_school' => $_POST['has_school'] ?? 0,
                    'needs_reconstruction' => $_POST['needs_reconstruction'] ?? 0,
                    'safety_level' => $_POST['safety_level']
                ]);
            }
            if (isset($_POST['delete_area'])) {
                $this->deleteArea($_POST['area_id']);
            }
            if (isset($_POST['update_role'])) {
                $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
                $stmt->execute([$_POST['new_role'], $_POST['user_id']]);
            }
            if (isset($_POST['delete_volunteer_request'])) {
                $this->deleteVolunteerRequest($_POST['request_id']);
            }

            if ($redirect) {
                header("Location: index.php?page=admin&tab=" . urlencode($redirectTab));
                exit;
            }
        }
    }

    // ==================== CORE CRUD METHODS (dynamic) ====================
    private function getTableRows($table, $limit, $offset, $search = '') {

     if (!$this->tableExists($table)) {
        return [];
    }

        $sql = "SELECT * FROM $table";
        if (!empty($search)) {
            // get first text column to search on (simple: search all columns)
            $columns = $this->getTableColumns($table);
            $searchConditions = [];
            foreach ($columns as $col) {
                if ($col !== 'id') {
                    $searchConditions[] = "$col LIKE :search";
                }
            }
            if (!empty($searchConditions)) {
                $sql .= " WHERE " . implode(' OR ', $searchConditions);
            }
        }
        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
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


    private function countTableRows($table, $search = '') {

     if (!$this->tableExists($table)) {
        return 0;
    }

        $sql = "SELECT COUNT(*) FROM $table";
        if (!empty($search)) {
            $columns = $this->getTableColumns($table);
            $searchConditions = [];
            foreach ($columns as $col) {
                if ($col !== 'id') {
                    $searchConditions[] = "$col LIKE :search";
                }
            }
            if (!empty($searchConditions)) {
                $sql .= " WHERE " . implode(' OR ', $searchConditions);
            }
        }
        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function getRecord($table, $id) {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function saveRecord($table, $id, $data) {
        unset($data['save_record']); // remove the submit button value
        if ($id > 0) {
            // Update
            $fields = [];
            $values = [];
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }
            $values[] = $id;
            $sql = "UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        } else {
            // Insert
            $columns = array_keys($data);
            $placeholders = array_fill(0, count($columns), '?');
            $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($data));
        }
    }

    private function deleteRecord($table, $id) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
    }

    // ==================== HELPERS FOR DYNAMIC TABLES ====================
    private function getTableColumns($table) {
        // Define which columns to show for each table (avoid sensitive fields like password)


         if (!$this->tableExists($table)) {
        return [];
    }

        $map = [
            'areas' => ['id', 'governorate_id', 'area_name', 'is_safe', 'has_water', 'has_electricity', 'has_health_center', 'has_school', 'needs_reconstruction', 'safety_level'],
            'governorates' => ['id', 'name'],
            'aid_requests' => ['id', 'user_id', 'request_type', 'description', 'status', 'created_at'],
            'losses' => ['id', 'user_id', 'document_type', 'title', 'description', 'loss_date', 'created_at'],
            'volunteer_organizations' => ['id', 'name', 'area', 'services', 'phone', 'whatsapp'],
            'support_team' => ['id', 'name', 'role', 'avatar_letters', 'avatar_color', 'status', 'display_order', 'is_active'],
            'aid_packages' => ['id', 'title', 'organization', 'status', 'contents', 'expected_date'],
            'roadmap_tasks' => ['id', 'task_name', 'task_category', 'due_date', 'status'],
'mental_specialists' => ['id', 'name', 'specialty', 'avatar_letter', 'phone', 'whatsapp', 'is_available'],            'mental_centers' => ['id', 'name', 'area', 'address', 'phone', 'services', 'working_hours'],
            'mental_articles' => ['id', 'title', 'category', 'read_time', 'badge_text', 'badge_color', 'card_color', 'created_at'],
            'mental_crisis_lines' => ['id', 'title', 'description', 'action_text', 'icon_class', 'bg_color', 'is_active'],
            'mental_daily_tips' => ['id', 'tip_text', 'display_date', 'created_at'],
            'mental_appointments' => ['id', 'user_id', 'specialist_id', 'appointment_date', 'appointment_time', 'status', 'created_at'],
            'volunteer_requests' => ['id', 'title', 'volunteers_needed', 'request_date', 'urgency'],
        ];
        return $map[$table] ?? ['id', 'name'];
    }

    private function getTableDisplayName($table) {
        $names = [
            'areas' => 'المناطق',
            'governorates' => 'المحافظات',
            'aid_requests' => 'طلبات المساعدة',
            'losses' => 'الخسائر',
            'volunteer_organizations' => 'منظمات التطوع',
            'support_team' => 'فريق الدعم',
            'aid_packages' => 'حزم المساعدات',
            'roadmap_tasks' => 'مهام خارطة الطريق',
            'mental_specialists' => 'الأخصائيون النفسيون',
            'mental_centers' => 'مراكز الدعم النفسي',
            'mental_articles' => 'المقالات النفسية',
            'mental_crisis_lines' => 'خدمات الدعم السريعة',
            'mental_daily_tips' => 'نصائح اليوم',
            'mental_appointments' => 'مواعيد الاستشارات النفسية',
            'volunteer_requests_list' => 'طلبات متطوعين (عامة)',
        ];
        return $names[$table] ?? $table;
    }

    // ==================== EXISTING DATA METHODS (kept for backward compatibility) ====================
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
        $stmt1 = $this->db->query("
            SELECT d.*, 'aid_request' as source, r.request_type, u.full_name
            FROM documents d
            JOIN aid_requests r ON d.request_id = r.id
            JOIN users u ON r.user_id = u.id
            ORDER BY d.uploaded_at DESC
        ");
        $aidDocs = $stmt1->fetchAll();
        $stmt2 = $this->db->query("
            SELECT l.id, l.user_id, l.document_type as request_type, l.title as description,
                   l.file_path, l.created_at as uploaded_at, 'loss' as source, u.full_name,
                   l.title
            FROM losses l
            JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
        ");
        $lossDocs = $stmt2->fetchAll();
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
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM volunteer_requests");
        $stats['volunteer_requests'] = $stmt->fetch()['total'];
        return $stats;
    }




    private function tableExists($table) {
    try {
        $result = $this->db->query("SHOW TABLES LIKE '$table'");
        return $result->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}


    private function getAllAreas() {
        $stmt = $this->db->query("
            SELECT a.*, g.name as governorate_name
            FROM areas a
            JOIN governorates g ON a.governorate_id = g.id
            ORDER BY g.name, a.area_name
        ");
        return $stmt->fetchAll();
    }

    private function updateArea($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE areas SET
                governorate_id = ?,
                area_name = ?,
                is_safe = ?,
                has_water = ?,
                has_electricity = ?,
                has_health_center = ?,
                has_school = ?,
                needs_reconstruction = ?,
                safety_level = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['governorate_id'],
            $data['area_name'],
            $data['is_safe'],
            $data['has_water'],
            $data['has_electricity'],
            $data['has_health_center'],
            $data['has_school'],
            $data['needs_reconstruction'],
            $data['safety_level'],
            $id
        ]);
    }

    private function deleteArea($id) {
        $stmt = $this->db->prepare("DELETE FROM areas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function deleteVolunteerRequest($id) {
        $stmt = $this->db->prepare("DELETE FROM volunteer_requests WHERE id = ?");
        return $stmt->execute([$id]);
    }



    private function exportRequestsToCSV() {
    // جلب نفس الفلاتر المستخدمة في عرض الجدول
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';

    $sql = "SELECT ar.id as request_id, u.full_name, ar.request_type, ar.created_at, ar.status
            FROM aid_requests ar
            JOIN users u ON ar.user_id = u.id";
    $params = [];

    if (!empty($search)) {
        $sql .= " WHERE (u.full_name LIKE ? OR ar.id LIKE ? OR ar.request_type LIKE ?)";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam];
    }
    if (!empty($status)) {
        if (!empty($params)) {
            $sql .= " AND ar.status = ?";
        } else {
            $sql .= " WHERE ar.status = ?";
        }
        $params[] = $status;
    }
    $sql .= " ORDER BY ar.id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // إعداد اسم الملف
    $filename = 'aid_requests_' . date('Y-m-d_H-i-s') . '.csv';

    // إرسال رؤوس HTTP لتنزيل الملف
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // فتح مؤشر الإخراج
    $output = fopen('php://output', 'w');

    // إضافة BOM للتعامل مع اللغة العربية
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // كتابة رأس الأعمدة
    fputcsv($output, ['رقم الطلب', 'اسم العائلة', 'نوع الخسارة', 'تاريخ الطلب', 'الحالة']);

    // كتابة البيانات
    foreach ($rows as $row) {
        $statusText = '';
        switch ($row['status']) {
            case 'pending': $statusText = 'معلق'; break;
            case 'in_progress': $statusText = 'قيد التنفيذ'; break;
            case 'completed': $statusText = 'مكتمل'; break;
            default: $statusText = $row['status'];
        }
        fputcsv($output, [
            $row['request_id'],
            $row['full_name'],
            $row['request_type'],
            date('Y-m-d H:i', strtotime($row['created_at'])),
            $statusText
        ]);
    }

    fclose($output);
}

}




?>
