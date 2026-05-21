<?php include 'views/partials/header.php'; ?>
<div class="container">
    <h2>طلباتي</h2>
    <?php if(empty($requests)): ?>
        <p>لا توجد طلبات بعد. <a href="index.php?page=create_request">أنشئ طلبًا الآن</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>النوع</th><th>رقم التواصل</th><th>الوصف</th><th>الحالة</th><th>تاريخ الطلب</th></tr>
            </thead>
            <tbody>
                <?php foreach($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['request_type']) ?></td>
                    <td><?= htmlspecialchars($req['contact_phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars(substr($req['description'],0,100)) ?>...</td>
                    <td><?= $req['status'] ?></td>
                    <td><?= $req['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include 'views/partials/footer.php'; ?>
