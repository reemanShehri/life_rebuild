<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/dashboard.css">
<div class="dashboard-container">
    <div class="welcome-section">
        <h2>مرحبًا <?= htmlspecialchars($_SESSION['name']) ?></h2>
        <p>مرحباً بعودتك. إليك ملخص نشاطك على المنصة.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-info">
                <h3>إجمالي الطلبات</h3>
                <p class="stat-number"><?= $totalRequests ?></p>
            </div>
        </div>
        <div class="stat-card pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>طلبات معلقة</h3>
                <p class="stat-number"><?= $pendingRequests ?></p>
            </div>
        </div>
        <div class="stat-card completed">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3>طلبات مكتملة</h3>
                <p class="stat-number"><?= $completedRequests ?></p>
            </div>
        </div>
        <div class="stat-card roadmap">
            <div class="stat-icon">🗺️</div>
            <div class="stat-info">
                <h3>نسبة الإنجاز</h3>
                <p class="stat-number"><?= $percentage ?>%</p>
            </div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="index.php?page=profile" class="btn btn-secondary">👤 ملفي الشخصي</a>   <!-- هذا السطر المضاف -->
        <a href="index.php?page=create_request" class="btn btn-primary">➕ طلب مساعدة جديد</a>
        <a href="index.php?page=my_requests" class="btn btn-secondary">📋 طلباتي السابقة</a>
        <a href="index.php?page=roadmap" class="btn btn-secondary">🗺️ خارطة طريق العودة</a>
        <a href="index.php?page=my_volunteer_requests" class="btn btn-secondary">🤝 طلبات التطوع الخاصة بي</a>
        <a href="index.php?page=logout" class="btn btn-danger">🚪 تسجيل خروج</a>
    </div>

    <?php if (!empty($recentRequests)): ?>
    <div class="recent-requests">
        <h3>📌 آخر الطلبات</h3>
        <table class="recent-table">
            <thead>
                <tr><th>نوع الطلب</th><th>الحالة</th><th>التاريخ</th></tr>
            </thead>
            <tbody>
                <?php foreach ($recentRequests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['request_type']) ?></td>
                    <td class="status-<?= $req['status'] ?>"><?= $req['status'] ?></td>
                    <td><?= date('Y/m/d', strtotime($req['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include 'views/partials/footer.php'; ?>
