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

$numRec = 15;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();

        if (Input::get('delete_record')) {
            $user->updateRecord($_GET['table'], array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Recored Deleted Successful';
        }

        if (Input::get('search_by_site')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'site_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                // id = 2 & status = 1 & data = 1 & table = clients
                $url = 'data.php?id=' . $_GET['id'] . '&status=' . $_GET['status'] . '&data=' . $_GET['data'] . '&table=' . $_GET['table'] . '&page=' . $_GET['page'] . '&site_id=' . Input::get('site_id');
                Redirect::to($url);
                $pageError = $validate->errors();
            }
        }


        if (Input::get('download')) {
            $data = null;
            $filename = null;

            if (Input::get('table_name')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews(Input::get('table_name'), 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get(Input::get('table_name'), 'status', 1);
                    }
                } else {
                    $data = $override->getNews(Input::get('table_name'), 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = Input::get('table_name') . ' Data';
                $user->exportData($data, $filename);
            }
        }

        // if (Input::get('download')) {
        //     $data = null;
        //     $filename = null;

        //     if (Input::get('data') == 1) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('clients', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('clients', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('clients', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Registration Data';
        //     } elseif (Input::get('data') == 2) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('screening', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('screening', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('screening', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Screening Data';
        //     } elseif (Input::get('data') == 3) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('demographic', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('demographic', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('demographic', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Demographic Data';
        //     } elseif (Input::get('data') == 4) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('vital', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('vital', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('vital', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Vital Data';
        //     } elseif (Input::get('data') == 5) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('main_diagnosis', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('main_diagnosis', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Patient Category Data';
        //     } elseif (Input::get('data') == 6) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('history', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('history', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('history', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'History Data';
        //     } elseif (Input::get('data') == 7) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('symptoms', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('symptoms', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('symptoms', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Symptoms Data';
        //     } elseif (Input::get('data') == 8) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('cardiac', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('cardiac', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('cardiac', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Main diagnosis 1 ( Cardiac )';
        //     } elseif (Input::get('data') == 9) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('diabetic', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('diabetic', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('diabetic', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Main diagnosis 2 ( Diabetes )';
        //     } elseif (Input::get('data') == 10) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('sickle_cell', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('sickle_cell', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Main diagnosis 3 ( Sickle Cell )';
        //     } elseif (Input::get('data') == 11) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('sickle_cell_status_table', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Siblings Data';
        //     } elseif (Input::get('data') == 12) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('results', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('results', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('results', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Results Data';
        //     } elseif (Input::get('data') == 13) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('hospitalization', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('hospitalization', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('hospitalization', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Hospitalization Data';
        //     } elseif (Input::get('data') == 14) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('hospitalization_details', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Hospitalization Details Data';
        //     } elseif (Input::get('data') == 15) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('hospitalization_table', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Admissions Data';
        //     } elseif (Input::get('data') == 16) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('treatment_plan', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('treatment_plan', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Treatment plan Data';
        //     } elseif (Input::get('data') == 17) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('medication_treatments', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Medications Data';
        //     } elseif (Input::get('data') == 18) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('dgns_complctns_comorbdts', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Diagnosis, Complications and Comorbiditis Data';
        //     } elseif (Input::get('data') == 19) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('risks', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('risks', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('risks', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Risks Data';
        //     } elseif (Input::get('data') == 20) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('lab_details', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('lab_details', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('lab_details', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Lab Details Data';
        //     } elseif (Input::get('data') == 21) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('lab_requests', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('lab_requests', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('lab_requests', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Lab Tests Data';
        //     } elseif (Input::get('data') == 22) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('test_list', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('test_list', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('test_list', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Test Data';
        //     } elseif (Input::get('data') == 23) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('social_economic', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('social_economic', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('social_economic', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Social Economic Data';
        //     } elseif (Input::get('data') == 24) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('summary', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('summary', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('summary', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Summary Data';
        //     } elseif (Input::get('data') == 25) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('visit', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('visit', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('visit', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Schedule Data';
        //     } elseif (Input::get('data') == 26) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('study_id', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('study_id', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('study_id', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Study ID Data';
        //     } elseif (Input::get('data') == 27) {
        //         if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //             if ($_GET['site_id'] != null) {
        //                 $data = $override->getNews('site', 'status', 1, 'site_id', $_GET['site_id']);
        //             } else {
        //                 $data = $override->get('site', 'status', 1);
        //             }
        //         } else {
        //             $data = $override->getNews('site', 'status', 1, 'site_id', $user->data()->site_id);
        //         }
        //         $filename = 'Sites List Data';
        //     }

        //     $user->exportDataXls($data, $filename);
        // }
        //  elseif (Input::get('download_all')) {
        //     $data = null;
        //     $filename = null;

        //     $AllTables = $override->AllTables();

        //     foreach ($AllTables as $tables) {
        //         if (
        //             $tables['Tables_in_penplus'] == 'clients' || $tables['Tables_in_penplus'] == 'screening' ||
        //             $tables['Tables_in_penplus'] == 'demographic' || $tables['Tables_in_penplus'] == 'vitals' ||
        //             $tables['Tables_in_penplus'] == 'main_diagnosis' || $tables['Tables_in_penplus'] == 'history' ||
        //             $tables['Tables_in_penplus'] == 'symptoms' || $tables['Tables_in_penplus'] == 'cardiac' ||
        //             $tables['Tables_in_penplus'] == 'diabetic' || $tables['Tables_in_penplus'] == 'sickle_cell' ||
        //             $tables['Tables_in_penplus'] == 'results' || $tables['Tables_in_penplus'] == 'cardiac' ||
        //             $tables['Tables_in_penplus'] == 'hospitalization' || $tables['Tables_in_penplus'] == 'hospitalization_details' ||
        //             $tables['Tables_in_penplus'] == 'treatment_plan' || $tables['Tables_in_penplus'] == 'dgns_complctns_comorbdts' ||
        //             $tables['Tables_in_penplus'] == 'risks' || $tables['Tables_in_penplus'] == 'lab_details' ||
        //             $tables['Tables_in_penplus'] == 'social_economic' || $tables['Tables_in_penplus'] == 'summary' ||
        //             $tables['Tables_in_penplus'] == 'medication_treatments' || $tables['Tables_in_penplus'] == 'hospitalization_detail_id' ||
        //             $tables['Tables_in_penplus'] == 'sickle_cell_status_table' || $tables['Tables_in_penplus'] == 'visit' ||
        //             $tables['Tables_in_penplus'] == 'lab_requests'
        //         ) {
        //             if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //                 if ($_GET['site_id'] != null) {
        //                     $data = $override->getNews($tables['Tables_in_penplus'], 'status', 1, 'site_id', $_GET['site_id']);
        //                 } else {
        //                     $data = $override->get($tables['Tables_in_penplus'], 'status', 1);
        //                 }
        //             } else {
        //                 $data = $override->getNews($tables['Tables_in_penplus'], 'status', 1, 'site_id', $user->data()->site_id);
        //             }
        //             $filename = $tables['Tables_in_penplus'] . ' Data';
        //             $user->exportDataXls($data, $filename);
        //         }
        //     }
        // }
        //  elseif (Input::get('download_alls_data')) {
        //     $data = null;
        //     $filename = null;

        //     foreach (Input::get('table_name') as $tables) {
        //         if (
        //             $tables == 'clients' || $tables == 'screening'  ||
        //             $tables == 'demographic' || $tables == 'vital' ||
        //             $tables == 'main_diagnosis' || $tables == 'history' ||
        //             $tables == 'symptoms' || $tables == 'cardiac' ||
        //             $tables == 'diabetic' || $tables == 'sickle_cell' ||
        //             $tables == 'results' || $tables == 'cardiac' ||
        //             $tables == 'hospitalization' || $tables == 'hospitalization_details' ||
        //             $tables == 'treatment_plan' || $tables == 'dgns_complctns_comorbdts' ||
        //             $tables == 'risks' || $tables == 'lab_details' ||
        //             $tables == 'social_economic' || $tables == 'summary' ||
        //             $tables == 'medication_treatments' || $tables == 'hospitalization_detail_id' ||
        //             $tables == 'sickle_cell_status_table' || $tables == 'visit' ||
        //             $tables == 'lab_requests'
        //         ) {
        //             if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //                 if ($_GET['site_id'] != null) {
        //                     $data = $override->getNews($tables, 'status', 1, 'site_id', $_GET['site_id']);
        //                 } else {
        //                     $data = $override->get($tables, 'status', 1);
        //                 }
        //             } else {
        //                 $data = $override->getNews($tables, 'status', 1, 'site_id', $user->data()->site_id);
        //             }
        //             $filename = $tables . ' Data';
        //         }

        //         $user->exportDataXls($data, $filename);
        //     }
        // } 
        // elseif (Input::get('download_alls_data_xls')) {
        //     $data = null;
        //     $filename = null;

        // foreach (Input::get('table_name') as $tables) {
        //     if (
        //         $tables == 'clients' || $tables == 'screening'  ||
        //         $tables == 'demographic' || $tables == 'vital' ||
        //         $tables == 'main_diagnosis' || $tables == 'history' ||
        //         $tables == 'symptoms' || $tables == 'cardiac' ||
        //         $tables == 'diabetic' || $tables == 'sickle_cell' ||
        //         $tables == 'results' || $tables == 'cardiac' ||
        //         $tables == 'hospitalization' || $tables == 'hospitalization_details' ||
        //         $tables == 'treatment_plan' || $tables == 'dgns_complctns_comorbdts' ||
        //         $tables == 'risks' || $tables == 'lab_details' ||
        //         $tables == 'social_economic' || $tables == 'summary' ||
        //         $tables == 'medication_treatments' || $tables == 'hospitalization_detail_id' ||
        //         $tables == 'sickle_cell_status_table' || $tables == 'visit' ||
        //         $tables == 'lab_requests'
        //     ) {
        // if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //     if ($_GET['site_id'] != null) {
        //         $data = $override->getNews(Input::get('table_id'), 'status', 1, 'site_id', $_GET['site_id']);
        //     } else {
        //         $data = $override->get(Input::get('table_id'), 'status', 1);
        //     }
        // } else {
        //     $data = $override->getNews(Input::get('table_id'), 'status', 1, 'site_id', $user->data()->site_id);
        // }
        // $filename = Input::get('table_id') . ' Data';
        // $user->exportDataXls($data, $filename);
        // }

        // }
        // }
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
    <title>Penplus Database | Data</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
        <?php } elseif ($successMessage) { ?>
            <div class="alert alert-success text-center">
                <h4>Success!</h4>
                <?= $successMessage ?>
            </div>
        <?php } ?>

        <?php

        // $form_name = '';
        // $form_title = '';
        // if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        //     if ($_GET['site_id'] != null) {
        //         $pagNum = 0;
        //         $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['site_id']);

        //         if ($_GET['status'] == 1) {
        //             $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 2) {
        //             $pagNum = $override->countData('screening', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 3) {
        //             $pagNum = $override->countData('demographic', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 4) {
        //             $pagNum = $override->countData('vital', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 5) {
        //             $pagNum = $override->countData('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 6) {
        //             $pagNum = $override->countData('history', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 7) {
        //             $pagNum = $override->countData('symptoms', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 8) {
        //             $pagNum = $override->countData('cardiac', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 9) {
        //             $pagNum = $override->countData('diabetic', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 10) {
        //             $pagNum = $override->countData('sickle_cell', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 11) {
        //             $pagNum = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 12) {
        //             $pagNum = $override->countData('results', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 13) {
        //             $pagNum = $override->countData('hospitalization', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 14) {
        //             $pagNum = $override->countData('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 15) {
        //             $pagNum = $override->countData('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 16) {
        //             $pagNum = $override->countData('treatment_plan', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 17) {
        //             $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 18) {
        //             $pagNum = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 19) {
        //             $pagNum = $override->countData('risks', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 20) {
        //             $pagNum = $override->countData('lab_details', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 21) {
        //             $pagNum = $override->countData('lab_requests', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 22) {
        //             $pagNum = $override->getCount('test_list', 'status', 1);
        //         } elseif ($_GET['status'] == 23) {
        //             $pagNum = $override->countData('social_economic', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 24) {
        //             $pagNum = $override->countData('summary', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 25) {
        //             $pagNum = $override->countData('visit', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 26) {
        //             $pagNum = $override->countData('study_id', 'status', 1, 'site_id', $_GET['site_id']);
        //         } elseif ($_GET['status'] == 27) {
        //             $pagNum = $override->countData('site', 'status', 1, 'site_id', $_GET['site_id']);
        //         }

        //         $pages = ceil($pagNum / $numRec);
        //         if (!$_GET['page'] || $_GET['page'] == 1) {
        //             $page = 0;
        //         } else {
        //             $page = ($_GET['page'] * $numRec) - $numRec;
        //         }

        //         if ($_GET['status'] == 1) {
        //             $form_name = 'clients';
        //             $form_title = 'Clients';
        //             $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 2) {
        //             $form_name = 'screening';
        //             $form_title = 'screening';
        //             $clients = $override->getWithLimit1('screening', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 3) {
        //             $form_name = 'demographic';
        //             $form_title = 'demographic';
        //             $clients = $override->getWithLimit1('demographic', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 4) {
        //             $clients = $override->getWithLimit1('vital', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 5) {
        //             $clients = $override->getWithLimit1('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 6) {
        //             $clients = $override->getWithLimit1('history', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 7) {
        //             $clients = $override->getWithLimit1('symptoms', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 8) {
        //             $clients = $override->getWithLimit1('cardiac', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 9) {
        //             $clients = $override->getWithLimit1('diabetic', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 10) {
        //             $clients = $override->getWithLimit1('sickle_cell', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 11) {
        //             $clients = $override->getWithLimit1('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 12) {
        //             $clients = $override->getWithLimit1('results', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 13) {
        //             $clients = $override->getWithLimit1('hospitalization', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 14) {
        //             $clients = $override->getWithLimit1('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 15) {
        //             $clients = $override->getWithLimit1('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 16) {
        //             $clients = $override->getWithLimit1('treatment_plan', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 17) {
        //             $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 18) {
        //             $clients = $override->getWithLimit1('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 19) {
        //             $clients = $override->getWithLimit1('risks', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 20) {
        //             $clients = $override->getWithLimit1('lab_details', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 21) {
        //             $clients = $override->getWithLimit1('lab_requests', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 22) {
        //             $clients = $override->getWithLimit('test_list', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 23) {
        //             $clients = $override->getWithLimit1('social_economic', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 24) {
        //             $clients = $override->getWithLimit1('summary', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 25) {
        //             $clients = $override->getWithLimit1('visit', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 26) {
        //             $clients = $override->getWithLimit1('study_id', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         } elseif ($_GET['status'] == 27) {
        //             $clients = $override->getWithLimit1('site', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
        //         }
        //     } else {

        //         $pagNum = 0;
        //         if ($_GET['status'] == 1) {
        //             $pagNum = $override->getCount('clients', 'status', 1);
        //         } elseif ($_GET['status'] == 2) {
        //             $pagNum = $override->getCount('screening', 'status', 1);
        //         } elseif ($_GET['status'] == 3) {
        //             $pagNum = $override->getCount('demographic', 'status', 1);
        //         } elseif ($_GET['status'] == 4) {
        //             $pagNum = $override->getCount('vital', 'status', 1);
        //         } elseif ($_GET['status'] == 5) {
        //             $pagNum = $override->getCount('main_diagnosis', 'status', 1);
        //         } elseif ($_GET['status'] == 6) {
        //             $pagNum = $override->getCount('history', 'status', 1);
        //         } elseif ($_GET['status'] == 7) {
        //             $pagNum = $override->getCount('symptoms', 'status', 1);
        //         } elseif ($_GET['status'] == 8) {
        //             $pagNum = $override->getCount('cardiac', 'status', 1);
        //         } elseif ($_GET['status'] == 9) {
        //             $pagNum = $override->getCount('diabetic', 'status', 1);
        //         } elseif ($_GET['status'] == 10) {
        //             $pagNum = $override->getCount('sickle_cell', 'status', 1);
        //         } elseif ($_GET['status'] == 11) {
        //             $pagNum = $override->getCount('sickle_cell_status_table', 'status', 1);
        //         } elseif ($_GET['status'] == 12) {
        //             $pagNum = $override->getCount('results', 'status', 1);
        //         } elseif ($_GET['status'] == 13) {
        //             $pagNum = $override->getCount('hospitalization', 'status', 1);
        //         } elseif ($_GET['status'] == 14) {
        //             $pagNum = $override->getCount('hospitalization_details', 'status', 1);
        //         } elseif ($_GET['status'] == 15) {
        //             $pagNum = $override->getCount('hospitalization_table', 'status', 1);
        //         } elseif ($_GET['status'] == 16) {
        //             $pagNum = $override->getCount('treatment_plan', 'status', 1);
        //         } elseif ($_GET['status'] == 17) {
        //             $pagNum = $override->getCount('medication_treatments', 'status', 1);
        //         } elseif ($_GET['status'] == 18) {
        //             $pagNum = $override->getCount('dgns_complctns_comorbdts', 'status', 1);
        //         } elseif ($_GET['status'] == 19) {
        //             $pagNum = $override->getCount('risks', 'status', 1);
        //         } elseif ($_GET['status'] == 20) {
        //             $pagNum = $override->getCount('lab_details', 'status', 1);
        //         } elseif ($_GET['status'] == 21) {
        //             $pagNum = $override->getCount('lab_requests', 'status', 1);
        //         } elseif ($_GET['status'] == 22) {
        //             $pagNum = $override->getCount('test_list', 'status', 1);
        //         } elseif ($_GET['status'] == 23) {
        //             $pagNum = $override->getCount('social_economic', 'status', 1);
        //         } elseif ($_GET['status'] == 24) {
        //             $pagNum = $override->getCount('summary', 'status', 1);
        //         } elseif ($_GET['status'] == 25) {
        //             $pagNum = $override->getCount('visit', 'status', 1);
        //         } elseif ($_GET['status'] == 26) {
        //             $pagNum = $override->getCount('study_id', 'status', 1);
        //         } elseif ($_GET['status'] == 27) {
        //             $pagNum = $override->getCount('site', 'status', 1);
        //         }
        //         $pages = ceil($pagNum / $numRec);
        //         if (!$_GET['page'] || $_GET['page'] == 1) {
        //             $page = 0;
        //         } else {
        //             $page = ($_GET['page'] * $numRec) - $numRec;
        //         }

        //         if ($_GET['status'] == 1) {
        //             $clients = $override->getWithLimit('clients', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 2) {
        //             $clients = $override->getWithLimit('screening', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 3) {
        //             $clients = $override->getWithLimit('demographic', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 4) {
        //             $clients = $override->getWithLimit('vital', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 5) {
        //             $clients = $override->getWithLimit('main_diagnosis', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 6) {
        //             $clients = $override->getWithLimit('history', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 7) {
        //             $clients = $override->getWithLimit('symptoms', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 8) {
        //             $clients = $override->getWithLimit('cardiac', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 9) {
        //             $clients = $override->getWithLimit('diabetic', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 10) {
        //             $clients = $override->getWithLimit('sickle_cell', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 11) {
        //             $clients = $override->getWithLimit('sickle_cell_status_table', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 12) {
        //             $clients = $override->getWithLimit('results', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 13) {
        //             $clients = $override->getWithLimit('hospitalization', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 14) {
        //             $clients = $override->getWithLimit('hospitalization_details', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 15) {
        //             $clients = $override->getWithLimit('hospitalization_table', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 16) {
        //             $clients = $override->getWithLimit('treatment_plan', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 17) {
        //             $clients = $override->getWithLimit('medication_treatments', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 18) {
        //             $clients = $override->getWithLimit('dgns_complctns_comorbdts', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 19) {
        //             $clients = $override->getWithLimit('risks', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 20) {
        //             $clients = $override->getWithLimit('lab_details', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 21) {
        //             $clients = $override->getWithLimit('lab_requests', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 22) {
        //             $clients = $override->getWithLimit('test_list', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 23) {
        //             $clients = $override->getWithLimit('social_economic', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 24) {
        //             $clients = $override->getWithLimit('summary', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 25) {
        //             $clients = $override->getWithLimit('visit', 'status', 1,  $page, $numRec);
        //         } elseif ($_GET['status'] == 26) {
        //             $clients = $override->getWithLimit('study_id', 'status', 1, $page, $numRec);
        //         } elseif ($_GET['status'] == 27) {
        //             $clients = $override->getWithLimit('site', 'status', 1,  $page, $numRec);
        //         }
        //     }
        // } else {
        //     $pagNum = 0;
        //     if ($_GET['status'] == 1) {
        //         $pagNum = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 2) {
        //         $pagNum = $override->countData('screening', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 3) {
        //         $pagNum = $override->countData('demographic', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 4) {
        //         $pagNum = $override->countData('vital', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 5) {
        //         $pagNum = $override->countData('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
        //     } elseif ($_GET['status'] == 6) {
        //         $pagNum = $override->countData('history', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 7) {
        //         $pagNum = $override->countData('symptoms', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 8) {
        //         $pagNum = $override->countData('cardiac', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 9) {
        //         $pagNum = $override->countData('diabetic', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 10) {
        //         $pagNum = $override->countData('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 11) {
        //         $pagNum = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 12) {
        //         $pagNum = $override->countData('results', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 13) {
        //         $pagNum = $override->countData('hospitalization', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 14) {
        //         $pagNum = $override->countData('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 15) {
        //         $pagNum = $override->countData('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 16) {
        //         $pagNum = $override->countData('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 17) {
        //         $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 18) {
        //         $pagNum = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 19) {
        //         $pagNum = $override->countData('risks', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 20) {
        //         $pagNum = $override->countData('lab_details', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 21) {
        //         $pagNum = $override->countData('lab_requests', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 22) {
        //         $pagNum = $override->getCount('test_list', 'status', 1);
        //     } elseif ($_GET['status'] == 23) {
        //         $pagNum = $override->countData('social_economic', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 24) {
        //         $pagNum = $override->countData('summary', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 25) {
        //         $pagNum = $override->countData('visit', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 26) {
        //         $pagNum = $override->countData('study_id', 'status', 1, 'site_id', $user->data()->site_id);
        //     } elseif ($_GET['status'] == 27) {
        //         $pagNum = $override->countData('site', 'status', 1, 'site_id', $user->data()->site_id);
        //     }

        //     $pages = ceil($pagNum / $numRec);
        //     if (!$_GET['page'] || $_GET['page'] == 1) {
        //         $page = 0;
        //     } else {
        //         $page = ($_GET['page'] * $numRec) - $numRec;
        //     }

        //     if ($_GET['status'] == 1) {
        //         $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 2) {
        //         $clients = $override->getWithLimit1('screening', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 3) {
        //         $clients = $override->getWithLimit1('demographic', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 4) {
        //         $clients = $override->getWithLimit1('vital', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 5) {
        //         $clients = $override->getWithLimit1('main_diagnosis', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 6) {
        //         $clients = $override->getWithLimit1('history', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 7) {
        //         $clients = $override->getWithLimit1('symptoms', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 8) {
        //         $clients = $override->getWithLimit1('cardiac', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 9) {
        //         $clients = $override->getWithLimit1('diabetic', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 10) {
        //         $clients = $override->getWithLimit1('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 11) {
        //         $clients = $override->getWithLimit1('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 12) {
        //         $clients = $override->getWithLimit1('results', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 13) {
        //         $clients = $override->getWithLimit1('hospitalization', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 14) {
        //         $clients = $override->getWithLimit1('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 15) {
        //         $clients = $override->getWithLimit1('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 16) {
        //         $clients = $override->getWithLimit1('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 17) {
        //         $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 18) {
        //         $clients = $override->getWithLimit1('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 19) {
        //         $clients = $override->getWithLimit1('risks', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 20) {
        //         $clients = $override->getWithLimit1('lab_details', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 21) {
        //         $clients = $override->getWithLimit1('lab_requests', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 22) {
        //         $clients = $override->getWithLimit('test_list', 'status', 1,  $page, $numRec);
        //     } elseif ($_GET['status'] == 23) {
        //         $clients = $override->getWithLimit1('social_economic', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 24) {
        //         $clients = $override->getWithLimit1('summary', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 25) {
        //         $clients = $override->getWithLimit1('visit', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 26) {
        //         $clients = $override->getWithLimit1('study_id', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     } elseif ($_GET['status'] == 27) {
        //         $clients = $override->getWithLimit1('site', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
        //     }
        // }
        ?>

        <?php if ($_GET['id'] == 1) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    // if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                    //     if ($_GET['site_id'] != null) {
                                    //         $clients = $override->getDataDesc1('study_id', 'site_id', $_GET['site_id'],  'id');
                                    //     } else {
                                    //         $clients = $override->getDataDesc('study_id', 'id');
                                    //     }
                                    // } else {
                                    //     $clients = $override->getDataDesc1('visit', 'site_id', $user->data()->site_id,  'id');
                                    // } 
                                    ?>
                                    List of Data Tables
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">List of Data Tables</li>
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
                                                <div class="col-sm-12">
                                                    <div class="card-header">
                                                        <h3 class="card-title">List of Data Tables</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $visit; ?></span>
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
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">                                      

                                        <div class="card-body">
                                            <table id="search-results" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Table Name</th>
                                                        <th>Records</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $x = 1;
                                                    foreach ($override->AllTables() as $tables) {

                                                        $sites = $override->getNews('site', 'status', 1, 'id', $value['site_id'])[0];

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
                                                        ) {
                                                    ?>
                                                            <tr>
                                                                <td class="table-user">
                                                                    <?= $x; ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="table_id" value="<?= $tables['Tables_in_penplus']; ?>">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="table_name[]" id="table_name[]" value="<?= $tables['Tables_in_penplus']; ?>" <?php if ($tables['Tables_in_penplus'] != '') {
                                                                                                                                                                                                                echo 'checked';
                                                                                                                                                                                                            } ?>>
                                                                        <label class="form-check-label"><?= $tables['Tables_in_penplus']; ?></label>
                                                                    </div>
                                                                </td>
                                                                <td class="table-user">
                                                                    <?= $override->getCount($tables['Tables_in_penplus'], 'status', 1); ?>
                                                                </td>
                                                                <td class="table-user text-center">
                                                                    <input type="hidden" name="data" value="<?= $x; ?>">
                                                                    <input type="hidden" name="table_name" value="<?= $tables['Tables_in_penplus']; ?>">
                                                                    <input type="submit" name="download" value="Download Data">
                                                                    <a href="data.php?id=2&status=<?= $_GET['status'] ?>&data=<?= $_GET['data'] ?>&table=<?= $tables['Tables_in_penplus'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?= $_GET['page'] ?>" role=" button" class="btn btn-info"> View Recoreds </a>
                                                                </td>
                                                            </tr>
                                                    <?php $x++;
                                                        }
                                                    } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Table Name</th>
                                                        <th>Records</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <input type="submit" name="download_alls_data" value="Download Data" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item">
                                                <a class="page-link" href="data.php?id=1&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
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
                                                                        } ?>" href="data.php?id=1&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?= $i ?>"><?= $i ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="page-item">
                                                <a class="page-link" href="data.php?id=1&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
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
        <?php } elseif ($_GET['id'] == 2) { ?>
            <?php
            $form_id = $form_id;
            $table_name = $_GET['table'];

            if ($table_name == 'clients') {
                $form_id = 4;
            } elseif ($table_name == 'screening') {
                $form_id = 45;
            } elseif ($table_name == 'demographic') {
                $form_id = 7;
            } elseif ($table_name == 'vital') {
                $form_id = 8;
            } elseif ($table_name == 'main_diagnosis') {
                $form_id = 9;
            } elseif ($table_name == 'history') {
                $form_id = 10;
            } elseif ($table_name == 'symptoms') {
                $form_id = 11;
            } elseif ($table_name == 'cardiac') {
                $form_id = 12;
            } elseif ($table_name == 'diabetic') {
                $form_id = 13;
            } elseif ($table_name == 'sickle_cell') {
                $form_id = 14;
            } elseif ($table_name == 'results') {
                $form_id = 15;
            } elseif ($table_name == 'hospitalization') {
                $form_id = 16;
            } elseif ($table_name == 'hospitalization_details') {
                $form_id = 17;
            } elseif ($table_name == 'treatment_plan') {
                $form_id = 18;
            } elseif ($table_name == 'dgns_complctns_comorbdts') {
                $form_id = 19;
            } elseif ($table_name == 'risks') {
                $form_id = 20;
            } elseif ($table_name == 'lab_details') {
                $form_id = 21;
            } elseif ($table_name == 'social_economic') {
                $form_id = 23;
            } elseif ($table_name == 'medication_treatments') {
                $form_id = 18;
            } elseif ($table_name == 'hospitalization_detail_id') {
                $form_id = 17;
            } elseif ($table_name == 'sickle_cell_status_table') {
                $form_id = 10;
            }
            // elseif ($table_name == 'visit') {
            //     $form_id = 4;
            // } 
            elseif ($table_name == 'summary') {
                $form_id = 22;
            }
            $form_name = $table_name;
            $form_title = $table_name;
            $form_id = $form_id;

            if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                if ($_GET['site_id'] != null) {
                    $pagNum = 0;
                    $pagNum = $override->countData($table_name, 'status', 1, 'site_id', $_GET['site_id']);

                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }

                    if ($_GET['search_item']) {
                        $searchTerm = $_GET['search_item'];
                        $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    } else {
                        $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    }
                } else {

                    $pagNum = 0;
                    $pagNum = $override->getCount($table_name, 'status', 1);
                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }

                    if ($_GET['search_item']) {
                        // print_r('HI');
                        // $searchTerm = $_GET['search_item'];
                        $data = $override->getWithLimitSearch($table_name, 'status', 1, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                        // $url = 'data.php?id=2&status=' . $_GET['status'] . '&data=' . $_GET['data'] . '&table=' . $_GET['table'] . '&site_id=' . Input::get('site_id') . '&page=' . $_GET['page'];
                        // Redirect::to($url);
                    } else {
                        $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    }

                    // $pagNum = 0;
                    // $pagNum = $override->getWithLimitSearchCount($table_name, 'status', 1, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    // $pages = ceil($pagNum / $numRec);
                    // if (!$_GET['page'] || $_GET['page'] == 1) {
                    //     $page = 0;
                    // } else {
                    //     $page = ($_GET['page'] * $numRec) - $numRec;
                    // }

                    // // if ($_GET['search']) {
                    // //     $searchTerm = $_GET['search'];
                    // //     $data = $override->getWithLimitSearch($table_name, 'status', 1, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    // //     // $url = 'info.php?id=' . $_GET['id'] . '&site_id=' . Input::get('site_id');
                    // //     // Redirect::to($url);
                    // // } else {
                    // $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    // // }
                }
            } else {
                $pagNum = 0;
                $pagNum = $override->countData($table_name, 'status', 1, 'site_id', $user->data()->site_id);

                $pages = ceil($pagNum / $numRec);
                if (!$_GET['page'] || $_GET['page'] == 1) {
                    $page = 0;
                } else {
                    $page = ($_GET['page'] * $numRec) - $numRec;
                }

                if ($_GET['search_item']) {
                    $searchTerm = $_GET['search_item'];
                    $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                } else {
                    $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                }
            }
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?= $_GET['table']; ?> Data
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?= $_GET['table']; ?></li>
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
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                            <div class="card-header">
                                                <h3 class="card-title">List of <?= $_GET['table']; ?> Records</h3>&nbsp;&nbsp;
                                                <span class="badge badge-info right"><?= $pagNum; ?></span>
                                                <div class="card-tools">
                                                    <ul class="pagination pagination-sm float-right">
                                                        <li class="page-item"><a class="page-link" href="data.php?id=1&status=<?= $_GET['status']; ?>&data=<?= $_GET['data']; ?>">&laquo; Back</a></li>
                                                        <li class="page-item"><a class="page-link" href="index1.php">&raquo; Home</a></li>
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
                                                                        <select class="form-control float-right" name="site_id" style="width: 100%;" autocomplete="off">
                                                                            <option value="">Select Site</option>
                                                                            <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                                <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <input type="submit" name="search_by_site" value="Search by Site" class="btn btn-info"><i class="fas fa-search"></i>
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
                                                            <input type="hidden" name="status" value="<?= $_GET['status'] ?>">
                                                            <input type="text" name="search_item" id="search_item" class="form-control float-right" placeholder="Search Study ID or Patient ID">
                                                            <input type="submit" value="Search" class="btn btn-default"><i class="fas fa-search"></i>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="search-results" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Record Id</th>
                                                <?php if ($_GET['table'] != 'clients') { ?>
                                                    <th>Visit Day</th>
                                                    <th>Visit Code</th>
                                                <?php } ?>

                                                <th>Study Id</th>
                                                <?php if ($_GET['table'] == 'clients') { ?>
                                                    <th>Category</th>
                                                    <th>age</th>
                                                    <th>sex</th>
                                                <?php } else { ?>
                                                    <th>Patient ID</th>
                                                <?php } ?>

                                                <th>Site</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 1;
                                            foreach ($data as $value) {
                                                $sites = $override->getNews('site', 'status', 1, 'id', $value['site_id'])[0];
                                            ?>
                                                <tr>
                                                    <td class="table-user">
                                                        <?= $value['id']; ?>
                                                    </td>
                                                    <?php if ($_GET['table'] != 'clients') { ?>
                                                        <td class="table-user">
                                                            <?= $value['visit_day']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['visit_code']; ?>
                                                        </td>
                                                    <?php } ?>

                                                    <td class="table-user">
                                                        <?= $value['study_id']; ?>
                                                    </td>
                                                    <?php if ($_GET['table'] == 'clients') { ?>
                                                        <?php if ($value['dignosis_type'] == 1) { ?>
                                                            <td class="table-user">
                                                                Cardiac </td>
                                                        <?php } elseif ($value['dignosis_type'] == 2) { ?>
                                                            <td class="table-user">
                                                                Diabetes </td>
                                                        <?php } elseif ($value['dignosis_type'] == 3) { ?>
                                                            <td class="table-user">
                                                                Sickle Cell </td>
                                                        <?php } else { ?>
                                                            <td class="table-user">
                                                                Other
                                                            </td>
                                                        <?php } ?>
                                                        <td class="table-user">
                                                            <?= $value['age']; ?>
                                                        </td>
                                                        <?php if ($value['gender'] == 1) { ?>
                                                            <td class="table-user">
                                                                Male
                                                            </td>
                                                        <?php } elseif ($value['gender'] == 2) { ?>
                                                            <td class="table-user">
                                                                Female
                                                            </td>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <td class="table-user">
                                                            <?= $value['patient_id']; ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="table-user">
                                                        <?= $sites['name']; ?>
                                                    </td>
                                                    <td class="table-user text-center">
                                                        <a href="#" class="btn btn-success">Active</a>
                                                    </td>
                                                    <td class="table-user text-center">
                                                        <!-- <a href="add.php?id=4&cid=<?= $value['id'] ?>" class="btn btn-info">Update Record</a> -->
                                                        <a href="add.php?id=<?= $form_id ?>&cid=<?= $value['patient_id'] ?>&vid=<?= $value['vid'] ?>&vcode=<?= $value['visit_code'] ?>&seq=<?= $value['seq_no'] ?>&sid=<?= $value['study_id'] ?>&vday=<?= $value['visit_day'] ?>&status=3" class="btn btn-info">Update Record</a>
                                                        <!-- add.php?id=7&cid=4&vid=12&vcode=EV&seq=1&sid=1-004&vday=Day%201&status=3 -->
                                                        <a href="#delete_record<?= $value['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete Record</a>

                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="delete_record<?= $value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Delete Record</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <strong style="font-weight: bold;color: red">
                                                                        <p>Are you sure you want to delete this Record ?</p>
                                                                    </strong>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                    <?php if ($user->data()->accessLevel == 1) { ?>
                                                                        <input type="submit" name="delete_record" value="Delete" class="btn btn-danger">
                                                                    <?php } ?>
                                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
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
                                                <th>Record Id</th>
                                                <th>Study Id</th>
                                                <?php if ($_GET['table'] != 'clients') { ?>
                                                    <th>Visit Day</th>
                                                    <th>Visit Code</th>
                                                <?php } ?>
                                                <?php if ($_GET['table'] == 'clients') { ?>
                                                    <th>Category</th>
                                                    <th>age</th>
                                                    <th>sex</th>
                                                <?php } else { ?>
                                                    <th>Patient ID</th>
                                                <?php } ?>
                                                <th>Site</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <li class="page-item">
                                            <a class="page-link" href="data.php?id=2&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
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
                                                                    } ?>" href="data.php?id=2&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?= $i ?>"><?= $i ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li class="page-item">
                                            <a class="page-link" href="data.php?id=2&status=<?= $_GET['status'] ?>&table=<?= $_GET['table'] ?>&site_id=<?= $_GET['site_id'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                                                                                                                                                        echo $_GET['page'] + 1;
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo $i - 1;
                                                                                                                                                                                    } ?>">&raquo;
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!--/.col (right) -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

        <?php  } ?>

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
    <!-- Page specific script -->
    <script>
        // $(function() {
        //     $("#example1").DataTable({
        //         "responsive": true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //     $('#example2').DataTable({
        //         "paging": true,
        //         "lengthChange": false,
        //         "searching": false,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false,
        //         "responsive": true,
        //     });
        // });
    </script>
</body>

</html>