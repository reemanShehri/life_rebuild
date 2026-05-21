<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/volunteer.css">
<div class="volunteer-container">
    <div class="volunteer-hero">
        <h1>🤝 طلب مساعدة من المتطوعين</h1>
        <p>املأ النموذج التالي وسنقوم بتوجيه طلبك إلى المنظمات والمتطوعين المناسبين.</p>
    </div>

    <div class="request-form-wrapper">
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">✅ <?= $success ?></div>
        <?php elseif (isset($error) && $error): ?>
            <div class="alert alert-danger">❌ <?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>نوع المساعدة المطلوبة *</label>
                <select name="request_type" required>
                    <option value="">اختر نوع المساعدة</option>
                    <option value="food">🍞 طعام ومواد غذائية</option>
                    <option value="shelter">🏠 إيواء / خيمة</option>
                    <option value="medical">🏥 مساعدة طبية</option>
                    <option value="psychological">🧠 دعم نفسي</option>
                    <option value="education">📚 تعليم / أطفال</option>
                    <option value="water">💧 مياه / نظافة</option>
                    <option value="clothes">👕 كسوة / ملابس</option>
                    <option value="other">📦 أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>تفاصيل الطلب (ماذا تحتاج بالضبط؟)</label>
                <textarea name="details" rows="4" placeholder="مثال: نحتاج خيمة عائلية، مواد تنظيف، دواء لطفل مصاب بالربو..."></textarea>
            </div>
            <div class="form-group">
                <label>رقم التواصل (واتساب / هاتف) *</label>
                <input type="text" name="contact_phone" required placeholder="059xxxxxxx">
            </div>
            <button type="submit" class="btn-submit">📨 إرسال الطلب</button>
        </form>
    </div>
</div>
<?php include 'views/partials/footer.php'; ?>
