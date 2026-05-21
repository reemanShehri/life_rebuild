<?php include 'views/partials/header.php'; ?>
<div class="container">
    <h2>📎 توثيق خسارة جديدة</h2>
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>نوع المستند / الخسارة</label>
            <select name="document_type" required>
                <option value="id_card">🪪 هوية شخصية</option>
                <option value="property_deed">🏠 سند ملكية / عقد إيجار</option>
                <option value="medical_report">🏥 تقرير طبي</option>
                <option value="education_cert">🎓 شهادة دراسية</option>
                <option value="house_damage">🏚️ ضرر في المنزل / ممتلكات</option>
                <option value="personal_item">💍 ممتلكات شخصية (مجوهرات، أجهزة...)</option>
                <option value="other">📄 أخرى</option>
            </select>
        </div>
        <div class="form-group">
            <label>العنوان (مثال: جواز سفر أحمد، أضرار المنزل)</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>الوصف التفصيلي</label>
            <textarea name="description" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label>تاريخ الخسارة (تقريبي)</label>
            <input type="date" name="loss_date">
        </div>
        <div class="form-group">
            <label>رفع ملف (صورة أو PDF)</label>
            <input type="file" name="document_file" accept="image/*,application/pdf" required>
        </div>
        <button type="submit" class="btn">💾 حفظ التوثيق</button>
    </form>
</div>
<?php include 'views/partials/footer.php'; ?>
