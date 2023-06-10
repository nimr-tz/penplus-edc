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
                // 'age_6_above' => array(
                //     'required' => true,
                // ),
                'consent' => array(
                    'required' => true,
                ),
                // 'scd' => array(
                //     'required' => true,
                // ),
                // 'rhd' => array(
                //     'required' => true,
                // ),
                // 'residence' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $eligibility = 0;
                    if (Input::get('consent') == 1) {
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
                        // 'enrolled' => $eligibility,
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

                $summary = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', $sq, 'visit_code', $visit_code)[0];

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

                        'summary_date' => Input::get('summary_date'),
                        'comments' => Input::get('comments'),
                        'diagnosis' => Input::get('diagnosis'),
                        'diagnosis_other' => Input::get('diagnosis_other'),
                        'outcome' => Input::get('outcome'),
                        'transfer_out' => Input::get('transfer_out'),
                        'transfer_other' => Input::get('transfer_other'),
                        'cause_death' => Input::get('cause_death'),
                        'death_other' => Input::get('death_other'),
                        'next_notes' => Input::get('next_notes'),

                        'visit_window' => 0,
                        'status' => 0,
                        'client_id' => Input::get('id'),
                        'created_on' => date('Y-m-d'),
                        'seq_no' => $sq,
                        'reasons' => '',
                        'visit_status' => 0,
                    ));

                    $successMessage = 'Schedule Summary  Added Successful';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_summary2')) {
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
        } elseif (Input::get('edit_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'visit_date' => Input::get('visit_date'),
                        'status' => 1,
                        'visit_status' => 1,
                        'reasons' => Input::get('reasons'),
                    ), Input::get('id'));

                    // $client_id = $override->getNews('clients', 'id', Input::get('cid'), 'status', 1)[0];


                    if (Input::get('visit_name') == 'Study Termination Visit') {
                        $user->updateRecord('clients', array(
                            'end_study' => 1,
                        ), Input::get('cid'));
                    } else {
                        $user->updateRecord('clients', array(
                            'end_study' => 0,
                        ), Input::get('cid'));
                    }

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
                    if (Input::get('name')) {
                        if (Input::get('name') == 'user' || Input::get('name') == 'schedule' || Input::get('name') == 'study_id') {
                            $errorMessage = 'Table ' . '"' . Input::get('name') . '"' . '  can not be Cleared';
                        } else {
                            $clearData = $override->clearDataTable(Input::get('name'));
                        }
                        $successMessage = 'Table ' . '"' . Input::get('name') . '"' . ' Cleared Successfull';
                    } else {
                        $errorMessage = 'Table ' . '"' . Input::get('name') . '"' . '  can not be Found!';
                    }
                    // die;
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
                $filename = 'Registartion Data';
            } elseif (Input::get('screening')) {
                $data = $override->getData('site');
                $filename = 'screening Data';
            } elseif (Input::get('demographic')) {
                $data = $override->getData('demographic');
                $filename = 'Demographic Data';
            } elseif (Input::get('vital')) {
                $data = $override->getData('site');
                $filename = 'Vitals Sign Data';
            } elseif (Input::get('main_diagnosis')) {
                $data = $override->getData('main_diagnosis');
                $filename = 'Pateint Category Data';
            } elseif (Input::get('history')) {
                $data = $override->getData('history');
                $filename = 'Patient & Family History & Complication';
            } elseif (Input::get('symptoms')) {
                $data = $override->getData('symptoms');
                $filename = 'Symtom & Exam';
            } elseif (Input::get('diagnosis')) {
                $data = $override->getData('diagnosis');
                $filename = 'Main diagnosis 3 ( Cardiac )';
            } elseif (Input::get('diabetic')) {
                $data = $override->getData('diabetic');
                $filename = 'Main diagnosis 3 ( Diabetic )';
            } elseif (Input::get('sickle_cell')) {
                $data = $override->getData('sickle_cell');
                $filename = 'Main diagnosis 3 ( Sickle Cell )';
            } elseif (Input::get('hospitalization')) {
                $data = $override->getData('hospitalization');
                $filename = 'Hospitalization Data';
            } elseif (Input::get('treatment_plan')) {
                $data = $override->getData('treatment_plan');
                $filename = 'Treatment Plan Data';
            } elseif (Input::get('dgns_complctns_comorbdts')) {
                $data = $override->getData('dgns_complctns_comorbdts');
                $filename = 'Diagnosis, Complications, & Comorbidities Data';
            } elseif (Input::get('crf2')) {
                $data = $override->getData('crf2');
                $filename = 'CRF 2';
            } elseif (Input::get('crf3')) {
                $data = $override->getData('crf3');
                $filename = 'CRF 3';
            } elseif (Input::get('crf4')) {
                $data = $override->getData('crf4');
                $filename = 'CRF 4';
            } elseif (Input::get('crf5')) {
                $data = $override->getData('crf5');
                $filename = 'CRF 5';
            } elseif (Input::get('crf5')) {
                $data = $override->getData('crf5');
                $filename = 'CRF 5';
            } elseif (Input::get('crf6')) {
                $data = $override->getData('crf6');
                $filename = 'CRF 6';
            } elseif (Input::get('crf7')) {
                $data = $override->getData('crf7');
                $filename = 'CRF 7';
            } elseif (Input::get('herbal')) {
                $data = $override->getData('herbal_treatment');
                $filename = 'Other Herbal Treatment';
            } elseif (Input::get('medication')) {
                $data = $override->getData('other_medication');
                $filename = 'other_medication';
            } elseif (Input::get('nimregenin')) {
                $data = $override->getData('nimregenin');
                $filename = 'nimregenin';
            } elseif (Input::get('radiotherapy')) {
                $data = $override->getData('radiotherapy');
                $filename = 'radiotherapy';
            } elseif (Input::get('chemotherapy')) {
                $data = $override->getData('chemotherapy');
                $filename = 'chemotherapy';
            } elseif (Input::get('surgery')) {
                $data = $override->getData('surgery');
                $filename = 'surgery';
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
                                        $pagNum = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 2) {
                                        $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 3) {
                                        $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 4) {
                                        $pagNum = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 5) {
                                        $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 6) {
                                        $pagNum = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 7) {
                                        $pagNum = $override->getCount('clients', 'site_id', $_GET['sid']);
                                    } elseif ($_GET['status'] == 8) {
                                        $pagNum = $override->countData('clients', 'status', 0, 'site_id', $_GET['sid']);
                                    }
                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    if ($_GET['status'] == 1) {
                                        $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 2) {
                                        $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 3) {
                                        $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 4) {
                                        $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 5) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 6) {
                                        $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 7) {
                                        $clients = $override->getWithLimit('clients', 'site_id', $_GET['sid'], $page, $numRec);
                                    } elseif ($_GET['status'] == 8) {
                                        $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $_GET['sid'], $page, $numRec);
                                    }
                                } else {
                                    $pagNum = 0;
                                    if ($_GET['status'] == 1) {
                                        $pagNum = $override->getCount1('clients', 'status', 1, 'screened', 1);
                                    } elseif ($_GET['status'] == 2) {
                                        $pagNum = $override->getCount1('clients', 'status', 1, 'eligible', 1);
                                    } elseif ($_GET['status'] == 3) {
                                        $pagNum = $override->getCount1('clients', 'status', 1, 'enrolled', 1);
                                    } elseif ($_GET['status'] == 4) {
                                        $pagNum = $override->getCount1('clients', 'status', 1, 'end_study', 1);
                                    } elseif ($_GET['status'] == 5) {
                                        $pagNum = $override->getCount('clients', 'status', 1);
                                    } elseif ($_GET['status'] == 6) {
                                        $pagNum = $override->getCount1('clients', 'status', 1, 'screened', 0);
                                    } elseif ($_GET['status'] == 7) {
                                        $clients = $override->getNo('clients');
                                    } elseif ($_GET['status'] == 8) {
                                        $pagNum = $override->getCount('clients', 'status', 0);
                                    }
                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    if ($_GET['status'] == 1) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 2) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'eligible', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 3) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'enrolled', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 4) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'end_study', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 5) {
                                        $clients = $override->getWithLimit('clients', 'status', 1, $page, $numRec);
                                    } elseif ($_GET['status'] == 6) {
                                        $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 0, $page, $numRec);
                                    } elseif ($_GET['status'] == 7) {
                                        $clients = $override->getDataLimit('clients', $page, $numRec);
                                    } elseif ($_GET['status'] == 8) {
                                        $clients = $override->getWithLimit('clients', 'status', 0, $page, $numRec);
                                    }
                                }
                            } else {

                                $pagNum = 0;
                                if ($_GET['status'] == 1) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 2) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 3) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 4) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 5) {
                                    $pagNum = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 6) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 7) {
                                    $pagNum = $override->getCount('clients', 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 8) {
                                    $pagNum = $override->countData('clients', 'status', 0, 'site_id', $user->data()->site_id);
                                }
                                $pages = ceil($pagNum / $numRec);
                                if (!$_GET['page'] || $_GET['page'] == 1) {
                                    $page = 0;
                                } else {
                                    $page = ($_GET['page'] * $numRec) - $numRec;
                                }
                                if ($_GET['status'] == 1) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 2) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 3) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 4) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 5) {
                                    $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 6) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 7) {
                                    $clients = $override->getWithLimit('clients', 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 8) {
                                    $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec);
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
                                            <?php if ($_GET['status'] == 4) { ?>

                                                <th width="10%">REASON</th>
                                            <?php } else { ?>
                                                <th width="40%">ACTION</th>

                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x = 1;
                                        foreach ($clients as $client) {
                                            $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                            $visit = $override->getCount('visit', 'client_id', $client['id']);
                                            $visits = $override->get('visit', 'client_id', $client['id'])[0];
                                            $end_study = $override->getNews('screening', 'status', 1, 'patient_id', $client['id'])[0];
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

                                                <?php if ($_GET['status'] == 1) { ?>

                                                    <?php if ($client['eligible'] == 1) { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-success">Eligible</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">Not Eligible</a>
                                                        </td>
                                                <?php }
                                                } ?>

                                                <?php if ($_GET['status'] == 2) { ?>

                                                    <?php if ($client['enrolled'] == 1) { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-success">Enrolled</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">Not Enrolled</a>
                                                        </td>
                                                <?php }
                                                } ?>

                                                <?php if ($_GET['status'] == 3) { ?>

                                                    <?php if ($client['enrolled'] == 1) { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-success">Enrolled</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">Not Enrolled</a>
                                                        </td>
                                                <?php }
                                                } ?>


                                                <?php if ($_GET['status'] == 4) { ?>

                                                    <?php if ($client['end_study'] == 1) { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">END</a>
                                                        </td>

                                                        <?php if ($end_study['end_study'] == 1) { ?>
                                                            <td>
                                                                <a href="#" class="btn btn-info">Completed 120 days</a>
                                                            </td>

                                                        <?php } elseif ($end_study['end_study'] == 1) { ?>
                                                            <td>
                                                                <a href="#" class="btn btn-info">Reported Dead</a>
                                                            </td>
                                                        <?php
                                                        } elseif ($end_study['end_study'] == 1) { ?>
                                                            <td>
                                                                <a href="#" class="btn btn-info">Withdrew Consent</a>
                                                            </td>
                                                        <?php
                                                        } else { ?>
                                                            <td>
                                                                <a href="#" class="btn btn-info">Other</a>
                                                            </td>
                                                        <?php
                                                        } ?>

                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-success">ACTIVE</a>
                                                        </td>
                                                <?php }
                                                } ?>

                                                <?php if ($_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>

                                                    <?php if ($client['screened'] == 1) { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-success">SCREENED</a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">NOT SCREENED</a>
                                                        </td>
                                                <?php }
                                                } ?>




                                                <td>
                                                    <?php if ($_GET['status'] == 1 || $_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>
                                                        <a href="#clientView<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->power == 1) { ?>
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

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-8">Date of Conset</div>
                                                                        <div class="col-md-4">
                                                                            <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($screening['screening_date']) {
                                                                                                                                                                                    print_r($screening['screening_date']);
                                                                                                                                                                                }  ?>" />
                                                                            <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- <div class="row-form clearfix">
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
                                                                    </div> -->

                                                                    <!-- <div class="row-form clearfix">
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
                                                                    </div> -->

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
                                                            $summary = $override->get3('visit', 'client_id', $client['id'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];

                                                            ?>


                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Add Visit</h4>
                                                            </div>

                                                            <div class="head clearfix">
                                                                <div class="head clearfix">
                                                                    <div class="isw-ok"></div>
                                                                    <h1>Summary</h1>
                                                                </div>
                                                            </div>



                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Visit Name</label>
                                                                            <select name="visit_name" style="width: 100%;" required>
                                                                                <option value="">Select</option>
                                                                                <?php foreach ($override->getData2('schedule', 'status', 4) as $study) { ?>
                                                                                    <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Summary Date</label>
                                                                            <input class="validate[required,custom[date]]" type="text" name="summary_date" id="summary_date" value="<?php if ($summary['summary_date']) {
                                                                                                                                                                                        print_r($summary['summary_date']);
                                                                                                                                                                                    }  ?>" required />
                                                                            <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Type of diagnosis</label>
                                                                            <select name="diagnosis" id="diagnosis" style="width: 100%;" required>
                                                                                <option value="<?= $summary['diagnosis'] ?>"><?php if ($summary) {
                                                                                                                                    if ($summary['diagnosis'] == 1) {
                                                                                                                                        echo 'Type 1 Diabetes';
                                                                                                                                    } elseif ($summary['diagnosis'] == 2) {
                                                                                                                                        echo 'Type 2 Diabetes';
                                                                                                                                    } elseif ($summary['diagnosis'] == 3) {
                                                                                                                                        echo 'Cardiac';
                                                                                                                                    } elseif ($summary['diagnosis'] == 4) {
                                                                                                                                        echo 'Sickle Cell Disease ';
                                                                                                                                    } elseif ($summary['diagnosis'] == 5) {
                                                                                                                                        echo 'Respiratory';
                                                                                                                                    } elseif ($summary['diagnosis'] == 6) {
                                                                                                                                        echo 'Liver';
                                                                                                                                    } elseif ($summary['diagnosis'] == 7) {
                                                                                                                                        echo 'Kidney';
                                                                                                                                    } elseif ($summary['diagnosis'] == 8) {
                                                                                                                                        echo 'Other';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                                                </option>
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
                                                                </div>
                                                            </div>


                                                            <div class="row">

                                                                <div class="col-sm-12" id="diagnosis_other">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>If other, Specify</label>
                                                                            <input type="text" name="diagnosis_other" value="<?php if ($summary['diagnosis_other']) {
                                                                                                                                    print_r($summary['diagnosis_other']);
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
                                                                            <label>Comments</label>
                                                                            <textarea name="comments" rows="4">
                                                                                    <?php if ($summary['comments']) {
                                                                                        print_r($summary['comments']);
                                                                                    }  ?>
                                                                                </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Outcome</label>
                                                                            <select name="outcome" id="outcome" style="width: 100%;" required>
                                                                                <option value="<?= $summary['outcome'] ?>"><?php if ($summary) {
                                                                                                                                if ($summary['outcome'] == 1) {
                                                                                                                                    echo 'On treatment';
                                                                                                                                } elseif ($summary['outcome'] == 2) {
                                                                                                                                    echo 'Default';
                                                                                                                                } elseif ($summary['outcome'] == 3) {
                                                                                                                                    echo 'Stop Treatment';
                                                                                                                                } elseif ($summary['outcome'] == 4) {
                                                                                                                                    echo 'Transfer Out';
                                                                                                                                } elseif ($summary['outcome'] == 5) {
                                                                                                                                    echo 'Death';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?>
                                                                                </option>
                                                                                <option value="1">On treatment</option>
                                                                                <option value="2">Default</option>
                                                                                <option value="3">Stop Treatment</option>
                                                                                <option value="4">Transfer Out</option>
                                                                                <option value="5">Death</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-4">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Transfer Out To</label>
                                                                            <select name="transfer_out" id="transfer_out" style="width: 100%;">
                                                                                <option value="<?= $summary['transfer_out'] ?>"><?php if ($summary) {
                                                                                                                                    if ($summary['transfer_out'] == 1) {
                                                                                                                                        echo 'Other NCD clinic';
                                                                                                                                    } elseif ($summary['transfer_out'] == 2) {
                                                                                                                                        echo 'Referral hospital';
                                                                                                                                    } elseif ($summary['transfer_out'] == 3) {
                                                                                                                                        echo 'Other';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                                                </option>
                                                                                <option value="1">Other NCD clinic</option>
                                                                                <option value="2">Referral hospital</option>
                                                                                <option value="3">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="row">


                                                                <div class="col-sm-4" id="diagnosis_other">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>If other, Specify</label>
                                                                            <input type="text" name="transfer_other" value="<?php if ($summary['transfer_other']) {
                                                                                                                                print_r($summary['transfer_other']);
                                                                                                                            }  ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4" id="death">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Cause of Death</label>
                                                                            <select name="cause_death" id="cause_death" style="width: 100%;">
                                                                                <option value="<?= $summary['cause_death'] ?>"><?php if ($summary) {
                                                                                                                                    if ($summary['cause_death'] == 1) {
                                                                                                                                        echo 'NCD';
                                                                                                                                    } elseif ($summary['cause_death'] == 2) {
                                                                                                                                        echo 'Unknown';
                                                                                                                                    } elseif ($summary['cause_death'] == 3) {
                                                                                                                                        echo 'Other';
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo 'Select';
                                                                                                                                } ?>
                                                                                </option>
                                                                                <option value="1">NCD</option>
                                                                                <option value="2">Unknown</option>
                                                                                <option value="3">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4" id="diagnosis_other">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>If other, Specify</label>
                                                                            <input type="text" name="death_other" value="<?php if ($summary['death_other']) {
                                                                                                                                print_r($summary['death_other']);
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
                                                                            <label>Notes for Next Appointment</label>
                                                                            <input type="text" name="next_notes" id="next_notes" value="<?php if ($summary['next_notes']) {
                                                                                                                                            print_r($summary['next_notes']);
                                                                                                                                        }  ?>" required />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-6" id="diagnosis_other">
                                                                    <div class="row-form clearfix">
                                                                        <!-- select -->
                                                                        <div class="form-group">
                                                                            <label>Next Appointment Date</label>
                                                                            <input class="validate[required,custom[date]]" type="text" name="expected_date" id="expected_date" value="<?php if ($summary['expected_date']) {
                                                                                                                                                                                            print_r($summary['expected_date']);
                                                                                                                                                                                        }  ?>" required />
                                                                            <span>Example: 2023-01-01</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="footer tar">
                                                                <input type="submit" name="add_Schedule" value="Submit" class="btn btn-default">
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
                                    <a href="info.php?id=3&status=<?= $_GET['status'] ?>&sid=<?= $_GET['sid'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
                                                                                                                            echo $_GET['page'] - 1;
                                                                                                                        } else {
                                                                                                                            echo 1;
                                                                                                                        } ?>" class="btn btn-default">
                                        < </a>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <a href="info.php?id=3&status=<?= $_GET['status'] ?>&sid=<?= $_GET['sid'] ?>&page=<?= $i ?>" class="btn btn-default <?php if ($i == $_GET['page']) {
                                                                                                                                                                        echo 'active';
                                                                                                                                                                    } ?>"><?= $i ?></a>
                                            <?php } ?>
                                            <a href="info.php?id=3&status=<?= $_GET['status'] ?>&sid=<?= $_GET['sid'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
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

                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <!-- select -->
                                                                                        <div class="form-group">
                                                                                            <label>Visit Name</label>
                                                                                            <select name="visit_name" style="width: 100%;" required>
                                                                                                <option value="">Select</option>
                                                                                                <?php foreach ($override->getData2('schedule', 'status', 4) as $study) { ?>
                                                                                                    <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <!-- select -->
                                                                                        <div class="form-group">
                                                                                            <label>Visit Date</label>
                                                                                            <input value="<?php if ($visit['status'] != 0) {
                                                                                                                echo $visit['visit_date'];
                                                                                                            } ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" />
                                                                                            <span>Example: 2010-12-01</span>
                                                                                        </div>
                                                                                    </div>
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
                                            <td>Registered Clients</td>
                                            <td>
                                                <form method="post"><input type="submit" name="clients" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Screened Clients</td>
                                            <td>
                                                <form method="post"><input type="submit" name="screening" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Demographic Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="demographic" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Vital Sign Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="vital" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td> Patient Categories Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="main_diagnosis" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td> Patient & Family History & Complication</td>
                                            <td>
                                                <form method="post"><input type="submit" name="history" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td> Symtom & Exam</td>
                                            <td>
                                                <form method="post"><input type="submit" name="symptoms" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Main diagnosis 3 ( Cardiac ) </td>
                                            <td>
                                                <form method="post"><input type="submit" name="diagnosis" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>9</td>
                                            <td>Main diagnosis 3 ( Diabetes ) </td>
                                            <td>
                                                <form method="post"><input type="submit" name="diabetic" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td>Main diagnosis 3 ( Sickle Cell ) </td>
                                            <td>
                                                <form method="post"><input type="submit" name="sickle_cell" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>Hospitalization Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="hospitalization" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>Treatment Plan Data
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="treatment_plan" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>Diagnosis, Complications, & Comorbidities Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="dgns_complctns_comorbdts" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>RISK</td>
                                            <td>
                                                <form method="post"><input type="submit" name="crf4" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td>Hospitalization Details
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="crf5" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td>Lab Details
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="crf6" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>17</td>
                                            <td>Socioeconomic Status
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="cr7" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>18</td>
                                            <td>Study IDs</td>
                                            <td>
                                                <form method="post"><input type="submit" name="study_id" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>19</td>
                                            <td>Sites</td>
                                            <td>
                                                <form method="post"><input type="submit" name="sites" value="Download"></form>
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
                                                <?php if ($override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php }  ?>

                                        <tr>
                                            <td>2</td>
                                            <td>Vitals</td>
                                            <?php if ($override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>Pateint Category</td>
                                            <?php if ($override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>Patient Hitory & Family History & Complication</td>
                                            <?php if ($override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td>Symtom & Exam</td>
                                            <?php if ($override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success" disabled> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning" disabled> Add </a> </td>
                                            <?php } ?>
                                        </tr>


                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                            <tr>
                                                <td>5</td>
                                                <td>Main diagnosis 1 ( Cardiac )</td>
                                                <?php if ($override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>

                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                            <tr>
                                                <td>5</td>
                                                <td>Main diagnosis 2 ( Diabetes )</td>
                                                <?php if ($override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=21&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=21&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>


                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>


                                            <tr>
                                                <td>5</td>
                                                <td>Main diagnosis 3 ( Sickle Cell )</td>
                                                <?php if ($override->get3('diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php } ?>

                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>


                                            <tr>
                                                <td>6</td>
                                                <td>Results at enrollment</td>
                                                <?php if ($override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>

                                            </tr>

                                        <?php } ?>


                                        <tr>
                                            <td>7</td>
                                            <td>Hospitalization</td>
                                            <?php if ($override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>8</td>
                                            <td>Treatment Plan</td>
                                            <?php if ($override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>9</td>
                                            <td>Diagnosis, Complications, & Comorbidities</td>
                                            <?php if ($override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td>RISK</td>
                                            <?php if ($override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>Hospitalization Details</td>
                                            <?php if ($override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>Lab Details</td>
                                            <?php if ($override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>12</td>
                                            <td>Socioeconomic Status</td>
                                            <?php if ($override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>



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
                                            <select name="name" id="name" style="width: 100%;" required>
                                                <option value="">Select Table Name</option>
                                                <option value="clients">clients</option>
                                                <option value="demographic">Demographic</option>
                                                <option value="vital">Vitals</option>
                                                <option value="history">Patient Hitory & Complication</option>
                                                <option value="symptoms">Family History(Symtom & Exam)</option>
                                                <option value="main_diagnosis">Pateint Category</option>
                                                <option value="diagnosis">Main diagnosis</option>
                                                <option value="results">Results at enrollment</option>
                                                <option value="hospitalization">Hospitalization</option>
                                                <option value="treatment_plan">Treatment Plan</option>
                                                <option value="dgns_complctns_comorbdts">Diagnosis, Complications, & Comorbidities</option>
                                                <option value="risks">RISK</option>
                                                <option value="hospitalization_details">Hospitalization Details</option>
                                                <option value="lab_details">Lab Details</option>
                                                <option value="summary">Summary</option>
                                                <option value="social_economic">Social Economic</option>
                                                <option value="visit">visit</option>
                                                <option value="user">user</option>
                                                <option value="unscheduled">unscheduled</option>
                                                <option value="study_id">study_id</option>
                                                <option value="sickle_cell">sickle_cell</option>
                                                <option value="screening">screening</option>
                                                <option value="diabetic">diabetic</option>
                                                <option value="schedule">schedule</option>
                                            </select>
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