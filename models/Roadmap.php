<?php
class Roadmap {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // إنشاء مهام افتراضية للمستخدم الجديد
    public function initDefaultTasks($userId) {
        $defaultTasks = [
            ['health', 'فحص طبي شامل', 'pending', date('Y-m-d', strtotime('+1 month'))],
            ['health', 'استشارة نفسية', 'pending', date('Y-m-d', strtotime('+15 days'))],
            ['education', 'تسجيل الأطفال في المدرسة', 'pending', date('Y-m-d', strtotime('+2 month'))],
            ['housing', 'تقديم طلب إعمار', 'pending', date('Y-m-d', strtotime('+3 month'))],
            ['infrastructure', 'صيانة شبكة المياه', 'pending', date('Y-m-d', strtotime('+45 days'))],
            ['employment', 'البحث عن فرصة عمل', 'pending', date('Y-m-d', strtotime('+60 days'))],
        ];

        $stmt = $this->db->prepare("INSERT INTO roadmap_tasks (user_id, task_category, task_name, status, due_date) VALUES (?, ?, ?, ?, ?)");
        foreach ($defaultTasks as $task) {
            $stmt->execute([$userId, $task[0], $task[1], $task[2], $task[3]]);
        }
    }

    // جلب كل مهام المستخدم
    public function getUserTasks($userId) {
        $stmt = $this->db->prepare("SELECT * FROM roadmap_tasks WHERE user_id = ? ORDER BY due_date ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // حساب نسبة الإنجاز
    public function getCompletionPercentage($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed FROM roadmap_tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        if ($row['total'] == 0) return 0;
        return round(($row['completed'] / $row['total']) * 100);
    }

    // تحديث حالة مهمة
    public function updateTaskStatus($taskId, $status) {
        $completed_at = ($status == 'completed') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->db->prepare("UPDATE roadmap_tasks SET status = ?, completed_at = ? WHERE id = ?");
        return $stmt->execute([$status, $completed_at, $taskId]);
    }

    // جلب إحصائيات المهام حسب الفئة
    public function getStatsByCategory($userId) {
        $stmt = $this->db->prepare("SELECT task_category,
                                            COUNT(*) as total,
                                            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                                     FROM roadmap_tasks
                                     WHERE user_id = ?
                                     GROUP BY task_category");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>
