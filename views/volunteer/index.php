<?php
// المتغيرات المتاحة: $organizations, $supportTeam, $aidPackages, $volunteerRequests
// بالإضافة إلى $error, $success من عملية إرسال الطلب
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ========== جميع تنسيقات التصميم الجديد ========== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; color: #1e293b; }
    .volunteers-hero-banner {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        border-radius: 2rem;
        padding: 2rem;
        text-align: center;
        margin: 1rem 1.5rem 2rem;
        color: white;
    }
    .hero-users-icon { font-size: 3rem; margin-bottom: 0.5rem; }
    .volunteers-hero-banner h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .dashboard-wrapper { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem 3rem; }
    .alert-info-box { background: #eff6ff; border-right: 4px solid #3b82f6; border-radius: 1rem; padding: 1rem; margin-bottom: 2rem; }
    .section-main-title { font-size: 1.5rem; margin: 1.5rem 0 1rem; color: #0f172a; }
    .support-team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .member-card { background: white; border-radius: 1rem; overflow: hidden; border-top: 4px solid; padding: 1rem; }
    .border-blue { border-top-color: #3b82f6; }
    .border-green { border-top-color: #10b981; }
    .border-pink { border-top-color: #ec489a; }
    .card-top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .member-avatar-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 1.2rem; }
    .bg-blue { background: #3b82f6; }
    .bg-green { background: #10b981; }
    .bg-pink { background: #ec489a; }
    .status-pill { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 2rem; }
    .blue-pill { background: #dbeafe; color: #1e3a8a; }
    .green-pill { background: #dcfce7; color: #166534; }
    .pink-pill { background: #fce7f3; color: #be185d; }
    .member-name { font-size: 1.1rem; margin: 0.2rem 0; }
    .member-role { color: #475569; font-size: 0.8rem; }
    .member-org { font-size: 0.75rem; color: #64748b; display: block; margin: 0.3rem 0; }
    .member-contact-info { margin: 0.5rem 0; }
    .contact-item { display: flex; justify-content: space-between; font-size: 0.75rem; margin: 0.2rem 0; }
    .card-actions-row { display: flex; gap: 0.5rem; margin-top: 1rem; }
    .btn-action-call, .btn-action-msg { flex: 1; padding: 0.4rem; border-radius: 2rem; border: none; cursor: pointer; font-weight: 600; }
    .btn-action-call { background: #dbeafe; color: #1e3a8a; }
    .btn-action-msg { background: #e2e8f0; color: #334155; }
    .bottom-layout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
    @media (max-width: 900px) { .bottom-layout-grid { grid-template-columns: 1fr; } }
    .white-section-card { background: white; border-radius: 1rem; padding: 1.2rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
    .section-card-header { display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1rem; }
    .package-item-box { padding: 1rem; border-radius: 1rem; margin-bottom: 1rem; }
    .bg-light-green { background: #f0fdf4; }
    .bg-light-blue { background: #eff6ff; }
    .bg-light-orange { background: #fff7ed; }
    .package-top-flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
    .pack-badge { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 2rem; }
    .green-badge { background: #dcfce7; color: #166534; }
    .blue-badge { background: #dbeafe; color: #1e3a8a; }
    .orange-badge { background: #ffedd5; color: #9a3412; }
    .pack-contents { margin: 0.5rem 0; display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .content-tag { background: white; padding: 0.2rem 0.5rem; border-radius: 2rem; font-size: 0.7rem; border: 1px solid #cbd5e1; }
    .org-list-item { padding: 0.8rem 0; border-bottom: 1px solid #e2e8f0; }
    .org-desc { font-size: 0.75rem; color: #475569; display: block; }
    .org-meta { font-size: 0.7rem; color: #64748b; display: inline-block; margin-left: 0.8rem; }
    .v-req-item { display: flex; justify-content: space-between; align-items: center; padding: 0.8rem; border-right: 3px solid; margin-bottom: 0.8rem; background: #f8fafc; border-radius: 0.8rem; }
    .border-left-red { border-right-color: #ef4444; }
    .border-left-orange { border-right-color: #f59e0b; }
    .req-badge-urgency { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 2rem; }
    .red-urg { background: #fee2e2; color: #991b1b; }
    .orange-urg { background: #ffedd5; color: #9a3412; }
    .btn-request-more, .btn-submit-volunteer, .btn-solid-blue-footer, .btn-outline-dark { background: #1e3a8a; color: white; border: none; padding: 0.5rem 1rem; border-radius: 2rem; cursor: pointer; font-weight: 600; margin-top: 0.5rem; display: inline-block; text-align: center; }
    .btn-outline-dark { background: transparent; border: 1px solid #1e3a8a; color: #1e3a8a; }
    .help-footer-banner { background: #1e3a8a; color: white; border-radius: 1rem; padding: 1.2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .help-footer-right { display: flex; align-items: center; gap: 1rem; }
    .help-footer-icon svg { width: 40px; height: 40px; stroke: white; }
    .alert { padding: 0.8rem; border-radius: 1rem; margin: 1rem 0; }
    .alert-success { background: #dcfce7; color: #166534; border-right: 4px solid #16a34a; }
    .alert-error { background: #fee2e2; color: #991b1b; border-right: 4px solid #dc2626; }
</style>

<div class="volunteers-hero-banner">
    <div class="hero-users-icon"><i class="fas fa-user-friends"></i></div>
    <h1>مركز المتطوعين والمنظمات</h1>
    <p>تواصل مع فريق الدعم المخصص لك واستفد من خدمات المنظمات الإنسانية</p>
</div>

<main class="dashboard-wrapper">
    <!-- عرض رسائل النجاح أو الخطأ -->
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="alert-info-box">
        <div class="alert-header"><i class="far fa-check-circle check-icon"></i> <strong>فريق دعم متكامل</strong></div>
        <p>فريقك المخصص جاهز لمساعدتك في جميع احتياجاتك، يمكنك التواصل معهم مباشرة في أي وقت.</p>
    </div>

    <!-- فريق الدعم (من قاعدة البيانات) -->
    <h2 class="section-main-title">فريق الدعم المخصص</h2>
    <div class="support-team-grid">
        <?php if (isset($supportTeam) && count($supportTeam) > 0): ?>
            <?php foreach ($supportTeam as $member): ?>
                <div class="member-card border-<?= $member['avatar_color'] ?>">
                    <div class="card-top-header">
                        <div class="member-avatar-circle bg-<?= $member['avatar_color'] ?>"><?= htmlspecialchars($member['avatar_letters']) ?></div>
                        <span class="status-pill <?= ($member['status'] == 'online') ? 'blue-pill' : 'pink-pill' ?>"><?= ($member['status'] == 'online') ? 'متاحة' : 'متاح عند الطلب' ?></span>
                    </div>
                    <div class="member-info-content">
                        <h3 class="member-name"><?= htmlspecialchars($member['name']) ?></h3>
                        <p class="member-role"><?= htmlspecialchars($member['role']) ?></p>
                        <span class="member-org"><i class="fas fa-building"></i> فريق الدعم</span>
                        <div class="member-contact-info">
                            <div class="contact-item"><span class="direction-ltr">+970 59 111 2222</span> <i class="fas fa-phone-alt"></i></div>
                            <div class="contact-item"><span><?= strtolower(str_replace(' ', '.', $member['name'])) ?>@rebuild.ps</span> <i class="far fa-envelope"></i></div>
                        </div>
                    </div>
                    <div class="card-actions-row">
                        <button class="btn-action-call" onclick="window.location.href='tel:+970591112222'"><i class="fas fa-phone-alt"></i> اتصال</button>
                        <button class="btn-action-msg" onclick="alert('سيتم تفعيل المراسلة قريباً')"><i class="far fa-comment-alt"></i> رسالة</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>سيتم تعيين فريق الدعم قريباً</p>
        <?php endif; ?>
    </div>

    <div class="bottom-layout-grid">
        <!-- حزم المساعدات (من قاعدة البيانات) -->
        <div class="packages-side">
            <div class="white-section-card">
                <div class="section-card-header"><i class="fas fa-box text-blue"></i><h3>حزم المساعدات</h3></div>
                <?php if (isset($aidPackages) && count($aidPackages) > 0): ?>
                    <?php foreach ($aidPackages as $pkg): ?>
                        <div class="package-item-box <?= $pkg['bg_class'] ?>">
                            <div class="package-top-flex">
                                <div class="pack-title-group"><h4><i class="fas fa-box"></i> <?= htmlspecialchars($pkg['title']) ?></h4><span class="pack-sub"><i class="fas fa-building"></i> <?= htmlspecialchars($pkg['organization']) ?></span></div>
                                <span class="pack-badge <?= $pkg['badge_class'] ?>"><?= ($pkg['status'] == 'delivered') ? 'مستلم' : (($pkg['status'] == 'approved') ? 'موافق عليه' : 'قيد المراجعة') ?></span>
                            </div>
                            <div class="pack-contents">
                                <?php foreach (explode(',', $pkg['contents']) as $item): ?>
                                    <span class="content-tag"><?= trim($item) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="pack-date"><i class="far fa-clock"></i> <?= ($pkg['expected_date']) ? date('d F Y', strtotime($pkg['expected_date'])) : 'قيد المراجعة' ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد حزم مساعدات حالياً</p>
                <?php endif; ?>
<div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
    <button class="btn-request-more" onclick="document.getElementById('request-form').scrollIntoView({behavior:'smooth'})">طلب مساعدة إضافية</button>
    <a href="index.php?page=volunteer_request" class="btn-request-more" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem; background: #234883; text-decoration: none; text-align: center; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 500; height: 35px; color: white;">📋 طلباتي</a>
</div>         </div>
        </div>

        <!-- المنظمات + طلبات المتطوعين -->
        <div class="organizations-side">
            <!-- المنظمات (من قاعدة البيانات) -->
            <div class="white-section-card">
                <div class="section-card-header"><i class="fas fa-building text-blue"></i><h3>المنظمات المتاحة</h3></div>
                <div class="org-list-wrapper">
                    <?php if (isset($organizations) && count($organizations) > 0): ?>
                        <?php foreach ($organizations as $org): ?>
                            <div class="org-list-item">
                                <div class="org-item-text">
                                    <strong><?= htmlspecialchars($org['name']) ?></strong>
                                    <span class="org-desc"><?= htmlspecialchars(substr($org['services'], 0, 60)) ?>...</span>
                                    <span class="org-meta"><i class="fas fa-phone-alt"></i> <?= htmlspecialchars($org['phone'] ?? 'غير متوفر') ?></span>
                                    <span class="org-meta"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($org['area'] ?? 'غزة') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>لا توجد منظمات مسجلة حالياً</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- طلبات المتطوعين (من قاعدة البيانات) -->
            <div class="white-section-card mt-24">
                <div class="section-card-header"><i class="fas fa-user-plus text-purple"></i><h3>طلبات المتطوعين</h3></div>
                <div class="volunteer-req-list">
                    <?php if (isset($volunteerRequests) && count($volunteerRequests) > 0): ?>
                        <?php foreach ($volunteerRequests as $req): ?>
                            <div class="v-req-item border-left-<?= ($req['urgency'] == 'urgent') ? 'red' : 'orange' ?>">
                                <div class="v-req-info">
                                    <strong><?= htmlspecialchars($req['title']) ?> <span class="v-date"><?= date('d F Y', strtotime($req['request_date'])) ?></span></strong>
                                    <small><?= $req['volunteers_needed'] ?> متطوعين مطلوبين</small>
                                </div>
                                <span class="req-badge-urgency <?= ($req['urgency'] == 'urgent') ? 'red-urg' : 'orange-urg' ?>"><?= ($req['urgency'] == 'urgent') ? 'عاجل' : 'متوسط' ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>لا توجد طلبات حالياً</p>
                    <?php endif; ?>
                </div>
                <button class="btn-submit-volunteer" onclick="document.getElementById('request-form').scrollIntoView({behavior:'smooth'})">طلب متطوعين جدد</button>
            </div>
        </div>
    </div>

    <!-- نموذج طلب المساعدة (نفس الكود الأول مع تحسين التصميم) -->
    <div id="request-form" class="white-section-card" style="margin-top: 1rem;">
        <div class="section-card-header"><i class="fas fa-hands-helping"></i><h3>📢 طلب مساعدة ميدانية</h3></div>
        <form method="POST" action="index.php?page=volunteer_request">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display:block; font-weight:600;">نوع المساعدة المطلوبة</label>
                <select name="request_type" required style="width:100%; padding:0.5rem; border-radius:0.8rem; border:1px solid #cbd5e1;">
                    <option value="food">🍲 طعام وطرود غذائية</option>
                    <option value="shelter">🏠 إيواء مؤقت أو خيمة</option>
                    <option value="medical">🏥 مساعدة طبية أو أدوية</option>
                    <option value="psychological">🧠 دعم نفسي</option>
                    <option value="education">📚 دعم تعليمي (كتب، تدريس)</option>
                    <option value="water">💧 مياه نظيفة</option>
                    <option value="clothes">👕 كسوة وملابس</option>
                    <option value="other">📦 أخرى</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display:block; font-weight:600;">التفاصيل (الكمية، العنوان، ملاحظات)</label>
                <textarea name="details" rows="3" required style="width:100%; padding:0.5rem; border-radius:0.8rem; border:1px solid #cbd5e1;"></textarea>
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display:block; font-weight:600;">طريقة التواصل المفضلة</label>
                <select name="preferred_contact" style="width:100%; padding:0.5rem; border-radius:0.8rem;">
                    <option value="phone">📞 اتصال هاتفي</option>
                    <option value="whatsapp">💬 واتساب</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
    <label style="display:block; font-weight:600;">رقم التواصل (واتساب/هاتف)</label>
    <input type="text" name="contact_phone" placeholder="مثال: 059xxxxxxx" required style="width:100%; padding:0.5rem; border-radius:0.8rem; border:1px solid #cbd5e1;">
</div>
            <button type="submit" class="btn-request-more" style="background:#1e3a8a; color:white;">إرسال طلب المساعدة 🚀</button>
        </form>
    </div>

    <!-- بانر المساعدة -->
    <footer class="help-footer-banner">
        <div class="help-footer-right">
            <div class="help-footer-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </div>
            <div class="help-footer-text">
                <h3>هل تحتاج إلى مساعدة إضافية؟</h3>
                <p>إذا كنت بحاجة إلى دعم إضافي أو ترغب في التواصل مع منظمات أخرى، يمكننا مساعدتك في ذلك. فريقنا جاهز للإجابة على أي أسئلة لديك.</p>
            </div>
        </div>
        <div class="help-footer-buttons">
            <button class="btn-solid-blue-footer" onclick="document.getElementById('request-form').scrollIntoView({behavior:'smooth'})">تواصل مع منسق المتطوعين</button>
            <button class="btn-outline-dark" onclick="alert('الخط الساخن: 16000')">اتصل بالخط الساخن</button>
        </div>
    </footer>
</main>

<?php include 'views/partials/footer.php'; ?>
