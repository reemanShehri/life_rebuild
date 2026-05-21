<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/profile.css">
<div class="profile-container">
    <h1>👤 ملفي الشخصي</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="profile-card">
        <h2>تعديل البيانات الأساسية</h2>
        <form method="POST">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>المحافظة</label>
                <select name="governorate" id="governorate">
                    <option value="">اختر المحافظة</option>
                    <?php foreach ($governorates as $gov): ?>
                        <option value="<?= $gov['id'] ?>" <?= ($user['governorate'] == $gov['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($gov['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>المنطقة / الحي</label>
                <select name="area" id="area">
                    <option value="">اختر المنطقة</option>
                    <?php foreach ($areas as $a): ?>
                        <option value="<?= htmlspecialchars($a['area_name']) ?>" <?= ($user['area'] == $a['area_name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['area_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>الشارع / العنوان التفصيلي</label>
                <input type="text" name="street_address" value="<?= htmlspecialchars($user['street_address'] ?? '') ?>">
            </div>

            <button type="submit" name="update_profile" class="btn">💾 حفظ التغييرات</button>
        </form>
    </div>

    <div class="profile-card">
        <h2>🔒 تغيير كلمة المرور</h2>
        <form method="POST">
            <div class="form-group">
                <label>كلمة المرور الحالية</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>كلمة المرور الجديدة</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" name="update_password" class="btn">🔑 تغيير كلمة المرور</button>
        </form>
    </div>
</div>

<script>
document.getElementById('governorate').addEventListener('change', function() {
    var govId = this.value;
    var areaSelect = document.getElementById('area');
    if (!govId) {
        areaSelect.innerHTML = '<option value="">اختر المنطقة</option>';
        return;
    }
    fetch('ajax_get_areas.php?gov_id=' + govId)
        .then(response => response.json())
        .then(data => {
            areaSelect.innerHTML = '<option value="">اختر المنطقة</option>';
            data.forEach(area => {
                var option = document.createElement('option');
                option.value = area.area_name;
                option.textContent = area.area_name;
                areaSelect.appendChild(option);
            });
        })
        .catch(error => console.error('خطأ:', error));
});
</script>
<?php include 'views/partials/footer.php'; ?>
