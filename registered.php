<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
header('Content-Type: application/json');

$data = array();
$result = $override->getDataRegister('status', 1);
foreach ($result as $value) {
    $site_id = $value['site_id'];
    $count = $value['count'];

    // Step 3: Format data for Charts.js
    $labels = array_column($data, 'site');
    $countData = array_column($data, 'count');

    $chartData = array(
        'labels' => $labels,
        'datasets' => array(
            array(
                'label' => 'Site Counts',
                'data' => $countData,
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            )
        )
    );

    // Step 4: Pass data to Charts.js
    $chartDataJSON = json_encode($chartData);
}
