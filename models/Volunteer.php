<?php
class Volunteer {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }



    public function addRequest($userId, $type, $details, $contactMethod, $contactValue) {
    $stmt = $this->db->prepare("INSERT INTO volunteer_requests (user_id, request_type, details, contact_method, contact_phone, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    return $stmt->execute([$userId, $type, $details, $contactMethod, $contactValue]);
}

// public function addRequest($userId, $type, $details, $contactMethod, $contactValue) {
//     $stmt = $this->db->prepare("INSERT INTO volunteer_requests (user_id, request_type, details, contact_method, contact_value, status) VALUES (?, ?, ?, ?, ?, 'pending')");
//     return $stmt->execute([$userId, $type, $details, $contactMethod, $contactValue]);
// }

    public function getUserRequests($userId) {
    $stmt = $this->db->prepare("SELECT * FROM volunteer_requests WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

    // public function getUserRequests($userId) {
    //     $stmt = $this->db->prepare("SELECT * FROM volunteer_requests WHERE user_id = ? ORDER BY created_at DESC");
    //     $stmt->execute([$userId]);
    //     return $stmt->fetchAll();
    // }
    public function getAllOrganizations() {
        $stmt = $this->db->query("SELECT * FROM volunteer_organizations ORDER BY area, name");
        return $stmt->fetchAll();
    }
}
?>
