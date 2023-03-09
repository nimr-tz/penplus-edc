<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage = null;
$pageError = null;
$errorMessage = null;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_user')) {
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
                'site_id' => array(
                    'required' => true,
                ),
                'username' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'email_address' => array(
                    'unique' => 'user'
                ),
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
                    $user->createRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'username' => Input::get('username'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'password' => Hash::make($password, $salt),
                        'salt' => $salt,
                        'create_on' => date('Y-m-d'),
                        'last_login' => '',
                        'status' => 1,
                        'power' => 0,
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                        'user_id' => $user->data()->id,
                        'site_id' => Input::get('site_id'),
                        'count' => 0,
                        'pswd' => 0,
                    ));
                    $successMessage = 'Account Created Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_position')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('position', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Position Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_site')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('site', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Site Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_client')) {
            $validate = new validate();
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
                $errorM = false;
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
                    if ($errorM == false) {
                        $chk = true;
                        $screening_id = $random->get_rand_alphanumeric(8);
                        $check_screening = $override->get('clients', 'participant_id', $screening_id)[0];
                        while ($chk) {
                            $screening_id = strtoupper($random->get_rand_alphanumeric(8));
                            if (!$check_screening = $override->get('clients', 'participant_id', $screening_id)) {
                                $chk = false;
                            }
                        }
                        $age = $user->dateDiffYears(date('Y-m-d'), Input::get('dob'));

                        $user->createRecord('clients', array(
                            'participant_id' => $screening_id,
                            'study_id' => '',
                            'clinic_date' => Input::get('clinic_date'),
                            'firstname' => Input::get('firstname'),
                            'middlename' => Input::get('middlename'),
                            'lastname' => Input::get('lastname'),
                            'dob' => Input::get('dob'),
                            'age' => Input::get('age'),
                            'id_number' => Input::get('id_number'),
                            'gender' => Input::get('gender'),
                            'site_id' => $user->data()->site_id,
                            'staff_id' => $user->data()->id,
                            'client_image' => $attachment_file,
                            'comments' => Input::get('comments'),
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                        ));

                        $client = $override->lastRow('clients', 'id')[0];

                        $user->createRecord('visit', array(
                            'visit_name' => 'Day 0',
                            'visit_code' => 'D0',
                            'visit_date' => date('Y-m-d'),
                            'visit_window' => 2,
                            'status' => 1,
                            'seq_no' => 0,
                            'client_id' => $client['id'],
                        ));

                        $successMessage = 'Client Added Successful';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_screening')) {
            $validate = $validate->check($_POST, array(
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
                    if (Input::get('age_6_above') == 1 && Input::get('consent') == 1 && Input::get('scd') == 2 && Input::get('rhd') == 2 && Input::get('residence') == 2) {
                        $eligibility = 1;
                    } else {
                        $eligibility = 0;
                    }
                    $user->createRecord('screening', array(
                        'age_6_above' => Input::get('age_6_above'),
                        'consent' => Input::get('consent'),
                        'scd' => Input::get('scd'),
                        'rhd' => Input::get('rhd'),
                        'residence' => Input::get('residence'),
                        'created_on' => date('Y-m-d'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'eligibility' => $eligibility,
                    ));

                    $user->updateRecord('clients', array(
                        'screened' => 1, 'eligibility' => $eligibility,
                    ), $_GET['cid']);
                    $successMessage = 'Patient Successful Screened';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_demographic')) {
            $validate = $validate->check($_POST, array(
                'phone_number' => array(
                    'required' => true,
                ),
                'next_visit' => array(
                    'required' => true,
                ),
                'physical_address' => array(
                    'required' => true,
                ),
                'chw' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('demographic', array(
                        'employment_status' => Input::get('employment_status'),
                        'education_level' => Input::get('education_level'),
                        'phone_number' => Input::get('phone_number'),
                        'guardian_phone' => Input::get('guardian_phone'),
                        'relation_patient' => Input::get('relation_patient'),
                        'physical_address' => Input::get('physical_address'),
                        'household_size' => Input::get('household_size'),
                        'occupation' => Input::get('occupation'),
                        'exposure' => Input::get('exposure'),
                        'grade_age' => Input::get('grade_age'),
                        'school_attendance' => Input::get('school_attendance'),
                        'missed_school' => Input::get('missed_school'),
                        'next_visit' => Input::get('next_visit'),
                        'chw' => Input::get('chw'),
                        'comments' => Input::get('comments'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));

                    $user->updateRecord('clients', array(
                        'enrolled' => 1,
                    ), $_GET['cid']);

                    $successMessage = 'Demographic added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_diagnosis')) {
            $validate = $validate->check($_POST, array(
                'cardiac' => array(
                    'required' => true,
                ),
                'diabetes' => array(
                    'required' => true,
                ),
                'sickle_cell' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('diagnosis', array(
                        'cardiac' => Input::get('cardiac'),
                        'diabetes' => Input::get('diabetes'),
                        'sickle_cell' => Input::get('sickle_cell'),
                        'diagnosis' => Input::get('diagnosis'),
                        'outcome' => Input::get('outcome'),
                        'transfer_out' => Input::get('transfer_out'),
                        'cause_death' => Input::get('cause_death'),
                        'next_appointment' => Input::get('next_appointment'),
                        'comments' => Input::get('comments'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));

                    $user->updateRecord('clients', array(
                        'cardiac' => Input::get('cardiac'),
                        'diabetes' => Input::get('diabetes'),
                        'sickle_cell' => Input::get('sickle_cell'),
                    ), $_GET['cid']);


                    $successMessage = 'Diagnosis added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_cardiac')) {
            $validate = $validate->check($_POST, array(
                'main_diagnosis' => array(
                    'required' => true,
                ),


            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('cardiac', array(
                        'main_diagnosis' => Input::get('main_diagnosis'),
                        'diagnosis_date' => Input::get('diagnosis_date'),
                        'cardiomyopathy' => Input::get('cardiomyopathy'),
                        'heumatic' => Input::get('heumatic'),
                        'congenital' => Input::get('congenital'),
                        'heart_failure' => Input::get('heart_failure'),
                        'pericardial' => Input::get('pericardial'),
                        'arrhythmia' => Input::get('arrhythmia'),
                        'stroke' => Input::get('stroke'),
                        'thromboembolic' => Input::get('thromboembolic'),
                        'referred' => Input::get('referred'),
                        'comments' => Input::get('comments'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));


                    $successMessage = 'Cardiac added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_diabetic')) {
            $validate = $validate->check($_POST, array(
                'hypertension' => array(
                    'required' => true,
                ),


            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('diabetic', array(
                        'hypertension' => Input::get('hypertension'),
                        'symptoms' => Input::get('symptoms'),
                        'cardiovascular' => Input::get('cardiovascular'),
                        'retinopathy' => Input::get('retinopathy'),
                        'renal_disease' => Input::get('renal_disease'),
                        'stroke' => Input::get('stroke'),
                        'pvd' => Input::get('pvd'),
                        'neuropathy' => Input::get('neuropathy'),
                        'sexual_dysfunction' => Input::get('sexual_dysfunction'),
                        'comments' => Input::get('comments'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));


                    $successMessage = 'Diabetic added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_scd')) {
            $validate = $validate->check($_POST, array(
                'history_scd' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('sickle_cell', array(
                        'history_scd' => Input::get('history_scd'),
                        'scd_test' => Input::get('scd_test'),
                        'confirmatory_test' => Input::get('confirmatory_test'),
                        'confirmatory_test_type' => Input::get('confirmatory_test_type'),
                        'vaccine_history' => Input::get('vaccine_history'),
                        'blood_group' => Input::get('blood_group'),
                        'comments' => Input::get('comments'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));


                    $successMessage = 'Sickle Cell added Successful';
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
    <title> Add - PenPLus </title>
    <?php include "head.php"; ?>

    <style>
        /* #box {
            display: none;
            background-color: salmon;
            color: white;
            width: 100px;
            height: 100px;
        } */
    </style>
</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">


            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                    <li class="active">Add Info</li>
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
                                <h1>Add User</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">First Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="firstname" id="firstname" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Last Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="lastname" id="lastname" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Username:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="username" id="username" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Site</div>
                                        <div class="col-md-9">
                                            <select name="site_id" style="width: 100%;" required>
                                                <option value="">Select site</option>
                                                <?php foreach ($override->getData('site') as $site) { ?>
                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Position</div>
                                        <div class="col-md-9">
                                            <select name="position" style="width: 100%;" required>
                                                <option value="">Select position</option>
                                                <?php foreach ($override->getData('position') as $position) { ?>
                                                    <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Phone Number:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">E-mail Address:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" /> <span>Example: someone@nowhere.com</span></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_user" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 2 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Position</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_position" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 3 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Study</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name: </div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" required />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">PI</div>
                                        <div class="col-md-9">
                                            <select name="pi" style="width: 100%;" required>
                                                <option value="">Select staff</option>
                                                <?php foreach ($override->getData('user') as $staff) { ?>
                                                    <option value="<?= $staff['id'] ?>"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Coordinator</div>
                                        <div class="col-md-9">
                                            <select name="coordinator" style="width: 100%;" required>
                                                <option value="">Select staff</option>
                                                <?php foreach ($override->getData('user') as $staff) { ?>
                                                    <option value="<?= $staff['id'] ?>"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Doctor</div>
                                        <div class="col-md-9">
                                            <select name="doctor" style="width: 100%;" required>
                                                <option value="">Select staff</option>
                                                <?php foreach ($override->getData('user') as $staff) { ?>
                                                    <option value="<?= $staff['id'] ?>"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Start Date:</div>
                                        <div class="col-md-9"><input type="text" name="start_date" id="mask_date" required /> <span>Example: 04/10/2012</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">End Date:</div>
                                        <div class="col-md-9"><input type="text" name="end_date" id="mask_date" required /> <span>Example: 04/10/2012</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Study details:</div>
                                        <div class="col-md-9"><textarea name="details" rows="4" required></textarea></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_study" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 4) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Client</h1>
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
                                        <div class="col-md-3">Date:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date" /> <span>Example: 2010-12-01</span>
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">First Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="firstname" id="firstname" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Middle Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="middlename" id="middlename" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Last Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="lastname" id="lastname" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Age:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="number" name="age" id="age" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Date of Birth:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required,custom[date]]" type="text" name="dob" id="date" /> <span>Example: 2010-12-01</span>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Gender</div>
                                        <div class="col-md-9">
                                            <select name="gender" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ID Number:</div>
                                        <div class="col-md-9">
                                            <input value="" type="text" name="id_number" id="id_number" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_client" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 5 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Study</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Code:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="code" id="code" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Sample Size:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="number" name="sample_size" id="sample_size" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Start Date:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required,custom[date]]" type="text" name="start_date" id="start_date" /> <span>Example: 2010-12-01</span>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">End Date:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required,custom[date]]" type="text" name="end_date" id="end_date" /> <span>Example: 2010-12-01</span>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_study" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 6 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Site</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_site" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 7) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Visit</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Visit Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_site" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <div class="col-md-offset-1 col-md-8">

                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Screening</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Aged 6 years and above </div>
                                        <div class="col-md-4">
                                            <select name="age_6_above" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Consenting individuals</div>
                                        <div class="col-md-4">
                                            <select name="consent" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Known SCD</div>
                                        <div class="col-md-4">
                                            <select name="scd" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Diabetes, RHD patients,</div>
                                        <div class="col-md-4">
                                            <select name="rhd" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Non permanent resident</div>
                                        <div class="col-md-4">
                                            <select name="residence" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_screening" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 9) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Demographic</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Education Level</div>
                                        <div class="col-md-9">
                                            <select name="education_level" style="width: 100%;" required>
                                                <option value="">Select</option>
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

                                    <?php if ($override->get4('clients', 'id', $_GET['cid'], 'age')) { ?>

                                        <div id="adult">
                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Employment status</div>
                                                <div class="col-md-9">
                                                    <select name="employment_status" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="Employed">Employed</option>
                                                        <option value="Self-employed">Self-employed</option>
                                                        <option value="Employed but on leave of absence">Employed but on leave of absence</option>
                                                        <option value="Unemployed">Unemployed</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Occupational Exposures:</div>
                                                <div class="col-md-9">
                                                    <select name="occupation" id="occupation" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unknown</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="list_exposure">
                                                <div class="row-form clearfix">
                                                    <div class="col-md-3">If yes, list exposure: :</div>
                                                    <div class="col-md-9"><textarea name="exposure" rows="4"></textarea> </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (!$override->get4('clients', 'id', $_GET['cid'], 'age')) { ?>

                                        <div id="child">
                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Appropriate grade for age:</div>
                                                <div class="col-md-9">
                                                    <select name="grade_age" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">N/A</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row-form clearfix">
                                                <div class="col-md-3">NCD limiting school attendance:</div>
                                                <div class="col-md-9">
                                                    <select name="school_attendance" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">N/A</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Days of missed school in past month:</div>
                                                <div class="col-md-9"><input value="" class="" type="number" min="1" name="missed_school" id="missed_school" /></div>
                                            </div>
                                        </div>

                                    <?php } ?>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient referred from:</div>
                                        <div class="col-md-9">
                                            <select name="next_visit" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Inpatient / hospital stay</option>
                                                <option value="2">Primary care clinic</option>
                                                <option value="3">Other outpatient clinic</option>
                                                <option value="4">Maternal health</option>
                                                <option value="5">Community</option>
                                                <option value="6">Self</option>
                                                <option value="7">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Other Specify:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Phone Number:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Guardian Phone Number:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="guardian_phone" id="guardian_phone" /> <span>Example: 0700 000 111</span></div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Relation to patient:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="relation_patient" id="relation_patient" required /></div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Physical Address:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="physical_address" id="physical_address" required /></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Household Size:</div>
                                        <div class="col-md-9"><input value="" class="" type="number" min="1" name="household_size" id="household_size" /></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Agrees to home visits</div>
                                        <div class="col-md-9">
                                            <select name="next_visit" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">CHW name:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="chw" id="chw" /></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_demographic" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 10) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diagnosis</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Cardiac</div>
                                        <div class="col-md-9">
                                            <select name="cardiac" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Diabetes</div>
                                        <div class="col-md-9">
                                            <select name="diabetes" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Sickle cell</div>
                                        <div class="col-md-9">
                                            <select name="sickle_cell" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Type of diagnosis:</div>
                                        <div class="col-md-9">
                                            <select name="diagnosis" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="Type 1 Diabetes">Type 1 Diabetes</option>
                                                <option value="Type 2 Diabetes ">Type 2 Diabetes </option>
                                                <option value="Cardiac">Cardiac</option>
                                                <option value="Sickle Cell Disease">Sickle Cell Disease </option>
                                                <option value="Respiratory">Respiratory</option>
                                                <option value="Liver">Liver</option>
                                                <option value="Kidney">Kidney</option>
                                                <option value="Postgraduate degree">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Outcome</div>
                                        <div class="col-md-9">
                                            <select name="outcome" id="outcome" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">On treatment</option>
                                                <option value="2">Default</option>
                                                <option value="3">Stop Treatment</option>
                                                <option value="4">Transfer Out</option>
                                                <option value="5">Death</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div id="hidden_div" style="display:none;"> -->
                                    <div id="transfer_to">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Transfer Out To</div>
                                            <div class="col-md-9">
                                                <select name="transfer_out" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">Other NCD clinic</option>
                                                    <option value="2">Referral hospital</option>
                                                    <option value="3">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="death">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Cause of Death</div>
                                            <div class="col-md-9">
                                                <select name="cause_death" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">NCD</option>
                                                    <option value="2">Unknown</option>
                                                    <option value="3">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Next Appointment:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="next_appointment" id="next_appointment" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_diagnosis" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 11) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Cardiac</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Main diagnosis:</div>
                                        <div class="col-md-9">
                                            <select name="main_diagnosis" id="main_diagnosis" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Cardiomyopathy</option>
                                                <option value="2">Rheumatic Heart Disease</option>
                                                <option value="3">Severe / Uncontrolled Hypertension</option>
                                                <option value="4">Hypertensive Heart Disease</option>
                                                <option value="5">Congenital heart Disease</option>
                                                <option value="6">Right Heart Failure</option>
                                                <option value="7">Pericardial disease</option>
                                                <option value="8">Coronary Artery Disease</option>
                                                <option value="9">Arrhythmia</option>
                                                <option value="10">Thromboembolic</option>
                                                <option value="11">Stroke</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Diagnosis Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div id="Cardiomyopathy">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Cardiomyopathy</div>
                                            <div class="col-md-9">
                                                <select name="cardiomyopathy" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">Ischemic</option>
                                                    <option value="2">Alcohol-related</option>
                                                    <option value="3">Peripartum</option>
                                                    <option value="4">Arrhythmia-related </option>
                                                    <option value="5">HIV-related</option>
                                                    <option value="6">Chemotherapy-related </option>
                                                    <option value="7">Viral/idiopathic </option>
                                                    <option value="8">Other </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="heumatic">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If heumatic Heart Disease</div>
                                            <div class="col-md-9">
                                                <select name="heumatic" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">Pure mitral stenosis</option>
                                                    <option value="2">Pure mitral regurgitation</option>
                                                    <option value="3">Mixed mitral valve disease (MS + MR) </option>
                                                    <option value="4">Isolated aortic valve disease (AVD)</option>
                                                    <option value="5">Mixed mitral and aortic valve disease (MMAVD) </option>
                                                    <option value="6">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Congenital">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Congenital heart Disease</div>
                                            <div class="col-md-9">
                                                <select name="congenital" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">ASD</option>
                                                    <option value="2">VSD</option>
                                                    <option value="3">PDA</option>
                                                    <option value="4">Coarctation of aorta </option>
                                                    <option value="5">Tetralogy of Fallot</option>
                                                    <option value="6">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="Failure">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Right Heart Failure</div>
                                            <div class="col-md-9">
                                                <select name="heart_failure" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="Pericardial">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Pericardial disease</div>
                                            <div class="col-md-9">
                                                <select name="pericardial" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Tuberculosis</option>
                                                    <option value="2">HIV</option>
                                                    <option value="3">malignancy</option>
                                                    <option value="4">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="Arrhythmia">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Arrhythmia</div>
                                            <div class="col-md-9">
                                                <select name="arrhythmia" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Atrial fibrillation </option>
                                                    <option value="2">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="Thromboembolic">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Thromboembolic</div>
                                            <div class="col-md-9">
                                                <select name="thromboembolic" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="1">pulmonary embolism </option>
                                                    <option value="2">DVT</option>
                                                    <option value="3">other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="Stroke">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Stroke</div>
                                            <div class="col-md-9">
                                                <select name="stroke" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option value="1">Ischemic</option>
                                                    <option value="2">hemorrhagic</option>
                                                    <option value="3">unknown</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient referred from: </div>
                                        <div class="col-md-9">
                                            <select name="referred" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Inpatient /hospital stay </option>
                                                <option value="2">Primary care clinic</option>
                                                <option value="3">Other outpatient clinic </option>
                                                <option value="4">Maternal health </option>
                                                <option value="5">Community</option>
                                                <option value="6">Self</option>
                                                <option value="7">Other</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_cardiac" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 12) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diabetic</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Main diagnosis:</div>
                                        <div class="col-md-9">
                                            <select name="main_diagnosis" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Type 1 DM</option>
                                                <option value="2">Type 2 DM</option>
                                                <option value="3">Gestational DM</option>
                                                <option value="4">DM not yet specified</option>
                                                <option value="5">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Diagnosis Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Hypertension:</div>
                                        <div class="col-md-9">
                                            <select name="hypertension" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Presentation with any of the following?</div>
                                        <div class="col-md-9">
                                            <select name="symptoms" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">DKA with coma </option>
                                                <option value="2">Ketosis</option>
                                                <option value="3">Hyperglycemia </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Cardiovascular Disease </div>
                                        <div class="col-md-9">
                                            <select name="cardiovascular" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Retinopathy</div>
                                        <div class="col-md-9">
                                            <select name="retinopathy" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Renal disease (e.g. elevated creatinine)</div>
                                        <div class="col-md-9">
                                            <select name="renal_disease" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Stroke/TIA</div>
                                        <div class="col-md-9">
                                            <select name="stroke" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">PVD (e.g. ulcers, gangrene)</div>
                                        <div class="col-md-9">
                                            <select name="pvd" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Neuropathy</div>
                                        <div class="col-md-9">
                                            <select name="neuropathy" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Sexual dysfunction</div>
                                        <div class="col-md-9">
                                            <select name="sexual_dysfunction" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_diabetic" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 13) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>SICKLE CELL</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Main diagnosis:</div>
                                            <div class="col-md-9">
                                                <select name="main_diagnosis" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Sickle Cell Disease</option>
                                                    <option value="5">Other Hemoglobinopathy</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Diagnosis Date:</div>
                                            <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                        </div>

                                        <div class="col-md-3">Family History of SCD?:</div>
                                        <div class="col-md-9">
                                            <select name="history_scd" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">SCD Test Result?</div>
                                        <div class="col-md-9">
                                            <select name="scd_test" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Presumptive Diagnosis</option>
                                                <option value="2">Sickling Test</option>
                                                <option value="3">SS </option>
                                                <option value="4">SA </option>
                                                <option value="5">SBThal </option>
                                                <option value="6">SC </option>
                                                <option value="7">Other </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Confirmatory Test </div>
                                        <div class="col-md-9">
                                            <select name="confirmatory_test" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Type of Confirmatory Test</div>
                                        <div class="col-md-9">
                                            <select name="confirmatory_test_type" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">HPLC</option>
                                                <option value="2">HBE</option>
                                                <option value="3">IEF</option>
                                                <option value="4">Basique</option>
                                                <option value="5">Acide</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Vaccine History</div>
                                        <div class="col-md-9">
                                            <select name="vaccine_history" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Pneumococcal </option>
                                                <option value="2">Meningococcal</option>
                                                <option value="3">Haemophilus Influenza type B (Hib)</option>
                                                <option value="4">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ABO Blood Group</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">A+</option>
                                                <option value="2">A-</option>
                                                <option value="3">B+</option>
                                                <option value="4">B-</option>
                                                <option value="5">O+</option>
                                                <option value="6">O-</option>
                                                <option value="7">AB+</option>
                                                <option value="8">AB</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_scd" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 14) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>VITAL SIGNS</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Ht (cm):</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Wt (kg):</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">MUAC (cm):</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">BP:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">PR:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_scd" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 15) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Patient Hitory & Complication</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Diseases History</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Hypertension</option>
                                                <option value="2">Diabetes</option>
                                                <option value="3">CKD</option>
                                                <option value="4">Depression</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">HIV</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">R</option>
                                                <option value="2">NR</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ART Start Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">TB</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Smear pos</option>
                                                <option value="2">Smear neg</option>
                                                <option value="3">EPTB</option>
                                                <option value="4">never had TB</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Year TB tested:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">History of smoking</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Number of pack years:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Active smoker</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Alcohol consumption</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes, currently</option>
                                                <option value="2">Yes, in the past</option>
                                                <option value="3">never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Quantity (number of bottle):</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Family History of cardiac disease?</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                            <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">History of cardiac surgery?</div>
                                        <div class="col-md-9">
                                            <select name="blood_group" style="width: 100%;" required>
                                            <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Specify surgery:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_scd" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 16) { ?>

                    <?php } elseif ($_GET['id'] == 17) { ?>

                    <?php } elseif ($_GET['id'] == 18) { ?>

                    <?php } elseif ($_GET['id'] == 19) { ?>

                    <?php } ?>
                    <div class="dr"><span></span></div>
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

        $(document).ready(function() {
            $('#fl_wait').hide();
            $('#wait_ds').hide();
            $('#region').change(function() {
                var getUid = $(this).val();
                $('#wait_ds').show();
                $.ajax({
                    url: "process.php?cnt=region",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#ds_data').html(data);
                        $('#wait_ds').hide();
                    }
                });
            });
            $('#wait_wd').hide();
            $('#ds_data').change(function() {
                $('#wait_wd').hide();
                var getUid = $(this).val();
                $.ajax({
                    url: "process.php?cnt=district",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#wd_data').html(data);
                        $('#wait_wd').hide();
                    }
                });

            });

            $('#a_cc').change(function() {
                var getUid = $(this).val();
                $('#wait').show();
                $.ajax({
                    url: "process.php?cnt=payAc",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#cus_acc').html(data);
                        $('#wait').hide();
                    }
                });

            });


            // $('#study_id').change(function() {
            //     var getUid = $(this).val();
            //     var type = $('#type').val();
            //     $('#fl_wait').show();
            //     $.ajax({
            //         url: "process.php?cnt=study",
            //         method: "GET",
            //         data: {
            //             getUid: getUid,
            //             type: type
            //         },
            //         success: function(data) {
            //             $('#s2_2').html(data);
            //             $('#fl_wait').hide();
            //         }
            //     });

            // });


            $('#study_id').change(function() {
                var getUid = $(this).val();
                var type = $('#type').val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid,
                        type: type
                    },

                    success: function(data) {
                        console.log(data);
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });

        });


        // $('#outcome').change(function() {
        //     $('#hidden_div').hide();

        //     var getUid = $(this).val();
        //     // $('#wait').show();
        //     // alert(getUid);
        //     function showDiv(select) {
        //         // if (select.value === 4) {
        //         if (getUid === 4) {
        //             $('#hidden_div').show();
        //             // document.getElementById("hidden_div").style.display = "block";
        //         } else {
        //             document.getElementById("hidden_div").style.display = "none";
        //         }
        //     }

        // });

        const el = document.getElementById('outcome');

        const transfer_to = document.getElementById('transfer_to');
        const death = document.getElementById('death');


        el.addEventListener('change', function handleChange(event) {
            if (event.target.value === '4') {
                transfer_to.style.display = 'block';
            } else if (event.target.value === '5') {
                death.style.display = 'block';
            } else {
                transfer_to.style.display = 'none';
                death.style.display = 'none';
            }
        });


        const oc = document.getElementById('occupation');


        const list_exposure = document.getElementById('list_exposure');

        oc.addEventListener('change', function handleChange(event) {
            if (event.target.value === '1') {
                list_exposure.style.display = 'block';
            } else {
                list_exposure.style.display = 'none';
            }
        });




        const diagnosis = document.getElementById('main_diagnosis');

        const Cardiomyopathy = document.getElementById('Cardiomyopathy');
        const heumatic = document.getElementById('heumatic');
        const Congenital = document.getElementById('Congenital');
        const Failure = document.getElementById('Failure');
        const Pericardial = document.getElementById('Pericardial');
        const Arrhythmia = document.getElementById('Arrhythmia');
        const Thromboembolic = document.getElementById('Thromboembolic');
        const Stroke = document.getElementById('Stroke');


        diagnosis.addEventListener('change', function handleChange(event) {
            if (event.target.value === '1') {
                Cardiomyopathy.style.display = 'block';
            } else if (event.target.value === '2') {
                heumatic.style.display = 'block';
            } else if (event.target.value === '5') {
                Congenital.style.display = 'block';
            } else if (event.target.value === '6') {
                Failure.style.display = 'block';
            } else if (event.target.value === '7') {
                Pericardial.style.display = 'block';
            } else if (event.target.value === '8') {
                Arrhythmia.style.display = 'block';
            } else if (event.target.value === '9') {
                Thromboembolic.style.display = 'block';
            } else if (event.target.value === '10') {
                Stroke.style.display = 'block';
            } else {
                Cardiomyopathy.style.display = 'none';
                heumatic.style.display = 'none';
                Congenital.style.display = 'none';
                Failure.style.display = 'none';
                Pericardial.style.display = 'none';
                Arrhythmia.style.display = 'none';
                Thromboembolic.style.display = 'none';
                Stroke.style.display = 'none';
            }
        });
    </script>
</body>

</html>