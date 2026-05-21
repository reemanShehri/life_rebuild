<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/mental.css">
<div class="mental-container">
    <div class="mental-hero">
        <h1>🧠 الدعم النفسي</h1>
        <p>رحلة التعافي تبدأ من الداخل. نقدم لك نصائح وإرشادات ومراكز متخصصة لمساعدتك على تجاوز الصدمات وبناء السلام الداخلي.</p>
    </div>

    <!-- النصائح النفسية -->
    <section class="tips-section">
        <h2>✨ نصائح لتعزيز صحتك النفسية</h2>
        <div class="tips-grid">
            <div class="tip-card">💬 تحدث عن مشاعرك مع شخص تثق به - لا تبقَ وحيدًا.</div>
            <div class="tip-card">🧘 مارس تمارين التنفس العميق لمدة 5 دقائق يوميًا.</div>
            <div class="tip-card">📖 اكتب يومياتك - التعبير الكتابي يقلل التوتر.</div>
            <div class="tip-card">🚶 تحرك - المشي لمدة 20 دقيقة يحسن المزاج.</div>
            <div class="tip-card">🌙 حافظ على روتين نوم منتظم.</div>
            <div class="tip-card">🙏 اطلب المساعدة المهنية عند الحاجة - لا تتردد.</div>
        </div>
    </section>

    <!-- المراكز والعيادات -->
    <section class="centers-section">
        <h2>🏥 مراكز الدعم النفسي في غزة</h2>
        <div class="centers-grid">
            <?php foreach ($centers as $center): ?>
            <div class="center-card">
                <div class="center-image">
                    <img src="<?= $center['image_url'] ?>" alt="<?= htmlspecialchars($center['name']) ?>">
                </div>
                <div class="center-info">
                    <h3><?= htmlspecialchars($center['name']) ?></h3>
                    <p class="area">📍 <?= htmlspecialchars($center['area']) ?> | <?= htmlspecialchars($center['address']) ?></p>
                    <p class="hours">🕒 <?= htmlspecialchars($center['working_hours']) ?></p>
                    <p class="phone">📞 <?= htmlspecialchars($center['phone']) ?></p>
                    <p class="services">🩺 <?= nl2br(htmlspecialchars($center['services'])) ?></p>
                    <div class="card-actions">
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $center['phone']) ?>" class="btn-call">📞 اتصل الآن</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($center['address'] . ', ' . $center['area'] . ' غزة') ?>" target="_blank" class="btn-map">🗺️ اتجاهات</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php include 'views/partials/footer.php'; ?>
