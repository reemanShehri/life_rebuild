<?php
// المتغيرات المتاحة: $myRequests (طلبات المستخدم السابقة), $success, $error
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; color: #1e293b; }
    .volunteer-request-hero {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        border-radius: 2rem;
        padding: 2rem;
        text-align: center;
        margin: 1rem 1.5rem 2rem;
        color: white;
    }
    .hero-icon { font-size: 3rem; margin-bottom: 0.5rem; }
    .volunteer-request-hero h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .dashboard-wrapper { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem 3rem; }
    .form-card, .requests-card {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.8rem;
        margin-bottom: 1.2rem;
    }
    .section-header i { font-size: 1.4rem; color: #1e3a8a; }
    .section-header h2 { font-size: 1.3rem; margin: 0; }
    .form-group { margin-bottom: 1.2rem; }
    label { display: block; font-weight: 600; margin-bottom: 0.3rem; font-size: 0.85rem; }
    input, select, textarea {
        width: 100%;
        padding: 0.7rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.8rem;
        font-family: inherit;
    }
    .btn-submit {
        background: #1e3a8a;
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
    }
    .alert {
        padding: 0.8rem;
        border-radius: 1rem;
        margin-bottom: 1rem;
    }
    .alert-success { background: #dcfce7; color: #166534; border-right: 4px solid #16a34a; }
    .alert-error { background: #fee2e2; color: #991b1b; border-right: 4px solid #dc2626; }
    .requests-grid {
        display: grid;
        gap: 1rem;
        max-height: 400px;
        overflow-y: auto;
    }
    .request-item {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1rem;
        border-right: 4px solid;
        transition: 0.2s;
    }
    .request-item.pending { border-right-color: #f59e0b; }
    .request-item.in_progress { border-right-color: #3b82f6; }
    .request-item.completed { border-right-color: #10b981; }
    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    .request-type {
        font-weight: 700;
        font-size: 1rem;
    }
    .request-status {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-in_progress { background: #dbeafe; color: #1e40af; }
    .status-completed { background: #dcfce7; color: #166534; }
    .request-details { font-size: 0.85rem; color: #475569; margin: 0.3rem 0; }
    .request-meta { font-size: 0.7rem; color: #64748b; display: flex; gap: 1rem; margin-top: 0.5rem; }
    .empty-msg { text-align: center; padding: 2rem; color: #64748b; }
    @media (max-width: 640px) {
        .request-header { flex-direction: column; align-items: flex-start; gap: 0.3rem; }
    }
</style>

<div class="volunteer-request-hero">
    <div class="hero-icon"><i class="fas fa-hands-helping"></i></div>
    <h1>طلب مساعدة من المتطوعين</h1>
    <p>املأ النموذج التالي وسنقوم بتوجيه طلبك إلى المنظمات والمتطوعين المناسبين.</p>
</div>



<div style="margin-bottom: 1rem; text-align: right;">
    <a href="index.php?page=volunteer" class="back-to-volunteer" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #e2e8f0; padding: 0.4rem 1rem; border-radius: 2rem; color: #1e293b; text-decoration: none; font-weight: 600;">
        <i class="fas fa-arrow-right"></i> العودة إلى مركز المتطوعين
    </a>
</div>

<main class="dashboard-wrapper">
    <!-- نموذج إرسال الطلب -->
    <div class="form-card">
        <div class="section-header">
            <i class="fas fa-pen-alt"></i>
            <h2>طلب مساعدة جديد</h2>
        </div>
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php elseif (isset($error) && $error): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div>
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

    <!-- عرض طلباتي السابقة -->
    <div class="requests-card">
        <div class="section-header">
            <i class="fas fa-list-ul"></i>
            <h2>طلبات التطوع الخاصة بي</h2>
        </div>
        <?php if (isset($myRequests) && count($myRequests) > 0): ?>
            <div class="requests-grid">
                <?php foreach ($myRequests as $req): ?>
                    <div class="request-item <?= $req['status'] ?>">


                        <div class="request-header">
                            <span class="request-type">
                                <?php
                                    $typeMap = [
                                        'food' => '🍞 طعام ومواد غذائية',
                                        'shelter' => '🏠 إيواء / خيمة',
                                        'medical' => '🏥 مساعدة طبية',
                                        'psychological' => '🧠 دعم نفسي',
                                        'education' => '📚 تعليم / أطفال',
                                        'water' => '💧 مياه / نظافة',
                                        'clothes' => '👕 كسوة / ملابس',
                                        'other' => '📦 أخرى'
                                    ];
                                    echo htmlspecialchars($typeMap[$req['request_type']] ?? $req['request_type']);
                                ?>
                            </span>
                            <span class="request-status status-<?= $req['status'] ?>">
                                <?= ($req['status'] == 'pending') ? '⏳ قيد الانتظار' : (($req['status'] == 'in_progress') ? '⚙️ قيد التنفيذ' : '✅ مكتمل') ?>
                            </span>
                        </div>
                        <div class="request-details"><?= nl2br(htmlspecialchars($req['details'])) ?></div>
                        <div class="request-meta">
                            <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($req['created_at'])) ?></span>
                            <span><i class="fas fa-phone-alt"></i> <?= htmlspecialchars($req['contact_phone']) ?></span>
                        </div>

                        <div class="request-actions" style="margin-top: 0.8rem;">
    <form method="POST" action="index.php?page=cancel_volunteer_request" style="display:inline;">
        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
        <button type="submit" class="btn-cancel" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">🗑️ إلغاء الطلب</button>
    </form>


    <style>
        .btn-cancel {
    background: #fee2e2;
    border: none;
    color: #991b1b;
    padding: 0.3rem 0.8rem;
    border-radius: 2rem;
    cursor: pointer;
    font-size: 0.7rem;
    font-weight: 600;
}
.btn-cancel:hover {
    background: #fecaca;
}
</style>
</div>
                    </div>



                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-msg">
                <i class="far fa-folder-open" style="font-size: 2rem; opacity: 0.5;"></i>
                <p>لم تقم بتقديم أي طلب مساعدة حتى الآن.</p>
                <p>استخدم النموذج أعلاه لتقديم طلبك الأول.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'views/partials/footer.php'; ?>
