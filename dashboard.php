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
<<<<<<< HEAD
    if ($user->data()->power == 1) {
        $screened = $override->getCount('clients', 'status', 1);
        $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
        $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
        $end = $override->countData('clients', 'status', 0, 'enrolled', 1);
    } else {
        $screened = $override->countData('clients', 'site_id', $user->data()->site_id, 'status', 1);
        $eligible = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'eligible', 1);
        $enrolled = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'enrolled', 1);
        $end = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 0, 'enrolled', 1);
    }
=======
   if($user->data()->power == 1){
       $screened = $override->getNo('clients');
       $enrolled = $override->getCount('clients', 'enrolled', 1);
   }else{
       $screened = $override->getCount('clients', 'site_id', $user->data()->site_id);
       $enrolled = $override->countData('clients', 'enrolled', 1,'site_id', $user->data()->site_id);
   }
>>>>>>> fix navigation
} else {
    Redirect::to('index.php');
}
?>
<<<<<<< HEAD

=======
>>>>>>> fix navigation
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Dashboard - PenPLus</title>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">


            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Dashboard</a> <span class="divider">></span></li>
                </ul>
                <?php include 'pageInfo.php' ?>
            </div>

<<<<<<< HEAD
            <div class="workplace">   
            <?php include 'header.php' ?>          
=======
            <div class="workplace">

                <div class="row">

                    <div class="col-md-4">

                        <div class="wBlock red clearfix">
                            <div class="dSpace">
                                <h3>Screened</h3>
                                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                    <!--130,190,260,230,290,400,340,360,390-->
                                </span>
                                <a href="#">
                                    <span class="number"><?=$screened?></span>
                                </a>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="wBlock green clearfix">
                            <div class="dSpace">
                                <h3>Enrolled</h3>
                                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                    <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                                </span>
                                <a href="#">
                                    <span class="number"><?=$enrolled?></span>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="wBlock blue clearfix">
                            <div class="dSpace">
                                <h3>End of study</h3>
                                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                    <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                </span>
                                <a href="info.php?id=6">
                                    <span class="number">0</span>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
>>>>>>> fix navigation

                <div class="dr"><span></span></div>
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($errorMessage) { ?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?= $errorMessage ?>
                            </div>
                        <?php } elseif ($pageError) { ?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?php foreach ($pageError as $error) {
                                    echo $error . ' , ';
                                } ?>
                            </div>
                        <?php } elseif ($successMessage) { ?>
                            <div class="alert alert-success">
                                <h4>Success!</h4>
                                <?= $successMessage ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Today Schedule</h1>
                                <ul class="buttons">
                                    <li><a href="#" class="isw-download"></a></li>
                                    <li><a href="#" class="isw-attachment"></a></li>
                                    <li>
                                        <a href="#" class="isw-settings"></a>
                                        <ul class="dd-list">
                                            <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                            <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                            <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
<<<<<<< HEAD
                            <?php if ($user->data()->power == 1) {
                                $visits = $override->getNews('visit', 'expected_date', date('Y-m-d'), 'status', 0);
                            } else {
                                $visits = $override->get3('visit', 'expected_date', date('Y-m-d'), 'site_id', $user->data()->site_id, 'status', 0);
                            } ?>
                            <div class="block-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <td width="20">#</td>
                                            <th width="40">Picture</th>
                                            <th width="20%">Screening ID</th>
                                            <th width="20%">Study ID</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Gender</th>
                                            <th width="10%">Age</th>
                                            <th width="30%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x = 1;
                                        foreach ($visits as $visit) {
                                            $client = $override->get('clients', 'id', $visit['client_id'])[0] ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td><?= $x ?></td>
                                                <td width="100">
                                                    <?php if ($client['client_image'] != '' || is_null($client['client_image'])) {
                                                        $img = $client['client_image'];
                                                    } else {
                                                        $img = 'img/users/blank.png';
                                                    } ?>
                                                    <a href="#img<?= $client['id'] ?>" data-toggle="modal"><img src="<?= $img ?>" width="90" height="90" class="" /></a>
                                                </td>
                                                <td><?= $client['participant_id'] ?></td>
                                                <td><?= $client['study_id'] ?></td>
                                                <td> <?= $client['firstname'] . ' ' . $client['lastname'] ?></td>
                                                <td><?= $client['gender'] ?></td>
                                                <td><?= $client['age'] ?></td>
                                                <td>
                                                    <a href="info.php?id=4&cid=<?= $client['id'] ?>" role="button" class="btn btn-warning">Schedule</a>
                                                </td>

                                            </tr>
                                            <div class="modal fade" id="img<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Client Image</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="<?= $img ?>" width="350">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php $x++;
                                        } ?>
=======
                            <?php if($user->data()->power == 1){
                                $visits=$override->getNews('visit', 'visit_date', date('Y-m-d'), 'status', 0);
                            }else {
                                $visits=$override->get3('visit', 'visit_date', date('Y-m-d'), 'site_id',$user->data()->site_id, 'status', 0);
                            }?>
                            <div class="block-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="checkall" /></th>
                                        <td width="20">#</td>
                                        <th width="40">Picture</th>
                                        <th width="20%">Screening ID</th>
                                        <th width="20%">Study ID</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Gender</th>
                                        <th width="10%">Age</th>
                                        <th width="30%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach ($visits as $visit) {$client=$override->get('clients', 'id', $visit['client_id'])[0] ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox" /></td>
                                            <td><?=$x?></td>
                                            <td width="100">
                                                <?php if($client['client_image'] !='' || is_null($client['client_image'])){$img=$client['client_image'];}else{$img='img/users/blank.png';}?>
                                                <a href="#img<?= $client['id'] ?>" data-toggle="modal"><img src="<?=$img?>" width="90" height="90" class=""/></a>
                                            </td>
                                            <td><?=$client['participant_id'] ?></td>
                                            <td><?=$client['study_id'] ?></td>
                                            <td> <?=$client['firstname'] . ' ' . $client['lastname'] ?></td>
                                            <td><?=$client['gender'] ?></td>
                                            <td><?=$client['age'] ?></td>
                                            <td>
                                                <a href="info.php?id=4&cid=<?=$client['id']?>" role="button" class="btn btn-warning" >Schedule</a>
                                            </td>

                                        </tr>
                                        <div class="modal fade" id="img<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Client Image</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="<?=$img?>" width="350">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <?php $x++;} ?>
>>>>>>> fix navigation
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dr"><span></span></div>

                <div class="row">

                </div>
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