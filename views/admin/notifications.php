<h2>الإشعارات المرسلة</h2>
<table class="admin-table">
    <thead>
        <tr><th>المستخدم</th><th>الرسالة</th><th>تاريخ الإرسال</th><th>حذف</th></tr>
    </thead>
    <tbody>
        <?php foreach($notifications as $notif): ?>
        <tr>
            <td><?= htmlspecialchars($notif['full_name']) ?></td>
            <td><?= htmlspecialchars($notif['message']) ?></td>
            <td><?= $notif['created_at'] ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('حذف الإشعار؟');">
                    <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                    <button type="submit" name="delete_notification" class="btn-danger">🗑️</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
