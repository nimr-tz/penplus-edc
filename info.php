<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec = 50;
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
        } elseif (Input::get('delete_client')) {
            $user->updateRecord('clients', array(
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
                'screening_date' => array(
                    'required' => true,
                ),
                // 'conset_date' => array(
                //     'required' => true,
                // ),
                'ncd' => array(
                    'required' => true,
                ),
                'dm' => array(
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
                    if ((Input::get('consent') == 1 && Input::get('residence') == 1) && (Input::get('ncd') == 1 || Input::get('dm') == 1 || Input::get('scd') == 1 || Input::get('rhd') == 1)) {
                        $eligibility = 1;
                    }

                    if ($override->get('screening', 'patient_id', Input::get('id'))) {
                        $user->updateRecord('screening', array(
                            'screening_date' => Input::get('screening_date'),
                            'conset_date' => Input::get('conset_date'),
                            // 'study_id' => '',
                            'dm' => Input::get('dm'),
                            'ncd' => Input::get('ncd'),
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

                        $visit = $override->getNews('visit', 'client_id', Input::get('id'), 'seq_no', 0, 'visit_name', 'Screening')[0];

                        $user->updateRecord('visit', array(
                            'expected_date' => Input::get('screening_date'),
                            'visit_date' => Input::get('screening_date'),
                        ), $visit['id']);
                    } else {
                        $user->createRecord('screening', array(
                            'screening_date' => Input::get('screening_date'),
                            'conset_date' => Input::get('conset_date'),
                            'consent' => Input::get('consent'),
                            'ncd' => Input::get('ncd'),
                            'study_id' => '',
                            'dm' => Input::get('dm'),
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
                            'expected_date' => Input::get('screening_date'),
                            'visit_date' => Input::get('screening_date'),
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
                $visit = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', Input::get('visit_name'));
                $visit_id = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', Input::get('visit_name'))[0];

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
                    $user->updateRecord('visit', array('expected_date' => Input::get('visit_date'), 'reasons' => Input::get('reasons')), $visit_id['id']);

                    // $errorMessage = 'Visit with the same Date ana Name already exists for this Client';
                } else {
                    $user->createRecord('visit', array(
                        'study_id' => $study_id,
                        'visit_name' => Input::get('visit_name'),
                        'visit_code' => $visit_code,
                        'visit_day' => 'Day 1',
                        'expected_date' => Input::get('visit_date'),
                        'visit_date' => '',
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
        }
        // elseif (Input::get('edit_Enrollment')) {
        //     $validate = $validate->check($_POST, array(
        //         'visit_date' => array(
        //             'required' => true,
        //         ),
        //     ));
        //     if ($validate->passed()) {
        //         $client_study = $override->getNews('clients', 'id', Input::get('id'), 'status', 1)[0];
        //         $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
        //         $screening_id = $override->getNews('screening', 'patient_id', Input::get('id'), 'status', 1)[0];
        //         $visit_id = $override->get('visit', 'client_id', Input::get('id'))[0];
        //         $last_visit = $override->getlastRow('visit', 'client_id', Input::get('id'), 'id')[0];
        //         $visit = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', Input::get('visit_name'));
        //         $visit_id = $override->get3('visit', 'client_id', Input::get('id'), 'seq_no', 1, 'visit_name', Input::get('visit_name'))[0];

        //         if (!$client_study['study_id']) {
        //             $study_id = $std_id['study_id'];
        //         } else {
        //             $study_id = $client_study['study_id'];
        //         }

        //         if (Input::get('visit_name') == 'Registration Visit') {
        //             $visit_code = 'RV';
        //         } elseif (Input::get('visit_name') == 'Screening Visit') {
        //             $visit_code = 'SV';
        //         } elseif (Input::get('visit_name') == 'Enrollment Visit') {
        //             $visit_code = 'EV';
        //         } elseif (Input::get('visit_name') == 'Follow Up Visit') {
        //             $visit_code = 'FV';
        //         } elseif (Input::get('visit_name') == 'Study Termination Visit') {
        //             $visit_code = 'TV';
        //         } elseif (Input::get('visit_name') == 'Unschedule Visit') {
        //             $visit_code = 'UV';
        //         }



        //         $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
        //         // $enrollment_date = $override->get('clients', 'id', Input::get('id'))[0];
        //         $visit_date = $override->firstRow2('visit', 'visit_date', 'id', 'client_id', Input::get('id'),'seq_no', 1, 'visit_name', Input::get('visit_name'))[0];
        //         if ($override->get('visit', 'client_id', Input::get('id'))) {
        //             if (Input::get('visit_date') != $visit_date['visit_date']) {
        //                 $user->deleteRecord('visit', 'client_id', Input::get('id'));
        //                 $user->createRecord('visit', array(
        //                     'study_id' => $study_id,
        //                     'visit_name' => Input::get('visit_name'),
        //                     'visit_code' => $visit_code,
        //                     'visit_day' => 'Day 1',
        //                     'expected_date' => Input::get('visit_date'),
        //                     'visit_date' => '',
        //                     'visit_window' => 0,
        //                     'status' => 0,
        //                     'client_id' => Input::get('id'),
        //                     'created_on' => date('Y-m-d'),
        //                     'seq_no' => 1,
        //                     'reasons' => Input::get('reasons'),
        //                     'visit_status' => 1,
        //                 ));
        //             }

        //             // if (!$client_study['study_id']) {
        //             //     $user->updateRecord('screening', array('study_id' => $std_id['study_id']), $screening_id['id']);
        //             //     $user->updateRecord('clients', array('study_id' => $std_id['study_id'], 'enrolled' => 1), Input::get('id'));
        //             //     $user->updateRecord('study_id', array('status' => 1, 'client_id' => Input::get('id')), $std_id['id']);
        //             // } else {
        //             //     $user->updateRecord('screening', array('study_id' => $client_study['study_id']), $screening_id['id']);
        //             //     $user->updateRecord('clients', array('study_id' => $client_study['study_id'], 'enrolled' => 1), Input::get('id'));
        //             // }
        //         }
        //         $successMessage = 'Enrollment  Added Successful';
        //         Redirect::to('info.php?id=3&status=3');
        //     } else {
        //         $pageError = $validate->errors();
        //     }
        // }
        elseif (Input::get('add_Schedule')) {
            $validate = $validate->check($_POST, array(
                'expected_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $client_study = $override->getNews('clients', 'id', $_GET['cid'], 'status', 1)[0];
                $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
                $screening_id = $override->getNews('screening', 'patient_id', $_GET['cid'], 'status', 1)[0];
                $visit_id = $override->get('visit', 'client_id', $_GET['cid'])[0];
                $last_visit = $override->getlastRow('visit', 'client_id', $_GET['cid'], 'id')[0];
                $expected_date = $override->getNews('visit', 'expected_date', Input::get('expected_date'), 'client_id', $_GET['cid'])[0];

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

                $summary = $override->get3('visit', 'client_id', $_GET['cid'], 'seq_no', $sq, 'visit_code', $visit_code)[0];

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
                        'client_id' => $_GET['cid'],
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

                    $client_id = $override->getNews('clients', 'id', Input::get('cid'), 'status', 1)[0];


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
                        if (Input::get('name') == 'user' || Input::get('name') == 'medications' || Input::get('name') == 'site' || Input::get('name') == 'schedule' || Input::get('name') == 'study_id') {
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
                    if (Input::get('action') == 'edit') {
                        $user->updateRecord('medications', array(
                            'name' => Input::get('name'),
                            'cardiac' => Input::get('cardiac'),
                            'diabetes' => Input::get('diabetes'),
                            'sickle_cell' => Input::get('sickle_cell'),
                            'status' => 1,
                        ), Input::get('id'));
                        $successMessage = 'Medications Successful Updated';
                    } elseif (Input::get('action') == 'add') {

                        $medications = $override->get('medications', 'name', Input::get('name'));
                        if ($medications) {
                            $errorMessage = 'Medications Already  Available Please Update instead!';
                        } else {
                            $user->createRecord('medications', array(
                                'name' => Input::get('name'),
                                'cardiac' => Input::get('cardiac'),
                                'diabetes' => Input::get('diabetes'),
                                'sickle_cell' => Input::get('sickle_cell'),
                                'status' => 1,
                            ));
                            $successMessage = 'Medications Successful Added';
                        }
                    }
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
                $data = $override->getData('screening');
                $filename = 'screening Data';
            } elseif (Input::get('demographic')) {
                $data = $override->getData('demographic');
                $filename = 'Demographic Data';
            } elseif (Input::get('vital')) {
                $data = $override->getData('vital');
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
                $data = $override->getData('cardiac');
                $filename = 'Main diagnosis 3 ( Cardiac )';
            } elseif (Input::get('diabetic')) {
                $data = $override->getData('diabetic');
                $filename = 'Main diagnosis 3 ( Diabetic )';
            } elseif (Input::get('sickle_cell')) {
                $data = $override->getData('sickle_cell');
                $filename = 'Main diagnosis 3 ( Sickle Cell )';
            } elseif (Input::get('results')) {
                $data = $override->getData('results');
                $filename = 'Results Data';
            } elseif (Input::get('hospitalization')) {
                $data = $override->getData('hospitalization');
                $filename = 'Hospitalization Data';
            } elseif (Input::get('treatment_plan')) {
                $data = $override->getData('treatment_plan');
                $filename = 'Treatment Plan Data';
            } elseif (Input::get('dgns_complctns_comorbdts')) {
                $data = $override->getData('dgns_complctns_comorbdts');
                $filename = 'Diagnosis, Complications, & Comorbidities Data';
            } elseif (Input::get('risks')) {
                $data = $override->getData('risks');
                $filename = 'RISK Data';
            } elseif (Input::get('hospitalization_details')) {
                $data = $override->getData('hospitalization_details');
                $filename = 'Hospitalization Details Data';
            } elseif (Input::get('lab_details')) {
                $data = $override->getData('lab_details');
                $filename = 'Lab Details Data';
            } elseif (Input::get('social_economic')) {
                $data = $override->getData('social_economic');
                $filename = 'Socioeconomic Status Data';
            } elseif (Input::get('visit')) {
                $data = $override->getData('visit');
                $filename = 'visit Schedule Data';
            } elseif (Input::get('study_id')) {
                $data = $override->getData('study_id');
                $filename = 'study_id Status Data';
            } elseif (Input::get('site')) {
                $data = $override->getData('site');
                $filename = 'Site List';
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

    <style>
        .hidden {
            display: none;
        }
    </style>
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
                            <br>
                            <input class="form-control" id="myInput11" type="text" placeholder="Search participant name here...">

                            <div class="block-fluid">
                                <table id='inventory_report1' cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                        <tr>
                                            <!-- <th><input type="checkbox" name="checkall" /></th> -->
                                            <td width="2">#</td>
                                            <th width="30">Picture</th>
                                            <th width="8%">Study ID</th>
                                            <th width="10%">Enrollment date</th>
                                            <th width="20%">Name</th>
                                            <th width="10%">TYPE</th>
                                            <th width="5%">Gender</th>
                                            <th width="5%">Age</th>
                                            <th width="5%">Site</th>
                                            <th width="10%">Status</th>
                                            <?php if ($_GET['status'] == 4) { ?>

                                                <th width="10%">REASON</th>
                                                <!-- <th width="10%">DETAILS</th> -->

                                            <?php } else { ?>
                                                <th width="20%">ACTION</th>

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
                                            $termination = $override->firstRow1('visit', 'outcome', 'id', 'client_id', $client['id'], 'visit_code', 'TV')[0]['outcome'];
                                            $type = $override->get('main_diagnosis', 'patient_id', $client['id'])[0];

                                            $enrollment_date = $override->firstRow2('visit', 'visit_date', 'id', 'client_id', $client['id'], 'seq_no', 1, 'visit_code', 'EV')[0]['visit_date'];



                                            // print_r($termination);

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
                                                <!-- <td>
                                                    <input type="checkbox" name="checkbox" />
                                                </td> -->
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
                                                <td><?= $enrollment_date ?></td>

                                                <td> <?= $client['firstname'] . ' ' . $client['lastname'] ?></td>

                                                <?php if ($type['cardiac'] == 1) { ?>
                                                    <td>
                                                        <a href="#" class="btn btn-default">Cardiac</a>
                                                    </td>
                                                <?php } elseif ($type['diabetes'] == 1) { ?>
                                                    <td>
                                                        <a href="#" class="btn btn-info">Diabtes</a>
                                                    </td>
                                                <?php } elseif ($type['sickle_cell'] == 1) { ?>
                                                    <td>
                                                        <a href="#" class="btn btn-success">Sickle Cell</a>
                                                    </td>
                                                <?php } else { ?>
                                                    <td>
                                                        <a href="#" class="btn btn-warning">Not Diagnosised</a>
                                                    </td>
                                                <?php
                                                } ?>
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
                                                    <td>
                                                        <?php if ($client['end_study'] == 1) { ?>
                                                            <a href="#" class="btn btn-danger">END</a>
                                                    </td>

                                                    <td>
                                                        <?php if ($termination == 1) { ?>
                                                            <a href="#" class="btn btn-info">On Treatment</a>
                                                        <?php } elseif ($termination == '2') { ?>

                                                            <a href="#" class="btn btn-info">Default</a>
                                                        <?php
                                                            } elseif ($termination == 3) { ?>
                                                            <a href="#" class="btn btn-info">Stop treatment</a>
                                                        <?php
                                                            } elseif ($termination == 4) { ?>
                                                            <a href="#" class="btn btn-info">Trnasfer Out</a>
                                                        <?php
                                                            } elseif ($termination == 5) { ?>
                                                            <a href="#" class="btn btn-info">Death</a>
                                                        <?php
                                                            } else { ?>
                                                            <a href="#" class="btn btn-info">Other</a>
                                                        <?php
                                                            } ?>
                                                    </td>


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
                                                    <a href="add.php?id=4&cid=<?= $client['id'] ?>" role="button" class="btn btn-default" data-toggle="modal">View</a>
                                                    <?php if ($user->data()->position == 1) { ?>
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
                                                        <!-- <a href="#addSchedule<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Add Next Visit</a> -->

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
                                                                <h4>Delete Client</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <strong style="font-weight: bold;color: red">
                                                                    <p>Are you sure you want to delete this Client</p>
                                                                </strong>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="submit" name="delete_client" value="Delete" class="btn btn-danger">
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

                                                                    <div class="col-sm-4">
                                                                        <div class="row-form clearfix">
                                                                            <div class="form-group">
                                                                                <label>Date of Screening</label>
                                                                                <input class="validate[required,custom[date]]" type="text" name="screening_date" id="screening_date" value="<?php if ($screening['screening_date']) {
                                                                                                                                                                                                print_r($screening['screening_date']);
                                                                                                                                                                                            }  ?>" />
                                                                                <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="row-form clearfix">
                                                                            <div class="form-group">
                                                                                <label>Consenting individuals?</label>
                                                                                <select name="consent" id="consent" style="width: 100%;" onchange="checkQuestionValue1('consent','conset_date')" required>
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
                                                                    </div>
                                                                    <div class="col-sm-4" id="conset_date">
                                                                        <div class="row-form clearfix">
                                                                            <div class="form-group">
                                                                                <label>Date of Conset</label>
                                                                                <input type="text" name="conset_date" value="<?php if ($screening['conset_date']) {
                                                                                                                                    print_r($screening['conset_date']);
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
                                                                                <label>Known NCD?</label>
                                                                                <select name="ncd" style="width: 100%;" required>
                                                                                    <option value="<?= $screening['scd'] ?>"><?php if ($screening) {
                                                                                                                                    if ($screening['ncd'] == 1) {
                                                                                                                                        echo 'Yes';
                                                                                                                                    } elseif ($screening['ncd'] == 2) {
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
                                                                            <div class="form-group">
                                                                                <label>Confirmed cases for DM ?</label>
                                                                                <select name="dm" style="width: 100%;" required>
                                                                                    <option value="<?= $screening['dm'] ?>"><?php if ($screening) {
                                                                                                                                if ($screening['dm'] == 1) {
                                                                                                                                    echo 'Yes';
                                                                                                                                } elseif ($screening['dm'] == 2) {
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
                                                                            <div class="form-group">
                                                                                <label>Confirmed cases for RHD ?</label>
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
                                                                    </div>
                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-sm-6">
                                                                        <div class="row-form clearfix">
                                                                            <div class="form-group">
                                                                                <label>Confirmed cases for SCD ?</label>
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
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="row-form clearfix">
                                                                            <div class="form-group">
                                                                                <label>Permanent resident ?</label>
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
                                                                    </div>
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
                                                            $visits_date = $override->firstRow1('visit', 'visit_date', 'id', 'client_id', $client['id'], 'visit_code', 'EV')[0];
                                                            $visits_reason = $override->firstRow1('visit', 'reasons', 'id', 'client_id', $client['id'], 'visit_code', 'EV')[0];

                                                            // $visits = $override->getlastRow('visit', 'client_id', $client['id'], 'id')[0];
                                                            ?>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Edit Visit</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Date of Enrollment:</div>
                                                                        <div class="col-md-9">
                                                                            <input class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" value="<?php if ($visits_date['visit_date']) {
                                                                                                                                                                                    print_r($visits_date['visit_date']);
                                                                                                                                                                                }  ?>" />
                                                                            <span>Example: 2010-12-01</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Notes / Remarks / Comments:</div>
                                                                        <div class="col-md-9">
                                                                            <textarea name="reasons" rows="4">
                                                                                 <?php
                                                                                    if ($visits_reason['reasons']) {
                                                                                        print_r($visits_reason['reasons']);
                                                                                    } ?>
                                                                                </textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                <input type="hidden" name="visit_name" value="Enrollment Visit">
                                                                <input type="submit" name="add_Enrollment" class="btn btn-warning" value="Save">
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
                            <?php
                            $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                            $visits_status = $override->firstRow1('visit', 'status', 'id', 'client_id', $_GET['cid'], 'visit_code', 'EV')[0]['status'];

                            // $patient = $override->get('clients', 'id', $_GET['cid'])[0];
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
                                            <h4><strong style="font-size: medium">Category: <?= $cat ?></strong></h4>
                                            <h4><strong style="font-size: larger">Study ID: <?= $patient['study_id'] ?></strong></h4>
                                            <h4><strong style="font-size: larger">Sex: <?= $gender ?></strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="head clearfix">
                                        <div class="isw-grid"></div>
                                        <h1>Schedule <h4><strong style="font-size: larger"><?= $name ?></strong></h4>
                                        </h1>
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
                                                    <th width="10%">Action</th>
                                                    <th width="10%">Action (Next Appointment)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($override->get('visit', 'client_id', $_GET['cid']) as $visit) {
                                                    // print_r($visit['visit_code']);
                                                    $clnt = $override->get('clients', 'id', $_GET['cid'])[0];
                                                    $cntV = $override->getCount('visit', 'client_id', $visit['client_id']);

                                                    $demographic = $override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $vital = $override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $history = $override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $symptoms = $override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $diagnosis = $override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $results = $override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $hospitalization = $override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $treatment_plan = $override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $dgns_complctns_comorbdts = $override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $risks = $override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $hospitalization_details = $override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $lab_details = $override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $summary = $override->get3('summary', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);
                                                    $social_economic = $override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $visit['seq_no'], 'visit_code', $visit['visit_code']);


                                                    // print_r($treatment_plan);
                                                    if ($visit['status'] == 0) {
                                                        $btnV = 'Add';
                                                    } elseif ($visit['status'] == 1) {
                                                        $btnV = 'Edit';
                                                    }

                                                    $visit_name = $visit['visit_name'];

                                                ?>
                                                    <tr>
                                                        <td><?= $x ?></td>
                                                        <td> <?= $visit['visit_name'] ?></td>
                                                        <td> <?= $visit['visit_code'] ?></td>
                                                        <td> <?= $visit['visit_day'] ?></td>
                                                        <td> <?= $visit['expected_date'] ?></td>
                                                        <td> <?= $visit['visit_date'] ?> </td>
                                                        <td>
                                                            <?php if ($visit['status'] == 1) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button" class="btn btn-success" data-toggle="modal">Done</a>
                                                            <?php } elseif ($visit['status'] == 0) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Pending</a>
                                                            <?php } ?>
                                                        </td>

                                                        <td>
                                                            <?php if ($visit['visit_code'] == 'EV') { ?>

                                                                <?php if ($visit['status'] == 1 && ($visit['visit_code'] == 'EV' || $visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                    <?php if ($demographic && $vital && $history && $symptoms && $diagnosis && $results && $hospitalization && $treatment_plan && $dgns_complctns_comorbdts && $risks && $hospitalization_details  && $lab_details && $social_economic) { ?>

                                                                        <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-info"> Edit Study Forms </a>


                                                                    <?php } else { ?>
                                                                        <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-warning"> Fill Study Forms </a>

                                                            <?php }
                                                                }
                                                            } ?>


                                                            <?php if (($visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                <?php if ($visit['status'] == 1 && ($visit['visit_code'] == 'EV' || $visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                    <?php if ($vital && $symptoms && $results && $hospitalization && $treatment_plan && $dgns_complctns_comorbdts && $risks && $hospitalization_details  && $lab_details) { ?>

                                                                        <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-info"> Edit Study Forms </a>


                                                                    <?php } else { ?>
                                                                        <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>" role="button" class="btn btn-warning"> Fill Study Forms </a>

                                                            <?php }
                                                                }
                                                            } ?>

                                                        </td>
                                                        <td>
                                                            <?php if ($visit['visit_code'] == 'RV') { ?>
                                                                <?php if ($visits_status  == 1) { ?>
                                                                    <a href="#addSchedule<?= $visit['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Add Next Visit</a>
                                                                <?php } ?>
                                                            <?php } ?>

                                                        </td>
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
                                                                                            <label>Visit Date</label>
                                                                                            <input value="<?php if ($visit['status'] != 0) {
                                                                                                                echo $visit['visit_date'];
                                                                                                            } ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" />
                                                                                            <span>Example: 2010-12-01</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- <div class="col-sm-3">
                                                                                    <div class="row-form clearfix"> -->
                                                                                <!-- select -->
                                                                                <!-- <div class="form-group">
                                                                                            <label>Visit Name</label>
                                                                                            <select name="visit_name" style="width: 100%;" required>
                                                                                                <option value="">Select</option>
                                                                                                <?php foreach ($override->getData('schedule') as $study) { ?>
                                                                                                    <option value="<?= $study['name'] ?>"><?= $study['name'] ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> -->

                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <!-- select -->
                                                                                        <div class="form-group">
                                                                                            <label>Notes / Remarks /Comments</label>
                                                                                            <textarea name="reasons" rows="4"><?php if ($visit['status'] != 0) {
                                                                                                                                    echo $visit['reasons'];
                                                                                                                                } ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="dr"><span></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" value="<?= $visit['id'] ?>">
                                                                        <input type="hidden" name="vc" value="<?= $visit['visit_code'] ?>">
                                                                        <input type="hidden" name="visit_name" value="<?= $visit['visit_name'] ?>">
                                                                        <input type="hidden" name="cid" value="<?= $visit['client_id'] ?>">
                                                                        <input type="submit" name="edit_visit" class="btn btn-warning" value="Save">
                                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="addSchedule<?= $visit['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                            <h1>Summary Visit</h1>
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
                                                                                    <input class="validate[required,custom[date]]" type="text" name="summary_date" id="summary_date" value="<?php if ($visit['summary_date']) {
                                                                                                                                                                                                print_r($visit['summary_date']);
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
                                                                                    <select name="diagnosis" id="diagnosis" style="width: 100%;" onchange="checkQuestionValue96('diagnosis','diagnosis_other')" required>
                                                                                        <option value="<?= $visit['diagnosis'] ?>"><?php if ($visit) {
                                                                                                                                        if ($visit['diagnosis'] == 1) {
                                                                                                                                            echo 'Type 1 Diabetes';
                                                                                                                                        } elseif ($visit['diagnosis'] == 2) {
                                                                                                                                            echo 'Type 2 Diabetes';
                                                                                                                                        } elseif ($visit['diagnosis'] == 3) {
                                                                                                                                            echo 'Cardiac';
                                                                                                                                        } elseif ($visit['diagnosis'] == 4) {
                                                                                                                                            echo 'Sickle Cell Disease ';
                                                                                                                                        } elseif ($visit['diagnosis'] == 5) {
                                                                                                                                            echo 'Respiratory';
                                                                                                                                        } elseif ($visit['diagnosis'] == 6) {
                                                                                                                                            echo 'Liver';
                                                                                                                                        } elseif ($visit['diagnosis'] == 7) {
                                                                                                                                            echo 'Kidney';
                                                                                                                                        } elseif ($visit['diagnosis'] == 96) {
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
                                                                                        <option value="96">Other</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row hidden" id="diagnosis_other">

                                                                        <div class="col-sm-12">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>If other, Specify</label>
                                                                                    <input type="text" name="diagnosis_other" value="<?php if ($visit['diagnosis_other']) {
                                                                                                                                            print_r($visit['diagnosis_other']);
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
                                                                                    <?php if ($visit['comments']) {
                                                                                        print_r($visit['comments']);
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
                                                                                    <select name="outcome" id="outcome" style="width: 100%;" onchange="checkQuestionValue96('outcome','outcome')" required>
                                                                                        <option value="<?= $visit['outcome'] ?>"><?php if ($visit) {
                                                                                                                                        if ($visit['outcome'] == 1) {
                                                                                                                                            echo 'On treatment';
                                                                                                                                        } elseif ($visit['outcome'] == 2) {
                                                                                                                                            echo 'Default';
                                                                                                                                        } elseif ($visit['outcome'] == 3) {
                                                                                                                                            echo 'Stop Treatment';
                                                                                                                                        } elseif ($visit['outcome'] == 4) {
                                                                                                                                            echo 'Transfer Out';
                                                                                                                                        } elseif ($visit['outcome'] == 5) {
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
                                                                                    <select name="transfer_out" id="transfer_out" style="width: 100%;" onchange="checkQuestionValue96('transfer_out','transfer_other')">
                                                                                        <option value="<?= $visit['transfer_out'] ?>"><?php if ($visit) {
                                                                                                                                            if ($visit['transfer_out'] == 1) {
                                                                                                                                                echo 'Other NCD clinic';
                                                                                                                                            } elseif ($visit['transfer_out'] == 2) {
                                                                                                                                                echo 'Referral hospital';
                                                                                                                                            } elseif ($visit['transfer_out'] == 96) {
                                                                                                                                                echo 'Other';
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                            echo 'Select';
                                                                                                                                        } ?>
                                                                                        </option>
                                                                                        <option value="1">Other NCD clinic</option>
                                                                                        <option value="2">Referral hospital</option>
                                                                                        <option value="96">Other</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="row">


                                                                        <div class="col-sm-4 hidden" id="transfer_other">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>If other, Specify</label>
                                                                                    <input type="text" name="transfer_other" value="<?php if ($visit['transfer_other']) {
                                                                                                                                        print_r($visit['transfer_other']);
                                                                                                                                    }  ?>" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-4" id="death">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Cause of Death</label>
                                                                                    <select name="cause_death" id="cause_death" style="width: 100%;" onchange="checkQuestionValue96('cause_death','death_other')">
                                                                                        <option value="<?= $visit['cause_death'] ?>"><?php if ($visit) {
                                                                                                                                            if ($visit['cause_death'] == 1) {
                                                                                                                                                echo 'NCD';
                                                                                                                                            } elseif ($visit['cause_death'] == 2) {
                                                                                                                                                echo 'Unknown';
                                                                                                                                            } elseif ($visit['cause_death'] == 96) {
                                                                                                                                                echo 'Other';
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                            echo 'Select';
                                                                                                                                        } ?>
                                                                                        </option>
                                                                                        <option value="1">NCD</option>
                                                                                        <option value="2">Unknown</option>
                                                                                        <option value="96">Other</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-4 hidden" id="death_other">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>If other, Specify</label>
                                                                                    <input type="text" name="death_other" value="<?php if ($visit['death_other']) {
                                                                                                                                        print_r($visit['death_other']);
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
                                                                                    <input type="text" name="next_notes" id="next_notes" value="<?php if ($visit['next_notes']) {
                                                                                                                                                    print_r($visit['next_notes']);
                                                                                                                                                }  ?>" required />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6" id="diagnosis_other">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Next Appointment Date</label>
                                                                                    <input class="validate[required,custom[date]]" type="text" name="expected_date" id="expected_date" value="<?php if ($visit['expected_date']) {
                                                                                                                                                                                                    print_r($visit['expected_date']);
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
                                            <td>Registered Clients Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="clients" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Screened Clients Data</td>
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
                                            <td> Patient & Family History & Complication Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="history" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td> Symtom & Exam Data</td>
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
                                            <td>Results Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="results" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>12</td>
                                            <td>Hospitalization Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="hospitalization" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>Hospitalization Details Data
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="hospitalization_details" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>Treatment Plan Data
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="treatment_plan" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td>Diagnosis, Complications, & Comorbidities Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="dgns_complctns_comorbdts" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td>RISK Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="risks" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>17</td>
                                            <td>Lab Details Data
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="lab_details" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>18</td>
                                            <td>Socioeconomic Status Data
                                            </td>
                                            <td>
                                                <form method="post"><input type="submit" name="social_economic" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>19</td>
                                            <td>Study IDs</td>
                                            <td>
                                                <form method="post"><input type="submit" name="study_id" value="Download"></form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20</td>
                                            <td>Sites List</td>
                                            <td>
                                                <form method="post"><input type="submit" name="site" value="Download"></form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>21</td>
                                            <td>Visit Schedules Data</td>
                                            <td>
                                                <form method="post"><input type="submit" name="visit" value="Download"></form>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 7) { ?>
                        <div class="col-md-2">
                            <?php
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

                            ?>
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
                                    <h4><strong style="font-size: medium">Category: <?= $cat ?></strong></h4>
                                    <h4><strong style="font-size: larger">Study ID: <?= $patient['study_id'] ?></strong></h4>
                                    <h4><strong style="font-size: larger">Sex: <?= $gender ?></strong></h4>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Study CRF (Enrollment) Category : <?= $cat ?></h1>
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
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php }  ?>

                                        <tr>
                                            <td>2</td>
                                            <td>Vitals</td>
                                            <?php if ($override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>
                                        </tr>

                                        <?php if ($_GET['seq'] == 1) { ?>

                                            <tr>
                                                <td>2</td>
                                                <td>Pateint Category</td>
                                                <?php if ($override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                    <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php }  ?>


                                        <?php if ($_GET['seq'] == 1) { ?>

                                            <tr>
                                                <td>3</td>
                                                <td>Patient Hitory & Family History & Complication</td>
                                                <?php if ($override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                <?php } ?>
                                            </tr>

                                        <?php }  ?>


                                        <tr>
                                            <td>4</td>
                                            <td>History, Symtom & Exam</td>
                                            <?php if ($override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>
                                        </tr>


                                        <?php if ($_GET['seq'] == 1) { ?>

                                            <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                                <tr>
                                                    <td>5</td>
                                                    <td>Main diagnosis 1 ( Cardiac )</td>
                                                    <?php if ($override->get3('cardiac', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

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
                                                    <?php if ($override->get3('diabetic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

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
                                                    <?php if ($override->get3('sickle_cell', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                        <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                    <?php } else { ?>
                                                        <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                                    <?php } ?>
                                                </tr>

                                            <?php } ?>

                                        <?php }  ?>


                                        <?php
                                        //  if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { 
                                        ?>
                                        <tr>
                                            <td>6</td>
                                            <?php if ($_GET['seq'] == 1) { ?>
                                                <td>Results at enrollment</td>
                                            <?php } else { ?>
                                                <td>Results at Follow Up</td>
                                            <?php } ?>

                                            <?php if ($override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <?php
                                        //  }
                                        ?>

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
                                            <td>Hospitalization Details</td>
                                            <?php if ($override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>9</td>
                                            <td>Treatment Plan</td>
                                            <?php if ($override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td>Diagnosis, Complications, & Comorbidities</td>
                                            <?php if ($override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td>RISK</td>
                                            <?php if ($override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>



                                        <tr>
                                            <td>12</td>
                                            <td>Lab Details</td>
                                            <?php if ($override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                            <?php } else { ?>
                                                <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
                                            <?php } ?>

                                        </tr>
                                        <?php if ($_GET['seq'] == 1) { ?>

                                            <tr>
                                                <td>13</td>
                                                <td>Socioeconomic Status</td>
                                                <?php if ($override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                    <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-success"> Change </a> </td>
                                                <?php } else { ?>
                                                    <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>" class="btn btn-warning"> Add </a> </td>
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

                        <div class="col-md-6">
                            <input class="form-control" id="myInput" type="text" placeholder="Search medications..">

                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>List of Medications</h1>
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
                                <table id="medication_list" cellpadding="0" cellspacing="0" width="100%" class="table">
                                    <thead>
                                        <tr>
                                            <th width="25%">Name</th>
                                            <th width="5%">cardiac</th>
                                            <th width="5%">diabetes</th>
                                            <th width="5%">sickle_cell</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($override->getData('medications') as $medication) { ?>
                                            <tr>
                                                <td> <?= $medication['name'] ?></td>
                                                <td>
                                                    <?php if ($medication['cardiac'] == 1) {
                                                        echo 'Yes';
                                                    } else {
                                                        echo 'No';
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if ($medication['diabetes'] == 1) {
                                                        echo 'Yes';
                                                    } else {
                                                        echo 'No';
                                                    } ?>
                                                </td>

                                                <td>
                                                    <?php if ($medication['sickle_cell'] == 1) {
                                                        echo 'Yes';
                                                    } else {
                                                        echo 'No';
                                                    } ?>
                                                </td>

                                                <td><a href="#medication<?= $medication['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                                <!-- EOF Bootrstrap modal form -->
                                            </tr>
                                            <div class="modal fade" id="medication<?= $medication['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>List of Medications</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="row-form clearfix">
                                                                            <!-- select -->
                                                                            <div class="form-group">
                                                                                <label>Medication Name:</label>
                                                                                <input type="text" name="name" value="<?php if ($medication['name']) {
                                                                                                                            print_r($medication['name']);
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
                                                                                    <option value="<?= $medication['cardiac'] ?>"><?php if ($medication) {
                                                                                                                                        if ($medication['cardiac'] == 1) {
                                                                                                                                            echo 'Yes';
                                                                                                                                        } elseif ($medication['cardiac'] == 2) {
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
                                                                                    <option value="<?= $medication['diabetes'] ?>"><?php if ($medication) {
                                                                                                                                        if ($medication['diabetes'] == 1) {
                                                                                                                                            echo 'Yes';
                                                                                                                                        } elseif ($medication['diabetes'] == 2) {
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
                                                                                    <option value="<?= $medication['sickle_cell'] ?>"><?php if ($medication) {
                                                                                                                                            if ($medication['sickle_cell'] == 1) {
                                                                                                                                                echo 'Yes';
                                                                                                                                            } elseif ($medication['sickle_cell'] == 2) {
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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $medication['id'] ?>">
                                                                <input type="hidden" name="action" value="edit">
                                                                <input type="submit" name="add_medications" value="Submit" class="btn btn-default">
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
                                <div class="isw-ok"></div>
                                <h1>Add Medications</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <div class="autocomplete" style="width:300px;">
                                                        <!-- <div class="form-group autocomplete" style="width:300px;"> -->
                                                        <label>Medication Name:</label>
                                                        <input type="text" name="name" id="medication_name" value="" placeholder="Type medications name..." onkeyup="myFunction()" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cardiac:</label>
                                                    <select name="cardiac" style="width: 100%;" required>
                                                        <option value="">Select </option>
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
                                                        <option value="">Select </option>
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
                                                        <option value="">Select </option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="hidden" name="action" value="add">
                                        <input type="submit" name="add_medications" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 10) { ?>

                        <?php
                        $AllTables = $override->AllTables();
                        ?>
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
                                                <?php foreach ($AllTables as $tables) { ?>
                                                    <option value="<?= $tables['Tables_in_penplus'] ?>"><?= $tables['Tables_in_penplus'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="clear_data" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

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

    $(document).ready(function() {
        $("#myInput11").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#inventory_report1 tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#medication_list tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function checkQuestionValue1(currentQuestion, elementToHide) {
        var currentQuestionInput = document.getElementById(currentQuestion);
        var elementToHide = document.getElementById(elementToHide);

        var questionValue = currentQuestionInput.value;

        if (questionValue === "1") {
            elementToHide.classList.remove("hidden");
        } else {
            elementToHide.classList.add("hidden");
        }
    }

    function checkQuestionValue96(currentQuestion, elementToHide) {
        var currentQuestionInput = document.getElementById(currentQuestion);
        var elementToHide = document.getElementById(elementToHide);

        var questionValue = currentQuestionInput.value;

        if (questionValue === "96") {
            elementToHide.classList.remove("hidden");
        } else {
            elementToHide.classList.add("hidden");
        }
    }

    function toggleQuestionVisibility(currentQuestionId, nextQuestionId) {
        var currentQuestion = document.getElementById(currentQuestionId);
        var nextQuestion = document.getElementById(nextQuestionId);

        // Check if the current question has a value
        if (currentQuestion.value) {
            nextQuestion.classList.remove("hidden"); // Show the next question
        } else {
            nextQuestion.classList.add("hidden"); // Hide the next question
        }
    }


    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });

        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }

    /*An array containing all the country names in the world:*/
    // var countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia & Herzegovina", "Botswana", "Brazil", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central Arfrican Republic", "Chad", "Chile", "China", "Colombia", "Congo", "Cook Islands", "Costa Rica", "Cote D Ivoire", "Croatia", "Cuba", "Curacao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Polynesia", "French West Indies", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauro", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russia", "Rwanda", "Saint Pierre & Miquelon", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "St Kitts & Nevis", "St Lucia", "St Vincent", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor L'Este", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks & Caicos", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Virgin Islands (US)", "Yemen", "Zambia", "Zimbabwe"];
    // var getUid = $(this).val();
    fetch('fetch_medications.php')
        .then(response => response.json())
        .then(data => {
            // Process the data received from the PHP script
            // console.log(data);
            autocomplete(document.getElementById("medication_name"), data);
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch request
            console.error('Error:', error);
        });

    fetch('fetching_brand.php')
        .then(response => response.json())
        .then(data => {
            // Process the data received from the PHP script
            // console.log(data);
            autocomplete(document.getElementById("brand_id2"), data);
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch request
            console.error('Error:', error);
        });


    fetch('fetching_batch.php')
        .then(response => response.json())
        .then(data => {
            // Process the data received from the PHP script
            // console.log(data);
            autocomplete(document.getElementById("batch_no"), data);
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch request
            console.error('Error:', error);
        });

    fetch('fetching_manufacturer.php')
        .then(response => response.json())
        .then(data => {
            // Process the data received from the PHP script
            // console.log(data);
            autocomplete(document.getElementById("manufacturer"), data);
        })
        .catch(error => {
            // Handle any errors that occurred during the fetch request
            console.error('Error:', error);
        });

    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    // autocomplete(document.getElementById("myInput"), countries);
</script>

</html>