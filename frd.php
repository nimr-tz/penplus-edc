<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$x = 1;

while ($x <= 200) {
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
    if ($x <= 200) {
        $user->createRecord('study_id', array(
            'study_id' => $KND,
            'client_id' => 0,
            'site_id' => 1,
            'status' => 0,
        ));
    }
    if ($x <= 200) {
        $user->createRecord('study_id', array(
            'study_id' => $KRT,
            'client_id' => 0,
            'site_id' => 2,
            'status' => 0,
        ));
    }
    echo $KND . ' : ' . $KRT;
    $x++;
}
