<?php
class Mental {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllCenters() {
        $stmt = $this->db->query("SELECT * FROM mental_centers ORDER BY area, name");
        return $stmt->fetchAll();
    }
}
?>
