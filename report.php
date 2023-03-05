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
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('search')) {
            $validate = $validate->check($_POST, array(
                'start_date' => array(
                    'required' => true,
                ),
                'end_date' => array(
                    'required' => true,
                ),
                'report' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    switch (Input::get('report')){
                        case 1:
                            $data=$override->searchBtnDate2('clients','enrolled_date',Input::get('start_date'),'enrolled_date',Input::get('end_date'));
                            break;
                        case 2:
                            $data=$override->searchBtnDate2('visit','visit_date',Input::get('start_date'),'visit_date',Input::get('end_date'));
                            break;
                    }
                    $successMessage = 'Position Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Report - PenPlus </title>
    <?php include "head.php"; ?>
</head>

<body>
<div class="wrapper">

    <?php include 'topbar.php' ?>
    <?php include 'menu.php' ?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Report</a> <span class="divider">></span></li>
            </ul>
            <?php include 'pageInfo.php' ?>
        </div>

        <div class="workplace">
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

            <div class="row">
                <?php if ($_GET['id'] == 1 && ($user->data()->position == 1 || $user->data()->position == 2)) { ?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Search Report</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post">
                                <div class="row-form clearfix">
                                    <div class="col-md-1">Start Date:</div>
                                    <div class="col-md-2">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="start_date" id="start_date" /><span>Example: 2010-12-01</span>
                                    </div>
                                    <div class="col-md-1">End Date:</div>
                                    <div class="col-md-2">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="end_date" id="end_date" /><span>Example: 2010-12-01</span>
                                    </div>
                                    <div class="col-md-1">Type</div>
                                    <div class="col-md-2">
                                        <select name="report" style="width: 100%;" required>
                                            <option value="">Select Report</option>
                                            <option value="1">Enrollment</option>
                                            <option value="2">Visit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" name="search" value="Search Report" class="btn btn-info">
                                    </div>
                                </div>

                                <div class="footer tar">

                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Report</h1>
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
                        <div class="block-fluid">
                            <?php if($user->data()->power == 1){
                                $pagNum=0;
                                $pagNum=$override->getCount('clients','status',1);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit('clients', 'status', 1,$page,$numRec);
                            }else{
                                $pagNum=0;
                                $pagNum=$override->getCount('clients','status',1);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit1('clients','site_id',$user->data()->site_id, 'status',1,$page,$numRec);
                            }?>
                            <?php if($_POST && Input::get('report')==1){?>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th width="10%">Screening No</th>
                                        <th width="10%">Initials</th>
                                        <th width="10%">Sex</th>
                                        <th width="10%">Consent Date</th>
                                        <th width="10%">Enrolled</th>
                                        <th width="10%">Enrollment Date</th>
                                        <th width="10%">Enrollment No</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data as $client){?>
                                        <tr>
                                            <td><?=$client['participant_id']?></td>
                                            <td><?=strtoupper(substr($client['firstname'], 0, 1).substr($client['lastname'], 0, 1))?></td>
                                            <td><?=strtoupper(substr($client['gender'], 0, 1))?></td>
                                            <td><?=$client['consent_date']?></td>
                                            <td><?php if($client['enrolled'] == 1){echo 'YES';}else{echo 'NO';}?></td>
                                            <td><?=$client['enrolled_date']?></td>
                                            <td><?=$client['study_id']?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            <?php }elseif ($_POST && Input::get('report')==2){?>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th width="10%">Screening No</th>
                                        <th width="10%">Initials</th>
                                        <th width="10%">Sex</th>
                                        <th width="10%">Visit Date</th>
                                        <th width="10%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data as $visit){
                                        $client=$override->get('clients','id', $visit['client_id'])[0]
                                        ?>
                                        <tr>
                                            <td><?=$client['participant_id']?></td>
                                            <td><?=strtoupper(substr($client['firstname'], 0, 1).substr($client['lastname'], 0, 1))?></td>
                                            <td><?=strtoupper(substr($client['gender'], 0, 1))?></td>
                                            <td><?=$visit['visit_date']?></td>
                                            <td><?php if($visit['status'] == 1){echo 'Done';}else{echo 'Missed';}?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            <?php }else{?>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th width="10%">Screening No</th>
                                        <th width="10%">Initials</th>
                                        <th width="10%">Sex</th>
                                        <th width="10%">Consent Date</th>
                                        <th width="10%">Enrolled</th>
                                        <th width="10%">Enrollment Date</th>
                                        <th width="10%">Enrollment No</th>
                                        <th width="20%">Reason for Ineligibility</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($clients as $client){?>
                                        <tr>
                                            <td><?=$client['participant_id']?></td>
                                            <td><?=strtoupper(substr($client['firstname'], 0, 1).substr($client['lastname'], 0, 1))?></td>
                                            <td><?=strtoupper(substr($client['gender'], 0, 1))?></td>
                                            <td><?=$client['consent_date']?></td>
                                            <td><?php if($client['enrolled'] == 1){echo 'YES';}else{echo 'NO';}?></td>
                                            <td><?=$client['enrolled_date']?></td>
                                            <td><?=$client['study_id']?></td>
                                            <td></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            <?php }?>
                        </div>
                        <?php if(!$_POST){?>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="report.php?id=1&page=<?php if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?>" class="btn btn-default"> < </a>
                                    <?php for($i=1;$i<=$pages;$i++){?>
                                        <a href="report.php?id=1&page=<?=$_GET['id']?>&page=<?=$i?>" class="btn btn-default <?php if($i == $_GET['page']){echo 'active';}?>"><?=$i?></a>
                                    <?php } ?>
                                    <a href="report.php?id=1&page=<?php if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?>" class="btn btn-default"> > </a>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                <?php } ?>
            </div>

            <div class="dr"><span></span></div>
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