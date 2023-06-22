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
        } elseif (Input::get('add_medication')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
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
                    $medications = $override->get('medications', 'name', Input::get('name'));
                    if ($medications) {
                        $user->updateRecord('medications', array(
                            'name' => Input::get('name'),
                            'cardiac' => Input::get('cardiac'),
                            'diabetes' => Input::get('diabetes'),
                            'sickle_cell' => Input::get('sickle_cell'),
                            'status' => 1,
                        ), $medications[0]['id']);
                    } else {
                        $user->createRecord('medications', array(
                            'name' => Input::get('name'),
                            'cardiac' => Input::get('cardiac'),
                            'diabetes' => Input::get('diabetes'),
                            'sickle_cell' => Input::get('sickle_cell'),
                            'status' => 1,
                        ));
                    }
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
        } elseif (Input::get('add_visit')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'code' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('schedule', array(
                        'name' => Input::get('name'),
                        'code' => Input::get('code'),
                    ));
                    $successMessage = 'Schedule Successful Added';
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


                        if ($override->get('clients', 'id', $_GET['cid'])) {
                            $user->updateRecord('clients', array(
                                'hospital_id' => Input::get('hospital_id'),
                                'clinic_date' => Input::get('clinic_date'),
                                'firstname' => Input::get('firstname'),
                                'middlename' => Input::get('middlename'),
                                'lastname' => Input::get('lastname'),
                                'dob' => Input::get('dob'),
                                'age' => $age,
                                'gender' => Input::get('gender'),
                                'employment_status' => Input::get('employment_status'),
                                'education_level' => Input::get('education_level'),
                                'occupation' => Input::get('occupation'),
                                'exposure' => Input::get('exposure'),
                                'phone_number' => Input::get('phone_number'),
                                'guardian_phone' => Input::get('guardian_phone'),
                                'guardian_name' => Input::get('guardian_name'),
                                'relation_patient' => Input::get('relation_patient'),
                                'physical_address' => Input::get('physical_address'),
                                'client_image' => $attachment_file,
                                'comments' => Input::get('comments'),
                            ), $_GET['cid']);
                        } else {
                            $user->createRecord('clients', array(
                                'participant_id' => $screening_id,
                                'study_id' => '',
                                'hospital_id' => Input::get('hospital_id'),
                                'clinic_date' => Input::get('clinic_date'),
                                'firstname' => Input::get('firstname'),
                                'middlename' => Input::get('middlename'),
                                'lastname' => Input::get('lastname'),
                                'dob' => Input::get('dob'),
                                'age' => $age,
                                'gender' => Input::get('gender'),
                                'employment_status' => Input::get('employment_status'),
                                'education_level' => Input::get('education_level'),
                                'occupation' => Input::get('occupation'),
                                'exposure' => Input::get('exposure'),
                                'phone_number' => Input::get('phone_number'),
                                'guardian_phone' => Input::get('guardian_phone'),
                                'guardian_name' => Input::get('guardian_name'),
                                'relation_patient' => Input::get('relation_patient'),
                                'physical_address' => Input::get('physical_address'),
                                'site_id' => $user->data()->site_id,
                                'staff_id' => $user->data()->id,
                                'client_image' => $attachment_file,
                                'comments' => Input::get('comments'),
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                            ));

                            $last_row = $override->lastRow('clients', 'id')[0];

                            $user->createRecord('visit', array(
                                'study_id' => '',
                                'visit_name' => 'Registration Visit',
                                'visit_code' => 'RV',
                                'visit_day' => 'Day -1',
                                'expected_date' => Input::get('clinic_date'),
                                'visit_date' => Input::get('clinic_date'),
                                'visit_window' => 0,
                                'status' => 1,
                                'client_id' => $last_row['id'],
                                'created_on' => date('Y-m-d'),
                                'seq_no' => -1,
                                'reasons' => '',
                                'visit_status' => 1,
                            ));
                        }

                        $successMessage = 'Client Added Successful';
                        // Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                        Redirect::to('info.php?id=3&status=5');
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_demographic')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'next_visit' => array(
                    'required' => true,
                ),
                'referred' => array(
                    'required' => true,
                ),
                'chw' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {

                    $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($demographic) {
                        $user->updateRecord('demographic', array(
                            'visit_date' => Input::get('visit_date'),
                            'household_size' => Input::get('household_size'),
                            'grade_age' => Input::get('grade_age'),
                            'school_attendance' => Input::get('school_attendance'),
                            'missed_school' => Input::get('missed_school'),
                            'next_visit' => Input::get('next_visit'),
                            'chw' => Input::get('chw'),
                            'comments' => Input::get('comments'),
                            'referred' => Input::get('referred'),
                            'referred_other' => Input::get('referred_other'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $demographic['id']);
                    } else {
                        $user->createRecord('demographic', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'household_size' => Input::get('household_size'),
                            'grade_age' => Input::get('grade_age'),
                            'school_attendance' => Input::get('school_attendance'),
                            'missed_school' => Input::get('missed_school'),
                            'next_visit' => Input::get('next_visit'),
                            'chw' => Input::get('chw'),
                            'comments' => Input::get('comments'),
                            'referred' => Input::get('referred'),
                            'referred_other' => Input::get('referred_other'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Demographic added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_main_diagnosis')) {
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

                    $main_diagnosis = $override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    // if ((Input::get('cardiac') == 1 && Input::get('diabetes') == 1 && Input::get('sickle_cell') == 1)
                    //     || (Input::get('cardiac') == 1 && Input::get('diabetes') == 1)
                    //     || (Input::get('cardiac') == 1 && Input::get('sickle_cell') == 1)
                    //     || (Input::get('diabetes') == 1 && Input::get('sickle_cell') == 1)
                    // ) {
                    //     $errorMessage = 'Patient Diagnosed with more than one Disease';
                    // } else {

                    if ($main_diagnosis) {

                        $user->updateRecord('main_diagnosis', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'cardiac' => Input::get('cardiac'),
                            'diabetes' => Input::get('diabetes'),
                            'sickle_cell' => Input::get('sickle_cell'),
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $main_diagnosis['id']);
                    } else {
                        $user->createRecord('main_diagnosis', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'cardiac' => Input::get('cardiac'),
                            'diabetes' => Input::get('diabetes'),
                            'sickle_cell' => Input::get('sickle_cell'),
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }


                    $user->updateRecord('clients', array(
                        'cardiac' => Input::get('cardiac'),
                        'diabetes' => Input::get('diabetes'),
                        'sickle_cell' => Input::get('sickle_cell'),
                    ), $_GET['cid']);


                    $successMessage = 'Diagnosis added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                    // }
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

                    $diabetic = $override->get3('diabetic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($diabetic) {
                        $user->updateRecord('diabetic', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'diagnosis' => Input::get('diagnosis'),
                            'hypertension' => Input::get('hypertension'),
                            'hypertension_date' => Input::get('hypertension_date'),
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
                        ), $diabetic['id']);
                    } else {
                        $user->createRecord('diabetic', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'diagnosis' => Input::get('diagnosis'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hypertension' => Input::get('hypertension'),
                            'hypertension_date' => Input::get('hypertension_date'),
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
                    }
                    $successMessage = 'Diabetic added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
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

                    $sickle_cell = $override->get3('sickle_cell', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($sickle_cell) {
                        $user->updateRecord('sickle_cell', array(
                            'diagnosis' => Input::get('diagnosis'),
                            'visit_date' => Input::get('diagnosis_date'),
                            'history_scd' => Input::get('history_scd'),
                            'scd_test' => Input::get('scd_test'),
                            'scd_test_other' => Input::get('scd_test_other'),
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
                        ), $sickle_cell['id']);
                    } else {
                        $user->createRecord('sickle_cell', array(
                            'diagnosis' => Input::get('diagnosis'),
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'history_scd' => Input::get('history_scd'),
                            'scd_test' => Input::get('scd_test'),
                            'scd_test_other' => Input::get('scd_test_other'),
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
                    }
                    $successMessage = 'Sickle Cell added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_vital')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {

                    $vital = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($vital) {
                        $user->updateRecord('vital', array(
                            'visit_date' => Input::get('visit_date'),
                            'height' => Input::get('height'),
                            'weight' => Input::get('weight'),
                            'bmi' => Input::get('bmi'),
                            'muac' => Input::get('muac'),
                            'bp' => Input::get('bp'),
                            'pr' => Input::get('pr'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $vital['id']);
                    } else {
                        $user->createRecord('vital', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'height' => Input::get('height'),
                            'weight' => Input::get('weight'),
                            'bmi' => Input::get('bmi'),
                            'muac' => Input::get('muac'),
                            'bp' => Input::get('bp'),
                            'pr' => Input::get('pr'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Vital added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_history')) {
            $validate = $validate->check($_POST, array(
                // 'visit_date' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $history = $override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($history) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {

                            $user->updateRecord('history', array(
                                'visit_date' => date('Y-m-d'),
                                'hypertension' => Input::get('hypertension'),
                                'diabetes' => Input::get('diabetes'),
                                'ckd' => Input::get('ckd'),
                                'depression' => Input::get('depression'),
                                'hiv' => Input::get('hiv'),
                                'hiv_test' => Input::get('hiv_test'),
                                'art_date' => Input::get('art_date'),
                                'tb' => Input::get('tb'),
                                'tb_year' => Input::get('tb_year'),
                                'smoking' => Input::get('smoking'),
                                'packs' => Input::get('packs'),
                                'active_smoker' => Input::get('active_smoker'),
                                'alcohol' => Input::get('alcohol'),
                                'quantity' => Input::get('quantity'),
                                'cardiac_disease' => Input::get('cardiac_disease'),
                                'cardiac_surgery' => Input::get('cardiac_surgery'),
                                'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                                'surgery_other' => Input::get('surgery_other'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('history', array(
                                'visit_date' => date('Y-m-d'),
                                'hiv' => Input::get('hiv'),
                                'hiv_test' => Input::get('hiv_test'),
                                'art_date' => Input::get('art_date'),
                                'tb' => Input::get('tb'),
                                'tb_year' => Input::get('tb_year'),
                                'smoking' => Input::get('smoking'),
                                'packs' => Input::get('packs'),
                                'active_smoker' => Input::get('active_smoker'),
                                'alcohol' => Input::get('alcohol'),
                                'quantity' => Input::get('quantity'),
                                'cardiovascular' => Input::get('cardiovascular'),
                                'cardiovascular_date' => Input::get('cardiovascular_date'),
                                'retinopathy' => Input::get('retinopathy'),
                                'retinopathy_date' => Input::get('retinopathy_date'),
                                'renal' => Input::get('renal'),
                                'renal_date' => Input::get('renal_date'),
                                'stroke_tia' => Input::get('stroke_tia'),
                                'stroke_tia_date' => Input::get('stroke_tia_date'),
                                'pvd' => Input::get('pvd'),
                                'pvd_date' => Input::get('pvd_date'),
                                'neuropathy' => Input::get('neuropathy'),
                                'neuropathy_date' => Input::get('neuropathy_date'),
                                'sexual_dysfunction' => Input::get('sexual_dysfunction'),
                                'sexual_dysfunction_date' => Input::get('sexual_dysfunction_date'),
                                'diabetic_disease' => Input::get('diabetic_disease'),
                                'hypertension_disease' => Input::get('hypertension_disease'),
                                'history_other' => Input::get('history_other'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('history', array(
                                'visit_date' => date('Y-m-d'),
                                'hiv' => Input::get('hiv'),
                                'hiv_test' => Input::get('hiv_test'),
                                'art_date' => Input::get('art_date'),
                                'tb' => Input::get('tb'),
                                'tb_year' => Input::get('tb_year'),
                                'smoking' => Input::get('smoking'),
                                'packs' => Input::get('packs'),
                                'active_smoker' => Input::get('active_smoker'),
                                'alcohol' => Input::get('alcohol'),
                                'quantity' => Input::get('quantity'),
                                'pain_event' => Input::get('pain_event'),
                                'stroke' => Input::get('stroke'),
                                'pneumonia' => Input::get('pneumonia'),
                                'blood_transfusion' => Input::get('blood_transfusion'),
                                'acute_chest' => Input::get('acute_chest'),
                                'other_complication' => Input::get('other_complication'),
                                'specify_complication' => Input::get('specify_complication'),
                                'scd_disease' => Input::get('scd_disease'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }
                    } else {
                        $user->createRecord('history', array(
                            'visit_date' => date('Y-m-d'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hypertension' => Input::get('hypertension'),
                            'diabetes' => Input::get('diabetes'),
                            'ckd' => Input::get('ckd'),
                            'depression' => Input::get('depression'),
                            'hiv' => Input::get('hiv'),
                            'hiv_test' => Input::get('hiv_test'),
                            'art_date' => Input::get('art_date'),
                            'tb' => Input::get('tb'),
                            'tb_year' => Input::get('tb_year'),
                            'smoking' => Input::get('smoking'),
                            'packs' => Input::get('packs'),
                            'active_smoker' => Input::get('active_smoker'),
                            'alcohol' => Input::get('alcohol'),
                            'quantity' => Input::get('quantity'),
                            'cardiovascular' => Input::get('cardiovascular'),
                            'cardiovascular_date' => Input::get('cardiovascular_date'),
                            'retinopathy' => Input::get('retinopathy'),
                            'retinopathy_date' => Input::get('retinopathy_date'),
                            'renal' => Input::get('renal'),
                            'renal_date' => Input::get('renal_date'),
                            'stroke_tia' => Input::get('stroke_tia'),
                            'stroke_tia_date' => Input::get('stroke_tia_date'),
                            'pvd' => Input::get('pvd'),
                            'pvd_date' => Input::get('pvd_date'),
                            'neuropathy' => Input::get('neuropathy'),
                            'neuropathy_date' => Input::get('neuropathy_date'),
                            'sexual_dysfunction' => Input::get('sexual_dysfunction'),
                            'sexual_dysfunction_date' => Input::get('sexual_dysfunction_date'),
                            'pain_event' => Input::get('pain_event'),
                            'stroke' => Input::get('stroke'),
                            'pneumonia' => Input::get('pneumonia'),
                            'blood_transfusion' => Input::get('blood_transfusion'),
                            'acute_chest' => Input::get('acute_chest'),
                            'other_complication' => Input::get('other_complication'),
                            'specify_complication' => Input::get('specify_complication'),
                            'cardiac_disease' => Input::get('cardiac_disease'),
                            'cardiac_surgery' => Input::get('cardiac_surgery'),
                            'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                            'surgery_other' => Input::get('surgery_other'),
                            'diabetic_disease' => Input::get('diabetic_disease'),
                            'hypertension_disease' => Input::get('hypertension_disease'),
                            'history_other' => Input::get('history_other'),
                            'scd_disease' => Input::get('scd_disease'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Vital added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_symptoms')) {
            $validate = $validate->check($_POST, array(
                // 'visit_date' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($symptoms) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {
                            $user->updateRecord('symptoms', array(
                                'visit_date' => Input::get('visit_date'),
                                'dyspnea' => Input::get('dyspnea'),
                                'orthopnea' => Input::get('orthopnea'),
                                'paroxysmal' => Input::get('paroxysmal'),
                                'chest_pain' => Input::get('chest_pain'),
                                'cough' => Input::get('cough'),
                                'edema' => Input::get('edema'),
                                'lungs' => Input::get('lungs'),
                                'Other' => Input::get('Other'),
                                'jvp' => Input::get('jvp'),
                                'volume' => Input::get('volume'),
                                'murmur' => Input::get('murmur'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {
                            $user->updateRecord('symptoms', array(
                                'visit_date' => Input::get('visit_date'),
                                'thirst' => Input::get('thirst'),
                                'urination' => Input::get('urination'),
                                'vision' => Input::get('vision'),
                                'abnorminal_pain' => Input::get('abnorminal_pain'),
                                'vomiting' => Input::get('vomiting'),
                                'weight_loss' => Input::get('weight_loss'),
                                'foot_exam' => Input::get('foot_exam'),
                                'foot_exam_finding' => Input::get('foot_exam_finding'),
                                'fasting' => Input::get('fasting'),
                                'random_fs' => Input::get('random_fs'),
                                'hba1c' => Input::get('hba1c'),
                                'hypoglycemia_symptoms' => Input::get('hypoglycemia_symptoms'),
                                'hypoglycemia_severe' => Input::get('hypoglycemia_severe'),
                                'hypoglycemia__number' => Input::get('hypoglycemia__number'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                            $user->updateRecord('symptoms', array(
                                'visit_date' => Input::get('visit_date'),
                                'breathing' => Input::get('breathing'),
                                'chest_pain2' => Input::get('chest_pain2'),
                                'pain_score' => Input::get('pain_score'),
                                'other_sickle' => Input::get('other_sickle'),
                                'malnutrition' => Input::get('malnutrition'),
                                'pallor' => Input::get('pallor'),
                                'jaundice' => Input::get('jaundice'),
                                'splenomegaly' => Input::get('splenomegaly'),
                                'anemia' => Input::get('anemia'),
                                'hb' => Input::get('hb'),
                                'wbc' => Input::get('wbc'),
                                'plt' => Input::get('plt'),
                                'labs_other' => Input::get('labs_other'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $symptoms['id']);
                        }
                    } else {
                        $user->createRecord('symptoms', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'dyspnea' => Input::get('dyspnea'),
                            'orthopnea' => Input::get('orthopnea'),
                            'paroxysmal' => Input::get('paroxysmal'),
                            'chest_pain' => Input::get('chest_pain'),
                            'cough' => Input::get('cough'),
                            'thirst' => Input::get('thirst'),
                            'urination' => Input::get('urination'),
                            'vision' => Input::get('vision'),
                            'abnorminal_pain' => Input::get('abnorminal_pain'),
                            'vomiting' => Input::get('vomiting'),
                            'weight_loss' => Input::get('weight_loss'),
                            'breathing' => Input::get('breathing'),
                            'chest_pain2' => Input::get('chest_pain2'),
                            'pain_score' => Input::get('pain_score'),
                            'other_sickle' => Input::get('other_sickle'),
                            'edema' => Input::get('edema'),
                            'lungs' => Input::get('lungs'),
                            'Other' => Input::get('Other'),
                            'jvp' => Input::get('jvp'),
                            'volume' => Input::get('volume'),
                            'murmur' => Input::get('murmur'),
                            'foot_exam' => Input::get('foot_exam'),
                            'foot_exam_finding' => Input::get('foot_exam_finding'),
                            'malnutrition' => Input::get('malnutrition'),
                            'pallor' => Input::get('pallor'),
                            'jaundice' => Input::get('jaundice'),
                            'splenomegaly' => Input::get('splenomegaly'),
                            'anemia' => Input::get('anemia'),
                            'fasting' => Input::get('fasting'),
                            'random_fs' => Input::get('random_fs'),
                            'hba1c' => Input::get('hba1c'),
                            'hypoglycemia_symptoms' => Input::get('hypoglycemia_symptoms'),
                            'hypoglycemia_severe' => Input::get('hypoglycemia_severe'),
                            'hypoglycemia__number' => Input::get('hypoglycemia__number'),
                            'hb' => Input::get('hb'),
                            'wbc' => Input::get('wbc'),
                            'plt' => Input::get('plt'),
                            'labs_other' => Input::get('labs_other'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Symptoms added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_diagnosis')) {
            $validate = $validate->check($_POST, array(
                'diagnosis_date' => array(
                    'required' => true,
                ),


            ));
            if ($validate->passed()) {
                try {
                    $diagnosis = $override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($diagnosis) {
                        $user->updateRecord('diagnosis', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'diagnosis_date' => Input::get('diagnosis_date'),
                            'cardiac' => Input::get('cardiac'),
                            'diagnosis_date' => Input::get('diagnosis_date'),
                            'cardiomyopathy' => Input::get('cardiomyopathy'),
                            'heumatic' => Input::get('heumatic'),
                            'congenital' => Input::get('congenital'),
                            'heart_failure' => Input::get('heart_failure'),
                            'pericardial' => Input::get('pericardial'),
                            'arrhythmia' => Input::get('arrhythmia'),
                            'stroke' => Input::get('stroke'),
                            'thromboembolic' => Input::get('thromboembolic'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $diagnosis['id']);
                    } else {
                        $user->createRecord('diagnosis', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'cardiac' => Input::get('cardiac'),
                            'diagnosis_date' => Input::get('diagnosis_date'),
                            'cardiomyopathy' => Input::get('cardiomyopathy'),
                            'heumatic' => Input::get('heumatic'),
                            'congenital' => Input::get('congenital'),
                            'heart_failure' => Input::get('heart_failure'),
                            'pericardial' => Input::get('pericardial'),
                            'arrhythmia' => Input::get('arrhythmia'),
                            'stroke' => Input::get('stroke'),
                            'thromboembolic' => Input::get('thromboembolic'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Diagnosis added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_results')) {
            $validate = $validate->check($_POST, array(
                'ecg_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $results = $override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($results) {
                        $user->updateRecord('results', array(
                            'visit_date' => Input::get('visit_date'),
                            'ecg_date' => Input::get('ecg_date'),
                            'ecg' => Input::get('ecg'),
                            'ecg_other' => Input::get('ecg_other'),
                            'echo_date' => Input::get('echo_date'),
                            'echo' => Input::get('echo'),
                            'echo_other' => Input::get('echo_other'),
                            'echo_specify' => Input::get('echo_specify'),
                            'echo_other2' => Input::get('echo_other2'),
                            'lv' => Input::get('lv'),
                            'mitral' => Input::get('mitral'),
                            'rv' => Input::get('rv'),
                            'pericardial' => Input::get('pericardial'),
                            'ivc' => Input::get('ivc'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $results['id']);
                    } else {
                        $user->createRecord('results', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'ecg_date' => Input::get('ecg_date'),
                            'ecg' => Input::get('ecg'),
                            'ecg_other' => Input::get('ecg_other'),
                            'echo_date' => Input::get('echo_date'),
                            'echo' => Input::get('echo'),
                            'lv' => Input::get('lv'),
                            'mitral' => Input::get('mitral'),
                            'rv' => Input::get('rv'),
                            'pericardial' => Input::get('pericardial'),
                            'ivc' => Input::get('ivc'),
                            'echo_other' => Input::get('echo_other'),
                            'echo_specify' => Input::get('echo_specify'),
                            'echo_other2' => Input::get('echo_other2'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Results added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_hospitalizaion')) {
            $validate = $validate->check($_POST, array(
                'hospitalizations' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $hospitalization = $override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($hospitalization) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {
                            $user->updateRecord('hospitalization', array(
                                'visit_date' => Input::get('hospitalization_date'),
                                'hospitalization_date' => Input::get('hospitalization_date'),
                                'hospitalizations' => Input::get('hospitalizations'),
                                'ncd_hospitalizations' => Input::get('ncd_hospitalizations'),
                                'hospitalization_number' => Input::get('hospitalization_number'),
                                'missed_days' => Input::get('missed_days'),
                                'school_days' => Input::get('school_days'),
                                'fluid' => Input::get('fluid'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {
                            $user->updateRecord('hospitalization', array(
                                'visit_date' => Input::get('hospitalization_date'),
                                'hospitalization_date' => Input::get('hospitalization_date'),
                                'hospitalizations' => Input::get('hospitalizations'),
                                'ncd_hospitalizations' => Input::get('ncd_hospitalizations'),
                                'hospitalization_number' => Input::get('hospitalization_number'),
                                'missed_days' => Input::get('missed_days'),
                                'school_days' => Input::get('school_days'),
                                'fluid' => Input::get('fluid'),
                                'bg_measurement' => Input::get('bg_measurement'),
                                'bg_result180' => Input::get('bg_result180'),
                                'bg_result70_180' => Input::get('bg_result70_180'),
                                'bg_result70' => Input::get('bg_result70'),
                                'basal' => Input::get('basal'),
                                'prandial' => Input::get('prandial'),
                                'basal_nph' => Input::get('basal_nph'),
                                'basal_analog' => Input::get('basal_analog'),
                                'basal_am' => Input::get('basal_am'),
                                'basal_am' => Input::get('basal_am'),
                                'prandial_regular' => Input::get('prandial_regular'),
                                'prandial_analog' => Input::get('prandial_analog'),
                                'prandial_am' => Input::get('prandial_am'),
                                'prandial_lunch' => Input::get('prandial_lunch'),
                                'prandial_pm' => Input::get('prandial_pm'),
                                'total_insulin_dose' => Input::get('total_insulin_dose'),
                                'home_insulin_dose' => Input::get('home_insulin_dose'),
                                'issue_injection' => Input::get('issue_injection'),
                                'issue_injection_yes' => Input::get('issue_injection_yes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                            $user->updateRecord('hospitalization', array(
                                'visit_date' => Input::get('hospitalization_date'),
                                'hospitalization_date' => Input::get('hospitalization_date'),
                                'hospitalizations' => Input::get('hospitalizations'),
                                'ncd_hospitalizations' => Input::get('ncd_hospitalizations'),
                                'hospitalization_number' => Input::get('hospitalization_number'),
                                'missed_days' => Input::get('missed_days'),
                                'school_days' => Input::get('school_days'),
                                'transfusion' => Input::get('transfusion'),
                                'fluid' => Input::get('fluid'),
                                'prophylaxis' => Input::get('prophylaxis'),
                                'insecticide' => Input::get('insecticide'),
                                'folic_acid' => Input::get('folic_acid'),
                                'penicillin' => Input::get('penicillin'),
                                'pneumococcal' => Input::get('pneumococcal'),
                                'opioid' => Input::get('opioid'),
                                'opioid_type' => Input::get('opioid_type'),
                                'opioid_dose' => Input::get('opioid_dose'),
                                'hydroxyurea' => Input::get('hydroxyurea'),
                                'hydroxyurea_date' => Input::get('hydroxyurea_date'),
                                'hydroxyurea_dose' => Input::get('hydroxyurea_dose'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }
                    } else {
                        $user->createRecord('hospitalization', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalizations' => Input::get('hospitalizations'),
                            'ncd_hospitalizations' => Input::get('ncd_hospitalizations'),
                            'hospitalization_number' => Input::get('hospitalization_number'),
                            'missed_days' => Input::get('missed_days'),
                            'school_days' => Input::get('school_days'),
                            'transfusion' => Input::get('transfusion'),
                            'fluid' => Input::get('fluid'),
                            'bg_measurement' => Input::get('bg_measurement'),
                            'bg_result180' => Input::get('bg_result180'),
                            'bg_result70_180' => Input::get('bg_result70_180'),
                            'bg_result70' => Input::get('bg_result70'),
                            'basal' => Input::get('basal'),
                            'prandial' => Input::get('prandial'),
                            'prophylaxis' => Input::get('prophylaxis'),
                            'insecticide' => Input::get('insecticide'),
                            'folic_acid' => Input::get('folic_acid'),
                            'penicillin' => Input::get('penicillin'),
                            'pneumococcal' => Input::get('pneumococcal'),
                            'opioid' => Input::get('opioid'),
                            'opioid_type' => Input::get('opioid_type'),
                            'opioid_dose' => Input::get('opioid_dose'),
                            'hydroxyurea' => Input::get('hydroxyurea'),
                            'hydroxyurea_date' => Input::get('hydroxyurea_date'),
                            'hydroxyurea_dose' => Input::get('hydroxyurea_dose'),
                            'basal_nph' => Input::get('basal_nph'),
                            'basal_analog' => Input::get('basal_analog'),
                            'basal_am' => Input::get('basal_am'),
                            'basal_am' => Input::get('basal_am'),
                            'prandial_regular' => Input::get('prandial_regular'),
                            'prandial_analog' => Input::get('prandial_analog'),
                            'prandial_am' => Input::get('prandial_am'),
                            'prandial_lunch' => Input::get('prandial_lunch'),
                            'prandial_pm' => Input::get('prandial_pm'),
                            'total_insulin_dose' => Input::get('total_insulin_dose'),
                            'home_insulin_dose' => Input::get('home_insulin_dose'),
                            'issue_injection' => Input::get('issue_injection'),
                            'issue_injection_yes' => Input::get('issue_injection_yes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Hospitalization added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_lab_details')) {
            $validate = $validate->check($_POST, array(
                'lab_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $lab_details = $override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($lab_details) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {

                            $user->updateRecord('lab_details', array(
                                'visit_date' => Input::get('lab_date'),
                                'lab_date' => Input::get('lab_date'),
                                'ncd_coping' => Input::get('ncd_coping'),
                                'family_planning' => Input::get('family_planning'),
                                'na' => Input::get('na'),
                                'k' => Input::get('k'),
                                'bun' => Input::get('bun'),
                                'cre' => Input::get('cre'),
                                'bnp' => Input::get('bnp'),
                                'inr' => Input::get('inr'),
                                'lab_Other' => Input::get('lab_Other'),
                                'lab_specify' => Input::get('lab_specify'),
                                'lab_ecg' => Input::get('lab_ecg'),
                                'lab_ecg_other' => Input::get('lab_ecg_other'),
                                'cardiac_surgery' => Input::get('cardiac_surgery'),
                                'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('lab_details', array(
                                'visit_date' => Input::get('lab_date'),
                                'lab_date' => Input::get('lab_date'),
                                'ncd_coping' => Input::get('ncd_coping'),
                                'family_planning' => Input::get('family_planning'),
                                'dka_number' => Input::get('dka_number'),
                                'eyes_examined' => Input::get('eyes_examined'),
                                'cataracts' => Input::get('cataracts'),
                                'retinopathy_screening' => Input::get('retinopathy_screening'),
                                'foot_exam_diabetes' => Input::get('foot_exam_diabetes'),
                                'na_diabetes' => Input::get('na_diabetes'),
                                'k_diabetes' => Input::get('k_diabetes'),
                                'cre_diabetes' => Input::get('cre_diabetes'),
                                'proteinuria' => Input::get('proteinuria'),
                                'lipid_panel' => Input::get('lipid_panel'),
                                'other_lab_diabetes' => Input::get('other_lab_diabetes'),
                                'specify_lab_diabetes' => Input::get('specify_lab_diabetes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('lab_details', array(
                                'visit_date' => Input::get('lab_date'),
                                'lab_date' => Input::get('lab_date'),
                                'ncd_coping' => Input::get('ncd_coping'),
                                'family_planning' => Input::get('family_planning'),
                                'lab_transfusion_sickle' => Input::get('lab_transfusion_sickle'),
                                'transcranial_doppler' => Input::get('transcranial_doppler'),
                                'wbc' => Input::get('wbc'),
                                'hb' => Input::get('hb'),
                                'mcv' => Input::get('mcv'),
                                'plt' => Input::get('plt'),
                                'fe_studies' => Input::get('fe_studies'),
                                'lfts' => Input::get('lfts'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }
                    } else {
                        $user->createRecord('lab_details', array(
                            'visit_date' => Input::get('lab_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'lab_date' => Input::get('lab_date'),
                            'ncd_coping' => Input::get('ncd_coping'),
                            'family_planning' => Input::get('family_planning'),
                            'na' => Input::get('na'),
                            'k' => Input::get('k'),
                            'bun' => Input::get('bun'),
                            'cre' => Input::get('cre'),
                            'bnp' => Input::get('bnp'),
                            'inr' => Input::get('inr'),
                            'lab_Other' => Input::get('lab_Other'),
                            'lab_specify' => Input::get('lab_specify'),
                            'lab_ecg' => Input::get('lab_ecg'),
                            'lab_ecg_other' => Input::get('lab_ecg_other'),
                            'cardiac_surgery' => Input::get('cardiac_surgery'),
                            'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                            'dka_number' => Input::get('dka_number'),
                            'eyes_examined' => Input::get('eyes_examined'),
                            'cataracts' => Input::get('cataracts'),
                            'retinopathy_screening' => Input::get('retinopathy_screening'),
                            'foot_exam_diabetes' => Input::get('foot_exam_diabetes'),
                            'na_diabetes' => Input::get('na_diabetes'),
                            'k_diabetes' => Input::get('k_diabetes'),
                            'cre_diabetes' => Input::get('cre_diabetes'),
                            'proteinuria' => Input::get('proteinuria'),
                            'lipid_panel' => Input::get('lipid_panel'),
                            'other_lab_diabetes' => Input::get('other_lab_diabetes'),
                            'specify_lab_diabetes' => Input::get('specify_lab_diabetes'),
                            'lab_transfusion_sickle' => Input::get('lab_transfusion_sickle'),
                            'transcranial_doppler' => Input::get('transcranial_doppler'),
                            'wbc' => Input::get('wbc'),
                            'hb' => Input::get('hb'),
                            'mcv' => Input::get('mcv'),
                            'plt' => Input::get('plt'),
                            'fe_studies' => Input::get('fe_studies'),
                            'lfts' => Input::get('lfts'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Lab details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_hospitalization_details')) {
            $validate = $validate->check($_POST, array(
                'hospitalization_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $hospitalization_details = $override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($hospitalization_details) {
                        $user->updateRecord('hospitalization_details', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'admission_reason' => Input::get('admission_reason'),
                            'discharge_diagnosis' => Input::get('discharge_diagnosis'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $hospitalization_details['id']);
                    } else {
                        $user->createRecord('hospitalization_details', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'admission_reason' => Input::get('admission_reason'),
                            'discharge_diagnosis' => Input::get('discharge_diagnosis'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Hospitalization details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_risks')) {
            $validate = $validate->check($_POST, array(
                'risk_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $risks = $override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($risks) {
                        $user->updateRecord('risks', array(
                            'visit_date' => Input::get('risk_date'),
                            'risk_date' => Input::get('risk_date'),
                            'risk_tobacco' => Input::get('risk_tobacco'),
                            'risk_alcohol' => Input::get('risk_alcohol'),
                            'risk_employment' => Input::get('risk_employment'),
                            'ncd_limiting' => Input::get('ncd_limiting'),
                            'social_economic' => Input::get('social_economic'),
                            'risk_hiv_date' => Input::get('risk_hiv_date'),
                            'risk_hiv' => Input::get('risk_hiv'),
                            'risk_art_date' => Input::get('risk_art_date'),
                            'risk_tb_date' => Input::get('risk_tb_date'),
                            'risk_tb' => Input::get('risk_tb'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $risks['id']);
                    } else {
                        $user->createRecord('risks', array(
                            'visit_date' => Input::get('risk_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'risk_date' => Input::get('risk_date'),
                            'risk_tobacco' => Input::get('risk_tobacco'),
                            'risk_alcohol' => Input::get('risk_alcohol'),
                            'risk_employment' => Input::get('risk_employment'),
                            'ncd_limiting' => Input::get('ncd_limiting'),
                            'social_economic' => Input::get('social_economic'),
                            'risk_hiv_date' => Input::get('risk_hiv_date'),
                            'risk_hiv' => Input::get('risk_hiv'),
                            'risk_art_date' => Input::get('risk_art_date'),
                            'risk_tb_date' => Input::get('risk_tb_date'),
                            'risk_tb' => Input::get('risk_tb'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Risks details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_dgns_complctns_comorbdts')) {
            $validate = $validate->check($_POST, array(
                'diagns_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {
                    $dgns_complctns_comorbdts = $override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($dgns_complctns_comorbdts) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'visit_date' => Input::get('diagns_date'),
                                'diagns_date' => Input::get('diagns_date'),
                                'diagns_changed' => Input::get('diagns_changed'),
                                'ncd_diagns' => Input::get('ncd_diagns'),
                                'ncd_diagns_specify' => Input::get('ncd_diagns_specify'),
                                'diagns_complication' => Input::get('diagns_complication'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $dgns_complctns_comorbdts['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'visit_date' => Input::get('diagns_date'),
                                'diagns_date' => Input::get('diagns_date'),
                                'diagns_changed' => Input::get('diagns_changed'),
                                'ncd_diagns_diabetes' => Input::get('ncd_diagns_diabetes'),
                                'ncd_diabetes_specify' => Input::get('ncd_diabetes_specify'),
                                'diagns_complication_diabets' => Input::get('diagns_complication_diabets'),
                                'complication_diabets_specify' => Input::get('complication_diabets_specify'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $dgns_complctns_comorbdts['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'visit_date' => Input::get('diagns_date'),
                                'diagns_date' => Input::get('diagns_date'),
                                'diagns_changed' => Input::get('diagns_changed'),
                                'ncd_diagns_sickle' => Input::get('ncd_diagns_sickle'),
                                'ncd_sickle_specify' => Input::get('ncd_sickle_specify'),
                                'diagns_complication_sickle' => Input::get('diagns_complication_sickle'),
                                'complication_sickle_specify' => Input::get('complication_sickle_specify'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $dgns_complctns_comorbdts['id']);
                        }
                    } else {
                        $user->createRecord('dgns_complctns_comorbdts', array(
                            'visit_date' => Input::get('diagns_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagns_date' => Input::get('diagns_date'),
                            'diagns_changed' => Input::get('diagns_changed'),
                            'ncd_diagns' => Input::get('ncd_diagns'),
                            'ncd_diagns_specify' => Input::get('ncd_diagns_specify'),
                            'diagns_complication' => Input::get('diagns_complication'),
                            'ncd_diagns_diabetes' => Input::get('ncd_diagns_diabetes'),
                            'ncd_diabetes_specify' => Input::get('ncd_diabetes_specify'),
                            'diagns_complication_diabets' => Input::get('diagns_complication_diabets'),
                            'complication_diabets_specify' => Input::get('complication_diabets_specify'),
                            'ncd_diagns_sickle' => Input::get('ncd_diagns_sickle'),
                            'ncd_sickle_specify' => Input::get('ncd_sickle_specify'),
                            'diagns_complication_sickle' => Input::get('diagns_complication_sickle'),
                            'complication_sickle_specify' => Input::get('complication_sickle_specify'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Diagnosis details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_summary')) {
            $validate = $validate->check($_POST, array(
                'summary_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $summary = $override->get3('summary', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($summary) {
                        $user->updateRecord('summary', array(
                            'visit_date' => Input::get('summary_date'),
                            'summary_date' => Input::get('summary_date'),
                            'comments' => Input::get('comments'),
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'outcome' => Input::get('outcome'),
                            'transfer_out' => Input::get('transfer_out'),
                            'cause_death' => Input::get('cause_death'),
                            'next_appointment_notes' => Input::get('next_appointment_notes'),
                            'next_appointment' => Input::get('next_appointment'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $summary['id']);
                    } else {
                        $user->createRecord('summary', array(
                            'visit_date' => Input::get('summary_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'summary_date' => Input::get('summary_date'),
                            'comments' => Input::get('comments'),
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'outcome' => Input::get('outcome'),
                            'transfer_out' => Input::get('transfer_out'),
                            'cause_death' => Input::get('cause_death'),
                            'next_appointment_notes' => Input::get('next_appointment_notes'),
                            'next_appointment' => Input::get('next_appointment'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Visit Summary  details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_treatment_plan')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $treatment_plan = $override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($treatment_plan) {
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {

                            $user->updateRecord('treatment_plan', array(
                                'visit_date' => Input::get('visit_date'),
                                'asa' => Input::get('asa'),
                                'action_asa' => Input::get('action_asa'),
                                'dose_asa' => Input::get('dose_asa'),
                                'furosemide' => Input::get('furosemide'),
                                'action_furosemide' => Input::get('action_furosemide'),
                                'dose_furosemide' => Input::get('dose_furosemide'),
                                'ace_i' => Input::get('ace_i'),
                                'action_ace_i' => Input::get('action_ace_i'),
                                'dose_ace_i' => Input::get('dose_ace_i'),
                                'beta_blocker' => Input::get('beta_blocker'),
                                'action_beta_blocker' => Input::get('action_beta_blocker'),
                                'dose_beta_blocker' => Input::get('dose_beta_blocker'),
                                'anti_hypertensive' => Input::get('anti_hypertensive'),
                                'action_anti_hypertensive' => Input::get('action_anti_hypertensive'),
                                'dose_anti_hypertensive' => Input::get('dose_anti_hypertensive'),
                                'benzathine' => Input::get('benzathine'),
                                'action_benzathine' => Input::get('action_benzathine'),
                                'dose_benzathine' => Input::get('dose_benzathine'),
                                'anticoagulation' => Input::get('anticoagulation'),
                                'action_anticoagulation' => Input::get('action_anticoagulation'),
                                'dose_anticoagulation' => Input::get('dose_anticoagulation'),
                                'medication_other' => Input::get('medication_other'),
                                'action_medication_other' => Input::get('action_medication_other'),
                                'dose_medication_other' => Input::get('dose_medication_other'),
                                'salt' => Input::get('salt'),
                                'fluid' => Input::get('fluid'),
                                'restriction_other' => Input::get('restriction_other'),
                                'social_support' => Input::get('social_support'),
                                'social_support_type' => Input::get('social_support_type'),
                                'cardiology' => Input::get('cardiology'),
                                'completed' => Input::get('completed'),
                                'cardiology_reason' => Input::get('cardiology_reason'),
                                'cardiology_date' => Input::get('cardiology_date'),
                                'awaiting_surgery' => Input::get('awaiting_surgery'),
                                'new_referrals' => Input::get('new_referrals'),
                                'new_referrals_type' => Input::get('new_referrals_type'),
                                'medication_notes' => Input::get('medication_notes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('treatment_plan', array(
                                'visit_date' => Input::get('visit_date'),
                                'metformin' => Input::get('metformin'),
                                'action_metformin' => Input::get('action_metformin'),
                                'dose_metformin' => Input::get('dose_metformin'),
                                'glibenclamide' => Input::get('glibenclamide'),
                                'action_glibenclamide' => Input::get('action_glibenclamide'),
                                'dose_glibenclamide' => Input::get('dose_glibenclamide'),
                                'anti_diabetic' => Input::get('anti_diabetic'),
                                'action_anti_diabetic' => Input::get('action_anti_diabetic'),
                                'dose_anti_diabetic' => Input::get('dose_anti_diabetic'),
                                'aspirin' => Input::get('aspirin'),
                                'action_aspirin' => Input::get('action_aspirin'),
                                'dose_aspirin' => Input::get('dose_aspirin'),
                                'anti_hypertensive2' => Input::get('anti_hypertensive2'),
                                'action_anti_hypertensive2' => Input::get('action_anti_hypertensive2'),
                                'dose_anti_hypertensive2' => Input::get('dose_anti_hypertensive2'),
                                'medication_other2' => Input::get('medication_other2'),
                                'action_medication_other2' => Input::get('action_medication_other2'),
                                'dose_medication_other2' => Input::get('dose_medication_other2'),
                                'basal_changed' => Input::get('basal_changed'),
                                'basal_am2' => Input::get('basal_am2'),
                                'basal_pm2' => Input::get('basal_pm2'),
                                'prandial_changed' => Input::get('prandial_changed'),
                                'prandial_am2' => Input::get('prandial_am2'),
                                'prandial_lunch2' => Input::get('prandial_lunch2'),
                                'prandial_pm2' => Input::get('prandial_pm2'),
                                'salt' => Input::get('salt'),
                                'fluid' => Input::get('fluid'),
                                'restriction_other' => Input::get('restriction_other'),
                                'social_support' => Input::get('social_support'),
                                'social_support_type' => Input::get('social_support_type'),
                                'cardiology' => Input::get('cardiology'),
                                'completed' => Input::get('completed'),
                                'cardiology_reason' => Input::get('cardiology_reason'),
                                'cardiology_date' => Input::get('cardiology_date'),
                                'awaiting_surgery' => Input::get('awaiting_surgery'),
                                'new_referrals' => Input::get('new_referrals'),
                                'new_referrals_type' => Input::get('new_referrals_type'),
                                'medication_notes' => Input::get('medication_notes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('treatment_plan', array(
                                'visit_date' => Input::get('visit_date'),
                                'prophylaxis2' => Input::get('prophylaxis2'),
                                'action_prophylaxis2' => Input::get('action_prophylaxis2'),
                                'dose_prophylaxis2' => Input::get('dose_prophylaxis2'),
                                'folic_acid2' => Input::get('folic_acid2'),
                                'action_folic_acid2' => Input::get('action_folic_acid2'),
                                'dose_folic_acid2' => Input::get('dose_folic_acid2'),
                                'penicillin2' => Input::get('penicillin2'),
                                'action_penicillin2' => Input::get('action_penicillin2'),
                                'dose_penicillin2' => Input::get('dose_penicillin2'),
                                'pain_control' => Input::get('pain_control'),
                                'action_pain_control' => Input::get('action_pain_control'),
                                'dose_pain_control' => Input::get('dose_pain_control'),
                                'hydroxyurea' => Input::get('hydroxyurea'),
                                'action_hydroxyurea' => Input::get('action_hydroxyurea'),
                                'dose_hydroxyurea' => Input::get('dose_hydroxyurea'),
                                'medication_other3' => Input::get('medication_other3'),
                                'action_medication_other3' => Input::get('action_medication_other3'),
                                'dose_medication_other3' => Input::get('dose_medication_other3'),
                                'salt' => Input::get('salt'),
                                'fluid' => Input::get('fluid'),
                                'restriction_other' => Input::get('restriction_other'),
                                'vaccination' => Input::get('vaccination'),
                                'vaccination_specify' => Input::get('vaccination_specify'),
                                'transfusion_needed' => Input::get('transfusion_needed'),
                                'transfusion_units' => Input::get('transfusion_units'),
                                'diet' => Input::get('diet'),
                                'hydration' => Input::get('hydration'),
                                'acute_symptoms' => Input::get('acute_symptoms'),
                                'fever' => Input::get('fever'),
                                'other_support' => Input::get('other_support'),
                                'support_specify' => Input::get('support_specify'),
                                'social_support' => Input::get('social_support'),
                                'social_support_type' => Input::get('social_support_type'),
                                'cardiology' => Input::get('cardiology'),
                                'completed' => Input::get('completed'),
                                'cardiology_reason' => Input::get('cardiology_reason'),
                                'cardiology_date' => Input::get('cardiology_date'),
                                'awaiting_surgery' => Input::get('awaiting_surgery'),
                                'new_referrals' => Input::get('new_referrals'),
                                'new_referrals_type' => Input::get('new_referrals_type'),
                                'medication_notes' => Input::get('medication_notes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }
                    } else {
                        $user->createRecord('treatment_plan', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'asa' => Input::get('asa'),
                            'action_asa' => Input::get('action_asa'),
                            'dose_asa' => Input::get('dose_asa'),
                            'furosemide' => Input::get('furosemide'),
                            'action_furosemide' => Input::get('action_furosemide'),
                            'dose_furosemide' => Input::get('dose_furosemide'),
                            'ace_i' => Input::get('ace_i'),
                            'action_ace_i' => Input::get('action_ace_i'),
                            'dose_ace_i' => Input::get('dose_ace_i'),
                            'beta_blocker' => Input::get('beta_blocker'),
                            'action_beta_blocker' => Input::get('action_beta_blocker'),
                            'dose_beta_blocker' => Input::get('dose_beta_blocker'),
                            'anti_hypertensive' => Input::get('anti_hypertensive'),
                            'action_anti_hypertensive' => Input::get('action_anti_hypertensive'),
                            'dose_anti_hypertensive' => Input::get('dose_anti_hypertensive'),
                            'benzathine' => Input::get('benzathine'),
                            'action_benzathine' => Input::get('action_benzathine'),
                            'dose_benzathine' => Input::get('dose_benzathine'),
                            'anticoagulation' => Input::get('anticoagulation'),
                            'action_anticoagulation' => Input::get('action_anticoagulation'),
                            'dose_anticoagulation' => Input::get('dose_anticoagulation'),
                            'medication_other' => Input::get('medication_other'),
                            'action_medication_other' => Input::get('action_medication_other'),
                            'dose_medication_other' => Input::get('dose_medication_other'),
                            'metformin' => Input::get('metformin'),
                            'action_metformin' => Input::get('action_metformin'),
                            'dose_metformin' => Input::get('dose_metformin'),
                            'glibenclamide' => Input::get('glibenclamide'),
                            'action_glibenclamide' => Input::get('action_glibenclamide'),
                            'dose_glibenclamide' => Input::get('dose_glibenclamide'),
                            'anti_diabetic' => Input::get('anti_diabetic'),
                            'action_anti_diabetic' => Input::get('action_anti_diabetic'),
                            'dose_anti_diabetic' => Input::get('dose_anti_diabetic'),
                            'aspirin' => Input::get('aspirin'),
                            'action_aspirin' => Input::get('action_aspirin'),
                            'dose_aspirin' => Input::get('dose_aspirin'),
                            'anti_hypertensive2' => Input::get('anti_hypertensive2'),
                            'action_anti_hypertensive2' => Input::get('action_anti_hypertensive2'),
                            'dose_anti_hypertensive2' => Input::get('dose_anti_hypertensive2'),
                            'medication_other2' => Input::get('medication_other2'),
                            'action_medication_other2' => Input::get('action_medication_other2'),
                            'dose_medication_other2' => Input::get('dose_medication_other2'),
                            'prophylaxis2' => Input::get('prophylaxis2'),
                            'action_prophylaxis2' => Input::get('action_prophylaxis2'),
                            'dose_prophylaxis2' => Input::get('dose_prophylaxis2'),
                            'folic_acid2' => Input::get('folic_acid2'),
                            'action_folic_acid2' => Input::get('action_folic_acid2'),
                            'dose_folic_acid2' => Input::get('dose_folic_acid2'),
                            'penicillin2' => Input::get('penicillin2'),
                            'action_penicillin2' => Input::get('action_penicillin2'),
                            'dose_penicillin2' => Input::get('dose_penicillin2'),
                            'pain_control' => Input::get('pain_control'),
                            'action_pain_control' => Input::get('action_pain_control'),
                            'dose_pain_control' => Input::get('dose_pain_control'),
                            'hydroxyurea' => Input::get('hydroxyurea'),
                            'action_hydroxyurea' => Input::get('action_hydroxyurea'),
                            'dose_hydroxyurea' => Input::get('dose_hydroxyurea'),
                            'medication_other3' => Input::get('medication_other3'),
                            'action_medication_other3' => Input::get('action_medication_other3'),
                            'dose_medication_other3' => Input::get('dose_medication_other3'),
                            'basal_changed' => Input::get('basal_changed'),
                            'basal_am2' => Input::get('basal_am2'),
                            'basal_pm2' => Input::get('basal_pm2'),
                            'prandial_changed' => Input::get('prandial_changed'),
                            'prandial_am2' => Input::get('prandial_am2'),
                            'prandial_lunch2' => Input::get('prandial_lunch2'),
                            'prandial_pm2' => Input::get('prandial_pm2'),
                            'salt' => Input::get('salt'),
                            'fluid' => Input::get('fluid'),
                            'restriction_other' => Input::get('restriction_other'),
                            'vaccination' => Input::get('vaccination'),
                            'vaccination_specify' => Input::get('vaccination_specify'),
                            'transfusion_needed' => Input::get('transfusion_needed'),
                            'transfusion_units' => Input::get('transfusion_units'),
                            'diet' => Input::get('diet'),
                            'hydration' => Input::get('hydration'),
                            'acute_symptoms' => Input::get('acute_symptoms'),
                            'fever' => Input::get('fever'),
                            'other_support' => Input::get('other_support'),
                            'support_specify' => Input::get('support_specify'),
                            'social_support' => Input::get('social_support'),
                            'social_support_type' => Input::get('social_support_type'),
                            'cardiology' => Input::get('cardiology'),
                            'completed' => Input::get('completed'),
                            'cardiology_reason' => Input::get('cardiology_reason'),
                            'cardiology_date' => Input::get('cardiology_date'),
                            'awaiting_surgery' => Input::get('awaiting_surgery'),
                            'new_referrals' => Input::get('new_referrals'),
                            'new_referrals_type' => Input::get('new_referrals_type'),
                            'medication_notes' => Input::get('medication_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Treatment plan added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_social_economic')) {
            $validate = $validate->check($_POST, array(
                'social_economic_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $social_economic = $override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($social_economic) {
                        $user->updateRecord('social_economic', array(
                            'visit_date' => Input::get('social_economic_date'),
                            'social_economic_date' => Input::get('social_economic_date'),
                            'distance_km' => Input::get('distance_km'),
                            'distance_minutes' => Input::get('distance_minutes'),
                            'transport_mode' => Input::get('transport_mode'),
                            'transport_mode_other' => Input::get('transport_mode_other'),
                            'transportation_cost' => Input::get('transportation_cost'),
                            'household_head' => Input::get('household_head'),
                            'household_head_other' => Input::get('household_head_other'),
                            'household_years' => Input::get('household_years'),
                            'household_people' => Input::get('household_people'),
                            'wealth_rate' => Input::get('wealth_rate'),
                            'contributer_occupation' => Input::get('contributer_occupation'),
                            'contributer_occupation_other' => Input::get('contributer_occupation_other'),
                            'main_occupation' => Input::get('main_occupation'),
                            'main_occupation_other' => Input::get('main_occupation_other'),
                            'main_icome_based' => Input::get('main_icome_based'),
                            'main_icome_other' => Input::get('main_icome_other'),
                            'earn_individual' => Input::get('earn_individual'),
                            'earn_household' => Input::get('earn_household'),
                            'main_transport' => Input::get('main_transport'),
                            'time_from_home' => Input::get('time_from_home'),
                            'leave_children' => Input::get('leave_children'),
                            'looking_children' => Input::get('looking_children'),
                            'looking_children_other' => Input::get('looking_children_other'),
                            'occupation_looking_child' => Input::get('occupation_looking_child'),
                            'occupation_looking_child_other' => Input::get('occupation_looking_child_other'),
                            'accompany' => Input::get('accompany'),
                            'accompany_occupation' => Input::get('accompany_occupation'),
                            'accompany_occupation_other' => Input::get('accompany_occupation_other'),
                            'accompany_transport' => Input::get('accompany_transport'),
                            'accompany_expenses' => Input::get('accompany_expenses'),
                            'activities_disrupted' => Input::get('activities_disrupted'),
                            'material_floor' => Input::get('material_floor'),
                            'material_floor_other' => Input::get('material_floor_other'),
                            'material_roof' => Input::get('material_roof'),
                            'material_roof_other' => Input::get('material_roof_other'),
                            'cooking_fuel' => Input::get('cooking_fuel'),
                            'cooking_fuel_other' => Input::get('cooking_fuel_other'),
                            'water_access' => Input::get('water_access'),
                            'water_source' => Input::get('water_source'),
                            'water_source_other' => Input::get('water_source_other'),
                            'toilet_access' => Input::get('toilet_access'),
                            'toilet_facility' => Input::get('toilet_facility'),
                            'toilet_access_other' => Input::get('toilet_access_other'),
                            'television' => Input::get('television'),
                            'refrigerator' => Input::get('refrigerator'),
                            'sofa' => Input::get('sofa'),
                            'clock' => Input::get('clock'),
                            'fan' => Input::get('fan'),
                            'vcr_dvd' => Input::get('vcr_dvd'),
                            'bank_account' => Input::get('bank_account'),
                            'no_food' => Input::get('no_food'),
                            'sleep_hungry' => Input::get('sleep_hungry'),
                            'day_hungry' => Input::get('day_hungry'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $social_economic['id']);
                    } else {
                        $user->createRecord('social_economic', array(
                            'visit_date' => Input::get('social_economic_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'social_economic_date' => Input::get('social_economic_date'),
                            'distance_km' => Input::get('distance_km'),
                            'distance_minutes' => Input::get('distance_minutes'),
                            'transport_mode' => Input::get('transport_mode'),
                            'transport_mode_other' => Input::get('transport_mode_other'),
                            'transportation_cost' => Input::get('transportation_cost'),
                            'household_head' => Input::get('household_head'),
                            'household_head_other' => Input::get('household_head_other'),
                            'household_years' => Input::get('household_years'),
                            'household_people' => Input::get('household_people'),
                            'wealth_rate' => Input::get('wealth_rate'),
                            'contributer_occupation' => Input::get('contributer_occupation'),
                            'contributer_occupation_other' => Input::get('contributer_occupation_other'),
                            'main_occupation' => Input::get('main_occupation'),
                            'main_occupation_other' => Input::get('main_occupation_other'),
                            'main_icome_based' => Input::get('main_icome_based'),
                            'main_icome_other' => Input::get('main_icome_other'),
                            'earn_individual' => Input::get('earn_individual'),
                            'earn_household' => Input::get('earn_household'),
                            'main_transport' => Input::get('main_transport'),
                            'time_from_home' => Input::get('time_from_home'),
                            'leave_children' => Input::get('leave_children'),
                            'looking_children' => Input::get('looking_children'),
                            'looking_children_other' => Input::get('looking_children_other'),
                            'occupation_looking_child' => Input::get('occupation_looking_child'),
                            'occupation_looking_child_other' => Input::get('occupation_looking_child_other'),
                            'accompany' => Input::get('accompany'),
                            'accompany_occupation' => Input::get('accompany_occupation'),
                            'accompany_occupation_other' => Input::get('accompany_occupation_other'),
                            'accompany_transport' => Input::get('accompany_transport'),
                            'accompany_expenses' => Input::get('accompany_expenses'),
                            'activities_disrupted' => Input::get('activities_disrupted'),
                            'material_floor' => Input::get('material_floor'),
                            'material_floor_other' => Input::get('material_floor_other'),
                            'material_roof' => Input::get('material_roof'),
                            'material_roof_other' => Input::get('material_roof_other'),
                            'cooking_fuel' => Input::get('cooking_fuel'),
                            'cooking_fuel_other' => Input::get('cooking_fuel_other'),
                            'water_access' => Input::get('water_access'),
                            'water_source' => Input::get('water_source'),
                            'water_source_other' => Input::get('water_source_other'),
                            'toilet_access' => Input::get('toilet_access'),
                            'toilet_facility' => Input::get('toilet_facility'),
                            'toilet_access_other' => Input::get('toilet_access_other'),
                            'television' => Input::get('television'),
                            'refrigerator' => Input::get('refrigerator'),
                            'sofa' => Input::get('sofa'),
                            'clock' => Input::get('clock'),
                            'fan' => Input::get('fan'),
                            'vcr_dvd' => Input::get('vcr_dvd'),
                            'bank_account' => Input::get('bank_account'),
                            'no_food' => Input::get('no_food'),
                            'sleep_hungry' => Input::get('sleep_hungry'),
                            'day_hungry' => Input::get('day_hungry'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Social economic  details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq']);
                    die;
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
                        <?php $client = $override->get('clients', 'id', $_GET['cid'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Client</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" enctype="multipart/form-data" method="post">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Registration Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="clinic_date" id="clinic_date" value="<?php if ($client['clinic_date']) {
                                                                                                                                                                print_r($client['clinic_date']);
                                                                                                                                                            }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input class="validate[required]" type="text" name="firstname" id="firstname" value="<?php if ($client['firstname']) {
                                                                                                                                                print_r($client['firstname']);
                                                                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input class="validate[required]" type="text" name="middlename" id="middlename" value="<?php if ($client['middlename']) {
                                                                                                                                                print_r($client['middlename']);
                                                                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input class="validate[required]" type="text" name="lastname" id="lastname" value="<?php if ($client['lastname']) {
                                                                                                                                            print_r($client['lastname']);
                                                                                                                                        }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date of Birth</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="dob" id="dob" value="<?php if ($client['dob']) {
                                                                                                                                                print_r($client['dob']);
                                                                                                                                            }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" style="width: 100%;" required>
                                                        <option value="<?= $client['gender'] ?>"><?php if ($client) {
                                                                                                        if ($client['gender'] == 1) {
                                                                                                            echo 'Male';
                                                                                                        } elseif ($client['gender'] == 2) {
                                                                                                            echo 'Female';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Education Level</label>
                                                    <select name="education_level" style="width: 100%;" required>
                                                        <option value="<?= $client['education_level'] ?>"><?php if ($client) {
                                                                                                                if ($client['education_level'] == 1) {
                                                                                                                    echo 'Not attended school';
                                                                                                                } elseif ($client['education_level'] == 2) {
                                                                                                                    echo 'Primary';
                                                                                                                } elseif ($client['education_level'] == 3) {
                                                                                                                    echo 'Secondary';
                                                                                                                } elseif ($client['education_level'] == 4) {
                                                                                                                    echo 'Certificate';
                                                                                                                } elseif ($client['education_level'] == 5) {
                                                                                                                    echo 'Diploma';
                                                                                                                } elseif ($client['education_level'] == 6) {
                                                                                                                    echo 'Undergraduate degree';
                                                                                                                } elseif ($client['education_level'] == 7) {
                                                                                                                    echo 'Postgraduate degree';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Not attended school</option>
                                                        <option value="2">Primary</option>
                                                        <option value="3">Secondary</option>
                                                        <option value="4">Certificate</option>
                                                        <option value="5">Diploma</option>
                                                        <option value="6">Undergraduate degree</option>
                                                        <option value="7">Postgraduate degree</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hospital ID (Patient Hospital ID Number )</label>
                                                    <input type="text" name="hospital_id" id="hospital_id" value="<?php if ($client['hospital_id']) {
                                                                                                                        print_r($client['hospital_id']);
                                                                                                                    }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <?php
                                    //  if ($override->get4('clients', 'id', $_GET['cid'], 'age')) {
                                    ?>
                                    <div id="adult">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Employment status</label>
                                                        <select name="employment_status" style="width: 100%;" required>
                                                            <option value="<?= $client['employment_status'] ?>"><?php if ($client) {
                                                                                                                    if ($client['employment_status'] == 1) {
                                                                                                                        echo 'Employed';
                                                                                                                    } elseif ($client['employment_status'] == 2) {
                                                                                                                        echo 'Self-employed';
                                                                                                                    } elseif ($client['employment_status'] == 3) {
                                                                                                                        echo 'Employed but on leave of absence';
                                                                                                                    } elseif ($client['employment_status'] == 4) {
                                                                                                                        echo 'Unemployed';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Employed</option>
                                                            <option value="2">Self-employed</option>
                                                            <option value="3">Employed but on leave of absence</option>
                                                            <option value="4">Unemployed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Occupational Exposures</label>
                                                        <select name="occupation" style="width: 100%;" required>
                                                            <option value="<?= $client['occupation'] ?>"><?php if ($client) {
                                                                                                                if ($client['occupation'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($client['occupation'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($client['occupation'] == 3) {
                                                                                                                    echo 'Unknown';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>If yes, list exposure:</label>
                                                        <textarea name="exposure" rows="4"><?php if ($client['exposure']) {
                                                                                                print_r($client['exposure']);
                                                                                            }  ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    // }
                                    ?>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Patient Phone Number</label>
                                                    <input class="" type="text" name="phone_number" id="phone_number" value="<?php if ($client['phone_number']) {
                                                                                                                                    print_r($client['phone_number']);
                                                                                                                                }  ?>" /> <span>Example: 0700 000 111</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Guardian Phone Number</label>
                                                    <input class="" type="text" name="guardian_phone" id="guardian_phone" value="<?php if ($client['guardian_phone']) {
                                                                                                                                        print_r($client['guardian_phone']);
                                                                                                                                    }  ?>" /> <span>Example: 0700 000 111</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Guardian Name</label>
                                                    <input class="" type="text" name="guardian_name" id="guardian_name" value="<?php if ($client['guardian_name']) {
                                                                                                                                    print_r($client['guardian_name']);
                                                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Relation to patient</label>
                                                    <input class="" type="text" name="relation_patient" id="relation_patient" value="<?php if ($client['relation_patient']) {
                                                                                                                                            print_r($client['relation_patient']);
                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Physical Address</label>
                                                    <input class="" type="text" name="physical_address" id="physical_address" value="<?php if ($client['physical_address']) {
                                                                                                                                            print_r($client['physical_address']);
                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Comments / Remarks:</label>
                                                    <textarea name="comments" rows="4"><?php if ($client['comments']) {
                                                                                            print_r($client['comments']);
                                                                                        }  ?></textarea>
                                                </div>
                                            </div>
                                        </div>
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
                        <?php $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Demographic</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-8">Visit Date</div>
                                        <div class="col-md-4">
                                            <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($demographic['visit_date']) {
                                                                                                                                                    print_r($demographic['visit_date']);
                                                                                                                                                }  ?>" />
                                            <span>Example: 2010-12-01</span>
                                        </div>
                                    </div>

                                    <?php
                                    if (!$override->get4('clients', 'id', $_GET['cid'], 'age')) {
                                    ?>

                                        <div id="child">
                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Appropriate grade for age:</div>
                                                <div class="col-md-9">
                                                    <select name="grade_age" style="width: 100%;" required>
                                                        <option value="<?= $demographic['grade_age'] ?>"><?php if ($demographic) {
                                                                                                                if ($demographic['grade_age'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($demographic['grade_age'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($demographic['grade_age'] == 3) {
                                                                                                                    echo 'N/A';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
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
                                                        <option value="<?= $demographic['school_attendance'] ?>"><?php if ($demographic) {
                                                                                                                        if ($demographic['school_attendance'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($demographic['school_attendance'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        } elseif ($demographic['school_attendance'] == 3) {
                                                                                                                            echo 'N/A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">N/A</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="row-form clearfix">
                                                    <div class="col-md-3">Days of missed school in past month:</div>
                                                    <div class="col-md-9"><input class="" type="number" min="1" name="missed_school" id="missed_school" value="<?php if ($demographic['missed_school']) {
                                                                                                                                                                    print_r($demographic['missed_school']);
                                                                                                                                                                }  ?>" /></div>
                                                </div>
                                            </div>

                                        <?php
                                    }
                                        ?>


                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Patient referred from:</div>
                                            <div class="col-md-9">
                                                <select id="referred" name="referred" style="width: 100%;" required>
                                                    <option value="<?= $demographic['referred'] ?>"><?php if ($demographic) {
                                                                                                        if ($demographic['referred'] == 1) {
                                                                                                            echo 'Inpatient / hospital stay';
                                                                                                        } elseif ($demographic['referred'] == 2) {
                                                                                                            echo 'Primary care clinic';
                                                                                                        } elseif ($demographic['referred'] == 3) {
                                                                                                            echo 'Other outpatient clinic';
                                                                                                        } elseif ($demographic['referred'] == 4) {
                                                                                                            echo 'Maternal health';
                                                                                                        } elseif ($demographic['referred'] == 5) {
                                                                                                            echo 'Community';
                                                                                                        } elseif ($demographic['referred'] == 6) {
                                                                                                            echo 'Self';
                                                                                                        } elseif ($demographic['referred'] == 7) {
                                                                                                            echo 'Other';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
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

                                        <div id="referred_other">
                                            <div class="row-form clearfix">
                                                <div class="col-md-3">Other Specify:</div>
                                                <div class="col-md-9"><input class="" type="text" name="referred_other" value="<?php if ($demographic['referred_other']) {
                                                                                                                                    print_r($demographic['referred_other']);
                                                                                                                                }  ?>" /></div>
                                            </div>
                                        </div>

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Household Size:</div>
                                            <div class="col-md-9"><input class="" type="number" min="1" name="household_size" id="household_size" value="<?php if ($demographic['household_size']) {
                                                                                                                                                                print_r($demographic['household_size']);
                                                                                                                                                            }  ?>" /></div>
                                        </div>

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Agrees to home visits</div>
                                            <div class="col-md-9">
                                                <select name="next_visit" style="width: 100%;" required>
                                                    <option value="<?= $demographic['next_visit'] ?>"><?php if ($demographic) {
                                                                                                            if ($demographic['next_visit'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($demographic['next_visit'] == 2) {
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
                                            <div class="col-md-3">CHW name:</div>
                                            <div class="col-md-9"><input class="" type="text" name="chw" id="chw" value="<?php if ($demographic['chw']) {
                                                                                                                                print_r($demographic['chw']);
                                                                                                                            }  ?>" /></div>
                                        </div>

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Comments:</div>
                                            <div class="col-md-9"><textarea name="comments" rows="4"><?php if ($demographic['comments']) {
                                                                                                            print_r($demographic['comments']);
                                                                                                        }  ?> </textarea> </div>
                                        </div>

                                        <div class="footer tar">
                                            <input type="hidden" name="sid" value="<?= $_GET['sid'] ?>">
                                            <input type="submit" name="add_demographic" value="Submit" class="btn btn-default">
                                        </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <?php $vital = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>VITAL SIGNS</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Vital Signs Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($vital['visit_date']) {
                                                                                                                                                            print_r($vital['visit_date']);
                                                                                                                                                        }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Ht (cm)</label>
                                                    <input type="text" name="height" id="height" value="<?php if ($vital['height']) {
                                                                                                            print_r($vital['height']);
                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Wt (kg):</label>
                                                    <input type="text" name="weight" id="weight" value="<?php if ($vital['weight']) {
                                                                                                            print_r($vital['weight']);
                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-12">
                                        <div class="row-form clearfix">
                                            <div class="form-group">
                                                <label>BMI</label>
                                                <span id="bmi"></span>&nbsp;&nbsp;kg/m2
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>MUAC (cm)</label>
                                                    <input type="text" name="muac" id="muac" value="<?php if ($vital['muac']) {
                                                                                                        print_r($vital['muac']);
                                                                                                    }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>BP</label>
                                                    <input type="text" name="bp" id="bp" value="<?php if ($vital['bp']) {
                                                                                                    print_r($vital['bp']);
                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>PR</label>
                                                    <input type="text" name="pr" id="pr" value="<?php if ($vital['pr']) {
                                                                                                    print_r($vital['pr']);
                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="hidden" name="bmi" value="<?= $vital['bmi'] ?>">
                                        <input type="submit" name="add_vital" value="Submit" class="btn btn-default">
                                    </div>
                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 9) { ?>
                        <?php $history = $override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Patient Hitory & Complication</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Diseases History</h1>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Cardiac</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Hypertension</label>
                                                        <select name="hypertension" style="width: 100%;" required>
                                                            <option value="<?= $history['hypertension'] ?>"><?php if ($history) {
                                                                                                                if ($history['hypertension'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['hypertension'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Diabetes</label>
                                                        <select name="diabetes" style="width: 100%;" required>
                                                            <option value="<?= $history['diabetes'] ?>"><?php if ($history) {
                                                                                                            if ($history['diabetes'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($history['diabetes'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>CKD</label>
                                                        <select name="ckd" style="width: 100%;" required>
                                                            <option value="<?= $history['ckd'] ?>"><?php if ($history) {
                                                                                                        if ($history['ckd'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($history['ckd'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Depression</label>
                                                        <select name="depression" style="width: 100%;" required>
                                                            <option value="<?= $history['depression'] ?>"><?php if ($history) {
                                                                                                                if ($history['depression'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['depression'] == 2) {
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
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Diabetic</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Cardiovascular Diseases</label>
                                                        <select name="cardiovascular" style="width: 100%;" required>
                                                            <option value="<?= $history['cardiovascular'] ?>"><?php if ($history) {
                                                                                                                    if ($history['cardiovascular'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($history['cardiovascular'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <span> (eg. Heart attack, ischemic heart disease, CCF)</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Cardiovascular</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="cardiovascular_date" id="cardiovascular_date" value="<?php if ($history['cardiovascular_date']) {
                                                                                                                                                                                    print_r($history['cardiovascular_date']);
                                                                                                                                                                                }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Retinopathy</label>
                                                        <select name="retinopathy" style="width: 100%;" required>
                                                            <option value="<?= $history['retinopathy'] ?>"><?php if ($history) {
                                                                                                                if ($history['retinopathy'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['retinopathy'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Retinopathy</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="retinopathy_date" id="retinopathy_date" value="<?php if ($history['retinopathy_date']) {
                                                                                                                                                                            print_r($history['retinopathy_date']);
                                                                                                                                                                        }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Renal Disease </label>
                                                        <select name="renal" style="width: 100%;" required>
                                                            <option value="<?= $history['renal'] ?>"><?php if ($history) {
                                                                                                            if ($history['renal'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($history['renal'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <span> (e.g elevated creatinine)</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Renal</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="renal_date" id="renal_date" value="<?php if ($history['renal_date']) {
                                                                                                                                                                print_r($history['renal_date']);
                                                                                                                                                            }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Stroke / TIA</label>
                                                        <select name="stroke_tia" style="width: 100%;" required>
                                                            <option value="<?= $history['stroke_tia'] ?>"><?php if ($history) {
                                                                                                                if ($history['stroke_tia'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['stroke_tia'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Stroke / TIA</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="stroke_tia_date" id="stroke_tia_date" value="<?php if ($history['stroke_tia_date']) {
                                                                                                                                                                            print_r($history['stroke_tia_date']);
                                                                                                                                                                        }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>PVD </label>
                                                        <select name="pvd" style="width: 100%;" required>
                                                            <option value="<?= $history['pvd'] ?>"><?php if ($history) {
                                                                                                        if ($history['pvd'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($history['pvd'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <span> (e.g ulcers, gangrene)</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of PVD</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="pvd_date" id="pvd_date" value="<?php if ($history['pvd_date']) {
                                                                                                                                                            print_r($history['pvd_date']);
                                                                                                                                                        }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Neuropathy</label>
                                                        <select name="neuropathy" style="width: 100%;" required>
                                                            <option value="<?= $history['neuropathy'] ?>"><?php if ($history) {
                                                                                                                if ($history['neuropathy'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['neuropathy'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Neuropathy</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="neuropathy_date" id="neuropathy_date" value="<?php if ($history['neuropathy_date']) {
                                                                                                                                                                            print_r($history['neuropathy_date']);
                                                                                                                                                                        }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Sexual dysfunction</label>
                                                        <select name="sexual_dysfunction" style="width: 100%;" required>
                                                            <option value="<?= $history['sexual_dysfunction'] ?>"><?php if ($history) {
                                                                                                                        if ($history['sexual_dysfunction'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($history['sexual_dysfunction'] == 2) {
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
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date of Sexual dysfunction</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="sexual_dysfunction_date" id="sexual_dysfunction_date" value="<?php if ($history['sexual_dysfunction_date']) {
                                                                                                                                                                                            print_r($history['sexual_dysfunction_date']);
                                                                                                                                                                                        }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Sickle Cell</h1>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Previous complications at intake</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Event</label>
                                                        <select name="pain_event" style="width: 100%;" required>
                                                            <option value="<?= $history['pain_event'] ?>"><?php if ($history) {
                                                                                                                if ($history['pain_event'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['pain_event'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Stroke</label>
                                                        <select name="stroke" style="width: 100%;" required>
                                                            <option value="<?= $history['stroke'] ?>"><?php if ($history) {
                                                                                                            if ($history['stroke'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($history['stroke'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pneumonia </label>
                                                        <select name="pneumonia" style="width: 100%;" required>
                                                            <option value="<?= $history['pneumonia'] ?>"><?php if ($history) {
                                                                                                                if ($history['pneumonia'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['pneumonia'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Blood Transfusion</label>
                                                        <select name="blood_transfusion" style="width: 100%;" required>
                                                            <option value="<?= $history['blood_transfusion'] ?>"><?php if ($history) {
                                                                                                                        if ($history['blood_transfusion'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($history['blood_transfusion'] == 2) {
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
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Acute chest syndrome </label>
                                                        <select name="acute_chest" style="width: 100%;" required>
                                                            <option value="<?= $history['acute_chest'] ?>"><?php if ($history) {
                                                                                                                if ($history['acute_chest'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['acute_chest'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Other</label>
                                                        <select name="other_complication" style="width: 100%;" required>
                                                            <option value="<?= $history['other_complication'] ?>"><?php if ($history) {
                                                                                                                        if ($history['other_complication'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($history['other_complication'] == 2) {
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
                                            </div>

                                            <div class="col-sm-6" id="specify_complication">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other Specify</label>
                                                        <input type="text" name="specify_complication" value="<?php if ($history['specify_complication']) {
                                                                                                                    print_r($history['specify_complication']);
                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>


                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>HIV</label>
                                                    <select name="hiv" id="hiv" style="width: 100%;" required>
                                                        <option value="<?= $history['hiv'] ?>"><?php if ($history) {
                                                                                                    if ($history['hiv'] == 1) {
                                                                                                        echo 'R';
                                                                                                    } elseif ($history['hiv'] == 2) {
                                                                                                        echo 'NR';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?></option>
                                                        <option value="1">R</option>
                                                        <option value="2">NR</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date Of Test</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="hiv_test" id="hiv_test" value="<?php if ($history['hiv_test']) {
                                                                                                                                                        print_r($history['hiv_test']);
                                                                                                                                                    }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-4" id="art_date">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>ART Start Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="art_date" id="art_date" value="<?php if ($history['art_date']) {
                                                                                                                                                        print_r($history['art_date']);
                                                                                                                                                    }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>TB</label>
                                                    <select name="tb" id="tb" style="width: 100%;" required>
                                                        <option value="<?= $history['tb'] ?>"><?php if ($history) {
                                                                                                    if ($history['tb'] == 1) {
                                                                                                        echo 'Smear pos';
                                                                                                    } elseif ($history['tb'] == 2) {
                                                                                                        echo 'Smear neg';
                                                                                                    } elseif ($history['tb'] == 3) {
                                                                                                        echo 'EPTB';
                                                                                                    } elseif ($history['tb'] == 4) {
                                                                                                        echo 'never had TB';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?></option>
                                                        <option value="1">Smear pos</option>
                                                        <option value="2">Smear neg</option>
                                                        <option value="3">EPTB</option>
                                                        <option value="4">never had TB</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" id="tb_year">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Year TB tested</label>
                                                    <input type="text" name="tb_year" value="<?php if ($history['tb_year']) {
                                                                                                    print_r($history['tb_year']);
                                                                                                }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>History of smoking</label>
                                                    <select name="smoking" id="smoking" style="width: 100%;" required>
                                                        <option value="<?= $history['smoking'] ?>"><?php if ($history) {
                                                                                                        if ($history['smoking'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($history['smoking'] == 2) {
                                                                                                            echo 'No';
                                                                                                        } elseif ($history['smoking'] == 3) {
                                                                                                            echo 'Unknown';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unknown</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" id="packs">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Number of pack years</label>
                                                    <input type="text" name="packs" value="<?php if ($history['packs']) {
                                                                                                print_r($history['packs']);
                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-4" id="active_smoker">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Active smoker</label>
                                                    <select name="active_smoker" style="width: 100%;" required>
                                                        <option value="<?= $history['active_smoker'] ?>"><?php if ($history) {
                                                                                                                if ($history['active_smoker'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['active_smoker'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($history['active_smoker'] == 3) {
                                                                                                                    echo 'Unknown';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unknown</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Alcohol consumption</label>
                                                    <select name="alcohol" id="alcohol" style="width: 100%;" required>
                                                        <option value="<?= $history['alcohol'] ?>"><?php if ($history) {
                                                                                                        if ($history['alcohol'] == 1) {
                                                                                                            echo 'Yes, currently';
                                                                                                        } elseif ($history['alcohol'] == 2) {
                                                                                                            echo 'Yes, in the past';
                                                                                                        } elseif ($history['alcohol'] == 3) {
                                                                                                            echo 'never';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                        <option value="1">Yes, currently</option>
                                                        <option value="2">Yes, in the past</option>
                                                        <option value="3">never</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4" id="quantity">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Quantity (number of bottle)</label>
                                                    <input type="text" name="quantity" value="<?php if ($history['quantity']) {
                                                                                                    print_r($history['quantity']);
                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Family History of cardiac disease?</label>
                                                        <select name="cardiac_disease" style="width: 100%;" required>
                                                            <option value="<?= $history['cardiac_disease'] ?>"><?php if ($history) {
                                                                                                                    if ($history['cardiac_disease'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($history['cardiac_disease'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($history['cardiac_disease'] == 3) {
                                                                                                                        echo 'Unknown';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>History of cardiac surgery?</label>
                                                        <select name="cardiac_surgery" id="cardiac_surgery" style="width: 100%;" required>
                                                            <option value="<?= $history['cardiac_surgery'] ?>"><?php if ($history) {
                                                                                                                    if ($history['cardiac_surgery'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($history['cardiac_surgery'] == 2) {
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
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Type of cardiac surgery</label>
                                                        <select name="cardiac_surgery_type" id="cardiac_surgery_type" style="width: 100%;" onchange="handleSelectChange(this)" required>
                                                            <option value=" <?= $history['cardiac_surgery_type'] ?>"><?php if ($history) {
                                                                                                                            if ($history['cardiac_surgery_type'] == 1) {
                                                                                                                                echo 'Valve Surgery';
                                                                                                                            } elseif ($history['cardiac_surgery_type'] == 2) {
                                                                                                                                echo 'Defect repair';
                                                                                                                            } elseif ($history['cardiac_surgery_type'] == 96) {
                                                                                                                                echo 'Other specify';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                            <option value="1">Valve Surgery</option>
                                                            <option value="2">Defect repair</option>
                                                            <option value="96">Other specify</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3" id="surgery_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify surgery</label>
                                                        <input type="text" name="surgery_other" value="<?php if ($history['surgery_other']) {
                                                                                                            print_r($history['surgery_other']);
                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Family History of Diabetic disease?</label>
                                                        <select name="diabetic_disease" style="width: 100%;" required>
                                                            <option value="<?= $history['diabetic_disease'] ?>"><?php if ($history) {
                                                                                                                    if ($history['diabetic_disease'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($history['diabetic_disease'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($history['diabetic_disease'] == 3) {
                                                                                                                        echo 'Unknown';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Hypertension ?</label>
                                                        <select name="hypertension_disease" id="hypertension_disease" style="width: 100%;" required>
                                                            <option value="<?= $history['hypertension_disease'] ?>"><?php if ($history) {
                                                                                                                        if ($history['hypertension_disease'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($history['hypertension_disease'] == 2) {
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
                                            </div>

                                            <div class="col-sm-4" id="diabetic_other">
                                                2 <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify Other</label>
                                                        <input type="text" name="history_other" value="<?php if ($history['history_other']) {
                                                                                                            print_r($history['history_other']);
                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Family History of SCD?</label>
                                                        <select name="scd_disease" style="width: 100%;" required>
                                                            <option value="<?= $history['scd_disease'] ?>"><?php if ($history) {
                                                                                                                if ($history['scd_disease'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($history['scd_disease'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($history['scd_disease'] == 3) {
                                                                                                                    echo 'Unknown';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>


                                    <div class="footer tar">
                                        <input type="submit" name="add_history" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 10) { ?>
                        <?php $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>History, Symptoms, & Exam</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Symptoms ( Cardiac )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($symptoms['visit_date']) {
                                                                                                                                                                print_r($symptoms['visit_date']);
                                                                                                                                                            }  ?>" />
                                                        <span>Example: 2010-12-01</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Dyspnea on exertion: NYHA Classification</label>
                                                        <select name="dyspnea" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['dyspnea'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['dyspnea'] == 1) {
                                                                                                                echo 'I';
                                                                                                            } elseif ($symptoms['dyspnea'] == 2) {
                                                                                                                echo 'II';
                                                                                                            } elseif ($symptoms['dyspnea'] == 3) {
                                                                                                                echo 'III';
                                                                                                            } elseif ($symptoms['dyspnea'] == 4) {
                                                                                                                echo 'IV';
                                                                                                            } elseif ($symptoms['dyspnea'] == 5) {
                                                                                                                echo 'cannot determine';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">I</option>
                                                            <option value="2">II</option>
                                                            <option value="3">III</option>
                                                            <option value="4">IV</option>
                                                            <option value="5">cannot determine</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Orthopnea</label>
                                                        <select name="orthopnea" id="orthopnea" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['orthopnea'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['orthopnea'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['orthopnea'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['orthopnea'] == 3) {
                                                                                                                    echo 'Unsure';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Paroxysmal nocturnal dyspnea</label>
                                                        <select name="paroxysmal" id="paroxysmal" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['paroxysmal'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['paroxysmal'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['paroxysmal'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['paroxysmal'] == 3) {
                                                                                                                    echo 'Unsure';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Chest Pain</label>
                                                        <select name="chest_pain" id="chest_pain" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['chest_pain'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['chest_pain'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['chest_pain'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['chest_pain'] == 3) {
                                                                                                                    echo 'Unsure';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Cough</label>
                                                        <select name="cough" id="cough" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['cough'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['cough'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['cough'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['cough'] == 3) {
                                                                                                                echo 'Unsure';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    <?php } ?>


                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Glucose Monitoring ( Diabetic )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Fasting FS:</label>
                                                        <input type="text" name="fasting" value="<?php if ($symptoms['fasting']) {
                                                                                                        print_r($symptoms['fasting']);
                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Random FS:</label>
                                                        <input type="text" name="random_fs" value="<?php if ($symptoms['random_fs']) {
                                                                                                        print_r($symptoms['random_fs']);
                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>HbA1C:</label>
                                                        <input type="text" name="hba1c" value="<?php if ($symptoms['hba1c']) {
                                                                                                    print_r($symptoms['hba1c']);
                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Hypoglycemia ( Diabetic )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Symptoms of hypoglycemia?:</label>
                                                        <select name="hypoglycemia_symptoms" id="hypoglycemia_symptoms" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['hypoglycemia_symptoms'] ?>"><?php if ($symptoms) {
                                                                                                                            if ($symptoms['hypoglycemia_symptoms'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($symptoms['hypoglycemia_symptoms'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            } elseif ($symptoms['hypoglycemia_symptoms'] == 3) {
                                                                                                                                echo 'Unsure';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Severe hypoglycemia in last month?:</label>
                                                        <select name="hypoglycemia_severe" id="hypoglycemia_severe" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['hypoglycemia_severe'] ?>"><?php if ($symptoms) {
                                                                                                                        if ($symptoms['hypoglycemia_severe'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($symptoms['hypoglycemia_severe'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        } elseif ($symptoms['hypoglycemia_severe'] == 3) {
                                                                                                                            echo 'Unsure';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>If Yes, how many episodes of severe hypoglycemia</label>
                                                        <input type="text" name="hypoglycemia__number" value="<?php if ($symptoms['hypoglycemia__number']) {
                                                                                                                    print_r($symptoms['hypoglycemia__number']);
                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Symptoms ( Diabetic )</h1>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Increased thirst</label>
                                                        <select name="thirst" id="thirst" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['thirst'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['thirst'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['thirst'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['thirst'] == 3) {
                                                                                                                echo 'Unk';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Increased Urination</label>
                                                        <select name="urination" id="urination" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['urination'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['urination'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['urination'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['urination'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Vision Changes</label>
                                                        <select name="vision" id="vision" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['vision'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['vision'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['vision'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['vision'] == 3) {
                                                                                                                echo 'Unk';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Abnorminal Pain</label>
                                                        <select name="abnorminal_pain" id="abnorminal_pain" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['abnorminal_pain'] ?>"><?php if ($symptoms) {
                                                                                                                    if ($symptoms['abnorminal_pain'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($symptoms['abnorminal_pain'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($symptoms['abnorminal_pain'] == 3) {
                                                                                                                        echo 'Unk';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Vomiting</label>
                                                        <select name="vomiting" id="vomiting" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['vomiting'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['vomiting'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['vomiting'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['vomiting'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Weight Loss</label>
                                                        <select name="weight_loss" id="weight_loss" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['weight_loss'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['weight_loss'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['weight_loss'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['weight_loss'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Symptoms ( Sickle Cell )</h1>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Difficulty Breathing</label>
                                                        <select name="breathing" id="breathing" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['breathing'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['breathing'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['breathing'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['breathing'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Chest Pain</label>
                                                        <select name="chest_pain2" id="chest_pain2" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['chest_pain2'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['chest_pain2'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['chest_pain2'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['chest_pain2'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="3">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today:</label>
                                                        <input type="text" name="pain_score" value="<?php if ($symptoms['pain_score']) {
                                                                                                        print_r($symptoms['pain_score']);
                                                                                                    }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>

                                            <div class="col-sm-5">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <input type="text" name="other_sickle" value="<?php if ($symptoms['other_sickle']) {
                                                                                                            print_r($symptoms['other_sickle']);
                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <?php } ?>


                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Exam ( Cardiac )</h1>
                                        </div>

                                        <div class="row">


                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Edema</label>
                                                        <select name="edema" id="edema" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['edema'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['edema'] == 1) {
                                                                                                                echo 'None';
                                                                                                            } elseif ($symptoms['edema'] == 2) {
                                                                                                                echo 'Trace';
                                                                                                            } elseif ($symptoms['edema'] == 3) {
                                                                                                                echo '1+';
                                                                                                            } elseif ($symptoms['edema'] == 4) {
                                                                                                                echo '2+';
                                                                                                            } elseif ($symptoms['edema'] == 5) {
                                                                                                                echo '3+';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">None</option>
                                                            <option value="2">Trace</option>
                                                            <option value="3">1+</option>
                                                            <option value="4">2+</option>
                                                            <option value="5">3+</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Lungs</label>
                                                        <select name="lungs" id="lungs" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['lungs'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['lungs'] == 1) {
                                                                                                                echo 'Clear';
                                                                                                            } elseif ($symptoms['lungs'] == 2) {
                                                                                                                echo 'Bibasilar';
                                                                                                            } elseif ($symptoms['lungs'] == 3) {
                                                                                                                echo 'Crackles';
                                                                                                            } elseif ($symptoms['lungs'] == 4) {
                                                                                                                echo 'Wheeze';
                                                                                                            } elseif ($symptoms['lungs'] == 5) {
                                                                                                                echo 'Other';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Clear</option>
                                                            <option value="2">Bibasilar</option>
                                                            <option value="3">Crackles</option>
                                                            <option value="4">Wheeze</option>
                                                            <option value="5">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6" id="lungs_Other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify:</label>
                                                        <input type="text" name="Other" value="<?php if ($symptoms['Other']) {
                                                                                                    print_r($symptoms['Other']);
                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>JVP</label>
                                                        <select name="jvp" id="jvp" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['jvp'] ?>"><?php if ($symptoms) {
                                                                                                        if ($symptoms['jvp'] == 1) {
                                                                                                            echo 'Elevated';
                                                                                                        } elseif ($symptoms['jvp'] == 2) {
                                                                                                            echo 'Normal';
                                                                                                        } elseif ($symptoms['jvp'] == 3) {
                                                                                                            echo 'Unable to determine';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                            <option value="1">Elevated</option>
                                                            <option value="2">Normal</option>
                                                            <option value="3">Unable to determine</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Volume status</label>
                                                        <select name="volume" id="volume" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['volume'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['volume'] == 1) {
                                                                                                                echo 'Hyper';
                                                                                                            } elseif ($symptoms['volume'] == 2) {
                                                                                                                echo 'Hypo';
                                                                                                            } elseif ($symptoms['volume'] == 3) {
                                                                                                                echo 'Euvolemic';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Hyper</option>
                                                            <option value="2">Hypo</option>
                                                            <option value="3">Euvolemic</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Loud Murmur?</label>
                                                        <select name="murmur" id="murmur" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['murmur'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['murmur'] == 1) {
                                                                                                                echo 'Present';
                                                                                                            } elseif ($symptoms['murmur'] == 2) {
                                                                                                                echo 'Absent';
                                                                                                            } elseif ($symptoms['murmur'] == 3) {
                                                                                                                echo 'Unknown';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Present</option>
                                                            <option value="2">Absent</option>
                                                            <option value="3">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>


                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Foot Exam ( diabetes )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Foot Exam</label>
                                                        <select name="foot_exam" id="foot_exam" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['foot_exam'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['foot_exam'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['foot_exam'] == 2) {
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
                                            </div>

                                            <div class="col-sm-6" id="foot_exam_finding">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Foot exam Finding:</label>
                                                        <input type="text" name="foot_exam_finding" value="<?php if ($symptoms['foot_exam_finding']) {
                                                                                                                print_r($symptoms['foot_exam_finding']);
                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Exam ( Sickle Cell )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Malnutrition</label>
                                                        <select name="malnutrition" id="malnutrition" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['malnutrition'] ?>"><?php if ($symptoms) {
                                                                                                                    if ($symptoms['malnutrition'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($symptoms['malnutrition'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($symptoms['malnutrition'] == 3) {
                                                                                                                        echo 'Unk';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="2">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pallor</label>
                                                        <select name="pallor" id="pallor" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['pallor'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['pallor'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['pallor'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['pallor'] == 3) {
                                                                                                                echo 'Unk';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="2">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Jaundice</label>
                                                        <select name="jaundice" id="jaundice" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['jaundice'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['jaundice'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['jaundice'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['jaundice'] == 3) {
                                                                                                                    echo 'Unk';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="2">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Splenomegaly</label>
                                                        <select name="splenomegaly" id="splenomegaly" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['splenomegaly'] ?>"><?php if ($symptoms) {
                                                                                                                    if ($symptoms['splenomegaly'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($symptoms['splenomegaly'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($symptoms['splenomegaly'] == 3) {
                                                                                                                        echo 'Unk';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="2">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Anemia</label>
                                                        <select name="anemia" id="foot_exam" style="width: 100%;" required>
                                                            <option value="<?= $symptoms['anemia'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['anemia'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['anemia'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['anemia'] == 3) {
                                                                                                                echo 'Unk';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                            <option value="2">Unk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Labs ( Sickle Cell )</h1>
                                        </div>

                                        <div class="col-sm-3" id="hb">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hb:</label>
                                                    <input type="text" name="hb" value="<?php if ($symptoms['hb']) {
                                                                                            print_r($symptoms['hb']);
                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" id="wbc">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>WBC:</label>
                                                    <input type="text" name="wbc" value="<?php if ($symptoms['wbc']) {
                                                                                                print_r($symptoms['wbc']);
                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" id="plt">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Plt:</label>
                                                    <input type="text" name="plt" value="<?php if ($symptoms['plt']) {
                                                                                                print_r($symptoms['plt']);
                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" id="labs_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other:</label>
                                                    <input type="text" name="labs_other" value="<?php if ($symptoms['labs_other']) {
                                                                                                    print_r($symptoms['labs_other']);
                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div class="footer tar">
                                        <input type="submit" name="add_symptoms" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>



                    <?php } elseif ($_GET['id'] == 11) { ?>
                        <?php $diagnosis = $override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Main diagnosis (Cardiac)</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Diagnosis Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" value="<?php if ($diagnosis['diagnosis_date']) {
                                                                                                                                                                    print_r($diagnosis['diagnosis_date']);
                                                                                                                                                                }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Cardiac diagnosis</label>
                                                    <select name="cardiac" id="cardiac" style="width: 100%;" required>
                                                        <option value="<?= $diagnosis['cardiac'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['cardiac'] == 1) {
                                                                                                                echo 'Cardiomyopathy';
                                                                                                            } elseif ($diagnosis['cardiac'] == 2) {
                                                                                                                echo 'Rheumatic Heart Disease';
                                                                                                            } elseif ($diagnosis['cardiac'] == 3) {
                                                                                                                echo 'Severe / Uncontrolled Hypertension';
                                                                                                            } elseif ($diagnosis['cardiac'] == 4) {
                                                                                                                echo 'Hypertensive Heart Disease';
                                                                                                            } elseif ($diagnosis['cardiac'] == 5) {
                                                                                                                echo 'Congenital heart Disease';
                                                                                                            } elseif ($diagnosis['cardiac'] == 6) {
                                                                                                                echo 'Right Heart Failure';
                                                                                                            } elseif ($diagnosis['cardiac'] == 7) {
                                                                                                                echo 'Pericardial disease';
                                                                                                            } elseif ($diagnosis['cardiac'] == 8) {
                                                                                                                echo 'Coronary Artery Disease';
                                                                                                            } elseif ($diagnosis['cardiac'] == 9) {
                                                                                                                echo 'Arrhythmia';
                                                                                                            } elseif ($diagnosis['cardiac'] == 10) {
                                                                                                                echo 'Thromboembolic';
                                                                                                            } elseif ($diagnosis['cardiac'] == 11) {
                                                                                                                echo 'Stroke';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
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
                                        </div>
                                    </div>


                                    <div id="cardiomyopathy">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Cardiomyopathy</div>
                                            <div class="col-md-9">
                                                <select name="cardiomyopathy" id="cardiomyopathy1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['cardiomyopathy'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['cardiomyopathy'] == 1) {
                                                                                                                echo 'Ischemic';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 2) {
                                                                                                                echo 'Alcohol-related';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 3) {
                                                                                                                echo 'Peripartum';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 4) {
                                                                                                                echo 'Arrhythmia-related';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 5) {
                                                                                                                echo 'HIV-related';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 6) {
                                                                                                                echo 'Chemotherapy-related';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 7) {
                                                                                                                echo 'Viral/idiopathic';
                                                                                                            } elseif ($diagnosis['cardiomyopathy'] == 8) {
                                                                                                                echo 'Other';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
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
                                                <select name="heumatic" id="heumatic1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['heumatic'] ?>"><?php if ($diagnosis) {
                                                                                                        if ($diagnosis['heumatic'] == 1) {
                                                                                                            echo 'Pure mitral stenosis';
                                                                                                        } elseif ($diagnosis['heumatic'] == 2) {
                                                                                                            echo 'Pure mitral regurgitation';
                                                                                                        } elseif ($diagnosis['heumatic'] == 3) {
                                                                                                            echo 'Mixed mitral valve disease (MS + MR)';
                                                                                                        } elseif ($diagnosis['heumatic'] == 4) {
                                                                                                            echo 'Isolated aortic valve disease (AVD)';
                                                                                                        } elseif ($diagnosis['heumatic'] == 5) {
                                                                                                            echo 'Mixed mitral and aortic valve disease (MMAVD)';
                                                                                                        } elseif ($diagnosis['heumatic'] == 6) {
                                                                                                            echo 'Other';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
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

                                                <select name="congenital" id="congenital1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['congenital'] ?>"><?php if ($diagnosis) {
                                                                                                        if ($diagnosis['congenital'] == 1) {
                                                                                                            echo 'ASD';
                                                                                                        } elseif ($diagnosis['congenital'] == 2) {
                                                                                                            echo 'VSD';
                                                                                                        } elseif ($diagnosis['congenital'] == 3) {
                                                                                                            echo 'PDA';
                                                                                                        } elseif ($diagnosis['congenital'] == 4) {
                                                                                                            echo 'Coarctation of aorta';
                                                                                                        } elseif ($diagnosis['congenital'] == 5) {
                                                                                                            echo 'Tetralogy of Fallot';
                                                                                                        } elseif ($diagnosis['congenital'] == 6) {
                                                                                                            echo 'Other';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
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

                                                <select name="heart_failure" id="heart_failure1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['heart_failure'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['heart_failure'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($diagnosis['heart_failure'] == 2) {
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
                                    </div>


                                    <div id="Pericardial">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">If Pericardial disease</div>
                                            <div class="col-md-9">

                                                <select name="pericardial" id="pericardial1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['pericardial'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['pericardial'] == 1) {
                                                                                                                echo 'Tuberculosis';
                                                                                                            } elseif ($diagnosis['pericardial'] == 2) {
                                                                                                                echo 'HIV';
                                                                                                            } elseif ($diagnosis['pericardial'] == 3) {
                                                                                                                echo 'malignancy';
                                                                                                            } elseif ($diagnosis['pericardial'] == 4) {
                                                                                                                echo 'Other';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
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

                                                <select name="arrhythmia" id="arrhythmia1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['arrhythmia'] ?>"><?php if ($diagnosis) {
                                                                                                        if ($diagnosis['arrhythmia'] == 1) {
                                                                                                            echo 'Atrial fibrillation';
                                                                                                        } elseif ($diagnosis['arrhythmia'] == 2) {
                                                                                                            echo 'Other';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
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
                                                <select name="thromboembolic" id="thromboembolic1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['thromboembolic'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['thromboembolic'] == 1) {
                                                                                                                echo 'pulmonary embolism';
                                                                                                            } elseif ($diagnosis['thromboembolic'] == 2) {
                                                                                                                echo 'DVT';
                                                                                                            } elseif ($diagnosis['thromboembolic'] == 4) {
                                                                                                                echo 'Other';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
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
                                                <select name="stroke" id="stroke1" style="width: 100%;">
                                                    <option value="<?= $diagnosis['thromboembolic'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['stroke'] == 1) {
                                                                                                                echo 'Ischemic';
                                                                                                            } elseif ($diagnosis['thromboembolic'] == 2) {
                                                                                                                echo 'hemorrhagic';
                                                                                                            } elseif ($diagnosis['stroke'] == 3) {
                                                                                                                echo 'unknown';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                    <option value="1">Ischemic</option>
                                                    <option value="2">hemorrhagic</option>
                                                    <option value="3">unknown</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="diagnosis_other">
                                        <div class="col-md-3">Other specify:</div>
                                        <div class="col-md-9"><textarea name="diagnosis_other" rows="4"><?php if ($diagnosis['diagnosis_other']) {
                                                                                                            print_r($diagnosis['diagnosis_other']);
                                                                                                        }  ?></textarea> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"><?php if ($diagnosis['comments']) {
                                                                                                        print_r($diagnosis['comments']);
                                                                                                    }  ?></textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_diagnosis" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 12) { ?>
                        <?php $results = $override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <?php if ($_GET['seq'] == 1) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Results at enrollment</h1>
                                        </div>
                                    <?php } else { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Echo Results </h1>
                                        </div>


                                    <?php } ?>


                                    <?php if ($_GET['seq'] == 1) { ?>

                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>ECG Date:</label>
                                                        <input class="validate[required,custom[date]]" type="text" name="ecg_date" id="ecg_date" value="<?php if ($results['ecg_date']) {
                                                                                                                                                            print_r($results['ecg_date']);
                                                                                                                                                        }  ?>" required />
                                                        <span>Example: 2023-01-01</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>ECG</label>
                                                        <select name="ecg" id="ecg" style="width: 100%;" required>
                                                            <option value="<?= $results['ecg'] ?>"><?php if ($results) {
                                                                                                        if ($results['ecg'] == 1) {
                                                                                                            echo 'Single lead or';
                                                                                                        } elseif ($results['ecg'] == 2) {
                                                                                                            echo '12 lead';
                                                                                                        } elseif ($results['ecg'] == 3) {
                                                                                                            echo 'Normal sinus rhythm';
                                                                                                        } elseif ($results['ecg'] == 4) {
                                                                                                            echo 'Atrial fibrillation';
                                                                                                        } elseif ($results['ecg'] == 5) {
                                                                                                            echo 'Other';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                            <option value="1">Single lead or</option>
                                                            <option value="2">12 lead</option>
                                                            <option value="3">Normal sinus rhythm</option>
                                                            <option value="4">Atrial fibrillation</option>
                                                            <option value="5">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-6" id="ecg_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify::</label>
                                                        <textarea name="ecg_other" rows="4">
                                                        <?php if ($results['ecg_other']) {
                                                            print_r($results['ecg_other']);
                                                        }  ?>
                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>


                                    <div class="row">

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Echo Date:</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="echo_date" id="echo_date" value="<?php if ($results['echo_date']) {
                                                                                                                                                            print_r($results['echo_date']);
                                                                                                                                                        }  ?>" required />
                                                    <span>Example: 2023-01-01</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Echo:(Normal)</label>
                                                    <select name="echo" id="echo" style="width: 100%;" required>
                                                        <option value="<?= $results['echo'] ?>"><?php if ($results) {
                                                                                                    if ($results['echo'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($results['echo'] == 2) {
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
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>LV severely depressed</label>
                                                    <select name="lv" id="lv" style="width: 100%;" required>
                                                        <option value="<?= $results['echo'] ?>"><?php if ($results) {
                                                                                                    if ($results['lv'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($results['lv'] == 2) {
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
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Mitral stenosis</label>
                                                    <select name="mitral" id="mitral" style="width: 100%;" required>
                                                        <option value="<?= $results['mitral'] ?>"><?php if ($results) {
                                                                                                        if ($results['lv'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($results['lv'] == 2) {
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
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>RV severely dilated</label>
                                                    <select name="rv" id="rv" style="width: 100%;" required>
                                                        <option value="<?= $results['rv'] ?>"><?php if ($results) {
                                                                                                    if ($results['rv'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($results['rv'] == 2) {
                                                                                                        echo 'No';
                                                                                                    } elseif ($results['rv'] == 3) {
                                                                                                        echo 'Unseen';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unseen</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Pericardial effusion</label>
                                                    <select name="pericardial" id="pericardial" style="width: 100%;" required>
                                                        <option value="<?= $results['pericardial'] ?>"><?php if ($results) {
                                                                                                            if ($results['pericardial'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($results['pericardial'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($results['pericardial'] == 3) {
                                                                                                                echo 'Unseen';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unseen</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>IVC dilated,collapse less than 50%</label>
                                                    <select name="ivc" id="ivc" style="width: 100%;" required>
                                                        <option value="<?= $results['ivc'] ?>"><?php if ($results) {
                                                                                                    if ($results['ivc'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($results['ivc'] == 2) {
                                                                                                        echo 'No';
                                                                                                    } elseif ($results['ivc'] == 3) {
                                                                                                        echo 'Unseen';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unseen</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Other Echo</label>
                                                    <select name="echo_other" id="echo_other1" style="width: 100%;" required>
                                                        <option value="<?= $results['echo_other'] ?>"><?php if ($results) {
                                                                                                            if ($results['echo_other'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($results['echo_other'] == 2) {
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
                                        </div>

                                    </div>


                                    <div class="row">

                                        <div class="col-sm-8">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Other specify</label>
                                                    <textarea name="echo_specify" rows="4">
                                                        <?php if ($results['echo_specify']) {
                                                            print_r($results['echo_specify']);
                                                        }  ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4" id="echo_other2">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Other</label>
                                                    <select name="echo_other2" style="width: 100%;" required>
                                                        <option value="<?= $results['echo_other2'] ?>"><?php if ($results) {
                                                                                                            if ($results['echo_other2'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($results['echo_other2'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($results['echo_other2'] == 3) {
                                                                                                                echo 'Unseen';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unseen</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="footer tar">
                                        <input type="submit" name="add_results" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 13) { ?>
                        <?php $hospitalization = $override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Hospitalizations , School and Management at Home</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hospitalization Date:</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="hospitalization_date" id="hospitalization_date" value="<?php if ($hospitalization['hospitalization_date']) {
                                                                                                                                                                                print_r($hospitalization['hospitalization_date']);
                                                                                                                                                                            }  ?>" required />
                                                    <span>Example: 2023-01-01</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Hospitalizations</h1>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Any recent hospitalizations not yet recorded?</label>
                                                    <select name="hospitalizations" id="hospitalizations" style="width: 100%;" required>
                                                        <option value="<?= $hospitalization['hospitalizations'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['hospitalizations'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($hospitalization['hospitalizations'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="ncd_hospitalizations">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>If yes, for NCD?</label>
                                                    <select name="ncd_hospitalizations" id="ncd_hospitalizations" style="width: 100%;">
                                                        <option value="<?= $hospitalization['ncd_hospitalizations'] ?>"><?php if ($hospitalization) {
                                                                                                                            if ($hospitalization['ncd_hospitalizations'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($hospitalization['ncd_hospitalizations'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Number of hospitalization from NCD in last 12 months</label>
                                                    <input type="text" name="hospitalization_number" id="hospitalization_number" value="<?php if ($hospitalization['hospitalization_number']) {
                                                                                                                                            print_r($hospitalization['hospitalization_number']);
                                                                                                                                        }  ?>" required />
                                                    <span>Record on hospitalization record</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>School</h1>
                                    </div>

                                    <div class="row">


                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Number of missed days of school in the last month?</label>
                                                    <input type="text" name="school_days" id="school_days" value="<?php if ($hospitalization['school_days']) {
                                                                                                                        print_r($hospitalization['school_days']);
                                                                                                                    }  ?>" required />
                                                    <span>N / A</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Transfusion</h1>
                                        </div>

                                        <div class="row">


                                            <div class="col-sm-12">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Number of Transfusion in the past month?</label>
                                                        <input type="text" name="transfusion" id="transfusion" value="<?php if ($hospitalization['transfusion']) {
                                                                                                                            print_r($hospitalization['transfusion']);
                                                                                                                        }  ?>" required />
                                                        <span>N / A</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>



                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Management at Home</h1>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>How many days of missed medications in past 7 days?</label>
                                                        <input type="text" name="missed_days" id="missed_days" value="<?php if ($hospitalization['missed_days']) {
                                                                                                                            print_r($hospitalization['missed_days']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6" id="ncd_hospitalizations">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Fluid restricted?</label>
                                                        <select name="fluid" id="fluid" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['fluid'] ?>"><?php if ($hospitalization) {
                                                                                                                    if ($hospitalization['fluid'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($hospitalization['fluid'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Home BG measurement in last week</label>
                                                        <input type="text" name="bg_measurement" id="bg_measurement" value="<?php if ($hospitalization['bg_measurement']) {
                                                                                                                                print_r($hospitalization['bg_measurement']);
                                                                                                                            }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Home BG result in last week</label>
                                                        <input type="text" name="bg_result180" id="bg_result180" value="<?php if ($hospitalization['bg_result180']) {
                                                                                                                            print_r($hospitalization['bg_result180']);
                                                                                                                        }  ?>" required />
                                                        <span> # > 180: </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Home BG result in last week</label>
                                                        <input type="text" name="bg_result70_180" id="bg_result70_180" value="<?php if ($hospitalization['bg_result70_180']) {
                                                                                                                                    print_r($hospitalization['bg_result70_180']);
                                                                                                                                }  ?>" required />
                                                        <span> # 70 - 180: </span>

                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Home BG result in last week</label>
                                                        <input type="text" name="bg_result70" id="bg_result70" value="<?php if ($hospitalization['bg_result70']) {
                                                                                                                            print_r($hospitalization['bg_result70']);
                                                                                                                        }  ?>" required />
                                                        <span> # < 70: </span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Number of insulin doses missed in last week</label>
                                                        <input type="text" name="basal" id="basal" value="<?php if ($hospitalization['basal']) {
                                                                                                                print_r($hospitalization['basal']);
                                                                                                            }  ?>" required />
                                                        <span> Basal: </span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Number of insulin doses missed in last week</label>
                                                        <input type="text" name="prandial" id="prandial" value="<?php if ($hospitalization['prandial']) {
                                                                                                                    print_r($hospitalization['prandial']);
                                                                                                                }  ?>" required />
                                                        <span> Prandial: </span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Insulin</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Basal insulin</label>
                                                        <input type="text" name="basal_nph" id="basal_nph" value="<?php if ($hospitalization['basal_nph']) {
                                                                                                                        print_r($hospitalization['basal_nph']);
                                                                                                                    }  ?>" required />
                                                        <span> NPH: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Basal insulin</label>
                                                        <input type="text" name="basal_analog" id="basal_analog" value="<?php if ($hospitalization['basal_analog']) {
                                                                                                                            print_r($hospitalization['basal_analog']);
                                                                                                                        }  ?>" required />
                                                        <span> Analog: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Basal insulin</label>
                                                        <input type="text" name="basal_am" id="basal_am" value="<?php if ($hospitalization['basal_am']) {
                                                                                                                    print_r($hospitalization['basal_am']);
                                                                                                                }  ?>" required />
                                                        <span> Units in am: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Basal insulin</label>
                                                        <input type="text" name="basal_pm" id="basal_pm" value="<?php if ($hospitalization['basal_pm']) {
                                                                                                                    print_r($hospitalization['basal_pm']);
                                                                                                                }  ?>" required />
                                                        <span> Units in pm: </span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Prandial insulin</label>
                                                        <input type="text" name="prandial_regular" id="prandial_regular" value="<?php if ($hospitalization['prandial_regular']) {
                                                                                                                                    print_r($hospitalization['prandial_regular']);
                                                                                                                                }  ?>" required />
                                                        <span> Regular: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Prandial insulin</label>
                                                        <input type="text" name="prandial_analog" id="prandial_analog" value="<?php if ($hospitalization['prandial_analog']) {
                                                                                                                                    print_r($hospitalization['prandial_analog']);
                                                                                                                                }  ?>" required />
                                                        <span> Analog: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Prandial insulin</label>
                                                        <input type="text" name="prandial_am" id="prandial_am" value="<?php if ($hospitalization['prandial_am']) {
                                                                                                                            print_r($hospitalization['prandial_am']);
                                                                                                                        }  ?>" required />
                                                        <span> Units in am: </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Prandial insulin</label>
                                                        <input type="text" name="prandial_lunch" id="prandial_lunch" value="<?php if ($hospitalization['prandial_lunch']) {
                                                                                                                                print_r($hospitalization['prandial_lunch']);
                                                                                                                            }  ?>" required />
                                                        <span> Units in lunch: </span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Prandial insulin</label>
                                                        <input type="text" name="prandial_pm" id="prandial_pm" value="<?php if ($hospitalization['prandial_pm']) {
                                                                                                                            print_r($hospitalization['prandial_pm']);
                                                                                                                        }  ?>" required />
                                                        <span> Units in pm: </span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Total daily insulin dose</label>
                                                        <input type="text" name="total_insulin_dose" id="total_insulin_dose" value="<?php if ($hospitalization['total_insulin_dose']) {
                                                                                                                                        print_r($hospitalization['total_insulin_dose']);
                                                                                                                                    }  ?>" required />
                                                        <span> ( Units ): </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Adjusts insulin dosing at home?</label>
                                                        <select name="home_insulin_dose" id="home_insulin_dose" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['home_insulin_dose'] ?>"><?php if ($hospitalization) {
                                                                                                                                if ($hospitalization['home_insulin_dose'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($hospitalization['home_insulin_dose'] == 2) {
                                                                                                                                    echo 'No';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Any issues at injection sites?</label>
                                                        <select name="issue_injection" id="issue_injection" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['issue_injection'] ?>"><?php if ($hospitalization) {
                                                                                                                            if ($hospitalization['issue_injection'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($hospitalization['issue_injection'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>If yes</label>
                                                        <select name="issue_injection_yes" id="issue_injection_yes" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['issue_injection_yes'] ?>"><?php if ($hospitalization) {
                                                                                                                                if ($hospitalization['issue_injection_yes'] == 1) {
                                                                                                                                    echo 'Infection';
                                                                                                                                } elseif ($hospitalization['issue_injection_yes'] == 2) {
                                                                                                                                    echo 'Lipo-hypertrophy';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Infection</option>
                                                            <option value="2">Lipo-hypertrophy</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Taking at home ( Malaria prophylaxis )?</label>
                                                        <select name="prophylaxis" id="prophylaxis" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['prophylaxis'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['prophylaxis'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['prophylaxis'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['prophylaxis'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                        </select>
                                                        <span>( Medication )</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Taking at home ( Insecticide treated bed net )?</label>
                                                        <select name="insecticide" id="insecticide" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['insecticide'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['insecticide'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['insecticide'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['insecticide'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                        </select>
                                                        <span>( Medication )</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Taking at home ( Folic acid )?</label>
                                                        <select name="folic_acid" id="folic_acid" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['folic_acid'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['folic_acid'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['folic_acid'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['folic_acid'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                        </select>
                                                        <span>( Medication )</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3" id="ncd_hospitalizations">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Taking at home ( Penicillin prophylaxis )?</label>
                                                        <select name="penicillin" id="penicillin" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['penicillin'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['penicillin'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['penicillin'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['penicillin'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                        </select>
                                                        <span>( Medication )</span>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pneumococcal vaccination up to date?</label>
                                                        <select name="pneumococcal" id="pneumococcal" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['pneumococcal'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['pneumococcal'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['pneumococcal'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['pneumococcal'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        } elseif ($hospitalization['pneumococcal'] == 4) {
                                                                                                                            echo 'Unsure';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                            <option value="4">Unsure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>On chronic opioid therapy?</label>
                                                        <select name="opioid" id="opioid" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['opioid'] ?>"><?php if ($hospitalization) {
                                                                                                                    if ($hospitalization['opioid'] == 1) {
                                                                                                                        echo 'Y';
                                                                                                                    } elseif ($hospitalization['opioid'] == 2) {
                                                                                                                        echo 'N';
                                                                                                                    } elseif ($hospitalization['opioid'] == 3) {
                                                                                                                        echo 'N / A';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="3">N / A</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Type</label>
                                                        <input type="text" name="opioid_type" id="opioid_type" value="<?php if ($hospitalization['opioid_type']) {
                                                                                                                            print_r($hospitalization['opioid_type']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Dose</label>
                                                        <input type="text" name="opioid_dose" id="opioid_dose" value="<?php if ($hospitalization['opioid_dose']) {
                                                                                                                            print_r($hospitalization['opioid_dose']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>On hydroxyurea?</label>
                                                        <select name="hydroxyurea" id="hydroxyurea" style="width: 100%;" required>
                                                            <option value="<?= $hospitalization['hydroxyurea'] ?>"><?php if ($hospitalization) {
                                                                                                                        if ($hospitalization['hydroxyurea'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($hospitalization['hydroxyurea'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        } elseif ($hospitalization['hydroxyurea'] == 3) {
                                                                                                                            echo 'N / A';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                            <option value="2">N / A</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Start Date</label>
                                                        <input type="text" name="hydroxyurea_date" id="hydroxyurea_date" value="<?php if ($hospitalization['hydroxyurea_date']) {
                                                                                                                                    print_r($hospitalization['hydroxyurea_date']);
                                                                                                                                }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Dose</label>
                                                        <input type="text" name="hydroxyurea_dose" id="hydroxyurea_dose" value="<?php if ($hospitalization['hydroxyurea_dose']) {
                                                                                                                                    print_r($hospitalization['hydroxyurea_dose']);
                                                                                                                                }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    <?php } ?>



                                    <div class="footer tar">
                                        <input type="submit" name="add_hospitalizaion" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 14) { ?>
                        <?php
                        $treatment_plan = $override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>TREATMMENT PLAN</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Date:</div>
                                        <div class="col-md-9">
                                            <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($treatment_plan['visit_date']) {
                                                                                                                                                    print_r($treatment_plan['visit_date']);
                                                                                                                                                }  ?>" required />
                                            <span>Example: 2023-01-01</span>
                                        </div>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Medications ( Cardiac )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>ASA:</label>
                                                        <input value="ASA" type="text" name="asa" id="asa" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_asa" id="action_asa" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_asa'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['action_asa'] == 1) {
                                                                                                                            echo 'Continue';
                                                                                                                        } elseif ($treatment_plan['action_asa'] == 2) {
                                                                                                                            echo 'Start';
                                                                                                                        } elseif ($treatment_plan['action_asa'] == 3) {
                                                                                                                            echo 'Stop';
                                                                                                                        } elseif ($treatment_plan['action_asa'] == 4) {
                                                                                                                            echo 'Not eligible';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_asa" id="dose_asa" value="<?php if ($treatment_plan['dose_asa']) {
                                                                                                                                                    print_r($treatment_plan['dose_asa']);
                                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Furosemide:</label>
                                                        <input value="Furosemide" type="text" name="furosemide" id="furosemide" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_furosemide" id="action_furosemide" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_furosemide'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['action_furosemide'] == 1) {
                                                                                                                                echo 'Continue';
                                                                                                                            } elseif ($treatment_plan['action_furosemide'] == 2) {
                                                                                                                                echo 'Start';
                                                                                                                            } elseif ($treatment_plan['action_furosemide'] == 3) {
                                                                                                                                echo 'Stop';
                                                                                                                            } elseif ($treatment_plan['action_furosemide'] == 4) {
                                                                                                                                echo 'Not eligible';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_furosemide" id="dose_furosemide" value="<?php if ($treatment_plan['dose_furosemide']) {
                                                                                                                                                                print_r($treatment_plan['dose_furosemide']);
                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>ACE-I:</label>
                                                        <input type="text" name="ace_i" id="ace_i" value="<?php if ($treatment_plan['ace_i']) {
                                                                                                                print_r($treatment_plan['ace_i']);
                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_ace_i" id="action_ace_i" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_ace_i'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['action_ace_i'] == 1) {
                                                                                                                            echo 'Continue';
                                                                                                                        } elseif ($treatment_plan['action_ace_i'] == 2) {
                                                                                                                            echo 'Start';
                                                                                                                        } elseif ($treatment_plan['action_ace_i'] == 3) {
                                                                                                                            echo 'Stop';
                                                                                                                        } elseif ($treatment_plan['action_ace_i'] == 4) {
                                                                                                                            echo 'Not eligible';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_ace_i" id="dose_ace_i" value="<?php if ($treatment_plan['dose_ace_i']) {
                                                                                                                                                        print_r($treatment_plan['dose_ace_i']);
                                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Beta blocker:</label>
                                                        <input type="text" name="beta_blocker" id="beta_blocker" value="<?php if ($treatment_plan['beta_blocker']) {
                                                                                                                            print_r($treatment_plan['beta_blocker']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_beta_blocker" id="action_beta_blocker" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_beta_blocker'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_beta_blocker'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_beta_blocker'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_beta_blocker'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_beta_blocker'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input type="number" name="dose_beta_blocker" id="dose_beta_blocker" value="<?php if ($treatment_plan['dose_beta_blocker']) {
                                                                                                                                        print_r($treatment_plan['dose_beta_blocker']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other anti-hypertensive:</label>
                                                        <input type="text" name="anti_hypertensive" id="anti_hypertensive" value="<?php if ($treatment_plan['anti_hypertensive']) {
                                                                                                                                        print_r($treatment_plan['anti_hypertensive']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_anti_hypertensive" id="action_anti_hypertensive" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_anti_hypertensive'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_anti_hypertensive'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['actiaction_anti_hypertensiveon_ace_i'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_anti_hypertensive'] == 3) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_anti_hypertensive'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_anti_hypertensive" id="dose_anti_hypertensive" value="<?php if ($treatment_plan['dose_anti_hypertensive']) {
                                                                                                                                                                                print_r($treatment_plan['dose_anti_hypertensive']);
                                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Benzathine PCN:</label>
                                                        <input value="Benzathine PCN:" type="text" name="benzathine" id="benzathine" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_benzathine" id="action_benzathine" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_benzathine'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['action_benzathine'] == 1) {
                                                                                                                                echo 'Continue';
                                                                                                                            } elseif ($treatment_plan['action_benzathine'] == 2) {
                                                                                                                                echo 'Start';
                                                                                                                            } elseif ($treatment_plan['action_benzathine'] == 3) {
                                                                                                                                echo 'Stop';
                                                                                                                            } elseif ($treatment_plan['action_benzathine'] == 4) {
                                                                                                                                echo 'Not eligible';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_benzathine" id="dose_benzathine" value="<?php if ($treatment_plan['dose_benzathine']) {
                                                                                                                                                                print_r($treatment_plan['dose_benzathine']);
                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Anticoagulation:</label>
                                                        <input type="text" name="anticoagulation" id="anticoagulation" value="<?php if ($treatment_plan['anticoagulation']) {
                                                                                                                                    print_r($treatment_plan['anticoagulation']);
                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_anticoagulation" id="action_anticoagulation" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_anticoagulation'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_anticoagulation'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['action_anticoagulation'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_anticoagulation'] == 3) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_anticoagulation'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_anticoagulation" id="dose_anticoagulation" value="<?php if ($treatment_plan['dose_anticoagulation']) {
                                                                                                                                                                            print_r($treatment_plan['dose_anticoagulation']);
                                                                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <input type="text" name="medication_other" id="medication_other" value="<?php if ($treatment_plan['medication_other']) {
                                                                                                                                    print_r($treatment_plan['medication_other']);
                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_medication_other" id="action_medication_other" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_medication_other'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_medication_other'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other'] == 3) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_medication_other" id="dose_medication_other" value="<?php if ($treatment_plan['dose_medication_other']) {
                                                                                                                                                                            print_r($treatment_plan['dose_medication_other']);
                                                                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Medications ( Diabetes )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Metformin:</label>
                                                        <input value="Metformin" type="text" name="metformin" id="metformin" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_metformin" id="action_metformin" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_metformin'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['action_metformin'] == 1) {
                                                                                                                                echo 'Continue';
                                                                                                                            } elseif ($treatment_plan['action_metformin'] == 2) {
                                                                                                                                echo 'Start';
                                                                                                                            } elseif ($treatment_plan['action_metformin'] == 3) {
                                                                                                                                echo 'Stop';
                                                                                                                            } elseif ($treatment_plan['action_metformin'] == 4) {
                                                                                                                                echo 'Not eligible';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_metformin" id="dose_metformin" value="<?php if ($treatment_plan['dose_metformin']) {
                                                                                                                                                                print_r($treatment_plan['dose_metformin']);
                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Glibenclamide:</label>
                                                        <input value="Glibenclamide" type="text" name="glibenclamide" id="glibenclamide" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_glibenclamide" id="action_glibenclamide" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_glibenclamide'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_glibenclamide'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_glibenclamide'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_glibenclamide'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_glibenclamide'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_glibenclamide" id="dose_glibenclamide" value="<?php if ($treatment_plan['dose_glibenclamide']) {
                                                                                                                                                                        print_r($treatment_plan['dose_glibenclamide']);
                                                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other oral anti-diabetic:</label>
                                                        <input type="text" name="anti_diabetic" id="anti_diabetic" value="<?php if ($treatment_plan['anti_diabetic']) {
                                                                                                                                print_r($treatment_plan['anti_diabetic']);
                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_anti_diabetic" id="action_anti_diabetic" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_anti_diabetic'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_anti_diabetic'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_anti_diabetic'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_anti_diabetic'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_anti_diabetic'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_anti_diabetic" id="dose_anti_diabetic" value="<?php if ($treatment_plan['dose_anti_diabetic']) {
                                                                                                                                                                        print_r($treatment_plan['dose_anti_diabetic']);
                                                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Aspirin:</label>
                                                        <input type="text" name="aspirin" id="aspirin" value="<?php if ($treatment_plan['aspirin']) {
                                                                                                                    print_r($treatment_plan['aspirin']);
                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_aspirin" id="action_aspirin" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_aspirin'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['action_aspirin'] == 1) {
                                                                                                                                echo 'Continue';
                                                                                                                            } elseif ($treatment_plan['action_aspirin'] == 2) {
                                                                                                                                echo 'Start';
                                                                                                                            } elseif ($treatment_plan['action_aspirin'] == 3) {
                                                                                                                                echo 'Stop';
                                                                                                                            } elseif ($treatment_plan['action_aspirin'] == 4) {
                                                                                                                                echo 'Not eligible';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input type="number" name="dose_aspirin" id="dose_aspirin" value="<?php if ($treatment_plan['dose_aspirin']) {
                                                                                                                                print_r($treatment_plan['dose_aspirin']);
                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Anti-hypertensive:</label>
                                                        <input type="text" name="anti_hypertensive2" id="anti_hypertensive2" value="<?php if ($treatment_plan['anti_hypertensive2']) {
                                                                                                                                        print_r($treatment_plan['anti_hypertensive2']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_anti_hypertensive2" id="action_anti_hypertensive2" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_anti_hypertensive2'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_anti_hypertensive2'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['action_anti_hypertensive2'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_anti_hypertensive2'] == 3) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_anti_hypertensive2'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_anti_hypertensive2" id="dose_anti_hypertensive2" value="<?php if ($treatment_plan['dose_anti_hypertensive2']) {
                                                                                                                                                                                print_r($treatment_plan['dose_anti_hypertensive2']);
                                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <input type="text" name="medication_other2" id="medication_other2" value="<?php if ($treatment_plan['medication_other2']) {
                                                                                                                                        print_r($treatment_plan['medication_other2']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_medication_other2" id="action_medication_other2" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_medication_other2'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_medication_other2'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other2'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other2'] == 4) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other2'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_medication_other2" id="dose_medication_other2" value="<?php if ($treatment_plan['dose_medication_other2']) {
                                                                                                                                                                                print_r($treatment_plan['dose_medication_other2']);
                                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Basal Insulin</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Changed?</label>
                                                        <select name="basal_changed" id="basal_changed" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['basal_changed'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['basal_changed'] == 1) {
                                                                                                                            echo 'Y';
                                                                                                                        } elseif ($treatment_plan['basal_changed'] == 2) {
                                                                                                                            echo 'N';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Units in am:</label>
                                                        <input type="text" name="basal_am2" id="basal_am2" value="<?php if ($treatment_plan['basal_am2']) {
                                                                                                                        print_r($treatment_plan['basal_am2']);
                                                                                                                    }  ?>" required />

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Units in pm:</label>
                                                        <input type="text" name="basal_pm2" id="basal_pm2" value="<?php if ($treatment_plan['basal_pm2']) {
                                                                                                                        print_r($treatment_plan['basal_pm2']);
                                                                                                                    }  ?>" required />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Prandial Insulin</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Changed?</label>
                                                        <select name="prandial_changed" id="prandial_changed" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['prandial_changed'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['prandial_changed'] == 1) {
                                                                                                                                echo 'Y';
                                                                                                                            } elseif ($treatment_plan['prandial_changed'] == 2) {
                                                                                                                                echo 'N';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Y</option>
                                                            <option value="2">N</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Units in am :</label>
                                                        <input type="text" name="prandial_am2" id="prandial_am2" value="<?php if ($treatment_plan['prandial_am2']) {
                                                                                                                            print_r($treatment_plan['prandial_am2']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Units at lunch :</label>
                                                        <input type="text" name="prandial_lunch2" id="prandial_lunch2" value="<?php if ($treatment_plan['prandial_lunch2']) {
                                                                                                                                    print_r($treatment_plan['prandial_lunch2']);
                                                                                                                                }  ?>" required />

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Units in pm :</label>
                                                        <input type="text" name="prandial_pm2" id="prandial_pm2" value="<?php if ($treatment_plan['prandial_pm2']) {
                                                                                                                            print_r($treatment_plan['prandial_pm2']);
                                                                                                                        }  ?>" required />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Medications ( Sickle Cell )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Malaria prophylaxis:</label>
                                                        <input value="Malaria prophylaxis:" type="text" name="prophylaxis2" id="prophylaxis2" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_prophylaxis2" id="action_prophylaxis2" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_prophylaxis2'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_prophylaxis2'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_prophylaxis2'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_prophylaxis2'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_prophylaxis2'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_prophylaxis2" id="dose_prophylaxis2" value="<?php if ($treatment_plan['dose_prophylaxis2']) {
                                                                                                                                                                    print_r($treatment_plan['dose_prophylaxis2']);
                                                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Folic Acid:</label>
                                                        <input value="Folic Acid" type="text" name="folic_acid2" id="folic_acid2" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_folic_acid2" id="action_folic_acid2" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_folic_acid2'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_folic_acid2'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_folic_acid2'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_folic_acid2'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_folic_acid2'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_folic_acid2" id="dose_folic_acid2" value="<?php if ($treatment_plan['dose_folic_acid2']) {
                                                                                                                                                                    print_r($treatment_plan['dose_folic_acid2']);
                                                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Penicillin:</label>
                                                        <input type="text" name="penicillin2" id="penicillin2" value="<?php if ($treatment_plan['penicillin2']) {
                                                                                                                            print_r($treatment_plan['penicillin2']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_penicillin2" id="action_penicillin2" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_penicillin2'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_penicillin2'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_penicillin2'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_penicillin2'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_penicillin2'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_penicillin2" id="dose_penicillin2" value="<?php if ($treatment_plan['dose_penicillin2']) {
                                                                                                                                                                    print_r($treatment_plan['dose_penicillin2']);
                                                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Pain control:</label>
                                                        <input type="text" name="pain_control" id="pain_control" value="<?php if ($treatment_plan['pain_control']) {
                                                                                                                            print_r($treatment_plan['pain_control']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_pain_control" id="action_pain_control" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_pain_control'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_pain_control'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_pain_control'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_pain_control'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_pain_control'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input type="number" name="dose_pain_control" id="dose_pain_control" value="<?php if ($treatment_plan['dose_pain_control']) {
                                                                                                                                        print_r($treatment_plan['dose_pain_control']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Hydroxyurea:</label>
                                                        <input type="text" name="hydroxyurea" id="hydroxyurea" value="<?php if ($treatment_plan['hydroxyurea']) {
                                                                                                                            print_r($treatment_plan['hydroxyurea']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_hydroxyurea" id="action_hydroxyurea" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_hydroxyurea'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['action_hydroxyurea'] == 1) {
                                                                                                                                    echo 'Continue';
                                                                                                                                } elseif ($treatment_plan['action_hydroxyurea'] == 2) {
                                                                                                                                    echo 'Start';
                                                                                                                                } elseif ($treatment_plan['action_hydroxyurea'] == 3) {
                                                                                                                                    echo 'Stop';
                                                                                                                                } elseif ($treatment_plan['action_hydroxyurea'] == 4) {
                                                                                                                                    echo 'Not eligible';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_hydroxyurea" id="dose_hydroxyurea" value="<?php if ($treatment_plan['dose_hydroxyurea']) {
                                                                                                                                                                    print_r($treatment_plan['dose_hydroxyurea']);
                                                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <input type="text" name="medication_other3" id="medication_other3" value="<?php if ($treatment_plan['medication_other3']) {
                                                                                                                                        print_r($treatment_plan['medication_other3']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Action:</label>
                                                        <select name="action_medication_other3" id="action_medication_other3" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['action_medication_other3'] ?>"><?php if ($treatment_plan) {
                                                                                                                                    if ($treatment_plan['action_medication_other3'] == 1) {
                                                                                                                                        echo 'Continue';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other3'] == 2) {
                                                                                                                                        echo 'Start';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other3'] == 3) {
                                                                                                                                        echo 'Stop';
                                                                                                                                    } elseif ($treatment_plan['action_medication_other3'] == 4) {
                                                                                                                                        echo 'Not eligible';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Continue</option>
                                                            <option value="2">Start</option>
                                                            <option value="3">Stop</option>
                                                            <option value="4">Not eligible</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>DOSE:</label>
                                                        <input class="validate[required]" type="number" name="dose_medication_other3" id="dose_medication_other3" value="<?php if ($treatment_plan['dose_medication_other3']) {
                                                                                                                                                                                print_r($treatment_plan['dose_medication_other3']);
                                                                                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php
                                    if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {
                                    ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Diet and Fluid restriction</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Salt:</label>
                                                        <input type="text" name="salt" id="salt" value="<?php if ($treatment_plan['salt']) {
                                                                                                            print_r($treatment_plan['salt']);
                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Fluid:</label>
                                                        <input type="text" name="fluid" id="fluid" value="<?php if ($treatment_plan['fluid']) {
                                                                                                                print_r($treatment_plan['fluid']);
                                                                                                            }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <input type="text" name="restriction_other" id="restriction_other" value="<?php if ($treatment_plan['restriction_other']) {
                                                                                                                                        print_r($treatment_plan['restriction_other']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                    ?>



                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Support</h1>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>
                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Vaccination</h1>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Vaccination needed?:</label>
                                                        <select name="vaccination" id="vaccination" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['vaccination'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['vaccination'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($treatment_plan['vaccination'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8" id="vaccination_specify">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Which::</label>
                                                        <input style="width: 100%;" type="text" name="vaccination_specify" value="<?php if ($treatment_plan['vaccination_specify']) {
                                                                                                                                        print_r($treatment_plan['vaccination_specify']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Transfusions</h1>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Transfusion needed today?</label>
                                                        <select name="transfusion_needed" id="transfusion_needed" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['transfusion_needed'] ?>"><?php if ($treatment_plan) {
                                                                                                                                if ($treatment_plan['transfusion_needed'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($treatment_plan['transfusion_needed'] == 2) {
                                                                                                                                    echo 'No';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8" id="transfusion_units">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label># units::</label>
                                                        <input style="width: 100%;" type="text" name="transfusion_units" value="<?php if ($treatment_plan['transfusion_units']) {
                                                                                                                                    print_r($treatment_plan['transfusion_units']);
                                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Family Education and counselling</h1>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Diet?:</label>
                                                        <select name="diet" id="diet" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['diet'] ?>"><?php if ($treatment_plan) {
                                                                                                                if ($treatment_plan['diet'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($treatment_plan['diet'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Hydration?:</label>
                                                        <select name="hydration" id="hydration" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['hydration'] ?>"><?php if ($treatment_plan) {
                                                                                                                    if ($treatment_plan['hydration'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($treatment_plan['hydration'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Acute Symptoms?:</label>
                                                        <select name="acute_symptoms" id="acute_symptoms" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['acute_symptoms'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['acute_symptoms'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($treatment_plan['acute_symptoms'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Fever ?:</label>
                                                        <select name="fever" id="fever" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['fever'] ?>"><?php if ($treatment_plan) {
                                                                                                                if ($treatment_plan['fever'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($treatment_plan['fever'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other ?:</label>
                                                        <select name="other_support" id="other_support" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['other_support'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['other_support'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($treatment_plan['other_support'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-9">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify:</label>
                                                        <input style="width: 100%;" type="text" name="support_specify" id="support_specify" value="<?php if ($treatment_plan['support_specify']) {
                                                                                                                                                        print_r($treatment_plan['support_specify']);
                                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Social support provided?:</label>

                                                    <select name="social_support" id="social_support" style="width: 100%;" required>
                                                        <option value="<?= $treatment_plan['social_support'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['social_support'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($treatment_plan['social_support'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Type:</label>
                                                    <input style="width: 100%;" type="text" name="social_support_type" id="social_support_type" value="<?php if ($treatment_plan['social_support_type']) {
                                                                                                                                                            print_r($treatment_plan['social_support_type']);
                                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Cardiology referral:</label>
                                                        <select name="cardiology" id="cardiology" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['cardiology'] ?>"><?php if ($treatment_plan) {
                                                                                                                        if ($treatment_plan['cardiology'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($treatment_plan['cardiology'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Completed ?:</label>
                                                        <select name="completed" id="completed" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['completed'] ?>"><?php if ($treatment_plan) {
                                                                                                                    if ($treatment_plan['completed'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($treatment_plan['completed'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>If no, why ?:</label>
                                                        <input style="width: 100%;" type="text" name="cardiology_reason" id="cardiology_reason" value="<?php if ($treatment_plan['cardiology_reason']) {
                                                                                                                                                            print_r($treatment_plan['cardiology_reason']);
                                                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Date Completed:</label>
                                                        <input style="width: 100%;" type="text" name="cardiology_date" id="cardiology_date" value="<?php if ($treatment_plan['cardiology_date']) {
                                                                                                                                                        print_r($treatment_plan['cardiology_date']);
                                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Awaiting surgery:</label>
                                                        <select name="awaiting_surgery" id="awaiting_surgery" style="width: 100%;" required>
                                                            <option value="<?= $treatment_plan['awaiting_surgery'] ?>"><?php if ($treatment_plan) {
                                                                                                                            if ($treatment_plan['awaiting_surgery'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($treatment_plan['awaiting_surgery'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Any new referrals provided?:</label>
                                                    <select name="new_referrals" id="new_referrals" style="width: 100%;" required>
                                                        <option value="<?= $treatment_plan['new_referrals'] ?>"><?php if ($treatment_plan) {
                                                                                                                    if ($treatment_plan['new_referrals'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($treatment_plan['new_referrals'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Type:</label>
                                                    <input style="width: 100%;" type="text" name="new_referrals_type" id="new_referrals_type" value="<?php if ($treatment_plan['new_referrals_type']) {
                                                                                                                                                            print_r($treatment_plan['new_referrals_type']);
                                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">


                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Notes:</label>
                                                    <textarea name="medication_notes" rows="4">
                                                        <?php if ($treatment_plan['medication_notes']) {
                                                            print_r($treatment_plan['medication_notes']);
                                                        }  ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="footer tar">
                                        <input type="submit" name="add_treatment_plan" value="Submit" class="btn btn-default">
                                    </div>
                                </form>
                            </div>
                        </div>


                    <?php } elseif ($_GET['id'] == 15) { ?>
                        <?php $dgns_complctns_comorbdts = $override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diagnosis, Complications, & Comorbidities</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date:</label>
                                                    <input style="width: 100%;" type="text" name="diagns_date" id="diagns_date" value="<?php if ($dgns_complctns_comorbdts['diagns_date']) {
                                                                                                                                            print_r($dgns_complctns_comorbdts['diagns_date']);
                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Has diagnosis been changed or specified?:</label>
                                                    <select name="diagns_changed" id="diagns_changed" style="width: 100%;" required>
                                                        <option value="<?= $dgns_complctns_comorbdts['diagns_changed'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                if ($dgns_complctns_comorbdts['diagns_changed'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_changed'] == 2) {
                                                                                                                                    echo 'No';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="ncd_diagns">
                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>If yes, what is the NCD diagnosis?:</label>
                                                        <select name="ncd_diagns" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['ncd_diagns'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                if ($dgns_complctns_comorbdts['ncd_diagns'] == 1) {
                                                                                                                                    echo 'Cardiomyopathy';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 2) {
                                                                                                                                    echo 'Rheumatic Heart Disease';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 3) {
                                                                                                                                    echo 'Severe / Uncontrolled Hypertension';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 4) {
                                                                                                                                    echo 'Hypertensive Heart Disease';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 5) {
                                                                                                                                    echo 'Congenital heart Disease';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 6) {
                                                                                                                                    echo 'Right Heart Failure';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 7) {
                                                                                                                                    echo 'Pericardial disease';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 8) {
                                                                                                                                    echo 'Coronary Artery Disease';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 9) {
                                                                                                                                    echo 'Arrhythmia';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 10) {
                                                                                                                                    echo 'Thromboembolic';
                                                                                                                                } elseif ($dgns_complctns_comorbdts['ncd_diagns'] == 11) {
                                                                                                                                    echo 'Stroke';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
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
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify diagnosis:</label>
                                                        <input type="text" name="ncd_diagns_specify" id="ncd_diagns_specify" value="<?php if ($dgns_complctns_comorbdts['ncd_diagns_specify']) {
                                                                                                                                        print_r($dgns_complctns_comorbdts['ncd_diagns_specify']);
                                                                                                                                    }  ?>" />
                                                        <span>(See intake form for options)</span>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>If yes, what is the NCD diagnosis?:</label>
                                                        <select name="ncd_diagns_diabetes" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['ncd_diagns_diabetes'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                        if ($dgns_complctns_comorbdts['ncd_diagns_diabetes'] == 1) {
                                                                                                                                            echo 'Type 1 DM';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_diabetes'] == 2) {
                                                                                                                                            echo 'Type 2 DM';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_diabetes'] == 3) {
                                                                                                                                            echo 'Gestational DM';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_diabetes'] == 4) {
                                                                                                                                            echo 'DM not yet specified';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_diabetes'] == 5) {
                                                                                                                                            echo 'Other';
                                                                                                                                        }
                                                                                                                                    } else {
                                                                                                                                        echo 'Select';
                                                                                                                                    } ?></option>
                                                            <option value="1">Type 1 DM</option>
                                                            <option value="2">Type 2 DM</option>
                                                            <option value="3">Gestational DM</option>
                                                            <option value="4">DM not yet specified</option>
                                                            <option value="5">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify diagnosis:</label>
                                                        <input type="text" name="ncd_diabetes_specify" id="ncd_diabetes_specify" value="<?php if ($dgns_complctns_comorbdts['ncd_diabetes_specify']) {
                                                                                                                                            print_r($dgns_complctns_comorbdts['ncd_diabetes_specify']);
                                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>


                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>If yes, what is the NCD diagnosis?:</label>
                                                        <select name="ncd_diagns_sickle" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['ncd_diagns_sickle'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                        if ($dgns_complctns_comorbdts['ncd_diagns_sickle'] == 1) {
                                                                                                                                            echo 'Sickle Cell Disease';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_sickle'] == 2) {
                                                                                                                                            echo 'Other hemoglobinopathy';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['ncd_diagns_sickle'] == 3) {
                                                                                                                                            echo 'Other';
                                                                                                                                        }
                                                                                                                                    } else {
                                                                                                                                        echo 'Select';
                                                                                                                                    } ?></option>
                                                            <option value="1">Sickle Cell Disease</option>
                                                            <option value="2">Other hemoglobinopathy</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify diagnosis:</label>
                                                        <input type="text" name="ncd_sickle_specify" id="ncd_sickle_specify" value="<?php if ($dgns_complctns_comorbdts['ncd_sickle_specify']) {
                                                                                                                                        print_r($dgns_complctns_comorbdts['ncd_sickle_specify']);
                                                                                                                                    }  ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>





                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>New complications:</label>
                                                        <select name="diagns_complication" id="diagns_complication" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['diagns_complication'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                        if ($dgns_complctns_comorbdts['diagns_complication'] == 1) {
                                                                                                                                            echo 'Hypertension';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['diagns_complication'] == 2) {
                                                                                                                                            echo 'Diabetes';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['diagns_complication'] == 3) {
                                                                                                                                            echo 'CKD';
                                                                                                                                        } elseif ($dgns_complctns_comorbdts['diagns_complication'] == 4) {
                                                                                                                                            echo 'Depression';
                                                                                                                                        }
                                                                                                                                    } else {
                                                                                                                                        echo 'Select';
                                                                                                                                    } ?></option>
                                                            <option value="1">Hypertension</option>
                                                            <option value="2">Diabetes</option>
                                                            <option value="3">CKD</option>
                                                            <option value="4">Depression</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>


                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>New complications:</label>
                                                        <select name="diagns_complication_diabets" id="diagns_complication_diabets" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['diagns_complication_diabets'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                                if ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 1) {
                                                                                                                                                    echo 'Cardiovascular';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 2) {
                                                                                                                                                    echo 'Neuropathy';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 3) {
                                                                                                                                                    echo 'Sexual dysfunction';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 4) {
                                                                                                                                                    echo 'Stroke / TIA';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 5) {
                                                                                                                                                    echo 'PVD';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 6) {
                                                                                                                                                    echo 'Retinopathy';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 7) {
                                                                                                                                                    echo 'Renal disease';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_diabets'] == 8) {
                                                                                                                                                    echo 'Other';
                                                                                                                                                }
                                                                                                                                            } else {
                                                                                                                                                echo 'Select';
                                                                                                                                            } ?></option>
                                                            <option value="1">Cardiovascular</option>
                                                            <option value="2">Neuropathy</option>
                                                            <option value="3">Sexual dysfunction</option>
                                                            <option value="4">Stroke / TIA</option>
                                                            <option value="5">PVD</option>
                                                            <option value="6">Retinopathy</option>
                                                            <option value="7">Renal disease</option>
                                                            <option value="8">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8" id="complication_diabets_specify">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify Other:</label>
                                                        <input type="text" name="complication_diabets_specify" value="<?php if ($dgns_complctns_comorbdts['complication_diabets_specify']) {
                                                                                                                            print_r($dgns_complctns_comorbdts['complication_diabets_specify']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>New complications:</label>
                                                        <select name="diagns_complication_sickle" id="diagns_complication_sickle" style="width: 100%;" required>
                                                            <option value="<?= $dgns_complctns_comorbdts['diagns_complication_sickle'] ?>"><?php if ($dgns_complctns_comorbdts) {
                                                                                                                                                if ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 1) {
                                                                                                                                                    echo 'Pain Event';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 2) {
                                                                                                                                                    echo 'Stroke';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 3) {
                                                                                                                                                    echo 'Pneumonia';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 4) {
                                                                                                                                                    echo 'Blood Transfusion';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 5) {
                                                                                                                                                    echo 'Acute chest syndrome';
                                                                                                                                                } elseif ($dgns_complctns_comorbdts['diagns_complication_sickle'] == 6) {
                                                                                                                                                    echo 'Other';
                                                                                                                                                }
                                                                                                                                            } else {
                                                                                                                                                echo 'Select';
                                                                                                                                            } ?></option>
                                                            <option value="1">Pain Event</option>
                                                            <option value="2">Stroke</option>
                                                            <option value="3">Pneumonia</option>
                                                            <option value="4">Blood Transfusion</option>
                                                            <option value="5">Acute chest syndrome</option>
                                                            <option value="6">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8" id="complication_sickle_specify">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify Other:</label>
                                                        <input type="text" name="complication_sickle_specify" value="<?php if ($dgns_complctns_comorbdts['complication_sickle_specify']) {
                                                                                                                            print_r($dgns_complctns_comorbdts['complication_sickle_specify']);
                                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div class="footer tar">
                                        <input type="submit" name="add_dgns_complctns_comorbdts" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 16) { ?>
                        <?php
                        $risks = $override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>RISK</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date:</label>
                                                    <input type="text" name="risk_date" id="risk_date" value="<?php if ($risks['risk_date']) {
                                                                                                                    print_r($risks['risk_date']);
                                                                                                                }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Tobacco:</label>
                                                    <select name="risk_tobacco" id="risk_tobacco" style="width: 100%;" required>
                                                        <option value="<?= $risks['risk_tobacco'] ?>"><?php if ($risks) {
                                                                                                            if ($risks['risk_tobacco'] == 1) {
                                                                                                                echo 'Yes, currently';
                                                                                                            } elseif ($risks['risk_tobacco'] == 2) {
                                                                                                                echo 'Yes, in the past';
                                                                                                            } elseif ($risks['risk_tobacco'] == 3) {
                                                                                                                echo 'never';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes, currently</option>
                                                        <option value="2">Yes, in the past</option>
                                                        <option value="3">never</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Alcohol:</label>
                                                    <select name="risk_alcohol" id="risk_alcohol" style="width: 100%;" required>
                                                        <option value="<?= $risks['risk_alcohol'] ?>"><?php if ($risks) {
                                                                                                            if ($risks['risk_alcohol'] == 1) {
                                                                                                                echo 'Yes, currently';
                                                                                                            } elseif ($risks['risk_alcohol'] == 2) {
                                                                                                                echo 'Yes, in the past';
                                                                                                            } elseif ($risks['risk_alcohol'] == 3) {
                                                                                                                echo 'never';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes, currently</option>
                                                        <option value="2">Yes, in the past</option>
                                                        <option value="3">never</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Employment status:</label>
                                                    <select name="risk_employment" id="risk_employment" style="width: 100%;" required>
                                                        <option value="<?= $risks['risk_employment'] ?>"><?php if ($risks) {
                                                                                                                if ($risks['risk_employment'] == 1) {
                                                                                                                    echo 'Employed';
                                                                                                                } elseif ($risks['risk_employment'] == 2) {
                                                                                                                    echo 'Self-employed';
                                                                                                                } elseif ($risks['risk_employment'] == 3) {
                                                                                                                    echo 'Unemployed';
                                                                                                                } elseif ($risks['risk_employment'] == 4) {
                                                                                                                    echo 'Leave of absence';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                        </option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self-employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="3">Leave of absence</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>NCD limiting school?:</label>
                                                    <select name="ncd_limiting" id="ncd_limiting" style="width: 100%;" required>
                                                        <option value="<?= $risks['ncd_limiting'] ?>"><?php if ($risks) {
                                                                                                            if ($risks['ncd_limiting'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($risks['ncd_limiting'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($risks['ncd_limiting'] == 3) {
                                                                                                                echo 'N/A';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">N/A</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Socioeconomic question?:</label>
                                                    <select name="social_economic" id="social_economic" style="width: 100%;" required>
                                                        <option value="<?= $risks['social_economic'] ?>"><?php if ($risks) {
                                                                                                                if ($risks['social_economic'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($risks['social_economic'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($risks['social_economic'] == 3) {
                                                                                                                    echo 'N/A';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">N/A</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Last HIv test</h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date Test:</label>
                                                    <input type="text" name="risk_hiv_date" id="risk_hiv_date" value="<?php if ($risks['risk_hiv_date']) {
                                                                                                                            print_r($risks['risk_hiv_date']);
                                                                                                                        }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>HIV:</label>
                                                    <select name="risk_hiv" id="risk_hiv" style="width: 100%;" required>
                                                        <option value="<?= $risks['risk_hiv'] ?>"><?php if ($risks) {
                                                                                                        if ($risks['risk_hiv'] == 1) {
                                                                                                            echo 'R';
                                                                                                        } elseif ($risks['risk_hiv'] == 2) {
                                                                                                            echo 'NR';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                        </option>
                                                        <option value="1">R</option>
                                                        <option value="2">RN</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>ART start date:</label>
                                                    <input type="text" name="risk_art_date" value="<?php if ($risks['risk_art_date']) {
                                                                                                        print_r($risks['risk_art_date']);
                                                                                                    }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Last TB screening</h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date Screened:</label>
                                                    <input type="text" name="risk_tb_date" id="risk_tb_date" value="<?php if ($risks['risk_tb_date']) {
                                                                                                                        print_r($risks['risk_tb_date']);
                                                                                                                    }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>TB:</label>
                                                    <select name="risk_tb" id="risk_tb" style="width: 100%;" required>
                                                        <option value="<?= $risks['risk_tb'] ?>"><?php if ($risks) {
                                                                                                        if ($risks['risk_tb'] == 1) {
                                                                                                            echo 'Positive : Smear / Xpert / Other';
                                                                                                        } elseif ($risks['risk_tb'] == 2) {
                                                                                                            echo 'Negative : Smear / Xpert / Other';
                                                                                                        } elseif ($risks['risk_tb'] == 3) {
                                                                                                            echo 'EPTB';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                        </option>
                                                        <option value="1">Positive : Smear / Xpert / Other</option>
                                                        <option value="2">Negative : Smear / Xpert / Other</option>
                                                        <option value="3">EPTB</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_risks" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 17) { ?>
                        <?php
                        $hospitalization_details = $override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Hospitalizazions Details</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Last HIv test</h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hospitalizazions date:</label>
                                                    <input type="text" name="hospitalization_date" id="hospitalization_date" value="<?php if ($hospitalization_details['hospitalization_date']) {
                                                                                                                                        print_r($hospitalization_details['hospitalization_date']);
                                                                                                                                    }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hospitalized in the last year for this NCD?:</label>
                                                    <select name="hospitalization_ncd" id="hospitalization_ncd" style="width: 100%;" required>
                                                        <option value="<?= $hospitalization_details['hospitalization_ncd'] ?>"><?php if ($hospitalization_details) {
                                                                                                                                    if ($hospitalization_details['hospitalization_ncd'] == 1) {
                                                                                                                                        echo 'Yes';
                                                                                                                                    } elseif ($hospitalization_details['hospitalization_ncd'] == 2) {
                                                                                                                                        echo 'No';
                                                                                                                                    } elseif ($hospitalization_details['hospitalization_ncd'] == 3) {
                                                                                                                                        echo 'Unknown';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                        <option value="3">Unknown</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="hospitalization_year">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If yes , Number of hospitalizations in past year:</label>
                                                    <input type="text" name="hospitalization_year" value="<?php if ($hospitalization_details['hospitalization_year']) {
                                                                                                                print_r($hospitalization_details['hospitalization_year']);
                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If yes , Number of hospital days in past year:</label>
                                                    <input type="text" name="hospitalization_day" value="<?php if ($hospitalization_details['hospitalization_day']) {
                                                                                                                print_r($hospitalization_details['hospitalization_day']);
                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Reason for admission:</label>
                                                    <input type="text" name="admission_reason" id="admission_reason" value="<?php if ($hospitalization_details['admission_reason']) {
                                                                                                                                print_r($hospitalization_details['admission_reason']);
                                                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Discharge Diagnosisr:</label>
                                                    <input type="text" name="discharge_diagnosis" id="discharge_diagnosis" value="<?php if ($hospitalization_details['discharge_diagnosis']) {
                                                                                                                                        print_r($hospitalization_details['discharge_diagnosis']);
                                                                                                                                    }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_hospitalization_details" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 18) { ?>
                        <?php
                        $lab_details = $override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Lab and Clinical Monitoring</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date:</label>
                                                    <input type="text" name="lab_date" id="lab_date" value="<?php if ($lab_details['lab_date']) {
                                                                                                                print_r($lab_details['lab_date']);
                                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>NCD coping ?:</label>
                                                    <select name="ncd_coping" id="ncd_coping" style="width: 100%;" required>
                                                        <option value="<?= $lab_details['ncd_coping'] ?>"><?php if ($lab_details) {
                                                                                                                if ($lab_details['ncd_coping'] == 1) {
                                                                                                                    echo 'Well';
                                                                                                                } elseif ($lab_details['ncd_coping'] == 2) {
                                                                                                                    echo 'Some problems';
                                                                                                                } elseif ($lab_details['ncd_coping'] == 3) {
                                                                                                                    echo 'Poor';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                        </option>
                                                        <option value="1">Well</option>
                                                        <option value="2">Some problems</option>
                                                        <option value="3">Poor</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Family planning ?:</label>
                                                    <select name="family_planning" id="family_planning" style="width: 100%;" required>
                                                        <option value="<?= $lab_details['family_planning'] ?>"><?php if ($lab_details) {
                                                                                                                    if ($lab_details['family_planning'] == 1) {
                                                                                                                        echo 'Not eligible';
                                                                                                                    } elseif ($lab_details['family_planning'] == 2) {
                                                                                                                        echo 'Not interested';
                                                                                                                    } elseif ($lab_details['family_planning'] == 3) {
                                                                                                                        echo 'Currently using';
                                                                                                                    } elseif ($lab_details['family_planning'] == 3) {
                                                                                                                        echo 'referred';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                        </option>
                                                        <option value="1">Not eligible</option>
                                                        <option value="2">Not interested</option>
                                                        <option value="3">Currently using</option>
                                                        <option value="3">referred</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Cardiac</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Na:</label>
                                                        <input type="text" name="na" id="na" value="<?php if ($lab_details['na']) {
                                                                                                        print_r($lab_details['na']);
                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>K:</label>
                                                        <input type="text" name="k" id="k" value="<?php if ($lab_details['k']) {
                                                                                                        print_r($lab_details['k']);
                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>BUN:</label>
                                                        <input type="text" name="bun" id="bun" value="<?php if ($lab_details['bun']) {
                                                                                                            print_r($lab_details['bun']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Cre:</label>
                                                        <input type="text" name="cre" id="cre" value="<?php if ($lab_details['cre']) {
                                                                                                            print_r($lab_details['cre']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>BNP:</label>
                                                        <input type="text" name="bnp" id="bnp" value="<?php if ($lab_details['bnp']) {
                                                                                                            print_r($lab_details['bnp']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>INR:</label>
                                                        <input type="text" name="inr" id="inr" value="<?php if ($lab_details['inr']) {
                                                                                                            print_r($lab_details['inr']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other:</label>
                                                        <select name="lab_Other" id="lab_Other" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['lab_Other'] ?>"><?php if ($lab_details) {
                                                                                                                    if ($lab_details['lab_Other'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($lab_details['lab_Other'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other Specify:</label>
                                                        <input type="text" name="lab_specify" id="lab_specify" value="<?php if ($lab_details['lab_specify']) {
                                                                                                                            print_r($lab_details['lab_specify']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>ECG:</label>
                                                        <select name="lab_ecg" id="lab_ecg" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['lab_ecg'] ?>"><?php if ($lab_details) {
                                                                                                                if ($lab_details['lab_ecg'] == 1) {
                                                                                                                    echo 'NSR';
                                                                                                                } elseif ($lab_details['lab_ecg'] == 2) {
                                                                                                                    echo 'Other';
                                                                                                                } elseif ($lab_details['lab_ecg'] == 3) {
                                                                                                                    echo 'Afib';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">NSR</option>
                                                            <option value="2">Other</option>
                                                            <option value="3">Afib</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3" id="lab_ecg_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify:</label>
                                                        <input type="text" name="lab_ecg_other" value="<?php if ($lab_details['lab_ecg_other']) {
                                                                                                            print_r($lab_details['lab_ecg_other']);
                                                                                                        }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Cardiac surgery / intervention?:</label>
                                                        <select name="cardiac_surgery" id="cardiac_surgery" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['cardiac_surgery'] ?>"><?php if ($lab_details) {
                                                                                                                        if ($lab_details['cardiac_surgery'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($lab_details['cardiac_surgery'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Type:</label>
                                                        <input type="text" name="cardiac_surgery_type" value="<?php if ($lab_details['cardiac_surgery_type']) {
                                                                                                                    print_r($lab_details['cardiac_surgery_type']);
                                                                                                                }  ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Diabetes</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label># episodes DKA in last yr:</label>
                                                        <input type="text" name="dka_number" id="dka_number" value="<?php if ($lab_details['dka_number']) {
                                                                                                                        print_r($lab_details['dka_number']);
                                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Eyes examined:</label>
                                                        <select name="eyes_examined" id="eyes_examined" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['eyes_examined'] ?>"><?php if ($lab_details) {
                                                                                                                        if ($lab_details['eyes_examined'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($lab_details['eyes_examined'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4" id="cataracts">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Cataracts ?:</label>
                                                        <select name="cataracts" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['cataracts'] ?>"><?php if ($lab_details) {
                                                                                                                    if ($lab_details['cataracts'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($lab_details['cataracts'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Retinopathy screening:</label>
                                                        <select name="retinopathy_screening" id="retinopathy_screening" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['retinopathy_screening'] ?>"><?php if ($lab_details) {
                                                                                                                                if ($lab_details['retinopathy_screening'] == 1) {
                                                                                                                                    echo 'Unknown';
                                                                                                                                } elseif ($lab_details['retinopathy_screening'] == 2) {
                                                                                                                                    echo 'None';
                                                                                                                                } elseif ($lab_details['retinopathy_screening'] == 3) {
                                                                                                                                    echo 'Background';
                                                                                                                                } elseif ($lab_details['retinopathy_screening'] == 4) {
                                                                                                                                    echo 'Profilerative';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                            </option>
                                                            <option value="1">Unknown</option>
                                                            <option value="2">None</option>
                                                            <option value="3">Background</option>
                                                            <option value="4">Profilerative</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Foot exam:</label>
                                                        <select name="foot_exam_diabetes" id="foot_exam_diabetes" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['foot_exam_diabetes'] ?>"><?php if ($lab_details) {
                                                                                                                            if ($lab_details['foot_exam_diabetes'] == 1) {
                                                                                                                                echo 'Normal';
                                                                                                                            } elseif ($lab_details['foot_exam_diabetes'] == 2) {
                                                                                                                                echo 'Abnormal';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Normal</option>
                                                            <option value="2">Abnormal</option>
                                                        </select>
                                                        <span>Light touch or monofilament</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Na:</label>
                                                        <input type="text" name="na_diabetes" id="na_diabetes" value="<?php if ($lab_details['na_diabetes']) {
                                                                                                                            print_r($lab_details['na_diabetes']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>K:</label>
                                                        <input type="text" name="k_diabetes" id="k_diabetes" value="<?php if ($lab_details['k_diabetes']) {
                                                                                                                        print_r($lab_details['k_diabetes']);
                                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Cre:</label>
                                                        <input type="text" name="cre_diabetes" id="cre_diabetes" value="<?php if ($lab_details['cre_diabetes']) {
                                                                                                                            print_r($lab_details['cre_diabetes']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Proteinuria:</label>
                                                        <input type="text" name="proteinuria" id="proteinuria" value="<?php if ($lab_details['proteinuria']) {
                                                                                                                            print_r($lab_details['proteinuria']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Lipid panel:</label>
                                                        <input type="text" name="lipid_panel" id="lipid_panel" value="<?php if ($lab_details['lipid_panel']) {
                                                                                                                            print_r($lab_details['lipid_panel']);
                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other ?:</label>
                                                        <select name="other_lab_diabetes" id="other_lab_diabetes" style="width: 100%;" required>
                                                            <option value="<?= $lab_details['other_lab_diabetes'] ?>"><?php if ($lab_details) {
                                                                                                                            if ($lab_details['other_lab_diabetes'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($lab_details['other_lab_diabetes'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?>
                                                            </option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8" id="specify_lab_diabetes">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify:</label>
                                                        <input type="text" name="specify_lab_diabetes" value="<?php if ($lab_details['specify_lab_diabetes']) {
                                                                                                                    print_r($lab_details['specify_lab_diabetes']);
                                                                                                                }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Sickle Cell</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label># Transfusion in last 12 months</label>
                                                        <input type="text" name="lab_transfusion_sickle" id="lab_transfusion_sickle" value="<?php if ($lab_details['na']) {
                                                                                                                                                print_r($lab_details['lab_transfusion_sickle']);
                                                                                                                                            }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label># Transcranial Doppler</label>
                                                        <input type="text" name="transcranial_doppler" id="transcranial_doppler" value="<?php if ($lab_details['transcranial_doppler']) {
                                                                                                                                            print_r($lab_details['transcranial_doppler']);
                                                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">


                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>WBC:</label>
                                                        <input type="text" name="wbc" id="wbc" value="<?php if ($lab_details['wbc']) {
                                                                                                            print_r($lab_details['wbc']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Hb:</label>
                                                        <input type="text" name="hb" id="hb" value="<?php if ($lab_details['hb']) {
                                                                                                        print_r($lab_details['hb']);
                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>MCV:</label>
                                                        <input type="text" name="mcv" id="mcv" value="<?php if ($lab_details['mcv']) {
                                                                                                            print_r($lab_details['mcv']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">


                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Plt:</label>
                                                        <input type="text" name="plt" id="plt" value="<?php if ($lab_details['plt']) {
                                                                                                            print_r($lab_details['plt']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Fe studies:</label>
                                                        <input type="text" name="fe_studies" id="fe_studies" value="<?php if ($lab_details['fe_studies']) {
                                                                                                                        print_r($lab_details['fe_studies']);
                                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>LFTs:</label>
                                                        <input type="text" name="lfts" id="lfts" value="<?php if ($lab_details['lfts']) {
                                                                                                            print_r($lab_details['lfts']);
                                                                                                        }  ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div class="footer tar">
                                        <input type="submit" name="add_lab_details" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 19) { ?>
                        <?php
                        $main_diagnosis = $override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diagnosis Category</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Date</div>
                                        <div class="col-md-9">
                                            <input type="text" name="diagnosis_date" id="diagnosis_date" value="<?php if ($main_diagnosis['visit_date']) {
                                                                                                                    print_r($main_diagnosis['visit_date']);
                                                                                                                }  ?>" required />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Cardiac</div>
                                        <div class="col-md-9">
                                            <select name="cardiac" id="cardiac" style="width: 100%;" required>
                                                <option value="<?= $main_diagnosis['cardiac'] ?>"><?php if ($main_diagnosis) {
                                                                                                        if ($main_diagnosis['cardiac'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($main_diagnosis['cardiac'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Diabetes</div>
                                        <div class="col-md-9">
                                            <select name="diabetes" style="width: 100%;" required>
                                                <option value="<?= $main_diagnosis['diabetes'] ?>"><?php if ($main_diagnosis) {
                                                                                                        if ($main_diagnosis['diabetes'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($main_diagnosis['diabetes'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Patient for Sickle cell</div>
                                        <div class="col-md-9">
                                            <select name="sickle_cell" style="width: 100%;" required>
                                                <option value="<?= $main_diagnosis['sickle_cell'] ?>"><?php if ($main_diagnosis) {
                                                                                                            if ($main_diagnosis['sickle_cell'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($main_diagnosis['sickle_cell'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Reamrks / Comments:</div>
                                        <div class="col-md-9">
                                            <textarea name="comments" id="comments" cols="30" rows="10">
                                            <?php if ($main_diagnosis['comments']) {
                                                print_r($main_diagnosis['comments']);
                                            }  ?>
                                            </textarea>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_main_diagnosis" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 20) { ?>
                        <?php
                        $social_economic = $override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Socioeconomic Status ( EXIT-TB SOCIAL ECONOMIC TOOL (2018)) </h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>date:</label>
                                                    <input type="text" name="social_economic_date" id="social_economic_date" value="<?php if ($social_economic['social_economic_date']) {
                                                                                                                                        print_r($social_economic['social_economic_date']);
                                                                                                                                    }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Distance to clinic:</label>
                                                    <input type="text" name="distance_km" id="distance_km" value="<?php if ($social_economic['distance_km']) {
                                                                                                                        print_r($social_economic['distance_km']);
                                                                                                                    }  ?>" required />
                                                    <span>( km )</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Distance to clinic:</label>
                                                    <input type="text" name="distance_minutes" id="distance_minutes" value="<?php if ($social_economic['distance_minutes']) {
                                                                                                                                print_r($social_economic['distance_minutes']);
                                                                                                                            }  ?>" required />
                                                    <span>( minutes )</span>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Mode of transportation to clinic:</label>
                                                    <select name="transport_mode" id="transport_mode" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['transport_mode'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['transport_mode'] == 1) {
                                                                                                                            echo 'Walk';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 2) {
                                                                                                                            echo 'Taxi';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 3) {
                                                                                                                            echo 'Bodaboda Motorcycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 4) {
                                                                                                                            echo 'Bodaboda Bicycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 5) {
                                                                                                                            echo 'My own car';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 6) {
                                                                                                                            echo 'My own bicycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 7) {
                                                                                                                            echo 'Commuter bus/Daladala';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 8) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Walk</option>
                                                        <option value="2">Taxi</option>
                                                        <option value="3">Bodaboda Motorcycle</option>
                                                        <option value="4">Bodaboda Bicycle</option>
                                                        <option value="5">My own car</option>
                                                        <option value="6">My own bicycle</option>
                                                        <option value="7">Commuter bus/Daladala</option>
                                                        <option value="8">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="transport_mode_other" id="transport_mode_other" value="<?php if ($social_economic['transport_mode_other']) {
                                                                                                                                        print_r($social_economic['transport_mode_other']);
                                                                                                                                    }  ?>" required />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cost of transportation to clinic (round trip)::</label>
                                                    <input type="text" name="transportation_cost" id="transportation_cost" value="<?php if ($social_economic['transportation_cost']) {
                                                                                                                                        print_r($social_economic['transportation_cost']);
                                                                                                                                    }  ?>" required />
                                                    <span>( TSHS )</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Who is the head of your household?:</label>
                                                    <select name="household_head" id="household_head" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['household_head'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['household_head'] == 1) {
                                                                                                                            echo 'Yourself';
                                                                                                                        } elseif ($social_economic['household_head'] == 2) {
                                                                                                                            echo 'Your spouse/partner';
                                                                                                                        } elseif ($social_economic['household_head'] == 3) {
                                                                                                                            echo 'Your father or mother';
                                                                                                                        } elseif ($social_economic['household_head'] == 4) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yourself</option>
                                                        <option value="2">Your spouse/partner</option>
                                                        <option value="3">Your father or mother</option>
                                                        <option value="4">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="household_head_other" id="household_head_other" value="<?php if ($social_economic['household_head_other']) {
                                                                                                                                        print_r($social_economic['household_head_other']);
                                                                                                                                    }  ?>" required />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How old is your head of household?</label>
                                                    <input type="text" name="household_years" id="household_years" value="<?php if ($social_economic['household_years']) {
                                                                                                                                print_r($social_economic['household_years']);
                                                                                                                            }  ?>" required />
                                                    <span>( Age in Years )</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How many people are in your household?</label>
                                                    <input type="text" name="household_people" id="household_head_other" value="<?php if ($social_economic['household_head_other']) {
                                                                                                                                    print_r($social_economic['household_head_other']);
                                                                                                                                }  ?>" required />
                                                    <span>( ENTER NUMBERS )</span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How do you rate your wealth in comparison to others?:</label>
                                                    <select name="wealth_rate" id="wealth_rate" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['wealth_rate'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['wealth_rate'] == 1) {
                                                                                                                        echo 'Among most wealthy';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 2) {
                                                                                                                        echo 'Above average';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 3) {
                                                                                                                        echo 'Average wealth';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 4) {
                                                                                                                        echo 'Below average';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 5) {
                                                                                                                        echo 'Among least wealthy';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Among most wealthy</option>
                                                        <option value="2">Above average</option>
                                                        <option value="3">Average wealth</option>
                                                        <option value="4">Below average</option>
                                                        <option value="5">Among least wealthy</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What is the main occupation of the person
                                                        who contributes most for your regular
                                                        expenditure?:</label>
                                                    <select name="contributer_occupation" id="contributer_occupation" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['contributer_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['contributer_occupation'] == 1) {
                                                                                                                                    echo 'Employed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 2) {
                                                                                                                                    echo 'Self employed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 3) {
                                                                                                                                    echo 'Unemployed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 4) {
                                                                                                                                    echo 'Farmer';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 5) {
                                                                                                                                    echo 'Fisher';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 6) {
                                                                                                                                    echo 'Student';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 7) {
                                                                                                                                    echo 'Housewife';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 8) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="8">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="contributer_occupation_other" rows="4">
                                                        <?php if ($social_economic['contributer_occupation_other']) {
                                                            print_r($social_economic['contributer_occupation_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What type of work do you do for your main occupation?:</label>
                                                    <select name="main_occupation" id="main_occupation" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['main_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['main_occupation'] == 1) {
                                                                                                                            echo 'Employed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 2) {
                                                                                                                            echo 'Self employed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 3) {
                                                                                                                            echo 'Unemployed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 4) {
                                                                                                                            echo 'Farmer';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 5) {
                                                                                                                            echo 'Fisher';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 6) {
                                                                                                                            echo 'Student';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 7) {
                                                                                                                            echo 'Housewife';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 8) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="8">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="main_occupation_other" rows="4">
                                                        <?php if ($social_economic['main_occupation_other']) {
                                                            print_r($social_economic['main_occupation_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What is your main income based on?:</label>
                                                    <select name="main_icome_based" id="main_icome_based" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['main_icome_based'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['main_icome_based'] == 1) {
                                                                                                                            echo 'Monthly salary';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 2) {
                                                                                                                            echo 'Daily wage';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 3) {
                                                                                                                            echo 'Business/firm earnings';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 4) {
                                                                                                                            echo 'Sale of farm produce';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 5) {
                                                                                                                            echo 'Have no income';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 6) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Monthly salary</option>
                                                        <option value="2">Daily wage</option>
                                                        <option value="3">Business/firm earnings</option>
                                                        <option value="4">Sale of farm produce</option>
                                                        <option value="5">Have no income</option>
                                                        <option value="6">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="main_icome_other" rows="4">
                                                        <?php if ($social_economic['main_icome_other']) {
                                                            print_r($social_economic['main_icome_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Altogether, how much did you earn from
                                                        all your different sources of income in the
                                                        last month?</label>
                                                    <input type="text" name="earn_individual" id="earn_individual" value="<?php if ($social_economic['earn_individual']) {
                                                                                                                                print_r($social_economic['earn_individual']);
                                                                                                                            }  ?>" required />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Altogether how much did others in your
                                                        household (including the household head)
                                                        earn in the last month?</label>
                                                    <input type="text" name="earn_household" id="earn_household" value="<?php if ($social_economic['earn_household']) {
                                                                                                                            print_r($social_economic['earn_household']);
                                                                                                                        }  ?>" required />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What was the main form of transport that
                                                        you used to get here today?</label>
                                                    <input type="text" name="main_transport" id="main_transport" value="<?php if ($social_economic['main_transport']) {
                                                                                                                            print_r($social_economic['main_transport']);
                                                                                                                        }  ?>" required />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>About how long did it take you to arrive here from home?</label>
                                                    <input type="text" name="time_from_home" id="time_from_home" value="<?php if ($social_economic['time_from_home']) {
                                                                                                                            print_r($social_economic['time_from_home']);
                                                                                                                        }  ?>" required />
                                                    <span>Amount in hours (e.g 0.5, 2.25 etc)</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did you leave young children (aged less
                                                        than about 6 years) at home to come here
                                                        today?</label>
                                                    <select name="leave_children" id="leave_children" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['leave_children'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['leave_children'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['leave_children'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label> If yes, who is looking after them?
                                                    </label>
                                                    <select name="looking_children" id="looking_children" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['looking_children'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['looking_children'] == 1) {
                                                                                                                            echo 'Adult relatives/Household members';
                                                                                                                        } elseif ($social_economic['looking_children'] == 2) {
                                                                                                                            echo 'Other older children';
                                                                                                                        } elseif ($social_economic['looking_children'] == 3) {
                                                                                                                            echo 'Neighbour';
                                                                                                                        } elseif ($social_economic['looking_children'] == 4) {
                                                                                                                            echo 'Maid';
                                                                                                                        } elseif ($social_economic['looking_children'] == 5) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Adult relatives/Household members</option>
                                                        <option value="2">Other older children</option>
                                                        <option value="3">Neighbouro</option>
                                                        <option value="4">Maid</option>
                                                        <option value="5">Other</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify?</label>
                                                    <textarea name="looking_children_other" rows="4">
                                                        <?php if ($social_economic['looking_children_other']) {
                                                            print_r($social_economic['looking_children_other']);
                                                        }  ?>
                                                        </textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What type of work does the person
                                                        looking after the children do for their main
                                                        occupation??:</label>
                                                    <select name="occupation_looking_child" id="occupation_looking_child" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['occupation_looking_child'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['occupation_looking_child'] == 1) {
                                                                                                                                    echo 'Employed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 2) {
                                                                                                                                    echo 'Self employed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 3) {
                                                                                                                                    echo 'Unemployed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 4) {
                                                                                                                                    echo 'Farmer';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 5) {
                                                                                                                                    echo 'Fisher';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 6) {
                                                                                                                                    echo 'Student';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 7) {
                                                                                                                                    echo 'Housewife';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 8) {
                                                                                                                                    echo 'Other';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 9) {
                                                                                                                                    echo 'Don’t know';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="8">Other</option>
                                                        <option value="9">Don’t know</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="occupation_looking_child_other" rows="4">
                                                        <?php if ($social_economic['occupation_looking_child_other']) {
                                                            print_r($social_economic['occupation_looking_child_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did anyone accompany you today?</label>
                                                    <select name="accompany" id="accompany" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['accompany'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['accompany'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['accompany'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What is the main occupation of the person
                                                        you came with today?:</label>
                                                    <select name="accompany_occupation" id="accompany_occupation" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['accompany_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['accompany_occupation'] == 1) {
                                                                                                                                echo 'Employed';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 2) {
                                                                                                                                echo 'Self employed';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 3) {
                                                                                                                                echo 'Unemployed';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 4) {
                                                                                                                                echo 'Farmer';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 5) {
                                                                                                                                echo 'Fisher';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 6) {
                                                                                                                                echo 'Student';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 7) {
                                                                                                                                echo 'Housewife';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 8) {
                                                                                                                                echo 'Other';
                                                                                                                            } elseif ($social_economic['accompany_occupation'] == 9) {
                                                                                                                                echo 'Don’t know';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="8">Other</option>
                                                        <option value="9">Don’t know</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="accompany_occupation_other" rows="4">
                                                        <?php if ($social_economic['accompany_occupation_other']) {
                                                            print_r($social_economic['accompany_occupation_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How much was spent on transport for the
                                                        person to accompany you today?</label>
                                                    <input type="text" name="accompany_transport" id="accompany_transport" value="<?php if ($social_economic['accompany_transport']) {
                                                                                                                                        print_r($social_economic['accompany_transport']);
                                                                                                                                    }  ?>" required />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What other expenses have they made to
                                                        accompany you today? (for example food,
                                                        child care)</label>
                                                    <input type="text" name="accompany_expenses" id="accompany_expenses" value="<?php if ($social_economic['accompany_expenses']) {
                                                                                                                                    print_r($social_economic['accompany_expenses']);
                                                                                                                                }  ?>" required />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>In the last month, on how many days were
                                                        your normal activities disrupted through
                                                        illness?</label>
                                                    <input type="text" name="activities_disrupted" id="activities_disrupted" value="<?php if ($social_economic['activities_disrupted']) {
                                                                                                                                        print_r($social_economic['activities_disrupted']);
                                                                                                                                    }  ?>" required />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>DHS Questions </h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main material of the floor::</label>
                                                    <select name="material_floor" id="material_floor" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['material_floor'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['material_floor'] == 1) {
                                                                                                                            echo 'Earth/ sand/dung ';
                                                                                                                        } elseif ($social_economic['material_floor'] == 2) {
                                                                                                                            echo 'Concrete cement';
                                                                                                                        } elseif ($social_economic['material_floor'] == 3) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Earth/ sand/dung</option>
                                                        <option value="2">Concrete cement</option>
                                                        <option value="3">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="material_floor_other" rows="4">
                                                        <?php if ($social_economic['material_floor_other']) {
                                                            print_r($social_economic['material_floor_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main material of the Roof:</label>
                                                    <select name="material_roof" id="material_roof" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['material_roof'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['material_roof'] == 1) {
                                                                                                                            echo 'Thatch/ palm ';
                                                                                                                        } elseif ($social_economic['material_roof'] == 2) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Thatch/ palm</option>
                                                        <option value="2">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="material_roof_other" rows="4">
                                                        <?php if ($social_economic['material_roof_other']) {
                                                            print_r($social_economic['material_roof_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main type of cooking fuel:</label>
                                                    <select name="cooking_fuel" id="cooking_fuel" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['cooking_fuel'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['cooking_fuel'] == 1) {
                                                                                                                        echo 'Electricity ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 2) {
                                                                                                                        echo 'LPG/ natural gas/ biogas';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 3) {
                                                                                                                        echo 'Kerosene ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 4) {
                                                                                                                        echo 'coal/lignite/ charcoal';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 5) {
                                                                                                                        echo 'wood/ straw/shrub/grass/agricultural crop animal dung ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 6) {
                                                                                                                        echo 'no food cooked';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 7) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Electricity</option>
                                                        <option value="2">LPG/ natural gas/ biogas</option>
                                                        <option value="3">Kerosene </option>
                                                        <option value="4">coal/lignite/ charcoal</option>
                                                        <option value="5">wood/ straw/shrub/grass/agricultural crop animal dung</option>
                                                        <option value="6">no food cooked</option>
                                                        <option value="7">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="cooking_fuel_other" rows="4">
                                                        <?php if ($social_economic['cooking_fuel_other']) {
                                                            print_r($social_economic['cooking_fuel_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Water Access:?</label>
                                                    <select name="water_access" id="water_access" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['water_access'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['water_access'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['water_access'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Drinking water source:?</label>
                                                    <select name="water_source" id="water_source" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['water_source'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['water_source'] == 1) {
                                                                                                                        echo 'Piped into dwelling';
                                                                                                                    } elseif ($social_economic['water_source'] == 2) {
                                                                                                                        echo 'Piped to neighbor ';
                                                                                                                    } elseif ($social_economic['water_source'] == 3) {
                                                                                                                        echo 'Tube well or borehole';
                                                                                                                    } elseif ($social_economic['water_source'] == 4) {
                                                                                                                        echo 'Unprotected well';
                                                                                                                    } elseif ($social_economic['water_source'] == 5) {
                                                                                                                        echo 'Unprotected spring';
                                                                                                                    } elseif ($social_economic['water_source'] == 6) {
                                                                                                                        echo 'Surface water';
                                                                                                                    } elseif ($social_economic['water_source'] == 7) {
                                                                                                                        echo 'Piped to yard/plot';
                                                                                                                    } elseif ($social_economic['water_source'] == 8) {
                                                                                                                        echo 'Public tap or standpipe ';
                                                                                                                    } elseif ($social_economic['water_source'] == 9) {
                                                                                                                        echo 'Protected well';
                                                                                                                    } elseif ($social_economic['water_source'] == 10) {
                                                                                                                        echo 'Protected spring';
                                                                                                                    } elseif ($social_economic['water_source'] == 11) {
                                                                                                                        echo 'Rainwater';
                                                                                                                    } elseif ($social_economic['water_source'] == 96) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Piped into dwelling</option>
                                                        <option value="2">Piped to neighbor</option>
                                                        <option value="3">Tube well or borehole</option>
                                                        <option value="4">Unprotected well </option>
                                                        <option value="5">Unprotected spring</option>
                                                        <option value="6">Surface water</option>
                                                        <option value="7">Piped to yard/plot</option>
                                                        <option value="8">Public tap or standpipe</option>
                                                        <option value="9">Protected well</option>
                                                        <option value="10">Protected spring</option>
                                                        <option value="11">Rainwater</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="water_source_other" rows="4">
                                                        <?php if ($social_economic['water_source_other']) {
                                                            print_r($social_economic['water_source_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Toilet Access:?</label>
                                                    <select name="toilet_access" id="toilet_access" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['toilet_access'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['toilet_access'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['toilet_access'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Type of Toilet facility?</label>
                                                    <select name="toilet_facility" id="toilet_facility" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['toilet_facility'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['toilet_facility'] == 1) {
                                                                                                                            echo 'Flush to pit latrine';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 2) {
                                                                                                                            echo 'Flush to somewhere else';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 3) {
                                                                                                                            echo 'Flush, don’t know where';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 4) {
                                                                                                                            echo 'Ventilated improved pit latrine';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 5) {
                                                                                                                            echo 'Pit latrine with slab';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 6) {
                                                                                                                            echo 'Pit latrine without slab/open pit';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 7) {
                                                                                                                            echo 'Composting toilet ';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 8) {
                                                                                                                            echo 'Hanging toilet';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 9) {
                                                                                                                            echo 'Bucket toilet';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 10) {
                                                                                                                            echo 'No facilities';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Flush to pit latrine</option>
                                                        <option value="2">Flush to somewhere else</option>
                                                        <option value="3">Flush, don’t know where</option>
                                                        <option value="4">Ventilated improved pit latrine</option>
                                                        <option value="5">Pit latrine with slab</option>
                                                        <option value="6">Pit latrine without slab/open pit</option>
                                                        <option value="7">Composting toilet </option>
                                                        <option value="8">Hanging toilet</option>
                                                        <option value="9">Bucket toilet</option>
                                                        <option value="10">No facilities</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="toilet_access_other" rows="4">
                                                        <?php if ($social_economic['toilet_access_other']) {
                                                            print_r($social_economic['toilet_access_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Does your household have </h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Television?</label>
                                                    <select name="television" id="television" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['television'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['television'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['television'] == 2) {
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
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Refrigerator?</label>
                                                    <select name="refrigerator" id="refrigerator" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['refrigerator'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['refrigerator'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['refrigerator'] == 2) {
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
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Sofa ?</label>
                                                    <select name="sofa" id="sofa" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['sofa'] ?>"><?php if ($social_economic) {
                                                                                                            if ($social_economic['sofa'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($social_economic['sofa'] == 2) {
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
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Clock:?</label>
                                                    <select name="clock" id="clock" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['clock'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['clock'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($social_economic['clock'] == 2) {
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
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Fan ?</label>
                                                    <select name="fan" id="fan" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['fan'] ?>"><?php if ($social_economic) {
                                                                                                            if ($social_economic['fan'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($social_economic['fan'] == 2) {
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
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>VCR/DVD?</label>
                                                    <select name="vcr_dvd" id="vcr_dvd" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['vcr_dvd'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['vcr_dvd'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($social_economic['vcr_dvd'] == 2) {
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
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Does any member of this household have a bank account? </label>
                                                    <select name="bank_account" id="bank_account" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['bank_account'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['bank_account'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['bank_account'] == 2) {
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
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Was there ever no food at all in your household because there were not enough resources to get more?</label>
                                                    <select name="no_food" id="no_food" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['no_food'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['no_food'] == 1) {
                                                                                                                    echo 'never, rarely (once or twice)';
                                                                                                                } elseif ($social_economic['no_food'] == 2) {
                                                                                                                    echo 'sometimes (3-10 times)';
                                                                                                                } elseif ($social_economic['no_food'] == 3) {
                                                                                                                    echo 'often (>10 times)';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did you or any household member go to sleep at night hungry because there was not enough food?</label>
                                                    <select name="sleep_hungry" id="sleep_hungry" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['sleep_hungry'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['sleep_hungry'] == 1) {
                                                                                                                        echo 'never, rarely (once or twice)';
                                                                                                                    } elseif ($social_economic['sleep_hungry'] == 2) {
                                                                                                                        echo 'sometimes (3-10 times)';
                                                                                                                    } elseif ($social_economic['sleep_hungry'] == 3) {
                                                                                                                        echo 'often (>10 times)';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- s8elect -->
                                                <div class="form-group">
                                                    <label>Did you or any household member go a whole day without eating anything because there was not enough food?</label>
                                                    <select name="day_hungry" id="day_hungry" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['day_hungry'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['day_hungry'] == 1) {
                                                                                                                        echo 'never, rarely (once or twice)';
                                                                                                                    } elseif ($social_economic['day_hungry'] == 2) {
                                                                                                                        echo 'sometimes (3-10 times)';
                                                                                                                    } elseif ($social_economic['day_hungry'] == 3) {
                                                                                                                        echo 'often (>10 times)';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Notes on socioeconomic situation & needs: </label>
                                                    <textarea name="socioeconomic_notes" rows="4">
                                                        <?php if ($social_economic['socioeconomic_notes']) {
                                                            print_r($social_economic['socioeconomic_notes']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="footer tar">
                                        <input type="submit" name="add_social_economic" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>


                    <?php } elseif ($_GET['id'] == 21) { ?>
                        <?php
                        $diabetic = $override->get3('diabetic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
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
                                            <select name="diagnosis" style="width: 100%;" required>
                                                <option value="<?= $diabetic['diagnosis'] ?>"><?php if ($diabetic) {
                                                                                                    if ($diabetic['diagnosis'] == 1) {
                                                                                                        echo 'Type 1 DM';
                                                                                                    } elseif ($diabetic['diagnosis'] == 2) {
                                                                                                        echo 'Type 2 DM';
                                                                                                    } elseif ($diabetic['diagnosis'] == 2) {
                                                                                                        echo 'Gestational DM';
                                                                                                    } elseif ($diabetic['diagnosis'] == 2) {
                                                                                                        echo 'DM not yet specified';
                                                                                                    } elseif ($diabetic['diagnosis'] == 2) {
                                                                                                        echo 'Other';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                </option>
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
                                        <div class="col-md-9">
                                            <input class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" value="<?php if ($diabetic['visit_date']) {
                                                                                                                                                            print_r($diabetic['visit_date']);
                                                                                                                                                        }  ?>" required />
                                            <span>Example: 2023-01-01</span>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Hypertension:</div>
                                        <div class="col-md-9">
                                            <select name="hypertension" style="width: 100%;" required>
                                                <option value="<?= $diabetic['hypertension'] ?>"><?php if ($diabetic) {
                                                                                                        if ($diabetic['hypertension'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($diabetic['hypertension'] == 2) {
                                                                                                            echo 'No';
                                                                                                        } elseif ($diabetic['hypertension'] == 2) {
                                                                                                            echo 'Unknown';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Hypertension Date:</div>
                                        <div class="col-md-9">
                                            <input class="validate[required,custom[date]]" type="text" name="hypertension_date" id="hypertension_date" value="<?php if ($diabetic['hypertension_date']) {
                                                                                                                                                                    print_r($diabetic['hypertension_date']);
                                                                                                                                                                }  ?>" required />
                                            <span>Example: 2023-01-01</span>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Presentation with any of the following?</div>
                                        <div class="col-md-9">
                                            <select name="symptoms" style="width: 100%;" required>
                                                <option value="<?= $diabetic['symptoms'] ?>"><?php if ($diabetic) {
                                                                                                    if ($diabetic['symptoms'] == 1) {
                                                                                                        echo 'DKA with coma';
                                                                                                    } elseif ($diabetic['symptoms'] == 2) {
                                                                                                        echo 'DKA without coma';
                                                                                                    } elseif ($diabetic['symptoms'] == 3) {
                                                                                                        echo 'Ketosis';
                                                                                                    } elseif ($diabetic['symptoms'] == 4) {
                                                                                                        echo 'Hyperglycemia';
                                                                                                    } elseif ($diabetic['symptoms'] == 5) {
                                                                                                        echo 'By Screening';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                </option>
                                                <option value="1">DKA with coma</option>
                                                <option value="2">DKA without coma</option>
                                                <option value="3">Ketosis</option>
                                                <option value="4">Hyperglycemia</option>
                                                <option value="5">By Screening</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Cardiovascular Disease </div>
                                        <div class="col-md-9">
                                            <select name="cardiovascular" style="width: 100%;" required>
                                                <option value="<?= $diabetic['cardiovascular'] ?>"><?php if ($diabetic) {
                                                                                                        if ($diabetic['cardiovascular'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($diabetic['cardiovascular'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Retinopathy</div>
                                        <div class="col-md-9">
                                            <select name="retinopathy" style="width: 100%;" required>
                                                <option value="<?= $diabetic['retinopathy'] ?>"><?php if ($diabetic) {
                                                                                                    if ($diabetic['retinopathy'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($diabetic['retinopathy'] == 2) {
                                                                                                        echo 'No';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Renal disease (e.g. elevated creatinine)</div>
                                        <div class="col-md-9">
                                            <select name="renal_disease" style="width: 100%;" required>
                                                <option value="<?= $diabetic['renal_disease'] ?>"><?php if ($diabetic) {
                                                                                                        if ($diabetic['renal_disease'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($diabetic['renal_disease'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Stroke/TIA</div>
                                        <div class="col-md-9">
                                            <select name="stroke" style="width: 100%;" required>
                                                <option value="<?= $diabetic['stroke'] ?>"><?php if ($diabetic) {
                                                                                                if ($diabetic['stroke'] == 1) {
                                                                                                    echo 'Yes';
                                                                                                } elseif ($diabetic['stroke'] == 2) {
                                                                                                    echo 'No';
                                                                                                }
                                                                                            } else {
                                                                                                echo 'Select';
                                                                                            } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">PVD (e.g. ulcers, gangrene)</div>
                                        <div class="col-md-9">
                                            <select name="pvd" style="width: 100%;" required>
                                                <option value="<?= $diabetic['pvd'] ?>"><?php if ($diabetic) {
                                                                                            if ($diabetic['pvd'] == 1) {
                                                                                                echo 'Yes';
                                                                                            } elseif ($diabetic['pvd'] == 2) {
                                                                                                echo 'No';
                                                                                            }
                                                                                        } else {
                                                                                            echo 'Select';
                                                                                        } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Neuropathy</div>
                                        <div class="col-md-9">
                                            <select name="neuropathy" style="width: 100%;" required>
                                                <option value="<?= $diabetic['neuropathy'] ?>"><?php if ($diabetic) {
                                                                                                    if ($diabetic['neuropathy'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($diabetic['neuropathy'] == 2) {
                                                                                                        echo 'No';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Sexual dysfunction</div>
                                        <div class="col-md-9">
                                            <select name="sexual_dysfunction" style="width: 100%;" required>
                                                <option value="<?= $diabetic['sexual_dysfunction'] ?>"><?php if ($diabetic) {
                                                                                                            if ($diabetic['sexual_dysfunction'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($diabetic['sexual_dysfunction'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9">
                                            <textarea name="comments" rows="4">
                                            <?php if ($diabetic['comments']) {
                                                print_r($diabetic['comments']);
                                            }  ?>
                                            </textarea>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_diabetic" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 22) { ?>
                        <?php
                        $sickle_cell = $override->get3('sickle_cell', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        ?>
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
                                                <select name="diagnosis" style="width: 100%;" required>
                                                    <option value="<?= $sickle_cell['diagnosis'] ?>"><?php if ($sickle_cell) {
                                                                                                            if ($sickle_cell['diagnosis'] == 1) {
                                                                                                                echo 'Sickle Cell Disease';
                                                                                                            } elseif ($sickle_cell['diagnosis'] == 2) {
                                                                                                                echo 'Other Hemoglobinopathy';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                    </option>
                                                    <option value="1">Sickle Cell Disease</option>
                                                    <option value="2">Other Hemoglobinopathy</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Diagnosis Date:</div>
                                            <div class="col-md-9"><input class="validate[required,custom[date]]" type="text" name="diagnosis_date" id="diagnosis_date" value="<?php if ($sickle_cell['visit_date']) {
                                                                                                                                                                                    print_r($sickle_cell['visit_date']);
                                                                                                                                                                                }  ?>" required />
                                                <span>Example: 2023-01-01</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">Family History of SCD?:</div>
                                        <div class="col-md-9">
                                            <select name="history_scd" style="width: 100%;" required>
                                                <option value="<?= $sickle_cell['history_scd'] ?>"><?php if ($sickle_cell) {
                                                                                                        if ($sickle_cell['history_scd'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($sickle_cell['history_scd'] == 2) {
                                                                                                            echo 'No';
                                                                                                        } elseif ($sickle_cell['history_scd'] == 3) {
                                                                                                            echo 'Unknown';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">SCD Test Result?</div>
                                        <div class="col-md-9">

                                            <select name="scd_test" style="width: 100%;" required>
                                                <option value="<?= $sickle_cell['scd_test'] ?>"><?php if ($sickle_cell) {
                                                                                                    if ($sickle_cell['scd_test'] == 1) {
                                                                                                        echo 'Presumptive Diagnosis';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 2) {
                                                                                                        echo 'Sickling Test';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 3) {
                                                                                                        echo 'SS';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 4) {
                                                                                                        echo 'SA';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 5) {
                                                                                                        echo 'SBThal';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 6) {
                                                                                                        echo 'SC';
                                                                                                    } elseif ($sickle_cell['scd_test'] == 7) {
                                                                                                        echo 'Other';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                </option>
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
                                        <div class="col-md-3">SCD Other Specify:</div>
                                        <div class="col-md-9"><textarea name="scd_test_other" rows="4">
                                        <?php if ($sickle_cell['scd_test_other']) {
                                            print_r($sickle_cell['scd_test_other']);
                                        }  ?>
                                        </textarea> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Confirmatory Test </div>
                                        <div class="col-md-9">
                                            <select name="confirmatory_test" style="width: 100%;" required>
                                                <option value="<?= $sickle_cell['confirmatory_test'] ?>"><?php if ($sickle_cell) {
                                                                                                                if ($sickle_cell['confirmatory_test'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($sickle_cell['confirmatory_test'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                </option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Type of Confirmatory Test</div>
                                        <div class="col-md-9">
                                            <select name="confirmatory_test_type" style="width: 100%;" required>
                                                <option value="<?= $sickle_cell['confirmatory_test_type'] ?>"><?php if ($sickle_cell) {
                                                                                                                    if ($sickle_cell['confirmatory_test_type'] == 1) {
                                                                                                                        echo 'HPLC';
                                                                                                                    } elseif ($sickle_cell['confirmatory_test_type'] == 2) {
                                                                                                                        echo 'HBE';
                                                                                                                    } elseif ($sickle_cell['confirmatory_test_type'] == 3) {
                                                                                                                        echo 'IEF';
                                                                                                                    } elseif ($sickle_cell['confirmatory_test_type'] == 4) {
                                                                                                                        echo 'Basique';
                                                                                                                    } elseif ($sickle_cell['confirmatory_test_type'] == 5) {
                                                                                                                        echo 'Acide';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                </option>
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
                                                <option value="<?= $sickle_cell['vaccine_history'] ?>"><?php if ($sickle_cell) {
                                                                                                            if ($sickle_cell['vaccine_history'] == 1) {
                                                                                                                echo 'Pneumococcal';
                                                                                                            } elseif ($sickle_cell['vaccine_history'] == 2) {
                                                                                                                echo 'Meningococcal';
                                                                                                            } elseif ($sickle_cell['vaccine_history'] == 3) {
                                                                                                                echo 'Haemophilus Influenza type B (Hib)';
                                                                                                            } elseif ($sickle_cell['vaccine_history'] == 4) {
                                                                                                                echo 'Unknown';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                </option>
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
                                                <option value="<?= $sickle_cell['blood_group'] ?>"><?php if ($sickle_cell) {
                                                                                                        if ($sickle_cell['blood_group'] == 1) {
                                                                                                            echo 'A+';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 2) {
                                                                                                            echo 'A-';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 3) {
                                                                                                            echo 'B+';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 4) {
                                                                                                            echo 'B-';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 5) {
                                                                                                            echo 'O+';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 6) {
                                                                                                            echo 'O-';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 7) {
                                                                                                            echo 'AB+';
                                                                                                        } elseif ($sickle_cell['blood_group'] == 8) {
                                                                                                            echo 'AB-';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?>
                                                </option>
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
                                        <div class="col-md-9"><textarea name="comments" rows="4">
                                        <?php if ($sickle_cell['comments']) {
                                            print_r($sickle_cell['comments']);
                                        }  ?>
                                        </textarea> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_social_economic" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>



                    <?php } elseif ($_GET['id'] == 26) { ?>
                        <?php
                        $medications = $override->get('medications', 'status', 1)[0];
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Medications</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Medication Name:</label>
                                                    <input type="text" name="name" value="<?php if ($medications['name']) {
                                                                                                print_r($medications['name']);
                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">





                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cardiac:</label>
                                                    <select name="cardiac" style="width: 100%;" required>
                                                        <option value="<?= $medications['cardiac'] ?>"><?php if ($medications) {
                                                                                                            if ($medications['cardiac'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($medications['cardiac'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Diabetes:</label>
                                                    <select name="diabetes" style="width: 100%;" required>
                                                        <option value="<?= $medications['diabetes'] ?>"><?php if ($medications) {
                                                                                                            if ($medications['diabetes'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($medications['diabetes'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Sickle Cell:</label>
                                                    <select name="sickle_cell" style="width: 100%;" required>
                                                        <option value="<?= $medications['sickle_cell'] ?>"><?php if ($medications) {
                                                                                                                if ($medications['sickle_cell'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($medications['sickle_cell'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?>
                                                        </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="footer tar">
                                        <input type="submit" name="add_position" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

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

        $('#weight, #height').on('input', function() {
            setTimeout(function() {
                var weight = $('#weight').val();
                var height = $('#height').val() / 100; // Convert cm to m
                var bmi = weight / (height * height);
                $('#bmi').text(bmi.toFixed(2));
            }, 1);
        });

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


        $('#hypertension_medicatn_name').hide();
        $('#hypertension_medicatn').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#hypertension_medicatn_name').show();
            } else {
                $('#hypertension_medicatn_name').hide();
            }
        });


        $('#list_exposure').hide();
        $('#occupation').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#list_exposure').show();
            } else {
                $('#list_exposure').hide();
            }
        });

        if ($('#referred').val() === "7") {
            $('#referred_other').show();
            $('#referred').change(function() {
                var getUid = $(this).val();
                if (getUid === "7") {
                    $('#referred_other').show();
                } else {
                    $('#referred_other').hide();
                }
            });
        } else {
            $('#referred_other').hide();
            $('#referred').change(function() {
                var getUid = $(this).val();
                if (getUid === "7") {
                    $('#referred_other').show();
                }
            });
        }


        if ($('#hiv').val() === "1") {
            $('#art_date').show();
            $('#hiv').change(function() {
                var getUid = $(this).val();
                if (getUid === "1") {
                    $('#art_date').show();
                } else {
                    $('#art_date').hide();
                }
            });
        } else {
            $('#art_date').hide();
            $('#hiv').change(function() {
                var getUid = $(this).val();
                if (getUid === "1") {
                    $('#art_date').show();
                }
            });
        }

        if ($('#tb').val() != "4") {
            $('#tb_year').show();
            $('#tb').change(function() {
                var getUid = $(this).val();
                if (getUid != "4") {
                    $('#tb_year').show();
                } else {
                    $('#tb_year').hide();
                }
            });
        } else {
            $('#tb_year').hide();
            $('#tb').change(function() {
                var getUid = $(this).val();
                if (getUid != "4") {
                    $('#tb_year').show();
                }
            });
        }

        if ($('#smoking').val() === "1") {
            $('#active_smoker').show();
            $('#packs').show();
            $('#smoking').change(function() {
                var getUid = $(this).val();
                if (getUid === "1") {
                    $('#active_smoker').show();
                    $('#packs').show();
                } else {
                    $('#active_smoker').hide();
                    $('#packs').hide();
                }
            });
        } else {
            $('#active_smoker').hide();
            $('#packs').hide();
            $('#smoking').change(function() {
                var getUid = $(this).val();
                if (getUid === "1") {
                    $('#active_smoker').show();
                    $('#packs').show();
                }
            });
        }

        if ($('#alcohol').val() != "3") {
            $('#quantity').show();
            $('#alcohol').change(function() {
                var getUid = $(this).val();
                if (getUid != "3") {
                    $('#quantity').show();
                } else {
                    $('#quantity').hide();
                }
            });
        } else {
            $('#quantity').hide();
            $('#alcohol').change(function() {
                var getUid = $(this).val();
                if (getUid != "3") {
                    $('#quantity').show();
                }
            });
        }

        function handleSelectChange(selectElement) {
            var selectedValue = selectElement.value;

            if (selectedValue === '96') {
                var input = prompt('Please enter your option:');

                // Perform any necessary validation or processing on the entered value
                // ...

                // Create a new option with the entered value
                var newOption = document.createElement('option');
                newOption.value = input;
                newOption.text = input;

                // Add the new option to the select element
                selectElement.appendChild(newOption);

                // Set the new option as the selected option
                selectElement.value = input;
            }
        }

        if ($('#lungs').val() === "5") {
            $('#lungs_Other').show();
            $('#lungs').change(function() {
                var getUid = $(this).val();
                if (getUid === "5") {
                    $('#lungs_Other').show();
                } else {
                    $('#lungs_Other').hide();
                }
            });
        } else {
            $('#lungs_Other').hide();
            $('#lungs').change(function() {
                var getUid = $(this).val();
                if (getUid === "5") {
                    $('#lungs_Other').show();
                }
            });
        }


        $('#cardiac').change(function() {
            $('#cardiomyopathy').hide();
            $('#heumatic').hide();
            $('#Congenital').hide();
            $('#Failure').hide();
            $('#Pericardial').hide();
            $('#Arrhythmia').hide();
            $('#Thromboembolic').hide();
            $('#Stroke').hide();
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#cardiomyopathy').show();
            } else if (getUid === "2") {
                $('#heumatic').show();
            } else if (getUid === "5") {
                $('#Congenital').show();
            } else if (getUid === "6") {
                $('#Failure').show();
            } else if (getUid === "7") {
                $('#Pericardial').show();
            } else if (getUid === "9") {
                $('#Arrhythmia').show();
            } else if (getUid === "10") {
                $('#Thromboembolic').show();
            } else if (getUid === "11") {
                $('#Stroke').show();
            } else {
                $('#cardiomyopathy').hide();
                $('#heumatic').hide();
                $('#Congenital').hide();
                $('#Failure').hide();
                $('#Pericardial').hide();
                $('#Arrhythmia').hide();
                $('#Thromboembolic').hide();
                $('#Stroke').hide();
            }
        });


        $('#diagnosis_other').hide();

        $('#cardiomyopathy1').change(function() {
            var getUid = $(this).val();
            if (getUid === "8") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });

        $('#heumatic1').change(function() {
            var getUid = $(this).val();
            if (getUid === "6") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });

        $('#Congenital1').change(function() {
            var getUid = $(this).val();
            if (getUid === "6") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });

        $('#Pericardial1').change(function() {
            var getUid = $(this).val();
            if (getUid === "4") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });

        $('#Arrhythmia1').change(function() {
            var getUid = $(this).val();
            if (getUid === "2") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });

        $('#Thromboembolic1').change(function() {
            var getUid = $(this).val();
            if (getUid === "3") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });


        $('#ecg_other').hide();
        $('#ecg').change(function() {
            var getUid = $(this).val();
            if (getUid === "5") {
                $('#ecg_other').show();
            } else {
                $('#ecg_other').hide();
            }
        });

        $('#echo_other').hide();
        $('#echo_other2').hide();
        $('#echo_other1').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#echo_other').show();
                $('#echo_other2').show();
            } else {
                $('#echo_other').hide();
                $('#echo_other2').hide();
            }
        });

        $('#ncd_hospitalizations').hide();
        $('#hospitalizations').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#ncd_hospitalizations').show();
            } else {
                $('#ncd_hospitalizations').hide();
            }
        });

        $('#lab_ecg_other').hide();
        $('#lab_ecg').change(function() {
            var getUid = $(this).val();
            if (getUid === "2") {
                $('#lab_ecg_other').show();
            } else {
                $('#lab_ecg_other').hide();
            }
        });

        $('#cardiac_surgery_type').hide();
        $('#cardiac_surgery').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#cardiac_surgery_type').show();
            } else {
                $('#cardiac_surgery_type').hide();
            }
        });

        $('#risk_art_date').hide();
        $('#risk_hiv').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#risk_art_date').show();
            } else {
                $('#risk_art_date').hide();
            }
        });

        $('#cardiac_surgery_type').hide();
        $('#cardiac_surgery').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#cardiac_surgery_type').show();
            } else {
                $('#cardiac_surgery_type').hide();
            }
        });

        $('#ncd_diagns').hide();
        $('#diagns_changed').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#ncd_diagns').show();
            } else {
                $('#ncd_diagns').hide();
            }
        });

        $('#transfer_to').hide();
        $('#outcome').change(function() {
            var getUid = $(this).val();
            if (getUid === "4") {
                $('#transfer_to').show();
            } else {
                $('#transfer_to').hide();
            }
        });

        $('#transfer_other').hide();
        $('#transfer_to').change(function() {
            var getUid = $(this).val();
            if (getUid === "3") {
                $('#transfer_other').show();
            } else {
                $('#transfer_other').hide();
            }
        });

        $('#death').hide();
        $('#outcome').change(function() {
            var getUid = $(this).val();
            if (getUid === "5") {
                $('#death').show();
            } else {
                $('#death').hide();
            }
        });

        $('#death_other').hide();
        $('#death').change(function() {
            var getUid = $(this).val();
            if (getUid === "3") {
                $('#death_other').show();
            } else {
                $('#death_other').hide();
            }
        });

        $('#diagnosis_other').hide();
        $('#diagnosis').change(function() {
            var getUid = $(this).val();
            if (getUid === "8") {
                $('#diagnosis_other').show();
            } else {
                $('#diagnosis_other').hide();
            }
        });


        // const el = document.getElementById('outcome');

        // const transfer_to = document.getElementById('transfer_to');
        // const death = document.getElementById('death');


        // el.addEventListener('change', function handleChange(event) {
        //     if (event.target.value === '4') {
        //         transfer_to.style.display = 'block';
        //     } else if (event.target.value === '5') {
        //         death.style.display = 'block';
        //     } else {
        //         transfer_to.style.display = 'none';
        //         death.style.display = 'none';
        //     }
        // });


        // const occupation = document.getElementById('occupation');


        // const list_exposure = document.getElementById('list_exposure');

        // occupation.addEventListener('change', function handleChange(event) {
        //     if (event.target.value === '1') {
        //         list_exposure.style.display = 'block';
        //     } else {
        //         list_exposure.style.display = 'none';
        //     }
        // });




        // const diagnosis = document.getElementById('cardiac');

        // const Cardiomyopathy = document.getElementById('Cardiomyopathy');
        // const heumatic = document.getElementById('heumatic');
        // const Congenital = document.getElementById('Congenital');
        // const Failure = document.getElementById('Failure');
        // const Pericardial = document.getElementById('Pericardial');
        // const Arrhythmia = document.getElementById('Arrhythmia');
        // const Thromboembolic = document.getElementById('Thromboembolic');
        // const Stroke = document.getElementById('Stroke');


        // diagnosis.addEventListener('change', function handleChange(event) {
        //     if (event.target.value === '1') {
        //         Cardiomyopathy.style.display = 'block';
        //     } else if (event.target.value === '2') {
        //         heumatic.style.display = 'block';
        //     } else if (event.target.value === '5') {
        //         Congenital.style.display = 'block';
        //     } else if (event.target.value === '6') {
        //         Failure.style.display = 'block';
        //     } else if (event.target.value === '7') {
        //         Pericardial.style.display = 'block';
        //     } else if (event.target.value === '8') {
        //         Arrhythmia.style.display = 'block';
        //     } else if (event.target.value === '9') {
        //         Thromboembolic.style.display = 'block';
        //     } else if (event.target.value === '10') {
        //         Stroke.style.display = 'block';
        //     } else {
        //         Cardiomyopathy.style.display = 'none';
        //         heumatic.style.display = 'none';
        //         Congenital.style.display = 'none';
        //         Failure.style.display = 'none';
        //         Pericardial.style.display = 'none';
        //         Arrhythmia.style.display = 'none';
        //         Thromboembolic.style.display = 'none';
        //         Stroke.style.display = 'none';
        //     }
        // });
    </script>
</body>

</html>