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
                'population_group' => array(
                    'required' => true,
                ),
                'street' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                ),
                'age' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
//                    $attachment_file = Input::get('image');
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
                        $attachment_file = Input::get('client_image');
                    }
                    if($errorM == false){
                        $user->updateRecord('clients', array(
                            'participant_id' => Input::get('participant_id'),
                            'clinic_date' => Input::get('clinic_date'),
                            'firstname' => Input::get('firstname'),
                            'lastname' => Input::get('lastname'),
                            'dob' => Input::get('dob'),
                            'age' => Input::get('age'),
                            'id_number' => Input::get('id_number'),
                            'id_type' => Input::get('id_type'),
                            'gender' => Input::get('gender'),
                            'marital_status' => Input::get('marital_status'),
                            'population_group' => Input::get('population_group'),
                            'education_level' => Input::get('education_level'),
                            'workplace' => Input::get('workplace'),
                            'occupation' => Input::get('occupation'),
                            'phone_number' => Input::get('phone_number'),
                            'other_phone' => Input::get('other_phone'),
                            'street' => Input::get('street'),
                            'ward' => Input::get('ward'),
                            'block_no' => Input::get('block_no'),
                            'client_image' => $attachment_file,
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

                    if(Input::get('visit_status') == 8){
                        $user->updateRecord('clients',array('withdraw_consent'=>1,'withdraw_consent_date'=>Input::get('visit_date')),Input::get('cid'));
                    }elseif (Input::get('visit_status') == 9){
                        $user->updateRecord('clients',array('screened_out'=>1,'screened_out_date' =>Input::get('visit_date')),Input::get('cid'));
                    }

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
        elseif (Input::get('add_unscheduled')){
            $validate = $validate->check($_POST, array(
                'reasons' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('unscheduled', array(
                        'visit_code' => Input::get('vc'),
                        'seq' => Input::get('sq'),
                        'client_id' => Input::get('cid'),
                        'visit_date' => Input::get('visit_date'),
                        'reasons' => Input::get('reasons'),
                        'created_on' => date('Y-m-d'),
                        'staff_id' => $user->data()->id,
                        'status' => Input::get('visit_status'),
                    ));
                    $successMessage = 'Visit Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_enroll')){
            $validate = $validate->check($_POST, array(
                'enrolled_date' => array(
                    'required' => true,
                ),
                'consent_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if(Input::get('eligible')==1 && Input::get('consent')==1 && Input::get('enroll_status')==1){
                    $check=$override->getNews('visit','visit_code','V4','client_id', Input::get('cid'))[0];
                    if(!$check){
                        $user->updateRecord('clients', array('enrolled'=>1,'enrolled_date'=>Input::get('enrolled_date'),'consent_date'=>Input::get('consent_date'),'reasons'=>Input::get('reasons')),Input::get('cid'));
                        $user->visit(Input::get('cid'), Input::get('seq'));
                    }
                    $successMessage = 'Visit Successful Enrolled';
                }else{
                    $errorMessage='Please make sure, client meet eligibility criteria and signed Consent form';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_pre_screening')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'contact' => array(
                    'required' => true,
                ),
                'test_date' => array(
                    'required' => true,
                ),
                'rapid_test_result' => array(
                    'required' => true,
                ),
                'tested_by' => array(
                    'required' => true,
                ),
                'appointment_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $errorM = false;
                try {
                    $user->updateRecord('pre_screening', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'contact' => Input::get('contact'),
                        'test_date' => Input::get('test_date'),
                        'rapid_test_result' => Input::get('rapid_test_result'),
                        'tested_by' => Input::get('tested_by'),
                        'appointment_date' => Input::get('appointment_date'),
                        'staff_id' => $user->data()->id,
                    ),Input::get('id'));
                    $successMessage = 'Pre Screening Client Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('study_screened')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'screened' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $errorM = false;
                try {
                    $user->updateRecord('pre_screening', array(
                        'screened' => Input::get('screened'),
                    ),Input::get('id'));
                    $successMessage = 'Pre Screening Client Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_unscheduled')) {
            $validate = $validate->check($_POST, array(
                'reasons' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('unscheduled', array(
                        'visit_date' => Input::get('visit_date'),
                        'reasons' => Input::get('reasons'),
                    ),Input::get('id'));
                    $successMessage = 'Visit Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }

        if($_GET['id'] == 7){
            $data = null;
            $filename = null;
            if(Input::get('clients')){
                $data = $override->getData('clients');
                $filename = 'Clients';
            }elseif (Input::get('visits')){
                $data = $override->getData('visit');
                $filename = 'Visits';
            }elseif (Input::get('unscheduled_visits')){
                $data = $override->getData('unscheduled');
                $filename = 'Unscheduled visits';
            }
            elseif (Input::get('pre_screening')){
                $data = $override->getData('pre_screening');
                $filename = 'Pre Screening';
            }
            elseif (Input::get('sites')){
                $data = $override->getData('site');
                $filename = 'Sites';
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
    <title> Info - PenPlus </title>
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
                    <?php } elseif ($_GET['id'] == 3) { ?>
                        <div class="col-md-12">
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
                                $pagNum=0;
                                $pagNum=$override->getCount('clients','status',1);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit('clients', 'status', 1,$page,$numRec);
                            }else {
                                $pagNum=0;
                                $pagNum=$override->countData('clients','status',1,'site_id',$user->data()->site_id);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit1('clients','site_id',$user->data()->site_id, 'status',1,$page,$numRec);
                            }?>
                            <div class="block-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="checkall" /></th>
                                        <td width="20">#</td>
                                        <th width="40">Picture</th>
                                        <th width="20%">ParticipantID</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Gender</th>
                                        <th width="10%">Age</th>
                                        <th width="40%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $amnt = 0;
                                    $x=1;foreach ($clients as $client) {?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox" /></td>
                                            <td><?=$x?></td>
                                            <td width="100">
                                                <?php if($client['client_image'] !='' || is_null($client['client_image'])){$img=$client['client_image'];}else{$img='img/users/blank.png';}?>
                                                <a href="#img<?= $client['id'] ?>" data-toggle="modal"><img src="<?=$img?>" width="90" height="90" class=""/></a>
                                            </td>
                                            <td><?=$client['participant_id'] ?></td>
                                            <td> <?=$client['firstname'] . ' ' . $client['lastname'] ?></td>
                                            <td><?=$client['gender'] ?></td>
                                            <td><?=$client['age'] ?></td>
                                            <td>
                                                <a href="#clientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                                <a href="info.php?id=8&cid=<?= $client['id'] ?>" role="button" class="btn btn-info">Edit</a>
                                                <a href="id.php?cid=<?= $client['id'] ?>"  class="btn btn-warning" >Patient ID</a>
                                                <a href="#delete<?= $client['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
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
                                                                        <div class="col-md-3">ID Number:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" disabled/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Identification Type</div>
                                                                        <div class="col-md-9">
                                                                            <select name="id_type" style="width: 100%;" disabled>
                                                                                <option value="<?=$client['id_type']?>"><?=$client['id_type']?></option>
                                                                                <option value="Driving License">Driving License</option>
                                                                                <option value="Voters ID">Voters ID</option>
                                                                                <option value="National ID">National ID</option>
                                                                                <option value="Employment ID">Employment ID</option>
                                                                                <option value="Introductory Letter">Introductory Letter</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Population Group</div>
                                                                        <div class="col-md-9">
                                                                            <select name="population_group" style="width: 100%;" disabled>
                                                                                <option value="<?=$client['population_group']?>"><?=$client['population_group']?></option>
                                                                                <option value="General Population">General Population</option>
                                                                                <option value="Police and Prison Forces">Police and Prison Forces</option>
                                                                            </select>
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
                                                                        <div class="col-md-3">Other Phone Number:</div>
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
                                                                        <div class="col-md-3">Block Number:</div>
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
                                                <form enctype="multipart/form-data" method="post">
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
                                                                            <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" />
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
                                                                        <div class="col-md-3">ID Number:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Identification Type</div>
                                                                        <div class="col-md-9">
                                                                            <select name="id_type" style="width: 100%;" required>
                                                                                <option value="<?=$client['id_type']?>"><?=$client['id_type']?></option>
                                                                                <option value="Driving License">Driving License</option>
                                                                                <option value="Voters ID">Voters ID</option>
                                                                                <option value="National ID">National ID</option>
                                                                                <option value="Employment ID">Employment ID</option>
                                                                                <option value="Introductory Letter">Introductory Letter</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Population Group</div>
                                                                        <div class="col-md-9">
                                                                            <select name="population_group" style="width: 100%;" required>
                                                                                <option value="<?=$client['population_group']?>"><?=$client['population_group']?></option>
                                                                                <option value="General Population">General Population</option>
                                                                                <option value="Police and Prison Forces">Police and Prison Forces</option>
                                                                            </select>
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
                                                                        <div class="col-md-3">Other Phone Number:</div>
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
                                                                        <div class="col-md-3">Block Number:</div>
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
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="info.php?id=3&page=<?php if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?>" class="btn btn-default"> < </a>
                                    <?php for($i=1;$i<=$pages;$i++){?>
                                        <a href="info.php?id=3&page=<?=$_GET['id']?>&page=<?=$i?>" class="btn btn-default <?php if($i == $_GET['page']){echo 'active';}?>"><?=$i?></a>
                                    <?php } ?>
                                    <a href="info.php?id=3&page=<?php if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?>" class="btn btn-default"> > </a>
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
                                            <h4><strong style="font-size: larger">Study ID: <?=$patient['participant_id']?></strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="pull-right">
                                        <a href="info.php?id=9&cid=<?=$_GET['cid']?>" role="button" class="btn btn-info" data-toggle="modal">View Unscheduled</a>&nbsp;&nbsp;
                                        <a href="schedule.php?cid=<?=$_GET['cid']?>" role="button" class="btn btn-warning" >Print Page</a>
                                    </div>
                                    <div class="head clearfix">
                                        <div class="isw-grid"></div>
                                        <h1>Unscheduled</h1>
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
                                                <th width="10%">Visit Name</th>
                                                <th width="5%">Visit Code</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Visit Date(Lower Limit)</th>
                                                <th width="10%">Visit Date(Exact)</th>
                                                <th width="10%">Visit Date(Upper Limit)</th>
                                                <th width="10%">Missed Days</th>
                                                <th width="10%">Status</th>
                                                <th width="35%">Action</th>
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
                                                        <td> <strong> - </strong> </td>
                                                        <td> <strong style="color: darkgreen"><?=$visit['visit_date'] ?></strong></td>
                                                        <td> <strong> - </strong></td>
                                                        <td> <strong> - </strong></td>
                                                    <?php }else{if($msv > 0){$dur=$msv;}else{$dur='-';}?>
                                                        <td> <strong style="color: darkgreen"><?=$lower ?></strong> </td>
                                                        <td> <strong style="color: coral"><?=$visit['visit_date'] ?></strong></td>
                                                        <td> <strong style="color: darkred"><?=$upper ?></strong></td>
                                                        <td> <strong><?=$dur ?></strong></td>
                                                    <?php }?>

                                                    <?php if($visit['status'] == 1) {?>
                                                        <td>
                                                            <a href="#<?= $visit['id'] ?>" role="button" class="btn btn-success">Done</a>
                                                            <?php if($visit['seq_no'] > 3){?>
                                                                <a href="#addUnscheduled<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Unscheduled</a>
                                                            <?php }?>
                                                        </td>
                                                        <td>
                                                            <?php if($visit['visit_code'] == 'V3'){?>
                                                                <a href="#addEnroll<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Enrollment</a>
                                                            <?php }?>
                                                        </td>
                                                    <?php }
                                                    elseif($visit['status'] == 0){
                                                        if($msv > 0){?>
                                                            <td><a href="#<?= $visit['id'] ?>" role="button" class="btn btn-danger"> Missed </a></td>
                                                            <!-- if you want to allow user to enter visit even when its missed uncomment and remove last td tag-->
                                                            <td>
                                                                <a href="#addUnscheduled<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Unscheduled</a>
<!--                                                                --><?php //if($visit['seq_no'] > 3){
//                                                                    if($visit['seq_no'] < 11) {
//                                                                        $sn = $visit['seq_no'] + 1;
//                                                                        $n_sn = $override->getNews('visit', 'seq_no', $sn, 'client_id', $_GET['cid'])[0];
//                                                                        if($user->dateDiff(date('Y-m-d'),$n_sn['visit_date']) > 0){?>
<!--                                                                            <a href="#addUnscheduled--><?//= $visit['id'] ?><!--" role="button" class="btn btn-info" data-toggle="modal">Add Unscheduled</a>-->
<!--                                                                        --><?php //}}}?>
                                                            </td>
                                                            <td></td>
                                                        <?php }else {?>
                                                            <td><a href="#<?= $visit['id'] ?>" role="button" class="btn btn-warning">Pending</a></td>
                                                            <td>
                                                                <a href="#addVisit<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add</a>
                                                                <?php if($visit['seq_no'] > 3){?>
                                                                    <a href="#addUnscheduled<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Unscheduled</a>
                                                                <?php }?>
                                                                <?php if($visit['visit_code'] == 'V3'){?>
                                                                    <a href="#addEnroll<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Enrollment</a>
                                                                <?php }?>
                                                            </td>
                                                        <?php }}
                                                        elseif ($visit['status'] == 2){?>
                                                        <td>
                                                            <a href="#<?= $visit['id'] ?>" role="button" class="btn btn-warning">Not Done</a>
                                                            <?php if($visit['seq_no'] > 3){?>
                                                                <a href="#addUnscheduled<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Unscheduled</a>
                                                            <?php }?>
                                                            <?php if($visit['visit_code'] == 'V3'){?>
                                                                <a href="#addEnroll<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Add Enrollment</a>
                                                            <?php }?>
                                                        </td>
                                                    <?php }?>
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
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">Attended</option>
                                                                                    <option value="2">Missed Visit</option>
                                                                                    <option value="3">Vaccinated</option>
                                                                                    <option value="4">Not Vaccinated</option>
                                                                                    <option value="5">Follow Up Visit</option>
                                                                                    <option value="6">Early Termination</option>
                                                                                    <option value="7">Termination</option>
                                                                                    <option value="8">Withdraw Consent</option>
                                                                                    <option value="9">Screened Out</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Reasons:</div>
                                                                            <div class="col-md-9">
                                                                                <textarea name="reasons" rows="4" ></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" required/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?=$visit['id'] ?>">
                                                                    <input type="hidden" name="vc" value="<?=$visit['visit_code'] ?>">
                                                                    <input type="hidden" name="seq" value="<?=$visit['seq_no'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="edit_visit" class="btn btn-warning" value="Save">
                                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="addUnscheduled<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Add Unscheduled Visit</h4>
                                                                </div>
                                                                <div class="modal-body modal-body-np">
                                                                    <div class="row">
                                                                        <?php $unscheduled=$override->getlastRow1('unscheduled','visit_code',$visit['visit_code'],'client_id',$visit['client_id'], 'id');
                                                                        if($unscheduled){$sq=$unscheduled[0]['seq']+1;}else{$sq=1;}?>
                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="<?=$visit['visit_code'].'.'.$sq?>" disabled /></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit Type:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="Unscheduled" disabled /></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Status</div>
                                                                            <div class="col-md-9">
                                                                                <select name="visit_status" style="width: 100%;" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">Attended</option>
                                                                                    <option value="2">Not Attended</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date"/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Reasons:</div>
                                                                            <div class="col-md-9">
                                                                               <textarea name="reasons" rows="4" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="sq" value="<?=$sq?>">
                                                                    <input type="hidden" name="vc" value="<?=$visit['visit_code'] ?>">
                                                                    <input type="hidden" name="seq" value="<?=$visit['seq_no'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="add_unscheduled" class="btn btn-warning" value="Save">
                                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="addEnroll<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Add Enrollment Visit</h4>
                                                                </div>
                                                                <div class="modal-body modal-body-np">
                                                                    <div class="row">
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Current Status</div>
                                                                            <div class="col-md-9">
                                                                                <select name="enroll_status" style="width: 100%;" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">Vaccinated</option>
                                                                                    <option value="2">Not Vaccinated</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-5">Eligibility:</div>
                                                                            <div class="col-md-7">
                                                                                <select name="eligible" style="width: 100%;" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">Eligible</option>
                                                                                    <option value="2">Not Eligible</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-5">Consent:</div>
                                                                            <div class="col-md-7">
                                                                                <select name="consent" style="width: 100%;" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">Signed and Confirm</option>
                                                                                    <option value="2">Not Signed</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Consent Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="" class="validate[required,custom[date]]" type="text" name="consent_date" id="visit_date" required/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Reasons:</div>
                                                                            <div class="col-md-9">
                                                                                <textarea name="reasons" rows="4" ></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Enrollment Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="" class="validate[required,custom[date]]" type="text" name="enrolled_date" id="visit_date" required/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?=$visit['id'] ?>">
                                                                    <input type="hidden" name="vc" value="<?=$visit['visit_code'] ?>">
                                                                    <input type="hidden" name="seq" value="<?=$visit['seq_no'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="add_enroll" class="btn btn-warning" value="Save">
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
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>List of Pre Screened Clients</h1>
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
                                $pagNum=0;
                                $pagNum=$override->getCount('pre_screening','status',1);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit('pre_screening', 'status', 1,$page,$numRec);
                            }else {
                                $pagNum=0;
                                $pagNum=$override->countData('pre_screening','status',1,'site_id',$user->data()->site_id);
                                $pages = ceil($pagNum / $numRec);if(!$_GET['page'] || $_GET['page'] == 1){$page = 0;}else{$page = ($_GET['page']*$numRec)-$numRec;}
                                $clients=$override->getWithLimit1('pre_screening','site_id',$user->data()->site_id, 'status',1,$page,$numRec);
                            }?>
                            <div class="block-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="checkall" /></th>
                                        <td width="5">#</td>
                                        <th width="10%">Name</th>
                                        <th width="10%">Contact</th>
                                        <th width="10%">Test date</th>
                                        <th width="10%">Rapid Ab Test Results</th>
                                        <th width="10%">Tested By</th>
                                        <th width="10%">Appointment Date</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Study Screened</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $amnt = 0;
                                    $x=1;foreach ($clients as $client) {?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox" /></td>
                                            <td><?=$x?></td>
                                            <td> <?=$client['firstname'] . ' ' . $client['lastname'] ?></td>
                                            <td><?=$client['contact'] ?></td>
                                            <td><?=$client['test_date'] ?></td>
                                            <td><?=$client['rapid_test_result'] ?></td>
                                            <td><?=$client['tested_by'] ?></td>
                                            <td><?=$client['appointment_date'] ?></td>
                                            <td>
                                                <?php if($client['rapid_test_result'] == '1'){?>
                                                    <a href="#" role="button" class="btn btn-success">Eligible</a>
                                                <?php }else{?>
                                                    <a href="#" role="button" class="btn btn-danger">Ineligible</a>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if($client['screened'] == 1){?>
                                                    <a href="#" role="button" class="btn btn-success">Done</a>
                                                <?php }elseif ($client['screened'] == 0){?>
                                                    <a href="#" role="button" class="btn btn-warning">Pending</a>
                                                <?php }else{?>
                                                    <a href="#" role="button" class="btn btn-danger">Ineligible</a>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <a href="#PclientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                                <a href="#Pclient<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                                <a href="#sScreened<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">Study Screened</a>
                                            </td>

                                        </tr>
                                        <div class="modal fade" id="PclientView<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>View Client Pre Screening Info</h4>
                                                        </div>
                                                        <div class="modal-body modal-body-np">
                                                            <div class="row">
                                                                <div class="block-fluid">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">First Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" disabled/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Last Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" disabled/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Contact Number:</div>
                                                                        <div class="col-md-9"><input value="<?=$client['contact']?>" class="" type="text" name="contact" id="contact" disabled /> <span>Example: 0700 000 111</span></div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Test Date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['test_date']?>" class="validate[required,custom[date]]" type="text" name="test_date" id="test_date" disabled/> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Rapid Ab test results</div>
                                                                        <div class="col-md-9">
                                                                            <select name="rapid_test_result" style="width: 100%;" disabled>
                                                                                <option value="<?=$client['rapid_test_result']?>"><?php if($client['rapid_test_result'] == 1){echo 'None Reactive';}else{echo 'Reactive';}?></option>
                                                                                <option value="1">None Reactive</option>
                                                                                <option value="2">Reactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Tested by(name):</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['tested_by']?>" type="text" name="tested_by" id="tested_by" disabled/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Appointment date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['appointment_date']?>" class="validate[required,custom[date]]" type="text" name="appointment_date" id="appointment_date" disabled/> <span>Example: 2010-12-01</span>
                                                                        </div>
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
                                        <div class="modal fade" id="Pclient<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form id="validation" enctype="multipart/form-data" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Edit Client Pre Screening Info</h4>
                                                        </div>
                                                        <div class="modal-body modal-body-np">
                                                            <div class="row">
                                                                <div class="block-fluid">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">First Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Last Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Contact Number:</div>
                                                                        <div class="col-md-9"><input value="<?=$client['contact']?>" class="" type="text" name="contact" id="contact" required /> <span>Example: 0700 000 111</span></div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Test Date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['test_date']?>" class="validate[required,custom[date]]" type="text" name="test_date" id="test_date"/> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Rapid Ab test results</div>
                                                                        <div class="col-md-9">
                                                                            <select name="rapid_test_result" style="width: 100%;" required>
                                                                                <option value="<?=$client['rapid_test_result']?>"><?php if($client['rapid_test_result'] == 1){echo 'None Reactive';}else{echo 'Reactive';}?></option>
                                                                                <option value="1">None Reactive</option>
                                                                                <option value="2">Reactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Tested by(name):</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['tested_by']?>" type="text" name="tested_by" id="tested_by" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Appointment date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['appointment_date']?>" class="validate[required,custom[date]]" type="text" name="appointment_date" id="appointment_date"/> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dr"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?=$client['id'] ?>">
                                                            <input type="submit" name="edit_pre_screening" value="Save updates" class="btn btn-warning">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="sScreened<?=$client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Screened for the Study</h4>
                                                        </div>
                                                        <div class="modal-body modal-body-np">
                                                            <div class="row">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-6">Is Client Screened for the study</div>
                                                                    <div class="col-md-4">
                                                                        <select name="screened" style="width: 100%;" required>
                                                                            <option value="<?=$client['screened'] ?>"><?php if($client['screened']){echo $client['screened'];}else{echo 'Select';}?></option>
                                                                            <option value="1">Yes</option>
                                                                            <option value="2">No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="dr"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                            <input type="submit" name="study_screened" class="btn btn-warning" value="Save updates">
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
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="info.php?id=6&page=<?php if(($_GET['page']-1) > 0){echo $_GET['page']-1;}else{echo 1;}?>" class="btn btn-default"> < </a>
                                    <?php for($i=1;$i<=$pages;$i++){?>
                                        <a href="info.php?id=3&page=<?=$_GET['id']?>&page=<?=$i?>" class="btn btn-default <?php if($i == $_GET['page']){echo 'active';}?>"><?=$i?></a>
                                    <?php } ?>
                                    <a href="info.php?id=6&page=<?php if(($_GET['page']+1) <= $pages){echo $_GET['page']+1;}else{echo $i-1;}?>" class="btn btn-default"> > </a>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 7) { ?>
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
                                        <td>Unscheduled Visit</td>
                                        <td><form method="post"><input type="submit" name="unscheduled_visits" value="Download"></form> </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Pre screening</td>
                                        <td><form method="post"><input type="submit" name="pre_screening" value="Download"></form> </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Sites</td>
                                        <td><form method="post"><input type="submit" name="sites" value="Download"></form> </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php }elseif($_GET['id'] == 8){ ?>
                        <div class="col-md-offset-1 col-md-8">
                            <?php $client=$override->get('clients','id', $_GET['cid'])[0]?>
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Edit Client</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" enctype="multipart/form-data" method="post">
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
                                            <input value="<?=$client['age']?>" class="validate[required]" type="text" name="age" id="age" />
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
                                        <div class="col-md-3">ID Number:</div>
                                        <div class="col-md-9">
                                            <input value="<?=$client['id_number']?>" class="validate[required]" type="text" name="id_number" id="id_number" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Identification Type</div>
                                        <div class="col-md-9">
                                            <select name="id_type" style="width: 100%;" required>
                                                <option value="<?=$client['id_type']?>"><?=$client['id_type']?></option>
                                                <option value="Driving License">Driving License</option>
                                                <option value="Voters ID">Voters ID</option>
                                                <option value="National ID">National ID</option>
                                                <option value="Employment ID">Employment ID</option>
                                                <option value="Introductory Letter">Introductory Letter</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Population Group</div>
                                        <div class="col-md-9">
                                            <select name="population_group" style="width: 100%;" required>
                                                <option value="<?=$client['population_group']?>"><?=$client['population_group']?></option>
                                                <option value="General Population">General Population</option>
                                                <option value="Police and Prison Forces">Police and Prison Forces</option>
                                            </select>
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
                                        <div class="col-md-3">Other Phone Number:</div>
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
                                        <div class="col-md-3">Block Number:</div>
                                        <div class="col-md-9"><input value="<?=$client['block_no']?>" class="" type="text" name="block_no" id="block_no"  /></div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"><?=$client['comments']?></textarea> </div>
                                    </div>
                                    <div class="footer tar">
                                        <input type="hidden" name="client_image" value="<?=$client['client_image']?>"/>
                                        <input type="hidden" name="id" value="<?=$client['id'] ?>" />
                                        <input type="submit" name="edit_client" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php }elseif ($_GET['id'] == 9){?>
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
                                            <h4><strong style="font-size: larger">Study ID: <?=$patient['participant_id']?></strong></h4>
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
                                                <th width="5%">Visit Code</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Visit Date</th>
                                                <th width="45%">Reasons</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $x=1;foreach ($override->get('unscheduled', 'client_id', $_GET['cid']) as $visit) {?>
                                                <tr>
                                                    <td><?=$x?></td>
                                                    <td> <?=$visit['visit_code'].'.'.$visit['seq'] ?></td>
                                                    <td> Unscheduled </td>
                                                    <td> <?=$visit['visit_date'] ?></td>
                                                    <td> <?=$visit['reasons'] ?></td>
                                                    <td><a href="#editUnscheduled<?= $visit['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                                </tr>
                                                <div class="modal fade" id="editUnscheduled<?=$visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Add Unscheduled Visit</h4>
                                                                </div>
                                                                <div class="modal-body modal-body-np">
                                                                    <div class="row">
                                                                        <?php $unscheduled=$override->getlastRow1('unscheduled','visit_code',$visit['visit_code'],'client_id',$visit['client_id'], 'id');
                                                                        if($unscheduled){$sq=$unscheduled[0]['seq']+1;}else{$sq=1;}?>
                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="<?=$visit['visit_code'].'.'.$visit['seq']?>" disabled /></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="block-fluid">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit Type:</div>
                                                                                <div class="col-md-9"><input type="text" name="name" value="Unscheduled" disabled /></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Status</div>
                                                                            <div class="col-md-9">
                                                                                <select name="visit_status" style="width: 100%;" disabled>
                                                                                    <option value="<?=$visit['status']?>"><?php if($visit['status']==1){echo'Attended';}elseif ($visit['status']==2){echo'Not Attended';}else{echo 'Select';}?></option>
                                                                                    <option value="1">Attended</option>
                                                                                    <option value="2">Not Attended</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Date:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?=$visit['visit_date']?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date"/> <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Reasons:</div>
                                                                            <div class="col-md-9">
                                                                                <textarea name="reasons" rows="4" required><?=$visit['reasons']?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?=$visit['id'] ?>">
                                                                    <input type="hidden" name="cid" value="<?=$visit['client_id'] ?>">
                                                                    <input type="submit" name="edit_unscheduled" class="btn btn-warning" value="Save">
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