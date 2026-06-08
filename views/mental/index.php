<?php
// المتغيرات المتاحة: $centers, $specialists, $articles, $quickServices, $dailyTip, $upcomingAppointments, $allAppointments
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* جميع التنسيقات كما هي موجودة لديك - سأضعها مختصرة لعدم الإطالة */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f9fafc; }
    .mental-page { max-width: 1400px; margin: 0 auto; padding: 1rem 1.5rem 3rem; }
    .support-hero { background: linear-gradient(135deg, #fce4ec, #f8bbd0); border-radius: 2rem; padding: 3rem 2rem; text-align: center; margin-bottom: 2.5rem; }
    .hero-icon { font-size: 4rem; color: #e91e63; margin-bottom: 1rem; }
    .support-hero h1 { font-size: 2.5rem; font-weight: 800; color: #2c3e50; }
    .support-hero p { font-size: 1.1rem; color: #4a5a6e; max-width: 700px; margin: 0 auto; }
    .support-quick-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.8rem; margin-bottom: 3rem; }
    .q-card { background: #fff; border-radius: 1.5rem; padding: 1.5rem; box-shadow: 0 6px 14px rgba(0,0,0,0.03); border: 1px solid #eee; transition: 0.2s; }
    .q-card:hover { transform: translateY(-4px); }
    .q-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .q-icon { width: 48px; height: 48px; background: #f0f2f5; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
    .q-txt span { font-weight: 700; font-size: 1.2rem; }
    .q-txt p { color: #6c7a8e; font-size: 0.85rem; }
    .q-action-text { display: inline-block; background: #eef2f5; padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; margin-top: 0.5rem; }
    .btn-purple-action { background: #9c27b0; color: white; border: none; padding: 0.5rem 1.2rem; border-radius: 2rem; font-weight: 600; cursor: pointer; }
    .pink-q .q-icon { background: #fce4ec; color: #e91e63; }
    .purple-q .q-icon { background: #f3e5f5; color: #9c27b0; }
    .blue-q .q-icon { background: #e3f2fd; color: #2196f3; }
    .support-layout-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 2rem; margin-bottom: 3rem; }
    @media (max-width: 900px) { .support-layout-grid { grid-template-columns: 1fr; } }
    .white-card { background: white; border-radius: 1.5rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #edf2f7; }
    .card-head-flex { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1rem; border-bottom: 1px solid #edf2f7; padding-bottom: 0.8rem; }
    .blue-icon { color: #2196f3; font-size: 1.4rem; }
    .green-icon { color: #4caf50; font-size: 1.4rem; }
    .calendar-widget { margin: 1rem 0; overflow-x: auto; }
    .cal-days-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.3rem; text-align: center; }
    .day-name { font-weight: 600; color: #6c7a8e; padding: 0.5rem; }
    .day { background: #f8fafc; padding: 0.6rem; border-radius: 0.8rem; cursor: pointer; }
    .day:hover { background: #e3f2fd; }
    .empty { background: transparent; cursor: default; }
    .specialists-list { margin-top: 1.5rem; }
    .list-title { font-size: 1.1rem; margin-bottom: 1rem; }
    .specialist-card { display: flex; justify-content: space-between; align-items: center; background: #fafcff; border-radius: 1rem; padding: 0.8rem; margin-bottom: 0.8rem; border: 1px solid #eef2f8; }
    .sp-info-group { display: flex; align-items: center; gap: 0.8rem; }
    .sp-avatar { width: 44px; height: 44px; background: #fce4ec; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #c2185b; }
    .sp-text strong { display: block; font-size: 0.9rem; }
    .sp-text span { font-size: 0.75rem; color: #7f8c8d; }
    .btn-book-pill { background: #e91e63; color: white; border: none; padding: 0.4rem 1rem; border-radius: 2rem; font-weight: 600; cursor: pointer; }
    .apt-item { background: #fef9f0; border-right: 4px solid #4caf50; padding: 0.8rem; border-radius: 1rem; margin-bottom: 1rem; }
    .apt-content-top { display: flex; justify-content: space-between; align-items: center; }
    .text-group strong { display: block; }
    .date-text, .time-green { font-size: 0.75rem; color: #2e7d32; }
    .video-icon, .phone-icon { font-size: 1.3rem; color: #4caf50; }
    .btn-cancel { background: #ffebee; border: none; color: #c62828; padding: 0.2rem 1rem; border-radius: 2rem; margin-top: 0.5rem; cursor: pointer; }
    .tip-box p { font-size: 0.9rem; color: #4a627a; margin: 0.8rem 0; }
    .read-more-pink { color: #e91e63; text-decoration: none; font-weight: 600; }
    .centers-section { margin: 3rem 0; }
    .section-header { display: flex; align-items: center; gap: 0.6rem; font-size: 1.8rem; margin-bottom: 1.5rem; }
    .centers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.8rem; }
    .center-card { background: white; border-radius: 1.2rem; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #eef2f6; }
    .center-image { background: #f0f4fa; text-align: center; padding: 1rem; }
    .center-info { padding: 1.2rem; }
    .center-info h3 { margin-bottom: 0.3rem; font-size: 1.2rem; }
    .area, .hours, .phone, .services { font-size: 0.85rem; margin: 0.3rem 0; color: #4b5e77; }
    .card-actions { display: flex; gap: 0.8rem; margin-top: 1rem; }
    .btn-call, .btn-map { background: #eef2f6; padding: 0.4rem 0.8rem; border-radius: 2rem; font-size: 0.8rem; text-decoration: none; color: #1e2a3e; }
    .bottom-library-section { margin: 3rem 0; }
    .sec-header { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1.5rem; }
    .library-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; }
    .lib-card { background: white; border-radius: 1rem; padding: 1.5rem; border-top: 4px solid; }
    .lib-card.pink-card { border-top-color: #e91e63; }
    .lib-card.purple-card { border-top-color: #9c27b0; }
    .lib-card.blue-card { border-top-color: #2196f3; }
    .lib-card.green-card { border-top-color: #4caf50; }
    .badge { background: #f0f2f5; display: inline-block; padding: 0.2rem 0.7rem; border-radius: 2rem; font-size: 0.7rem; margin-bottom: 0.8rem; }
    .view-all-container { text-align: center; margin-top: 2rem; }
    .btn-view-all { background: #2c3e50; color: white; padding: 0.6rem 1.8rem; border-radius: 2rem; border: none; font-weight: 600; cursor: pointer; }
    .chat-widget-container { position: fixed; bottom: 1.5rem; left: 1.5rem; z-index: 1000; }
    .chat-trigger-btn { background: #e91e63; color: white; width: 55px; height: 55px; border-radius: 50%; border: none; font-size: 1.8rem; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    .chat-popup-box { display: none; flex-direction: column; width: 280px; background: white; border-radius: 1rem; box-shadow: 0 8px 20px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 0.8rem; }
    .chat-header { background: #e91e63; color: white; padding: 0.6rem; display: flex; justify-content: space-between; }
    .chat-body { height: 200px; overflow-y: auto; padding: 0.6rem; background: #fef9f0; }
    .message-received { background: #eef2f6; padding: 0.4rem; border-radius: 1rem; margin-bottom: 0.4rem; }
    .chat-footer { display: flex; border-top: 1px solid #ddd; }
    .chat-footer input { flex: 1; border: none; padding: 0.6rem; }
    .btn-send-chat { background: #e91e63; border: none; color: white; padding: 0 0.8rem; }
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1100; }
    .modal-content { background: white; padding: 2rem; border-radius: 1.5rem; width: 90%; max-width: 400px; text-align: center; }
    .modal-input { width: 100%; padding: 0.6rem; margin: 1rem 0; border: 1px solid #ccc; border-radius: 1rem; }
    .btn-confirm, .btn-close { padding: 0.5rem 1rem; border-radius: 2rem; margin: 0.3rem; border: none; cursor: pointer; }
    .btn-confirm { background: #4caf50; color: white; }
    .btn-close { background: #ccc; }
</style>

<div class="mental-page">
    <div class="support-hero">
        <div class="hero-icon"><i class="fas fa-heart"></i></div>
        <h1>الدعم النفسي والاجتماعي</h1>
        <p>نحن هنا لدعمك في رحلة التعافي النفسي. احجز موعداً مع أخصائي أو تصفح مكتبة الموارد.</p>
    </div>

    <!-- الخدمات السريعة -->
    <div class="support-quick-grid">
        <?php foreach ($quickServices as $service): ?>
            <div class="q-card <?= $service['bg_color'] ?>-q">
                <div class="q-header">
                    <div class="q-icon"><i class="fas <?= htmlspecialchars($service['icon_class']) ?>"></i></div>
                    <div class="q-txt">
                        <span><?= htmlspecialchars($service['title']) ?></span>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                </div>
                <?php if ($service['action_text'] == 'ابدأ جلسة الآن'): ?>
                    <button class="btn-purple-action" onclick="alert('جاري تحويلك لجلسة فورية...')"><?= htmlspecialchars($service['action_text']) ?></button>
                <?php else: ?>
                    <strong class="q-action-text"><?= htmlspecialchars($service['action_text']) ?></strong>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- حجز ومواعيد -->
    <div class="support-layout-grid">
        <div class="calendar-booking-section">
            <div class="white-card">
                <div class="card-head-flex"><i class="fa-regular fa-calendar-days blue-icon"></i><h3>حجز موعد</h3></div>
                <div class="calendar-widget"><div class="cal-days-grid" id="simpleCalendar"></div></div>
               <div class="specialists-list">
    <h4 class="list-title">الأخصائيون المتاحون</h4>
    <?php if (count($specialists) > 0): ?>
        <?php foreach ($specialists as $spec): ?>
            <div class="specialist-card">
                <div class="sp-info-group">
                    <div class="sp-avatar avatar-pink"><?= htmlspecialchars($spec['avatar_letter']) ?></div>
                    <div class="sp-text">
                        <strong><?= htmlspecialchars($spec['name']) ?></strong>
                        <span><?= htmlspecialchars($spec['specialty']) ?></span>
                        <?php if (!empty($spec['whatsapp'])): ?>
                            <div class="contact-info">
                                <a href="https://wa.me/<?= ltrim($spec['whatsapp'], '+') ?>" target="_blank" class="whatsapp-link">
                                    <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($spec['whatsapp']) ?>
                                </a>
                            </div>
                        <?php elseif (!empty($spec['phone'])): ?>
                            <div class="contact-info">
                                <a href="tel:<?= htmlspecialchars($spec['phone']) ?>" class="phone-link">
                                    <i class="fas fa-phone-alt"></i> <?= htmlspecialchars($spec['phone']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="btn-book-pill" onclick="openBookingModal('<?= htmlspecialchars($spec['name']) ?>', <?= $spec['id'] ?>)">احجز</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا يوجد أخصائيون متاحون حالياً.</p>
    <?php endif; ?>
</div>
            </div>
        </div>

        <div class="upcoming-sidebar">
            <div class="white-card">
                <div class="card-head-flex"><i class="fa-regular fa-clock green-icon"></i><h3>مواعيدك القادمة</h3></div>
                <?php if(isset($_SESSION['user_id']) && count($allAppointments) > 0): ?>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($allAppointments as $app): ?>
                            <?php
                                $borderColor = '#ff9800'; $statusText = 'قيد المراجعة';
                                if($app['status'] == 'confirmed') { $borderColor = '#4caf50'; $statusText = 'مؤكد'; }
                                elseif($app['status'] == 'cancelled') { $borderColor = '#f44336'; $statusText = 'ملغي'; }
                                elseif($app['status'] == 'completed') { $borderColor = '#2196f3'; $statusText = 'مكتمل'; }
                                $isPast = (strtotime($app['appointment_date']) < strtotime(date('Y-m-d')));
                            ?>
                            <div class="apt-item" style="border-right: 4px solid <?= $borderColor ?>; margin-bottom:1rem;">
                                <div class="apt-content-top" style="justify-content:space-between;">
                                    <div class="text-group">
                                        <strong><?= htmlspecialchars($app['specialist_name']) ?></strong>
                                        <span class="date-text"><?= date('j F Y', strtotime($app['appointment_date'])) ?></span>
                                        <span class="time-green"><?= date('g:i A', strtotime($app['appointment_time'])) ?></span>
                                    </div>
                                    <div style="background:<?= $borderColor ?>20; padding:0.2rem 0.8rem; border-radius:2rem; font-size:0.7rem;"><?= $statusText ?></div>
                                </div>
                                <?php if (!in_array($app['status'], ['cancelled','completed']) && !$isPast): ?>
                                    <div class="apt-actions" style="margin-top:0.5rem;"><button class="btn-cancel" onclick="cancelAppointment(<?= (int)$app['id'] ?>)">إلغاء</button></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align:center;">لا توجد مواعيد مسجلة</p>
                <?php endif; ?>
            </div>
            <div class="white-card tip-box">
                <h3>نصيحة اليوم</h3>
                <p><?= htmlspecialchars($dailyTip['tip_text']) ?></p>
                <a href="#" class="read-more-pink">اقرأ المزيد ←</a>
            </div>
        </div>
    </div>

    <!-- مراكز الدعم -->
    <div class="centers-section">
        <div class="section-header"><i class="fas fa-hospital-user"></i><h2>مراكز الدعم النفسي في غزة</h2></div>
        <div class="centers-grid">
            <?php foreach ($centers as $center): ?>
                <div class="center-card">
                    <div class="center-image"><i class="fas fa-heart" style="font-size:3rem; color:#e91e63;"></i></div>
                    <div class="center-info">
                        <h3><?= htmlspecialchars($center['name']) ?></h3>
                        <p class="area">📍 <?= htmlspecialchars($center['area']) ?> | <?= htmlspecialchars($center['address']) ?></p>
                        <p class="hours">🕒 <?= htmlspecialchars($center['working_hours']) ?></p>
                        <p class="phone">📞 <?= htmlspecialchars($center['phone']) ?></p>
                        <p class="services">🩺 <?= nl2br(htmlspecialchars($center['services'])) ?></p>
                        <div class="card-actions">
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $center['phone']) ?>" class="btn-call">📞 اتصل الآن</a>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($center['address'].', '.$center['area'].' غزة') ?>" target="_blank" class="btn-map">🗺️ اتجاهات</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- مكتبة الموارد -->
    <div class="bottom-library-section">
        <div class="sec-header"><i class="fas fa-book-open blue-icon"></i><h3>مكتبة الصحة النفسية</h3></div>
        <div class="library-grid">
            <?php foreach ($articles as $article): ?>
                <div class="lib-card <?= $article['card_color'] ?>-card">
                    <span class="badge"><?= htmlspecialchars($article['badge_text']) ?></span>
                    <h4><?= htmlspecialchars($article['title']) ?></h4>
                    <div class="card-meta"><i class="fa-regular fa-clock"></i><small><?= htmlspecialchars($article['read_time']) ?></small></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-all-container"><button class="btn-view-all" onclick="window.location.href='index.php?page=mental_library'">عرض جميع المقالات</button></div>
    </div>
</div>

<!-- شات -->
<div class="chat-widget-container">
    <div class="chat-popup-box" id="chatPopup">
        <div class="chat-header"><span>💬 تحدث معنا</span><button class="close-chat" onclick="toggleChat()">✖</button></div>
        <div class="chat-body"><div class="message-received">مرحباً! كيف يمكنني مساعدتك اليوم؟</div></div>
        <div class="chat-footer"><input type="text" id="chatInput" placeholder="اكتب رسالتك..."><button class="btn-send-chat" onclick="sendMessage()">إرسال</button></div>
    </div>
    <button class="chat-trigger-btn" onclick="toggleChat()"><i class="fas fa-comment-dots"></i></button>
</div>

<!-- مودال الحجز -->
<div id="bookingModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3>🎯 تأكيد حجز الجلسة</h3>
        <p>حجز مع: <span id="modalDocName"></span></p>
        <div class="form-group"><label>التاريخ:</label><input type="date" id="appointmentDate" class="modal-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>"></div>
        <div class="form-group"><label>الوقت:</label><select id="appointmentTime" class="modal-input">
            <option value="09:00:00">09:00 صباحاً</option><option value="10:00:00" selected>10:00 صباحاً</option>
            <option value="11:00:00">11:00 صباحاً</option><option value="12:00:00">12:00 مساءً</option>
            <option value="13:00:00">01:00 مساءً</option><option value="14:00:00">02:00 مساءً</option>
            <option value="15:00:00">03:00 مساءً</option><option value="16:00:00">04:00 مساءً</option>
        </select></div>
        <div class="form-group"><label>طريقة التواصل:</label><select id="communicationMethod" class="modal-input">
            <option value="call">📞 اتصال صوتي</option><option value="video">🎥 اتصال فيديو</option>
        </select></div>
        <div class="modal-buttons">
            <button class="btn-confirm" onclick="confirmBooking()">تأكيد الحجز</button>
            <button class="btn-close" onclick="closeBookingModal()">إلغاء</button>
        </div>
    </div>
</div>

<script>
// ========== دوال الحجز والإلغاء والتقويم ==========
let selectedSpecialistId = null;

function openBookingModal(docName, specId) {
    selectedSpecialistId = specId;
    document.getElementById('modalDocName').innerText = docName;
    // تعيين التاريخ الافتراضي إلى الغد
    let tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    let yyyy = tomorrow.getFullYear();
    let mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
    let dd = String(tomorrow.getDate()).padStart(2, '0');
    document.getElementById('appointmentDate').value = `${yyyy}-${mm}-${dd}`;
    document.getElementById('bookingModal').style.display = 'flex';
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
}

function confirmBooking() {
    const date = document.getElementById('appointmentDate').value;
    const time = document.getElementById('appointmentTime').value;
    const method = document.getElementById('communicationMethod').value;
    if (!date || !time) { alert('الرجاء اختيار التاريخ والوقت'); return; }
    const today = new Date().toISOString().split('T')[0];
    if (date < today) { alert('لا يمكن حجز موعد في تاريخ سابق'); return; }
    fetch('index.php?page=book_appointment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `specialist_id=${selectedSpecialistId}&date=${date}&time=${time}&method=${method}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { alert('تم إرسال طلب الحجز بنجاح'); location.reload(); }
        else alert('خطأ: ' + (data.error || 'حدث خطأ أثناء الحجز'));
    })
    .catch(err => alert('خطأ في الاتصال بالخادم'));
}

function cancelAppointment(appId) {
    if (confirm('هل أنت متأكد من إلغاء هذا الموعد؟')) {
        fetch('index.php?page=cancel_appointment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'appointment_id=' + appId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { alert('تم الإلغاء بنجاح'); location.reload(); }
            else alert('فشل الإلغاء: ' + (data.error || ''));
        })
        .catch(() => alert('خطأ في الاتصال'));
    }
}

function toggleChat() {
    const chat = document.getElementById('chatPopup');
    chat.style.display = chat.style.display === 'flex' ? 'none' : 'flex';
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    if (input.value.trim()) { alert('تم إرسال رسالتك، سيتم الرد عليك قريباً'); input.value = ''; }
}

function buildCalendar() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month+1, 0).getDate();
    let html = '';
    const dayNames = ['أحد','إثنين','ثلاثاء','أربعاء','خميس','جمعة','سبت'];
    for (let d of dayNames) html += `<span class="day-name">${d}</span>`;
    let startOffset = (firstDay + 1) % 7;
    for (let i=0; i<startOffset; i++) html += `<div class="day empty"></div>`;
    for (let i=1; i<=daysInMonth; i++) html += `<div class="day">${i}</div>`;
    document.getElementById('simpleCalendar').innerHTML = html;
}
buildCalendar();
</script>
<?php include 'views/partials/footer.php'; ?>
