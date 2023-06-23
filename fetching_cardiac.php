<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
header('Content-Type: application/json');

$output = array();
$all_generic = $override->get('card_test', 'status', 1);
foreach ($all_generic as $name) {
    $output[] = $name['cardiac'];
}
echo json_encode($output);
