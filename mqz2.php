<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$x = 1;

while ($x < 1000) {
    if ($x < 10) {
        $AM = '1-00' . $x;
        $MN = '2-00' . $x;
        $TK = '3-00' . $x;
        $MR = '4-00' . $x;
        $MH = '5-00' . $x;
    } elseif ($x < 100) {
        $AM = '1-0' . $x;
        $MN = '2-0' . $x;
        $TK = '3-0' . $x;
        $MR = '4-0' . $x;
        $MH = '5-0' . $x;
    } else {
        $AM = '1-' . $x;
        $MN = '2-' . $x;
        $TK = '3-' . $x;
        $MR = '4-' . $x;
        $MH = '5-' . $x;
    }
    if ($x < 1000) {
        $user->createRecord('study_id2', array(
            'study_id' => $AM,
            'client_id' => 0,
            'site_id' => 1,
            'status' => 0,
        ));
    }
    if ($x < 1000) {
        $user->createRecord('study_id2', array(
            'study_id' => $MN,
            'client_id' => 0,
            'site_id' => 2,
            'status' => 0,
        ));
    }
    if ($x < 1000) {
        $user->createRecord('study_id2', array(
            'study_id' => $TK,
            'client_id' => 0,
            'site_id' => 3,
            'status' => 0,
        ));
    }
    if ($x < 1000) {
        $user->createRecord('study_id2', array(
            'study_id' => $MR,
            'client_id' => 0,
            'site_id' => 4,
            'status' => 0,
        ));
    }

    if ($x < 1000) {
        $user->createRecord('study_id2', array(
            'study_id' => $MH,
            'client_id' => 0,
            'site_id' => 5,
            'status' => 0,
        ));
    }
    echo $AM . ' : ' . $MN . ' , ' . ' : ' . $TK . ' : ' . $MR . ' : ' . $MH;
    $x++;
}
