<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;

$numRec = 7;
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
        } elseif (Input::get('reset_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Reset Successful';
        } elseif (Input::get('change_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = Input::get('password');
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Changed Successful';
        } elseif (Input::get('lock_account')) {
            $user->updateRecord('user', array(
                'count' => 4,
            ), Input::get('id'));
            $successMessage = 'Account locked Successful';
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
        } elseif (Input::get('restore_staff')) {
            $user->updateRecord('user', array(
                'status' => 1,
            ), Input::get('id'));
            $successMessage = 'User Deleted Successful';
        } elseif (Input::get('delete_client')) {
            $user->updateRecord('clients', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Client Deleted Successful';
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
        } elseif (Input::get('add_screening')) {
            $validate = $validate->check($_POST, array(
                'screening_date' => array(
                    'required' => true,
                ),
                'lab_request' => array(
                    'required' => true,
                ),
                'ncd' => array(
                    'required' => true,
                ),
                'consent' => array(
                    'required' => true,
                ),
                'residence' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $eligibility = 0;
                    if ((Input::get('consent') == 1 && Input::get('residence') == 1) && (Input::get('ncd') == 1)) {
                        $eligibility = 1;
                    }

                    $doctor_confirm = 0;
                    if ((Input::get('consent') == 1 && Input::get('residence') == 1)) {
                        if (Input::get('ncd') == 1) {
                            $doctor_confirm = 2;
                        }
                    }

                    if ($override->getNews('screening', 'status', 1, 'patient_id', Input::get('cid'))) {
                        $user->updateRecord('screening', array(
                            'study_id' => Input::get('study_id'),
                            'visit_code' => 'SV',
                            'visit_day' => 'Day 0',
                            'seq_no' => 0,
                            'vid' => 0,
                            // 'study_id' => Input::get('study_id'),
                            'screening_date' => Input::get('screening_date'),
                            'conset_date' => Input::get('conset_date'),
                            'ncd' => Input::get('ncd'),
                            'lab_request' => Input::get('lab_request'),
                            'lab_request_date' => Input::get('lab_request_date'),
                            'screening_type' => Input::get('screening_type'),
                            'consent' => Input::get('consent'),
                            'residence' => Input::get('residence'),
                            'created_on' => date('Y-m-d'),
                            'patient_id' => Input::get('cid'),
                            'staff_id' => $user->data()->id,
                            'eligibility' => $eligibility,
                            'doctor_confirm' => $doctor_confirm,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), Input::get('id'));

                        $visit = $override->getNews('visit', 'client_id', Input::get('cid'), 'seq_no', 0, 'visit_name', 'Screening')[0];

                        if ($visit) {
                            $user->updateRecord('visit', array(
                                'expected_date' => Input::get('screening_date'),
                                'visit_date' => Input::get('screening_date'),
                            ), $visit['id']);
                        } else {
                            $user->createRecord('visit', array(
                                'summary_id' => 0,
                                'study_id' => Input::get('study_id'),
                                'visit_name' => 'Screening',
                                'visit_code' => 'SV',
                                'visit_day' => 'Day 0',
                                'expected_date' => Input::get('screening_date'),
                                'visit_date' => Input::get('screening_date'),
                                'visit_window' => 0,
                                'status' => 1,
                                'seq_no' => 0,
                                'client_id' => Input::get('cid'),
                                'created_on' => date('Y-m-d'),
                                'reasons' => '',
                                'visit_status' => 1,
                                'site_id' => $user->data()->site_id,
                            ));
                        }

                        $successMessage = 'Screening Successful Updated';
                    } else {
                        $user->createRecord('screening', array(
                            'study_id' => Input::get('study_id'),
                            'visit_code' => 'SV',
                            'visit_day' => 'Day 0',
                            'seq_no' => 0,
                            'vid' => 0,
                            'screening_date' => Input::get('screening_date'),
                            'conset_date' => Input::get('conset_date'),
                            'consent' => Input::get('consent'),
                            'ncd' => Input::get('ncd'),
                            'lab_request' => Input::get('lab_request'),
                            'lab_request_date' => Input::get('lab_request_date'),
                            'screening_type' => Input::get('screening_type'),
                            'residence' => Input::get('residence'),
                            'created_on' => date('Y-m-d'),
                            'patient_id' => Input::get('cid'),
                            'staff_id' => $user->data()->id,
                            'eligibility' => $eligibility,
                            'status' => 1,
                            'doctor_confirm' => $doctor_confirm,
                            'site_id' => $user->data()->site_id,
                        ));

                        $user->createRecord('visit', array(
                            'summary_id' => 0,
                            'study_id' => Input::get('study_id'),
                            'visit_name' => 'Screening',
                            'visit_code' => 'SV',
                            'visit_day' => 'Day 0',
                            'expected_date' => Input::get('screening_date'),
                            'visit_date' => Input::get('screening_date'),
                            'visit_window' => 0,
                            'status' => 1,
                            'seq_no' => 0,
                            'client_id' => Input::get('cid'),
                            'created_on' => date('Y-m-d'),
                            'reasons' => '',
                            'visit_status' => 1,
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $user->updateRecord('clients', array(
                        'eligible' => $eligibility,
                        // 'enrolled' => $eligibility,
                        'screened' => 1,
                    ), Input::get('cid'));

                    $successMessage = 'Screening Successful Added';

                    if ($eligibility) {
                        Redirect::to('info.php?id=3&status=2');
                    } else {
                        Redirect::to('info.php?id=3&status=1');
                        // Redirect::to('info.php?id=3&status=' . $_GET['status']);
                        // Redirect::to('add_lab.php?cid=' . Input::get('id') . '&status=1&msg=' . $successMessage);
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
                $visit = $override->getNews('visit', 'client_id', Input::get('id'), 'seq_no', 1);

                if ($visit) {
                    $user->updateRecord('visit', array('expected_date' => Input::get('visit_date'), 'reasons' => Input::get('reasons')), $visit[0]['id']);

                    foreach ($override->get('visit', 'client_id', Input::get('id')) as $visit_client) {
                        $user->updateRecord('visit', array('study_id' => Input::get('study_id'), 'site_id' => Input::get('site_id')), $visit_client['id']);
                    }

                    $successMessage = 'Enrollment  Updated Successful';
                } else {

                    $user->createRecord('visit', array(
                        'summary_id' => 0,
                        'study_id' => Input::get('study_id'),
                        'visit_name' => 'Enrollment Visit',
                        'visit_code' => 'EV',
                        'visit_day' => 'Day 1',
                        'expected_date' => Input::get('visit_date'),
                        'visit_date' => '',
                        'visit_window' => 0,
                        'status' => 1,
                        'client_id' => Input::get('id'),
                        'created_on' => date('Y-m-d'),
                        'seq_no' => 1,
                        'reasons' => Input::get('reasons'),
                        'visit_status' => 0,
                        'site_id' => Input::get('site_id'),
                    ));

                    foreach ($override->get('visit', 'client_id', Input::get('id')) as $visit_client) {
                        $user->updateRecord('visit', array('study_id' => Input::get('study_id'), 'site_id' => Input::get('site_id')), $visit_client['id']);
                    }

                    $user->updateRecord('clients', array('enrolled' => 1), Input::get('id'));


                    $successMessage = 'Enrollment  Added Successful';
                }
                Redirect::to('info.php?id=3&status=3&msg=' . $successMessage);
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                // 'visit_status' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'visit_date' => Input::get('visit_date'),
                        'visit_status' => Input::get('visit_status'),
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
        } elseif (Input::get('update_visit')) {
            $validate = $validate->check($_POST, array(
                'expected_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'expected_date' => Input::get('expected_date'),
                    ), Input::get('id'));

                    $user->updateRecord('summary', array(
                        'next_appointment_date' => Input::get('expected_date'),
                    ), Input::get('summary_id'));

                    $successMessage = 'Visit  Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_visit')) {
            $validate = $validate->check($_POST, array(
                // 'expected_date' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'status' => 0,
                    ), Input::get('id'));

                    $tables = ['demographic', 'vital', 'main_diagnosis', 'history', 'cardiac', 'diabetic', 'sickle_cell', 'results', 'hospitalization', 'hospitalization_details', 'sickle_cell_status_table', 'sickle_cell_status_table', 'treatment_plan', 'medication_treatments', 'dgns_complctns_comorbdts', 'risks', 'lab_details', 'social_economic', 'symptoms', 'summary'];
                    foreach ($tables as $table) {
                        // $columns = $override->getNews($table, 'patient_id', Input::get('cid'), 'vid', Input::get('id'), 'seq_no', Input::get('seq_no'));
                        $columns = $override->getNews($table, 'patient_id', Input::get('cid'), 'vid', Input::get('id'));
                        foreach ($columns as $column) {
                            $user->updateRecord($table, array(
                                'status' => 0,
                            ), $column['id']);
                        }
                    }
                    $successMessage = 'Visit  Deleted Successful';
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
                        if (Input::get('name') == 'user' || Input::get('name') == 'sub_category' || Input::get('name') == 'test_list' || Input::get('name') == 'category' || Input::get('name') == 'medications' || Input::get('name') == 'site' || Input::get('name') == 'schedule' || Input::get('name') == 'study_id') {
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
        } elseif (Input::get('setSiteId')) {

            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $setSiteId = $override->setSiteId('visit', 'site_id', Input::get('name'), 1);
                    $successMessage = 'Site ID Successfull';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('set_study_id')) {

            $validate = $validate->check($_POST, array(
                'client_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];

                    $user->updateRecord('clients', array(
                        'study_id' => $std_id['study_id'],
                    ), Input::get('client_id'));

                    $user->updateRecord('study_id', array(
                        'status' => 1,
                        'client_id' => Input::get('client_id'),
                    ), $std_id['id']);

                    $successMessage = 'STUDY ID ADDED Successfull';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('update_study_id')) {

            $validate = $validate->check($_POST, array(
                'client_id' => array(
                    'required' => true,
                ),
                'table_name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('client_id')) {

                        $client_id = '';

                        if (Input::get('table_name') == 'visit') {
                            $client_id = 'client_id';
                        } else {
                            $client_id = 'patient_id';
                        }

                        $clients = $override->get('clients', 'id', Input::get('client_id'));
                        $tables = $override->get(Input::get('table_name'), $client_id, Input::get('client_id'));

                        foreach ($tables as $table) {
                            $user->updateRecord(Input::get('table_name'), array(
                                'study_id' => $clients[0]['study_id'],
                                'site_id' => $clients[0]['site_id'],
                            ), $table['id']);
                        }

                        $successMessage = 'STUDY ID Updated Successfull';
                    } else {
                        $errorMessage = 'Error on updating Table  ' . Input::get('table_name');
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('update_study_id_all_tables')) {
            $validate = $validate->check($_POST, array(
                'patient_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $patient_id = '';
                    if (Input::get('patient_id')) {
                        $patient_id = 'patient_id';
                        $clients = $override->get('clients', 'id', Input::get('patient_id'))[0];

                        foreach ($override->AllTables() as $tables) {

                            // print_r($tables);

                            if ($tables['Tables_in_penplus'] == 'screening') {
                                $table = $override->get('screening', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('screening', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }
                            if ($tables['Tables_in_penplus'] == 'demographic') {
                                $table = $override->get('demographic', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('demographic', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }
                            if ($tables['Tables_in_penplus'] == 'vital') {
                                $table = $override->get('vital', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('vital', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'main_diagnosis') {
                                $table = $override->get('main_diagnosis', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('main_diagnosis', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'history') {
                                $table = $override->get('history', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('history', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'symptoms') {
                                $table = $override->get('symptoms', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('symptoms', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'cardiac') {
                                $table = $override->get('cardiac', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('cardiac', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'diabetic') {
                                $table = $override->get('diabetic', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('diabetic', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'sickle_cell') {
                                $table = $override->get('sickle_cell', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('sickle_cell', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'results') {
                                $table = $override->get('results', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('results', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'hospitalization') {
                                $table = $override->get('hospitalization', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('hospitalization', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'hospitalization_details') {
                                $table = $override->get('hospitalization_details', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('hospitalization_details', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'treatment_plan') {
                                $table = $override->get('treatment_plan', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('treatment_plan', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'dgns_complctns_comorbdts') {
                                $table = $override->get('dgns_complctns_comorbdts', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('dgns_complctns_comorbdts', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'risks') {
                                $table = $override->get('risks', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('risks', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'lab_details') {
                                $table = $override->get('lab_details', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('lab_details', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'social_economic') {
                                $table = $override->get('social_economic', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('social_economic', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'summary') {
                                $table = $override->get('summary', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('summary', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'medication_treatments') {
                                $table = $override->get('medication_treatments', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('medication_treatments', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'hospitalization_detail_id') {
                                $table = $override->get('hospitalization_detail_id', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('hospitalization_detail_id', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'sickle_cell_status_table') {
                                $table = $override->get('sickle_cell_status_table', $patient_id, Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('sickle_cell_status_table', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            if ($tables['Tables_in_penplus'] == 'visit') {
                                $table = $override->get('visit', 'client_id', Input::get('patient_id'));
                                foreach ($table as $value) {
                                    $user->updateRecord('visit', array(
                                        'study_id' => $clients['study_id'],
                                        'site_id' => $clients['site_id'],
                                    ), $value['id']);
                                }
                            }

                            $successMessage = 'STUDY ID Updated Successfull On All Tables';
                            Redirect::to('info.php?id=' . $_GET['id'] . '&msg=' . $successMessage);
                        }
                    } else {
                        $errorMessage = 'Please select Patient Study ID';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('unset_study_id')) {

            $validate = $validate->check($_POST, array(
                'client_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients', array(
                        'study_id' => '',
                    ), Input::get('client_id'));

                    $successMessage = 'STUDY ID DELETED Successfull';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_data')) {

            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'site' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('name')) {
                        $site_id = '';
                        if (Input::get('site') == '1') {
                            $site_id = 'KNODOA';
                        } elseif (Input::get('site') == '2') {
                            $site_id = 'KARATU';
                        }

                        if (Input::get('name') == 'user' || Input::get('name') == 'sub_category' || Input::get('name') == 'test_list' || Input::get('name') == 'category' || Input::get('name') == 'medications' || Input::get('name') == 'site' || Input::get('name') == 'schedule' || Input::get('name') == 'study_id') {
                            $errorMessage = 'Table ' . '"' . Input::get('name') . '"' . '  can not be Deleted';
                        } else {
                            // $clearData = $override->deleteDataTable(Input::get('name'), Input::get('site'));
                            $deleteData = $user->deleteRecord(Input::get('name'), 'site_id', Input::get('site'));
                            $successMessage = 'Data on Table ' . '"' . Input::get('name') . 'On site "' . '"' . $site_id . '"' . ' Deleted Successfull';
                        }
                    } else {
                        $errorMessage = 'Data on Table ' . '"' . Input::get('name') . '"' . '  can not be Found!';
                    }
                    // die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_data2')) {

            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'date2' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('name')) {
                        $site_id = '';
                        if (Input::get('site') == '1') {
                            $site_id = 'KNODOA';
                        } elseif (Input::get('site') == '2') {
                            $site_id = 'KARATU';
                        }

                        if (Input::get('name') == 'user' || Input::get('name') == 'sub_category' || Input::get('name') == 'test_list' || Input::get('name') == 'category' || Input::get('name') == 'medications' || Input::get('name') == 'site' || Input::get('name') == 'schedule' || Input::get('name') == 'study_id') {
                            $errorMessage = 'Table ' . '"' . Input::get('name') . '"' . '  can not be Deleted';
                        } else {
                            // $clearData = $override->deleteDataTable(Input::get('name'), Input::get('site'));
                            $deleteData = $user->deleteRecord(Input::get('name'), 'created_on', Input::get('date2'));
                            $successMessage = 'Data on Table ' . '"' . Input::get('name') . ' On site "' . '"' . $site_id . '"' . ' On date "' . '"' . Input::get('date2') . '"' . ' Deleted Successfull';
                        }
                    } else {
                        $errorMessage = 'Data on Table ' . '"' . Input::get('name') . '"' . '  can not be Found!';
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
        } elseif (Input::get('DoctorConfirm')) {

            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $setSiteId = $override->DoctorConfirm('screening', 'doctor_confirm', Input::get('name'), 1);
                    $successMessage = 'Doctor Confirm Successfull Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_medication')) {
            $user->updateRecord('medications', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = Input::get('name') . ' - ' . 'Medications Deleted Successful';
        } elseif (Input::get('delete_batch')) {
            $user->updateRecord('batch', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = Input::get('name') . ' - ' . 'Medications Deleted Successful';
        } elseif (Input::get('search_by_site1')) {
            $validate = $validate->check($_POST, array(
                'site_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('status')) {
                    $url = 'info.php?id=3&status=' . Input::get('status') . '&site_id=' . Input::get('site_id');
                } else {
                    $url = 'info.php?id=' . $_GET['id'] . '&site_id=' . Input::get('site_id');
                }
                Redirect::to($url);
                $pageError = $validate->errors();
            }
        } elseif (Input::get('increase_batch')) {
            $validate = $validate->check($_POST, array(
                'date' => array(
                    'required' => true,
                ),
                'cost' => array(
                    'required' => true,
                ),
                'price' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $batch = $override->getNews('batch', 'status', 1, 'id', Input::get('id'))[0];
                    $amount = $batch['amount'] + Input::get('received');
                    $price = $batch['price'] + Input::get('cost');

                    $user->updateRecord('batch', array(
                        'amount' => $amount,
                        'price' => $price,
                    ), Input::get('id'));

                    $user->createRecord('batch_records', array(
                        'date' => Input::get('date'),
                        'batch_id' => $batch['id'],
                        'medication_id' => $batch['medication_id'],
                        'serial_name' => $batch['serial_name'],
                        'received' => Input::get('received'),
                        'removed' => 0,
                        'amount' => $amount,
                        'expire_date' => $batch['expire_date'],
                        'remarks' => Input::get('remarks'),
                        'cost' => Input::get('cost'),
                        'price' => $price,
                        'status' => 1,
                        'create_on' => date('Y-m-d H:i:s'),
                        'site_id' => $user->data()->site_id,
                        'staff_id' => $user->data()->id,
                    ));

                    $successMessage = 'Medication name : ' . Input::get('name') . ' : Batch : ' . Input::get('serial_name') . ' - ' . Input::get('removed') . ' Increased Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('decrease_batch')) {
            $validate = $validate->check($_POST, array(
                'date' => array(
                    'required' => true,
                ),
                'removed' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $batch = $override->getNews('batch', 'status', 1, 'id', Input::get('id'))[0];
                    if (Input::get('removed') <= $batch['amount']) {
                        $amount = $batch['amount'] - Input::get('removed');

                        $user->updateRecord('batch', array(
                            'amount' => $amount,
                        ), Input::get('id'));

                        $user->createRecord('batch_records', array(
                            'date' => Input::get('date'),
                            'batch_id' => $batch['id'],
                            'medication_id' => $batch['medication_id'],
                            'serial_name' => $batch['serial_name'],
                            'received' => 0,
                            'removed' => Input::get('removed'),
                            'amount' => $amount,
                            'expire_date' => $batch['expire_date'],
                            'remarks' => Input::get('remarks'),
                            'cost' => 0,
                            'price' => $batch['price'],
                            'status' => 1,
                            'create_on' => date('Y-m-d H:i:s'),
                            'site_id' => $user->data()->site_id,
                            'staff_id' => $user->data()->id,
                        ));

                        $successMessage = 'Medication name : ' . Input::get('name') . ' : Batch : ' . Input::get('serial_name') . ' - ' . Input::get('removed') . ' Decreased Successful';
                    } else {
                        $errorMessage = 'Batch to remove exceeds the available Amount';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('update_pids_all_tables')) {
            $validate = $validate->check($_POST, array(
                'pid' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $client_id = $override->get('clients', 'id', Input::get('pid'));

                    if (Input::get('pid')) {
                        $array1 = ["screening", "demographic", "vital", "main_diagnosis", "history", "symptoms", "cardiac", "diabetic", "sickle_cell", "results", "hospitalization", "hospitalization_details", "treatment_plan", "dgns_complctns_comorbdts", "risks", "lab_details", "social_economic", "summary", "medication_treatments", "hospitalization_detail_id", "sickle_cell_status_table", "visit"];
                        $array2 = $override->AllTables();
                        $array2_values = array_column($array2, 'Tables_in_penplus');

                        foreach ($array1 as $value) {
                            if (in_array($value, $array2_values)) {
                                if ($value == 'visit') {
                                    $tables = $override->get($value, 'client_id', Input::get('pid'));
                                    foreach ($tables as $table) {
                                        $user->updateRecord($value, array(
                                            'study_id' => $client_id[0]['study_id'],
                                            'site_id' => $client_id[0]['site_id'],
                                        ), $table['id']);
                                    }
                                } else {
                                    $tables = $override->get($value, 'patient_id', Input::get('pid'));
                                    foreach ($tables as $table) {
                                        $user->updateRecord($value, array(
                                            'study_id' => $client_id[0]['study_id'],
                                            'site_id' => $client_id[0]['site_id'],
                                        ), $table['id']);
                                    }
                                }
                            }
                        }

                        $successMessage = 'PIDs Updated Successfull On All Tables';
                        Redirect::to('info.php?id=' . $_GET['id'] . '&msg=' . $successMessage);
                    } else {
                        $errorMessage = 'Please select Patient Study ID';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }

    }


    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        if ($_GET['site_id'] != null) {
            $screened = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id']);
            $eligible = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id']);
            $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
            $end = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id']);
        } else {
            $screened = $override->countData('clients', 'status', 1, 'screened', 1);
            $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
            $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
            $end = $override->countData('clients', 'status', 1, 'end_study', 1);
        }
    } else {
        $screened = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id);
        $eligible = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
        $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
        $end = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id);
    }
} else {
    Redirect::to('index.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penplus Database | Info</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidemenu.php'; ?>

        <?php if ($errorMessage) { ?>
            <div class="alert alert-danger text-center">
                <h4>Error!</h4>
                <?= $errorMessage ?>
            </div>
        <?php } elseif ($pageError) { ?>
            <div class="alert alert-danger text-center">
                <h4>Error!</h4>
                <?php foreach ($pageError as $error) {
                    echo $error . ' , ';
                } ?>
            </div>
        <?php } elseif ($_GET['msg']) { ?>
            <div class="alert alert-success text-center">
                <h4>Success!</h4>
                <?= $_GET['msg'] ?>
            </div>
        <?php } ?>


        <?php if ($_GET['id'] == 1 && ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2)) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    $pagNum = 0;
                                    if ($_GET['status'] == 1) {
                                        $pagNum = $override->countData2Active('user', 'status', 1, 'power', 0, 'count', 4);
                                    } elseif ($_GET['status'] == 2) {
                                        $pagNum = $override->countData2Active('user', 'status', 0, 'power', 0, 'count', 4);
                                    } elseif ($_GET['status'] == 3) {
                                        $pagNum = $override->countData2Locked('user', 'status', 1, 'power', 0, 'count', 4);
                                    } elseif ($_GET['status'] == 4) {
                                        $pagNum = $override->countData2Locked('user', 'status', 0, 'power', 0, 'count', 4);
                                    } elseif ($_GET['status'] == 5) {
                                        $pagNum = $override->getCount('user', 'status', 1);
                                    }

                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    if ($_GET['status'] == 1) {
                                        $data = $override->getWithLimit3Active('user', 'status', 1, 'power', 0, 'count', 4, $page, $numRec);
                                    } elseif ($_GET['status'] == 2) {
                                        $data = $override->getWithLimit3Active('user', 'status', 0, 'power', 0, 'count', 4, $page, $numRec);
                                    } elseif ($_GET['status'] == 3) {
                                        $data = $override->getWithLimit3Locked('user', 'status', 1, 'power', 0, 'count', 4, $page, $numRec);
                                    } elseif ($_GET['status'] == 4) {
                                        $data = $override->getWithLimit3Locked('user', 'status', 0, 'power', 0, 'count', 4, $page, $numRec);
                                    } elseif ($_GET['status'] == 5) {
                                        $data = $override->getWithLimit('user', 'status', 1, $page, $numRec);
                                    }
                                    ?>
                                    List of Staff
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">List of Staff
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <section class="content-header">
                                        <div class="container-fluid">
                                            <div class="row mb-2">
                                                <div class="col-sm-6">
                                                    <div class="card-header">
                                                        <a href="user.php?condition=<?= $_GET['status']; ?>"> List of Staff
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <ol class="breadcrumb float-sm-right">
                                                        <li class="breadcrumb-item">
                                                            <a href="index1.php">
                                                                < Back</a>
                                                        </li>
                                                        &nbsp;
                                                        <li class="breadcrumb-item">
                                                            <a href="index1.php">
                                                                Go Home > </a>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <hr>
                                        </div><!-- /.container-fluid -->
                                    </section>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="search-results" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>username</th>
                                                    <th>Position</th>
                                                    <th>Access Level</th>
                                                    <th>Sex</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($data as $staff) {
                                                    $position = $override->getNews('position', 'status', 1, 'id', $staff['position'])[0];
                                                    $sites = $override->getNews('site', 'status', 1, 'id', $staff['site_id'])[0];

                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?= $x; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $staff['firstname'] . '  ' . $staff['middlename'] . ' ' . $staff['lastname']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $staff['username']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $position['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $staff['accessLevel']; ?>
                                                        </td>
                                                        <?php if ($staff['sex'] == 1) { ?>
                                                            <td class="table-user">
                                                                Male
                                                            </td>
                                                        <?php } elseif ($staff['sex'] == 2) { ?>
                                                            <td class="table-user">
                                                                Female
                                                            </td>
                                                        <?php } else { ?>
                                                            <td class="table-user">
                                                                Not Available
                                                            </td>
                                                        <?php } ?>

                                                        <td class="table-user">
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <?php if ($staff['count'] < 4) { ?>
                                                            <?php if ($staff['status'] == 1) { ?>
                                                                <td class="text-center">
                                                                    <a href="#" class="btn btn-success">
                                                                        <i class="ri-edit-box-line">
                                                                        </i>Active
                                                                    </a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="text-center">
                                                                    <a href="#" class="btn btn-danger">
                                                                        <i class="ri-edit-box-line">
                                                                        </i>Not Active
                                                                    </a>
                                                                </td>
                                                            <?php } ?>

                                                        <?php } else { ?>
                                                            <td class="text-center">
                                                                <a href="#" class="btn btn-warning"> <i
                                                                        class="ri-edit-box-line"></i>Locked</a>
                                                            </td>
                                                        <?php } ?>
                                                        <td class="text-center">
                                                            <a href="add.php?id=1&staff_id=<?= $staff['id'] ?>"
                                                                class="btn btn-info">Update</a>
                                                            <a href="#reset<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-default" data-toggle="modal">Reset</a>
                                                            <a href="#change<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-success" data-toggle="modal">Change</a>
                                                            <a href="#lock<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-warning" data-toggle="modal">Lock</a>
                                                            <a href="#unlock<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-primary" data-toggle="modal">Unlock</a>
                                                            <a href="#delete<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-danger" data-toggle="modal">Delete</a>
                                                            <a href="#restore<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-secondary" data-toggle="modal">Restore</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="reset<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Reset Password</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to reset password to default
                                                                            (12345678)</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="reset_pass" value="Reset"
                                                                            class="btn btn-warning">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="change<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Change Password</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="text" name="password" value="">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="change_pass" value="Change"
                                                                            class="btn btn-warning">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="lock<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Lock Account</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to lock this account </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="lock_account" value="Lock"
                                                                            class="btn btn-warning">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="unlock<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Unlock Account</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <strong style="font-weight: bold;color: red">
                                                                            <p>Are you sure you want to unlock this account </p>
                                                                        </strong>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="unlock_account"
                                                                            value="Unlock" class="btn btn-success">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="delete<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Delete User</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <strong style="font-weight: bold;color: red">
                                                                            <p>Are you sure you want to delete this user</p>
                                                                        </strong>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="delete_staff" value="Delete"
                                                                            class="btn btn-danger">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="restore<?= $staff['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Restore User</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <strong style="font-weight: bold;color: green">
                                                                            <p>Are you sure you want to restore this user</p>
                                                                        </strong>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $staff['id'] ?>">
                                                                        <input type="submit" name="restore_staff"
                                                                            value="Restore" class="btn btn-success">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>username</th>
                                                    <th>Position</th>
                                                    <th>Sex</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item">
                                                <a class="page-link" href="info.php?id=1&status=<?= $_GET['status'] ?>site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
                                                        echo $_GET['page'] - 1;
                                                    } else {
                                                        echo 1;
                                                    } ?>">&laquo;
                                                </a>
                                            </li>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <li class="page-item">
                                                    <a class="page-link <?php if ($i == $_GET['page']) {
                                                        echo 'active';
                                                    } ?>"
                                                        href="info.php?id=1&status=<?= $_GET['status'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?= $i ?>"><?= $i ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="page-item">
                                                <a class="page-link" href="info.php?id=1&status=<?= $_GET['status'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                        echo $_GET['page'] + 1;
                                                    } else {
                                                        echo $i - 1;
                                                    } ?>">&raquo;
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 2 && ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2)) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    $pagNum = 0;
                                    $pagNum = $override->getCount('position', 'status', 1);

                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    $data = $override->getWithLimit('position', 'status', 1, $page, $numRec);

                                    ?>
                                    List of Posiitions
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">List of Posiitions</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <section class="content-header">
                                        <div class="container-fluid">
                                            <div class="row mb-2">
                                                <div class="col-sm-6">
                                                    <div class="card-header">
                                                        List of Posiitions
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <ol class="breadcrumb float-sm-right">
                                                        <li class="breadcrumb-item">
                                                            <a href="index1.php">
                                                                < Back</a>
                                                        </li>
                                                        &nbsp;
                                                        <li class="breadcrumb-item">
                                                            <a href="index1.php">
                                                                Go Home > </a>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <hr>
                                        </div><!-- /.container-fluid -->
                                    </section>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="search-results" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Access Level</th>
                                                    <!-- <th>Status</th> -->
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($data as $value) {
                                                    $position = $override->getNews('position', 'status', 1, 'id', $staff['position'])[0];
                                                    $access_level = $override->getNews('site', 'status', 1, 'id', $staff['site_id'])[0];

                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?= $x; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['access_level']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="add.php?id=2&position_id=<?= $value['id'] ?>"
                                                                class="btn btn-info">Update</a>
                                                            <a href="#delete<?= $staff['id'] ?>" role="button"
                                                                class="btn btn-danger" data-toggle="modal">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Access Level</th>
                                                    <!-- <th>Status</th> -->
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item">
                                                <a class="page-link" href="info.php?id=2&status=<?= $_GET['status'] ?>site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
                                                        echo $_GET['page'] - 1;
                                                    } else {
                                                        echo 1;
                                                    } ?>">&laquo;
                                                </a>
                                            </li>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <li class="page-item">
                                                    <a class="page-link <?php if ($i == $_GET['page']) {
                                                        echo 'active';
                                                    } ?>"
                                                        href="info.php?id=2&status=<?= $_GET['status'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?= $i ?>"><?= $i ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="page-item">
                                                <a class="page-link" href="info.php?id=2&status=<?= $_GET['status'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                        echo $_GET['page'] + 1;
                                                    } else {
                                                        echo $i - 1;
                                                    } ?>">&raquo;
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

        <?php } elseif ($_GET['id'] == 3) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <?php
                    $Site = '';
                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
                        $Site = 'ALL SITES';
                        if ($_GET['site_id']) {
                            $Site = $override->getNews('site', 'status', 1, 'id', $_GET['site_id'])[0]['name'];
                        }
                    } else {
                        $Site = $override->getNews('site', 'status', 1, 'id', $user->data()->site_id)[0]['name'];
                    }
                    ?>
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    if ($_GET['status'] == 1) {
                                        echo $title = 'Screening for ' . $Site;
                                        ?>
                                        <?php
                                    } elseif ($_GET['status'] == 2) {
                                        echo $title = 'Eligibility  for ' . $Site;
                                        ?>
                                        <?php
                                    } elseif ($_GET['status'] == 3) {
                                        echo $title = 'Enrollment for ' . $Site;
                                        ?>
                                        <?php
                                    } elseif ($_GET['status'] == 4) {
                                        echo $title = 'Termination for ' . $Site;
                                        ?>
                                        <?php
                                    } elseif ($_GET['status'] == 5) {
                                        echo $title = 'Registration for ' . $Site; ?>
                                        <?php
                                    } ?>
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?= $title; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
                                    if ($_GET['site_id'] != null) {
                                        $pagNum = 1;
                                        if ($_GET['status'] == 1) {
                                            $pagNum = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 2) {
                                            $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 3) {
                                            $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 4) {
                                            $pagNum = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 5) {
                                            $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 6) {
                                            $pagNum = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['site_id']);
                                        } elseif ($_GET['status'] == 7) {
                                            $pagNum = $override->getCount('clients', 'site_id', $_GET['site_id']);
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
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit3Search('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 2) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit3Search('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 3) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit3Search('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 4) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit3Search('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 5) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 6) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit3Search('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 7) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimitSearch('clients', 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit('clients', 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 8) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 0, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $_GET['site_id'], $page, $numRec);
                                            }
                                        }
                                    } else {

                                        $pagNum = 1;
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
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'screened', 1, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 1, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 2) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'eligible', 1, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'eligible', 1, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 3) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'enrolled', 1, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'enrolled', 1, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 4) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'end_study', 1, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'end_study', 1, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 5) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimitSearch('clients', 'status', 1, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit('clients', 'status', 1, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 6) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimit1Search('clients', 'status', 1, 'screened', 0, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 0, $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 7) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getDataLimitSearch('clients', $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getDataLimit('clients', $page, $numRec);
                                            }
                                        } elseif ($_GET['status'] == 8) {
                                            if ($_GET['search_name']) {
                                                $searchTerm = $_GET['search_name'];
                                                $clients = $override->getWithLimitSearch('clients', 'status', 0, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                            } else {
                                                $clients = $override->getWithLimit('clients', 'status', 0, $page, $numRec);
                                            }
                                        }
                                    }
                                } else {
                                    $pagNum = 1;
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
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit3Search('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 2) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit3Search('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 3) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit3Search('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 4) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit3Search('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 5) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit1Search('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 6) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit3Search('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 7) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimitSearch('clients', 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit('clients', 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    } elseif ($_GET['status'] == 8) {
                                        if ($_GET['search_name']) {
                                            $searchTerm = $_GET['search_name'];
                                            $clients = $override->getWithLimit1Search('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'firstname', 'middlename', 'lastname', 'study_id');
                                        } else {
                                            $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                                        }
                                    }
                                }
                                ?>
                                <hr>

                                <div class="card">
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                            <div class="card-header">
                                                <?php
                                                if ($_GET['status'] == 1) { ?>
                                                    <h3 class="card-title">List of Screened Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $screened; ?></span>
                                                    <?php
                                                } elseif ($_GET['status'] == 2) { ?>
                                                    <h3 class="card-title">List of Eligible Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $eligible; ?></span>
                                                    <?php
                                                } elseif ($_GET['status'] == 3) { ?>
                                                    <h3 class="card-title">List of Enrolled Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $enrolled; ?></span>
                                                    <?php
                                                } elseif ($_GET['status'] == 4) { ?>
                                                    <h3 class="card-title">List of Terminated Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $end; ?></span>
                                                    <?php
                                                } elseif ($_GET['status'] == 5) { ?>
                                                    <h3 class="card-title">List of Registered Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $registered; ?></span>
                                                    <?php
                                                } elseif ($_GET['status'] == 7) { ?>
                                                    <h3 class="card-title">List of Registered Clients for <?= $Site; ?></h3>
                                                    &nbsp;&nbsp;
                                                    <span class="badge badge-info right"><?= $registered; ?></span>
                                                <?php } ?>
                                                <div class="card-tools">
                                                    <ul class="pagination pagination-sm float-right">
                                                        <li class="page-item"><a class="page-link" href="index1.php">&laquo;
                                                                Back</a></li>
                                                        <li class="page-item"><a class="page-link" href="index1.php">&raquo;
                                                                Home</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>

                                            <?php
                                            if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
                                                ?>
                                                <div class="card-tools">
                                                    <div class="input-group input-group-sm float-left" style="width: 350px;">
                                                        <form method="post">
                                                            <div class="form-inline">
                                                                <div class="input-group-append">
                                                                    <div class="col-sm-12">
                                                                        <select class="form-control float-right" name="site_id"
                                                                            style="width: 100%;" autocomplete="off">
                                                                            <option value="">Select Site</option>
                                                                            <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                                <option value="<?= $site['id'] ?>">
                                                                                    <?= $site['name'] ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <input type="submit" name="search_by_site1"
                                                                        value="Search by Site" class="btn btn-info"><i
                                                                        class="fas fa-search"></i>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="card-tools">
                                                <div class="input-group input-group-sm float-right" style="width: 350px;">
                                                    <form method="get">
                                                        <div class="form-inline">
                                                            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                                            <!-- <input type="hidden" name="site_id" value="<?= $_GET['site_id'] ?>"> -->
                                                            <input type="hidden" name="status"
                                                                value="<?= $_GET['status'] ?>">
                                                            <input type="text" name="search_name" id="search_name"
                                                                class="form-control float-right"
                                                                placeholder="Search here Names or Study ID">
                                                            <input type="submit" value="Search" class="btn btn-default"><i
                                                                class="fas fa-search"></i>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="search-results" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <?php if ($_GET['status'] == 1 || $_GET['status'] == 2) { ?>
                                                    <th>Screening Date</th>
                                                <?php } elseif ($_GET['status'] == 3) { ?>
                                                    <th>Enrollment Date</th>
                                                <?php } elseif ($_GET['status'] == 4) { ?>
                                                    <th>Termination Date</th>
                                                <?php } elseif ($_GET['status'] == 5) { ?>
                                                    <th>Recruitment Date</th>
                                                <?php } ?>
                                                <th>
                                                    Patient ID
                                                    <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                        /
                                                        <hr>
                                                        Patient Name
                                                    <?php } ?>
                                                    </t>

                                                <th>
                                                    Status /
                                                    <hr>
                                                    Type
                                                    <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) { ?>
                                                        /
                                                        <hr>
                                                        Site
                                                    <?php } ?>
                                                </th>

                                                <?php if ($_GET['status'] == 4) { ?>
                                                    <th class="text-center"> Reason /
                                                        <hr> Comments
                                                    </th>
                                                <?php } else { ?>
                                                    <th class="text-center">Action ( Progress )</th>
                                                <?php } ?>

                                                <?php if ($_GET['status'] == 3) { ?>
                                                    <th>Summary</th>
                                                <?php } ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                            $currentSite = $_GET['site_id'];
                                            $itemsPerPage = $numRec; // Set this to the number of records you display per page
                                        
                                            // Calculate the starting number for the current page
                                            $startNumber = ($currentPage - 1) * $itemsPerPage + 1;

                                            // Pagination range
                                            $range = 2;
                                            $start = max(1, $currentPage - $range);
                                            $end = min($pages, $currentPage + $range);

                                            // Initialize $x using $startNumber
                                            $x = $startNumber;

                                            foreach ($clients as $client) {
                                                $screening = $override->getNews('screening', 'status', 1, 'patient_id', $client['id'])[0];
                                                $visit = $override->getCount('visit', 'client_id', $client['id']);
                                                $visits = $override->get('visit', 'client_id', $client['id'])[0];
                                                $termination = $override->firstRow1('visit', 'outcome', 'id', 'client_id', $client['id'], 'visit_code', 'TV')[0]['outcome'];
                                                $type = $override->get('main_diagnosis', 'patient_id', $client['id'])[0];
                                                $site = $override->get('site', 'id', $client['site_id'])[0];
                                                $termination = $override->get3('summary', 'status', 1, 'patient_id', $client['id'], 'visit_type', 5)[0];
                                                $schedule = $override->get('schedule', 'id', $summary['visit_type'])[0];
                                                $outcome = $override->get('outcome', 'id', $termination['outcome'])[0];


                                                $category = 1;

                                                if ($type['cardiac'] == 1) {
                                                    $category = $override->countData('cardiac', 'patient_id', $client['id'], 'status', 1);
                                                } elseif ($type['diabetes'] == 1) {
                                                    $category = $override->countData('diabetic', 'patient_id', $client['id'], 'status', 1);
                                                } elseif ($type['sickle_cell'] == 1) {
                                                    $category = $override->countData('sickle_cell', 'patient_id', $client['id'], 'status', 1);
                                                } else {
                                                    $category = 1;
                                                }


                                                $demographic = $override->countData('demographic', 'patient_id', $client['id'], 'status', 1);
                                                $vital = $override->countData('vital', 'patient_id', $client['id'], 'status', 1);
                                                $history = $override->countData('history', 'patient_id', $client['id'], 'status', 1);
                                                $symptoms = $override->countData('symptoms', 'patient_id', $client['id'], 'status', 1);
                                                $diagnosis = $override->countData('main_diagnosis', 'patient_id', $client['id'], 'status', 1);
                                                $results = $override->countData('results', 'patient_id', $client['id'], 'status', 1);
                                                $hospitalization = $override->countData('hospitalization', 'patient_id', $client['id'], 'status', 1);
                                                $treatment_plan = $override->countData('treatment_plan', 'patient_id', $client['id'], 'status', 1);
                                                $dgns_complctns_comorbdts = $override->countData('dgns_complctns_comorbdts', 'patient_id', $client['id'], 'status', 1);
                                                $risks = $override->countData('risks', 'patient_id', $client['id'], 'status', 1);
                                                $hospitalization_details = $override->countData('hospitalization_details', 'patient_id', $client['id'], 'status', 1);
                                                $lab_details = $override->countData('lab_details', 'patient_id', $client['id'], 'status', 1);
                                                $summary = $override->countData('summary', 'patient_id', $client['id'], 'status', 1);
                                                $social_economic = $override->countData('social_economic', 'patient_id', $client['id'], 'status', 1);

                                                $Total_visit_available = 1;
                                                $Total_CRF_available = 1;
                                                $Total_CRF_required = 1;
                                                $progress = 1;

                                                $Total_visit_available1 = intval($override->getCount0('visit', 'client_id', $client['id'], 'seq_no', 1));
                                                $Total_visit_available2 = intval($override->getCountStatus('visit', 'client_id', $client['id'], 'seq_no', 1, 'visit_status', 1));
                                                $Total_visit_available3 = intval($override->getCountStatus('visit', 'client_id', $client['id'], 'seq_no', 1, 'visit_status', 2));
                                                $Total_visit_available4 = intval($override->getCountStatus1('visit', 'client_id', $client['id'], 'seq_no', 1, 'visit_status', 0, 'expected_date', date('Y-m-d')));
                                                $Total_visit_available5 = intval($override->getCountStatus1('visit', 'client_id', $client['id'], 'seq_no', 1, 'visit_status', 0, 'expected_date', date('Y-m-d')));



                                                $All_visits = $override->get0('visit', 'client_id', $client['id'], 'seq_no', 1);


                                                if ($Total_visit_available1 < 1) {
                                                    $Total_visit_available = 1;
                                                    $Total_CRF_available = 1;
                                                    $Total_CRF_available1 = 1;
                                                    $Total_CRF_available2 = 1;
                                                    $Total_CRF_required = 1;
                                                } elseif ($Total_visit_available1 == 1) {
                                                    foreach ($All_visits as $visit_day) {

                                                        if ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                                            $Total_visit_available = intval($Total_visit_available1);

                                                            $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_required = 15;
                                                        } elseif ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] > date('Y-m-d')) {
                                                            $Total_visit_available = intval($Total_visit_available1);

                                                            $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_required = 15;
                                                        } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                                            $Total_visit_available = intval($Total_visit_available1);

                                                            $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_required = 15;
                                                        } elseif ($visit_day['visit_status'] == 2) {
                                                            $Total_visit_available = 1;

                                                            $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_required = 1;
                                                        } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] > date('Y-m-d')) {
                                                            $Total_visit_available = 0;

                                                            $Total_CRF_available = 0;

                                                            $Total_CRF_required = 0;
                                                        }
                                                    }
                                                } elseif ($Total_visit_available1 > 1) {
                                                    foreach ($All_visits as $visit_day) {
                                                        if ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                                            $Total_visit_available++;
                                                            $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                                            $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                                            $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                                            $Total_CRF_required = $Total_CRF_required + 10;
                                                        } elseif ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] > date('Y-m-d')) {
                                                            $Total_visit_available++;
                                                            $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                                            $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                                            $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                                            $Total_CRF_required = $Total_CRF_required + 10;
                                                        } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                                            $Total_visit_available++;

                                                            $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                                            $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                                            $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                                            $Total_CRF_required = $Total_CRF_required + 10;
                                                        } elseif ($visit_day['visit_status'] == 2) {
                                                            $Total_visit_available++;

                                                            $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                                            $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                                            $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                                            $Total_CRF_required = $Total_CRF_required + 1;
                                                        } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] > date('Y-m-d')) {
                                                            $Total_visit_available = $Total_visit_available + 0;

                                                            $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                                            $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                                                + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                                                + intval($summary));

                                                            $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                                            $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                                            $Total_CRF_required = $Total_CRF_required + 0;
                                                        }
                                                    }

                                                    $Total_CRF_required = $Total_CRF_required + 5;


                                                    $Total_CRF_available = intval($Total_CRF_available1) + intval($Total_CRF_available2);
                                                }

                                                $client_progress = intval(intval($Total_CRF_available) / intval($Total_CRF_required) * 100);


                                                $enrollment_date = $override->firstRow2('visit', 'visit_date', 'id', 'client_id', $client['id'], 'seq_no', 1, 'visit_code', 'EV')[0]['visit_date'];

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
                                                    <td><?= $x ?></td>
                                                    <td><?= $client['clinic_date'] ?></td>
                                                    <td>
                                                        <?= $client['study_id'] ?>
                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                            /
                                                            <hr>
                                                            <?= $client['firstname'] . ' - ' . $client['middlename'] . ' - ' . $client['lastname'] ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td class="text-center">

                                                        <?php if ($_GET['status'] == 1) { ?>

                                                            <?php if ($client['eligible'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">Eligible</a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-danger">Not Eligible</a>
                                                            <?php }
                                                        } ?>

                                                        <?php if ($_GET['status'] == 2) { ?>

                                                            <?php if ($client['enrolled'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">Enrolled</a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-danger">Not Enrolled</a>
                                                            <?php }
                                                        } ?>

                                                        <?php if ($_GET['status'] == 3) { ?>

                                                            <?php if ($client['enrolled'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">Enrolled</a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-danger">Not Enrolled</a>
                                                            <?php }
                                                        } ?>

                                                        <?php if ($_GET['status'] == 4) { ?>
                                                            <a href="#" class="btn btn-danger">Terminated</a>
                                                            <?php
                                                        } ?>


                                                        <?php if ($_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>

                                                            <?php if ($client['screened'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">SCREENED</a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-danger">NOT SCREENED</a>
                                                            <?php }
                                                        } ?>

                                                        /
                                                        <hr>

                                                        <?php if ($type['cardiac'] == 1) { ?>
                                                            <a href="#" class="btn btn-default">Cardiac</a>
                                                        <?php } elseif ($type['diabetes'] == 1) { ?>
                                                            <a href="#" class="btn btn-info">Diabtes</a>
                                                        <?php } elseif ($type['sickle_cell'] == 1) { ?>
                                                            <a href="#" class="btn btn-success">Sickle Cell</a>
                                                        <?php } else { ?>
                                                            <a href="#" class="btn btn-warning">Not Diagnosised</a>
                                                            <?php
                                                        } ?>

                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) { ?>
                                                            /
                                                            <hr>
                                                            <?= $site['name'] ?>
                                                        <?php } ?>
                                                    </td>


                                                    <?php if ($_GET['status'] == 4) { ?>
                                                        <td class="text-center">
                                                            <a href="add.php?id=22&cid=<?= $client['id'] ?>&vid=<?= $termination['vid'] ?>&vcode=<?= $termination['visit_code'] ?>&seq=<?= $termination['seq_no'] ?>&sid=<?= $termination['study_id'] ?>&vday=<?= $termination['visit_day'] ?>&status=3"
                                                                class="btn btn-info">
                                                                <?= $outcome['name'] ?>
                                                            </a>
                                                            /
                                                            <hr>
                                                            <?= $termination['comments'] . ' , ' . $termination['remarks'] ?>
                                                        </td>

                                                    <?php } ?>


                                                    <?php if ($_GET['status'] == 1 || $_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>
                                                        <td class="text-center">

                                                            <?php if ($_GET['status'] == 5) { ?>
                                                                <a href="add.php?id=4&cid=<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-default">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Update
                                                                    <?php } else { ?> View<?php } ?>Registration Details
                                                                </a>
                                                                <br>
                                                                <hr>
                                                                <?php
                                                            } ?>

                                                            <?php if ($screened) { ?>

                                                                <a href="#addScreening<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-info" data-toggle="modal">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Update
                                                                    <?php } else { ?> View<?php } ?>
                                                                    Screening Details
                                                                </a>

                                                                <?php if ($screening['lab_request'] == 1) { ?>

                                                                    <a href="add_lab.php?cid=<?= $client['id'] ?>" class="btn btn-warning">
                                                                        <?php
                                                                        if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Add
                                                                        <?php } else { ?> View<?php } ?>
                                                                        Lab
                                                                    </a>
                                                                    <?php
                                                                } ?>
                                                            <?php } else { ?>

                                                                <a href="#addScreening<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Add
                                                                    <?php } else { ?> View<?php } ?>
                                                                    Screening Details </a>
                                                            <?php }
                                                    } ?>

                                                        <?php if ($_GET['status'] == 5) { ?>
                                                            <?php if ($user->data()->power == 1) { ?>
                                                                <br>
                                                                <hr>
                                                                <a href="#delete_client<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-danger" data-toggle="modal">Delete</a>
                                                                <?php
                                                            }
                                                        } ?>

                                                        <?php if ($_GET['status'] == 2) { ?>
                                                        <td class="text-center">

                                                            <?php if ($enrollment == 1) { ?>
                                                                <a href="#addEnrollment<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-info" data-toggle="modal">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Update
                                                                    <?php } else { ?> View<?php } ?>
                                                                    Enrollment Details
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="#addEnrollment<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Add
                                                                    <?php } else { ?> View<?php } ?>
                                                                    Enrollment Details
                                                                </a>
                                                            </td>

                                                        <?php }
                                                            ?>
                                                    <?php } ?>
                                                    <?php if ($_GET['status'] == 3) { ?>
                                                        <?php if ($enrollment == 1) { ?>
                                                            <td class="text-center">
                                                                <a href="info.php?id=4&cid=<?= $client['id'] ?>&status=<?= $_GET['status'] ?>"
                                                                    role="button" class="btn btn-warning">
                                                                    <?php
                                                                    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>Add
                                                                    <?php } else { ?> View<?php } ?>
                                                                    Study CRF's </a>
                                                                <?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { ?>
                                                                    <hr>
                                                                    <?php if ($client_progress == 100) { ?>
                                                                        <span class="badge badge-primary right">
                                                                            <?= $Total_CRF_available ?> out of <?= $Total_CRF_required ?>
                                                                        </span>
                                                                        <span class="badge badge-primary right">
                                                                            <?= $client_progress ?>%
                                                                        </span>
                                                                    <?php } elseif ($client_progress > 100) { ?>
                                                                        <span class="badge badge-warning right">
                                                                            <?= $Total_CRF_available ?> out of <?= $Total_CRF_required ?>
                                                                        </span>
                                                                        <span class="badge badge-warning right">
                                                                            <?= $client_progress ?>%
                                                                        </span>
                                                                    <?php } elseif ($client_progress >= 80 && $client_progress < 100) { ?>
                                                                        <span class="badge badge-info right">
                                                                            <?= $Total_CRF_available ?> out of <?= $Total_CRF_required ?>
                                                                        </span>
                                                                        <span class="badge badge-info right">
                                                                            <?= $client_progress ?>%
                                                                        </span>
                                                                    <?php } elseif ($client_progress >= 50 && $client_progress < 80) { ?>
                                                                        <span class="badge badge-secondary right">
                                                                            <?= $Total_CRF_available ?> out of <?= $Total_CRF_required ?>
                                                                        </span>
                                                                        <span class="badge badge-secondary right">
                                                                            <?= $client_progress ?>%
                                                                        </span>
                                                                    <?php } elseif ($client_progress < 50) { ?>
                                                                        <span class="badge badge-danger right">
                                                                            <?= $Total_CRF_available ?> out of <?= $Total_CRF_required ?>
                                                                        </span>
                                                                        <span class="badge badge-danger right">
                                                                            <?= $client_progress ?>%
                                                                        </span>
                                                                    <?php } ?>
                                                                <?php }
                                                                ?>

                                                            </td>
                                                            <?php
                                                        }
                                                    } ?>

                                                    <?php if ($_GET['status'] == 3) { ?>
                                                        <?php if ($enrollment == 1) { ?>
                                                            <td class="text-center">
                                                                <a href="summary.php?cid=<?= $client['id'] ?>" role="button"
                                                                    class="btn btn-primary">View Patient Summary</a>
                                                                <hr>
                                                                <span class="badge badge-secondary right">
                                                                    Visits Expected&nbsp; :
                                                                    &nbsp;&nbsp;&nbsp;<?= $Total_visit_available1 ?> <br>
                                                                </span><br>
                                                                <span class="badge badge-info right">
                                                                    Visits
                                                                    Done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                                                                    &nbsp;&nbsp;&nbsp;<?= $Total_visit_available2 ?> <br>
                                                                </span><br>
                                                                <span class="badge badge-danger right">
                                                                    Visits Missed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                                                                    &nbsp;&nbsp;&nbsp;<?= $Total_visit_available3 ?> <br>
                                                                </span><br>
                                                                <span class="badge badge-warning right">
                                                                    Visits Pending&nbsp;&nbsp;&nbsp; :
                                                                    &nbsp;&nbsp;&nbsp;<?= $Total_visit_available4 ?> <br>
                                                                </span><br>
                                                                <span class="badge badge-default right">
                                                                    Next Follow Up&nbsp;&nbsp;:
                                                                    &nbsp;&nbsp;&nbsp;<?= $Total_visit_available5 ?> <br>
                                                                </span>
                                                            </td>
                                                        <?php }
                                                    } ?>
                                                </tr>
                                                <div class="modal fade" id="addScreening<?= $client['id'] ?>">
                                                    <div class="modal-dialog">
                                                        <form method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">SCREENING FORM</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Date of Screening</label>
                                                                                    <input class="form-control" type="date"
                                                                                        max="<?= date('Y-m-d'); ?>"
                                                                                        type="screening_date"
                                                                                        name="screening_date"
                                                                                        id="screening_date" style="width: 100%;"
                                                                                        value="<?php if ($screening['screening_date']) {
                                                                                            print_r($screening['screening_date']);
                                                                                        } ?>" required />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Consenting individuals?</label>
                                                                            <!-- radio -->
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="radio" name="consent"
                                                                                            id="consent1" value="1" <?php if ($screening['consent'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> required>
                                                                                        <label
                                                                                            class="form-check-label">Yes</label>
                                                                                    </div>

                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="radio" name="consent"
                                                                                            id="consent2" value="2" <?php if ($screening['consent'] == 2) {
                                                                                                echo 'checked';
                                                                                            } ?>>
                                                                                        <label
                                                                                            class="form-check-label">No</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-sm-6" id="conset_date1">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Date of Conset</label>
                                                                                    <input class="form-control" type="date"
                                                                                        max="<?= date('Y-m-d'); ?>"
                                                                                        type="conset_date" name="conset_date"
                                                                                        id="conset_date" style="width: 100%;"
                                                                                        value="<?php if ($screening['conset_date']) {
                                                                                            print_r($screening['conset_date']);
                                                                                        } ?>" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6">
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <label>Permanent resident?</label>
                                                                                    <select class="form-control"
                                                                                        name="residence" style="width: 100%;"
                                                                                        required>
                                                                                        <option
                                                                                            value="<?= $screening['residence'] ?>">
                                                                                            <?php if ($screening['residence']) {
                                                                                                if ($screening['residence'] == 1) {
                                                                                                    echo 'Yes';
                                                                                                } elseif ($screening['residence'] == 2) {
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
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <label>Known NCD?</label>
                                                                                    <select class="form-control" name="ncd"
                                                                                        style="width: 100%;" required>
                                                                                        <option
                                                                                            value="<?= $screening['ncd'] ?>">
                                                                                            <?php if ($screening['ncd']) {
                                                                                                if ($screening['ncd'] == 1) {
                                                                                                    echo 'Yes';
                                                                                                } elseif ($screening['ncd'] == 2) {
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
                                                                            <label>Request Lab Test ?</label>
                                                                            <!-- radio -->
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="radio" name="lab_request"
                                                                                            id="lab_request1" value="1" <?php if ($screening['lab_request'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> required>
                                                                                        <label
                                                                                            class="form-check-label">Yes</label>
                                                                                    </div>

                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="radio" name="lab_request"
                                                                                            id="lab_request2" value="2" <?php if ($screening['lab_request'] == 2) {
                                                                                                echo 'checked';
                                                                                            } ?>>
                                                                                        <label
                                                                                            class="form-check-label">No</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-sm-6" id="lab_request_date1">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Date of Request</label>
                                                                                    <input class="form-control" type="date"
                                                                                        max="<?= date('Y-m-d'); ?>"
                                                                                        type="lab_request_date"
                                                                                        name="lab_request_date"
                                                                                        id="lab_request_date"
                                                                                        style="width: 100%;" value="<?php if ($screening['lab_request_date']) {
                                                                                            print_r($screening['lab_request_date']);
                                                                                        } ?>" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <label>Type of Screening</label>
                                                                                    <select class="form-control"
                                                                                        name="screening_type"
                                                                                        style="width: 100%;" required>
                                                                                        <option
                                                                                            value="<?= $screening['screening_type'] ?>">
                                                                                            <?php if ($screening['screening_type']) {
                                                                                                if ($screening['screening_type'] == 1) {
                                                                                                    echo 'Facility';
                                                                                                } elseif ($screening['ncd'] == 2) {
                                                                                                    echo 'Community';
                                                                                                }
                                                                                            } else {
                                                                                                echo 'Select';
                                                                                            } ?>
                                                                                        </option>
                                                                                        <option value="1">Facility</option>
                                                                                        <option value="2">Community</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input type="hidden" name="id"
                                                                        value="<?= $screening['id'] ?>">
                                                                    <input type="hidden" name="cid"
                                                                        value="<?= $client['id'] ?>">
                                                                    <input type="hidden" name="gender"
                                                                        value="<?= $client['gender'] ?>">
                                                                    <input type="hidden" name="study_id"
                                                                        value="<?= $client['study_id'] ?>">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close</button>
                                                                    <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                        <input type="submit" name="add_screening"
                                                                            class="btn btn-primary" value="Submit">
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal fade" id="addEnrollment<?= $client['id'] ?>">
                                                    <div class="modal-dialog">
                                                        <form method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Visit</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <?php
                                                                $enrollment = $override->getNews('visit', 'client_id', $client['id'], 'seq_no', 1)[0];
                                                                ?>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="row-form clearfix">
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Date of Enrollment</label>
                                                                                    <input class="form-control" type="date"
                                                                                        max="<?= date('Y-m-d'); ?>"
                                                                                        type="visit_date" name="visit_date"
                                                                                        id="visit_date" style="width: 100%;"
                                                                                        value="<?php if ($enrollment['visit_date']) {
                                                                                            print_r($enrollment['visit_date']);
                                                                                        } ?>" required />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-8">
                                                                            <div class="row-form clearfix">
                                                                                <div class="form-group">
                                                                                    <label>Notes / Remarks / Comments</label>
                                                                                    <textarea class="form-control"
                                                                                        name="reasons" rows="3">
                                                                                                                                                                                                                                                                                         <?php
                                                                                                                                                                                                                                                                                         if ($enrollment['reasons']) {
                                                                                                                                                                                                                                                                                             print_r($enrollment['reasons']);
                                                                                                                                                                                                                                                                                         } ?>
                                                                                                                                                                                                                                                                                        </textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                    <input type="hidden" name="visit_name"
                                                                        value="Enrollment Visit">
                                                                    <input type="hidden" name="study_id"
                                                                        value="<?= $client['study_id'] ?>">
                                                                    <input type="hidden" name="site_id"
                                                                        value="<?= $client['site_id'] ?>">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close</button>
                                                                    <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                        <input type="submit" name="add_Enrollment"
                                                                            class="btn btn-primary" value="Submit">
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal fade" id="delete_client<?= $client['id'] ?>" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"><span
                                                                            aria-hidden="true">&times;</span><span
                                                                            class="sr-only">Close</span></button>
                                                                    <h4>Delete Client</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <strong style="font-weight: bold;color: red">
                                                                        <p>Are you sure you want to delete this Client ?</p>
                                                                    </strong>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                                    <?php if ($user->data()->accessLevel == 1) { ?>
                                                                        <input type="submit" name="delete_client" value="Delete"
                                                                            class="btn btn-danger">
                                                                    <?php } ?>
                                                                    <button class="btn btn-default" data-dismiss="modal"
                                                                        aria-hidden="true">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <?php $x++;
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <!-- Previous Page -->
                                        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                        </li>

                                        <!-- First Page (if outside the range) -->
                                        <?php if ($start > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=1">1</a>
                                            </li>
                                            <?php if ($start > 2): ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Visible Page Links -->
                                        <?php for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <!-- Last Page (if outside the range) -->
                                        <?php if ($end < $pages): ?>
                                            <?php if ($end < $pages - 1): ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <!-- Next Page -->
                                        <li class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 4) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Participant Schedules</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Participant Schedules</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <?php
                                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                                        $visits_status = $override->firstRow1('visit', 'status', 'id', 'client_id', $_GET['cid'], 'visit_code', 'EV')[0]['status'];

                                        $required_visit = $override->countData1('visit', 'status', 1, 'client_id', $_GET['cid'], 'seq_no', $_GET['seq']);


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

                                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['middlename'] . ' ' . $patient['lastname'];

                                        ?>


                                        <div class="row mb-2">
                                            <div class="col-sm-6">
                                                <h1>Study ID: <?= $patient['study_id'] ?></h1>
                                                <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                    <h4>Name: <?= $name ?></h4>
                                                <?php } ?>
                                                <h4>Age: <?= $patient['age'] ?></h4>
                                                <h4>Gender: <?= $gender ?></h4>
                                                <h4>Category: <?= $cat ?></h4>
                                            </div>
                                            <div class="col-sm-6">
                                                <ol class="breadcrumb float-sm-right">
                                                    <li class="breadcrumb-item"><a
                                                            href="info.php?id=3&status=<?= $_GET['status'] ?>">
                                                            < Back</a>
                                                    </li>
                                                    <li class="breadcrumb-item"><a href="#">
                                                            <?php if ($visit['seq_no'] >= 1) {
                                                                $summary = '';
                                                                ?>
                                                                <?php
                                                                //  if ($visit['visit_status']) {
                                                                ?>
                                                                <a href="#addSchedule<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-info" data-toggle="modal">Update</a>
                                                            <?php } else {
                                                                $summary = 1;
                                                                ?>
                                                                <a href="index1.php">Go Home</a>
                                                                <?php
                                                                //  }
                                                            } ?>
                                                        </a>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Visit Day</th>
                                                    <th>Expected Date</th>
                                                    <th>Visit Date</th>
                                                    <th>Status</th>
                                                    <th>Action ( Completion )</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                $currentSite = $_GET['site_id'];
                                                $itemsPerPage = $numRec; // Set this to the number of records you display per page
                                            
                                                // Calculate the starting number for the current page
                                                $startNumber = ($currentPage - 1) * $itemsPerPage + 1;

                                                // Pagination range
                                                $range = 2;
                                                $start = max(1, $currentPage - $range);
                                                $end = min($pages, $currentPage + $range);

                                                // Initialize $x using $startNumber
                                                $x = $startNumber;

                                                $datas = $override->getNewsAsc022('visit', 'status', 1, 'client_id', $_GET['cid'], 'seq_no');
                                                foreach ($datas as $visit) {
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



                                                    $category = 1;

                                                    if ($diagnosis[0]['cardiac'] == 1) {
                                                        $category = $override->countData1('cardiac', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    } elseif ($diagnosis[0]['diabetes'] == 1) {
                                                        $category = $override->countData('diabetic', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    } elseif ($diagnosis[0]['sickle_cell'] == 1) {
                                                        $category = $override->countData('sickle_cell', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    } else {
                                                        $category = 1;
                                                    }


                                                    $demographic1 = $override->countData1('demographic', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $vital1 = $override->countData1('vital', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $history1 = $override->countData1('history', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $symptoms1 = $override->countData1('symptoms', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $diagnosis1 = $override->countData1('main_diagnosis', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $results1 = $override->countData1('results', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $hospitalization1 = $override->countData1('hospitalization', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $treatment_plan1 = $override->countData1('treatment_plan', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $dgns_complctns_comorbdts1 = $override->countData1('dgns_complctns_comorbdts', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $risks1 = $override->countData1('risks', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $hospitalization_details1 = $override->countData1('hospitalization_details', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $lab_details1 = $override->countData1('lab_details', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $summary1 = $override->countData1('summary', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);
                                                    $social_economic1 = $override->countData1('social_economic', 'patient_id', $visit['client_id'], 'status', 1, 'seq_no', $visit['seq_no']);


                                                    $total_required = 1;

                                                    if ($visit['seq_no'] == 1) {
                                                        $total_required = 15;
                                                        if ($visit['visit_status'] == 1 && $visit['expected_date'] <= date('Y-m-d')) {
                                                            $total_available = intval($category) + intval($demographic1) + intval($vital1) + intval($history1) + intval($symptoms1) + intval($diagnosis1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1) + intval($social_economic1);
                                                        } elseif ($visit['visit_status'] == 0 && $visit['expected_date'] <= date('Y-m-d')) {
                                                            $total_available = intval($category) + intval($demographic1) + intval($vital1) + intval($history1) + intval($symptoms1) + intval($diagnosis1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1) + intval($social_economic1);
                                                        } elseif ($visit['visit_status'] == 1 && $visit['expected_date'] > date('Y-m-d')) {
                                                            $total_available = intval($category) + intval($demographic1) + intval($vital1) + intval($history1) + intval($symptoms1) + intval($diagnosis1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1) + intval($social_economic1);
                                                        } elseif ($visit['visit_status'] == 2) {
                                                            $total_available = intval($summary1);
                                                            $total_required = 1;
                                                        } elseif ($visit['visit_status'] == 0 && $visit['expected_date'] > date('Y-m-d')) {
                                                            $total_available = 1;
                                                            $total_required = 1;
                                                        }

                                                        if ($total_required != 0) {
                                                            $progress = intval((intval($total_available) / $total_required) * 100);
                                                        } else {
                                                            // Handle the case when divisor is zero, maybe return a default value or error message
                                                            $progress = 1; // or handle it in another appropriate way
                                                        }


                                                    } elseif ($visit['seq_no'] > 1) {
                                                        $total_required = 10;
                                                        if ($visit['visit_status'] == 1 && $visit['expected_date'] <= date('Y-m-d')) {
                                                            $total_available = intval($vital1) + intval($symptoms1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1);
                                                        } elseif ($visit['visit_status'] == 0 && $visit['expected_date'] <= date('Y-m-d')) {
                                                            $total_available = intval($vital1) + intval($symptoms1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1);
                                                        } elseif ($visit['visit_status'] == 1 && $visit['expected_date'] > date('Y-m-d')) {
                                                            $total_available = intval($vital1) + intval($symptoms1) + intval($results1) + intval($hospitalization1)
                                                                + intval($treatment_plan1) + intval($dgns_complctns_comorbdts1) + intval($risks1) + intval($hospitalization_details1) + intval($lab_details1)
                                                                + intval($summary1);
                                                        } elseif ($visit['visit_status'] == 2) {
                                                            $total_available = intval($summary1);
                                                            $total_required = 1;
                                                        } elseif ($visit['visit_status'] == 0 && $visit['expected_date'] > date('Y-m-d')) {
                                                            $total_available = 1;
                                                            $total_required = 1;
                                                        }

                                                        if ($total_required != 0) {
                                                            $progress = intval((intval($total_available) / $total_required) * 100);
                                                        } else {
                                                            // Handle the case when divisor is zero, maybe return a default value or error message
                                                            $progress = 1; // or handle it in another appropriate way
                                                        }
                                                    }


                                                    if ($visit['status'] == 0) {
                                                        $btnV = 'Add';
                                                    } elseif ($visit['status'] == 1) {
                                                        $btnV = 'Edit';
                                                    }

                                                    $visit_name = $visit['visit_name'];

                                                    ?>
                                                    <tr>
                                                        <td> <?= $x ?></td>
                                                        <td>
                                                            <?= $visit['visit_day'] ?><br>
                                                            <?php if ($visit['seq_no'] == -1) {
                                                                echo '( Registration )';
                                                            } elseif ($visit['seq_no'] == 0) {
                                                                echo '( Screening )';
                                                            } elseif ($visit['seq_no'] == 1) {
                                                                echo '( Enrollment )';
                                                            } elseif ($visit['seq_no'] > 1) {
                                                                echo '( Follow Up )';
                                                            } ?>
                                                        </td>
                                                        <td> <?= $visit['expected_date'] ?></td>
                                                        <td> <?= $visit['visit_date'] ?> </td>
                                                        <td>
                                                            <?php if ($visit['visit_status'] == 1) { ?>
                                                                <a href="#AddVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-success" data-toggle="modal">Done</a>
                                                            <?php } elseif ($visit['visit_status'] == 0) { ?>
                                                                <a href="#AddVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Pending</a>
                                                            <?php } elseif ($visit['visit_status'] == 2) { ?>
                                                                <a href="#AddVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-danger" data-toggle="modal">Missed</a>
                                                            <?php } else { ?>
                                                                <a href="#AddVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Pending</a>
                                                            <?php } ?>
                                                        <td>
                                                            <?php if ($visit['visit_code'] == 'EV') { ?>

                                                                <?php if (($visit['visit_status'] == 1 || $visit['visit_status'] == 2) && ($visit['visit_code'] == 'EV' || $visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                    <?php if ($demographic && $vital && $history && $symptoms && $diagnosis && $results && $hospitalization && $treatment_plan && $dgns_complctns_comorbdts && $risks && $hospitalization_details && $lab_details && $social_economic && $summary) { ?>
                                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role=" button" class="btn btn-info"> Edit Study Forms </a>
                                                                        <?php } else { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role=" button" class="btn btn-info"> View Study Forms </a>
                                                                        <?php } ?>
                                                                        <?php
                                                                        // if ($user->data()->power == 1 || $user->data()->accessLevel == 1) {
                                                                        ?>
                                                                        <hr>
                                                                        <?php if ($progress == 100) { ?>
                                                                            <span class="badge badge-primary right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-primary right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress > 100) { ?>
                                                                            <span class="badge badge-warning right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-warning right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress >= 80 && $progress < 100) { ?>
                                                                            <span class="badge badge-info right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-info right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress >= 50 && $progress < 80) { ?>
                                                                            <span class="badge badge-secondary right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-secondary right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress < 50) { ?>
                                                                            <span class="badge badge-danger right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-danger right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } ?>
                                                                        <?php
                                                                        //  }
                                                                        ?>

                                                                    <?php } else { ?>
                                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role=" button" class="btn btn-warning"> Fill Study Forms </a>
                                                                        <?php } else { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role=" button" class="btn btn-warning"> View Study Forms </a>
                                                                        <?php } ?>
                                                                        <?php
                                                                        //  if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { 
                                                                        ?>
                                                                        <hr>
                                                                        <?php if ($progress == 100) { ?>
                                                                            <span class="badge badge-primary right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-primary right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress > 100) { ?>
                                                                            <span class="badge badge-warning right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-warning right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress >= 80 && $progress < 100) { ?>
                                                                            <span class="badge badge-info right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-info right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress >= 50 && $progress < 80) { ?>
                                                                            <span class="badge badge-secondary right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-secondary right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } elseif ($progress < 50) { ?>
                                                                            <span class="badge badge-danger right">
                                                                                <?= $total_available ?> out of <?= $total_required ?>
                                                                            </span>
                                                                            <span class="badge badge-danger right">
                                                                                <?= $progress ?>%
                                                                            </span>
                                                                        <?php } ?>
                                                                        <?php
                                                                        //  }
                                                                        ?>
                                                                    <?php }
                                                                }
                                                            }
                                                            ?>


                                                            <?php if (($visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                <?php if (($visit['visit_status'] == 1 || $visit['visit_status'] == 2) && ($visit['visit_code'] == 'EV' || $visit['visit_code'] == 'FV' || $visit['visit_code'] == 'TV' || $visit['visit_code'] == 'UV')) { ?>

                                                                    <?php if ($vital && $symptoms && $results && $hospitalization && $treatment_plan && $dgns_complctns_comorbdts && $risks && $hospitalization_details && $lab_details && $summary) { ?>
                                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role="button" class="btn btn-info"> Edit Study Forms </a>
                                                                        <?php } else { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role="button" class="btn btn-info"> View Study Forms </a>
                                                                        <?php } ?>
                                                                        <?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { ?>
                                                                            <hr>
                                                                            <?php if ($progress == 100) { ?>
                                                                                <span class="badge badge-primary right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-primary right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress > 100) { ?>
                                                                                <span class="badge badge-warning right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-warning right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress >= 80 && $progress < 100) { ?>
                                                                                <span class="badge badge-info right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-info right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress >= 50 && $progress < 80) { ?>
                                                                                <span class="badge badge-secondary right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-secondary right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress < 50) { ?>
                                                                                <span class="badge badge-danger right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-danger right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } ?>
                                                                        <?php }
                                                                        ?>

                                                                    <?php } else { ?>
                                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role="button" class="btn btn-warning"> Fill Study Forms </a>
                                                                        <?php } else { ?>
                                                                            <a href="info.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $visit['id'] ?>&vcode=<?= $visit['visit_code'] ?>&seq=<?= $visit['seq_no'] ?>&sid=<?= $visit['study_id'] ?>&vday=<?= $visit['visit_day'] ?>&status=<?= $_GET['status'] ?>"
                                                                                role="button" class="btn btn-warning"> View Study Forms </a>
                                                                        <?php } ?>
                                                                        <?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { ?>
                                                                            <hr>
                                                                            <?php if ($progress == 100) { ?>
                                                                                <span class="badge badge-primary right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-primary right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress > 100) { ?>
                                                                                <span class="badge badge-warning right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-warning right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress >= 80 && $progress < 100) { ?>
                                                                                <span class="badge badge-info right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-info right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress >= 50 && $progress < 80) { ?>
                                                                                <span class="badge badge-secondary right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-secondary right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } elseif ($progress < 50) { ?>
                                                                                <span class="badge badge-danger right">
                                                                                    <?= $total_available ?> out of <?= $total_required ?>
                                                                                </span>
                                                                                <span class="badge badge-danger right">
                                                                                    <?= $progress ?>%
                                                                                </span>
                                                                            <?php } ?>
                                                                        <?php }
                                                                        ?>
                                                                    <?php }
                                                                }
                                                            }
                                                            if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { ?>
                                                                <hr>
                                                                <a href="#updateVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-info" data-toggle="modal">Update Expected
                                                                    Date</a>
                                                                <?php if ($user->data()->power == 1) { ?>
                                                                    <hr>

                                                                    <a href="#deleteVisit<?= $visit['id'] ?>" role="button"
                                                                        class="btn btn-danger" data-toggle="modal">Delete Visit</a>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade" id="AddVisit<?= $visit['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Add Visit Details</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <?php $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                                                        ?>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Visit Date</label>
                                                                                        <input class="form-control"
                                                                                            max="<?= date('Y-m-d'); ?>"
                                                                                            type="date" name="visit_date"
                                                                                            id="visit_date" style="width: 100%;"
                                                                                            value="<?php if ($visit['visit_date']) {
                                                                                                print_r($visit['visit_date']);
                                                                                            } ?>" required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="row-form clearfix">
                                                                                    <div class="form-group">
                                                                                        <label>Status</label>
                                                                                        <select id="visit_status"
                                                                                            name="visit_status"
                                                                                            class="form-control" required>
                                                                                            <option value="<?= $visit['id'] ?>">
                                                                                                <?php if ($visit['visit_status']) {
                                                                                                    if ($visit['visit_status'] == 1) {
                                                                                                        echo 'Attended';
                                                                                                    } else if ($visit['visit_status'] == 2) {
                                                                                                        echo 'Missed';
                                                                                                    } else if ($visit['visit_status'] == 0) {
                                                                                                        echo 'Pending';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?>
                                                                                            </option>
                                                                                            <option value="1">Attended</option>
                                                                                            <option value="2">Missed</option>
                                                                                            <option value="0">Pending</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>comments / remarks:</label>
                                                                                        <textarea class="form-control"
                                                                                            name="reasons" rows="3"
                                                                                            placeholder="Type reason / comments here..."
                                                                                            required>
                                                                                                                                                                                                                                                                                                    <?php if ($visit['reasons']) {
                                                                                                                                                                                                                                                                                                        print_r($visit['reasons']);
                                                                                                                                                                                                                                                                                                    } ?>
                                                                                                                                                                                                                                                                                                </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $visit['id'] ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                                            <input type="submit" name="add_visit"
                                                                                class="btn btn-primary" value="Submit">
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->

                                                    <div class="modal fade" id="updateVisit<?= $visit['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Visit Details</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <?php $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                                                    ?>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Expected Date</label>
                                                                                        <input class="form-control" type="date"
                                                                                            name="expected_date"
                                                                                            id="expected_date"
                                                                                            style="width: 100%;" value="<?php if ($visit['expected_date']) {
                                                                                                print_r($visit['expected_date']);
                                                                                            } ?>" required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $visit['id'] ?>">
                                                                        <input type="hidden" name="summary_id"
                                                                            value="<?= $visit['summary_id'] ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <input type="submit" name="update_visit"
                                                                            class="btn btn-primary" value="Save changes">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->

                                                    <div class="modal fade" id="deleteVisit<?= $visit['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span
                                                                                aria-hidden="true">&times;</span><span
                                                                                class="sr-only">Close</span></button>
                                                                        <h4>Delete Visit</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <strong style="font-weight: bold;color: red">
                                                                            <p>Are you sure you want to delete this Visit ?</p>
                                                                        </strong>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $visit['id'] ?>">
                                                                        <input type="hidden" name="cid"
                                                                            value="<?= $visit['client_id'] ?>">
                                                                        <input type="hidden" name="seq_no"
                                                                            value="<?= $visit['seq_no'] ?>">
                                                                        <input type="submit" name="delete_visit" value="Delete"
                                                                            class="btn btn-danger">
                                                                        <button class="btn btn-default" data-dismiss="modal"
                                                                            aria-hidden="true">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Visit Day</th>
                                                    <th>Expected Date</th>
                                                    <th>Visit Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <!-- Previous Page -->
                                            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link"
                                                    href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                            </li>

                                            <!-- First Page (if outside the range) -->
                                            <?php if ($start > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=1">1</a>
                                                </li>
                                                <?php if ($start > 2): ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <!-- Visible Page Links -->
                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                    <a class="page-link"
                                                        href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- Last Page (if outside the range) -->
                                            <?php if ($end < $pages): ?>
                                                <?php if ($end < $pages - 1): ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <!-- Next Page -->
                                            <li class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                <a class="page-link"
                                                    href="info.php?id=<?= $_GET['id']; ?>&status=<?= $_GET['status']; ?>&site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 5) { ?>
            <?php
            $AllTables = $override->AllTables();
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>STUDY ID FORM ( SET STUDY ID )</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">STUDY ID FORM ( SET STUDY ID )</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <?php if ($user->data()->power == 1) { ?>
                                <!-- left column -->
                                <div class="col-md-4">
                                    <!-- general form elements disabled -->
                                    <div class="card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Add STUDY ID </h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <label for="forms">FULL NAME ( STUDY ID )</label>
                                                                <select name="client_id" class="form-control"
                                                                    style="width: 100%;" required>
                                                                    <option value="">Select Name</option>
                                                                    <?php foreach ($override->get('clients', 'status', 1) as $client) { ?>
                                                                        <option value="<?= $client['id'] ?>">
                                                                            <?= $client['id'] . ' - ( ' . $client['study_id'] . ' - ' . $client['firstname'] . ' - ' . $client['middelname'] . ' - ' . $client['lastname'] . ' ) ' ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer">
                                                <a href='index1.php' class="btn btn-default">Back</a>
                                                <input type="hidden" name="study_id" value="<?= $client['id']; ?>">
                                                <input type="submit" name="set_study_id" value="Submit" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!--/.col (left) -->
                            <?php } ?>

                            <!-- Center column -->
                            <div class="col-md-4">
                                <!-- general form elements disabled -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Update STUDY ID </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="table_name" class="form-label">TABLE NAME</label>
                                                        <select name="table_name" id="table_name" class="form-control"
                                                            required>
                                                            <option value="">Select Table</option>
                                                            <?php $x = 1;
                                                            foreach ($AllTables as $tables) {
                                                                if (
                                                                    $tables['Tables_in_penplus'] == 'clients' || $tables['Tables_in_penplus'] == 'screening' ||
                                                                    $tables['Tables_in_penplus'] == 'demographic' || $tables['Tables_in_penplus'] == 'vital' ||
                                                                    $tables['Tables_in_penplus'] == 'main_diagnosis' || $tables['Tables_in_penplus'] == 'history' ||
                                                                    $tables['Tables_in_penplus'] == 'symptoms' || $tables['Tables_in_penplus'] == 'cardiac' ||
                                                                    $tables['Tables_in_penplus'] == 'diabetic' || $tables['Tables_in_penplus'] == 'sickle_cell' ||
                                                                    $tables['Tables_in_penplus'] == 'results' || $tables['Tables_in_penplus'] == 'cardiac' ||
                                                                    $tables['Tables_in_penplus'] == 'hospitalization' || $tables['Tables_in_penplus'] == 'hospitalization_details' ||
                                                                    $tables['Tables_in_penplus'] == 'treatment_plan' || $tables['Tables_in_penplus'] == 'dgns_complctns_comorbdts' ||
                                                                    $tables['Tables_in_penplus'] == 'risks' || $tables['Tables_in_penplus'] == 'lab_details' ||
                                                                    $tables['Tables_in_penplus'] == 'social_economic' || $tables['Tables_in_penplus'] == 'summary' ||
                                                                    $tables['Tables_in_penplus'] == 'medication_treatments' || $tables['Tables_in_penplus'] == 'hospitalization_detail_id' ||
                                                                    $tables['Tables_in_penplus'] == 'sickle_cell_status_table' || $tables['Tables_in_penplus'] == 'visit' ||
                                                                    $tables['Tables_in_penplus'] == 'lab_requests'
                                                                ) { ?>
                                                                    <option value="<?= $tables['Tables_in_penplus'] ?>">
                                                                        <?= $x . ' - ' . $tables['Tables_in_penplus'] ?>
                                                                    </option>
                                                                <?php }
                                                                $x++;
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label for="forms">FULL NAME ( STUDY ID )</label>
                                                            <select name="client_id" class="form-control"
                                                                style="width: 100%;" required>
                                                                <option value="">Select Name</option>
                                                                <?php foreach ($override->get('clients', 'status', 1) as $client) { ?>
                                                                    <option value="<?= $client['id'] ?>">
                                                                        <?= $client['id'] . ' - ( ' . $client['study_id'] . ' - ' . $client['firstname'] . ' - ' . $client['middelname'] . ' - ' . $client['lastname'] . ' ) ' ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <a href='index1.php' class="btn btn-default">Back</a>
                                            <input type="submit" name="update_study_id" value="Submit"
                                                class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (Center) -->

                            <!-- right column -->
                            <div class="col-md-4">
                                <!-- general form elements disabled -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Update STUDY ID (ALL TABLES) </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label for="forms">( PATIENT ID ) FULL NAME ( STUDY ID )</label>
                                                            <select name="patient_id" class="form-control"
                                                                style="width: 100%;" required>
                                                                <option value="">Select Name</option>
                                                                <?php foreach ($override->get('clients', 'status', 1) as $client) { ?>
                                                                    <option value="<?= $client['id'] ?>">
                                                                        <?= $client['id'] . ' - ( ' . $client['study_id'] . ' - ' . $client['firstname'] . ' - ' . $client['middelname'] . ' - ' . $client['lastname'] . ' ) ' ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <a href='index1.php' class="btn btn-default">Back</a>
                                            <input type="submit" name="update_study_id_all_tables" value="Submit"
                                                class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 6) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>STUDY ID FORM ( UNSET STUDY ID )</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">STUDY ID FORM ( UNSET STUDY ID )</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- right column -->
                            <div class="col-md-12">
                                <!-- general form elements disabled -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Remove STUDY ID </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label for="forms">FULL NAME ( STUDY ID )</label>
                                                            <select name="client_id" class="form-control"
                                                                style="width: 100%;" required>
                                                                <option value="">Select Name</option>
                                                                <?php foreach ($override->get('clients', 'status', 1) as $client) { ?>
                                                                    <option value="<?= $client['id'] ?>">
                                                                        <?= $client['id'] . ' - ( ' . $client['study_id'] . ' - ' . $client['firstname'] . ' - ' . $client['middelname'] . ' - ' . $client['lastname'] . ' ) ' ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <a href='index1.php' class="btn btn-default">Back</a>
                                            <input type="submit" name="unset_study_id" value="Submit"
                                                class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 7) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Participants Study CRF's</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Participants Study CRF's</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- <?php if ($errorMessage) { ?>
                                    <div class="alert alert-danger text-center">
                                        <h4>Error!</h4>
                                        <?= $errorMessage ?>
                                    </div>
                                <?php } elseif ($pageError) { ?>
                                    <div class="alert alert-danger text-center">
                                        <h4>Error!</h4>
                                        <?php foreach ($pageError as $error) {
                                            echo $error . ' , ';
                                        } ?>
                                    </div>
                                <?php } elseif ($_GET['msg']) { ?>
                                    <div class="alert alert-success text-center">
                                        <h4>Success!</h4>
                                        <?= $_GET['msg'] ?>
                                    </div>
                                <?php } ?> -->
                                <div class="card">
                                    <div class="card-header">
                                        <?php
                                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                                        $visits_status = $override->firstRow1('visit', 'status', 'id', 'client_id', $_GET['cid'], 'visit_code', 'EV')[0]['status'];

                                        $required_visit = $override->countData1('visit', 'status', 1, 'client_id', $_GET['cid'], 'seq_no', $_GET['seq']);

                                        $status = $override->get3('visit', 'client_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'id', $_GET['vid'])[0];


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

                                        $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['middlename'] . ' ' . $patient['lastname'];

                                        ?>

                                        <div class="row mb-2">
                                            <div class="col-sm-6">
                                                <h1>Study ID: <?= $patient['study_id'] ?></h1>
                                                <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                                    <h4>Name: <?= $name ?></h4>
                                                    <?php
                                                } ?>
                                                <h4>Age: <?= $patient['age'] ?></h4>
                                                <h4>Gender: <?= $gender ?></h4>
                                                <h4>Category: <?= $cat ?></h4>
                                            </div>
                                            <div class="col-sm-6">
                                                <ol class="breadcrumb float-sm-right">
                                                    <li class="breadcrumb-item"><a
                                                            href="info.php?id=4&cid=<?= $_GET['cid'] ?>&status=<?= $_GET['status'] ?>">
                                                            < Back</a>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>CRF</th>
                                                    <th>Records ( Completion )</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($status['visit_status'] == 1) { ?>
                                                    <tr>
                                                        <?php if ($_GET['seq'] == 1) { ?>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Demographic</td>
                                                            <td>
                                                                <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                <?php $demographic = intval($override->countData1('demographic', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                <span class="badge badge-info right">
                                                                    <?= $demographic ?> out of 1
                                                                </span>
                                                                <?php $demographic = intval($demographic / 1) * 100 ?>
                                                                <?php if ($demographic == 100) { ?>
                                                                    <span class="badge badge-primary right">
                                                                        <?= $demographic ?>%
                                                                    </span>
                                                                <?php } elseif ($demographic > 100) { ?>
                                                                    <span class="badge badge-warning right">
                                                                        <?= $demographic ?>%
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger right">
                                                                        <?= $demographic ?>%
                                                                    </span>
                                                                <?php } ?>
                                                            </td>
                                                            <?php if ($override->get3('demographic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                                <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-success"> Change </a> </td>
                                                            <?php } else { ?>
                                                                <td><a href="add.php?id=7&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-warning"> Add </a> </td>
                                                            <?php } ?>
                                                        </tr>

                                                    <?php } ?>

                                                    <tr>
                                                        <td>2</td>
                                                        <td>Vitals</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $vital = intval($override->countData1('vital', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $vital ?> out of 1
                                                            </span>
                                                            <?php $vital = intval($vital / 1) * 100 ?>
                                                            <?php if ($vital == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $vital ?>%
                                                                </span>
                                                            <?php } elseif ($vital > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $vital ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $vital ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('vital', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                            <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=8&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>
                                                    </tr>

                                                    <?php if ($_GET['seq'] == 1) { ?>

                                                        <tr>
                                                            <td>3</td>
                                                            <td>Pateint Category</td>
                                                            <td>
                                                                <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                <?php $main_diagnosis = intval($override->countData1('main_diagnosis', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                <span class="badge badge-info right">
                                                                    <?= $main_diagnosis ?> out of 1
                                                                </span>
                                                                <?php $main_diagnosis = intval($main_diagnosis / 1) * 100 ?>
                                                                <?php if ($main_diagnosis == 100) { ?>
                                                                    <span class="badge badge-primary right">
                                                                        <?= $main_diagnosis ?>%
                                                                    </span>
                                                                <?php } elseif ($main_diagnosis > 100) { ?>
                                                                    <span class="badge badge-warning right">
                                                                        <?= $main_diagnosis ?>%
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger right">
                                                                        <?= $main_diagnosis ?>%
                                                                    </span>
                                                                <?php } ?>
                                                            </td>
                                                            <?php if ($override->get3('main_diagnosis', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>
                                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-success"> Change </a> </td>
                                                            <?php } else { ?>
                                                                <td><a href="add.php?id=9&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-warning"> Add </a> </td>
                                                            <?php } ?>
                                                        </tr>

                                                    <?php } ?>


                                                    <?php if ($_GET['seq'] == 1) { ?>

                                                        <tr>
                                                            <td>4</td>
                                                            <td>Patient Hitory & Family History & Complication</td>
                                                            <td>
                                                                <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                <?php $history = intval($override->countData1('history', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                <span class="badge badge-info right">
                                                                    <?= $history ?> out of 1
                                                                </span>
                                                                <?php $history = intval($history / 1) * 100 ?>
                                                                <?php if ($history == 100) { ?>
                                                                    <span class="badge badge-primary right">
                                                                        <?= $history ?>%
                                                                    </span>
                                                                <?php } elseif ($history > 100) { ?>
                                                                    <span class="badge badge-warning right">
                                                                        <?= $history ?>%
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger right">
                                                                        <?= $history ?>%
                                                                    </span>
                                                                <?php } ?>
                                                            </td>
                                                            <?php if ($override->get3('history', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-success"> Change </a> </td>
                                                            <?php } else { ?>
                                                                <td><a href="add.php?id=10&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-warning"> Add </a> </td>
                                                            <?php } ?>
                                                        </tr>

                                                    <?php } ?>


                                                    <tr>
                                                        <td>5</td>
                                                        <td>History, Symtom & Exam</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $symptoms = intval($override->countData1('symptoms', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $symptoms ?> out of 1
                                                            </span>
                                                            <?php $symptoms = intval($symptoms / 1) * 100 ?>
                                                            <?php if ($symptoms == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $symptoms ?>%
                                                                </span>
                                                            <?php } elseif ($symptoms > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $symptoms ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $symptoms ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('symptoms', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=11&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>
                                                    </tr>


                                                    <?php if ($_GET['seq'] == 1) { ?>

                                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1)) { ?>

                                                            <tr>
                                                                <td>6</td>
                                                                <td>Main diagnosis 1 ( Cardiac )</td>
                                                                <td>
                                                                    <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                    <?php $cardiac = intval($override->countData1('cardiac', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                    <span class="badge badge-info right">
                                                                        <?= $cardiac ?> out of 1
                                                                    </span>
                                                                    <?php $cardiac = intval($cardiac / 1) * 100 ?>
                                                                    <?php if ($cardiac == 100) { ?>
                                                                        <span class="badge badge-primary right">
                                                                            <?= $cardiac ?>%
                                                                        </span>
                                                                    <?php } elseif ($cardiac > 100) { ?>
                                                                        <span class="badge badge-warning right">
                                                                            <?= $cardiac ?>%
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <span class="badge badge-danger right">
                                                                            <?= $cardiac ?>%
                                                                        </span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if ($override->get3('cardiac', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-success"> Change </a> </td>
                                                                <?php } else { ?>
                                                                    <td><a href="add.php?id=12&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-warning"> Add </a> </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>


                                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'diabetes', 1)) { ?>

                                                            <tr>
                                                                <td>6</td>
                                                                <td>Main diagnosis 2 ( Diabetes )</td>
                                                                <td>
                                                                    <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                    <?php $diabetic = intval($override->countData1('diabetic', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                    <span class="badge badge-info right">
                                                                        <?= $diabetic ?> out of 1
                                                                    </span>
                                                                    <?php $diabetic = intval($diabetic / 1) * 100 ?>
                                                                    <?php if ($diabetic == 100) { ?>
                                                                        <span class="badge badge-primary right">
                                                                            <?= $diabetic ?>%
                                                                        </span>
                                                                    <?php } elseif ($diabetic > 100) { ?>
                                                                        <span class="badge badge-warning right">
                                                                            <?= $diabetic ?>%
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <span class="badge badge-danger right">
                                                                            <?= $diabetic ?>%
                                                                        </span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if ($override->get3('diabetic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                    <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-success"> Change </a> </td>
                                                                <?php } else { ?>
                                                                    <td><a href="add.php?id=13&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-warning"> Add </a> </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>


                                                        <?php if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) { ?>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>Main diagnosis 3 ( Sickle Cell )</td>
                                                                <td>
                                                                    <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                    <?php $sickle_cell = intval($override->countData1('sickle_cell', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                    <span class="badge badge-info right">
                                                                        <?= $sickle_cell ?> out of 1
                                                                    </span>
                                                                    <?php $sickle_cell = intval($sickle_cell / 1) * 100 ?>
                                                                    <?php if ($sickle_cell == 100) { ?>
                                                                        <span class="badge badge-primary right">
                                                                            <?= $sickle_cell ?>%
                                                                        </span>
                                                                    <?php } elseif ($sickle_cell > 100) { ?>
                                                                        <span class="badge badge-warning right">
                                                                            <?= $sickle_cell ?>%
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <span class="badge badge-danger right">
                                                                            <?= $sickle_cell ?>%
                                                                        </span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if ($override->get3('sickle_cell', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                    <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-success"> Change </a> </td>
                                                                <?php } else { ?>
                                                                    <td><a href="add.php?id=14&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                            class="btn btn-warning"> Add </a> </td>
                                                                <?php } ?>
                                                            </tr>

                                                        <?php } ?>

                                                    <?php } ?>


                                                    <?php
                                                    //  if ($override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'cardiac', 1) || $override->get2('main_diagnosis', 'patient_id', $_GET['cid'], 'sickle_cell', 1)) {
                                                    ?>
                                                    <tr>
                                                        <td>7</td>
                                                        <?php if ($_GET['seq'] == 1) { ?>
                                                            <td>Results at enrollment</td>
                                                        <?php } else { ?>
                                                            <td>Results at Follow Up</td>
                                                        <?php } ?>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $results = intval($override->countData1('results', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $results ?> out of 1
                                                            </span>
                                                            <?php $results = intval($results / 1) * 100 ?>
                                                            <?php if ($results == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $results ?>%
                                                                </span>
                                                            <?php } elseif ($results > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $results ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $results ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>

                                                            <?php if ($override->get3('results', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                <a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a>

                                                            <?php } else { ?>
                                                                <a href="add.php?id=15&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a>

                                                            <?php } ?>

                                                            <!-- <?php if ($override->get3('lab_requests', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <a href="add_lab_request.php?id=1&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>" class="btn btn-success"> Change Lab Requests</a>

                                                        <?php } else { ?>
                                                            <a href="add_lab_request.php?id=1&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>" class="btn btn-warning"> Add Lab Requests</a>

                                                        <?php } ?> -->
                                                        </td>


                                                    </tr>

                                                    <?php
                                                    //  }
                                                    ?>

                                                    <tr>
                                                        <td>8</td>
                                                        <td>Hospitalization</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $hospitalization = intval($override->countData1('hospitalization', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $hospitalization ?> out of 1
                                                            </span>
                                                            <?php $hospitalization = intval($hospitalization / 1) * 100 ?>
                                                            <?php if ($hospitalization == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $hospitalization ?>%
                                                                </span>
                                                            <?php } elseif ($vital > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $hospitalization ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $hospitalization ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('hospitalization', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=16&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>Hospitalization Details</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $hospitalization_details = intval($override->countData1('hospitalization_details', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $hospitalization_details ?> out of 1
                                                            </span>
                                                            <?php $hospitalization_details = intval($hospitalization_details / 1) * 100 ?>
                                                            <?php if ($hospitalization_details == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $hospitalization_details ?>%
                                                                </span>
                                                            <?php } elseif ($hospitalization_details > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $hospitalization_details ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $hospitalization_details ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=17&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>

                                                    <tr>
                                                        <td>10</td>
                                                        <td>Treatment Plan</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $treatment_plan = intval($override->countData1('treatment_plan', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $treatment_plan ?> out of 1
                                                            </span>
                                                            <?php $treatment_plan = intval($treatment_plan / 1) * 100 ?>
                                                            <?php if ($treatment_plan == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $treatment_plan ?>%
                                                                </span>
                                                            <?php } elseif ($treatment_plan > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $treatment_plan ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $treatment_plan ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=18&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>

                                                    <tr>
                                                        <td>11</td>
                                                        <td>Diagnosis, Complications, & Comorbidities</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $dgns_complctns_comorbdts = intval($override->countData1('dgns_complctns_comorbdts', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $dgns_complctns_comorbdts ?> out of 1
                                                            </span>
                                                            <?php $dgns_complctns_comorbdts = intval($dgns_complctns_comorbdts / 1) * 100 ?>
                                                            <?php if ($dgns_complctns_comorbdts == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $dgns_complctns_comorbdts ?>%
                                                                </span>
                                                            <?php } elseif ($dgns_complctns_comorbdts > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $dgns_complctns_comorbdts ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $dgns_complctns_comorbdts ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('dgns_complctns_comorbdts', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=19&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>

                                                    <tr>
                                                        <td>12</td>
                                                        <td>RISK</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $risks = intval($override->countData1('risks', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $risks ?> out of 1
                                                            </span>
                                                            <?php $risks = intval($risks / 1) * 100 ?>
                                                            <?php if ($risks == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $risks ?>%
                                                                </span>
                                                            <?php } elseif ($risks > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $risks ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $risks ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('risks', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=20&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>



                                                    <tr>
                                                        <td>13</td>
                                                        <td>Lab Details</td>
                                                        <td>
                                                            <!-- <i class="nav-icon fas fa-th"></i> -->
                                                            <?php $lab_details = intval($override->countData1('lab_details', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                            <span class="badge badge-info right">
                                                                <?= $lab_details ?> out of 1
                                                            </span>
                                                            <?php $lab_details = intval($lab_details / 1) * 100 ?>
                                                            <?php if ($lab_details == 100) { ?>
                                                                <span class="badge badge-primary right">
                                                                    <?= $lab_details ?>%
                                                                </span>
                                                            <?php } elseif ($lab_details > 100) { ?>
                                                                <span class="badge badge-warning right">
                                                                    <?= $lab_details ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger right">
                                                                    <?= $lab_details ?>%
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <?php if ($override->get3('lab_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                            <td><a href="add.php?id=21&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-success"> Change </a> </td>
                                                        <?php } else { ?>
                                                            <td><a href="add.php?id=21&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                    class="btn btn-warning"> Add </a> </td>
                                                        <?php } ?>

                                                    </tr>
                                                    <?php if ($_GET['seq'] == 1) { ?>

                                                        <tr>
                                                            <td>14</td>
                                                            <td>Socioeconomic Status</td>
                                                            <td>
                                                                <!-- <i class="nav-icon fas fa-th"></i> -->
                                                                <?php $social_economic = intval($override->countData1('social_economic', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                                <span class="badge badge-info right">
                                                                    <?= $social_economic ?> out of 1
                                                                </span>
                                                                <?php $social_economic = intval($social_economic / 1) * 100 ?>
                                                                <?php if ($social_economic == 100) { ?>
                                                                    <span class="badge badge-primary right">
                                                                        <?= $social_economic ?>%
                                                                    </span>
                                                                <?php } elseif ($social_economic > 100) { ?>
                                                                    <span class="badge badge-warning right">
                                                                        <?= $social_economic ?>%
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger right">
                                                                        <?= $social_economic ?>%
                                                                    </span>
                                                                <?php } ?>
                                                            </td>
                                                            <?php if ($override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                                <td><a href="add.php?id=23&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-success"> Change </a> </td>
                                                            <?php } else { ?>
                                                                <td><a href="add.php?id=23&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                        class="btn btn-warning"> Add </a> </td>
                                                            <?php } ?>

                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>

                                                <tr>
                                                    <td>15</td>
                                                    <td>Next Visit Summary</td>
                                                    <td>
                                                        <!-- <i class="nav-icon fas fa-th"></i> -->
                                                        <?php $summary = intval($override->countData1('summary', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'])) ?>
                                                        <span class="badge badge-info right">
                                                            <?= $summary ?> out of 1
                                                        </span>
                                                        <?php $summary = intval($summary / 1) * 100 ?>
                                                        <?php if ($summary == 100) { ?>
                                                            <span class="badge badge-primary right">
                                                                <?= $summary ?>%
                                                            </span>
                                                        <?php } elseif ($summary > 100) { ?>
                                                            <span class="badge badge-warning right">
                                                                <?= $summary ?>%
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="badge badge-danger right">
                                                                <?= $summary ?>%
                                                            </span>
                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($override->get3('summary', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])) { ?>

                                                        <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                class="btn btn-success"> Change </a> </td>
                                                    <?php } else { ?>
                                                        <td><a href="add.php?id=22&cid=<?= $_GET['cid'] ?>&vid=<?= $_GET['vid'] ?>&vcode=<?= $_GET['vcode'] ?>&seq=<?= $_GET['seq'] ?>&sid=<?= $_GET['sid'] ?>&vday=<?= $_GET['vday'] ?>&status=<?= $_GET['status'] ?>"
                                                                class="btn btn-warning"> Add </a> </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <th>#</th>
                                                <th>CRF</th>
                                                <th>Records</th>
                                                <th>Action</th>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 8) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Medications</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="add.php?id=5&btn=Add">
                                            < Go Back</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Medications</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <?php
                                        $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                                        // $visits_status = $override->firstRow1('visit', 'status', 'id', 'client_id', $_GET['cid'], 'visit_code', 'EV')[0]['status'];
                                    
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
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Amount</th>
                                                    <th>Form</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($override->get('medications', 'status', 1) as $value) {

                                                    $batch_sum = $override->getSumD2('batch', 'amount', 'status', 1, 'medication_id', $value['id'])[0]['SUM(amount)'];
                                                    $forms = $override->getNewsAsc0('forms', 'status', 1, 'id', $value['forms'])[0];
                                                    if ($batch_sum) {
                                                        $batch_sum = $batch_sum;
                                                    } else {
                                                        $batch_sum = 0;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td><?= $value['name'] ?></td>
                                                        <td> <?= $batch_sum ?></td>
                                                        <td> <?= $forms['name'] ?></td>
                                                        <td>
                                                            <?php if ($value['expire_date'] > date('Y-m-d')) { ?>
                                                                <a href="#editVisit<?= $value['id'] ?>" role="button"
                                                                    class="btn btn-success" data-toggle="modal">Valid</a>
                                                            <?php } else { ?>
                                                                <a href="#editVisit<?= $value['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Expired</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <a href="add.php?id=5&medication_id=<?= $value['id'] ?>&forms=<?= $value['forms'] ?>&use_group=<?= $value['use_group'] ?>&maintainance=<?= $value['maintainance'] ?>&btn=Update"
                                                                role="button" class="btn btn-info">Update</a>
                                                            <a href="info.php?id=9&medication_id=<?= $value['id'] ?>&forms=<?= $value['forms'] ?>&use_group=<?= $value['use_group'] ?>&maintainance=<?= $value['maintainance'] ?>&btn=Update"
                                                                role="button" class="btn btn-success">View</a>
                                                            <a href="#delete_medication<?= $value['id'] ?>" role="button"
                                                                class="btn btn-danger" data-toggle="modal">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="delete_medication<?= $value['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Delete Medication</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-muted text-center">Are you sure yoy want
                                                                            to delete this medication ?</p>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $value['id'] ?>">
                                                                        <input type="hidden" name="name"
                                                                            value="<?= $value['name'] ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <input type="submit" name="delete_medication"
                                                                            class="btn btn-danger" value="Yes, Delete">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Amount</th>
                                                    <th>Form</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 9) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Medications</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Medications</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
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

                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a href="index1.php">
                                                        <a href='info.php?id=8' class="btn btn-default">Back</a>
                                                    </a>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($override->getNews('batch', 'status', 1, 'medication_id', $_GET['medication_id']) as $value) {
                                                    $name = $override->get('medications', 'status', 1, 'id', $value['medication_id'])[0];
                                                    $batch_sum = $override->getSumD2('batch', 'amount', 'status', 1, 'medication_id', $value['id'])[0]['SUM(amount)'];
                                                    $forms = $override->get('forms', 'status', 1, 'id', $value['forms'])[0];
                                                    if ($batch_sum) {
                                                        $batch_sum = $batch_sum;
                                                    } elseif ($visit['status'] == 1) {
                                                        $batch_sum = 0;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td><?= $name['name'] ?></td>
                                                        <td><?= $value['serial_name'] ?></td>
                                                        <td><?= $value['amount'] ?></td>
                                                        <td><?= $value['expire_date'] ?></td>
                                                        <td>
                                                            <?php if ($value['expire_date'] > date('Y-m-d')) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-success" data-toggle="modal">Valid</a>
                                                            <?php } elseif ($visit['status'] == 0) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Expired</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <!-- <a href="#editVisit<?= $value['id'] ?>" role="button" class="btn btn-success" data-toggle="modal">Update</a> -->
                                                            <a href="#increase<?= $value['id'] ?>" role="button"
                                                                class="btn btn-info" data-toggle="modal">Increase Batch</a>
                                                            <a href="#decrease<?= $value['id'] ?>" role="button"
                                                                class="btn btn-warning" data-toggle="modal">Decrease Batch</a>
                                                            <a href="info.php?id=9&generic_id=<?= $value['id'] ?>" role="button"
                                                                class="btn btn-deafult">View</a>
                                                        </td>

                                                    </tr>

                                                    <div class="modal fade" id="increase<?= $value['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Increase (
                                                                            <?= $name['name']; ?>) :- Batch / Serial (
                                                                            <?= $value['serial_name']; ?>)
                                                                        </h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Date Received</label>
                                                                                        <input class="form-control" value=""
                                                                                            type="date" name="date" id="date"
                                                                                            required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Available Amount</label>
                                                                                        <input class="form-control" value="<?php if ($value['amount']) {
                                                                                            echo $value['amount'];
                                                                                        } ?>" type="number" min="0"
                                                                                            name="amount" id="amount"
                                                                                            readonly />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Received Amount</label>
                                                                                        <input class="form-control" value=""
                                                                                            type="number" min="0"
                                                                                            name="received" id="received"
                                                                                            required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Total Price ( TSHS )</label>
                                                                                        <input class="form-control" value="<?php if ($value['price']) {
                                                                                            echo $value['price'];
                                                                                        } ?>" type="number" min="0"
                                                                                            name="price" id="price" readonly />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>New Price ( TSHS )</label>
                                                                                        <input class="form-control" value=""
                                                                                            type="number" min="0" name="cost"
                                                                                            id="cost" required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $value['id'] ?>">
                                                                        <input type="hidden" name="name"
                                                                            value="<?= $name['name']; ?>">
                                                                        <input type="hidden" name="serial_name"
                                                                            value="<?= $name['serial_name']; ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <input type="submit" name="increase_batch"
                                                                            class="btn btn-primary" value="Save changes">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->

                                                    <div class="modal fade" id="decrease<?= $value['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Decrease (
                                                                            <?= $name['name']; ?>) :- Batch / Serial (
                                                                            <?= $value['serial_name']; ?>)
                                                                        </h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Date Removed</label>
                                                                                        <input class="form-control" value=""
                                                                                            type="date" name="date" id="date"
                                                                                            required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Available Amount</label>
                                                                                        <input class="form-control" value="<?php if ($value['amount']) {
                                                                                            echo $value['amount'];
                                                                                        } ?>" type="number" min="0"
                                                                                            name="amount" id="amount"
                                                                                            readonly />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Remove Amount</label>
                                                                                        <input class="form-control" value=""
                                                                                            type="number" min="0" name="removed"
                                                                                            id="removed" required />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dr"><span></span></div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $value['id'] ?>">
                                                                        <input type="hidden" name="name"
                                                                            value="<?= $name['name']; ?>">
                                                                        <input type="hidden" name="serial_name"
                                                                            value="<?= $name['serial_name']; ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <input type="submit" name="decrease_batch"
                                                                            class="btn btn-primary" value="Save changes">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 10) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Medications Batch</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="add.php?id=6&btn=Add">
                                            < Go Back</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Medications Batch</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
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
                                    </div>

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Forms</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($override->getAsc('batch', 'status', 1) as $value) {
                                                    $medication = $override->getNews('medications', 'status', 1, 'id', $value['medication_id'])['0'];
                                                    $batch_sum = $override->getSumD2('batch', 'amount', 'status', 1, 'medication_id', $value['id'])[0]['SUM(amount)'];
                                                    $forms = $override->getNews('forms', 'status', 1, 'id', $medication['forms'])[0];
                                                    if ($batch_sum) {
                                                        $batch_sum = $batch_sum;
                                                    } elseif ($visit['status'] == 1) {
                                                        $batch_sum = 0;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td><?= $medication['name'] ?></td>
                                                        <td><?= $value['serial_name'] ?></td>
                                                        <td><?= $value['amount'] ?></td>
                                                        <td><?= $forms['name'] ?></td>
                                                        <td><?= $value['expire_date'] ?></td>
                                                        <td>
                                                            <?php if ($value['expire_date'] > date('Y-m-d')) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-success" data-toggle="modal">Valid</a>
                                                            <?php } elseif ($visit['status'] == 0) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Expired</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= $value['remarks'] ?></td>
                                                        <td><?= $value['price'] ?></td>
                                                        <td>
                                                            <a href="add.php?id=6&batch_id=<?= $value['id'] ?>&medication_id=<?= $medication['id'] ?>&btn=Update"
                                                                role="button" class="btn btn-info">Update</a>
                                                            <a href="#delete_batch<?= $value['id'] ?>" role="button"
                                                                class="btn btn-danger" data-toggle="modal">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="delete_batch<?= $value['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Delete Medication Batch</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-muted text-center">Are you sure yoy want
                                                                            to delete this medication batch ?</p>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $value['id'] ?>">
                                                                        <input type="hidden" name="name"
                                                                            value="<?= $value['serial_name'] ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <input type="submit" name="delete_batch"
                                                                            class="btn btn-danger" value="Yes, Delete">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Forms</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 11) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Medications Batch</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="add.php?id=6&btn=Add">
                                            < Go Back</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Medications Batch</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <?php
                                        $dm = $override->get('main_diagnosis', 'diabetes', 1);
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
                                    </div>

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Forms</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x = 1;
                                                foreach ($dm as $value) {
                                                    $medication = $override->getNews('medications', 'status', 1, 'id', $value['medication_id'])['0'];
                                                    $batch_sum = $override->getSumD2('batch', 'amount', 'status', 1, 'medication_id', $value['id'])[0]['SUM(amount)'];
                                                    $forms = $override->getNews('forms', 'status', 1, 'id', $medication['forms'])[0];
                                                    if ($batch_sum) {
                                                        $batch_sum = $batch_sum;
                                                    } elseif ($visit['status'] == 1) {
                                                        $batch_sum = 0;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td><?= $medication['name'] ?></td>
                                                        <td><?= $value['serial_name'] ?></td>
                                                        <td><?= $value['amount'] ?></td>
                                                        <td><?= $forms['name'] ?></td>
                                                        <td><?= $value['expire_date'] ?></td>
                                                        <td>
                                                            <?php if ($value['expire_date'] > date('Y-m-d')) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-success" data-toggle="modal">Valid</a>
                                                            <?php } elseif ($visit['status'] == 0) { ?>
                                                                <a href="#editVisit<?= $visit['id'] ?>" role="button"
                                                                    class="btn btn-warning" data-toggle="modal">Expired</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= $value['remarks'] ?></td>
                                                        <td><?= $value['price'] ?></td>
                                                        <td>
                                                            <a href="add.php?id=6&batch_id=<?= $value['id'] ?>&medication_id=<?= $medication['id'] ?>&btn=Update"
                                                                role="button" class="btn btn-info">Update</a>
                                                            <a href="#delete_batch<?= $value['id'] ?>" role="button"
                                                                class="btn btn-danger" data-toggle="modal">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="delete_batch<?= $value['id'] ?>">
                                                        <div class="modal-dialog">
                                                            <form method="post">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Delete Medication Batch</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-muted text-center">Are you sure yoy want
                                                                            to delete this medication batch ?</p>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $value['id'] ?>">
                                                                        <input type="hidden" name="name"
                                                                            value="<?= $value['serial_name'] ?>">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <input type="submit" name="delete_batch"
                                                                            class="btn btn-danger" value="Yes, Delete">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
                                                    <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Batch / Serial No.</th>
                                                    <th>Amount</th>
                                                    <th>Forms</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 12) { ?>
            <?php
            $AllTables = $override->AllTables();
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>STUDY ID FORM ( SET STUDY ID )</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">STUDY ID FORM ( SET STUDY ID )</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">

                            <!-- right column -->
                            <div class="col-md-12">
                                <!-- general form elements disabled -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">UPDATE ALL TABLES PID </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row-form clearfix">
                                                        <div class="form-group">
                                                            <label for="forms">( PATIENT ID ) FULL NAME ( STUDY ID )</label>
                                                            <select name="pid" class="form-control" style="width: 100%;"
                                                                required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($override->get('clients', 'status', 1) as $client) { ?>
                                                                    <option value="<?= $client['id'] ?>">
                                                                        <?= $client['id'] . ' - ( ' . $client['study_id'] . ' - ' . $client['firstname'] . ' - ' . $client['middelname'] . ' - ' . $client['lastname'] . ' ) ' ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <a href='index1.php' class="btn btn-default">Back</a>
                                            <input type="submit" name="update_pids_all_tables" value="Submit"
                                                class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } elseif ($_GET['id'] == 13) { ?>
        <?php } elseif ($_GET['id'] == 14) { ?>
        <?php } elseif ($_GET['id'] == 15) { ?>
        <?php } ?>

        <?php include 'footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->




    <!-- Screening -->
    <script src="myjs/add/screening/screening1.js"></script>
    <script src="myjs/add/screening/screening2.js"></script>





    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <script>
        <?php if ($user->data()->pswd == 0) { ?>
            $(window).on('load', function () {
                $("#change_password_n").modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            });
        <?php } ?>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        $(document).ready(function () {
            $('#search').keyup(function () {
                var searchTerm = $(this).val();
                $.ajax({
                    url: 'fetch_details.php?content=fetchDetails',
                    type: 'GET',
                    data: {
                        search: searchTerm
                    },
                    // dataType: "json",
                    success: function (response) {
                        console.log(response)
                        $('#search-results').html(response);
                    }
                });
            });
        });

        $(document).ready(function () {
            $("#myInput11").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#inventory_report1 tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#medication_list tr").filter(function () {
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

        function checkQuestionValue45(currentQuestion, elementToHide1, elementToHide2) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide1 = document.getElementById(elementToHide1);
            var elementToHide2 = document.getElementById(elementToHide2);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "4") {
                elementToHide1.classList.remove("hidden");
            } else if (questionValue === "5") {
                elementToHide2.classList.remove("hidden");

            } else {
                elementToHide1.classList.add("hidden");
                elementToHide2.classList.add("hidden");

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
            inp.addEventListener("input", function (e) {
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
                        b.addEventListener("click", function (e) {
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
            inp.addEventListener("keydown", function (e) {
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
            document.addEventListener("click", function (e) {
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
</body>

</html>

</html>