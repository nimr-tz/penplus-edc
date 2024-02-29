<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
header('Content-Type: application/json');

$data = array();
$result = $override->getDataRegister5('status', 1);
foreach ($result as $value) {
    $monthname = $value['monthname'];
    $dignosis_type = $value['dignosis_type'];
    $count = $value['count'];

    if (!isset($data[$monthname])) {
        $data[$monthname] = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0);
    }

    $data[$monthname][$dignosis_type] = $count;
}


echo json_encode($data);
