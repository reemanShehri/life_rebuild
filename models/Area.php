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



public function getAreasPaginated($limit, $offset) {
    $stmt = $this->db->prepare("
        SELECT a.*, g.name as governorate_name
        FROM areas a
        JOIN governorates g ON a.governorate_id = g.id
        ORDER BY g.name, a.area_name
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// جلب العدد الإجمالي للمناطق
public function getAreasCount() {
    $stmt = $this->db->query("SELECT COUNT(*) as total FROM areas");
    return $stmt->fetch()['total'];
}

// إضافة منطقة جديدة
public function addArea($governorate_id, $area_name, $is_safe, $has_water, $has_electricity, $has_health_center, $has_school, $needs_reconstruction, $safety_level) {
    $stmt = $this->db->prepare("
        INSERT INTO areas (governorate_id, area_name, is_safe, has_water, has_electricity, has_health_center, has_school, needs_reconstruction, safety_level)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$governorate_id, $area_name, $is_safe, $has_water, $has_electricity, $has_health_center, $has_school, $needs_reconstruction, $safety_level]);
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


public function searchAreas($keyword, $limit, $offset) {
    $stmt = $this->db->prepare("
        SELECT a.*, g.name as governorate_name
        FROM areas a
        JOIN governorates g ON a.governorate_id = g.id
        WHERE a.area_name LIKE :keyword
        ORDER BY g.name, a.area_name
        LIMIT :limit OFFSET :offset
    ");
    $keyword = "%$keyword%";
    $stmt->bindParam(':keyword', $keyword);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}


// عدد نتائج البحث
public function countSearchAreas($keyword) {
    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM areas WHERE area_name LIKE :keyword");
    $keyword = "%$keyword%";
    $stmt->bindParam(':keyword', $keyword);
    $stmt->execute();
    return $stmt->fetch()['total'];
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
