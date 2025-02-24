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
                            <?php if ($_GET['id'] == 1) { ?>

                            <?php } elseif ($_GET['id'] == 3) { ?>
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
                            <?php } elseif ($_GET['id'] == 4) { ?>
                            <?php } ?>
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