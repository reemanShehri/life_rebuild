<h2>إدارة المستخدمين</h2>
<table class="admin-table">
    <thead>
        <tr>
            <th>الاسم</th>
            <th>البريد</th>
            <th>الهاتف</th>
            <th>الدور</th>
            <th>تاريخ التسجيل</th>
            <th>تغيير الدور</th>
            <th>إجراء</th>
        </tr>
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
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="new_role">
                        <option value="family" <?= $user['role']=='family' ? 'selected' : '' ?>>عائلة</option>
                        <option value="organization" <?= $user['role']=='organization' ? 'selected' : '' ?>>منظمة</option>
                        <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>مدير</option>
                    </select>
                    <button type="submit" name="update_role" class="btn-sm"
                        <?php if($user['id'] == $_SESSION['user_id']): ?>
                        onclick="return confirm('تحذير: أنت تغير دور حسابك الحالي. قد تفقد صلاحية الوصول إلى لوحة التحكم. هل أنت متأكد؟')"
                        <?php endif; ?>
                    >تحديث</button>
                </form>
            </td>
            <td>
                <form method="POST" onsubmit="return confirm('حذف المستخدم؟');">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="delete_user" class="btn-danger">🗑️ حذف</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
