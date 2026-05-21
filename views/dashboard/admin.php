<?php include 'views/partials/header.php'; ?>
<div class="container" style="max-width: 1200px;">
    <h2>لوحة تحكم المشرف</h2>
    <?php if(empty($requests)): ?>
        <p>لا توجد طلبات حتى الآن.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>المستخدم</th><th>النوع</th><th>الوصف</th><th>الحالة</th><th>تغيير الحالة</th></tr>
            </thead>
            <tbody>
                <?php foreach($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['full_name']) ?></td>
                    <td><?= htmlspecialchars($req['request_type']) ?></td>
                    <td><?= htmlspecialchars(substr($req['description'],0,80)) ?></td>
                    <td><?= $req['status'] ?></td>
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
                            <button type="submit" name="update_status">تحديث</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include 'views/partials/footer.php'; ?>
