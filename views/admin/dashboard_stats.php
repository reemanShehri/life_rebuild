<?php
// ========== التأكد من وجود المتغيرات، وإذا لم توجد نقوم بجلب البيانات مباشرة ==========
if (!isset($stats)) {
    $stats = [
        'families' => 0,
        'requests' => 0,
        'pending' => 0,
        'documents' => 0,
        'volunteer_requests' => 0,
        'appointments' => 0
    ];
}

// استخدام اتصال قاعدة البيانات العام
global $pdo;
$db = $pdo ?? (function() {
    require_once 'config/database.php';
    global $pdo;
    return $pdo;
})();

// ========== جلب بيانات طلبات العائلات (لجدول الطلبات) ==========
if (!isset($requests) || !isset($totalPages)) {
    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT ar.*, u.full_name, u.email
            FROM aid_requests ar
            JOIN users u ON ar.user_id = u.id";
    $countSql = "SELECT COUNT(*) FROM aid_requests ar JOIN users u ON ar.user_id = u.id";
    $params = [];

    if (!empty($search)) {
        $sql .= " WHERE (u.full_name LIKE ? OR ar.id LIKE ? OR ar.request_type LIKE ?)";
        $countSql .= " WHERE (u.full_name LIKE ? OR ar.id LIKE ? OR ar.request_type LIKE ?)";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam];
    }
    if (!empty($status)) {
        if (!empty($params)) {
            $sql .= " AND ar.status = ?";
            $countSql .= " AND ar.status = ?";
        } else {
            $sql .= " WHERE ar.status = ?";
            $countSql .= " WHERE ar.status = ?";
        }
        $params[] = $status;
    }
    $sql .= " ORDER BY ar.id DESC LIMIT $limit OFFSET $offset";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll();

    $stmtCount = $db->prepare($countSql);
    $stmtCount->execute($params);
    $totalRequests = $stmtCount->fetchColumn();
    $totalPages = ceil($totalRequests / $limit);
    $currentPage = $page;
}

// تعريف المتغيرات لتجنب الأخطاء
$totalPages = $totalPages ?? 1;
$currentPage = $currentPage ?? 1;
$search = $search ?? '';
$status = $status ?? '';

// ========== جلب بيانات حجوزات الاستشارات النفسية ==========
// $sqlApp = "SELECT ma.*, ms.name as specialist_name, u.full_name as user_name
//            FROM mental_appointments ma
//            LEFT JOIN mental_specialists ms ON ma.specialist_id = ms.id
//            LEFT JOIN users u ON ma.user_id = u.id
//            ORDER BY ma.appointment_date DESC, ma.appointment_time DESC
//            LIMIT 20";

$sqlApp = "SELECT ma.*,
                  ms.name as specialist_name,
                  ms.whatsapp as specialist_whatsapp,
                  u.full_name as user_name,
                  u.phone as user_phone
           FROM mental_appointments ma
           LEFT JOIN mental_specialists ms ON ma.specialist_id = ms.id
           LEFT JOIN users u ON ma.user_id = u.id
           ORDER BY ma.appointment_date DESC, ma.appointment_time DESC
           LIMIT 20";
           
