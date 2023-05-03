<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec = 15;
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
        } elseif (Input::get('edit_staff')) {
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
        } elseif (Input::get('reset_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Reset Successful';
        } elseif (Input::get('unlock_account')) {
            $user->updateRecord('user', array(
                'count' => 0,
            ), Input::get('id'));
            $successMessage = 'Account Unlock Successful';
        } elseif (Input::get('delete_staff')) {
            $user->updateRecord('user', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'User Deleted Successful';
        } elseif (Input::get('edit_study')) {
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
        } elseif (Input::get('edit_site')) {
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
        } elseif (Input::get('edit_client')) {
            $validate = $validate->check($_POST, array(
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
                            } else { {
                                    $errorM = true;
                                    $errorMessage = 'Your profile Picture Not Uploaded ,';
                                }
                            }
                        } else {
                            $errorM = true;
                            $errorMessage = 'None supported file format';
                        } //not supported format
                    } else {
                        $attachment_file = '';
                    }
                    if (!empty($_FILES['image']["tmp_name"])) {
                        $image = $attachment_file;
                    } else {
                        $image = Input::get('client_image');
                    }
                    if ($errorM == false) {
                        $age = $user->dateDiffYears(date('Y-m-d'), Input::get('dob'));
                        $user->updateRecord('clients', array(
                            'hospital_id' => Input::get('hospital_id'),
                            'clinic_date' => Input::get('clinic_date'),
                            'firstname' => Input::get('firstname'),
                            'middlename' => Input::get('middlename'),
                            'lastname' => Input::get('lastname'),
                            'dob' => Input::get('dob'),
                            'age' => $age,
                            'gender' => Input::get('gender'),
                            'site_id' => $user->data()->site_id,
                            'staff_id' => $user->data()->id,
                            'client_image' => $attachment_file,
                            'comments' => Input::get('comments'),
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                        ), Input::get('id'));

                        $successMessage = 'Client Updated Successful';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_screening')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'age_6_above' => array(
                    'required' => true,
                ),
                'consent' => array(
                    'required' => true,
                ),
                'scd' => array(
                    'required' => true,
                ),
                'rhd' => array(
                    'required' => true,
                ),
                'residence' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $eligibility = 0;
                    if (Input::get('age_6_above') == 1 && Input::get('consent') == 1 && Input::get('scd') == 2 && Input::get('rhd') == 2 && Input::get('residence') == 2) {
                        $eligibility = 1;
                    }

                    if ($override->get('screening', 'patient_id', Input::get('id'))) {
                        $user->updateRecord('screening', array(
                            'screening_date' => Input::get('visit_date'),
                            'study_id' => '',
                            'age_6_above' => Input::get('age_6_above'),
                            'consent' => Input::get('consent'),
                            'scd' => Input::get('scd'),
                            'rhd' => Input::get('rhd'),
                            'residence' => Input::get('residence'),
                            'created_on' => date('Y-m-d'),
                            'patient_id' => Input::get('id'),
                            'staff_id' => $user->data()->id,
                            'eligibility' => $eligibility,
                            'status' => 1,
                        ), Input::get('scrrening_id'));

                        $visit = $override->getNews('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', 'Screening')[0];

                        $user->updateRecord('visit', array(
                            'expected_date' => Input::get('visit_date'),
                            'visit_date' => Input::get('visit_date'),
                        ), $visit['id']);
                    } else {
                        $user->createRecord('screening', array(
                            'screening_date' => Input::get('visit_date'),
                            'study_id' => '',
                            'age_6_above' => Input::get('age_6_above'),
                            'consent' => Input::get('consent'),
                            'scd' => Input::get('scd'),
                            'rhd' => Input::get('rhd'),
                            'residence' => Input::get('residence'),
                            'created_on' => date('Y-m-d'),
                            'patient_id' => Input::get('id'),
                            'staff_id' => $user->data()->id,
                            'eligibility' => $eligibility,
                            'status' => 1,
                        ));

                        $user->createRecord('visit', array(
                            'study_id' => '',
                            'visit_name' => 'Screening',
                            'visit_code' => 'SV',
                            'visit_day' => 'Day 0',
                            'expected_date' => Input::get('visit_date'),
                            'visit_date' => Input::get('visit_date'),
                            'visit_window' => 0,
                            'status' => 1,
                            'seq_no' => 0,
                            'client_id' => Input::get('id'),
                            'created_on' => date('Y-m-d'),
                            'reasons' => '',
                            'visit_status' => 1,
                        ));
                    }

                    $user->updateRecord('clients', array(
                        'eligible' => $eligibility,
                        'screened' => 1,
                    ), Input::get('id'));

                    $successMessage = 'Patient Successful Screened';

                    if ($eligibility) {
                        Redirect::to('info.php?id=3&status=2');
                    } else {
                        Redirect::to('info.php?id=3&status=' . $_GET['status']);
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_Enrollment')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $client_study = $override->getNews('clients', 'id', Input::get('id'), 'status', 1)[0];
                $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
                $screening_id = $override->getNews('screening', 'patient_id', Input::get('id'), 'status', 1)[0];
                $visit_id = $override->get('visit', 'client_id', Input::get('id'))[0];
                $last_visit = $override->getlastRow('visit', 'client_id', Input::get('id'), 'id')[0];
                $visit = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', Input::get('visit_name'))[0];

                if (!$client_study['study_id']) {
                    $study_id = $std_id['study_id'];
                } else {
                    $study_id = $client_study['study_id'];
                }

                if (Input::get('visit_name') == 'Registration Visit') {
                    $visit_code = 'RV';
                } elseif (Input::get('visit_name') == 'Screening Visit') {
                    $visit_code = 'SV';
                } elseif (Input::get('visit_name') == 'Enrollment Visit') {
                    $visit_code = 'EV';
                } elseif (Input::get('visit_name') == 'Follow Up Visit') {
                    $visit_code = 'FV';
                } elseif (Input::get('visit_name') == 'Study Termination Visit') {
                    $visit_code = 'TV';
                } elseif (Input::get('visit_name') == 'Unschedule Visit') {
                    $visit_code = 'UV';
                }

                if ($visit) {
                    $errorMessage = 'Visit with the same Date ana Name already exists for this Client';
                } else {
                    $user->createRecord('visit', array(
                        'study_id' => $study_id,
                        'visit_name' => Input::get('visit_name'),
                        'visit_code' => $visit_code,
                        'visit_day' => 'Day 1',
                        'expected_date' => Input::get('visit_date'),
                        'visit_date' => Input::get('visit_date'),
                        'visit_window' => 0,
                        'status' => 0,
                        'client_id' => Input::get('id'),
                        'created_on' => date('Y-m-d'),
                        'seq_no' => 1,
                        'reasons' => Input::get('reasons'),
                        'visit_status' => 1,
                    ));

                    if (!$client_study['study_id']) {
                        $user->updateRecord('screening', array('study_id' => $std_id['study_id']), $screening_id['id']);
                        $user->updateRecord('clients', array('study_id' => $std_id['study_id'], 'enrolled' => 1), Input::get('id'));
                        $user->updateRecord('study_id', array('status' => 1, 'client_id' => Input::get('id')), $std_id['id']);
                    } else {
                        $user->updateRecord('screening', array('study_id' => $client_study['study_id']), $screening_id['id']);
                        $user->updateRecord('clients', array('study_id' => $client_study['study_id'], 'enrolled' => 1), Input::get('id'));
                    }

                    $successMessage = 'Enrollment  Added Successful';
                    Redirect::to('info.php?id=3&status=3');
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_Schedule')) {
            $validate = $validate->check($_POST, array(
                'expected_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $client_study = $override->getNews('clients', 'id', Input::get('id'), 'status', 1)[0];
                $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
                $screening_id = $override->getNews('screening', 'patient_id', Input::get('id'), 'status', 1)[0];
                $visit_id = $override->get('visit', 'client_id', Input::get('id'))[0];
                $last_visit = $override->getlastRow('visit', 'client_id', Input::get('id'), 'id')[0];
                $expected_date = $override->getNews('visit', 'expected_date', Input::get('expected_date'), 'client_id', Input::get('id'))[0];

                $sq = $last_visit['seq_no'] + 1;
                $visit_day = 'Day ' . $sq;

                if (!$client_study['study_id']) {
                    $study_id = $std_id['study_id'];
                } else {
                    $study_id = $client_study['study_id'];
                }

                if (Input::get('visit_name') == 'Registration Visit') {
                    $visit_code = 'RV';
                } elseif (Input::get('visit_name') == 'Screening Visit') {
                    $visit_code = 'SV';
                } elseif (Input::get('visit_name') == 'Enrollment Visit') {
                    $visit_code = 'EV';
                } elseif (Input::get('visit_name') == 'Follow Up Visit') {
                    $visit_code = 'FV';
                } elseif (Input::get('visit_name') == 'Study Termination Visit') {
                    $visit_code = 'TV';
                } elseif (Input::get('visit_name') == 'Unschedule Visit') {
                    $visit_code = 'UV';
                }

                if ($expected_date['expected_date'] == Input::get('expected_date')) {
                    $errorMessage = 'Next Date already exists';
                } else {
                    $user->createRecord('visit', array(
                        'study_id' => $study_id,
                        'visit_name' => Input::get('visit_name'),
                        'visit_code' => $visit_code,
                        'visit_day' => $visit_day,
                        'expected_date' => Input::get('expected_date'),
                        'visit_date' => '',
                        'visit_window' => 0,
                        'status' => 0,
                        'client_id' => Input::get('id'),
                        'created_on' => date('Y-m-d'),
                        'seq_no' => $sq,
                        'reasons' => '',
                        'visit_status' => 0,
                    ));

                    $successMessage = 'Schedule  Added Successful';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_visit')) {
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
                        'status' => 1,
                        'visit_status' => Input::get('visit_status'),
                        'reasons' => Input::get('reasons'),
                    ), Input::get('id'));

                    $successMessage = 'Visit  Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('search_by_site')) {

            $validate = $validate->check($_POST, array(
                'site' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $url = 'info.php?id=3&sid=' . Input::get('site');
                Redirect::to($url);
                $pageError = $validate->errors();
            }
        } elseif (Input::get('clear_data')) {

            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $clearData = $override->clearDataTable(Input::get('name'));

                    $successMessage = 'Table  Cleared Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }

        if ($_GET['id'] == 6) {
            $data = null;
            $filename = null;
            if (Input::get('clients')) {
                $data = $override->getData('clients');
                $filename = 'Clients';
            } elseif (Input::get('visits')) {
                $data = $override->getData('visit');
                $filename = 'Visits';
            } elseif (Input::get('lab')) {
                $data = $override->getData('lab');
                $filename = 'Laboratory Results';
            } elseif (Input::get('study_id')) {
                $data = $override->getData('study_id');
                $filename = 'Study IDs';
            } elseif (Input::get('sites')) {
                $data = $override->getData('site');
                $filename = 'Sites';
            } elseif (Input::get('screening')) {
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
                <?php include 'header.php' ?>
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
                                <?php if ($user->data()->power == 1) {
                                    $user = $override->get('user', 'status', 1);
                                } else {
                                    $users = $override->getNews('user', 'site_id', $user->data()->site_id, 'status', 1);
                                } ?>
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
                                                <td> <?= $staff['firstname'] . ' ' . $staff['lastname'] ?></td>
                                                <td><?= $staff['username'] ?></td>
                                                <td><?= $position['name'] ?></td>
                                                <td><?= $site['name'] ?></td>
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
                                                                            <input value="<?= $study['name'] ?>" class="validate[required]" type="text" name="name" id="name" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Code:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $study['code'] ?>" class="validate[required]" type="text" name="code" id="code" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Sample Size:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $study['sample_size'] ?>" class="validate[required]" type="number" name="sample_size" id="sample_size" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Start Date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $study['start_date'] ?>" class="validate[required,custom[date]]" type="text" name="start_date" id="start_date" /> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">End Date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $study['end_date'] ?>" class="validate[required,custom[date]]" type="text" name="end_date" id="end_date" /> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $study['id'] ?>">
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
                            <?php if ($user->data()->power == 1) { ?>
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
                                                    <?php foreach ($override->getData('site') as $site) { ?>
                                                        <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" name="search_by_site" value="Search" class="btn btn-info">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <?php
                                if ($_GET['status'] == 1) { ?>
                                    <h1>List of Screened Clients</h1>
                                <?php
                                } elseif ($_GET['status'] == 2) { ?>
                                    <h1>List of Eligible Clients</h1>
                                <?php
                                } elseif ($_GET['status'] == 3) { ?>
                                    <h1>List of Enrolled Clients</h1>
                                <?php
                                } elseif ($_GET['status'] == 4) { ?>
                                    <h1>List of Terminated Clients</h1>
                                <?php
                                } ?>
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
                            <?php if ($user->data()->power == 1) {
                                if ($_GET['sid'] != null) {
                                    $pagNum = 0;
                                    if ($_GET['status'] == 1) {
                                        $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 2) {
                                        $pagNum = $override->countData1('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 3) {
                                        $pagNum = $override->countData1('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 4) {
                                        $pagNum = $override->countData1('clients', 'status', 0, 'enrolled', 1, 'site_id', $_GET['sid']);
                                    }

                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    if ($_GET['status'] == 1) {
                                        $clients = $override->getWithLimit1('clients', 'site_id', $_GET['sid'], 'status', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 2) {
                                        $clients = $override->getWithLimit2('clients', 'site_id', $_GET['sid'], 'status', 1, 'eligible', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 3) {
                                        $clients = $override->getWithLimit2('clients', 'site_id', $_GET['sid'], 'status', 1, 'enrolled', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 4) {
                                        $clients = $override->getWithLimit2('clients', 'site_id', $_GET['sid'], 'status', 0, 'enrolled', 1, $page, $numRec);
                                    }
                                } else {
                                    $pagNum = 0;
                                    if ($_GET['status'] == 1) {
                                        $pagNum = $override->getCount('clients', 'status', 1);
                                    } elseif ($_GET['status'] == 2) {
                                        $pagNum = $override->countData('clients', 'status', 1, 'eligible', 1);
                                    } elseif ($_GET['status'] == 3) {
                                        $pagNum = $override->countData('clients', 'status', 1, 'enrolled', 1);
                                    } elseif ($_GET['status'] == 4) {
                                        $pagNum = $override->countData('clients', 'status', 0, 'enrolled', 1);
                                    }
                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }
                                    if ($_GET['status'] == 1) {
                                        $clients = $override->getWithLimit('clients', 'status', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 2) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'eligible', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 3) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'enrolled', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 4) {
                                        $clients = $override->getWithLimit1('clients', 'status', 0, 'enrolled', 1, $page, $numRec);
                                    }
                                }
                            } else {
                                $pagNum = 0;
                                if ($_GET['status'] == 1) {
                                    $pagNum = $override->countData('clients', 'site_id', $user->data()->site_id, 'status', 1);
                                } elseif ($_GET['status'] == 2) {
                                    $pagNum = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'eligible', 1);
                                } elseif ($_GET['status'] == 3) {
                                    $pagNum = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'enrolled', 1);
                                } elseif ($_GET['status'] == 4) {
                                    $pagNum = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 0, 'enrolled', 1);
                                }
                                $pages = ceil($pagNum / $numRec);
                                if (!$_GET['page'] || $_GET['page'] == 1) {
                                    $page = 0;
                                } else {
                                    $page = ($_GET['page'] * $numRec) - $numRec;
                                }
                                if ($_GET['status'] == 1) {
                                    $clients = $override->getWithLimit1('clients', 'site_id', $user->data()->site_id, 'status', 1, $page, $numRec);
                                } elseif ($_GET['status'] == 2) {
                                    $clients = $override->getWithLimit2('clients', 'site_id', $user->data()->site_id, 'status', 1, 'eligible', 1, $page, $numRec);
                                } elseif ($_GET['status'] == 3) {
                                    $clients = $override->getWithLimit2('clients', 'site_id', $user->data()->site_id, 'status', 1, 'enrolled', 1, $page, $numRec);
                                } elseif ($_GET['status'] == 4) {
                                    $clients = $override->getWithLimit2('clients', 'site_id', $user->data()->site_id, 'status', 0, 'enrolled', 1, $page, $numRec);
                                }
                            } ?>
                            <div class="block-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <td width="20">#</td>
                                            <th width="40">Picture</th>
                                            <th width="10%">Study ID</th>
                                            <th width="20%">Name</th>
                                            <th width="10%">Gender</th>
                                            <th width="10%">Age</th>
                                            <th width="10%">Site</th>
                                            <th width="10%">Status</th>
                                            <th width="30%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x = 1;
                                        foreach ($clients as $client) {
                                            $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                            $visit = $override->getCount('visit', 'client_id', $client['id']);
                                            $visits = $override->get('visit', 'client_id', $client['id'])[0];
                                            $screened = 0;
                                            $eligibility = 0;
                                            $enrollment = 0;

                                            if ($client) {
                                                if ($client['screened']) {
                                                    $screened = 1;
                                                }
                                            }

                                            if ($client) {
                                                if ($client['eligible']) {
                                                    $eligibility = 1;
                                                }
                                            }

                                            if ($client) {
                                                if ($client['enrolled']) {
                                                    $enrollment = 1;
                                                }
                                            }


                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="checkbox" />
                                                </td>
                                                <td><?= $x ?></td>
                                                <td width="100">
                                                    <?php if ($client['client_image'] != '' || is_null($client['client_image'])) {
                                                        $img = $client['client_image'];
                                                    } else {
                                                        $img = 'img/users/blank.png';
                                                    } ?>
                                                    <a href="#img<?= $client['id'] ?>" data-toggle="modal"><img src="<?= $img ?>" width="90" height="90" class="" /></a>
                                                </td>
                                                <td><?= $client['study_id'] ?></td>
                                                <td> <?= $client['firstname'] . ' ' . $client['lastname'] ?></td>
                                                <?php if ($client['gender'] == 1) { ?>
                                                    <td>MALE </td>
                                                <?php } else { ?>
                                                    <td>FEMALE </td>
                                                <?php } ?>
                                                <td><?= $client['age'] ?></td>
                                                <?php if ($client['site_id'] == 1) { ?>
                                                    <td>KONDOA </td>
                                                <?php } else { ?>
                                                    <td>KARATU </td>
                                                <?php } ?>
                                                <?php
                                                if ($_GET['status'] == 1) {
                                                    if ($eligibility == 1) {

                                                ?>
                                                        <td>
                                                            <a href="#" role="button" class="btn btn-success" data-toggle="modal">ELIGIBLE</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" role="button" class="btn btn-danger" data-toggle="modal">NOT ELIGIBLE</a>
                                                        </td>
                                                    <?php }
                                                } else {
                                                    if ($enrollment == 1) {
                                                    ?>
                                                        <td>
                                                            <a href="#" role="button" class="btn btn-success" data-toggle="modal">Enrolled</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" role="button" class="btn btn-danger" data-toggle="modal">NOT Enrolled</a>
                                                        </td>
                                                <?php

                                                    }
                                                }
                                                ?>

                                                <td>
                                                    <?php if ($_GET['status'] == 1) { ?>
                                                        <a href="#clientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                                        <?php if ($user->data()->accessLevel == 1) { ?>
                                                            <a href="id.php?cid=<?= $client['id'] ?>" class="btn btn-warning">Patient ID</a>
                                                            <a href="#delete<?= $client['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                                        <?php } ?>

                                                        <a href="add.php?id=4&cid=<?= $client['id'] ?>" class="btn btn-info">Edit Client</a>
                                                        <?php if ($screened) { ?>
                                                            <a href="#addScreening<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit Screening</a>
                                                        <?php } else {  ?>
                                                            <a href="#addScreening<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Add Screening</a>
                                                    <?php }
                                                    } ?>

                                                    <?php if ($_GET['status'] == 2) { ?>
                                                        <?php if ($enrollment == 1) { ?>
                                                            <a href="#addEnrollment<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit Enrollment</a>
                                                        <?php } else {  ?>
                                                            <a href="#addEnrollment<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Add Enrollment</a>

                                                        <?php }
                                                        ?>
                                                    <?php } ?>
                                                    <?php if ($_GET['status'] == 3) { ?>
                                                        <?php if ($enrollment == 1) { ?>
                                                            <a href="info.php?id=4&cid=<?= $client['id'] ?>" role="button" class="btn btn-warning">Study Crf</a>
                                                            <a href="#addSchedule<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Add Next Visit</a>

                                                    <?php }
                                                    } ?>
                                                </td>
                                            </tr>
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
                                                                <img src="<?= $img ?>" width="350">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="addScreening<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <?php $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                                            ?>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Add Screening</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Screening Date</div>
                                                                        <div class="col-md-4">
                                                                            <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($screening['screening_date']) {
                                                                                                                                                                                    print_r($screening['screening_date']);
                                                                                                                                                                                }  ?>" />
                                                                            <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Aged 6 years and above</div>
                                                                        <div class="col-md-4">
                                                                            <select name="age_6_above" style="width: 100%;" required>
                                                                                <option value="<?= $screening['age_6_above'] ?>"><?php if ($screening) {
                                                                                                                                        if ($screening['age_6_above'] == 1) {
                                                                                                                                            echo 'Yes';
                                                                                                                                        } elseif ($screening['age_6_above'] == 2) {
                                                                                                                                            echo 'No';
                                                                                                                                        }
                                                                                                                                    } else {
                                                                                                                                        echo 'Select';
                                                                                                                                    } ?></option>
                                                                                <option value="1">Yes</option>
                                                                                <option value="2">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Known SCD?</div>
                                                                        <div class="col-md-4">
                                                                            <select name="scd" style="width: 100%;" required>
                                                                                <option value="<?= $screening['scd'] ?>"><?php if ($screening) {
                                                                                                                                if ($screening['scd'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($screening['scd'] == 2) {
                                                                                                                                    echo 'No';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                                                <option value="1">Yes</option>
                                                                                <option value="2">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Diabetes, RHD patients</div>
                                                                        <div class="col-md-4">
                                                                            <select name="rhd" style="width: 100%;" required>
                                                                                <option value="<?= $screening['rhd'] ?>"><?php if ($screening) {
                                                                                                                                if ($screening['rhd'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($screening['rhd'] == 2) {
                                                                                                                                    echo 'No';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                                                <option value="1">Yes</option>
                                                                                <option value="2">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Non permanent resident</div>
                                                                        <div class="col-md-4">
                                                                            <select name="residence" style="width: 100%;" required>
                                                                                <option value="<?= $screening['residence'] ?>"><?php if ($screening) {
                                                                                                                                    if ($screening['residence'] == 1) {
                                                                                                                                        echo 'Yes';
                                                                                                                                    } elseif ($screening['residence'] == 2) {
                                                                                                                                        echo 'No';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?></option>
                                                                                <option value="1">Yes</option>
                                                                                <option value="2">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Consenting individuals?</div>
                                                                        <div class="col-md-4">
                                                                            <select name="consent" style="width: 100%;" required>
                                                                                <option value="<?= $screening['consent'] ?>"><?php if ($screening) {
                                                                                                                                    if ($screening['consent'] == 1) {
                                                                                                                                        echo 'Yes';
                                                                                                                                    } elseif ($screening['consent'] == 2) {
                                                                                                                                        echo 'No';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?></option>
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
                                                                <input type="hidden" name="screening_id" value="<?= $screening['id'] ?>">
                                                                <input type="hidden" name="gender" value="<?= $client['gender'] ?>">
                                                                <input type="submit" name="add_screening" class="btn btn-warning" value="Save">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="addEnrollment<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form id="validation" method="post">
                                                            <?php
                                                            $visits_date = $override->firstRow('visit', 'visit_date', 'id', 'client_id', $client['id'])[0];
                                                            $visits = $override->getlastRow('visit', 'client_id', $client['id'], 'id')[0];
                                                            ?>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Edit Visit</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">
                                                                    <div class="block-fluid">
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Visit Name:</div>
                                                                            <div class="col-md-9">
                                                                                <select name="visit_name" style="width: 100%;" required>
                                                                                    <option value="">Select</option>
                                                                                    <?php foreach ($override->getData2('schedule', 'status', 3) as $study) { ?>
                                                                                        <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Notes:</div>
                                                                        <div class="col-md-9">
                                                                            <textarea name="reasons" rows="4">
                                                                                 <?php
                                                                                    if ($visits['reasons']) {
                                                                                        print_r($visits['reasons']);
                                                                                    } ?>
                                                                                </textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Date of Enrollment:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $visits_date['visits_date'] ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" /> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="hidden" name="screening_id" value="<?= $screening['id'] ?>">
                                                                <input type="submit" name="add_Enrollment" class="btn btn-warning" value="Save">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="addSchedule<?= $client['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form id="validation" method="post">
                                                            <?php
                                                            $visits_date = $override->firstRow('visit', 'visit_date', 'id', 'client_id', $client['id'])[0];
                                                            $visits = $override->getlastRow('visit', 'client_id', $client['id'], 'id')[0];
                                                            ?>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Edit Schedule</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Next Visit Date:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="" class="validate[required,custom[date]]" type="text" name="expected_date" id="expected_date" /> <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Visit Name:</div>
                                                                    <div class="col-md-9">
                                                                        <select name="visit_name" style="width: 100%;" required>
                                                                            <option value="">Select</option>
                                                                            <?php foreach ($override->getData2('schedule', 'status', 4) as $study) { ?>
                                                                                <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="submit" name="add_Schedule" class="btn btn-warning" value="Save">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php $x++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="info.php?id=3&sid=<?= $_GET['sid'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
                                                                                            echo $_GET['page'] - 1;
                                                                                        } else {
                                                                                            echo 1;
                                                                                        } ?>" class="btn btn-default">
                                        < </a>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <a href="info.php?id=3&sid=<?= $_GET['sid'] ?>&page=<?= $i ?>" class="btn btn-default <?php if ($i == $_GET['page']) {
                                                                                                                                            echo 'active';
                                                                                                                                        } ?>"><?= $i ?></a>
                                            <?php } ?>
                                            <a href="info.php?id=3&sid=<?= $_GET['sid'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                                                                    echo $_GET['page'] + 1;
                                                                                                } else {
                                                                                                    echo $i - 1;
                                                                                                } ?>" class="btn btn-default"> > </a>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 4) { ?>
                        <div class="col-md-12">
                            <?php $patient = $override->get('clients', 'id', $_GET['cid'])[0] ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="ucard clearfix">
                                        <div class="right">
                                            <div class="image">
                                                <?php if ($patient['client_image'] != '' || is_null($patient['client_image'])) {
                                                    $img = $patient['client_image'];
                                                } else {
                                                    $img = 'img/users/blank.png';
                                                } ?>
                                                <a href="#"><img src="<?= $img ?>" width="300" class="img-thumbnail"></a>
                                            </div>
                                            <h5><?= 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] ?></h5>
                                            <h4><strong style="font-size: medium">Screening ID: <?= $patient['participant_id'] ?></strong></h4>
                                            <h4><strong style="font-size: larger">Study ID: <?= $patient['study_id'] ?></strong></h4>
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
                                                    <th width="10%">Visit Name</th>
                                                    <th width="8%">Visit Code</th>
                                                    <th width="8%">Visit Day</th>
                                                    <th width="8%">Expected Date</th>
                                                    <th width="8%">Visit Date</th>
                                                    <th width="5%">Status</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($override->get('visit', 'client_id', $_GET['cid']) as $visit) {
                                                    $clnt = $override->get('clients', 'id', $_GET['cid'])[0];
                                                    $cntV = $override->getCount('visit', 'client_id', $visit['client_id']);

                                                    $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $vital = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $history = $override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $diagnosis = $override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $results = $override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $hospitalization = $override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $treatment_plan = $override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $dgns_complctns_comorbdts = $override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $risks = $override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $hospitalization_details = $override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $lab_details = $override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];
                                                    $summary = $override->get3('summary', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code'])[0];

                                                    // print_r($treatment_plan);
                                                    if ($visit['status'] == 0) {
                                                        $btnV = 'Add';
                                                    } elseif ($visit['status'] == 1) {
                                                        $btnV = 'Edit';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?= $x ?></td>
                                                        <td> <?= $visit['visit_name'] ?></td>
                                                        <td> <?= $visit['visit_code'] ?></td>
                                                        <td> <?= $visit['visit_day'] ?></td>
                                                        <td> <?= $visit['expected_date'] ?></td>
                                                        <td> <?= $visit['visit_date'] ?></td>
                                                        <td>
                                                            <?php if ($visit['status'] == 1) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button" class="btn btn-success" data-toggle="modal">Done</a>
                                                            <?php } elseif ($visit['status'] == 0) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Pending</a>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($visit['status'] == 1 && ($visit['visit_code'] == 'EV' || $visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>
                                                            <?php if ($demographic && $vital && $history && $symptoms && $diagnosis && $results && $hospitalization && $treatment_plan && $dgns_complctns_comorbdts && $risks && $hospitalization_details  && $lab_details && $summary) { ?>
                                                                <td>
                                                                    <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-info"> Edit Study Forms </a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td>
                                                                    <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-warning"> Fill Study Forms </a>
                                                                </td>
                                                        <?php }
                                                        } ?>

                                                    </tr>
                                                    <div class="modal fade" id="editVisit<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form id="validation" method="post">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                        <h4>Add Visit</h4>
                                                                    </div>
                                                                    <div class="modal-body modal-body-np">
                                                                        <div class="row">
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Current Status</div>
                                                                                <div class="col-md-9">
                                                                                    <select name="visit_status" style="width: 100%;" required>
                                                                                        <?php
                                                                                        if ($visit['visit_status'] == 1) { ?>
                                                                                            <option value="<?= $visit['visit_status'] ?>">Attended</option>
                                                                                        <?php } else if ($visit['visit_status'] == 2) { ?>
                                                                                            <option value="<?= $visit['visit_status'] ?>">Missed Visit</option>
                                                                                        <?php } else { ?>
                                                                                            <option value="">Select</option>
                                                                                        <?php
                                                                                        } ?>
                                                                                        <option value="1">Attended</option>
                                                                                        <option value="2">Missed Visit</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Notes:</div>
                                                                                <div class="col-md-9">
                                                                                    <textarea name="reasons" rows="4"><?php if ($visit['status'] != 0) {
                                                                                                                            echo $visit['reasons'];
                                                                                                                        } ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row-form clearfix">
                                                                                <div class="col-md-3">Visit Date:</div>
                                                                                <div class="col-md-9">
                                                                                    <input value="<?php if ($visit['status'] != 0) {
                                                                                                        echo $visit['visit_date'];
                                                                                                    } ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" /> <span>Example: 2010-12-01</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="dr"><span></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" value="<?= $visit['id'] ?>">
                                                                        <input type="hidden" name="vc" value="<?= $visit['visit_code'] ?>">
                                                                        <input type="hidden" name="cid" value="<?= $visit['client_id'] ?>">
                                                                        <input type="submit" name="edit_visit" class="btn btn-warning" value="Save">
                                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $x++;
                                                } ?>
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
                                        <?php $x = 1;
                                        $pagNum = $override->getCount('study_id', 'site_id', $user->data()->site_id);
                                        $pages = ceil($pagNum / $numRec);
                                        if (!$_GET['page'] || $_GET['page'] == 1) {
                                            $page = 0;
                                        } else {
                                            $page = ($_GET['page'] * $numRec) - $numRec;
                                        }
                                        foreach ($override->getWithLimit('study_id', 'site_id', $user->data()->site_id, $page, $numRec) as $study_id) { ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td><?= $x ?></td>
                                                <td><?= $study_id['study_id'] ?></td>
                                                <td>
                                                    <?php if ($study_id['status'] == 1) { ?>
                                                        <a href="#" role="button" class="btn btn-success">Assigned</a>
                                                    <?php } else { ?>
                                                        <a href="#" role="button" class="btn btn-warning">Not Assigned</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php $x++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-left">
                                <div class="btn-group">
                                    <a href="info.php?id=5&page=<?php if (($_GET['page'] - 1) > 0) {
                                                                    echo $_GET['page'] - 1;
                                                                } else {
                                                                    echo 1;
                                                                } ?>" class="btn btn-default">
                                        < </a>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <a href="info.php?id=5&page=<?= $_GET['id'] ?>&page=<?= $i ?>" class="btn btn-default <?php if ($i == $_GET['page']) {
                                                                                                                                            echo 'active';
                                                                                                                                        } ?>"><?= $i ?></a>
                                            <?php } ?>
                                            <a href="info.php?id=5&page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                                            echo $_GET['page'] + 1;
                                                                        } else {
                                                                            echo $i - 1;
                                                                        } ?>" class="btn btn-default"> > </a>
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
                                            <td>
                                                <form method="post"><input type="submit" name="clients" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Visit</td>
                                            <td>
                                                <form method="post"><input type="submit" name="visits" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Laboratory</td>
                                            <td>
                                                <form method="post"><input type="submit" name="lab" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Study IDs</td>
                                            <td>
                                                <form method="post"><input type="submit" name="study_id" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Sites</td>
                                            <td>
                                                <form method="post"><input type="submit" name="sites" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Screening</td>
                                            <td>
                                                <form method="post"><input type="submit" name="screening" value="Download"></form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 7) { ?>
                        <div class="col-md-2">
                            <?php $patient = $override->get('clients', 'id', $_GET['cid'])[0] ?>
                            <div class="ucard clearfix">
                                <div class="right">
                                    <div class="image">
                                        <?php if ($patient['client_image'] != '' || is_null($patient['client_image'])) {
                                            $img = $patient['client_image'];
                                        } else {
                                            $img = 'img/users/blank.png';
                                        } ?>
                                        <a href="#"><img src="<?= $img ?>" width="300" class="img-thumbnail"></a>
                                    </div>
                                    <h5><?= 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] ?></h5>
                                    <h4><strong style="font-size: medium">Screening ID: <?= $patient['participant_id'] ?></strong></h4>
                                    <h4><strong style="font-size: larger">Study ID: <?= $patient['study_id'] ?></strong></h4>
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
                                        <?php if ($_GET['seq'] == 1) { ?>

                                            <tr>
                                                <td>1</td>
                                                <td>Demographic</td>
                                                <?php if ($override->get('demographic', 'patient_id', $_GET['cid'])) { ?>
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php }  ?>

                                        <tr>
                                            <td>2</td>
                                            <td>Vitals</td>
                                            <?php if ($override->get('vital', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>Patient Hitory & Complication</td>
                                            <?php if ($override->get('history', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td>Family History(Symtom & Exam)</td>
                                            <?php if ($override->get('symptoms', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td>Main diagnosis</td>
                                            <?php if ($override->get('diagnosis', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>6</td>
                                            <td>Results at enrollment</td>
                                            <?php if ($override->get('results', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>7</td>
                                            <td>Hospitalization</td>
                                            <?php if ($override->get('hospitalization', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>8</td>
                                            <td>Treatment Plan</td>
                                            <?php if ($override->get('treatment_plan', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>9</td>
                                            <td>Diagnosis, Complications, & Comorbidities</td>
                                            <?php if ($override->get('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td>RISK</td>
                                            <?php if ($override->get('risks', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>Hospitalization Details</td>
                                            <?php if ($override->get('hospitalization_details', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>Lab Details</td>
                                            <?php if ($override->get('lab_details', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>12</td>
                                            <td>Summary</td>
                                            <?php if ($override->get('summary', 'patient_id', $_GET['cid'])) { ?>
                                                <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>13</td>
                                            <td>Social Economic</td>
                                            <td><a href="#" class="btn btn-warning"> Add </a> </td>
                                        </tr>

                                        <?php if ($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 111)) { ?>

                                            <tr>
                                                <td>9</td>
                                                <td>Cardiac</td>
                                                <?php if ($override->get('cardiac', 'patient_id', $_GET['cid'])) { ?>
                                                    <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>" class="btn btn-success" disabled=> CHange </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                            <tr>
                                                <td>10</td>
                                                <td>Diabetes</td>
                                                <?php if ($override->get('diabetic', 'patient_id', $_GET['cid'])) { ?>
                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>

                                        <?php if ($override->get2('diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                            <tr>
                                                <td>11</td>
                                                <td>Sickle cell</td>
                                                <?php if ($override->get('sickle_cell', 'patient_id', $_GET['cid'])) { ?>
                                                    <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>" class="btn btn-success" disabled=""> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>

                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Clear Data on Table</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Table Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="clear_data" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 9) { ?>

                    <?php } elseif ($_GET['id'] == 10) { ?>

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