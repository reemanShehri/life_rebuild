<?php
require_once 'config/database.php';
require_once 'models/Area.php';

$areaModel = new Area($pdo);
$governorates = $areaModel->getGovernorates();

echo "<h2>المحافظات:</h2>";
foreach ($governorates as $gov) {
    echo "ID: {$gov['id']} - Name: {$gov['name']}<br>";
    $areas = $areaModel->getAreasByGovernorate($gov['id']);
    echo "<strong>مناطقها:</strong><br>";
    foreach ($areas as $area) {
        echo "- {$area['area_name']}<br>";
    }
    echo "<br>";
}
?>
