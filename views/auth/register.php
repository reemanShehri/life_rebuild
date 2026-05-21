<?php include 'views/partials/header.php'; ?>
<div class="container">
    <h2>إنشاء حساب جديد</h2>
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>الاسم الكامل</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>رقم الهاتف</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>المحافظة</label>
            <select name="governorate" id="governorate" required onchange="this.form.submit()">
                <option value="">اختر المحافظة</option>
                <?php foreach ($governorates as $gov): ?>
                    <option value="<?= $gov['id'] ?>" <?= (isset($_POST['governorate']) && $_POST['governorate'] == $gov['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($gov['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>المنطقة / الحي</label>
            <select name="area" required>
                <option value="">اختر المنطقة</option>
                <?php foreach ($areas as $areaItem): ?>
                    <option value="<?= htmlspecialchars($areaItem['area_name']) ?>" <?= (isset($_POST['area']) && $_POST['area'] == $areaItem['area_name']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($areaItem['area_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>الشارع / العنوان التفصيلي</label>
            <input type="text" name="street_address" value="<?= htmlspecialchars($_POST['street_address'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit">إنشاء حساب</button>
    </form>
    <p>لديك حساب؟ <a href="index.php?page=login">تسجيل الدخول</a></p>
</div>
<?php include 'views/partials/footer.php'; ?>
