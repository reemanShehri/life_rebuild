<h2>إدارة طلبات المساعدة</h2>
<table class="admin-table">
    <thead>
        <tr><th>العائلة</th><th>نوع الطلب</th><th>رقم التواصل</th><th>الوصف</th><th>الحالة</th><th>تاريخ الطلب</th><th>تحديث الحالة</th></tr>
    </thead>
    <tbody>
        <?php foreach($requests as $req): ?>
        <tr>
            <td><?= htmlspecialchars($req['full_name']) ?></td>
            <td><?= $req['request_type'] ?></td>
            <td><?= htmlspecialchars($req['contact_phone'] ?? '') ?></td>
            <td><?= htmlspecialchars(substr($req['description'], 0, 80)) ?>...</td>
            <td class="status-<?= $req['status'] ?>"><?= $req['status'] ?></td>
            <td><?= $req['created_at'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $req['status']=='pending'?'selected':'' ?>>قيد الانتظار</option>
                        <option value="approved" <?= $req['status']=='approved'?'selected':'' ?>>مقبول</option>
                        <option value="assigned" <?= $req['status']=='assigned'?'selected':'' ?>>تم التعيين</option>
                        <option value="completed" <?= $req['status']=='completed'?'selected':'' ?>>مكتمل</option>
                        <option value="rejected" <?= $req['status']=='rejected'?'selected':'' ?>>مرفوض</option>
                    </select>
                    <button type="submit" name="update_request_status">تحديث</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
