<?php
class Mental {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // جلب جميع المراكز النفسية (موجودة مسبقاً)
    public function getAllCenters() {
        $stmt = $this->db->query("SELECT * FROM mental_centers ORDER BY area");
        return $stmt->fetchAll();
    }

    // جلب الأخصائيين المتاحين
    public function getAllSpecialists() {
        $stmt = $this->db->query("SELECT * FROM mental_specialists WHERE is_available = 1 ORDER BY name");
        return $stmt->fetchAll();
    }




    // جلب جميع مواعيد المستخدم (بدون فلترة)
public function getUserAppointments($userId) {
    $stmt = $this->db->prepare("
        SELECT a.*, s.name as specialist_name, s.specialty
        FROM mental_appointments a
        JOIN mental_specialists s ON a.specialist_id = s.id
        WHERE a.user_id = ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}






    // جلب آخر 4 مقالات (للواجهة الرئيسية)
  public function getAllArticles($limit = 4) {
    // تحويل إلى int للحماية
    $limit = (int)$limit;
    $stmt = $this->db->query("SELECT * FROM mental_articles ORDER BY created_at DESC LIMIT $limit");
    return $stmt->fetchAll();
}

    // جلب الخدمات السريعة (خط الأزمات، جلسات فورية، مكتبة الموارد)
    public function getQuickServices() {
        $stmt = $this->db->query("SELECT * FROM mental_crisis_lines WHERE is_active = 1");
        return $stmt->fetchAll();
    }

    // جلب نصيحة اليوم
    public function getDailyTip() {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT tip_text FROM mental_daily_tips WHERE display_date = ? LIMIT 1");
        $stmt->execute([$today]);
        $tip = $stmt->fetch();
        if (!$tip) {
            // نصيطة احتياطية من قاعدة البيانات (أقدم نصيحة)
            $stmt2 = $this->db->query("SELECT tip_text FROM mental_daily_tips ORDER BY display_date DESC LIMIT 1");
            $tip = $stmt2->fetch();
            if (!$tip) {
                return ['tip_text' => 'الصحة النفسية هي أساس الحياة، اعتني بها يومياً.'];
            }
        }
        return $tip;
    }

    // جلب الحجوزات القادمة لمستخدم معين
    public function getUpcomingAppointments($userId) {
        $stmt = $this->db->prepare("
            SELECT a.*, s.name as specialist_name, s.specialty
            FROM mental_appointments a
            JOIN mental_specialists s ON a.specialist_id = s.id
            WHERE a.user_id = ? AND a.status = 'confirmed' AND a.appointment_date >= CURDATE()
            ORDER BY a.appointment_date, a.appointment_time
            LIMIT 5
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // إضافة حجز جديد
    public function addAppointment($userId, $specialistId, $date, $time, $method) {
        $stmt = $this->db->prepare("
            INSERT INTO mental_appointments (user_id, specialist_id, appointment_date, appointment_time, communication_method, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        return $stmt->execute([$userId, $specialistId, $date, $time, $method]);
    }

    // إلغاء حجز (تحديث الحالة إلى cancelled)
    public function cancelAppointment($appointmentId, $userId) {
    $sql = "UPDATE mental_appointments SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status != 'cancelled' AND status != 'completed'";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$appointmentId, $userId]);
}
}
?>
