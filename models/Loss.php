<?php
class Loss {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function addLoss($userId, $type, $title, $description, $filePath, $lossDate = null) {
        $stmt = $this->db->prepare("INSERT INTO losses (user_id, document_type, title, description, file_path, loss_date) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $type, $title, $description, $filePath, $lossDate]);
    }

    public function getUserLosses($userId) {
        $stmt = $this->db->prepare("SELECT * FROM losses WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function deleteLoss($id, $userId) {
        // احذف الملف الفعلي أولاً
        $stmt = $this->db->prepare("SELECT file_path FROM losses WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $file = $stmt->fetch();
        if ($file && file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
        $stmt2 = $this->db->prepare("DELETE FROM losses WHERE id = ? AND user_id = ?");
        return $stmt2->execute([$id, $userId]);
    }
}
?>
