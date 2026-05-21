<?php include 'views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/roadmap.css">
<div class="roadmap-container">
    <div class="roadmap-header">
        <h1>خارطة طريق العودة</h1>
        <p>تأتي خدمات حكومية جديدة نحو العودة أثناء أي ميزان.</p>
    </div>


<div>DATA</div> 
    <?php if (isset($areaDetails) && $areaDetails): ?>
<div class="area-status-card">
    <h4>حالة منطقتك: <?= htmlspecialchars($areaDetails['area_name']) ?></h4>
    <div class="status-grid">
        <span>🔒 الأمان: <?= $areaDetails['safety_level'] == 'high' ? 'آمنة' : ($areaDetails['safety_level'] == 'medium' ? 'متوسطة' : 'غير آمنة') ?></span>
        <span>💧 المياه: <?= $areaDetails['has_water'] ? 'متوفرة' : 'غير متوفرة' ?></span>
        <span>⚡ الكهرباء: <?= $areaDetails['has_electricity'] ? 'متوفرة' : 'غير متوفرة' ?></span>
        <span>🏥 مركز صحي: <?= $areaDetails['has_health_center'] ? 'موجود' : 'غير موجود' ?></span>
    </div>
</div>
<?php endif; ?>

   <div class="family-info-card">
    <h3>عائلة <?= htmlspecialchars($_SESSION['name']) ?></h3>
    <p>
        <?php
        if (isset($user) && $user) {
            $addressParts = [];
            if (!empty($user['governorate'])) $addressParts[] = htmlspecialchars($user['governorate']);
            if (!empty($user['area'])) $addressParts[] = htmlspecialchars($user['area']);
            if (!empty($user['street_address'])) $addressParts[] = htmlspecialchars($user['street_address']);
            echo implode(' - ', $addressParts);
        } else {
            echo 'الرجاء تحديث بيانات العنوان من ملف التعريف';
        }
        ?>
    </p>
    <!-- باقي الكود مثل نسبة الإنجاز والإحصائيات كما هو -->
        <div class="stats-mini">
            <div class="stat"><span>📄</span> <?= count($tasks) ?> مهام</div>
            <div class="stat"><span>✅</span> <?= array_sum(array_column($categoryStats, 'completed')) ?> منجزة</div>
        </div>
    </div>

    <div class="roadmap-grid">
        <div class="tasks-section">
            <h2>النشاطات والمهام</h2>
            <?php foreach ($tasks as $task): ?>
            <div class="task-card <?= $task['status'] ?>">
                <div class="task-info">
                    <span class="task-category">
                        <?php
                            switch($task['task_category']) {
                                case 'health': echo '🏥 صحة'; break;
                                case 'education': echo '📚 تعليم'; break;
                                case 'housing': echo '🏠 سكن'; break;
                                case 'psychological': echo '🧠 دعم نفسي'; break;
                                case 'infrastructure': echo '💧 بنية تحتية'; break;
                                case 'employment': echo '💼 عمل'; break;
                            }
                        ?>
                    </span>
                    <h3><?= htmlspecialchars($task['task_name']) ?></h3>
                    <p>آخر موعد: <?= date('Y/m/d', strtotime($task['due_date'])) ?></p>
                    <?php if($task['status'] == 'completed' && $task['completed_at']): ?>
                        <small>تم الإنجاز: <?= date('Y/m/d', strtotime($task['completed_at'])) ?></small>
                    <?php endif; ?>
                </div>
                <div class="task-status">
                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?= $task['status']=='pending'?'selected':'' ?>>⏳ قيد الانتظار</option>
                            <option value="in_progress" <?= $task['status']=='in_progress'?'selected':'' ?>>⚙️ قيد التنفيذ</option>
                            <option value="completed" <?= $task['status']=='completed'?'selected':'' ?>>✅ منجز</option>
                        </select>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="timeline-section">
            <h2>الحدود الزمنية</h2>
            <ul class="timeline">
                <?php
                $sortedTasks = $tasks;
                usort($sortedTasks, function($a,$b){ return strtotime($a['due_date']) - strtotime($b['due_date']); });
                foreach(array_slice($sortedTasks,0,5) as $task): ?>
                <li class="timeline-item">
                    <span class="date"><?= date('Y/m/d', strtotime($task['due_date'])) ?></span>
                    <span class="task-name"><?= htmlspecialchars($task['task_name']) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'views/partials/footer.php'; ?>
