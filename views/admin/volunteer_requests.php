<h2>🤝 طلبات التطوع (من العائلات)</h2>
<?php if (empty($volunteerRequests)): ?>
    <p>لا توجد طلبات تطوع حتى الآن.</p>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>العائلة</th>
                <th>البريد الإلكتروني</th>
                <th>نوع المساعدة</th>
                <th>التفاصيل</th>
                <th>رقم التواصل</th>
                <th>الحالة</th>
                <th>تاريخ الطلب</th>
                <th>إجراء</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($volunteerRequests as $req): ?>
            <tr>
                <td><?= htmlspecialchars($req['full_name'] ?? 'زائر') ?></td>
                <td><?= htmlspecialchars($req['email'] ?? 'غير مسجل') ?></td>
                <td><?= $req['request_type'] ?></td>
                <td><?= nl2br(htmlspecialchars($req['details'])) ?></td>
                <td><?= htmlspecialchars($req['contact_info']) ?></td>
                <td class="status-<?= $req['status'] ?>"><?= $req['status'] ?></td>
                <td><?= date('Y/m/d H:i', strtotime($req['created_at'])) ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                        <button type="submit" name="delete_volunteer_request" class="btn-danger">🗑️ حذف</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
