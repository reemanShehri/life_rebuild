<h2>📎 جميع المستندات (طلبات مساعدة + توثيق خسائر)</h2>
<table class="admin-table">
    <thead>
        <tr><th>المستخدم</th><th>النوع/المصدر</th><th>الوصف/العنوان</th><th>تاريخ الرفع</th><th>الملف</th><th>الإجراء</th></tr>
    </thead>
    <tbody>
        <?php foreach ($documents as $doc): ?>
        <tr>
            <td><?= htmlspecialchars($doc['full_name']) ?></td>
            <td>
                <?php if ($doc['source'] == 'loss'): ?>
                    🗂️ توثيق خسائر - <?= str_replace('_', ' ', $doc['request_type']) ?>
                <?php else: ?>
                    📝 طلب مساعدة - <?= $doc['request_type'] ?>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars(substr($doc['description'] ?? $doc['title'] ?? '', 0, 50)) ?>...</td>
            <td><?= date('Y-m-d', strtotime($doc['uploaded_at'])) ?></td>
            <td>
                <a href="<?= $doc['file_path'] ?>" target="_blank" class="btn-sm">📎 عرض</a>
            </td>
            <td>
                <?php if ($doc['source'] == 'loss'): ?>
                <a href="index.php?page=delete_loss&id=<?= $doc['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('حذف المستند؟')">🗑️</a>
                <?php else: ?>
                —
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
