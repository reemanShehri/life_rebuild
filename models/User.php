<?php
class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function register($name, $email, $phone, $governorate, $area, $street, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, phone, governorate, area, street_address, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'family')");
        return $stmt->execute([$name, $email, $phone, $governorate, $area, $street, $hashed]);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

//     public function getUserById($id) {
//         $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
//         $stmt->execute([$id]);
//         return $stmt->fetch();
//     }


public function getUserById($id) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

public function updateProfile($id, $full_name, $email, $phone, $governorate, $area, $street_address) {
    $stmt = $this->db->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, governorate = ?, area = ?, street_address = ? WHERE id = ?");
    return $stmt->execute([$full_name, $email, $phone, $governorate, $area, $street_address, $id]);
}

public function updatePassword($id, $new_password) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hashed, $id]);
}
}
?>
