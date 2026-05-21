<?php include 'views/partials/header.php'; ?>


<style>
.admin-wrapper { display: flex !important; gap: 2rem !important; align-items: flex-start !important; }
.admin-sidebar { width: 280px !important; background: #2c3e50 !important; border-radius: 20px !important; padding: 1.5rem !important; }
.admin-content { flex: 1 !important; background: white !important; border-radius: 20px !important; padding: 1.5rem !important; }
@media (max-width: 768px) { .admin-wrapper { flex-direction: column !important; } .admin-sidebar { width: 100% !important; } }
</style>

<style>
.admin-sidebar ul li a {
    color: white !important;
}
.admin-sidebar ul li a:hover {
    background: #1abc9c;
    color: #2c3e50 !important;
}
</style>


<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <h3>لوحة التحكم</h3>
        <ul>
            <li><a href="?page=dashboard&tab=dashboard">📊 الإحصائيات</a></li>
            <li><a href="?page=dashboard&tab=users">👥 المستخدمين</a></li>
            <li><a href="?page=dashboard&tab=requests">📝 الطلبات</a></li>
            <li><a href="?page=dashboard&tab=documents">📎 المستندات</a></li>
           <li><a href="?page=dashboard&tab=volunteer_requests">🤝 طلبات التطوع</a></li>
            <li><a href="?page=dashboard&tab=settings">⚙️ الإعدادات</a></li>
            <li><a href="index.php?page=profile">👤 ملفي الشخصي</a></li>
        </ul>
    </aside>
    <main class="admin-content">
        <?php include $content ?? 'views/admin/dashboard_stats.php'; ?>
    </main>
</div>
<?php include 'views/partials/footer.php'; ?>
