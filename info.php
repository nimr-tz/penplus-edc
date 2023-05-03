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
        if (Input::get('edit_position')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('position', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Position Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_staff')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'position' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                ),
                'email_address' => array(),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '12345678';
                switch (Input::get('position')) {
                    case 1:
                        $accessLevel = 1;
                        break;
                    case 2:
                        $accessLevel = 2;
                        break;
                    case 3:
                        $accessLevel = 3;
                        break;
                }
                try {
                    $user->updateRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                        'user_id' => $user->data()->id,
                    ), Input::get('id'));

                    $successMessage = 'Account Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('reset_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Reset Successful';
        }
        elseif (Input::get('unlock_account')) {
            $user->updateRecord('user', array(
                'count' => 0,
            ), Input::get('id'));
            $successMessage = 'Account Unlock Successful';
        }
        elseif (Input::get('delete_staff')) {
            $user->updateRecord('user', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'User Deleted Successful';
        }
        elseif (Input::get('edit_study')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'code' => array(
                    'required' => true,
                ),
                'sample_size' => array(
                    'required' => true,
                ),
                'start_date' => array(
                    'required' => true,
                ),
                'end_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('study', array(
                        'name' => Input::get('name'),
                        'code' => Input::get('code'),
                        'sample_size' => Input::get('sample_size'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                    ), Input::get('id'));
                    $successMessage = 'Study Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_site')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('site', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Site Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_client')) {
            $validate = $validate->check($_POST, array(
                'participant_id' => array(
                    'required' => true,
                ),
                'clinic_date' => array(
                    'required' => true,
                ),
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'dob' => array(
                    'required' => true,
                ),
                'street' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $attachment_file = Input::get('image');
                    if (!empty($_FILES['image']["tmp_name"])) {
                        $attach_file = $_FILES['image']['type'];
                        if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif") {
                            $folderName = 'clients/';
                            $attachment_file = $folderName . basename($_FILES['image']['name']);
                            if (@move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
                                $file = true;
                            } else {
                                {
                                    $errorM = true;
                                    $errorMessage = 'Your profile Picture Not Uploaded ,';
                                }
                            }
                        } else {
                            $errorM = true;
                            $errorMessage = 'None supported file format';
                        }//not supported format
                    }else{
                        $attachment_file = '';
                    }
                    if (!empty($_FILES['image']["tmp_name"])) {
                        $image = $attachment_file;
                    }else{
                        $image = Input::get('client_image');
                    }
                    if($errorM == false){
                        $age = $user->dateDiffYears(date('Y-m-d'),Input::get('dob'));
                        $user->updateRecord('clients', array(
                            'participant_id' => Input::get('participant_id'),
                            'clinic_date' => Input::get('clinic_date'),
                            'firstname' => Input::get('firstname'),
                            'midlename' => Input::get('midlename'),
                            'lastname' => Input::get('lastname'),
                            'dob' => Input::get('dob'),
                            'age' => $age,
                            'id_number' => Input::get('id_number'),
                            'gender' => Input::get('gender'),
                            'marital_status' => Input::get('marital_status'),
                            'education_level' => Input::get('education_level'),
                            'workplace' => Input::get('workplace'),
                            'occupation' => Input::get('occupation'),
                            'phone_number' => Input::get('phone_number'),
                            'other_phone' => Input::get('other_phone'),
                            'street' => Input::get('street'),
                            'ward' => Input::get('ward'),
                            'block_no' => Input::get('block_no'),
                            'client_image' => $image,
                            'comments' => Input::get('comments'),
                        ), Input::get('id'));

                        $successMessage = 'Client Updated Successful';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_status' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'visit_date' => Input::get('visit_date'),
                        'created_on' => date('Y-m-d'),
                        'status' => 1,
                        'visit_status' => Input::get('visit_status'),
                    ), Input::get('id'));

                    if(Input::get('seq') == 2){
                        $user->createRecord('visit', array(
                            'visit_name' => 'Visit 3',
                            'visit_code' => 'V3',
                            'visit_window' => 14,
                            'status' => 0,
                            'seq_no' => 3,
                            'client_id' => Input::get('cid'),
                        ));
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_screening')){
            $validate = $validate->check($_POST, array(

            ));
            if ($validate->passed()) {
                $eligibility=0;
                if(Input::get('age_18')==1 && Input::get('tr_pcr')==1 && Input::get('moderate_severe')==1 && Input::get('peptic_ulcers')==2 && Input::get('consented')==1 && (Input::get('pregnant')==2 || Input::get('pregnant')==3)){
                    $eligibility=1;
                }
                try {
                    if($override->get('screening','client_id',Input::get('cid'))){
                        $cl_id=$override->get('screening','client_id',Input::get('cid'))[0]['id'];
                        $user->updateRecord('screening', array(
                            'sample_date' => Input::get('sample_date'),
                            'results_date' => Input::get('results_date'),
                            'covid_result' => Input::get('covid_result'),
                            'age_18' => Input::get('age_18'),
                            'tr_pcr' => Input::get('tr_pcr'),
                            'hospitalized' => Input::get('hospitalized'),
                            'moderate_severe' => Input::get('moderate_severe'),
                            'peptic_ulcers' => Input::get('peptic_ulcers'),
                            'pregnant' => Input::get('pregnant'),
                            'eligibility' => $eligibility,
                            'consented' => Input::get('consented'),
                            'created_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                            'site_id' => $user->data()->site_id,
                            'status'=> 1,
                            'client_id' => Input::get('cid'),
                        ),$cl_id);
                    }else{
                        $user->createRecord('screening', array(
                            'sample_date' => Input::get('sample_date'),
                            'results_date' => Input::get('results_date'),
                            'covid_result' => Input::get('covid_result'),
                            'age_18' => Input::get('age_18'),
                            'tr_pcr' => Input::get('tr_pcr'),
                            'hospitalized' => Input::get('hospitalized'),
                            'moderate_severe' => Input::get('moderate_severe'),
                            'peptic_ulcers' => Input::get('peptic_ulcers'),
                            'pregnant' => Input::get('pregnant'),
                            'eligibility' => $eligibility,
                            'consented' => Input::get('consented'),
                            'created_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                            'site_id' => $user->data()->site_id,
                            'status'=> 1,
                            'client_id' => Input::get('cid'),
                        ));
                    }
                    $user->updateRecord('clients',array('consented'=>Input::get('consented')),Input::get('cid'));
                    $successMessage = 'Screening Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_lab')){
            $validate = $validate->check($_POST, array(

            ));
            if ($validate->passed()) {
                $eligibility=0;
                $clnt=$override->get('clients','id',Input::get('cid'))[0];
                $sc_e=$override->get('screening', 'client_id', Input::get('cid'))[0];
                $std_id=$override->getNews('study_id','site_id',$user->data()->site_id,'status', 0)[0];
                if((Input::get('wbc')>=1.5 && Input::get('wbc')<=11.0) && (Input::get('hb')>=8.5 && Input::get('hb')<=16.5)
                    && (Input::get('plt')>=50 && Input::get('plt')<=1000) && (Input::get('alt')>=2.0 && Input::get('alt')<=195.0)
                    && (Input::get('ast')>=2.0 && Input::get('ast')<=195.0)){
                    if($clnt['gender']=='male' && (Input::get('sc')>=44.0 && Input::get('sc')<=158.4) && $sc_e['eligibility'] == 1){
                        $eligibility=1;
                        if($override->getCount('visit','client_id', Input::get('cid')) == 1){
                            $user->visit(Input::get('cid'), 0);
                            $user->updateRecord('study_id',array('status'=>1,'client_id'=>Input::get('cid')),$std_id['id']);
                            $user->updateRecord('clients',array('study_id'=>$std_id['study_id'],'enrolled'=>1),Input::get('cid'));
                        }
                    }elseif ($clnt['gender']=='female' && (Input::get('sc')>=62.0 && Input::get('sc')<=190.8) && $sc_e['eligibility'] == 1){
                        $eligibility=1;
                        if($override->getCount('visit','client_id', Input::get('cid')) == 1){
                            $user->visit(Input::get('cid'), 0);
                            $user->updateRecord('study_id',array('status'=>1,'client_id'=>Input::get('cid')),$std_id['id']);
                            $user->updateRecord('clients',array('study_id'=>$std_id['study_id'],'enrolled'=>1),Input::get('cid'));
                        }
                    }
                }
                try {
                    if($override->get('lab','client_id',Input::get('cid'))){
                        $l_id=$override->get('lab','client_id',Input::get('cid'))[0]['id'];
                        $user->updateRecord('lab', array(
                            'wbc' => Input::get('wbc'),
                            'hb' => Input::get('hb'),
                            'plt' => Input::get('plt'),
                            'alt' => Input::get('alt'),
                            'ast' => Input::get('ast'),
                            'sc' => Input::get('sc'),
                            'eligibility' => $eligibility,
                            'created_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                            'site_id' => $user->data()->site_id,
                            'status'=> 1,
                            'client_id' => Input::get('cid'),
                        ),$l_id);
                    }else{
                        $user->createRecord('lab', array(
                            'wbc' => Input::get('wbc'),
                            'hb' => Input::get('hb'),
                            'plt' => Input::get('plt'),
                            'alt' => Input::get('alt'),
                            'ast' => Input::get('ast'),
                            'sc' => Input::get('sc'),
                            'eligibility' => $eligibility,
                            'created_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                            'site_id' => $user->data()->site_id,
                            'status'=> 1,
                            'client_id' => Input::get('cid'),
                        ));
                    }

                    $successMessage = 'Screening Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_status' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'visit_date' => Input::get('visit_date'),
                        'created_on' => date('Y-m-d'),
                        'status' => 1,
                        'visit_status' => Input::get('visit_status'),
                        'reasons' => Input::get('reasons'),
                    ), Input::get('id'));

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('search_by_site')){
            $validate = $validate->check($_POST, array(
                'site' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $url = 'info.php?id=3&sid='.Input::get('site');
                Redirect::to($url);
                $pageError = $validate->errors();
            }
        }

        if($_GET['id'] == 6){
            $data = null;
            $filename = null;
            if(Input::get('clients')){
                $data = $override->getData('clients');
                $filename = 'Clients';
            }elseif (Input::get('visits')){
                $data = $override->getData('visit');
                $filename = 'Visits';
            }elseif (Input::get('lab')){
                $data = $override->getData('lab');
                $filename = 'Laboratory Results';
            }
            elseif (Input::get('study_id')){
                $data = $override->getData('study_id');
                $filename = 'Study IDs';
            }
            elseif (Input::get('sites')){
                $data = $override->getData('site');
                $filename = 'Sites';
            }
            elseif (Input::get('screening')){
                $data = $override->getData('screening');
                $filename = 'Screening';
            }
            $user->exportData($data, $filename);
        }
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
<div class="wrapper">

    <?php include 'topbar.php' ?>
    <?php include 'menu.php' ?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Info</a> <span class="divider">></span></li>
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
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Staff</h1>
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
                                $user=$override->get('user', 'status', 1);
                            }else{
                                $users=$override->getNews('user', 'site_id',$user->data()->site_id, 'status',1);
                            }?>
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall" /></th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Username</th>
                                    <th width="20%">Position</th>
                                    <th width="20%">Site</th>
                                    <th width="20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('user', 'status', 1) as $staff) {
                                    $position = $override->get('position', 'id', $staff['position'])[0];
                                    $site = $override->get('site', 'id', $staff['site_id'])[0] ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox" /></td>
                                        <td> <?=$staff['firstname'] . ' ' . $staff['lastname'] ?></td>
                                        <td><?=$staff['username'] ?></td>
                                        <td><?=$position['name'] ?></td>
                                        <td><?=$site['name'] ?></td>
                                        <td>
                                            <a href="#user<?= $staff['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                            <a href="#reset<?= $staff['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Reset</a>
                                            <a href="#unlock<?= $staff['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">Unlock</a>
                                            <a href="#delete<?= $staff['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="user<?= $staff['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit User Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First name:</div>
                                                                    <div class="col-md-9"><input type="text" name="firstname" value="<?= $staff['firstname'] ?>" required /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last name:</div>
                                                                    <div class="col-md-9"><input type="text" name="lastname" value="<?= $staff['lastname'] ?>" required /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Position</div>
                                                                    <div class="col-md-9">
                                                                        <select name="position" style="width: 100%;" required>
                                                                            <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                            <?php foreach ($override->getData('position') as $position) { ?>
                                                                                <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?= $staff['phone_number'] ?>" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">E-mail Address:</div>
                                                                    <div class="col-md-9"><input value="<?= $staff['email_address'] ?>" class="validate[required,custom[email]]" type="text" name="email_address" id="email" /> <span>Example: someone@nowhere.com</span></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                        <input type="submit" name="edit_staff" value="Save updates" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="reset<?= $staff['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Reset Password</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reset password to default (12345678)</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                        <input type="submit" name="reset_pass" value="Reset" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="unlock<?= $staff['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Unlock Account</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to unlock this account </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                        <input type="submit" name="unlock_account" value="Unlock" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete<?= $staff['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Delete User</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong style="font-weight: bold;color: red">
                                                            <p>Are you sure you want to delete this user</p>
                                                        </strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                                        <input type="submit" name="delete_staff" value="Delete" class="btn btn-danger">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 2 && $user->data()->accessLevel == 1) { ?>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Positions</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="25%">Name</th>
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('position') as $position) { ?>
                                    <tr>
                                        <td> <?= $position['name'] ?></td>
                                        <td><a href="#position<?= $position['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="position<?= $position['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Position Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?= $position['name'] ?>" required /></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $position['id'] ?>">
                                                        <input type="submit" name="edit_position" class="btn btn-warning" value="Save updates">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Studies</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="30%">Name</th>
                                    <th width="10%">Code</th>
                                    <th width="10%">Sample Size</th>
                                    <th width="15%">Start Date</th>
                                    <th width="15%">End Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('study') as $study) { ?>
                                    <tr>
                                        <td><?= $study['name'] ?></td>
                                        <td><?= $study['code'] ?></td>
                                        <td><?= $study['sample_size'] ?></td>
                                        <td><?= $study['start_date'] ?></td>
                                        <td><?= $study['end_date'] ?></td>
                                        <td><a href="#study<?= $study['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="study<?= $study['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Name:</div>
                                                                <div class="col-md-9">
                                                                    <input value="<?=$study['name']?>" class="validate[required]" type="text" name="name" id="name" />
                                                                </div>
                                                            </div>
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Code:</div>
                                                                <div class="col-md-9">
                                                                    <input value="<?=$study['code']?>" class="validate[required]" type="text" name="code" id="code" />
                                                                </div>
                                                            </div>
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Sample Size:</div>
                                                                <div class="col-md-9">
                                                                    <input value="<?=$study['sample_size']?>" class="validate[required]" type="number" name="sample_size" id="sample_size" />
                                                                </div>
                                                            </div>
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Start Date:</div>
                                                                <div class="col-md-9">
                                                                    <input value="<?=$study['start_date']?>" class="validate[required,custom[date]]" type="text" name="start_date" id="start_date"/> <span>Example: 2010-12-01</span>
                                                                </div>
                                                            </div>

                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">End Date:</div>
                                                                <div class="col-md-9">
                                                                    <input value="<?=$study['end_date']?>" class="validate[required,custom[date]]" type="text" name="end_date" id="end_date" /> <span>Example: 2010-12-01</span>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$study['id']?>">
                                                        <input type="submit" name="edit_study" class="btn btn-warning" value="Save updates">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Sites</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="25%">Name</th>
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('site') as $site) { ?>
                                    <tr>
                                        <td> <?= $site['name'] ?></td>
                                        <td><a href="#site<?= $site['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="site<?= $site['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Site Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?= $site['name'] ?>" required /></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $site['id'] ?>">
                                                        <input type="submit" name="edit_site" class="btn btn-warning" value="Save updates">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }
                elseif ($_GET['id'] == 3) { ?>
                    <div class="col-md-12">
                        <?php if($user->data()->power==1){?>
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Search by Site</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-1">Site:</div>
                                        <div class="col-md-4">
                                            <select name="site" required>
                                                <option value="">Select Site</option>
                                                <?php foreach ($override->getData('site') as $site){?>
                                                    <option value="<?=$site['id']?>"><?=$site['name']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-info">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php }?>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Clients</h1>
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
                        <?php if($user->data()->power == 1){
                            if($_GET['sid'] != null){
                                $pagNum=0;
                                $pagNum=$override->getCounted('clients','status',1,'enrolled',1,'site_id',$_GET['sid']);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit1('clients', 'site_id',$_GET['sid'],'status', 1,$page,$numRec);
                            }else{
                                $pagNum=0;
                                $pagNum=$override->getCount('clients','status',1);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit('clients', 'status',1,$page,$numRec);
                            }
                        }else {
                            $pagNum=0;
                            $pagNum=$override->countData('clients','site_id',$user->data()->site_id, 'status',1);
                            $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                            $clients=$override->getWithLimit1('clients', 'site_id',$user->data()->site_id,'status', 1,$page,$numRec);
                        }?>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall" /></th>
                                    <td width="20">#</td>
                                    <th width="40">Picture</th>
                                    <th width="10%">ParticipantID</th>
                                    <th width="20%">Name</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">Age</th>
                                    <th width="10%">Status</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x=1;foreach ($clients as $client) {?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox" /></td>
                                        <td><?=$x?></td>
                                        <td width="100">
                                            <?php if($client['client_image'] !='' || is_null($client['client_image'])){$img=$client['client_image'];}else{$img='img/users/blank.png';}?>
                                            <a href="#img<?= $client['id'] ?>" data-toggle="modal"><img src="<?=$img?>" width="90" height="90" class=""/></a>
                                        </td>
                                        <td><?=$client['id_number'] ?></td>
                                        <td> <?=$client['firstname'] . ' ' . $client['lastname'] ?></td>
                                        <td><?=$client['gender'] ?></td>
                                        <td><?=$client['age'] ?></td>
                                        <td>
                                            <?php if($client['eligibility']==0 && $client['screened']==1){?>
                                                <a href="#" role="button" class="btn btn-danger" data-toggle="modal">Not Eligible</a>
                                            <?php }elseif($client['eligibility']==1 && $client['screened']==1){?>
                                                <a href="#" role="button" class="btn btn-success" data-toggle="modal">Eligible</a>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <a href="#clientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                            <a href="#client<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
<!--                                            <a href="id.php?cid=--><?//= $client['id'] ?><!--"  class="btn btn-warning" >Patient ID</a>-->
                                            <?php if($user->data()->accessLevel == 1){?>
                                                <a href="#delete<?= $client['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                            <?php }?>
                                            <?php if($client['screened']==0){?>
                                                <a href="add.php?id=8&cid=<?=$client['id']?>" role="button" class="btn btn-warning" > Add Screening </a>
                                            <?php }?>

                                            <?php if($client['screened']==1 && $client['eligibility']==1){?>
                                                <a href="info.php?id=8&cid=<?=$client['id']?>" role="button" class="btn btn-info" > Study CRFs </a>
                                            <?php }?>
<!--                                            <a href="info.php?id=4&cid=--><?//=$client['id']?><!--" role="button" class="btn btn-warning" >Schedule</a>-->
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="clientView<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Client View</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-2">Study</div>
                                                                    <div class="col-md-6">
                                                                        <select name="position" style="width: 100%;" disabled>
                                                                            <?php foreach ($override->getData('study') as $study) { ?>
                                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 pull-right">
                                                                        <img src="<?=$img?>" class="img-thumbnail" width="50%" height="50%"/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ParticipantID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="participant_id" id="participant_id" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['clinic_date']?>" class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date" disabled/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Middle Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['middlename']?>" class="validate[required]" type="text" name="middlename" id="middlename" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date of Birth:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['dob']?>" class="validate[required,custom[date]]" type="text" name="dob" id="dob" disabled/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Age:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" disabled>
                                                                            <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                            <option value="male">Male</option>
                                                                            <option value="female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ID NUMBER:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Comments:</div>
                                                                    <div class="col-md-9"><textarea name="comments" rows="4" disabled><?=$client['comments']?></textarea> </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="client<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form id="validation" enctype="multipart/form-data" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Client Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Study</div>
                                                                    <div class="col-md-9">
                                                                        <select name="position" style="width: 100%;" required>
                                                                            <?php foreach ($override->getData('study') as $study) { ?>
                                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ParticipantID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="participant_id" id="participant_id" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['clinic_date']?>" class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date"/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Middle Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['middlename']?>" class="validate[required]" type="text" name="middlename" id="middlename" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" />
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date of Birth:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['dob']?>" class="validate[required,custom[date]]" type="text" name="dob" id="dob"/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Age:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" required>
                                                                            <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                            <option value="male">Male</option>
                                                                            <option value="female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ID NUMBER:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" />
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Comments:</div>
                                                                    <div class="col-md-9"><textarea name="comments" rows="4"><?=$client['comments']?></textarea> </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="client_image" value="<?=$client['client_image']?>"/>
                                                        <input type="hidden" name="id" value="<?=$client['id'] ?>">
                                                        <input type="submit" name="edit_client" value="Save updates" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Delete User</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong style="font-weight: bold;color: red">
                                                            <p>Are you sure you want to delete this user</p>
                                                        </strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                        <input type="submit" name="delete_staff" value="Delete" class="btn btn-danger">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right">
                            <div class="btn-group">
                                <a href="info.php?id=3&sid=&page=<?php if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?>" class="btn btn-default"> < </a>
                                <?php for($i=1;$i<=$pages;$i++){?>
                                    <a href="info.php?id=3&sid=&page=<?=$_GET['id']?>&page=<?=$i?>" class="btn btn-default <?php if($i == $_GET['page']){echo 'active';}?>"><?=$i?></a>
                                <?php } ?>
                                <a href="info.php?id=3&sid=&page=<?php if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?>" class="btn btn-default"> > </a>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 4) { ?>
                    <div class="col-md-12">
                        <?php $patient = $override->get('clients', 'id', $_GET['cid'])[0]?>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="ucard clearfix">
                                    <div class="right">
                                        <div class="image">
                                            <?php if($patient['client_image'] !='' || is_null($patient['client_image'])){$img=$patient['client_image'];}else{$img='img/users/blank.png';}?>
                                            <a href="#"><img src="<?=$img?>" width="300" class="img-thumbnail"></a>
                                        </div>
                                        <h5><?='Name: '.$patient['firstname'].' '.$patient['lastname'].' Age: '.$patient['age']?></h5>
                                        <h4><strong style="font-size: medium">Screening ID: <?=$patient['participant_id']?></strong></h4>
                                        <h4><strong style="font-size: larger">Study ID: <?=$patient['study_id']?></strong></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="head clearfix">
                                    <div class="isw-grid"></div>
                                    <h1>Schedule</h1>
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
                                    <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                        <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Visit Name</th>
                                            <th width="15%">Visit Code</th>
                                            <th width="15%">Visit Type</th>
                                            <th width="15%">Visit Date</th>
                                            <th width="10%">Status</th>
                                            <th width="35%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $x=1;foreach ($override->get('visit', 'client_id', $_GET['cid']) as $visit) {
//                                            $sc=$override->get('screening','client_id',$_GET['cid'])[0];
                                            $clnt=$override->get('clients', 'id', $_GET['cid'])[0];
                                            $cntV=$override->getCount('visit','client_id',$visit['client_id']);
                                            if($visit['status']==0){$btnV='Add';}elseif ($visit['status']==1){$btnV='Edit';}
//                                            if($sc){$btnS='Edit';}else{$btnS='Add';}
//
                                            if($visit['visit_code'] == 'D0'){$v_typ='Screening & Enrollment';}else{$v_typ='Follow Up';}
                                            ?>
                                                <tr>
                                                    <td><?=$x?></td>
                                                    <td> <?=$visit['visit_name'] ?></td>
                                                    <td> <?=$visit['visit_code'] ?></td>
                                                    <td> <?=$v_typ ?></td>
                                                    <td> <?=$visit['visit_date'] ?></td>
                                                    <td>
                                                        <?php if($visit['status']==1){?>
                                                            <a href="#" role="button" class="btn btn-success">Done</a>
                                                        <?php }elseif ($visit['status']==0){?>
                                                            <a href="#" role="button" class="btn btn-warning">Pending</a>
                                                        <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php if($clnt['screened']==0){?>
                                                            <a href="add.php?id=8&cid=<?=$visit['client_id']?>" role="button" class="btn btn-info" > Screening </a>
                                                        <?php }?>
                                                        <?php if($clnt['eligibility']==0 && $clnt['screened']==1){?>
                                                            <a href="#" role="button" class="btn btn-danger" data-toggle="modal">Not Eligible</a>
                                                        <?php }?>
                                                        <?php if($clnt['screened']==1 && $clnt['eligibility']==1){?>
                                                            <a href="add.php?id=9&cid=<?=$visit['client_id']?>" role="button" class="btn btn-info" > Forms </a>
                                                        <?php }?>
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="addVisit<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Add Visit</h4>
                                                                </div>
                                                                <div class="modal-body modal-body-np">
                                                                    <div class="row">
                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="<?= $visit['visit_name'].' ('.$visit['visit_code'].')' ?>" disabled /></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit Type:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="<?=$v_typ?>" disabled /></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Current Status</div>
                                                                            <div class="col-md-9">
                                                                                <select name="visit_status" style="width: 100%;" required>
                                                                                    <?php if($visit['status'] !=0){?>
                                                                                        <option value="<?=$visit['visit_status']?>"><?=$visit['visit_status']?></option>
                                                                                    <?php }else{?>
                                                                                        <option value="">Select</option>
                                                                                    <?php }?>
                                                                                    <option value="1">Attended</option>
                                                                                    <option value="2">Missed Visit</option>
                                                                                    <option value="3">Vaccinated</option>
                                                                                    <option value="4">Not Vaccinated</option>
                                                                                    <option value="5">Follow Up Visit</option>
                                                                                    <option value="6">Early Termination</option>
                                                                                    <option value="7">Termination</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Notes:</div>
                                                                            <div class="col-md-9">
                                                                                <textarea name="reasons" rows="4" ><?php if($visit['status'] !=0){echo $visit['reasons'];}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?php if($visit['status'] !=0){echo $visit['visit_date'];}?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date"/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?=$visit['id'] ?>">
                                                                    <input type="hidden" name="vc" value="<?=$visit['visit_code'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="edit_visit" class="btn btn-warning" value="Save">
                                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="addScreening<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Add Screening</h4>
                                                                </div>
                                                                <div class="modal-body modal-body-np">
                                                                    <div class="row">

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-8">Aged 6 years and above </div>
                                                                            <div class="col-md-4">
                                                                                <select name="age_18" style="width: 100%;" required>
                                                                                    <option value="<?=$sc['age_6_above']?>"><?php if($sc){if($sc['age_6_above']==1){echo 'Yes';}elseif($sc['age_6_above']==2){echo 'No';}}else{echo 'Select';}?></option>
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="2">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-8">Consenting individuals</div>
                                                                            <div class="col-md-4">
                                                                                <select name="consent" style="width: 100%;" required>
                                                                                    <option value="<?=$sc['consent']?>"><?php if($sc){if($sc['consent']==1){echo 'Yes';}elseif($sc['consent']==2){echo 'No';}}else{echo 'Select';}?></option>
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="2">No</option>

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-8">Known SCD</div>
                                                                            <div class="col-md-4">
                                                                                <select name="scd" style="width: 100%;" required>
                                                                                    <option value="<?=$sc['scd']?>"><?php if($sc){if($sc['scd']==1){echo 'Yes';}elseif($sc['scd']==2){echo 'No';}}else{echo 'Select';}?></option>
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="2">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-8">Diabetes, RHD patients,</div>
                                                                            <div class="col-md-4">
                                                                                <select name="rhd" style="width: 100%;" required>
                                                                                    <option value="<?=$sc['rhd']?>"><?php if($sc){if($sc['rhd']==1){echo 'Yes';}elseif($sc['rhd']==2){echo 'No';}}else{echo 'Select';}?></option>
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="2">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-8">Non permanent resident</div>
                                                                            <div class="col-md-4">
                                                                                <select name="resident" style="width: 100%;" required>
                                                                                    <option value="<?=$sc['resident']?>"><?php if($sc){if($sc['resident']==1){echo 'Yes';}elseif($sc['resident']==2){echo 'No';}}else{echo 'Select';}?></option>
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="2">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?=$visit['id'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="add_screening" class="btn btn-warning" value="Save">
                                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php $x++;} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 5) { ?>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of IDs</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall" /></th>
                                    <td width="40">#</td>
                                    <th width="70">STUDY ID</th>
                                    <th width="80%">STATUS</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x=1;
                                $pagNum=$override->getCount('study_id', 'site_id', $user->data()->site_id);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                foreach ($override->getWithLimit('study_id', 'site_id', $user->data()->site_id,$page,$numRec) as $study_id) {?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox" /></td>
                                        <td><?=$x?></td>
                                        <td><?=$study_id['study_id'] ?></td>
                                        <td>
                                            <?php if($study_id['status'] == 1){?>
                                                <a href="#" role="button" class="btn btn-success" >Assigned</a>
                                            <?php }else{?>
                                                <a href="#" role="button" class="btn btn-warning" >Not Assigned</a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php $x++;} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-left">
                            <div class="btn-group">
                                <a href="info.php?id=5&page=<?php if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?>" class="btn btn-default"> < </a>
                                <?php for($i=1;$i<=$pages;$i++){?>
                                    <a href="info.php?id=5&page=<?=$_GET['id']?>&page=<?=$i?>" class="btn btn-default <?php if($i == $_GET['page']){echo 'active';}?>"><?=$i?></a>
                                <?php } ?>
                                <a href="info.php?id=5&page=<?php if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?>" class="btn btn-default"> > </a>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 6) { ?>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Download Data</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Name</th>
                                    <th width="25%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Clients</td>
                                    <td><form method="post"><input type="submit" name="clients" value="Download"></form> </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Visit</td>
                                    <td><form method="post"><input type="submit" name="visits" value="Download"></form> </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Laboratory</td>
                                    <td><form method="post"><input type="submit" name="lab" value="Download"></form> </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Study IDs</td>
                                    <td><form method="post"><input type="submit" name="study_id" value="Download"></form> </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Sites</td>
                                    <td><form method="post"><input type="submit" name="sites" value="Download"></form> </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Screening</td>
                                    <td><form method="post"><input type="submit" name="screening" value="Download"></form> </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 7) { ?>
                    <div class="col-md-12">
                        <?php if($user->data()->power==1){?>
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Search by Site</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-1">Site:</div>
                                        <div class="col-md-4">
                                            <select name="site" required>
                                                <option value="">Select Site</option>
                                                <?php foreach ($override->getData('site') as $site){?>
                                                    <option value="<?=$site['id']?>"><?=$site['name']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-info">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php }?>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Clients</h1>
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
                        <?php if($user->data()->power == 1){
                            if($_GET['sid'] != null){
//                                    $pagNum=0;
//                                    $pagNum=$override->countData('clients','status',1,'site_id',$_GET['sid']);
//                                    $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
//                                    $clients=$override->getWithLimit1('clients', 'site_id',$_GET['sid'],'status', 1,$page,$numRec);
                                $clients=$override->get3('clients','status',1,'enrolled', 1,'site_id',$_GET['sid']);
                            }else{
//                                    $pagNum=0;
//                                    $pagNum=$override->getCount('clients','status',1);
//                                    $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
//                                    $clients=$override->getWithLimit('clients', 'status',1,$page,$numRec);
                                $clients=$override->getNews('clients','status',1,'enrolled', 1);
                            }
                        }else {
//                                $pagNum=0;
//                                $pagNum=$override->countData('clients','site_id',$user->data()->site_id, 'status',1);
//                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
//                                $clients=$override->getWithLimit1('clients', 'site_id',$user->data()->site_id,'status', 1,$page,$numRec);
                            $clients=$override->get3('clients','status',1,'enrolled', 1,'site_id',$user->data()->site_id);
                        }?>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall" /></th>
                                    <td width="20">#</td>
                                    <th width="40">Picture</th>
                                    <th width="10%">ParticipantID</th>
                                    <th width="10%">Study ID</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">Age</th>
                                    <th width="40%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x=1;foreach ($clients as $client) {?>
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
                                            <a href="#clientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                            <a href="#client<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                            <a href="id.php?cid=<?= $client['id'] ?>"  class="btn btn-warning" >Patient ID</a>
                                            <?php if($user->data()->accessLevel == 1){?>
                                                <a href="#delete<?= $client['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                            <?php }?>
                                            <a href="info.php?id=4&cid=<?=$client['id']?>" role="button" class="btn btn-warning" >Schedule</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="clientView<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Client View</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-2">Study</div>
                                                                    <div class="col-md-6">
                                                                        <select name="position" style="width: 100%;" disabled>
                                                                            <?php foreach ($override->getData('study') as $study) { ?>
                                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 pull-right">
                                                                        <img src="<?=$img?>" class="img-thumbnail" width="50%" height="50%"/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ParticipantID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['participant_id']?>" class="validate[required]" type="text" name="participant_id" id="participant_id" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['clinic_date']?>" class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date" disabled/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Middle Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['middlename']?>" class="validate[required]" type="text" name="middlename" id="middlename" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date of Birth:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['dob']?>" class="validate[required,custom[date]]" type="text" name="dob" id="dob" disabled/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Age:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Initials:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['initials']?>" class="validate[required]" type="text" name="initials" id="initials" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" disabled>
                                                                            <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                            <option value="male">Male</option>
                                                                            <option value="female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Hospital ID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Marital Status</div>
                                                                    <div class="col-md-9">
                                                                        <select name="marital_status" style="width: 100%;" disabled>
                                                                            <option value="<?=$client['marital_status']?>"><?=$client['marital_status']?></option>
                                                                            <option value="Single">Single</option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Divorced">Divorced</option>
                                                                            <option value="Separated">Separated</option>
                                                                            <option value="Widower">Widower/Widow</option>
                                                                            <option value="Cohabit">Cohabit</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Education Level</div>
                                                                    <div class="col-md-9">
                                                                        <select name="education_level" style="width: 100%;" disabled>
                                                                            <option value="<?=$client['education_level']?>"><?=$client['education_level']?></option>
                                                                            <option value="Not attended school">Not attended school</option>
                                                                            <option value="Primary">Primary</option>
                                                                            <option value="Secondary">Secondary</option>
                                                                            <option value="Certificate">Certificate</option>
                                                                            <option value="Diploma">Diploma</option>
                                                                            <option value="Undergraduate degree">Undergraduate degree</option>
                                                                            <option value="Postgraduate degree">Postgraduate degree</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Workplace/station site:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['workplace']?>" class="" type="text" name="workplace" id="workplace" disabled /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Occupation:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['occupation']?>" class="" type="text" name="occupation" id="occupation" disabled /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['phone_number']?>" class="" type="text" name="phone_number" id="phone" disabled /> <span>Example: 0700 000 111</span></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Relative's Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['other_phone']?>" class="" type="text" name="other_phone" id="phone" disabled /> <span>Example: 0700 000 111</span></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Residence Street:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['street']?>" class="" type="text" name="street" id="street" disabled /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Ward:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['ward']?>" class="" type="text" name="ward" id="ward" disabled /></div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">House Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['block_no']?>" class="" type="text" name="block_no" id="block_no"  disabled/></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Comments:</div>
                                                                    <div class="col-md-9"><textarea name="comments" rows="4" disabled><?=$client['comments']?></textarea> </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="client<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form id="validation" enctype="multipart/form-data" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Client Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Study</div>
                                                                    <div class="col-md-9">
                                                                        <select name="position" style="width: 100%;" required>
                                                                            <?php foreach ($override->getData('study') as $study) { ?>
                                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">ParticipantID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['participant_id']?>" class="validate[required]" type="text" name="participant_id" id="participant_id" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['clinic_date']?>" class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date"/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Middle Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['middlename']?>" class="validate[required]" type="text" name="middlename" id="middlename" />
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" />
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Date of Birth:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['dob']?>" class="validate[required,custom[date]]" type="text" name="dob" id="dob"/> <span>Example: 2010-12-01</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Age:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" disabled/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Initials:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['initials']?>" class="validate[required]" type="text" name="initials" id="initials" />
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-5">Client Image:</div>
                                                                    <div class="col-md-7">
                                                                        <input type="file" id="image" name="image"/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" required>
                                                                            <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                            <option value="male">Male</option>
                                                                            <option value="female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Hospital ID:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" />
                                                                    </div>
                                                                </div>


                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Marital Status</div>
                                                                    <div class="col-md-9">
                                                                        <select name="marital_status" style="width: 100%;" required>
                                                                            <option value="<?=$client['marital_status']?>"><?=$client['marital_status']?></option>
                                                                            <option value="Single">Single</option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Divorced">Divorced</option>
                                                                            <option value="Separated">Separated</option>
                                                                            <option value="Widower">Widower/Widow</option>
                                                                            <option value="Cohabit">Cohabit</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Education Level</div>
                                                                    <div class="col-md-9">
                                                                        <select name="education_level" style="width: 100%;" required>
                                                                            <option value="<?=$client['education_level']?>"><?=$client['education_level']?></option>
                                                                            <option value="Not attended school">Not attended school</option>
                                                                            <option value="Primary">Primary</option>
                                                                            <option value="Secondary">Secondary</option>
                                                                            <option value="Certificate">Certificate</option>
                                                                            <option value="Diploma">Diploma</option>
                                                                            <option value="Undergraduate degree">Undergraduate degree</option>
                                                                            <option value="Postgraduate degree">Postgraduate degree</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Workplace/station site:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['workplace']?>" class="" type="text" name="workplace" id="workplace" required /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Occupation:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['occupation']?>" class="" type="text" name="occupation" id="occupation" required /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['phone_number']?>" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Relative's Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['other_phone']?>" class="" type="text" name="other_phone" id="other_phone" /> <span>Example: 0700 000 111</span></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Residence Street:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['street']?>" class="" type="text" name="street" id="street" required /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Ward:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['ward']?>" class="" type="text" name="ward" id="ward" required /></div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">House Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$client['block_no']?>" class="" type="text" name="block_no" id="block_no"  /></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Comments:</div>
                                                                    <div class="col-md-9"><textarea name="comments" rows="4"><?=$client['comments']?></textarea> </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="client_image" value="<?=$client['client_image']?>"/>
                                                        <input type="hidden" name="id" value="<?=$client['id'] ?>">
                                                        <input type="submit" name="edit_client" value="Save updates" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Delete User</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong style="font-weight: bold;color: red">
                                                            <p>Are you sure you want to delete this user</p>
                                                        </strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                        <input type="submit" name="delete_staff" value="Delete" class="btn btn-danger">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                </tbody>
                            </table>
                        </div>
                        <!--                            <div class="pull-right">-->
                        <!--                                <div class="btn-group">-->
                        <!--                                    <a href="info.php?id=3&sid=&page=--><?php //if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?><!--" class="btn btn-default"> < </a>-->
                        <!--                                    --><?php //for($i=1;$i<=$pages;$i++){?>
                        <!--                                        <a href="info.php?id=3&sid=&page=--><?//=$_GET['id']?><!--&page=--><?//=$i?><!--" class="btn btn-default --><?php //if($i == $_GET['page']){echo 'active';}?><!--">--><?//=$i?><!--</a>-->
                        <!--                                    --><?php //} ?>
                        <!--                                    <a href="info.php?id=3&sid=&page=--><?php //if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?><!--" class="btn btn-default"> > </a>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                    </div>
                <?php }elseif ($_GET['id'] == 8) {?>
                    <div class="col-md-2">
                        <?php $patient = $override->get('clients', 'id', $_GET['cid'])[0]?>
                        <div class="ucard clearfix">
                            <div class="right">
                                <div class="image">
                                    <?php if($patient['client_image'] !='' || is_null($patient['client_image'])){$img=$patient['client_image'];}else{$img='img/users/blank.png';}?>
                                    <a href="#"><img src="<?=$img?>" width="300" class="img-thumbnail"></a>
                                </div>
                                <h5><?='Name: '.$patient['firstname'].' '.$patient['lastname'].' Age: '.$patient['age']?></h5>
                                <h4><strong style="font-size: medium">Screening ID: <?=$patient['participant_id']?></strong></h4>
                                <h4><strong style="font-size: larger">Study ID: <?=$patient['study_id']?></strong></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Study CRF (Enrollment)</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Name</th>
                                    <th width="65%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Demographic</td>
                                    <?php if($override->get('demographic', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=9&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=9&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>Vitals</td>
                                    <?php if($override->get('vital', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=14&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=14&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>Patient Hitory & Complication</td>
                                    <?php if($override->get('history', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=15&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=15&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>Family History(Symtom & Exam)</td>
                                    <?php if($override->get('symptoms', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=16&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=16&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>



                                <tr>
                                    <td>5</td>
                                    <td>Main diagnosis</td>
                                    <?php if($override->get('diagnosis', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=17&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled=""> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=17&cid=<?=$_GET['cid']?>" class="btn btn-warning"> Add </a> </td>
                                    <?php }?>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>Results at enrollment</td>
                                    <?php if($override->get('results', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=18&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=18&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>Hospitalization</td>
                                    <?php if($override->get('hospitalization', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=19&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=19&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>Treatment Plan</td>
                                    <?php if($override->get('treatment_plan', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=20&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=20&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>9</td>
                                    <td>Diagnosis, Complications, & Comorbidities</td>
                                    <?php if($override->get('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=21&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=21&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td>RISK</td>
                                    <?php if($override->get('risks', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=22&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=22&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td>Hospitalization Details</td>
                                    <?php if($override->get('hospitalization_details', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=23&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=23&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td>Lab Details</td>
                                    <?php if($override->get('lab_details', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=24&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=24&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>12</td>
                                    <td>Summary</td>
                                    <?php if($override->get('summary', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=25&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=25&cid=<?=$_GET['cid']?>" class="btn btn-warning">Add </a> </td>
                                    <?php }?>

                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>Social Economic</td>
                                    <td><a href="#" class="btn btn-warning"> Add </a> </td>
                                </tr>

                                <?php if($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)){?>

                                <tr>
                                    <td>9</td>
                                    <td>Cardiac</td>
                                    <?php if($override->get('cardiac', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=11&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled=> CHange </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=11&cid=<?=$_GET['cid']?>" class="btn btn-warning"> Add </a> </td>
                                    <?php }?>
                                </tr>
                                <?php }?>
                                <?php if($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)){?>

                                <tr>
                                    <td>10</td>
                                    <td>Diabetes</td>
                                    <?php if($override->get('diabetic', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=12&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=12&cid=<?=$_GET['cid']?>" class="btn btn-warning"> Add </a> </td>
                                    <?php }?>
                                </tr>
                                <?php }?>

                                <?php if($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)){?>

                                <tr>
                                    <td>11</td>
                                    <td>Sickle cell</td>
                                    <?php if($override->get('sickle_cell', 'patient_id', $_GET['cid'])){?>
                                        <td><a href="add.php?id=13&cid=<?=$_GET['cid']?>" class="btn btn-success" disabled=""> Change </a> </td>
                                    <?php }else{?>
                                        <td><a href="add.php?id=13&cid=<?=$_GET['cid']?>" class="btn btn-warning"> Add </a> </td>
                                    <?php }?>

                                </tr>
                                <?php }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 9){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Study CRF (Follow UP)</h1>
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
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Name</th>
                                    <th width="65%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>vVIRTUAL Signs</td>
                                    <td><a href="#" class="btn btn-info">Add </a> </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Diagnosis</td>
                                    <td><a href="#" class="btn btn-info">Add </a> </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Cardiac</td>
                                    <td><a href="#" class="btn btn-info">Add </a> </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Diabetes</td>
                                    <td><a href="#" class="btn btn-info">Add </a> </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Sickle cell</td>
                                    <td><a href="#" class="btn btn-info">Add </a> </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }?>
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