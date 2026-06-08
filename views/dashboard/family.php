<?php
// المتغيرات المتاحة من RequestController::dashboard():
// $totalRequests, $pendingRequests, $completedRequests, $percentage, $recentRequests
// $supportTeam, $upcomingAppointments
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ========== نفس التنسيقات السابقة ========== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; color: #1e293b; }
    .dashboard-wrapper { max-width: 1400px; margin: 0 auto; padding: 1rem 1.5rem 3rem; }
    .welcome-section { background: white; border-radius: 1.5rem; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0; }
    .welcome-section h2 { font-size: 1.6rem; color: #1e3a8a; margin-bottom: 0.3rem; }
    .subtitle { color: #64748b; margin-bottom: 1.5rem; }
    .status-card { background: #f1f5f9; border-radius: 1rem; padding: 1rem; border-right: 4px solid #f59e0b; }
    .badge-pending { display: inline-block; background: #fef3c7; color: #b45309; padding: 0.2rem 0.8rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 600; margin-bottom: 0.8rem; }
    .status-header-row { margin-bottom: 1rem; }
    .status-title-group { display: flex; align-items: center; gap: 1rem; }
    .main-icon-circle { width: 48px; height: 48px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0284c7; font-size: 1.5rem; }
    .text-stack h3 { font-size: 1.1rem; margin: 0; }
    .update-time { font-size: 0.7rem; color: #64748b; }
    .status-steps { display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem; }
    .step-line { display: flex; align-items: center; gap: 0.8rem; font-size: 0.85rem; color: #334155; }
    .step-icon { font-size: 1.2rem; }
    .step-line.success .step-icon { color: #10b981; }
    .step-line.processing .step-icon { color: #f59e0b; }
    .step-line.waiting .step-icon { color: #94a3b8; }
    .actions-section { margin-bottom: 2rem; }
    .section-title { font-size: 1.3rem; margin-bottom: 1rem; color: #0f172a; }
    .quick-actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
    .action-card { background: white; border-radius: 1.2rem; padding: 1.2rem; text-decoration: none; border: 1px solid #e2e8f0; transition: 0.2s; display: block; }
    .action-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
    .action-icon svg { width: 36px; height: 36px; stroke-width: 1.5; }
    .card-blue .action-icon svg { stroke: #2563eb; }
    .card-green .action-icon svg { stroke: #16a34a; }
    .card-pink .action-icon svg { stroke: #db2777; }
    .action-card h4 { margin: 0.8rem 0 0.3rem; font-size: 1.1rem; }
    .action-card p { font-size: 0.8rem; color: #475569; margin-bottom: 0.8rem; }
    .action-link { color: #1e3a8a; font-weight: 600; font-size: 0.8rem; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
    .info-card { background: white; border-radius: 1.2rem; padding: 1.2rem; border: 1px solid #e2e8f0; }
    .card-title, .card-title1 { display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
    .appointment-item { margin-bottom: 1rem; padding-bottom: 0.8rem; border-bottom: 1px solid #f1f5f9; }
    .appointment-header { display: flex; justify-content: space-between; align-items: center; }
    .tag-scheduled, .tag-pending { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 2rem; }
    .tag-scheduled { background: #dcfce7; color: #166534; }
    .tag-pending { background: #fef9c3; color: #854d0e; }
    .date-text { font-size: 0.7rem; color: #64748b; display: block; margin-top: 0.3rem; }
    .team-member { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1rem; }
    .avatar-blue, .avatar-pink, .avatar-green, .avatar-purple { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; }
    .avatar-blue { background: #3b82f6; }
    .avatar-pink { background: #ec489a; }
    .member-info strong { display: block; font-size: 0.9rem; }
    .member-info span { font-size: 0.7rem; color: #475569; }
    .status-tag { display: inline-flex; align-items: center; gap: 0.2rem; }
    .dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .online .dot { background: #10b981; }
    .away .dot { background: #f59e0b; }
    .bottom-mini-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .mini-card { background: white; border-radius: 1rem; padding: 1rem; text-decoration: none; border: 1px solid #e2e8f0; transition: 0.2s; display: block; }
    .card-top-row { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem; }
    .mini-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; }
    .blue-bg { background: #3b82f6; }
    .purple-bg { background: #8b5cf6; }
    .green-bg { background: #10b981; }
    .mini-title { font-size: 0.9rem; margin: 0; color: #1e293b; }
    .card-bottom-row p { font-size: 0.75rem; color: #64748b; }
    .help-banner { background: #1e3a8a; color: white; border-radius: 1rem; padding: 1.2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .help-content { display: flex; align-items: center; gap: 1rem; }
    .help-circle-icon svg { width: 40px; height: 40px; stroke: white; }
    .help-text h4 { font-size: 1rem; margin-bottom: 0.2rem; }
    .help-text p { font-size: 0.8rem; opacity: 0.9; }
    .btn-support-green { background: #10b981; color: white; padding: 0.5rem 1rem; border-radius: 2rem; text-decoration: none; font-weight: 600; font-size: 0.85rem; }
</style>

<main class="dashboard-wrapper">
    <!-- قسم الترحيب -->
    <section class="welcome-section">
        <h2>مرحباً <?= htmlspecialchars($_SESSION['name'] ?? 'عادل') ?></h2>
        <p class="subtitle">إليك نظرة عامة على حالة طلبك والخدمات المتاحة</p>

        <div class="status-card">
            <?php
            $statusBadge = '';
            $lastRequestStatus = 'pending';
            if ($pendingRequests > 0) {
                $statusBadge = 'قيد المراجعة';
                $lastRequestStatus = 'pending';
            } elseif ($completedRequests > 0) {
                $statusBadge = 'مكتمل جزئياً';
                $lastRequestStatus = 'completed';
            } else {
                $statusBadge = 'لم يتم تقديم طلبات بعد';
                $lastRequestStatus = 'none';
            }
            ?>
            <span class="badge-pending"><?= $statusBadge ?></span>

            <div class="status-header-row">
                <div class="status-title-group">
                    <div class="main-icon-circle"><i class="far fa-clock"></i></div>
                    <div class="text-stack">
                        <h3>حالة الطلب</h3>
                        <p class="update-time">آخر تحديث: <?= date('Y/m/d H:i') ?></p>
                    </div>
                </div>
            </div>

            <div class="status-steps">
                <div class="step-line <?= ($lastRequestStatus != 'none') ? 'success' : 'waiting' ?>">
                    <i class="far <?= ($lastRequestStatus != 'none') ? 'fa-check-circle' : 'fa-pause-circle' ?> step-icon"></i>
                    <span class="step-label">تم تقديم الوثائق بنجاح</span>
                </div>
                <div class="step-line <?= ($pendingRequests > 0) ? 'processing' : (($completedRequests > 0) ? 'success' : 'waiting') ?>">
                    <i class="far <?= ($pendingRequests > 0) ? 'fa-clock' : (($completedRequests > 0) ? 'fa-check-circle' : 'fa-pause-circle') ?> step-icon"></i>
                    <span class="step-label">التحقق قيد التنفيذ (عادة 3-5 أيام عمل)</span>
                </div>
                <div class="step-line waiting">
                    <i class="far fa-pause-circle step-icon"></i>
                    <span class="step-label">التعيين لمسؤول الحالة قيد الانتظار</span>
                </div>
            </div>
        </div>
    </section>

    <!-- إجراءات سريعة -->
    <section class="actions-section">
        <h3 class="section-title">إجراءات سريعة</h3>
        <div class="quick-actions-grid">
            <a href="index.php?page=losses" class="action-card card-blue">
                <div class="action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <h4>توثيق خسارة جديدة</h4>
                <p>أضف توثيقاً إضافياً أو قم بتحديث السجلات الحالية</p>
                <span class="action-link"> ابدأ الآن ←</span>
            </a>
            <a href="index.php?page=roadmap" class="action-card card-green">
                <div class="action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div>
                <h4>عرض خطة العودة</h4>
                <p>الوصول إلى خارطة الطريق الشخصية للعودة للمنزل</p>
                <span class="action-link"> عرض الخطة ←</span>
            </a>
            <a href="index.php?page=mental" class="action-card card-pink">
                <div class="action-icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg></div>
                <h4>احصل على الدعم</h4>
                <p>تواصل مع موارد الاستشارة والصحة النفسية</p>
                <span class="action-link"> احصل على المساعدة ←</span>
            </a>
        </div>
    </section>

    <!-- شبكة معلومات: مواعيد قادمة وفريق الدعم -->
    <section class="info-grid">
        <div class="info-card">
            <h3 class="card-title"><i class="far fa-calendar-alt"></i> المواعيد القادمة</h3>
            <?php if (isset($upcomingAppointments) && count($upcomingAppointments) > 0): ?>
                <?php foreach ($upcomingAppointments as $app): ?>
                <div class="appointment-item">
                    <div class="appointment-header">
                        <h5>جلسة مع <?= htmlspecialchars($app['specialist_name']) ?></h5>
                        <span class="tag-<?= ($app['status'] == 'confirmed') ? 'scheduled' : 'pending' ?>"><?= ($app['status'] == 'confirmed') ? 'مجدول' : 'قيد الانتظار' ?></span>
                    </div>
                    <p><?= ($app['communication_method'] == 'video') ? 'جلسة فيديو' : 'اتصال صوتي' ?></p>
                    <span class="date-text"><i class="far fa-clock"></i> <?= date('j F Y', strtotime($app['appointment_date'])) ?> الساعة <?= date('g:i A', strtotime($app['appointment_time'])) ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="appointment-item"><p>لا توجد مواعيد قادمة</p></div>
            <?php endif; ?>
        </div>

       <div class="info-card">
    <h3 class="card-title1"><i class="fas fa-users"></i> فريق الدعم المعين</h3>
    <?php if (isset($supportTeam) && count($supportTeam) > 0): ?>
        <?php foreach ($supportTeam as $member): ?>
        <div class="team-member">
            <div class="avatar-<?= htmlspecialchars($member['avatar_color']) ?>"><?= htmlspecialchars($member['avatar_letters']) ?></div>
            <div class="member-info">
                <strong><?= htmlspecialchars($member['name']) ?></strong>
                <span><?= htmlspecialchars($member['role']) ?></span>
                <span class="status-tag <?= ($member['status'] == 'online') ? 'online' : 'away' ?>">
                    <?= ($member['status'] == 'online') ? 'متاحة' : 'متاح عند الطلب' ?> <span class="dot"></span>
                </span>
                <!-- أزرار التواصل -->
                <div class="contact-icons" style="margin-top: 5px; display: flex; gap: 8px;">
                    <?php if (!empty($member['phone'])): ?>
                        <a href="tel:<?= htmlspecialchars($member['phone']) ?>" class="contact-link phone" title="اتصال هاتفي" style="text-decoration: none; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 3px; background: #e2e8f0; padding: 2px 8px; border-radius: 20px; color: #0f172a;">
                            <i class="fas fa-phone-alt"></i> اتصل
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['whatsapp'])): ?>
                        <a href="https://wa.me/<?= ltrim($member['whatsapp'], '+') ?>" target="_blank" class="contact-link whatsapp" title="واتساب" style="text-decoration: none; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 3px; background: #25D366; padding: 2px 8px; border-radius: 20px; color: white;">
                            <i class="fab fa-whatsapp"></i> واتساب
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>سيتم تعيين فريق الدعم قريباً</p>
    <?php endif; ?>
</div>


    </section>

    <!-- بطاقات إضافية -->
    <section class="bottom-mini-grid">
        <a href="index.php?page=my_requests" class="mini-card">
            <div class="card-top-row"><div class="mini-icon blue-bg"><i class="fas fa-file-alt"></i></div><h4 class="mini-title">تفاصيل التحقق</h4></div>
            <div class="card-bottom-row"><p>تابع حالة التحقق من تقريرك</p></div>
        </a>
        <a href="index.php?page=volunteer" class="mini-card">
            <div class="card-top-row"><div class="mini-icon purple-bg"><i class="fas fa-user-friends"></i></div><h4 class="mini-title">المتطوعون والمنظمات</h4></div>
            <div class="card-bottom-row"><p>تواصل مع فريق الدعم والمنظمات</p></div>
        </a>
        <a href="index.php?page=profile" class="mini-card">
            <div class="card-top-row"><div class="mini-icon green-bg"><i class="fas fa-id-card"></i></div><h4 class="mini-title">ملفي الشخصي</h4></div>
            <div class="card-bottom-row"><p>تحديث البيانات الشخصية</p></div>
        </a>
    </section>

    <!-- عرض آخر الطلبات (من $recentRequests) -->
    <?php if (!empty($recentRequests)): ?>
    <div class="info-card" style="margin-bottom: 1.5rem;">
        <h3 class="card-title">📋 آخر الطلبات</h3>
        <table style="width:100%; border-collapse: collapse;">
            <thead><tr><th style="text-align:right; padding:0.5rem;">نوع الطلب</th><th>الحالة</th><th>التاريخ</th></tr></thead>
            <tbody>
                <?php foreach ($recentRequests as $req): ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:0.5rem;"><?= htmlspecialchars($req['request_type']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($req['status']) ?></td>
                    <td style="padding:0.5rem;"><?= date('Y/m/d', strtotime($req['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- بانر الدعم -->
    <div class="help-banner">
        <div class="help-content">
            <div class="help-circle-icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg></div>
            <div class="help-text"><h4>تحتاج دعماً فورياً؟</h4><p>خط دعم الأزمات متاح على مدار الساعة طوال أيام الأسبوع. لا تتردد في التواصل إذا كنت بحاجة إلى شخص للتحدث معه.</p></div>
        </div>
        <a href="index.php?page=mental" class="btn-support-green">اتصل بالدعم الآن</a>
    </div>
</main>

<?php include 'views/partials/footer.php'; ?>
