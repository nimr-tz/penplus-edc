<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$x = 1;

//while($x<500){
//    if($x<10){
//        $DA = 'DA00'.$x;
//        $MB = 'MB00'.$x;
//    }elseif($x<100){
//        $DA = 'DA0'.$x;
//        $MB = 'MB0'.$x;
//    }else{
//        $DA = 'DA'.$x;
//        $MB = 'MB'.$x;
//    }
//    $user->createRecord('study_id',array(
//        'study_id' => $DA,
//        'client_id' => 0,
//        'site_id' => 1,
//        'status' => 0,
//    ));
//    $user->createRecord('study_id',array(
//        'study_id' => $MB,
//        'client_id' => 0,
//        'site_id' => 2,
//        'status' => 0,
//    ));
//    echo $DA.' : '. $MB.' , ';
//    $x++;
//}

print_r(date('Y-m-d', strtotime('2023-05-28 + 28 days')));