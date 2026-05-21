<?php include 'views/partials/header.php'; ?>
<div class="container">
    <h2>طلب مساعدة جديد</h2>
    <form method="POST">
        <div class="form-group">
            <label>نوع المساعدة</label>
            <select name="type" required>
                <option value="document_recovery">استعادة وثائق</option>
                <option value="housing">سكن</option>
                <option value="education">تعليم</option>
                <option value="healthcare">رعاية صحية</option>
                <option value="psychosocial">دعم نفسي</option>
                <option value="other">أخرى</option>
            </select>
        </div>
        <div class="form-group">
            <label>رقم التواصل (واتساب / هاتف)</label>
            <input type="tel" name="contact_phone" placeholder="مثال: 059xxxxxxx" required>
        </div>
        <div class="form-group">
            <label>تفاصيل الطلب</label>
            <textarea name="description" rows="5" required></textarea>
        </div>
        <button type="submit">إرسال الطلب</button>
    </form>
</div>
<?php include 'views/partials/footer.php'; ?>