$stmtApp = $db->prepare($sqlApp);
$stmtApp->execute();
$mentalAppointments = $stmtApp->fetchAll();
$totalAppointments = count($mentalAppointments);
$stats['appointments'] = $totalAppointments;
?>
<style>
    /* تنسيقات الجدول والإحصائيات */
    .stats-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 1rem;
        border-top: 4px solid;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .stat-card h3 { font-size: 0.9rem; color: #475569; margin-bottom: 0.5rem; }
    .stat-card p { font-size: 2rem; font-weight: 800; margin: 0; }
    .admin-table-section {
        background: white;
        border-radius: 1rem;
        padding: 1.2rem;
        margin-top: 1rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .table-header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }
    .controls-left-side {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-input-wrapper {
        position: relative;
    }
    .search-input-wrapper input {
        padding: 0.5rem 2rem 0.5rem 0.8rem;
        border: 1px solid #cbd5e1;
        border-radius: 2rem;
        width: 220px;
    }
    .search-table-icon {
        position: absolute;
        right: 0.7rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .table-dropdown, .btn-export-data {
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        border: 1px solid #cbd5e1;
        background: white;
        cursor: pointer;
    }
    .data-table-main {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table-main th, .data-table-main td {
        padding: 0.7rem;
        text-align: right;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table-main th {
        background: #f8fafc;
        font-weight: 600;
    }
    .tbl-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-green-solid { background: #dcfce7; color: #166534; }
    .badge-red-solid { background: #fee2e2; color: #991b1b; }
    .badge-blue-solid { background: #dbeafe; color: #1e40af; }
    .badge-orange-solid { background: #ffedd5; color: #9a3412; }
    .badge-yellow-solid { background: #fef9c3; color: #854d0e; }
    .badge-gray-solid { background: #f1f5f9; color: #475569; }
    .btn-table-view {
        background: transparent;
        border: 1px solid #cbd5e1;
        border-radius: 2rem;
        padding: 0.2rem 0.6rem;
        cursor: pointer;
    }
    .table-pagination-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .pagination-buttons {
        display: flex;
        gap: 0.3rem;
    }
    .btn-page-nav, .btn-page-num {
        padding: 0.3rem 0.7rem;
        border: 1px solid #cbd5e1;
        background: white;
        border-radius: 0.5rem;
        cursor: pointer;
        text-decoration: none;
        color: #1e293b;
    }
    .btn-page-num.active {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
    }
    .border-teal { border-top-color: #14b8a6; }
    @media (max-width: 768px) {
        .table-pagination-footer { flex-direction: column; align-items: center; }
        .controls-left-side { flex-direction: column; align-items: stretch; }
    }
</style>

<!-- ========== بطاقات الإحصائيات ========== -->
<div class="stats-cards-grid">
    <div class="stat-card border-blue" onclick="window.location.href='index.php?page=admin&tab=users'">
        <h3>👨‍👩‍👧‍👦 العائلات المسجلة</h3>
        <p><?= number_format($stats['families'] ?? 0) ?></p>
    </div>
    <div class="stat-card border-orange" onclick="window.location.href='index.php?page=admin&tab=requests&status=pending'">
        <h3>⏳ طلبات معلقة</h3>
        <p><?= number_format($stats['pending'] ?? 0) ?></p>
    </div>
    <div class="stat-card border-green" onclick="window.location.href='index.php?page=admin&tab=requests'">
        <h3>📋 إجمالي الطلبات</h3>
        <p><?= number_format($stats['requests'] ?? 0) ?></p>
    </div>
    <div class="stat-card border-purple" onclick="window.location.href='index.php?page=admin&tab=volunteer_requests'">
        <h3>🤝 طلبات التطوع</h3>
        <p><?= number_format($stats['volunteer_requests'] ?? 0) ?></p>
    </div>
    <div class="stat-card border-teal" onclick="window.location.href='index.php?page=admin&tab=mental_appointments'">
        <h3>📅 مواعيد الاستشارات</h3>
        <p><?= number_format($stats['appointments'] ?? 0) ?></p>
    </div>
</div>

<!-- ========== جدول طلبات العائلات ========== -->
<div class="admin-table-section">
    <div class="table-header-controls">
        <h3 class="table-title">📌 طلبات العائلات</h3>
        <div class="controls-left-side">
            <form method="GET" action="index.php" style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="tab" value="dashboard">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-table-icon"></i>
                    <input type="text" name="search" placeholder="ابحث عن عائلة..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="status" class="table-dropdown">
                    <option value="">جميع الحالات</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>قيد الانتظار</option>
                    <option value="in_progress" <?= $status == 'in_progress' ? 'selected' : '' ?>>قيد التنفيذ</option>
                    <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>مكتمل</option>
                </select>
                <button type="submit" class="btn-export-data"><i class="fas fa-search"></i> بحث</button>
                <a href="index.php?page=admin&tab=dashboard" class="btn-export-data">إعادة تعيين</a>
            </form>
            <a href="?page=admin&tab=dashboard&export=csv&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="btn-export-data">
                تصدير <i class="fas fa-download"></i>
            </a>
        </div>
    </div>

    <div class="responsive-table-wrapper">
        <table class="data-table-main">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم العائلة</th>
                    <th>نوع الخسارة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الأولوية</th>
                    <th>مسؤول الحالة</th>
                    <th>القيمة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($requests) && count($requests) > 0): ?>
                    <?php foreach ($requests as $req): ?>
                        <?php
                        $statusClass = '';
                        switch ($req['status']) {
                            case 'pending': $statusClass = 'badge-yellow-solid'; $statusText = 'معلق'; break;
                            case 'in_progress': $statusClass = 'badge-blue-solid'; $statusText = 'قيد التنفيذ'; break;
                            case 'completed': $statusClass = 'badge-green-solid'; $statusText = 'مكتمل'; break;
                            default: $statusClass = 'badge-gray-solid'; $statusText = $req['status'];
                        }
                        $priority = 'متوسطة';
                        $priorityClass = 'badge-orange-solid';
                        $caseManager = 'غير معين';
                        $estimatedValue = '---';
                        ?>
                        <tr>
                            <td class="text-muted">#<?= $req['id'] ?></td>
                            <td class="text-dark-bold"><?= htmlspecialchars($req['full_name'] ?? 'غير معروف') ?></td>
                            <td><?= htmlspecialchars($req['request_type'] ?? 'غير محدد') ?></td>
                            <td><?= date('Y-m-d', strtotime($req['created_at'])) ?></td>
                            <td><span class="tbl-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                            <td><span class="tbl-badge <?= $priorityClass ?>"><?= $priority ?></span></td>
                            <td><?= $caseManager ?></td>
                            <td class="text-dark-bold"><?= $estimatedValue ?></td>
                            <td><button class="btn-table-view" onclick="alert('تفاصيل الطلب <?= $req['id'] ?>')"><i class="far fa-eye"></i></button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;">لا توجد طلبات مطابقة للبحث</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="table-pagination-footer">
        <span class="pagination-info">عرض <?= count($requests) ?> من <?= $totalRequests ?? 0 ?> نتيجة</span>
        <div class="pagination-buttons">
            <?php if ($currentPage > 1): ?>
                <a href="?page=admin&tab=dashboard&p=<?= $currentPage-1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="btn-page-nav">السابق</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=admin&tab=dashboard&p=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="btn-page-num <?= ($i == $currentPage) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=admin&tab=dashboard&p=<?= $currentPage+1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="btn-page-nav">التالي</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- ========== جدول مواعيد الاستشارات النفسية ========== -->
<div class="admin-table-section" style="margin-top: 2rem;">
    <div class="table-header-controls">
        <h3 class="table-title">📅 مواعيد الاستشارات النفسية</h3>
        <div class="controls-left-side">
            <a href="?page=admin&tab=mental_appointments" class="btn-export-data">إدارة الحجوزات</a>
        </div>
    </div>
    <div class="responsive-table-wrapper">
        <table class="data-table-main">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>هاتف المستخدم</th>
                    <th>الأخصائي</th>
                    <th>واتساب الأخصائي</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>طريقة التواصل</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($mentalAppointments) && count($mentalAppointments) > 0): ?>
                    <?php foreach ($mentalAppointments as $app): ?>
                        <?php
                        $statusClass = '';
                        switch ($app['status']) {
                            case 'pending': $statusClass = 'badge-yellow-solid'; $statusText = 'قيد الانتظار'; break;
                            case 'confirmed': $statusClass = 'badge-green-solid'; $statusText = 'مؤكد'; break;
                            case 'cancelled': $statusClass = 'badge-red-solid'; $statusText = 'ملغي'; break;
                            default: $statusClass = 'badge-gray-solid'; $statusText = $app['status'];
                        }
                        $commMethod = ($app['communication_method'] == 'video') ? '🎥 فيديو' : '📞 اتصال صوتي';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($app['user_name'] ?? 'غير معروف') ?></td>
                            <td>
                                <?php if (!empty($app['user_phone'])): ?>
                                    <a href="tel:<?= htmlspecialchars($app['user_phone']) ?>" style="direction: ltr; text-decoration: none;">📞 <?= htmlspecialchars($app['user_phone']) ?></a>
                                <?php else: ?>
                                    غير متوفر
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($app['specialist_name'] ?? 'غير معين') ?></td>
                            <td>
                                <?php if (!empty($app['specialist_whatsapp'])): ?>
                                    <a href="https://wa.me/<?= ltrim($app['specialist_whatsapp'], '+') ?>" target="_blank" style="direction: ltr; text-decoration: none;">💬 <?= htmlspecialchars($app['specialist_whatsapp']) ?></a>
                                <?php else: ?>
                                    غير متوفر
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d', strtotime($app['appointment_date'])) ?></td>
                            <td><?= date('g:i A', strtotime($app['appointment_time'])) ?></td>
                            <td><?= $commMethod ?></td>
                            <td><span class="tbl-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                            <td><button class="btn-table-view" onclick="alert('تفاصيل الموعد <?= $app['id'] ?>')"><i class="far fa-eye"></i></button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;">لا توجد مواعيد استشارات مسجلة</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
