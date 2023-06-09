<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$noE = 0;
$noC = 0;
$noD = 0;
$users = $override->getData('user');
if ($user->isLoggedIn()) {

} else {
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Printer - TanCov </title>
    <?php include "head.php"; ?>
</head>

<body>
<div class="wrapper">

    <?php include 'topbar.php' ?>
    <?php include 'menu.php' ?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Printer</a> <span class="divider">></span></li>
            </ul>
            <?php include 'pageInfo.php' ?>
        </div>

        <div class="workplace">

            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong style="font-size: medium">Info!</strong> For the printing function to work, you must use a <strong>Zebra Printer</strong> and make sure <strong>Browser Printing</strong> is Installed and Active
                    </div>
                </div>
                <div class="col-md-offset-1 col-md-8">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Zebra Browser Print</h1>
                    </div>
                    <div class="block-fluid">
                        <div class="row-form clearfix">
                            <div class="col-md-3">Selected Device:</div>
                            <div class="col-md-9">
                                <select id="selected_device" onchange=onDeviceSelected(this);></select>
                            </div>
                        </div>
                        <div class="row-form clearfix">
                            <div class="col-md-3">Number of Labels:</div>
                            <div class="col-md-9">
                                <input value="" class="validate[required]" type="number" name="no_label" id="no_label" />
                            </div>
                        </div>
                        <div class="row-form clearfix">
                            <div class="col-md-3">Study/Screening ID:</div>
                            <div class="col-md-9">
                                <input value="" class="validate[required]" type="text" name="study_id" id="study_id" />
                            </div>
                        </div>

                        <div class="footer tar">
                            <button id="myButton" class="btn btn-info">Print Barcode</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>
        </div>

    </div>
</div>
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
</body>

</html>