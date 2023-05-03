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
                        Redirect::to('info.php?id=3');
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
        } elseif (Input::get('add2_diagnosis22')) {
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
                            'study_id' => Input::get('sid'),
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
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $history = $override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($history) {
                        $user->updateRecord('history', array(
                            'visit_date' => Input::get('visit_date'),
                            'disease' => Input::get('disease'),
                            'hiv' => Input::get('hiv'),
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
                            'surgery_other' => Input::get('surgery_other'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ), $history['id']);
                    } else {
                        $user->createRecord('history', array(
                            'visit_date' => Input::get('visit_date'),
                            'study_id' => Input::get('sid'),
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'disease' => Input::get('disease'),
                            'hiv' => Input::get('hiv'),
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
                            'surgery_other' => Input::get('surgery_other'),
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
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                    if ($symptoms) {
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
                    $user->createRecord('results', array(
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
                        'echo_other2' => Input::get('echo_other2'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));


                    $successMessage = 'Results added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                    $user->createRecord('hospitalization', array(
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
                    ));


                    $successMessage = 'Hospitalization added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                    $user->createRecord('lab_details', array(
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
                        'lab_ecg' => Input::get('lab_ecg'),
                        'lab_ecg_other' => Input::get('lab_ecg_other'),
                        'cardiac_surgery' => Input::get('cardiac_surgery'),
                        'cardiac_surgery_type' => Input::get('cardiac_surgery_type'),
                        'patient_id' => $_GET['cid'],
                        'staff_id' => $user->data()->id,
                        'status' => 1,
                        'created_on' => date('Y-m-d'),
                        'site_id' => $user->data()->site_id,
                    ));


                    $successMessage = 'Lab details added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                print_r($_POST);
                try {
                    $user->createRecord('hospitalization_details', array(
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


                    $successMessage = 'Hospitalization details added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                    $user->createRecord('risks', array(
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


                    $successMessage = 'Risks details added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                    $user->createRecord('dgns_complctns_comorbdts', array(
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
                    ));


                    $successMessage = 'Diagnosis details added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                    $user->createRecord('summary', array(
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


                    $successMessage = 'Visit Summary  details added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_treatment_plan')) {
            $validate = $validate->check($_POST, array(
                'asa' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('treatment_plan', array(
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


                    $successMessage = 'Treatment plan added Successful';
                    Redirect::to('info.php?id=8&cid=' . $_GET['cid']);
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
                                                    <label>Hospital ID Number</label>
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
                                                    <label>Phone Number</label>
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
                                                    <label>Relation to patient</label>
                                                    <input class="" type="text" name="relation_patient" id="relation_patient" value="<?php if ($client['relation_patient']) {
                                                                                                                                            print_r($client['relation_patient']);
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
                                                    <label>Physical Address</label>
                                                    <input class="" type="text" name="physical_address" id="physical_address" value="<?php if ($client['physical_address']) {
                                                                                                                                            print_r($client['physical_address']);
                                                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Comments:</label>
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

                                    <?php if (!$override->get4('clients', 'id', $_GET['cid'], 'age')) { ?>

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

                                        <?php } ?>


                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Patient referred from:</div>
                                            <div class="col-md-9">
                                                <select name="referred" style="width: 100%;" required>
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
                                            <!-- select -->
                                            <div class="form-group">
                                                <label>BMI:</label>
                                                <input type="text" name="bmi" id="bmi" value="<?php if ($vital['bmi']) {
                                                                                                    print_r($vital['bmi']);
                                                                                                }  ?>" />
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

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Vital Signs Date</label>
                                                    <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($history['visit_date']) {
                                                                                                                                                            print_r($history['visit_date']);
                                                                                                                                                        }  ?>" />
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>Diseases History</label>
                                                    <select name="disease" style="width: 100%;" required>
                                                        <option value="<?= $history['disease'] ?>"><?php if ($history) {
                                                                                                        if ($history['disease'] == 1) {
                                                                                                            echo 'Male';
                                                                                                        } elseif ($history['disease'] == 2) {
                                                                                                            echo 'Female';
                                                                                                        } elseif ($history['disease'] == 3) {
                                                                                                            echo 'Female';
                                                                                                        } elseif ($history['disease'] == 4) {
                                                                                                            echo 'Female';
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

                                        <div class="col-sm-3">
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


                                        <div class="col-sm-3" id="art_date">
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
                                                        <option value="<?= $history['disease'] ?>"><?php if ($history) {
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
                                        <div class="col-sm-4">
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
                                                    <span>Example: 2010-12-01</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
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

                                        <div class="col-sm-4">
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

                                        <div class="col-sm-4" id="surgery_other">
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
                                <h1>Symptoms & Exam</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

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
                                        <div class="col-sm-3">
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

                                        <div class="col-sm-3">
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
                                                        <option value="<?= $symptoms['edema'] ?>"><?php if ($symptoms) {
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
                                    </div>


                                    <div class="row">

                                        <div class="col-sm-3" id="Other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other specify:</label>
                                                    <input type="text" name="Other" id="Other" value="<?php if ($symptoms['Other']) {
                                                                                                            print_r($symptoms['Other']);
                                                                                                        }  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <label>JVP</label>
                                                    <select name="jvp" id="jvp" style="width: 100%;" required>
                                                        <option value="<?= $symptoms['cough'] ?>"><?php if ($symptoms) {
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

                                        <div class="col-sm-3">
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

                                        <div class="col-sm-3">
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
                                <h1>Main diagnosis</h1>
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
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Results at enrollment</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ECG Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="ecg_date" id="ecg_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ECG:</div>
                                        <div class="col-md-9">
                                            <select name="ecg" id="ecg" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Single lead or</option>
                                                <option value="2">12 lead</option>
                                                <option value="3">Normal sinus rhythm</option>
                                                <option value="4">Atrial fibrillation</option>
                                                <option value="5">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="ecg_other">
                                        <div class="col-md-3">Other specify:</div>
                                        <div class="col-md-9"><textarea name="ecg_other" rows="4"></textarea> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Echo Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="echo_date" id="echo_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Echo:(Normal)</div>
                                        <div class="col-md-9">
                                            <select name="echo" id="echo" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">LV severely depressed</div>
                                        <div class="col-md-9">
                                            <select name="lv" id="lv" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Mitral stenosis</div>
                                        <div class="col-md-9">
                                            <select name="mitral" id="mitral" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">RV severely dilated</div>
                                        <div class="col-md-9">
                                            <select name="rv" id="rv" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Pericardial effusion</div>
                                        <div class="col-md-9">
                                            <select name="pericardial" id="pericardial" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">IVC dilated,collapse less than 50% </div>
                                        <div class="col-md-9">
                                            <select name="ivc" id="ivc" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Other Echo</div>
                                        <div class="col-md-9">
                                            <select name="echo_other1" id="echo_other1" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="echo_other">
                                        <div class="col-md-3">Other specify:</div>
                                        <div class="col-md-9"><textarea name="echo_other" rows="4"></textarea> </div>
                                    </div>

                                    <div class="row-form clearfix" id="echo_other2">
                                        <div class="col-md-3">Other</div>
                                        <div class="col-md-9">
                                            <select name="echo_other2" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unseen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_results" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 19) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Hospitalizations , School and Management at Home</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Any recent hospitalizations not yet recorded?</div>
                                        <div class="col-md-9">
                                            <select name="hospitalizations" id="hospitalizations" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="ncd_hospitalizations">
                                        <div class="col-md-3">If yes, for NCD?</div>
                                        <div class="col-md-9">
                                            <select name="ncd_hospitalizations" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Number of hospitalization from NCD in last 12 months:</div>
                                        <div class="col-md-9"><input value="" type="text" name="hospitalization_number" id="hospitalization_number" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Number of missed days of school in the last month?:</div>
                                        <div class="col-md-9"><input value="" type="text" name="missed_days" id="missed_days" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">How many days of school in the last month?:</div>
                                        <div class="col-md-9"><input value="" type="text" name="school_days" id="school_days" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Fluid restricted?</div>
                                        <div class="col-md-9">
                                            <select name="echo" id="fluid" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_hospitalizaion" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 20) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>TREATMMENT PLAN (Medications & Support)</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
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
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_asa" id="dose_asa" />
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
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_furosemide" id="dose_furosemide" />
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
                                                    <input value="" type="text" name="ace_i" id="ace_i" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Action:</label>
                                                    <select name="action_ace_i" id="action_ace_i" style="width: 100%;" required>
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_ace_i" id="dose_ace_i" />
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
                                                    <input value="" type="text" name="beta_blocker" id="beta_blocker" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Action:</label>
                                                    <select name="action_beta_blocker" id="action_beta_blocker" style="width: 100%;" required>
                                                        <option value="">Select</option>
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
                                                    <input value="" type="number" name="dose_beta_blocker" id="dose_beta_blocker" />
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
                                                    <input value="" type="text" name="anti_hypertensive" id="anti_hypertensive" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Action:</label>
                                                    <select name="action_anti_hypertensive" id="action_anti_hypertensive" style="width: 100%;" required>
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_anti_hypertensive" id="dose_anti_hypertensive" />
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
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_benzathine" id="dose_benzathine" />
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
                                                    <input value="" type="text" name="anticoagulation" id="anticoagulation" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Action:</label>
                                                    <select name="action_anticoagulation" id="action_anticoagulation" style="width: 100%;" required>
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_anticoagulation" id="dose_anticoagulation" />
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
                                                    <input value="" type="text" name="medication_other" id="medication_other" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Action:</label>
                                                    <select name="action_medication_other" id="action_medication_other" style="width: 100%;" required>
                                                        <option value="">Select</option>
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
                                                    <input value="" class="validate[required]" type="number" name="dose_medication_other" id="dose_medication_other" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Diet and Fluid restriction(Salt):</label>
                                                    <input value="" type="text" name="salt" id="salt" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Diet and Fluid restriction(Fluid):</label>
                                                    <input value="" type="text" name="fluid" id="fluid" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Diet and Fluid restriction(Other):</label>
                                                    <input value="" type="text" name="restriction_other" id="restriction_other" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Social support provided?:</label>
                                                    <select name="social_support" id="social_support" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
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
                                                    <label>Type:</label>
                                                    <input value="" style="width: 100%;" type="text" name="social_support_type" id="social_support_type" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cardiology referral:</label>
                                                    <select name="cardiology" id="cardiology" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
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
                                                    <label>Date Completed:</label>
                                                    <input value="" style="width: 100%;" type="text" name="cardiology_date" id="cardiology_date" />
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
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
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
                                                    <label>Any new referrals provided?:</label>
                                                    <select name="new_referrals" id="new_referrals" style="width: 100%;" required>
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
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
                                                    <label>Type:</label>
                                                    <input value="" style="width: 100%;" type="text" name="new_referrals_type" id="new_referrals_type" />
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
                                                    <input value="" style="width: 100%;" type="text" name="medication_notes" id="medication_notes" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>


                            <div class="footer tar">
                                <input type="submit" name="add_treatment_plan" value="Submit" class="btn btn-default">
                            </div>
                            </form>
                        </div>
                    <?php } elseif ($_GET['id'] == 21) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diagnosis, Complications, & Comorbidities</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">date</div>
                                        <div class="col-md-9"><input value="" type="text" name="diagns_date" id="diagns_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Has diagnosis been changed or specified?</div>
                                        <div class="col-md-9">
                                            <select name="diagns_changed" id="diagns_changed" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="ncd_diagns">
                                        <div class="col-md-3">If yes, what is the NCD diagnosis?</div>
                                        <div class="col-md-9">
                                            <select name="ncd_diagns" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Cardiomyopathy</option>
                                                <option value="2">Rheumatic heart disease</option>
                                                <option value="2">Severe / Uncontrolled HTN</option>
                                                <option value="2">Hypertensive / Heart Disease</option>
                                                <option value="2">Congenital Heart Disease</option>
                                                <option value="2">Right Heart Failure</option>
                                                <option value="2">Pericardial</option>
                                                <option value="2">Coronary Artery Disease</option>
                                                <option value="2">Arrhythmia</option>
                                                <option value="2">Thromboembolism</option>
                                                <option value="2">Stroke</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Specify diagnosis</div>
                                        <div class="col-md-9"><input value="" type="text" name="ncd_diagns_specify" id="ncd_diagns_specify" required /> </div>
                                    </div>

                                    <div class="row-form clearfix" id="diagns_complication">
                                        <div class="col-md-3">New complications</div>
                                        <div class="col-md-9">
                                            <select name="diagns_complication" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Hypertension</option>
                                                <option value="2">Diabetes</option>
                                                <option value="2">CKD</option>
                                                <option value="2">Depression</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_dgns_complctns_comorbdts" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 22) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>RISK</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">date</div>
                                        <div class="col-md-9"><input value="" type="text" name="risk_date" id="risk_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Tobacco</div>
                                        <div class="col-md-9">
                                            <select name="risk_tobacco" id="risk_tobacco" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes, currently</option>
                                                <option value="2">Yes, in the past</option>
                                                <option value="3">never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Alcohol</div>
                                        <div class="col-md-9">
                                            <select name="risk_alcohol" id="risk_alcohol" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes, currently</option>
                                                <option value="2">Yes, in the past</option>
                                                <option value="3">never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Employment status</div>
                                        <div class="col-md-9">
                                            <select name="risk_employment" id="risk_employment" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Employed</option>
                                                <option value="2">Self-employed</option>
                                                <option value="3">Unemployed</option>
                                                <option value="3">Leave of absence</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">NCD limiting school?</div>
                                        <div class="col-md-9">
                                            <select name="ncd_limiting" id="ncd_limiting" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">N/A</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Socioeconomic question</div>
                                        <div class="col-md-9"><input value="" type="text" name="social_economic" id="social_economic" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">date</div>
                                        <div class="col-md-9"><input value="" type="text" name="risk_hiv_date" id="risk_hiv_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Last HIV test?</div>
                                        <div class="col-md-9">
                                            <select name="risk_hiv" id="risk_hiv" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">R</option>
                                                <option value="2">RN</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="risk_art_date">
                                        <div class="col-md-3">ART start date</div>
                                        <div class="col-md-9"><input value="" type="text" name="risk_art_date" /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">date</div>
                                        <div class="col-md-9"><input value="" type="text" name="risk_tb_date" id="risk_tb_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix" id="risk_tb">
                                        <div class="col-md-3">Last TB screening</div>
                                        <div class="col-md-9">
                                            <select name="risk_tb" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="1">Positive : Smear / Xpert / Other</option>
                                                <option value="2">Negative : Smear / Xpert / Other</option>
                                                <option value="3">EPTB</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_risks" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 23) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Hospitalizazions Details</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">date</div>
                                        <div class="col-md-9"><input value="" type="text" name="hospitalization_date" id="hospitalization_date" required /> </div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Hospitalized in the last year for this NCD?</div>
                                        <div class="col-md-9">
                                            <select name="hospitalization_ncd" id="hospitalization_ncd" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                                <option value="3">Unknown</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="hospitalization_year">
                                        <div class="col-md-3">If yes , Number of hospitalizations in past year</div>
                                        <div class="col-md-9"><input value="" type="text" name="hospitalization_year" /> </div>
                                    </div>

                                    <div class="row-form clearfix" id="hospitalization_day">
                                        <div class="col-md-3">If yes , Number of hospital days in past year</div>
                                        <div class="col-md-9"><input value="" type="text" name="hospitalization_day" /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Reason for admission</div>
                                        <div class="col-md-9"><input value="" type="text" name="admission_reason" id="admission_reason" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Discharge Diagnosis</div>
                                        <div class="col-md-9"><input value="" type="text" name="discharge_diagnosis" id="discharge_diagnosis" required /> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_hospitalization_details" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 24) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Lab Details</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Date</div>
                                        <div class="col-md-9"><input value="" type="text" name="lab_date" id="lab_date" required /> </div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">NCD coping</div>
                                        <div class="col-md-9">
                                            <select name="ncd_coping" id="ncd_coping" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Well</option>
                                                <option value="2">Some problems</option>
                                                <option value="3">Poor</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Family planning</div>
                                        <div class="col-md-9">
                                            <select name="family_planning" id="family_planning" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Not eligible</option>
                                                <option value="2">Not interested</option>
                                                <option value="3">Currently using</option>
                                                <option value="3">referred</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Na</div>
                                        <div class="col-md-9"><input value="" type="text" name="na" id="na" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">K</div>
                                        <div class="col-md-9"><input value="" type="text" name="k" id="k" required /> </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">BUN</div>
                                        <div class="col-md-9"><input value="" type="text" name="bun" id="bun" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Cre</div>
                                        <div class="col-md-9"><input value="" type="text" name="cre" id="cre" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">BNP</div>
                                        <div class="col-md-9"><input value="" type="text" name="bnp" id="bnp" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">INR</div>
                                        <div class="col-md-9"><input value="" type="text" name="inr" id="inr" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">lab_Other:</div>
                                        <div class="col-md-9"><input value="" type="text" name="lab_Other" id="lab_Other" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">ECG</div>
                                        <div class="col-md-9">
                                            <select name="lab_ecg" id="lab_ecg" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">NSR</option>
                                                <option value="2">Other</option>
                                                <option value="3">Afib</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="lab_ecg_other">
                                        <div class="col-md-3">Specify:</div>
                                        <div class="col-md-9"><input value="" type="text" name="lab_ecg_other" /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Cardiac surgery / intervention?</div>
                                        <div class="col-md-9">
                                            <select name="cardiac_surgery" id="cardiac_surgery" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="cardiac_surgery_type">
                                        <div class="col-md-3">Type:</div>
                                        <div class="col-md-9"><input value="" type="text" name="cardiac_surgery_type" /> </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_lab_details" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 25) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Diagnosis</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Summary Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="summary_date" id="summary_date" required /> <span>Example: 2023-01-01</span></div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Type of diagnosis:</div>
                                        <div class="col-md-9">
                                            <select name="diagnosis" style="width: 100%;" required>
                                                <option value="">Select</option>
                                                <option value="1">Type 1 Diabetes</option>
                                                <option value="2">Type 2 Diabetes </option>
                                                <option value="3">Cardiac</option>
                                                <option value="4">Sickle Cell Disease </option>
                                                <option value="5">Respiratory</option>
                                                <option value="6">Liver</option>
                                                <option value="7">Kidney</option>
                                                <option value="8">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix" id="diagnosis_other">
                                        <div class="col-md-3">If other, Specify:</div>
                                        <div class="col-md-9"><input value="" type="text" name="diagnosis_other" /></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Comments:</div>
                                        <div class="col-md-9"><textarea name="comments" rows="4"></textarea> </div>
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

                                    <div class="row-form clearfix" id="transfer_other">
                                        <div class="col-md-3">If other, Specify:</div>
                                        <div class="col-md-9"><input value="" type="text" name="transfer_other" /></div>
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

                                    <div class="row-form clearfix" id="death_other">
                                        <div class="col-md-3">If other, Specify:</div>
                                        <div class="col-md-9"><input value="" type="text" name="death_other" /></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Notes for Next Appointment:</div>
                                        <div class="col-md-9"><input value="" type="text" name="next_appointment_notes" id="next_appointment_notes" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Next Appointment Date:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="next_appointment" id="next_appointment" required /> <span>Example: 2023-01-01</span></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_summary" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 26 && $user->data()->position == 1) { ?>


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


        $('#referred_other').hide();
        $('#referred').change(function() {
            var getUid = $(this).val();
            if (getUid === "7") {
                $('#referred_other').show();
            } else {
                $('#referred_other').hide();
            }
        });

        $('#art_date').hide();
        $('#hiv').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#art_date').show();
            } else {
                $('#art_date').val("NULL");
                $('#art_date').hide();
            }
        });

        $('#tb_year').hide();
        $('#tb').change(function() {
            var getUid = $(this).val();
            if (getUid != "4") {
                $('#tb_year').show();
            } else {
                $('#tb_year').val("NULL");
                $('#tb_year').hide();
            }
        });

        $('#active_smoker').hide();
        $('#packs').hide();
        $('#smoking').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#active_smoker').show();
                $('#packs').show();
            } else {
                $('#active_smoker').val("NULL");
                $('#packs').val("NULL");
                $('#active_smoker').hide();
                $('#packs').hide();
            }
        });

        $('#quantity').hide();
        $('#alcohol').change(function() {
            var getUid = $(this).val();
            if (getUid != "3") {
                $('#quantity').show();
            } else {
                $('#quantity').val("NULL");
                $('#quantity').hide();
            }
        });

        $('#surgery_other').hide();
        $('#cardiac_surgery').change(function() {
            var getUid = $(this).val();
            if (getUid === "1") {
                $('#surgery_other').show();
            } else {
                $('#surgery_other').val("NULL");
                $('#surgery_other').hide();
            }
        });

        $('#Other').hide();
        $('#lungs').change(function() {
            var getUid = $(this).val();
            if (getUid === "5") {
                $('#Other').show();
            } else {
                $('#Other').hide();
            }
        });


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