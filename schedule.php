<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec=15;
if ($user->isLoggedIn()) {
    $timestamp = time();
    $file = 'frdrck';
    $filename = $file.'_' . $timestamp . '.xlsx';

//    header("Content-Type: application/vnd.ms-excel");
//    header("Content-Disposition: attachment; filename=\"$filename\"");
    if (Input::exists('post')) {
        $validate = new validate();

    }
} else {
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Info - PenPLus </title>
    <?php include "head.php"; ?>

</head>

<body>
<div class="row">
    <div class="col-md-12">
        <?php $patient = $override->get('clients', 'id', $_GET['cid'])[0]?>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right"><button role="button" class="btn btn-info" onclick="window.print()" >Print</button></div>
                <div class="head clearfix">
                    <div class="isw-grid"></div>
                    <h1>Visit Schedule</h1>
                </div>
                <div class="block-fluid">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Visit Name</th>
                            <th width="5%">Visit Code</th>
                            <th width="10%">Visit Type</th>
                            <th width="10%">Visit Date(Exact)</th>
                            <th width="10%">Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $x=1;foreach ($override->get('visit', 'client_id', $_GET['cid']) as $visit) {
//                                                ****************** fix unscheduled visit button showing up
                            $sch = $override->get('schedule','visit',$visit['visit_code'])[0];
                            if($sch){$schedule=$sch['window'];}else{$schedule=0;}
                            $lower=date('Y-m-d', strtotime($visit['visit_date']. ' - '.$schedule.' days'));
                            $upper=date('Y-m-d', strtotime($visit['visit_date']. ' + '.$schedule.' days'));
                            $msv=$user->dateDiff($upper,date('Y-m-d'));
                            if($visit['visit_code'] == 'V1' || $visit['visit_code'] == 'V2'){$v_typ='Screening';}elseif ($visit['visit_code'] == 'V3'){$v_typ='Enrollment';}else{$v_typ='Follow Up';}?>
                            <tr>
                                <td><?=$x?></td>
                                <td> <?=$visit['visit_name'] ?></td>
                                <td> <?=$visit['visit_code'] ?></td>
                                <td> <?=$v_typ ?></td>
                                <?php if($visit['visit_code'] == 'V1' || $visit['visit_code'] == 'V2' || $visit['visit_code'] == 'V3'){?>
                                    <td> <strong style="color: darkgreen"><?=$visit['visit_date'] ?></strong></td>
                                <?php }else{if($msv > 0){$dur=$msv;}else{$dur='-';}?>
                                    <td> <strong style="color: coral"><?=$visit['visit_date'] ?></strong></td>
                                <?php }?>
                                <td></td>
                            </tr>
                            <?php $x++;} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
<script>
    <?php if ($user->data()->pswd == 0) { ?>
    $(window).on('load', function() {
        $("#change_password_n").modal({
            backdrop: 'static',
            keyboard: false
        }, 'show');
    });
    <?php } ?>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>
