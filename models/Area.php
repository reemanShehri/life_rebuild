<?php
class Area {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getGovernorates() {
        $stmt = $this->db->query("SELECT * FROM governorates ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getAreasByGovernorate($governorateId) {
    $stmt = $this->db->prepare("SELECT id, area_name FROM areas WHERE governorate_id = ? ORDER BY area_name");
    $stmt->execute([$governorateId]);
    return $stmt->fetchAll();
}

public function getAreaDetailsByGovId($areaName, $governorateId) {
    $stmt = $this->db->prepare("
        SELECT * FROM areas
        WHERE governorate_id = ? AND area_name = ?
    ");
    $stmt->execute([$governorateId, $areaName]);
    return $stmt->fetch();
}



    public function getAreaDetailsByName($areaName, $governorateName) {
    $stmt = $this->db->prepare("
        SELECT a.* FROM areas a
        JOIN governorates g ON a.governorate_id = g.id
        WHERE g.name = ? AND a.area_name = ?
    ");
    $stmt->execute([$governorateName, $areaName]);
    return $stmt->fetch();
}


    public function getAreaDetails($areaName, $governorateName = null) {
        if ($governorateName) {
            $stmt = $this->db->prepare("
                SELECT a.* FROM areas a
                JOIN governorates g ON a.governorate_id = g.id
                WHERE a.area_name = ? AND g.name = ?
            ");
            $stmt->execute([$areaName, $governorateName]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM areas WHERE area_name = ? LIMIT 1");
            $stmt->execute([$areaName]);
        }
        return $stmt->fetch();
    }
}
?>
