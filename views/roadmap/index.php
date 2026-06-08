<?php
// المتغيرات المتاحة: $tasks, $areaDetails, $user, $categoryStats
// $tasks: array of task objects with fields: id, task_name, task_category, due_date, status, completed_at
// $areaDetails: has safety_level, has_water, has_electricity, has_health_center, area_name
// $user: has name, governorate, area, street_address
?>
<?php include 'views/partials/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* ========== جميع تنسيقات الصفحة الجديدة (نفس ما سبق) ========== */
    :root {
        --primary-dark: #1e3a8a;
        --primary-light: #3b82f6;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --gray-bg: #f8fafc;
        --border: #e2e8f0;
    }
    body {
        font-family: 'Cairo', sans-serif;
        background: var(--gray-bg);
        color: #1e293b;
    }
    .roadmap-hero {
        background: linear-gradient(135deg, #488545, #70e669);
        /* border-radius: 2rem; */
        padding: 2.5rem 2rem;
        text-align: center;
        margin: 1rem 1.5rem 2rem;
        color: white;
    }
    .hero-icon svg {
        width: 60px;
        height: 60px;
        stroke: white;
        margin-bottom: 1rem;
    }
    .roadmap-hero h1 {
        font-size: 2rem;
        font-weight: 800;
    }
    .roadmap-hero p {
        font-size: 1rem;
        opacity: 0.9;
    }
    .roadmap-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem 3rem;
    }
    .progress-main-card {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
    }
    .progress-top-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .family-header h3 {
        font-size: 1.4rem;
        color: var(--primary-dark);
    }
    .road-sub-text {
        color: #64748b;
        font-size: 0.85rem;
    }
    .percentage-display {
        text-align: center;
    }
    .pct-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-dark);
    }
    .pct-label {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
    }
    .progress-track {
        background: #e2e8f0;
        border-radius: 1rem;
        height: 10px;
        margin: 1rem 0;
    }
    .progress-fill {
        background: var(--success);
        width: 0%;
        height: 10px;
        border-radius: 1rem;
        transition: width 0.3s;
    }
    .status-summary-nodes {
        display: flex;
        justify-content: space-around;
        gap: 1rem;
        margin-top: 1rem;
    }
    .node-item {
        text-align: center;
        flex: 1;
    }
    .node-count {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
    }
    .node-label {
        font-size: 0.8rem;
        color: #64748b;
    }
    .active-node .node-count {
        color: var(--primary-dark);
        border-bottom: 2px solid var(--primary-dark);
    }
    .services-status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .service-status-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid var(--border);
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .icon-box-card {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .blue-icon-bg { background: #dbeafe; color: #2563eb; }
    .yellow-icon-bg { background: #fef3c7; color: #d97706; }
    .green-icon-bg { background: #d1fae5; color: #10b981; }
    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .green-badge { background: #d1fae5; color: #065f46; }
    .yellow-badge { background: #fed7aa; color: #9a3412; }
    .card-body h4 {
        margin: 0.5rem 0 0.2rem;
        font-size: 1.1rem;
    }
    .card-body p {
        font-size: 0.8rem;
        color: #475569;
    }
    .last-update {
        font-size: 0.7rem;
        color: #94a3b8;
        display: block;
        margin-top: 0.5rem;
    }
    .roadmap-grid-layout {
        display: grid;
        grid-template-columns: 1fr 0.9fr;
        gap: 2rem;
    }
    @media (max-width: 900px) {
        .roadmap-grid-layout { grid-template-columns: 1fr; }
    }
    .white-card-box {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        border: 1px solid var(--border);
    }
    .card-header-flex {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.8rem;
        margin-bottom: 1rem;
    }
    .vertical-timeline-container {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }
    .roadmap-step {
        display: flex;
        gap: 1rem;
    }
    .step-marker {
        width: 40px;
        height: 40px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #475569;
    }
    .roadmap-step.done .step-marker { background: #10b981; color: white; }
    .roadmap-step.active .step-marker { background: #3b82f6; color: white; }
    .roadmap-step.locked .step-marker { background: #cbd5e1; color: #64748b; }
    .step-details {
        flex: 1;
    }
    .step-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    .badge-tag {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
    }
    .green-t { background: #d1fae5; color: #065f46; }
    .orange-t { background: #fed7aa; color: #9a3412; }
    .gray-t { background: #f1f5f9; color: #475569; }
    .step-date-text {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 0.3rem;
    }
    .info-card {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
    }
    .safety-list {
        list-style: none;
        padding: 0;
    }
    .safety-list li {
        margin-bottom: 1rem;
    }
    .content-wrapper {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .status-icon {
        font-size: 1.2rem;
    }
    .safety-list li.done .status-icon { color: #10b981; }
    .safety-list li.working .status-icon { color: #f59e0b; }
    .safety-list li.soon .status-icon { color: #94a3b8; }
    .task-name {
        font-weight: 600;
        display: block;
        font-size: 0.9rem;
    }
    .task-date {
        font-size: 0.7rem;
        color: #64748b;
    }
    .mini-calendar {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    .cal-row {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-size: 0.85rem;
    }
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .dot.green { background: #10b981; }
    .dot.blue { background: #3b82f6; }
    .dot.orange { background: #f59e0b; }
    .cal-row p {
        flex: 1;
        margin: 0;
    }
    .date {
        font-size: 0.7rem;
        color: #64748b;
    }
    .bottom-support-banner-v2 {
        background: #f1f5f9;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 2rem;
        text-align: center;
    }
    .support-title-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        margin-bottom: 0.8rem;
    }
    .avatar-circle-blue {
        width: 48px;
        height: 48px;
        background: #dbeafe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 1.5rem;
    }
    .support-actions-row {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1rem;
    }
    .btn-primary-blue {
        background: #1e3a8a;
        color: white;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-outline-gray {
        background: transparent;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        cursor: pointer;
    }
    #fullChatScreen {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 99999;
        direction: rtl;
        font-family: 'Cairo', sans-serif;
        padding: 20px;
    }
    .task-list-simple li {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid #e2e8f0;
    }
</style>

<div class="roadmap-hero">
    <div class="hero-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
        </svg>
    </div>
    <h1>خارطة طريق العودة</h1>
    <p>تابع تقدمك خطوة بخطوة نحو العودة الآمنة إلى منزلك</p>
</div>

<main class="roadmap-wrapper">
    <!-- بطاقة التقدم الرئيسية -->
    <div class="progress-main-card">
        <div class="progress-top-info">
            <div class="family-header">
                <h3>عائلة <?= htmlspecialchars($_SESSION['name'] ?? 'المواطن') ?></h3>
                <p class="road-sub-text">
                    <?php
                    if (isset($user) && $user) {
                        $addr = [];
                        if (!empty($user['governorate'])) $addr[] = htmlspecialchars($user['governorate']);
                        if (!empty($user['area'])) $addr[] = htmlspecialchars($user['area']);
                        if (!empty($user['street_address'])) $addr[] = htmlspecialchars($user['street_address']);
                        echo implode(' - ', $addr);
                    } else {
                        echo 'الرجاء تحديث بيانات العنوان';
                    }
                    ?>
                </p>
            </div>
            <div class="percentage-display">
                <?php
                $totalTasks = count($tasks);
                $completedTasks = 0;
                foreach ($tasks as $t) if ($t['status'] == 'completed') $completedTasks++;
                $percent = $totalTasks ? round(($completedTasks / $totalTasks) * 100) : 0;
                ?>
                <span class="pct-number"><?= $percent ?>%</span>
                <span class="pct-label">التقدم الإجمالي</span>
            </div>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width: <?= $percent ?>%;"></div>
        </div>
        <div class="status-summary-nodes">
            <?php
            $inProgress = 0; $pending = 0;
            foreach ($tasks as $t) {
                if ($t['status'] == 'completed') $completedTasks++;
                elseif ($t['status'] == 'in_progress') $inProgress++;
                else $pending++;
            }
            ?>
            <div class="node-item">
                <span class="node-count"><?= $completedTasks ?></span>
                <span class="node-label">مراحل مكتملة</span>
            </div>
            <div class="node-separator"></div>
            <div class="node-item active-node">
                <span class="node-count"><?= $inProgress ?></span>
                <span class="node-label">قيد التنفيذ</span>
            </div>
            <div class="node-separator"></div>
            <div class="node-item">
                <span class="node-count"><?= $pending ?></span>
                <span class="node-label">معلقة</span>
            </div>
        </div>
    </div>

    <!-- بطاقات الخدمات (المياه، الكهرباء، الأمان) -->
    <div class="services-status-grid">
        <?php
        $waterStatus = (isset($areaDetails['has_water']) && $areaDetails['has_water']) ? 'نشط' : 'غير متوفر';
        $waterBadge = $waterStatus == 'نشط' ? 'green-badge' : 'yellow-badge';
        $electricityStatus = (isset($areaDetails['has_electricity']) && $areaDetails['has_electricity']) ? 'نشط' : 'غير متوفر';
        $elecBadge = $electricityStatus == 'نشط' ? 'green-badge' : 'yellow-badge';
        $safetyLevel = $areaDetails['safety_level'] ?? 'low';
        $safetyText = $safetyLevel == 'high' ? 'آمنة' : ($safetyLevel == 'medium' ? 'متوسطة' : 'غير آمنة');
        $safetyBadge = $safetyLevel == 'high' ? 'green-badge' : ($safetyLevel == 'medium' ? 'yellow-badge' : 'red-badge');
        ?>
        <div class="service-status-card water-card">
            <div class="card-header">
                <div class="icon-box-card blue-icon-bg"><i class="fa-solid fa-droplet"></i></div>
                <span class="status-badge <?= $waterBadge ?>"><?= $waterStatus ?></span>
            </div>
            <div class="card-body">
                <h4>المياه</h4>
                <p><?= $waterStatus == 'نشط' ? 'متصل ويعمل' : 'غير متوفر حالياً' ?></p>
                <span class="last-update">آخر تحديث: من قاعدة البيانات</span>
            </div>
        </div>
        <div class="service-status-card electricity-card">
            <div class="card-header">
                <div class="icon-box-card yellow-icon-bg"><i class="fa-solid fa-bolt"></i></div>
                <span class="status-badge <?= $elecBadge ?>"><?= $electricityStatus ?></span>
            </div>
            <div class="card-body">
                <h4>الكهرباء</h4>
                <p><?= $electricityStatus == 'نشط' ? 'متصل' : 'غير متوفر' ?></p>
                <span class="last-update">آخر تحديث: من قاعدة البيانات</span>
            </div>
        </div>
        <div class="service-status-card security-card">
            <div class="card-header">
                <div class="icon-box-card green-icon-bg"><i class="fa-solid fa-shield-halved"></i></div>
                <span class="status-badge <?= $safetyBadge ?>"><?= $safetyText ?></span>
            </div>
            <div class="card-body">
                <h4>الأمان</h4>
                <p>مستوى الأمان: <?= $safetyText ?></p>
                <span class="last-update">آخر تحديث: من قاعدة البيانات</span>
            </div>
        </div>
    </div>

    <!-- مراحل ومهام + قائمة المهام المتوقعة -->
    <div class="roadmap-grid-layout">
        <!-- العمود الأيمن: مراحل خارطة الطريق (مع إمكانية تغيير الحالة) -->
        <div class="roadmap-stages-side">
            <div class="white-card-box">
                <div class="card-header-flex">
                    <i class="fa-solid fa-route"></i>
                    <h3>مراحل خارطة الطريق</h3>
                </div>
                <div class="vertical-timeline-container">
                    <?php
                    usort($tasks, function($a, $b) { return strtotime($a['due_date']) - strtotime($b['due_date']); });
                    foreach ($tasks as $index => $task):
                        $statusClass = '';
                        $statusBadge = '';
                        $markerContent = '';
                        if ($task['status'] == 'completed') {
                            $statusClass = 'done';
                            $statusBadge = '<span class="badge-tag green-t">مكتمل</span>';
                            $markerContent = '<i class="fa-solid fa-check"></i>';
                        } elseif ($task['status'] == 'in_progress') {
                            $statusClass = 'active';
                            $statusBadge = '<span class="badge-tag orange-t">جاري</span>';
                            $markerContent = ($index+1).'';
                        } else {
                            $statusClass = 'locked';
                            $statusBadge = '<span class="badge-tag gray-t">معلق</span>';
                            $markerContent = '<i class="fa-solid fa-hammer"></i>';
                        }
                    ?>
                    <div class="roadmap-step <?= $statusClass ?>">
                        <div class="step-marker"><?= $markerContent ?></div>
                        <div class="step-details">
                            <div class="step-title-row">
                                <h4><?= htmlspecialchars($task['task_name']) ?></h4>
                                <?= $statusBadge ?>
                            </div>
                            <p><?= htmlspecialchars($task['task_category']) ?></p>
                            <p class="step-date-text">آخر موعد: <?= date('d F Y', strtotime($task['due_date'])) ?></p>

                            <!-- نموذج تغيير الحالة -->
                            <form method="POST" style="margin-top: 0.8rem;">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <div style="display: flex; align-items: center; gap: 0.8rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.8rem; font-weight: bold;">الحالة:</span>
                                    <select name="status" onchange="this.form.submit()" style="padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.8rem; font-weight: bold; border: 1px solid #cbd5e1; background: white;">
                                        <option value="pending" <?= $task['status']=='pending' ? 'selected' : '' ?> style="color: #ef4444;">🔴 قيد الانتظار</option>
                                        <option value="in_progress" <?= $task['status']=='in_progress' ? 'selected' : '' ?> style="color: #f59e0b;">🟠 قيد التنفيذ</option>
                                        <option value="completed" <?= $task['status']=='completed' ? 'selected' : '' ?> style="color: #10b981;">✅ مكتمل</option>
                                    </select>
                                    <?php if($task['status'] == 'completed' && $task['completed_at']): ?>
                                        <span class="step-date-text">(تم في <?= date('d/m/Y', strtotime($task['completed_at'])) ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- العمود الأيسر: فحوصات السلامة + الجدول الزمني + قائمة المهام المتوقعة -->
        <div class="side-column">
            <!-- فحوصات السلامة (كما هي) -->
            <div class="info-card">
                <div class="card-header-flex">
                    <i class="fa-solid fa-shield-halved"></i>
                    <h3>فحوصات السلامة</h3>
                </div>
                <ul class="safety-list">
                    <?php
                    $safetyTasks = array_filter($tasks, function($t) { return stripos($t['task_name'], 'سلامة') !== false || stripos($t['task_category'], 'safety') !== false; });
                    if (empty($safetyTasks)) $safetyTasks = array_slice($tasks, 0, 5);
                    foreach ($safetyTasks as $st):
                        $statusIcon = '';
                        $statusClass = '';
                        $dateText = '';
                        if ($st['status'] == 'completed') {
                            $statusIcon = '<i class="fa-solid fa-circle-check status-icon"></i>';
                            $statusClass = 'done';
                            $dateText = date('d F Y', strtotime($st['completed_at'] ?? $st['due_date']));
                        } elseif ($st['status'] == 'in_progress') {
                            $statusIcon = '<i class="fa-solid fa-clock status-icon"></i>';
                            $statusClass = 'working';
                            $dateText = 'جاري العمل';
                        } else {
                            $statusIcon = '<i class="fa-solid fa-circle-info status-icon"></i>';
                            $statusClass = 'soon';
                            $dateText = 'قريباً';
                        }
                    ?>
                    <li class="<?= $statusClass ?>">
                        <div class="content-wrapper">
                            <?= $statusIcon ?>
                            <div class="text-group">
                                <span class="task-name"><?= htmlspecialchars($st['task_name']) ?></span>
                                <span class="task-date"><?= $dateText ?></span>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- الجدول الزمني (كما هو) -->
            <div class="info-card">
                <div class="card-header-flex">
                    <i class="fa-regular fa-calendar-days"></i>
                    <h3>الجدول الزمني</h3>
                </div>
                <div class="mini-calendar">
                    <?php
                    $upcomingTasks = array_slice($tasks, 0, 6);
                    foreach ($upcomingTasks as $ut):
                        $dotColor = '';
                        if ($ut['status'] == 'completed') $dotColor = 'green';
                        elseif ($ut['status'] == 'in_progress') $dotColor = 'orange';
                        else $dotColor = 'blue';
                    ?>
                    <div class="cal-row">
                        <span class="dot <?= $dotColor ?>"></span>
                        <p><?= htmlspecialchars($ut['task_name']) ?></p>
                        <span class="date"><?= date('d F', strtotime($ut['due_date'])) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- قائمة المهام المتوقعة (كما في الصورة: نقاط حمراء/برتقالية مع التاريخ) -->
            <div class="info-card">
                <div class="card-header-flex">
                    <i class="fa-regular fa-clock"></i>
                    <h3>المهام القادمة</h3>
                </div>
                <ul class="task-list-simple">
                    <?php
                    $pendingTasks = array_filter($tasks, function($t) { return $t['status'] != 'completed'; });
                    usort($pendingTasks, function($a, $b) { return strtotime($a['due_date']) - strtotime($b['due_date']); });
                    foreach ($pendingTasks as $pt):
                        $statusIcon = ($pt['status'] == 'pending') ? '🔴' : '🟠';
                        $statusText = ($pt['status'] == 'pending') ? 'قيد الانتظار' : 'قيد التنفيذ';
                    ?>
                    <li>
                        <span style="font-size: 1.2rem;"><?= $statusIcon ?></span>
                        <div style="flex:1;">
                            <strong style="font-size: 0.9rem;"><?= htmlspecialchars($pt['task_name']) ?></strong>
                            <div style="font-size: 0.7rem; color: #64748b;">تاريخ <?= date('Y/m/d', strtotime($pt['due_date'])) ?></div>
                        </div>
                        <span style="font-size: 0.7rem; color: <?= $pt['status']=='pending' ? '#ef4444' : '#f59e0b' ?>;"><?= $statusText ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- بانر الدعم والشات -->
    <div class="bottom-support-banner-v2">
        <div class="support-title-row">
            <div class="avatar-circle-blue"><i class="fa-solid fa-users-gear"></i></div>
            <h4>فريق الدعم المخصص</h4>
        </div>
        <p class="support-desc">فريقنا متاح لمساعدتك في كل خطوة من رحلة العودة. لا تتردد في التواصل إذا كانت لديك أي أسئلة أو مخاوف.</p>
        <div class="support-actions-row">
            <button class="btn-primary-blue" onclick="contactStatusManager()">اتصل بمسؤول الحالة</button>
            <button class="btn-outline-gray" onclick="requestEdit()">طلب تحديث</button>
        </div>
    </div>
</main>

<!-- شات الدعم -->
<div id="fullChatScreen" style="display: none;">
    <div style="background: white; border-radius: 12px; width: 100%; max-width: 650px; box-shadow: 0 5px 25px rgba(0,0,0,0.3); overflow: hidden; display: flex; flex-direction: column;">
        <div style="background: #1e8a25; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 40px; height: 40px; background: #3b82f6; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold;">سح</div>
                <div><h4 style="margin:0;">  (مسؤولة الحالة)</h4><span style="font-size:12px; color:#4ade80;">● متصلة الآن</span></div>
            </div>
            <button onclick="closeChat()" style="background:#ef4444; border:none; color:white; padding:8px 15px; border-radius:6px; cursor:pointer;">إغلاق الشات ✖</button>
        </div>
        <div id="fullChatBody" style="height: 320px; padding: 20px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 15px;">
            <div style="align-self: flex-start; max-width: 75%; background: white; padding: 12px 15px; border-radius: 0 12px 12px 12px; border:1px solid #e2e8f0;">
                <p>مرحباً عائلة <span id="chat-family-name"><?= htmlspecialchars($_SESSION['name'] ?? 'المواطن') ?></span>، معكم مسؤولة الحالة  . كيف يمكنني مساعدتكم اليوم؟</p>
                <span style="display:block; text-align:left; font-size:10px; color:#94a3b8;"><?= date('h:i A') ?></span>
            </div>
        </div>
        <div style="padding: 15px; background: white; border-top:1px solid #e2e8f0; display: flex; gap: 10px;">
            <input type="text" id="fullChatInput" placeholder="اكتب رسالتك   هنا..." style="flex:1; padding:12px; border:1px solid #cbd5e1; border-radius:8px;">
            <button onclick="sendFullChatMessage()" style="background:#1e3a8a; color:white; border:none; padding:0 20px; border-radius:8px;">إرسال <i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    function contactStatusManager() {
        document.getElementById('fullChatScreen').style.display = 'flex';
    }
    function closeChat() {
        document.getElementById('fullChatScreen').style.display = 'none';
    }
    function sendFullChatMessage() {
    let input = document.getElementById('fullChatInput');
    let msg = input.value.trim();
    if (msg === '') return;
    let chatBody = document.getElementById('fullChatBody');
    let time = new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});

    // رسالة المستخدم
    let msgDiv = document.createElement('div');
    msgDiv.style.alignSelf = 'flex-end';
    msgDiv.style.maxWidth = '75%';
    msgDiv.style.background = '#1e3a8a';
    msgDiv.style.color = 'white';
    msgDiv.style.padding = '10px 15px';
    msgDiv.style.borderRadius = '12px 0 12px 12px';
    msgDiv.style.marginBottom = '8px';
    msgDiv.innerHTML = `<p style="margin:0;">${escapeHtml(msg)}</p><span style="display:block; text-align:right; font-size:9px; opacity:0.8;">${time}</span>`;
    chatBody.appendChild(msgDiv);
    input.value = '';
    chatBody.scrollTop = chatBody.scrollHeight;

    // رد تلقائي مع معلومات الاتصال
    setTimeout(() => {
        let reply = document.createElement('div');
        reply.style.alignSelf = 'flex-start';
        reply.style.maxWidth = '85%';
        reply.style.background = 'white';
        reply.style.border = '1px solid #e2e8f0';
        reply.style.padding = '12px 15px';
        reply.style.borderRadius = '0 12px 12px 12px';
        reply.innerHTML = `
            <p style="margin:0 0 5px 0;"><strong>شكراً لتواصلك مع فريق الدعم النفسي</strong></p>
            <p style="margin:0 0 8px 0; font-size:13px;">سنقوم بالرد عليك في أقرب وقت ممكن. يمكنك أيضاً التواصل مباشرة عبر:</p>
            <div style="margin:5px 0; display:flex; flex-direction:column; gap:5px;">
                <a href="https://wa.me/970599123456" target="_blank" style="display:inline-flex; align-items:center; gap:8px; background:#25D366; color:white; padding:6px 12px; border-radius:25px; text-decoration:none; font-size:12px;">
                    <i class="fab fa-whatsapp"></i> واتساب الدعم: 0599 123 456
                </a>
                <a href="mailto:support@life-rebuild.ps" style="display:inline-flex; align-items:center; gap:8px; background:#1e3a8a; color:white; padding:6px 12px; border-radius:25px; text-decoration:none; font-size:12px;">
                    <i class="far fa-envelope"></i> support@life-rebuild.ps
                </a>
            </div>
            <span style="display:block; text-align:left; font-size:9px; color:#94a3b8; margin-top:8px;">${time}</span>
        `;
        chatBody.appendChild(reply);
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 1000);
}
    function escapeHtml(str) {
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    function requestEdit() {
        alert('سيتم فتح نموذج لطلب تحديث البيانات قريباً');
    }
    document.getElementById('chat-family-name').innerText = '<?= htmlspecialchars($_SESSION['name'] ?? 'المواطن') ?>';
</script>

<?php include 'views/partials/footer.php'; ?>
