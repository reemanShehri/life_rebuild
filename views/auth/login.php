<?php include 'views/partials/header.php'; ?>
<div class="container">
    <h2>تسجيل الدخول</h2>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">دخول</button>
    </form>
    <p>ليس لديك حساب؟ <a href="index.php?page=register">سجل الآن</a></p>
</div>
<?php include 'views/partials/footer.php'; ?>
