<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/volunteer.css">
<div class="volunteer-container">
    <div class="volunteer-hero">
        <h1>🙋‍♂️ طلبات التطوع الخاصة بي</h1>
        <p>هذه قائمة بجميع طلبات المساعدة التي قدمتها للمتطوعين والمنظمات.</p>
        <a href="index.php?page=volunteer" class="btn-primary">➕ تقديم طلب جديد</a>
    </div>

    <?php if (empty($requests)): ?>
        <div class="alert alert-info">📭 لم تقم بتقديم أي طلب تطوع بعد. <a href="index.php?page=volunteer">تقديم طلب الآن</a></div>
    <?php else: ?>
        <div class="requests-list">
            <?php foreach ($requests as $req): ?>
            <div class="request-card">
                <div class="request-header">
                    <span class="request-type"><?= htmlspecialchars($req['request_type']) ?></span>
                    <span class="request-status status-<?= $req['status'] ?>"><?= $req['status'] ?></span>
                </div>
                <div class="request-details">
                    <p><strong>📝 التفاصيل:</strong> <?= nl2br(htmlspecialchars($req['details'])) ?></p>
                    <p><strong>📞 رقم التواصل:</strong> <?= htmlspecialchars($req['contact_info']) ?></p>
                    <p><strong>📅 تاريخ الطلب:</strong> <?= date('Y/m/d H:i', strtotime($req['created_at'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'views/partials/footer.php'; ?>
