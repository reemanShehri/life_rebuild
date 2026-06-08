<?php
// views/admin/layout.php
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ========== تنسيقات الأدمن ========== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f1f5f9; color: #0f172a; }
    .admin-container { display: flex; gap: 2rem; align-items: flex-start; max-width: 1600px; margin: 1rem auto 2rem; padding: 0 1.5rem; }
    .admin-sidebar { width: 280px; background: #2c3e50; border-radius: 1rem; padding: 1.5rem; flex-shrink: 0; }
    .admin-sidebar h3 { color: white; font-size: 1.2rem; margin-bottom: 1rem; border-bottom: 2px solid #1abc9c; display: inline-block; }
    .admin-sidebar ul { list-style: none; margin-top: 1rem; max-height: 70vh; overflow-y: auto; }
    .admin-sidebar li { margin-bottom: 0.5rem; }
    .admin-sidebar a { display: block; padding: 0.5rem 0.8rem; color: #e2e8f0; text-decoration: none; border-radius: 0.5rem; transition: 0.2s; }
    .admin-sidebar a:hover, .admin-sidebar a.active { background: #1abc9c; color: #2c3e50; font-weight: 600; }
    .admin-content { flex: 1; background: white; border-radius: 1rem; padding: 1.5rem; overflow-x: auto; }
    .stats-cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: #f8fafc; padding: 1rem; border-radius: 1rem; border-top: 4px solid; }
    .stat-card h3 { font-size: 0.9rem; color: #475569; }
    .stat-card p { font-size: 2rem; font-weight: 800; margin: 0.5rem 0; }
    .table-header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem; }
    .btn-add, .btn-export { background: #1e3a8a; color: white; border: none; padding: 0.4rem 1rem; border-radius: 2rem; cursor: pointer; }
    .data-table-main { width: 100%; border-collapse: collapse; }
    .data-table-main th, .data-table-main td { padding: 0.7rem; text-align: right; border-bottom: 1px solid #e2e8f0; }
    .data-table-main th { background: #f8fafc; font-weight: 600; }
    .btn-table-view { background: transparent; border: 1px solid #cbd5e1; border-radius: 2rem; padding: 0.2rem 0.6rem; cursor: pointer; margin: 0 0.2rem; }
    .btn-table-view i { margin: 0 0.2rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 0.3rem; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; }
    .btn-save { background: #10b981; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 2rem; cursor: pointer; }
    .admin-content table, .admin-content td, .admin-content th { color: #1e293b !important; background-color: white !important; }
    .admin-content .data-table-main td { color: #0f172a !important; background: white !important; }
    .admin-content .btn-table-view i, .admin-content .btn-table-view { color: #334155 !important; }
    .tbl-badge { color: #1e293b !important; }
    @media (max-width: 800px) { .admin-container { flex-direction: column; } .admin-sidebar { width: 100%; } }
</style>

<div class="admin-container">
    <aside class="admin-sidebar">
        <h3>لوحة التحكم</h3>
        <ul>
            <li><a href="index.php?page=admin&tab=dashboard" class="<?= $tab == 'dashboard' ? 'active' : '' ?>">📊 الإحصائيات</a></li>
            <li><a href="index.php?page=admin&tab=users" class="<?= $tab == 'users' ? 'active' : '' ?>">👥 المستخدمين</a></li>
            <li><a href="index.php?page=admin&tab=areas" class="<?= $tab == 'areas' ? 'active' : '' ?>">🗺️ المناطق</a></li>
            <li><a href="index.php?page=admin&tab=governorates" class="<?= $tab == 'governorates' ? 'active' : '' ?>">🏙️ المحافظات</a></li>
            <li><a href="index.php?page=admin&tab=aid_requests" class="<?= $tab == 'aid_requests' ? 'active' : '' ?>">📝 طلبات المساعدة</a></li>
            <li><a href="index.php?page=admin&tab=documents" class="<?= $tab == 'documents' ? 'active' : '' ?>">📎 المستندات</a></li>
            <li><a href="index.php?page=admin&tab=losses" class="<?= $tab == 'losses' ? 'active' : '' ?>">🏚️ الخسائر</a></li>


              <!-- <li><a href="index.php?page=admin&tab=volunteer_requests" class="<?= $tab == 'volunteer_requests' ? 'active' : '' ?>">📢 طلبات متطوعين (عامة)< </a></li> -->


            <li><a href="index.php?page=admin&tab=volunteer_organizations" class="<?= $tab == 'volunteer_organizations' ? 'active' : '' ?>">🤝 منظمات التطوع</a></li>
            <li><a href="index.php?page=admin&tab=volunteer_requests" class="<?= $tab == 'volunteer_requests' ? 'active' : '' ?>">📋 طلبات التطوع</a></li>
            <li><a href="index.php?page=admin&tab=support_team" class="<?= $tab == 'support_team' ? 'active' : '' ?>">👥 فريق الدعم</a></li>
            <li><a href="index.php?page=admin&tab=roadmap_tasks" class="<?= $tab == 'roadmap_tasks' ? 'active' : '' ?>">🗺️ مهام خارطة الطريق</a></li>
            <li><a href="index.php?page=admin&tab=mental_specialists" class="<?= $tab == 'mental_specialists' ? 'active' : '' ?>">🧑‍⚕️ أخصائيون نفسيون</a></li>
            <li><a href="index.php?page=admin&tab=mental_centers" class="<?= $tab == 'mental_centers' ? 'active' : '' ?>">🏥 مراكز نفسية</a></li>
            <li><a href="index.php?page=admin&tab=mental_articles" class="<?= $tab == 'mental_articles' ? 'active' : '' ?>">📖 مقالات نفسية</a></li>
            <li><a href="index.php?page=admin&tab=mental_crisis_lines" class="<?= $tab == 'mental_crisis_lines' ? 'active' : '' ?>">🆘 خطوط أزمات</a></li>
            <li><a href="index.php?page=admin&tab=mental_daily_tips" class="<?= $tab == 'mental_daily_tips' ? 'active' : '' ?>">💡 نصائح يومية</a></li>
            <li><a href="index.php?page=admin&tab=mental_appointments" class="<?= $tab == 'mental_appointments' ? 'active' : '' ?>">📅 مواعيد استشارات</a></li>
            <!-- <li><a href="index.php?page=admin&tab=volunteer_requests_list" class="<?= $tab == 'volunteer_requests' ? 'active' : '' ?>">📢 طلبات متطوعين (عامة)</a></li> -->
            <li><a href="index.php?page=admin&tab=settings" class="<?= $tab == 'settings' ? 'active' : '' ?>">⚙️ الإعدادات</a></li>
        </ul>
    </aside>

    <main class="admin-content">
        <?php
        // 1. إذا كان هناك ملف مخصص للمحتوى (مثل users.php, areas.php) - نضمنه مباشرة
        if (isset($content) && file_exists($content)) {
            include $content;
        }
        // 2. إذا كان التبويب هو dashboard نعرض الإحصائيات
        elseif ($tab == 'dashboard') {
            ?>
            <div class="stats-cards-grid">
                <div class="stat-card border-blue"><h3>العائلات المسجلة</h3><p><?= number_format($stats['families'] ?? 0) ?></p></div>
                <div class="stat-card border-orange"><h3>الطلبات المعلقة</h3><p><?= number_format($stats['pending'] ?? 0) ?></p></div>
                <div class="stat-card border-green"><h3>إجمالي الطلبات</h3><p><?= number_format($stats['requests'] ?? 0) ?></p></div>
                <div class="stat-card border-purple"><h3>طلبات التطوع</h3><p><?= number_format($stats['volunteer_requests'] ?? 0) ?></p></div>
            </div>
            <div class="admin-table-section">
                <h3>آخر طلبات المساعدة</h3>
                <div class="responsive-table-wrapper">
                    <table class="data-table-main">
                        <thead><tr><th>ID</th><th>العائلة</th><th>نوع المساعدة</th><th>التاريخ</th><th>الحالة</th></tr></thead>
                        <tbody>
                            <?php
                            $recent = $this->db->query("SELECT ar.id, u.full_name, ar.request_type, ar.created_at, ar.status FROM aid_requests ar JOIN users u ON ar.user_id = u.id ORDER BY ar.id DESC LIMIT 5")->fetchAll();
                            foreach ($recent as $r): ?>
                            <tr><td>#<?= $r['id'] ?></td><td><?= htmlspecialchars($r['full_name']) ?></td><td><?= $r['request_type'] ?></td><td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td><td><span class="tbl-badge badge-yellow-solid"><?= $r['status'] ?></span></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        // 3. إذا كان لدينا عرض ديناميكي (إضافة أو تعديل)
        elseif ($action === 'add' || $action === 'edit') {
            ?>
            <div class="form-container">
                <h3><?= $action === 'add' ? 'إضافة جديد' : 'تعديل' ?> <?= htmlspecialchars($tableName) ?></h3>
                <form method="POST" action="index.php?page=admin&tab=<?= $tab ?>&action=save">
                    <?php if ($action === 'edit' && $record): ?>
                        <input type="hidden" name="id" value="<?= $record['id'] ?>">
                    <?php endif; ?>
                    <?php foreach ($columns as $col): if ($col == 'id') continue; ?>
                    <div class="form-group">
                        <label><?= str_replace('_', ' ', $col) ?></label>
                        <input type="text" name="<?= $col ?>" value="<?= htmlspecialchars($record[$col] ?? '') ?>">
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn-save">💾 حفظ</button>
                    <a href="index.php?page=admin&tab=<?= $tab ?>" class="btn-cancel">إلغاء</a>
                </form>
            </div>
            <?php
        }
        // 4. خلاف ذلك، نعرض الجدول الديناميكي (لأي جدول غير مخصص وليس dashboard)
        else {
            ?>
            <div class="table-header-controls">
                <h3>إدارة <?= htmlspecialchars($tableName) ?></h3>
                <button class="btn-add" onclick="location.href='index.php?page=admin&tab=<?= $tab ?>&action=add'">➕ إضافة جديد</button>
            </div>
            <div class="responsive-table-wrapper">
                <table class="data-table-main">
                    <thead><tr><?php foreach ($columns as $col): ?><th><?= str_replace('_', ' ', $col) ?></th><?php endforeach; ?><th>إجراءات</th></tr></thead>
                    <tbody>
                        <?php if (count($rows) > 0): ?>
                            <?php foreach ($rows as $row): ?>
                            <tr><?php foreach ($columns as $col): ?><td><?= htmlspecialchars($row[$col] ?? '') ?></td><?php endforeach; ?>
                            <td><button class="btn-table-view" onclick="location.href='index.php?page=admin&tab=<?= $tab ?>&action=edit&id=<?= $row['id'] ?>'"><i class="fas fa-edit"></i> تعديل</button>
                            <button class="btn-table-view" onclick="if(confirm('حذف؟')) location.href='index.php?page=admin&tab=<?= $tab ?>&action=delete&id=<?= $row['id'] ?>'"><i class="fas fa-trash"></i> حذف</button></td></tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="<?= count($columns)+1 ?>">لا توجد بيانات</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
    </main>
</div>

<?php include 'views/partials/footer.php'; ?>
