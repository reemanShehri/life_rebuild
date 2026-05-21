<?php
class AidRequest {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

  public function create($userId, $type, $description, $contactPhone, $filePath = null) {
    $stmt = $this->db->prepare("INSERT INTO aid_requests (user_id, request_type, description, contact_phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $type, $description, $contactPhone]);
    $requestId = $this->db->lastInsertId();

    if($filePath) {
        $stmt2 = $this->db->prepare("INSERT INTO documents (request_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt2->execute([$requestId, basename($filePath), $filePath]);
    }
    return $requestId;
}

    public function getByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM aid_requests WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

   public function getAll() {
    $stmt = $this->db->query("SELECT r.*, u.full_name, u.email FROM aid_requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
    return $stmt->fetchAll();
}

    public function updateStatus($id, $status, $assignedTo = null) {
        $sql = "UPDATE aid_requests SET status = ?" . ($assignedTo ? ", assigned_to = ?" : "") . " WHERE id = ?";
        $params = [$status];
        if($assignedTo) $params[] = $assignedTo;
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
?>
