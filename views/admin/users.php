<h2>إدارة المستخدمين</h2>
<table class="admin-table">
    <thead>
        <tr><th>الاسم</th><th>البريد</th><th>الهاتف</th><th>الدور</th><th>تاريخ التسجيل</th><th>إجراء</th></tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['full_name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['phone']) ?></td>
            <td><?= $user['role'] ?></td>
            <td><?= $user['created_at'] ?></td>
            <td>
                <?php if($user['role'] != 'admin'): ?>
                <form method="POST" onsubmit="return confirm('حذف المستخدم؟');">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="delete_user" class="btn-danger">🗑️ حذف</button>
                </form>
                <?php else: ?>
                <span>مدير</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
