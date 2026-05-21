<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/volunteer.css">
<div class="volunteer-container">
    <div class="volunteer-hero">
        <h1>🤝 تنسيق المتطوعين والمنظمات</h1>
        <p>تواصل مع الجهات الإنسانية لتلقي المساعدة المناسبة. اختر الخدمة التي تحتاجها، أو اطلب مساعدة ميدانية فورية.</p>
    </div>

    <div class="request-card">
        <h2>📢 طلب مساعدة ميدانية</h2>
        <form method="POST" action="index.php?page=volunteer_request">
            <div class="form-group">
                <label>نوع المساعدة المطلوبة</label>
                <select name="request_type" required>
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
            <div class="form-group">
                <label>التفاصيل (الكمية، العنوان، ملاحظات)</label>
                <textarea name="details" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>طريقة التواصل المفضلة</label>
                <select name="preferred_contact">
                    <option value="phone">📞 اتصال هاتفي</option>
                    <option value="whatsapp">💬 واتساب</option>
                    <option value="in_app">📱 رسالة داخل المنصة (قريباً)</option>
                </select>
            </div>
            <div class="form-group">
                <label>رقم التواصل (واتساب/هاتف)</label>
                <input type="text" name="contact_info" placeholder="مثال: 059xxxxxxx" required>
            </div>
            <button type="submit" class="btn-request">إرسال طلب المساعدة 🚀</button>
            <?php if(isset($success)) echo '<div class="alert success">'.$success.'</div>'; ?>
            <?php if(isset($error)) echo '<div class="alert error">'.$error.'</div>'; ?>
        </form>
    </div>

    <div class="organizations-section">
        <h2>🏢 منظمات إنسانية ومتطوعون نشطون</h2>
        <div class="org-grid">
            <?php foreach ($organizations as $org): ?>
            <div class="org-card">
<div class="org-logo">🏢</div>                <h3><?= htmlspecialchars($org['name']) ?></h3>
                <p>📍 <?= htmlspecialchars($org['area']) ?></p>
                <p>🩺 <?= htmlspecialchars(substr($org['services'],0,80)) ?>...</p>
                <div class="org-contact">
                    <?php if($org['phone']): ?>
                        <a href="tel:<?= $org['phone'] ?>">📞 اتصل</a>
                    <?php endif; ?>
                    <?php if($org['whatsapp']): ?>
                        <a href="https://wa.me/<?= $org['whatsapp'] ?>" target="_blank">💬 واتساب</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include 'views/partials/footer.php'; ?>
