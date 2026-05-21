<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/losses.css">
<div class="losses-container">
    <h1>📋 أرشيف توثيق الخسائر</h1>
    <a href="index.php?page=add_loss" class="btn btn-primary">➕ توثيق خسارة جديدة</a>

    <?php if (empty($losses)): ?>
        <p>لا توجد أي مستندات مرفوعة بعد. استخدم الزر أعلاه لتوثيق خسائر عائلتك.</p>
    <?php else: ?>
        <div class="losses-grid">
            <?php foreach ($losses as $loss): ?>
            <div class="loss-card">
                <div class="loss-icon">
                    <?php if ($loss['document_type'] == 'id_card'): ?>🪪
                    <?php elseif ($loss['document_type'] == 'property_deed'): ?>🏠
                    <?php elseif ($loss['document_type'] == 'medical_report'): ?>🏥
                    <?php elseif ($loss['document_type'] == 'education_cert'): ?>🎓
                    <?php elseif ($loss['document_type'] == 'house_damage'): ?>🏚️
                    <?php else: ?>📄
                    <?php endif; ?>
                </div>
                <div class="loss-details">
                    <h3><?= htmlspecialchars($loss['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($loss['description'])) ?></p>
                    <small>📅 <?= date('Y/m/d', strtotime($loss['created_at'])) ?></small>
                    <?php if ($loss['loss_date']): ?>
                        <small>🗓️ تاريخ الخسارة: <?= date('Y/m/d', strtotime($loss['loss_date'])) ?></small>
                    <?php endif; ?>
                    <a href="<?= $loss['file_path'] ?>" target="_blank" class="btn-sm">📎 عرض الملف</a>
                    <a href="index.php?page=delete_loss&id=<?= $loss['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('حذف هذا التوثيق؟')">🗑️ حذف</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'views/partials/footer.php'; ?>
