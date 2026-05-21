<?php
require_once 'config/database.php';
require_once 'models/Area.php';

header('Content-Type: application/json');

if (!isset($_GET['gov_id']) || empty($_GET['gov_id'])) {
    echo json_encode([]);
    exit;
}

$govId = (int)$_GET['gov_id'];
$areaModel = new Area($pdo);
$areas = $areaModel->getAreasByGovernorate($govId);

echo json_encode($areas);
?>
