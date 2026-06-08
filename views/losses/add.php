<?php
// المتغيرات المتاحة: $error, $success (من LossController)
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ========== تصميم النموذج الجديد ========== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; color: #1e293b; }
    .dashboard-wrapper { max-width: 1000px; margin: 0 auto; padding: 1.5rem; }
    .back-link { margin-bottom: 1rem; }
    .back-link a { text-decoration: none; color: #3b82f6; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; }
    .form-header { text-align: center; margin-bottom: 2rem; }
    .form-header h1 { font-size: 2rem; color: #1e3a8a; margin-bottom: 0.5rem; }
    .form-header p { color: #475569; }
    .main-form-card { background: white; border-radius: 1.5rem; padding: 2rem; box-shadow: 0 8px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
    .stepper-container { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; }
    .step { display: flex; align-items: center; gap: 0.8rem; background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 2rem; transition: 0.2s; }
    .step.active { background: #1e3a8a; color: white; }
    .step.active .step-number { background: white; color: #1e3a8a; }
    .step-number { width: 32px; height: 32px; background: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    .step-label { font-weight: 600; font-size: 0.9rem; }
    .step-label span { display: block; font-size: 0.7rem; font-weight: normal; color: #64748b; }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 0.5rem; }
    .form-step { display: none; animation: fade 0.3s ease; }
    .form-step.active-step { display: block; }
    @keyframes fade { from { opacity: 0; } to { opacity: 1; } }
    .section-title { font-size: 1.3rem; margin-bottom: 1.5rem; color: #0f172a; border-right: 4px solid #1e3a8a; padding-right: 0.8rem; }
    .input-group { margin-bottom: 1.2rem; }
    .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    label { display: block; font-weight: 600; margin-bottom: 0.3rem; font-size: 0.85rem; }
    input, select, textarea { width: 100%; padding: 0.7rem; border: 1px solid #cbd5e1; border-radius: 0.8rem; font-family: inherit; }
    .privacy-box { background: #f1f5f9; padding: 1rem; border-radius: 1rem; margin: 1rem 0; }
    .selection-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin: 1rem 0; }
    .selection-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1rem; text-align: center; cursor: pointer; transition: 0.2s; }
    .selection-card.active { border-color: #1e3a8a; background: #eff6ff; }
    .selection-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; }
    .blue-sq { background: #dbeafe; color: #2563eb; }
    .green-sq { background: #dcfce7; color: #16a34a; }
    .purple-sq { background: #f3e8ff; color: #9333ea; }
    .orange-sq { background: #ffedd5; color: #ea580c; }
    .pink-sq { background: #fce7f3; color: #db2777; }
    .upload-zone-box { border: 2px dashed #cbd5e1; border-radius: 1rem; padding: 1.5rem; text-align: center; cursor: pointer; margin-top: 0.5rem; }
    .map-box { background: #f1f5f9; border-radius: 1rem; padding: 1rem; text-align: center; margin: 1rem 0; }
    .form-footer { display: flex; justify-content: space-between; margin-top: 2rem; }
    .btn-next-action, .btn-back-link { background: #1e3a8a; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 2rem; cursor: pointer; font-weight: 600; }
    .btn-back-link { background: #e2e8f0; color: #1e293b; }
    .alert { padding: 0.8rem; border-radius: 1rem; margin-bottom: 1rem; }
    .alert-danger { background: #fee2e2; color: #991b1b; border-right: 4px solid #dc2626; }
    .alert-success { background: #dcfce7; color: #166534; border-right: 4px solid #16a34a; }
    @media (max-width: 640px) {
        .step-label span { display: none; }
        .stepper-container { flex-wrap: nowrap; overflow-x: auto; }
        .step-line { display: none; }
        .input-row { grid-template-columns: 1fr; }
    }
</style>

<main class="dashboard-wrapper">
    <div class="back-link">
        <a href="index.php?page=dashboard"><i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم</a>
    </div>

    <div class="form-header">
        <h1>وثق خسارتك</h1>
        <p>يرجى تقديم معلومات دقيقة. جميع البيانات يتم تخزينها بشكل آمن وسيتم التحقق منها من قبل فريقنا.</p>
    </div>

    <!-- عرض رسائل الخطأ والنجاح -->
    <?php if(isset($error) && $error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if(isset($success) && $success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="main-form-card">
        <!-- الـ Stepper -->
        <div class="stepper-container" id="stepper">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">المعلومات الشخصية<span>بيانات الاتصال</span></div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">نوع الخسارة<span>ما تأثر</span></div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">تفاصيل الممتلكات<span>الوصف والصور</span></div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">الموقع<span>موقع الخريطة</span></div>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" id="lossForm">
            <!-- الخطوة 1: المعلومات الشخصية -->
            <div class="form-step active-step" id="step-1">
                <h3 class="section-title">المعلومات الشخصية</h3>
                <div class="input-group">
                    <label>الاسم الكامل *</label>
                    <input type="text" name="fullname" id="form-fullname" placeholder="أدخل اسمك الكامل" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>
                </div>
                <div class="input-row">
                    <div class="input-group">
                        <label>البريد الإلكتروني *</label>
                        <input type="email" name="email" id="form-email" placeholder="your.email@example.com" required>
                    </div>
                    <div class="input-group">
                        <label>رقم الهاتف *</label>
                        <input type="text" name="phone" id="form-phone" placeholder="+970 XX XXX XXXX" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>حجم العائلة *</label>
                    <input type="number" name="family_size" id="form-familysize" placeholder="عدد أفراد العائلة" required>
                </div>
                <div class="privacy-box">
                    <p><strong>إشعار الخصوصية:</strong> معلوماتك الشخصية مشفرة ومخزنة بشكل آمن، سيتم استخدامها فقط للتحقق وخدمات الدعم.</p>
                </div>
            </div>

            <!-- الخطوة 2: نوع الخسارة -->
            <div class="form-step" id="step-2">
                <h3 class="section-title">نوع الخسارة</h3>
                <p class="section-sub">اختر الفئة التي تصف خسارتك بشكل أفضل. يمكنك تقديم تقارير متعددة لأنواع مختلفة</p>
                <div class="selection-grid">
                    <div class="selection-card" data-type="property_deed">
                        <div class="selection-icon blue-sq"><i class="fas fa-home"></i></div>
                        <span class="selection-text">عقار سكني</span>
                    </div>
                    <div class="selection-card" data-type="vehicle">
                        <div class="selection-icon green-sq"><i class="fas fa-car"></i></div>
                        <span class="selection-text">مركبة</span>
                    </div>
                    <div class="selection-card" data-type="business">
                        <div class="selection-icon purple-sq"><i class="fas fa-building"></i></div>
                        <span class="selection-text">عمل تجاري</span>
                    </div>
                    <div class="selection-card" data-type="personal_item">
                        <div class="selection-icon orange-sq"><i class="fas fa-briefcase"></i></div>
                        <span class="selection-text">ممتلكات شخصية</span>
                    </div>
                    <div class="selection-card" data-type="family_member">
                        <div class="selection-icon pink-sq"><i class="fas fa-heart"></i></div>
                        <span class="selection-text">فرد من العائلة</span>
                    </div>
                </div>
                <input type="hidden" name="document_type" id="selectedLossType" value="property_deed">
            </div>

            <!-- الخطوة 3: وصف الممتلكات -->
            <div class="form-step" id="step-3">
                <h3 class="section-title">وصف الممتلكات</h3>
                <div class="input-group">
                    <label>العنوان (مثال: جواز سفر أحمد، أضرار المنزل) *</label>
                    <input type="text" name="title" id="form-title" required>
                </div>
                <div class="input-group">
                    <label>وصف تفصيلي *</label>
                    <textarea name="description" id="form-description" rows="4" placeholder="قدم أكبر قدر ممكن من التفاصيل؛ ماذا فقد؟ متى حدث ذلك؟ ما هي الحالة قبل الخسارة؟..." required></textarea>
                </div>
                <div class="input-group">
                    <label>القيمة التقديرية (دولار)</label>
                    <input type="number" name="estimated_value" id="form-amount" placeholder="0.00" class="small-input">
                </div>
                <div class="input-group">
                    <label>تاريخ الخسارة (تقريبي)</label>
                    <input type="date" name="loss_date" id="loss-date">
                </div>
                <div class="upload-wrapper">
                    <label class="label-title">تحميل الصور / المستندات *</label>
                    <input type="file" name="document_file" id="actual-file-input" hidden accept="image/*,application/pdf" required>
                    <div class="upload-zone-box" onclick="document.getElementById('actual-file-input').click()">
                        <div class="upload-icon-circle"><i class="fas fa-cloud-upload-alt"></i></div>
                        <p class="upload-text"><span class="blue-text-link">انقر للتحميل</span> أو اسحب وأفلت</p>
                        <span class="upload-specs">PNG, JPG, PDF حتى 10 ميجابايت</span>
                    </div>
                    <p class="upload-footer-note">الصور تساعد في التحقق من خسارتك. يرجى رفع ملف واحد على الأقل.</p>
                </div>
            </div>

            <!-- الخطوة 4: الموقع -->
            <div class="form-step" id="step-4">
                <h3 class="section-title">معلومات الموقع</h3>
                <div class="location-details-group">
                    <div class="input-field-wrapper">
                        <label>المدينة *</label>
                        <input type="text" name="city" id="city-input" placeholder="مثال: غزة، خان يونس..." required>
                    </div>
                    <div class="input-group">
                        <label>عنوان الشارع *</label>
                        <input type="text" name="street_address" id="street-address" placeholder="أدخل عنوان الشارع أو الموقع" required>
                    </div>
                    <div class="input-field-wrapper">
                        <label>الحي / المنطقة *</label>
                        <input type="text" name="district" id="district-input" placeholder="مثال: الرمال، الشيخ رضوان..." required>
                    </div>
                    <div class="input-field-wrapper">
                        <label>رقم البناية (إن وجد)</label>
                        <input type="text" name="building_number" id="building-input" placeholder="مثال: بناية رقم 12، بجانب مسجد...">
                    </div>
                </div>
                <div class="map-box">
                    <div class="map-icon-circle"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>تحديد الموقع (اختياري)</h4>
                    <p>يمكنك استخدام الموقع الحالي أو تركها فارغة</p>
                    <button type="button" class="btn-location" onclick="getRealLocation()">استخدم الموقع الحالي</button>
                </div>
                <div class="input-row">
                    <div class="input-group">
                        <label>خط العرض</label>
                        <input type="text" name="latitude" id="lat-input" readonly>
                    </div>
                    <div class="input-group">
                        <label>خط الطول</label>
                        <input type="text" name="longitude" id="lng-input" readonly>
                    </div>
                </div>
                <div class="ready-banner">
                    <i class="fas fa-check-circle"></i>
                    <p>جاهز للإرسال: يرجى مراجعة جميع المعلومات قبل الإرسال. بمجرد الإرسال، ستتم مراجعة توثيقك خلال 3-5 أيام عمل.</p>
                </div>
            </div>

            <!-- أزرار التنقل -->
            <div class="form-footer">
                <button type="button" class="btn-back-link" id="prevBtn" onclick="changeStep(-1)" style="display: none;"><i class="fas fa-arrow-right"></i> السابق</button>
                <button type="button" class="btn-next-action" id="nextBtn" onclick="changeStep(1)">التالي <i class="fas fa-arrow-left"></i></button>
                <button type="submit" class="btn-next-action" id="submitBtn" style="display: none;">إرسال التوثيق <i class="fas fa-check"></i></button>
            </div>
        </form>


    </div>

      <div style="margin-top: 1rem;">
        <a href="index.php?page=losses" class="btn-back-losses" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #e2e8f0; padding: 0.4rem 1rem; border-radius: 2rem; color: #1e293b; text-decoration: none;">
            <i class="fas fa-arrow-right"></i> رجوع إلى قائمة الخسائر
        </a>
</main>

<script>
    // منطق التنقل بين الخطوات
    let currentStep = 1;
    const totalSteps = 4;

    function updateStepVisibility() {
        for (let i = 1; i <= totalSteps; i++) {
            const stepDiv = document.getElementById(`step-${i}`);
            if (stepDiv) {
                stepDiv.classList.toggle('active-step', i === currentStep);
            }
            const stepperStep = document.querySelector(`.step[data-step="${i}"]`);
            if (stepperStep) {
                if (i === currentStep) stepperStep.classList.add('active');
                else stepperStep.classList.remove('active');
            }
        }
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        if (currentStep === 1) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'inline-flex';
        }
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    function changeStep(delta) {
        const newStep = currentStep + delta;
        if (newStep >= 1 && newStep <= totalSteps) {
            currentStep = newStep;
            updateStepVisibility();
        }
    }

    // اختيار نوع الخسارة
    const cards = document.querySelectorAll('.selection-card');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            cards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const lossType = this.getAttribute('data-type');
            document.getElementById('selectedLossType').value = lossType;
        });
    });
    // تحديد أول خسارة بشكل افتراضي
    if (cards.length) cards[0].classList.add('active');

    // تحديد الموقع الحالي
    function getRealLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                document.getElementById('lat-input').value = position.coords.latitude;
                document.getElementById('lng-input').value = position.coords.longitude;
                alert('تم تحديد موقعك بنجاح');
            }, () => alert('لم نتمكن من تحديد موقعك'));
        } else {
            alert('متصفحك لا يدعم خاصية الموقع');
        }
    }

    // إظهار اسم الملف المختار
    const fileInput = document.getElementById('actual-file-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const uploadZone = document.querySelector('.upload-zone-box');
                uploadZone.innerHTML = `<div class="upload-icon-circle"><i class="fas fa-file-alt"></i></div><p class="upload-text">${fileName}</p>`;
            }
        });
    }

    // تحقق بسيط قبل إرسال النموذج
    document.getElementById('lossForm').addEventListener('submit', function(e) {
        // التحقق من رفع ملف
        if (!fileInput.files.length) {
            alert('الرجاء رفع ملف صورة أو PDF');
            e.preventDefault();
            return;
        }
        // يمكن إضافة تحقق من الحقول المطلوبة
        const required = ['fullname', 'email', 'phone', 'family_size', 'title', 'description', 'city', 'street_address', 'district'];
        for (let field of required) {
            const el = document.querySelector(`[name="${field}"]`);
            if (el && !el.value.trim()) {
                alert(`الرجاء تعبئة حقل ${el.previousElementSibling?.innerText || field}`);
                e.preventDefault();
                return;
            }
        }
    });
</script>

<?php include 'views/partials/footer.php'; ?>
