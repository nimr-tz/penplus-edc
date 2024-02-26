<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
header('Content-Type: application/json');


$data = array();

$result = $override->getDataRegister4('status', 1, 'eligible', 1);

foreach ($result as $value) {
    $monthname = $value['monthname'];
    $site_id = $value['site_id'];
    $count = $value['count'];

    if (!isset($data[$monthname])) {
        $data[$monthname] = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0);
    }

    $data[$monthname][$site_id] = $count;
}

echo json_encode($data);
