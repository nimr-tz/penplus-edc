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
        } elseif (Input::get('add_medications')) {
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

                        // $age = $user->dateDiffYears(date('Y-m-d'), Input::get('dob'));
                        $check_clients = $override->countData3('clients', 'firstname', Input::get('firstname'), 'middlename', Input::get('middlename'), 'lastname', Input::get('lastname'));

                        $firstname = Input::get('firstname');
                        $middlename = Input::get('middlename');
                        $lastname = Input::get('lastname');

                        if ($check_clients >= 1) {
                            $errorMessage = 'Participant ' . $firstname . ' -  ' . $middlename . '  -  ' . $lastname . '  -  ' . '  Already Registered';
                        } else {

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
                            Redirect::to('info.php?id=3&status=5');
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_demographic')) {
            $validate = $validate->check($_POST, array(
                // 'visit_date' => array(
                //     'required' => true,
                // ),
                'next_visit' => array(
                    'required' => true,
                ),
                'referred' => array(
                    'required' => true,
                ),
                // 'chw' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {

                    $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($demographic) {
                        $user->updateRecord('demographic', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
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
                            'site_id' => $user->data()->site_id,
                        ), $demographic['id']);
                    } else {
                        $user->createRecord('demographic', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                // 'main_diagnosis' => array(
                //     'required' => true,
                // ),


            ));
            if ($validate->passed()) {
                try {

                    $cardiac = $override->get3('cardiac', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($cardiac) {
                        $user->updateRecord('cardiac', array(
                            'diagnosis_date' => Input::get('diagnosis_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'cardiomyopathy' => Input::get('cardiomyopathy'),
                            'sub_cardiomyopathy' => Input::get('sub_cardiomyopathy'),
                            'cardiomyopathy_other' => Input::get('cardiomyopathy_other'),
                            'heumatic' => Input::get('heumatic'),
                            'sub_heumatic' => Input::get('sub_heumatic'),
                            'heumatic_other' => Input::get('heumatic_other'),
                            'congenital' => Input::get('congenital'),
                            'congenital_other' => Input::get('congenital_other'),
                            'sub_congenital' => Input::get('sub_congenital'),
                            'severe_hypertension' => Input::get('severe_hypertension'),
                            'coronary_artery' => Input::get('coronary_artery'),
                            'hypertensive_heart' => Input::get('hypertensive_heart'),
                            'heart_failure' => Input::get('heart_failure'),
                            'sub_heart_failure' => Input::get('sub_heart_failure'),
                            'heart_failure_other' => Input::get('heart_failure_other'),
                            'pericardial' => Input::get('pericardial'),
                            'sub_pericardial' => Input::get('sub_pericardial'),
                            'pericardial_other' => Input::get('pericardial_other'),
                            'arrhythmia' => Input::get('arrhythmia'),
                            'sub_arrhythmia' => Input::get('sub_arrhythmia'),
                            'arrhythmia_other' => Input::get('arrhythmia_other'),
                            'stroke' => Input::get('stroke'),
                            'sub_stroke' => Input::get('sub_stroke'),
                            'thromboembolic' => Input::get('thromboembolic'),
                            'sub_thromboembolic' => Input::get('sub_thromboembolic'),
                            'thromboembolic_other' => Input::get('thromboembolic_other'),
                            'comments' => Input::get('comments'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'diagnosis_specify' => Input::get('diagnosis_specify'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $cardiac['id']);
                    } else {
                        $user->createRecord('cardiac', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagnosis_date' => Input::get('diagnosis_date'),
                            'cardiomyopathy' => Input::get('cardiomyopathy'),
                            'sub_cardiomyopathy' => Input::get('sub_cardiomyopathy'),
                            'cardiomyopathy_other' => Input::get('cardiomyopathy_other'),
                            'heumatic' => Input::get('heumatic'),
                            'sub_heumatic' => Input::get('sub_heumatic'),
                            'heumatic_other' => Input::get('heumatic_other'),
                            'congenital' => Input::get('congenital'),
                            'congenital_other' => Input::get('congenital_other'),
                            'sub_congenital' => Input::get('sub_congenital'),
                            'severe_hypertension' => Input::get('severe_hypertension'),
                            'coronary_artery' => Input::get('coronary_artery'),
                            'hypertensive_heart' => Input::get('hypertensive_heart'),
                            'heart_failure' => Input::get('heart_failure'),
                            'sub_heart_failure' => Input::get('sub_heart_failure'),
                            'heart_failure_other' => Input::get('heart_failure_other'),
                            'pericardial' => Input::get('pericardial'),
                            'sub_pericardial' => Input::get('sub_pericardial'),
                            'pericardial_other' => Input::get('pericardial_other'),
                            'arrhythmia' => Input::get('arrhythmia'),
                            'sub_arrhythmia' => Input::get('sub_arrhythmia'),
                            'arrhythmia_other' => Input::get('arrhythmia_other'),
                            'stroke' => Input::get('stroke'),
                            'sub_stroke' => Input::get('sub_stroke'),
                            'thromboembolic' => Input::get('thromboembolic'),
                            'sub_thromboembolic' => Input::get('sub_thromboembolic'),
                            'thromboembolic_other' => Input::get('thromboembolic_other'),
                            'comments' => Input::get('comments'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'diagnosis_specify' => Input::get('diagnosis_specify'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $successMessage = 'Cardiac added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_diabetic')) {
            $validate = $validate->check($_POST, array(
                'diagnosis_date' => array(
                    'required' => true,
                ),


            ));
            if ($validate->passed()) {
                try {

                    $diabetic = $override->get3('diabetic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($diabetic) {
                        $user->updateRecord('diabetic', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
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
                            'site_id' => $user->data()->site_id,
                        ), $diabetic['id']);
                    } else {
                        $user->createRecord('diabetic', array(
                            'visit_date' => Input::get('diagnosis_date'),
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_scd')) {
            $validate = $validate->check($_POST, array(
                'diagnosis' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {

                    $sickle_cell = $override->get3('sickle_cell', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($sickle_cell) {
                        $user->updateRecord('sickle_cell', array(
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'visit_date' => Input::get('diagnosis_date'),
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $sickle_cell['id']);
                    } else {
                        $user->createRecord('sickle_cell', array(
                            'diagnosis' => Input::get('diagnosis'),
                            'diagnosis_other' => Input::get('diagnosis_other'),
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'comments' => Input::get('comments'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Sickle Cell added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'height' => Input::get('height'),
                            'weight' => Input::get('weight'),
                            'bmi' => Input::get('bmi'),
                            'muac' => Input::get('muac'),
                            'systolic' => Input::get('systolic'),
                            'dystolic' => Input::get('dystolic'),
                            'pr' => Input::get('pr'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
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
                            'systolic' => Input::get('systolic'),
                            'dystolic' => Input::get('dystolic'),
                            'pr' => Input::get('pr'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Vital added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                                'study_id' => $_GET['sid'],
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
                                'art' => Input::get('art'),
                                'art_date' => Input::get('art_date'),
                                'tb' => Input::get('tb'),
                                'tb_year' => Input::get('tb_year'),
                                'smoking' => Input::get('smoking'),
                                'packs' => Input::get('packs'),
                                'active_smoker' => Input::get('active_smoker'),
                                'alcohol' => Input::get('alcohol'),
                                'alcohol_type' => Input::get('alcohol_type'),
                                'alcohol_other' => Input::get('alcohol_other'),
                                'quantity' => Input::get('quantity'),
                                'cardiac_disease' => Input::get('cardiac_disease'),
                                'cardiac_surgery' => Input::get('cardiac_surgery'),
                                'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                                'surgery_other' => Input::get('surgery_other'),
                                'scd_disease' => Input::get('scd_disease'),
                                'history_other' => Input::get('history_other'),
                                'history_specify' => Input::get('history_specify'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }
                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('history', array(
                                'visit_date' => date('Y-m-d'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'hiv' => Input::get('hiv'),
                                'hiv_test' => Input::get('hiv_test'),
                                'art' => Input::get('art'),
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
                                'history_specify' => Input::get('history_specify'),
                                'scd_disease' => Input::get('scd_disease'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('history', array(
                                'visit_date' => date('Y-m-d'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'hiv' => Input::get('hiv'),
                                'hiv_test' => Input::get('hiv_test'),
                                'art' => Input::get('art'),
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
                                'transfusion_born' => Input::get('transfusion_born'),
                                'transfusion_12months' => Input::get('transfusion_12months'),
                                'acute_chest' => Input::get('acute_chest'),
                                'other_complication' => Input::get('other_complication'),
                                'specify_complication' => Input::get('specify_complication'),
                                'scd_disease' => Input::get('scd_disease'),
                                'history_scd' => Input::get('history_scd'),
                                'hepatitis_test' => Input::get('hepatitis_test'),
                                'hepatitis_date' => Input::get('hepatitis_date'),
                                'hepatitis_results' => Input::get('hepatitis_results'),
                                'vaccine_history' => Input::get('vaccine_history'),
                                'blood_group' => Input::get('blood_group'),
                                'siblings' => Input::get('siblings'),
                                'sibling_salive' => Input::get('sibling_salive'),
                                'history_other' => Input::get('history_other'),
                                'history_specify' => Input::get('history_specify'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $history['id']);
                        }
                    } else {
                        $user->createRecord('history', array(
                            'visit_date' => date('Y-m-d'),
                            'study_id' => $_GET['sid'],
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
                            'art' => Input::get('art'),
                            'art_date' => Input::get('art_date'),
                            'tb' => Input::get('tb'),
                            'tb_year' => Input::get('tb_year'),
                            'smoking' => Input::get('smoking'),
                            'packs' => Input::get('packs'),
                            'active_smoker' => Input::get('active_smoker'),
                            'alcohol' => Input::get('alcohol'),
                            'alcohol_type' => Input::get('alcohol_type'),
                            'alcohol_other' => Input::get('alcohol_other'),
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
                            'transfusion_born' => Input::get('transfusion_born'),
                            'transfusion_12months' => Input::get('transfusion_12months'),
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
                            'history_specify' => Input::get('history_specify'),
                            'scd_disease' => Input::get('scd_disease'),
                            'history_scd' => Input::get('history_scd'),
                            'hepatitis_test' => Input::get('hepatitis_test'),
                            'hepatitis_date' => Input::get('hepatitis_date'),
                            'hepatitis_results' => Input::get('hepatitis_results'),
                            'vaccine_history' => Input::get('vaccine_history'),
                            'blood_group' => Input::get('blood_group'),
                            'siblings' => Input::get('siblings'),
                            'sibling_salive' => Input::get('sibling_salive'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $multiArray = $override->get('sickle_cell_status_table', 'patient_id', $_GET['cid']);
                    $i = 0;
                    foreach (Input::get('age') as $searchValue) {
                        if ($user->isValueInMultiArrays($searchValue, $multiArray)) {
                            // echo "The value '{$searchValue}' exists in the multi-dimensional array.";
                            // $user->isValueInMultiArrays($searchValue, $multiArray);
                            // $id = $override->get('card_test', 'cardiac', $searchValue);
                            // $user->updateRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ), $id['id']);
                        } else {
                            // echo "The value '{$searchValue}' does not exist in the multi-dimensional array.";
                            // $user->createRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ));vehicle11
                            $user->createRecord('sickle_cell_status_table', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'age' => $searchValue,
                                'sex' => Input::get('sex')[$i],
                                'sickle_status' => Input::get('sickle_status')[$i],
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                        $i++;
                    }
                    $successMessage = 'History added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                        $user->updateRecord('symptoms', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $symptoms['id']);

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('symptoms', array(
                                'chest_pain' => Input::get('chest_pain'),
                                'score_chest_pain' => Input::get('score_chest_pain'),
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('symptoms', array(
                                'abnorminal_pain' => Input::get('abnorminal_pain'),
                                'score_abnorminal_pain' => Input::get('score_abnorminal_pain'),
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {
                            $user->updateRecord('symptoms', array(
                                'dyspnea' => Input::get('dyspnea'),
                                'orthopnea' => Input::get('orthopnea'),
                                'paroxysmal' => Input::get('paroxysmal'),
                                'cough' => Input::get('cough'),
                                'edema' => Input::get('edema'),
                                'lungs' => Input::get('lungs'),
                                'lungs_other' => Input::get('lungs_other'),
                                'jvp' => Input::get('jvp'),
                                'volume' => Input::get('volume'),
                                'murmur' => Input::get('murmur'),
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {
                            $user->updateRecord('symptoms', array(
                                'thirst' => Input::get('thirst'),
                                'urination' => Input::get('urination'),
                                'vision' => Input::get('vision'),
                                'vomiting' => Input::get('vomiting'),
                                'weight_loss' => Input::get('weight_loss'),
                                'foot_exam' => Input::get('foot_exam'),
                                'foot_exam_finding' => Input::get('foot_exam_finding'),
                                'foot_exam_other' => Input::get('foot_exam_other'),
                                'fasting' => Input::get('fasting'),
                                'random_fs' => Input::get('random_fs'),
                                'hba1c' => Input::get('hba1c'),
                                'hypoglycemia_symptoms' => Input::get('hypoglycemia_symptoms'),
                                'hypoglycemia_severe' => Input::get('hypoglycemia_severe'),
                                'hypoglycemia__number' => Input::get('hypoglycemia__number'),
                            ), $symptoms['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                            $user->updateRecord('symptoms', array(
                                'breathing' => Input::get('breathing'),
                                'other_sickle' => Input::get('other_sickle'),
                                'sickle_specify' => Input::get('sickle_specify'),
                                'malnutrition' => Input::get('malnutrition'),
                                'pallor' => Input::get('pallor'),
                                'jaundice' => Input::get('jaundice'),
                                'splenomegaly' => Input::get('splenomegaly'),
                                'anemia' => Input::get('anemia'),
                                'hb' => Input::get('hb'),
                                'wbc' => Input::get('wbc'),
                                'plt' => Input::get('plt'),
                                'labs_other' => Input::get('labs_other'),
                                'headache' => Input::get('headache'),
                                'score_headache' => Input::get('score_headache'),
                                'upper_arms' => Input::get('upper_arms'),
                                'score_upper_arms' => Input::get('score_upper_arms'),
                                'lower_arms' => Input::get('lower_arms'),
                                'score_lower_arms' => Input::get('score_lower_arms'),
                                'waist' => Input::get('waist'),
                                'score_waist' => Input::get('score_waist'),
                                'joints' => Input::get('joints'),
                                'spescify_joints' => Input::get('spescify_joints'),
                                'score_joints' => Input::get('score_joints'),
                                'other_pain' => Input::get('other_pain'),
                                'spescify_other_pain' => Input::get('spescify_other_pain'),
                                'score_other_pain' => Input::get('score_other_pain'),
                            ), $symptoms['id']);
                        }
                    } else {
                        $user->createRecord('symptoms', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'dyspnea' => Input::get('dyspnea'),
                            'orthopnea' => Input::get('orthopnea'),
                            'paroxysmal' => Input::get('paroxysmal'),
                            'headache' => Input::get('headache'),
                            'score_headache' => Input::get('score_headache'),
                            'chest_pain' => Input::get('chest_pain'),
                            'score_chest_pain' => Input::get('score_chest_pain'),
                            'abnorminal_pain' => Input::get('abnorminal_pain'),
                            'score_abnorminal_pain' => Input::get('score_abnorminal_pain'),
                            'upper_arms' => Input::get('upper_arms'),
                            'score_upper_arms' => Input::get('score_upper_arms'),
                            'lower_arms' => Input::get('lower_arms'),
                            'score_lower_arms' => Input::get('score_lower_arms'),
                            'waist' => Input::get('waist'),
                            'score_waist' => Input::get('score_waist'),
                            'joints' => Input::get('joints'),
                            'spescify_joints' => Input::get('spescify_joints'),
                            'score_joints' => Input::get('score_joints'),
                            'other_pain' => Input::get('other_pain'),
                            'spescify_other_pain' => Input::get('spescify_other_pain'),
                            'score_other_pain' => Input::get('score_other_pain'),
                            'cough' => Input::get('cough'),
                            'thirst' => Input::get('thirst'),
                            'urination' => Input::get('urination'),
                            'vision' => Input::get('vision'),
                            'vomiting' => Input::get('vomiting'),
                            'weight_loss' => Input::get('weight_loss'),
                            'breathing' => Input::get('breathing'),
                            'other_sickle' => Input::get('other_sickle'),
                            'sickle_specify' => Input::get('sickle_specify'),
                            'edema' => Input::get('edema'),
                            'lungs' => Input::get('lungs'),
                            'lungs_other' => Input::get('lungs_other'),
                            'jvp' => Input::get('jvp'),
                            'volume' => Input::get('volume'),
                            'murmur' => Input::get('murmur'),
                            'foot_exam' => Input::get('foot_exam'),
                            'foot_exam_finding' => Input::get('foot_exam_finding'),
                            'foot_exam_other' => Input::get('foot_exam_other'),
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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


                    // Counting number of checked checkboxes.
                    $checked_count = count(Input::get('cardiac'));

                    $i = 0;
                    foreach (Input::get('cardiac') as $selected) {

                        if ($diagnosis) {
                            $user->updateRecord('diagnosis', array(
                                'visit_date' => Input::get('diagnosis_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'diagnosis_date' => Input::get('diagnosis_date'),
                                'cardiac' => Input::get('cardiac')[$i],
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
                                'site_id' => $user->data()->site_id,
                            ), $diagnosis['id']);
                        } else {
                            $user->createRecord('diagnosis', array(
                                'visit_date' => Input::get('diagnosis_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'cardiac' => Input::get('cardiac')[$i],
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
                    }
                    $successMessage = 'Diagnosis added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_results')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {
                    $results = $override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($results) {
                        $user->updateRecord('results', array(
                            'visit_date' => Input::get('visit_date'),
                            'ecg_performed' => Input::get('ecg_performed'),
                            'echo_performed' => Input::get('echo_performed'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $results['id']);

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {
                            $user->updateRecord('results', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'congenital_defect' => Input::get('congenital_defect'),
                                'thrombus' => Input::get('thrombus'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $results['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                            $user->updateRecord('results', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'scd_test' => Input::get('scd_test'),
                                'scd_test_other' => Input::get('scd_test_other'),
                                'confirmatory_test' => Input::get('confirmatory_test'),
                                'confirmatory_test_type' => Input::get('confirmatory_test_type'),
                                'scd_done' => Input::get('scd_done'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $results['id']);
                        }
                    } else {
                        $user->createRecord('results', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'ecg_performed' => Input::get('ecg_performed'),
                            'ecg_date' => Input::get('ecg_date'),
                            'ecg' => Input::get('ecg'),
                            'ecg_other' => Input::get('ecg_other'),
                            'echo_performed' => Input::get('echo_performed'),
                            'echo_date' => Input::get('echo_date'),
                            'echo' => Input::get('echo'),
                            'lv' => Input::get('lv'),
                            'mitral' => Input::get('mitral'),
                            'rv' => Input::get('rv'),
                            'pericardial' => Input::get('pericardial'),
                            'ivc' => Input::get('ivc'),
                            'congenital_defect' => Input::get('congenital_defect'),
                            'thrombus' => Input::get('thrombus'),
                            'echo_other' => Input::get('echo_other'),
                            'echo_specify' => Input::get('echo_specify'),
                            'echo_other2' => Input::get('echo_other2'),
                            'scd_test' => Input::get('scd_test'),
                            'scd_done' => Input::get('scd_done'),
                            'scd_test_other' => Input::get('scd_test_other'),
                            'confirmatory_test' => Input::get('confirmatory_test'),
                            'confirmatory_test_type' => Input::get('confirmatory_test_type'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Results added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {
                            $user->updateRecord('hospitalization', array(
                                'visit_date' => Input::get('hospitalization_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'basal_insulin' => Input::get('basal_insulin'),
                                'basal_am' => Input::get('basal_am'),
                                'basal_pm' => Input::get('basal_pm'),
                                'prandial_insulin' => Input::get('prandial_insulin'),
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
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                            $user->updateRecord('hospitalization', array(
                                'visit_date' => Input::get('hospitalization_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'site_id' => $user->data()->site_id,
                            ), $hospitalization['id']);
                        }
                    } else {
                        $user->createRecord('hospitalization', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'study_id' => $_GET['sid'],
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
                            'basal_insulin' => Input::get('basal_insulin'),
                            'basal_am' => Input::get('basal_am'),
                            'basal_pm' => Input::get('basal_pm'),
                            'prandial_insulin' => Input::get('prandial_insulin'),
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'lab_date' => Input::get('lab_date'),
                                'ncd_coping' => Input::get('ncd_coping'),
                                'family_planning' => Input::get('family_planning'),
                                'chemistry_test' => Input::get('chemistry_test'),
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
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('lab_details', array(
                                'visit_date' => Input::get('lab_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'chemistry_test2' => Input::get('chemistry_test2'),
                                'proteinuria' => Input::get('proteinuria'),
                                'lipid_panel' => Input::get('lipid_panel'),
                                'other_lab_diabetes' => Input::get('other_lab_diabetes'),
                                'specify_lab_diabetes' => Input::get('specify_lab_diabetes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('lab_details', array(
                                'visit_date' => Input::get('lab_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'hematology_test' => Input::get('hematology_test'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $lab_details['id']);
                        }
                    } else {
                        $user->createRecord('lab_details', array(
                            'visit_date' => Input::get('lab_date'),
                            'study_id' => $_GET['sid'],
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
                            'chemistry_test' => Input::get('chemistry_test'),
                            'chemistry_test2' => Input::get('chemistry_test2'),
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
                            'hematology_test' => Input::get('hematology_test'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Lab details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $hospitalization_details['id']);
                    } else {
                        $user->createRecord('hospitalization_details', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $multiArray = $override->get('hospitalization_table', 'patient_id', $_GET['cid']);
                    $i = 0;
                    foreach (Input::get('admission_date') as $searchValue) {
                        if ($user->isValueInMultiArrays($searchValue, $multiArray)) {
                            // echo "The value '{$searchValue}' exists in the multi-dimensional array.";
                            // $user->isValueInMultiArrays($searchValue, $multiArray);
                            // $id = $override->get('card_test', 'cardiac', $searchValue);
                            // $user->updateRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ), $id['id']);
                        } else {
                            // echo "The value '{$searchValue}' does not exist in the multi-dimensional array.";
                            // $user->createRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ));vehicle11
                            $user->createRecord('hospitalization_table', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'admission_date' => $searchValue,
                                'admission_reason' => Input::get('admission_reason')[$i],
                                'discharge_diagnosis' => Input::get('discharge_diagnosis')[$i],
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                        $i++;
                    }


                    $successMessage = 'Hospitalization details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'risk_tobacco' => Input::get('risk_tobacco'),
                            'risk_alcohol' => Input::get('risk_alcohol'),
                            'risk_employment' => Input::get('risk_employment'),
                            'ncd_limiting' => Input::get('ncd_limiting'),
                            'social_economic' => Input::get('social_economic'),
                            'risk_hiv_date' => Input::get('risk_hiv_date'),
                            'risk_hiv' => Input::get('risk_hiv'),
                            'risk_art' => Input::get('risk_art'),
                            'risk_art_date' => Input::get('risk_art_date'),
                            'risk_tb_date' => Input::get('risk_tb_date'),
                            'risk_tb' => Input::get('risk_tb'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $risks['id']);
                    } else {
                        $user->createRecord('risks', array(
                            'visit_date' => Input::get('risk_date'),
                            'study_id' => $_GET['sid'],
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
                            'risk_art' => Input::get('risk_art'),
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                        $user->updateRecord('dgns_complctns_comorbdts', array(
                            'visit_date' => Input::get('diagns_date'),
                            'diagns_date' => Input::get('diagns_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagns_changed' => Input::get('diagns_changed'),
                            'diagns_cardiac' => Input::get('diagns_cardiac'),
                            'diagns_specify' => Input::get('diagns_specify'),
                            'new_diagns' => Input::get('new_diagns'),
                            'new_diagns_specify' => Input::get('new_diagns_specify'),
                            'diagns_complication' => Input::get('diagns_complication'),
                            'cmplctn_other' => Input::get('cmplctn_other'),
                            'complication_specify' => Input::get('complication_specify'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $dgns_complctns_comorbdts['id']);

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'cmplctn_pain_event' => Input::get('cmplctn_pain_event'),
                                'cmplctn_stroke' => Input::get('cmplctn_stroke'),
                            ), $dgns_complctns_comorbdts['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'diagns_cardiac' => Input::get('diagns_cardiac'),
                                'cmplctn_ckd' => Input::get('cmplctn_ckd'),
                                'cmplctn_depression' => Input::get('cmplctn_depression'),
                            ), $dgns_complctns_comorbdts['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'diagns_diabetes' => Input::get('diagns_diabetes'),
                                'cmplctn_cardiovascular' => Input::get('cmplctn_cardiovascular'),
                                'cmplctn_neuropathy' => Input::get('cmplctn_neuropathy'),
                                'cmplctn_dysfunction' => Input::get('cmplctn_dysfunction'),
                                'cmplctn_pvd' => Input::get('cmplctn_pvd'),
                                'cmplctn_retinopathy' => Input::get('cmplctn_retinopathy'),
                                'cmplctn_renal' => Input::get('cmplctn_renal'),
                            ), $dgns_complctns_comorbdts['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('dgns_complctns_comorbdts', array(
                                'diagns_sickle' => Input::get('diagns_sickle'),
                                'cmplctn_pneumonia' => Input::get('cmplctn_pneumonia'),
                                'cmplctn_priapism' => Input::get('cmplctn_priapism'),
                                'cmplctn_avn' => Input::get('cmplctn_avn'),
                                'cmplctn_hypersplenism' => Input::get('cmplctn_hypersplenism'),
                                'cmplctn_ulcer' => Input::get('cmplctn_ulcer'),
                                'cmplctn_visual_loss' => Input::get('cmplctn_visual_loss'),
                                'cmplctn_transfusion' => Input::get('cmplctn_transfusion'),
                                'cmplctn_syndrome' => Input::get('cmplctn_syndrome'),
                            ), $dgns_complctns_comorbdts['id']);
                        }
                    } else {
                        $user->createRecord('dgns_complctns_comorbdts', array(
                            'visit_date' => Input::get('diagns_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'diagns_date' => Input::get('diagns_date'),
                            'diagns_changed' => Input::get('diagns_changed'),
                            'diagns_cardiac' => Input::get('diagns_cardiac'),
                            'diagns_diabetes' => Input::get('diagns_diabetes'),
                            'diagns_sickle' => Input::get('diagns_sickle'),
                            'diagns_specify' => Input::get('diagns_specify'),
                            'new_diagns' => Input::get('new_diagns'),
                            'new_diagns_specify' => Input::get('new_diagns_specify'),
                            'diagns_complication' => Input::get('diagns_complication'),
                            'cmplctn_ckd' => Input::get('cmplctn_ckd'),
                            'cmplctn_depression' => Input::get('cmplctn_depression'),
                            'cmplctn_cardiovascular' => Input::get('cmplctn_cardiovascular'),
                            'cmplctn_neuropathy' => Input::get('cmplctn_neuropathy'),
                            'cmplctn_dysfunction' => Input::get('cmplctn_dysfunction'),
                            'cmplctn_pvd' => Input::get('cmplctn_pvd'),
                            'cmplctn_retinopathy' => Input::get('cmplctn_retinopathy'),
                            'cmplctn_renal' => Input::get('cmplctn_renal'),
                            'cmplctn_pain_event' => Input::get('cmplctn_pain_event'),
                            'cmplctn_stroke' => Input::get('cmplctn_stroke'),
                            'cmplctn_pneumonia' => Input::get('cmplctn_pneumonia'),
                            'cmplctn_priapism' => Input::get('cmplctn_priapism'),
                            'cmplctn_avn' => Input::get('cmplctn_avn'),
                            'cmplctn_hypersplenism' => Input::get('cmplctn_hypersplenism'),
                            'cmplctn_ulcer' => Input::get('cmplctn_ulcer'),
                            'cmplctn_visual_loss' => Input::get('cmplctn_visual_loss'),
                            'cmplctn_transfusion' => Input::get('cmplctn_transfusion'),
                            'cmplctn_syndrome' => Input::get('cmplctn_syndrome'),
                            'cmplctn_other' => Input::get('cmplctn_other'),
                            'complication_specify' => Input::get('complication_specify'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Diagnosis details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
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
                            'site_id' => $user->data()->site_id,
                        ), $summary['id']);
                    } else {
                        $user->createRecord('summary', array(
                            'visit_date' => Input::get('summary_date'),
                            'study_id' => $_GET['sid'],
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
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'salt' => Input::get('salt'),
                                'fluid' => Input::get('fluid'),
                                'restriction_other' => Input::get('restriction_other'),
                                'restriction_specify' => Input::get('restriction_specify'),
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
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) {

                            $user->updateRecord('treatment_plan', array(
                                'visit_date' => Input::get('visit_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }

                        if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {

                            $user->updateRecord('treatment_plan', array(
                                'visit_date' => Input::get('visit_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
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
                                'misconception' => Input::get('misconception'),
                                'life_style' => Input::get('life_style'),
                                'medication_notes' => Input::get('medication_notes'),
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'site_id' => $user->data()->site_id,
                            ), $treatment_plan['id']);
                        }
                    } else {
                        $user->createRecord('treatment_plan', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
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
                            'restriction_specify' => Input::get('restriction_specify'),
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
                            'misconception' => Input::get('misconception'),
                            'life_style' => Input::get('life_style'),
                            'medication_notes' => Input::get('medication_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $multiArray = $override->get('medication_treatments', 'patient_id', $_GET['cid']);
                    $i = 0;
                    foreach (Input::get('medication_type') as $searchValue) {
                        if ($user->isValueInMultiArrays($searchValue, $multiArray)) {
                            // echo "The value '{$searchValue}' exists in the multi-dimensional array.";
                            // $user->isValueInMultiArrays($searchValue, $multiArray);
                            // $id = $override->get('card_test', 'cardiac', $searchValue);
                            // $user->updateRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ), $id['id']);
                        } else {
                            // echo "The value '{$searchValue}' does not exist in the multi-dimensional array.";
                            // $user->createRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ));vehicle11
                            $user->createRecord('medication_treatments', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'medication_type' => $searchValue,
                                'medication_action' => Input::get('medication_action')[$i],
                                'medication_dose' => Input::get('medication_dose')[$i],
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                        $i++;
                    }

                    $successMessage = 'Treatment plan added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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
                            'study_id' => $_GET['sid'],
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
                            'primary_income_earner' => Input::get('primary_income_earner'),
                            'primary_income_earner_other' => Input::get('primary_income_earner_other'),
                            'formally_employed' => Input::get('formally_employed'),
                            'formally_employed_other' => Input::get('formally_employed_other'),
                            'main_income_based_other' => Input::get('main_income_based_other'),
                            'main_income_based' => Input::get('main_income_based'),
                            'reason_not_working' => Input::get('reason_not_working'),
                            'last_working' => Input::get('last_working'),
                            'stopped_working' => Input::get('stopped_working'),
                            'stopped_duration' => Input::get('stopped_duration'),
                            'someone_take_care' => Input::get('someone_take_care'),
                            'take_care_duration' => Input::get('take_care_duration'),
                            'quit_job' => Input::get('quit_job'),
                            'affect_social' => Input::get('affect_social'),
                            'affect_social_how' => Input::get('affect_social_how'),
                            'financial_burden' => Input::get('financial_burden'),
                            'affect_social_other' => Input::get('affect_social_other'),
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
                            'patient_education' => Input::get('patient_education'),
                            'patient_education_other' => Input::get('patient_education_other'),
                            'primary_earner_edctn' => Input::get('primary_earner_edctn'),
                            'household_education' => Input::get('household_education'),
                            'household_education_other' => Input::get('household_education_other'),
                            'earner_edctn_other' => Input::get('earner_edctn_other'),
                            'spouse_edctn' => Input::get('spouse_edctn'),
                            'spouse_edctn_other' => Input::get('spouse_edctn_other'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $social_economic['id']);
                    } else {
                        $user->createRecord('social_economic', array(
                            'visit_date' => Input::get('social_economic_date'),
                            'study_id' => $_GET['sid'],
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
                            'primary_income_earner' => Input::get('primary_income_earner'),
                            'primary_income_earner_other' => Input::get('primary_income_earner_other'),
                            'formally_employed' => Input::get('formally_employed'),
                            'formally_employed_other' => Input::get('formally_employed_other'),
                            'main_income_based_other' => Input::get('main_income_based_other'),
                            'main_income_based' => Input::get('main_income_based'),
                            'reason_not_working' => Input::get('reason_not_working'),
                            'last_working' => Input::get('last_working'),
                            'stopped_working' => Input::get('stopped_working'),
                            'stopped_duration' => Input::get('stopped_duration'),
                            'someone_take_care' => Input::get('someone_take_care'),
                            'take_care_duration' => Input::get('take_care_duration'),
                            'quit_job' => Input::get('quit_job'),
                            'affect_social' => Input::get('affect_social'),
                            'affect_social_how' => Input::get('affect_social_how'),
                            'affect_social_other' => Input::get('affect_social_other'),
                            'financial_burden' => Input::get('financial_burden'),
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
                            'patient_education' => Input::get('patient_education'),
                            'patient_education_other' => Input::get('patient_education_other'),
                            'primary_earner_edctn' => Input::get('primary_earner_edctn'),
                            'household_education' => Input::get('household_education'),
                            'household_education_other' => Input::get('household_education_other'),
                            'earner_edctn_other' => Input::get('earner_edctn_other'),
                            'spouse_edctn' => Input::get('spouse_edctn'),
                            'spouse_edctn_other' => Input::get('spouse_edctn_other'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Social economic  details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_card_test')) {
            $validate = $validate->check($_POST, array(
                // 'cardiac' => array(
                //     'required' => true,
                // ),


            ));
            if ($validate->passed()) {
                try {
                    $multiArray = $override->get('card_test', 'status', 1);
                    foreach (Input::get('cardiac') as $searchValue) {
                        if ($user->isValueInMultiArrays($searchValue, $multiArray)) {
                            // echo "The value '{$searchValue}' exists in the multi-dimensional array.";
                            // $user->isValueInMultiArrays($searchValue, $multiArray);
                            // $id = $override->get('card_test', 'cardiac', $searchValue);
                            // $user->updateRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ), $id['id']);
                        } else {
                            // echo "The value '{$searchValue}' does not exist in the multi-dimensional array.";
                            $user->createRecord('card_test', array(
                                'cardiac' => $searchValue,
                            ));
                        }
                    }

                    $successMessage = 'Diagnosis added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
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

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>
        #medication_table {
            border-collapse: collapse;
        }

        #medication_table th,
        #medication_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #medication_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        #medication_table {
            border-collapse: collapse;
        }

        #medication_list th,
        #medication_list td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #medication_list th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .remove-row {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .remove-row:hover {
            background-color: #da190b;
        }

        .edit-row {
            background-color: #3FF22F;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .edit-row:hover {
            background-color: #da190b;
        }

        #hospitalization_details_table {
            border-collapse: collapse;
        }

        #hospitalization_details_table th,
        #hospitalization_details_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #hospitalization_details_table th,
        #hospitalization_details_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #hospitalization_details_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        #sickle_cell_table {
            border-collapse: collapse;
        }

        #sickle_cell_table th,
        #sickle_cell_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #sickle_cell_table th,
        #sickle_cell_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #sickle_cell_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .hidden {
            display: none;
        }
    </style>

    <!-- <script type="text/javascript" src="hospital.js"></script> -->

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
                                        <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" /> <span>Example: 0700 000 111</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">E-mail Address:</div>
                                        <!-- <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" /> <span>Example: someone@nowhere.com</span></div> -->

                                        <div class="col-md-9"><input value="" type="text" name="email_address" id="email" /> <span>Example: someone@nowhere.com</span></div>
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
                                <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>
                                    <h1>Add Client</h1>
                                <?php } ?>

                                <?php if ($user->data()->position == 2) { ?>
                                    <h1>View clients info</h1>
                                <?php } ?>

                            </div>
                            <div class="block-fluid">
                                <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">

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
                                                    <input class="validate[required]" type="text" name="firstname" id="firstname" placeholder="Type firstname..." onkeyup="fetchData()" value="<?php if ($client['firstname']) {
                                                                                                                                                                                                    print_r($client['firstname']);
                                                                                                                                                                                                }  ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input class="validate[required]" type="text" name="middlename" id="middlename" placeholder="Type middlename..." onkeyup="fetchData()" value="<?php if ($client['middlename']) {
                                                                                                                                                                                                        print_r($client['middlename']);
                                                                                                                                                                                                    }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input class="validate[required]" type="text" name="lastname" id="lastname" placeholder="Type lastname..." onkeyup="fetchData()" value="<?php if ($client['lastname']) {
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
                                                                                                                } elseif ($client['education_level'] == 8) {
                                                                                                                    echo 'N / A';
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
                                                        <option value="8">N / A</option>

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
                                                                                                                    } elseif ($client['employment_status'] == 5) {
                                                                                                                        echo 'Student';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                            <option value="1">Employed</option>
                                                            <option value="2">Self-employed</option>
                                                            <option value="3">Employed but on leave of absence</option>
                                                            <option value="4">Unemployed</option>
                                                            <option value="5">Student</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Occupational Exposures</label>
                                                        <select name="occupation" id="occupation" style="width: 100%;" onchange="checkQuestionValue1('occupation','exposure')" required>
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
                                                <!-- <input type="text" id="occupation" onkeyup="checkQuestionValue('occupation','exposure')"> -->

                                            </div>
                                            <div class="col-sm-6 hidden" id="exposure">
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
                                        <button onclick="history.go(-1);">Back </button>
                                        <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>


                                            <input type="submit" name="add_client" value="Submit" class="btn btn-default">
                                    </div>
                                <?php } ?>


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
                                    <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>
                                        <div class="footer tar">
                                            <input type="submit" name="add_study" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>

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
                                    <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>
                                        <div class="footer tar">
                                            <input type="submit" name="add_site" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 7) { ?>
                        <?php $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                        $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                        $cat = '';

                        if ($category['cardiac'] == 1) {
                            $cat = 'Cardiac';
                        } elseif ($category['diabetes'] == 1) {
                            $cat = 'Diabetes';
                        } elseif ($category['sickle_cell'] == 1) {
                            $cat = 'Sickle cell';
                        } else {
                            $cat = 'Not Diagnosed';
                        }


                        if ($patient['gender'] == 1) {
                            $gender = 'Male';
                        } elseif ($patient['gender'] == 2) {
                            $gender = 'Female';
                        }



                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] . ' Gender: ' . $gender . ' Type: ' . $cat;
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Demographic</h1>
                                <h4><strong style="font-size: larger"><?= $name ?></strong></h4>

                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">



                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Date of Visit:</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($demographic['visit_date']) {
                                                                                                                                                            print_r($demographic['visit_date']);
                                                                                                                                                        }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if (!$override->get4('clients', 'id', $_GET['cid'], 'age')) {
                                    ?>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Appropriate grade for age:</label>
                                                        <select name="grade_age" style="width: 100%;">
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
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>NCD limiting school attendance::</label>
                                                        <select name="school_attendance" style="width: 100%;">
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
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Days of missed school in past month:</label>
                                                    <input type="number" min="0" max="100" name="missed_school" id="missed_school" value="<?php if ($demographic['missed_school']) {
                                                                                                                                                print_r($demographic['missed_school']);
                                                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Household Size:</label>
                                                    <input class="" type="number" min="0" max="100" name="household_size" id="household_size" value="<?php if ($demographic['household_size']) {
                                                                                                                                                            print_r($demographic['household_size']);
                                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Patient referred from:</label>
                                                    <select id="referred" name="referred" style="width: 100%;" onchange="checkQuestionValue96('referred','referred_other')">
                                                        <option value=" <?= $demographic['referred'] ?>"><?php if ($demographic) {
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
                                                                                                                } elseif ($demographic['referred'] == 96) {
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
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 hidden" id="referred_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify:</label>
                                                    <input class="" type="text" name="referred_other" value="<?php if ($demographic['referred_other']) {
                                                                                                                    print_r($demographic['referred_other']);
                                                                                                                }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Agrees to home visits:</label>
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
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>CHW name:</label>
                                                    <input class="" type="text" name="chw" id="chw" value="<?php if ($demographic['chw']) {
                                                                                                                print_r($demographic['chw']);
                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Comments:</label>
                                                    <textarea name="comments" rows="4"><?php if ($demographic['comments']) {
                                                                                            print_r($demographic['comments']);
                                                                                        }  ?> </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer tar">

                                        <button onclick="history.go(-1);">Back </button>

                                        <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>

                                            <input type="hidden" name="sid" value="<?= $_GET['sid'] ?>">
                                            <input type="hidden" name="vday" value="<?= $_GET['vday'] ?>">
                                            <input type="submit" name="add_demographic" value="Submit" class="btn btn-default">
                                    </div>
                                <?php } ?>



                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <?php $vital = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        if ($override->get5('clients', 'id', $_GET['cid'], 'age', 25)) {
                            $height = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', 1, 'visit_code', 'EV')[0]['height'];
                        }

                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                        $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                        $cat = '';

                        if ($category['cardiac'] == 1) {
                            $cat = 'Cardiac';
                        } elseif ($category['diabetes'] == 1) {
                            $cat = 'Diabetes';
                        } elseif ($category['sickle_cell'] == 1) {
                            $cat = 'Sickle cell';
                        } else {
                            $cat = 'Not Diagnosed';
                        }


                        if ($patient['gender'] == 1) {
                            $gender = 'Male';
                        } elseif ($patient['gender'] == 2) {
                            $gender = 'Female';
                        }



                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] . ' Gender: ' . $gender . ' Type: ' . $cat;

                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>VITAL SIGNS</h1>
                                <h4><strong style="font-size: larger"><?= $name ?></strong></h4>
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
                                                    <label>Ht (cm)<?php if ($_GET['seq'] > 1) {
                                                                        echo '( unchangeable )';
                                                                    } ?></label>
                                                    <input type="text" name="height" id="height" value="<?php if ($_GET['seq'] > 1) {
                                                                                                            if ($override->get5('clients', 'id', $_GET['cid'], 'age', 25)) {
                                                                                                                print_r($height);
                                                                                                            } else {
                                                                                                                print_r($vital['height']);
                                                                                                            }
                                                                                                        } elseif ($vital['height']) {
                                                                                                            print_r($vital['height']);
                                                                                                        }  ?>" <?php if ($_GET['seq'] > 1) {
                                                                                                                    if ($override->get5('clients', 'id', $_GET['cid'], 'age', 25)) {
                                                                                                                        echo 'readonly';
                                                                                                                    }
                                                                                                                } ?> />
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
                                        <div class="col-sm-3">
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

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Systolic</label>
                                                    <input type="text" name="systolic" id="systolic" value="<?php if ($vital['systolic']) {
                                                                                                                print_r($vital['systolic']);
                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Dystolic</label>
                                                    <input type="text" name="dystolic" id="dystolic" value="<?php if ($vital['dystolic']) {
                                                                                                                print_r($vital['dystolic']);
                                                                                                            }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
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
                                    <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>

                                        <div class="footer tar">
                                            <input type="hidden" name="bmi" value="<?= $vital['bmi'] ?>">
                                            <input type="submit" name="add_vital" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>
                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 9) { ?>

                    <?php } elseif ($_GET['id'] == 10) { ?>
                        <?php $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                        $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                        $cat = '';

                        if ($category['cardiac'] == 1) {
                            $cat = 'Cardiac';
                        } elseif ($category['diabetes'] == 1) {
                            $cat = 'Diabetes';
                        } elseif ($category['sickle_cell'] == 1) {
                            $cat = 'Sickle cell';
                        } else {
                            $cat = 'Not Diagnosed';
                        }


                        if ($patient['gender'] == 1) {
                            $gender = 'Male';
                        } elseif ($patient['gender'] == 2) {
                            $gender = 'Female';
                        }



                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] . ' Gender: ' . $gender . ' Type: ' . $cat;
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>History, Symptoms, & Exam</h1>
                                <h4><strong style="font-size: larger"><?= $name ?></strong></h4>

                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">



                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Visit Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($symptoms['visit_date']) {
                                                                                                                                                            print_r($symptoms['visit_date']);
                                                                                                                                                        }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                        <div class="head clearfix">
                                            <div class="isw-ok"></div>
                                            <h1>Symptoms ( Cardiac )</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
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


                                        <div class="row">

                                            <div class="col-sm-6">
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

                                            <div class="col-sm-6">
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


                                        </div>

                                    <?php } ?>


                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Pains ( Symptoms )</h1>
                                    </div>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Chest Pain</label>
                                                        <select name="chest_pain" id="chest_pain" style="width: 100%;" onchange="checkQuestionValue1('chest_pain','score_chest_pain')">
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
                                            <div class="col-sm-6 hidden" id="score_chest_pain">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Chest Pain ):</label>
                                                        <input type="text" name="score_chest_pain" value="<?php if ($symptoms['score_chest_pain']) {
                                                                                                                print_r($symptoms['score_chest_pain']);
                                                                                                            }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>

                                        </div>

                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Headache Pains </label>
                                                        <select name="headache" id="headache" style="width: 100%;" onchange="checkQuestionValue1('headache','score_headache')">
                                                            <option value="<?= $symptoms['headache'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['headache'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['headache'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['headache'] == 3) {
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

                                            <div class="col-sm-6 hidden" id="score_headache">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Headache ):</label>
                                                        <input type="text" name="score_headache" value="<?php if ($symptoms['score_headache']) {
                                                                                                            print_r($symptoms['score_headache']);
                                                                                                        }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Abnorminal Pain</label>
                                                        <select name="abnorminal_pain" id="abnorminal_pain" style="width: 100%;" onchange="checkQuestionValue1('abnorminal_pain','score_abnorminal_pain')">
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

                                            <div class="col-sm-6 hidden" id="score_abnorminal_pain">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Abnorminal Pain ):</label>
                                                        <input type="text" name="score_abnorminal_pain" value="<?php if ($symptoms['score_abnorminal_pain']) {
                                                                                                                    print_r($symptoms['score_abnorminal_pain']);
                                                                                                                }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>

                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Upper arms Pains </label>
                                                        <select name="upper_arms" id="upper_arms" style="width: 100%;" onchange="checkQuestionValue1('upper_arms','score_upper_arms')">
                                                            <option value="<?= $symptoms['upper_arms'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['upper_arms'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['upper_arms'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['upper_arms'] == 3) {
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
                                            <div class="col-sm-6 hidden" id="score_upper_arms">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Upper arms ):</label>
                                                        <input type="text" name="score_upper_arms" value="<?php if ($symptoms['score_upper_arms']) {
                                                                                                                print_r($symptoms['score_upper_arms']);
                                                                                                            }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Lower arms Pains </label>
                                                        <select name="lower_arms" id="lower_arms" style="width: 100%;" onchange="checkQuestionValue1('lower_arms','score_lower_arms')">
                                                            <option value="<?= $symptoms['lower_arms'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['lower_arms'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['lower_arms'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['lower_arms'] == 3) {
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

                                            <div class="col-sm-6 hidden" id="score_lower_arms">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Lower arms ):</label>
                                                        <input type="text" name="score_lower_arms" value="<?php if ($symptoms['score_lower_arms']) {
                                                                                                                print_r($symptoms['score_lower_arms']);
                                                                                                            }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Waist Pains </label>
                                                        <select name="waist" id="waist" style="width: 100%;" onchange="checkQuestionValue1('waist','score_waist')">
                                                            <option value="<?= $symptoms['waist'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['waist'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['waist'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['waist'] == 3) {
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

                                            <div class="col-sm-6 hidden" id="score_waist">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Pain Score Today( Waist ):</label>
                                                        <input type="text" name="score_waist" value="<?php if ($symptoms['score_waist']) {
                                                                                                            print_r($symptoms['score_waist']);
                                                                                                        }  ?>" />
                                                    </div>
                                                    <span> ( 1 - 10 )</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Joints Pains( Specify )</label>
                                                        <select name="joints" id="joints" style="width: 100%;" onchange="checkQuestionValue1('joints','joints_hides')">
                                                            <option value="<?= $symptoms['joints'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['joints'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($symptoms['joints'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } elseif ($symptoms['joints'] == 3) {
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
                                            <div class="hidden" id="joints_hides">

                                                <div class="col-sm-4">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label>Joints( Specify )</label>
                                                            <input type="text" name="spescify_joints" value="<?php if ($symptoms['spescify_joints']) {
                                                                                                                    print_r($symptoms['spescify_joints']);
                                                                                                                }  ?>" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label>Pain Score Today ( Joints ):</label>
                                                            <input type="text" name="score_joints" value="<?php if ($symptoms['score_joints']) {
                                                                                                                print_r($symptoms['score_joints']);
                                                                                                            }  ?>" />
                                                        </div>
                                                        <span> ( 1 - 10 )</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Any Other Pain?</label>
                                                        <select name="other_pain" id="other_pain" style="width: 100%;" onchange="checkQuestionValue1('other_pain','other_pain_hides')">
                                                            <option value="<?= $symptoms['other_pain'] ?>"><?php if ($symptoms) {
                                                                                                                if ($symptoms['other_pain'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($symptoms['other_pain'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } elseif ($symptoms['other_pain'] == 3) {
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
                                            <div class="hidden" id="other_pain_hides">
                                                <div class="col-sm-4">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label>Other Pain ( Specify )</label>
                                                            <input type="text" name="spescify_other_pain" value="<?php if ($symptoms['spescify_other_pain']) {
                                                                                                                        print_r($symptoms['spescify_other_pain']);
                                                                                                                    }  ?>" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label>Pain Score Today ( Other ):</label>
                                                            <input type="text" name="score_other_pain" value="<?php if ($symptoms['score_other_pain']) {
                                                                                                                    print_r($symptoms['score_other_pain']);
                                                                                                                }  ?>" />
                                                        </div>
                                                        <span> ( 1 - 10 )</span>
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
                                                        <?php if ($_GET['seq'] == 1) { ?>
                                                            <label>HbA1C:( During enrollment )</label>
                                                        <?php } else { ?>
                                                            <label>HbA1C:( During Follow up )</label>
                                                        <?php } ?>
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
                                                        <select name="hypoglycemia_symptoms" id="hypoglycemia_symptoms" style="width: 100%;">
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
                                                        <?php if ($_GET['seq'] == 1) { ?>
                                                            <label>Severe hypoglycemia in last month?:</label>
                                                        <?php } else { ?>
                                                            <label>Severe hypoglycemia since last visit?:</label>
                                                        <?php } ?>
                                                        <select name="hypoglycemia_severe" id="hypoglycemia_severe" style="width: 100%;" onchange="checkQuestionValue1('hypoglycemia_severe','hypoglycemia__number')">
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


                                            <div class="col-sm-6 hidden" id="hypoglycemia__number">
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
                                                        <select name="thirst" id="thirst" style="width: 100%;">
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

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Increased Urination</label>
                                                        <select name="urination" id="urination" style="width: 100%;">
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

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Vision Changes</label>
                                                        <select name="vision" id="vision" style="width: 100%;">
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
                                                        <label>Vomiting</label>
                                                        <select name="vomiting" id="vomiting" style="width: 100%;">
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
                                                        <select name="weight_loss" id="weight_loss" style="width: 100%;">
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

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Difficulty Breathing</label>
                                                        <select name="breathing" id="breathing" style="width: 100%;">
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

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Any Other (Symptoms) ?</label>
                                                        <select name="other_sickle" id="other_sickle" style="width: 100%;" onchange="checkQuestionValue1('other_sickle','sickle_specify')">
                                                            <option value="<?= $symptoms['other_sickle'] ?>"><?php if ($symptoms) {
                                                                                                                    if ($symptoms['other_sickle'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($symptoms['other_sickle'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } elseif ($symptoms['other_sickle'] == 3) {
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



                                            <div class="col-sm-6 hidden" id="sickle_specify">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Other Symptoms (Specify)</label>
                                                        <input type="text" name="sickle_specify" value="<?php if ($symptoms['sickle_specify']) {
                                                                                                            print_r($symptoms['sickle_specify']);
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
                                                        <select name="edema" id="edema" style="width: 100%;">
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
                                                        <select name="lungs" id="lungs" style="width: 100%;" onchange="checkQuestionValue96('chest_pain','lungs_other')">
                                                            <option value="<?= $symptoms['lungs'] ?>"><?php if ($symptoms) {
                                                                                                            if ($symptoms['lungs'] == 1) {
                                                                                                                echo 'Clear';
                                                                                                            } elseif ($symptoms['lungs'] == 2) {
                                                                                                                echo 'Bibasilar';
                                                                                                            } elseif ($symptoms['lungs'] == 3) {
                                                                                                                echo 'Crackles';
                                                                                                            } elseif ($symptoms['lungs'] == 4) {
                                                                                                                echo 'Wheeze';
                                                                                                            } elseif ($symptoms['lungs'] == 96) {
                                                                                                                echo 'Other';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                            <option value="1">Clear</option>
                                                            <option value="2">Bibasilar</option>
                                                            <option value="3">Crackles</option>
                                                            <option value="4">Wheeze</option>
                                                            <option value="96">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 hidden" id="lungs_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other Lung specify:</label>
                                                        <input type="text" name="lungs_other" value="<?php if ($symptoms['lungs_other']) {
                                                                                                            print_r($symptoms['lungs_other']);
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
                                                        <select name="jvp" id="jvp" style="width: 100%;">
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
                                                        <select name="volume" id="volume" style="width: 100%;">
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
                                                        <select name="murmur" id="murmur" style="width: 100%;">
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
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Foot Exam</label>
                                                        <select name="foot_exam" id="foot_exam" style="width: 100%;" onchange="checkQuestionValue1('foot_exam','foot_exam_finding1')">
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

                                            <div class="col-sm-3 hidden" id="foot_exam_finding1">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Foot Exam Category</label>
                                                        <select name="foot_exam_finding" id="foot_exam_finding" style="width: 100%;" onchange="checkQuestionValue21('foot_exam_finding','foot_exam_other')">
                                                            <option value="<?= $symptoms['foot_exam_finding'] ?>"><?php if ($symptoms) {
                                                                                                                        if ($symptoms['foot_exam_finding'] == 1) {
                                                                                                                            echo 'Normal';
                                                                                                                        } elseif ($symptoms['foot_exam_finding'] == 2) {
                                                                                                                            echo 'Abnormal';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                            <option value="1">Normal</option>
                                                            <option value="2">Abnormal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 hidden" id="foot_exam_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Foot exam Finding Other:</label>
                                                        <input type="text" name="foot_exam_other" value="<?php if ($symptoms['foot_exam_other']) {
                                                                                                                print_r($symptoms['foot_exam_other']);
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
                                                        <select name="malnutrition" id="malnutrition" style="width: 100%;">
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
                                                        <select name="pallor" id="pallor" style="width: 100%;">
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
                                                        <select name="jaundice" id="jaundice" style="width: 100%;">
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
                                            <div class="col-sm-12">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Splenomegaly</label>
                                                        <select name="splenomegaly" id="splenomegaly" style="width: 100%;">
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



                                    <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>

                                        <div class="footer tar">
                                            <input type="submit" name="add_symptoms" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>

                                </form>
                            </div>

                        </div>


                    <?php } elseif ($_GET['id'] == 11) { ?>
                        <?php
                        $diagnosis = $override->get3('cardiac', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                        $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                        $cat = '';

                        if ($category['cardiac'] == 1) {
                            $cat = 'Cardiac';
                        } elseif ($category['diabetes'] == 1) {
                            $cat = 'Diabetes';
                        } elseif ($category['sickle_cell'] == 1) {
                            $cat = 'Sickle cell';
                        } else {
                            $cat = 'Not Diagnosed';
                        }


                        if ($patient['gender'] == 1) {
                            $gender = 'Male';
                        } elseif ($patient['gender'] == 2) {
                            $gender = 'Female';
                        }



                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age'] . ' Gender: ' . $gender . ' Type: ' . $cat;
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Main diagnosis ( Cardiac )</h1>
                                <h4><strong style="font-size: larger"><?= $name ?></strong></h4>

                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
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


                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hypertensive Heart Disease</label>
                                                    <select name="hypertensive_heart" id="hypertensive_heart" style="width: 100%;">
                                                        <option value="<?= $diagnosis['hypertensive_heart'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['hypertensive_heart'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($diagnosis['hypertensive_heart'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    }
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
                                                    <label> Coronary Artery Disease</label>
                                                    <select name="coronary_artery" id="coronary_artery" style="width: 100%;">
                                                        <option value="<?= $diagnosis['coronary_artery'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['coronary_artery'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($diagnosis['coronary_artery'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    }
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
                                                    <label>Severe / Uncontrolled Hypertension</label>
                                                    <select name="severe_hypertension" id="severe_hypertension" style="width: 100%;">
                                                        <option value="<?= $diagnosis['severe_hypertension'] ?>"><?php if ($diagnosis) {
                                                                                                                        if ($diagnosis['severe_hypertension'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($diagnosis['severe_hypertension'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        }
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
                                                    <label>Cardiomyopathy</label>
                                                    <select name="cardiomyopathy" id="cardiomyopathy" style="width: 100%;" onchange="checkQuestionValue1('cardiomyopathy','sub_cardiomyopathy1')">
                                                        <option value="<?= $diagnosis['cardiomyopathy'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['cardiomyopathy'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($diagnosis['cardiomyopathy'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                }
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
                                                    <label>Rheumatic Heart Disease</label>
                                                    <select name="heumatic" id="heumatic" style="width: 100%;" onchange="checkQuestionValue1('heumatic','sub_heumatic1')">
                                                        <option value="<?= $diagnosis['heumatic'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['heumatic'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($diagnosis['heumatic'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            }
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
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Congenital heart Disease</label>
                                                    <select name="congenital" id="congenital" style="width: 100%;" onchange="checkQuestionValue1('congenital','sub_congenital1')">
                                                        <option value="<?= $diagnosis['congenital'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['congenital'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($diagnosis['congenital'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            }
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
                                                    <label>Right Heart Failure</label>
                                                    <select name="heart_failure" id="heart_failure" style="width: 100%;" onchange="checkQuestionValue1('heart_failure','heart_failure1')">
                                                        <option value="<?= $diagnosis['heart_failure'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['heart_failure'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($diagnosis['heart_failure'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                }
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
                                                    <label>Pericardial disease</label>
                                                    <select name="pericardial" id="pericardial" style="width: 100%;" onchange="checkQuestionValue1('pericardial','sub_pericardial1')">
                                                        <option value="<?= $diagnosis['pericardial'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['pericardial'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($diagnosis['pericardial'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                }
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
                                                    <label>Stroke</label>
                                                    <select name="stroke" id="stroke" style="width: 100%;" onchange="checkQuestionValue1('stroke','sub_stroke1')">
                                                        <option value="<?= $diagnosis['stroke'] ?>"><?php if ($diagnosis) {
                                                                                                        if ($diagnosis['stroke'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($diagnosis['stroke'] == 2) {
                                                                                                            echo 'No';
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        }
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
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label> Arrhythmia</label>
                                                    <select name="arrhythmia" id="arrhythmia" style="width: 100%;" onchange="checkQuestionValue1('arrhythmia','sub_arrhythmia1')">
                                                        <option value="<?= $diagnosis['arrhythmia'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['arrhythmia'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($diagnosis['arrhythmia'] == 2) {
                                                                                                                echo 'No';
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            }
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
                                                    <label>Thromboembolic</label>
                                                    <select name="thromboembolic" id="thromboembolic" style="width: 100%;" onchange="checkQuestionValue1('thromboembolic','sub_thromboembolic1')">
                                                        <option value="<?= $diagnosis['thromboembolic'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['thromboembolic'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($diagnosis['thromboembolic'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                }
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
                                                    <label>Any Other diagnosis? </label>
                                                    <select name="diagnosis_other" id="diagnosis_other" style="width: 100%;" onchange="checkQuestionValue1('diagnosis_other','diagnosis_specify1')">
                                                        <option value="<?= $diagnosis['diagnosis_other'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['diagnosis_other'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($diagnosis['diagnosis_other'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    }
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 hidden" id="diagnosis_specify1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Diagnosis specify</label>
                                                    <textarea name="diagnosis_specify" rows="4"><?php if ($diagnosis['diagnosis_specify']) {
                                                                                                    print_r($diagnosis['diagnosis_specify']);
                                                                                                }  ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Sub diagnosis (Cardiac)</h1>
                                    </div>

                                    <div class="row hidden" id="sub_cardiomyopathy1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Cardiomyopathy</label>
                                                    <select name="sub_cardiomyopathy" id="sub_cardiomyopathy" style="width: 100%;" onchange="checkQuestionValue96('sub_cardiomyopathy','cardiomyopathy_other1')">
                                                        <option value="<?= $diagnosis['sub_cardiomyopathy'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['sub_cardiomyopathy'] == 1) {
                                                                                                                        echo 'Ischemic';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 2) {
                                                                                                                        echo 'Alcohol-related';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 3) {
                                                                                                                        echo 'Peripartum';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 4) {
                                                                                                                        echo 'Arrhythmia-related';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 5) {
                                                                                                                        echo 'HIV-related';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 6) {
                                                                                                                        echo 'Chemotherapy-related';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 7) {
                                                                                                                        echo 'Viral/idiopathic';
                                                                                                                    } elseif ($diagnosis['sub_cardiomyopathy'] == 96) {
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
                                                        <option value="96">Other </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 hidden" id="cardiomyopathy_other1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other specify</label>
                                                    <textarea name="cardiomyopathy_other" rows="4"><?php if ($diagnosis['cardiomyopathy_other']) {
                                                                                                        print_r($diagnosis['cardiomyopathy_other']);
                                                                                                    }  ?>
                                                                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row hidden" id="sub_heumatic1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Rheumatic Heart Disease</label>
                                                    <select name="sub_heumatic" id="sub_heumatic" style="width: 100%;" onchange="checkQuestionValue96('sub_heumatic','heumatic_other1')">
                                                        <option value="<?= $diagnosis['sub_heumatic'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['sub_heumatic'] == 1) {
                                                                                                                    echo 'Pure mitral stenosis';
                                                                                                                } elseif ($diagnosis['sub_heumatic'] == 2) {
                                                                                                                    echo 'Pure mitral regurgitation';
                                                                                                                } elseif ($diagnosis['sub_heumatic'] == 3) {
                                                                                                                    echo 'Mixed mitral valve disease (MS + MR)';
                                                                                                                } elseif ($diagnosis['sub_heumatic'] == 4) {
                                                                                                                    echo 'Isolated aortic valve disease (AVD)';
                                                                                                                } elseif ($diagnosis['sub_heumatic'] == 5) {
                                                                                                                    echo 'Mixed mitral and aortic valve disease (MMAVD)';
                                                                                                                } elseif ($diagnosis['sub_heumatic'] == 96) {
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
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 hidden" id="heumatic_other1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other specify( If heumatic Heart Disease )</label>
                                                    <textarea name="heumatic_other" rows="4"><?php if ($diagnosis['heumatic_other']) {
                                                                                                    print_r($diagnosis['heumatic_other']);
                                                                                                }  ?>
                                                                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row hidden" id="sub_congenital1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Congenital heart Disease</label>
                                                    <select name="sub_congenital" id="sub_congenital" style="width: 100%;" onchange="checkQuestionValue96('sub_congenital','congenital_other1')">
                                                        <option value="<?= $diagnosis['sub_congenital'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['sub_congenital'] == 1) {
                                                                                                                    echo 'ASD';
                                                                                                                } elseif ($diagnosis['sub_congenital'] == 2) {
                                                                                                                    echo 'VSD';
                                                                                                                } elseif ($diagnosis['sub_congenital'] == 3) {
                                                                                                                    echo 'PDA';
                                                                                                                } elseif ($diagnosis['sub_congenital'] == 4) {
                                                                                                                    echo 'Coarctation of aorta';
                                                                                                                } elseif ($diagnosis['sub_congenital'] == 5) {
                                                                                                                    echo 'Tetralogy of Fallot';
                                                                                                                } elseif ($diagnosis['sub_congenital'] == 96) {
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
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="congenital_other1" class="hidden">
                                            <div class="col-sm-8">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify( If Congenital Heart Disease )</label>
                                                        <textarea name="congenital_other" rows="4"><?php if ($diagnosis['congenital_other']) {
                                                                                                        print_r($diagnosis['congenital_other']);
                                                                                                    }  ?>
                                                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row hidden" id="heart_failure1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Right Heart Failure</label>
                                                    <select name="sub_heart_failure" id="sub_heart_failure" style="width: 100%;">
                                                        <option value="<?= $diagnosis['sub_heart_failure'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['heart_failure'] == 1) {
                                                                                                                        echo 'Chronic Lung Disease';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Chronic Lung Disease</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row hidden" id="sub_pericardial1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Pericardial disease</label>
                                                    <select name="sub_pericardial" id="sub_pericardial" style="width: 100%;" onchange="checkQuestionValue96('sub_pericardial','pericardial_other1')">
                                                        <option value="<?= $diagnosis['sub_pericardial'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['sub_pericardial'] == 1) {
                                                                                                                        echo 'Tuberculosis';
                                                                                                                    } elseif ($diagnosis['sub_pericardial'] == 2) {
                                                                                                                        echo 'HIV';
                                                                                                                    } elseif ($diagnosis['sub_pericardial'] == 3) {
                                                                                                                        echo 'malignancy';
                                                                                                                    } elseif ($diagnosis['sub_pericardial'] == 96) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Tuberculosis</option>
                                                        <option value="2">HIV</option>
                                                        <option value="3">malignancy</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="pericardial_other1" class="hidden">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify( If heumatic Heart Disease )</label>
                                                        <textarea name="pericardial_other" rows="4"><?php if ($diagnosis['pericardial_other']) {
                                                                                                        print_r($diagnosis['pericardial_other']);
                                                                                                    }  ?>
                                                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row hidden" id="sub_arrhythmia1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Arrhythmia</label>
                                                    <select name="sub_arrhythmia" id="sub_arrhythmia" style="width: 100%;" onchange="checkQuestionValue96('sub_arrhythmia','arrhythmia_other1')">
                                                        <option value="<?= $diagnosis['sub_arrhythmia'] ?>"><?php if ($diagnosis) {
                                                                                                                if ($diagnosis['sub_arrhythmia'] == 1) {
                                                                                                                    echo 'Atrial fibrillation';
                                                                                                                } elseif ($diagnosis['sub_arrhythmia'] == 96) {
                                                                                                                    echo 'Other';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Atrial fibrillation </option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="arrhythmia_other1" class="hidden">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify( If heumatic Heart Disease )</label>
                                                        <textarea name="arrhythmia_other" rows="4"><?php if ($diagnosis['arrhythmia_other']) {
                                                                                                        print_r($diagnosis['arrhythmia_other']);
                                                                                                    }  ?>
                                                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row hidden" id="sub_thromboembolic1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Thromboembolic</label>
                                                    <select name="sub_thromboembolic" id="sub_thromboembolic" style="width: 100%;" onchange="checkQuestionValue96('sub_thromboembolic','thromboembolic_other1')">
                                                        <option value="<?= $diagnosis['sub_thromboembolic'] ?>"><?php if ($diagnosis) {
                                                                                                                    if ($diagnosis['sub_thromboembolic'] == 1) {
                                                                                                                        echo 'pulmonary embolism';
                                                                                                                    } elseif ($diagnosis['sub_thromboembolic'] == 2) {
                                                                                                                        echo 'DVT';
                                                                                                                    } elseif ($diagnosis['sub_thromboembolic'] == 96) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">pulmonary embolism </option>
                                                        <option value="2">DVT</option>
                                                        <option value="96">other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden" id="thromboembolic_other1">
                                            <div class="col-sm-8">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Other specify( If Thromboembolic )</label>
                                                        <textarea name="thromboembolic_other" rows="4"><?php if ($diagnosis['thromboembolic_other']) {
                                                                                                            print_r($diagnosis['thromboembolic_other']);
                                                                                                        }  ?>
                                                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row hidden" id="sub_stroke1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Stroke</label>
                                                    <select name="sub_stroke" id="sub_stroke" style="width: 100%;">
                                                        <option value="<?= $diagnosis['sub_stroke'] ?>"><?php if ($diagnosis) {
                                                                                                            if ($diagnosis['sub_stroke'] == 1) {
                                                                                                                echo 'Ischemic';
                                                                                                            } elseif ($diagnosis['sub_stroke'] == 2) {
                                                                                                                echo 'hemorrhagic';
                                                                                                            } elseif ($diagnosis['sub_stroke'] == 3) {
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
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>General Comments</label>
                                                    <textarea name="comments" rows="4"><?php if ($diagnosis['comments']) {
                                                                                            print_r($diagnosis['comments']);
                                                                                        }  ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>
                                        <div class="footer tar">
                                            <input type="submit" name="add_cardiac" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>

                                </form>
                            </div>

                        </div>

                    <?php } ?>