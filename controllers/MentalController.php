<?php
require_once 'models/Mental.php';

class MentalController {
    private $db;
    private $mentalModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->mentalModel = new Mental($pdo);
    }

    public function index() {
        $centers = $this->mentalModel->getAllCenters();
        include 'views/mental/index.php';
    }
}
?>
