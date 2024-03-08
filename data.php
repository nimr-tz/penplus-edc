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
        $validate = new validate();

        if ($_GET['data'] == 1) {
            $data = null;
            $filename = null;

            if (Input::get('download_clients')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('clients', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('clients', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('clients', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Clients Data';
            } elseif (Input::get('download_screening')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('screening', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('screening', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('screening', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'screening Data';
            } elseif (Input::get('download_demographic')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('demographic', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('demographic', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('demographic', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'demographic Data';
            } elseif (Input::get('download_vital')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('vital', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('vital', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('vital', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'vital Data';
            } elseif (Input::get('download_main_diagnosis')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('main_diagnosis', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('main_diagnosis', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Main diagnosis Data';
            } elseif (Input::get('download_history')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('history', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('history', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('history', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'history Data';
            } elseif (Input::get('download_symptoms')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('symptoms', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('symptoms', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('symptoms', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'symptoms Data';
            } elseif (Input::get('download_cardiac')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('cardiac', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('cardiac', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('cardiac', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Main diagnosis 3 ( Cardiac )';
            } elseif (Input::get('download_diabetic')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('diabetic', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('diabetic', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('diabetic', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Main diagnosis 3 ( diabetic )';
            } elseif (Input::get('download_sickle_cell')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('sickle_cell', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('sickle_cell', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Main diagnosis 3 ( Sickle Cell )';
            } elseif (Input::get('download_siblings')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('sickle_cell_status_table', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Siblings Data';
            } elseif (Input::get('download_results')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('results', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('results', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('results', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Results Data';
            } elseif (Input::get('download_hospitalization')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('hospitalization', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('hospitalization', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('hospitalization', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Hospitalization Data';
            } elseif (Input::get('download_hospitalization_details')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('hospitalization_details', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Hospitalization Details Data';
            } elseif (Input::get('download_admissions')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('hospitalization_table', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Admissions Data';
            } elseif (Input::get('download_treatment_plan')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('treatment_plan', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('treatment_plan', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Treatment_plan Data';
            } elseif (Input::get('download_medications')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('medication_treatments', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Medications Data';
            } elseif (Input::get('download_dgns_complctns_comorbdts')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('dgns_complctns_comorbdts', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Diagnosis, Complications and Comorbiditis Data';
            } elseif (Input::get('download_risks')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('risks', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('risks', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('risks', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Risks Data';
            } elseif (Input::get('download_lab_details')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('lab_details', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('lab_details', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('lab_details', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Lab Details Data';
            } elseif (Input::get('download_lab_requests')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('lab_requests', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('lab_requests', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('lab_requests', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Lab Tests Data';
            } elseif (Input::get('download_test_list')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('test_list', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('test_list', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('test_list', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Test Data';
            } elseif (Input::get('download_social_economic')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('social_economic', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('social_economic', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('social_economic', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Social Economic Data';
            } elseif (Input::get('download_summary')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('summary', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('summary', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('summary', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Summary Data';
            } elseif (Input::get('download_schedule')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('visit', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('visit', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('visit', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Schedule Data';
            } elseif (Input::get('download_study_id')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('study_id', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('study_id', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('study_id', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Study ID Data';
            } elseif (Input::get('download_sites')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('site', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('site', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('site', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Sites List Data';
            }

            $user->exportData($data, $filename);
        }
    }

    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        if ($_GET['site_id'] != null) {
            $kap = $override->getCount1('kap', 'status', 1, 'site_id', $_GET['site_id']);
            $histroy = $override->getCount1('history', 'status', 1, 'site_id', $_GET['site_id']);
            $kap = $override->getCount1('results', 'status', 1, 'site_id', $_GET['site_id']);
            $classification = $override->getCount1('classification', 'status', 1, 'site_id', $_GET['site_id']);
            $outcome = $override->getCount1('outcome', 'status', 1, 'site_id', $_GET['site_id']);
            $economic = $override->getCount1('economic', 'status', 1, 'site_id', $_GET['site_id']);
            $visit = $override->getCount1('visit', 'status', 1, 'site_id', $_GET['site_id']);

            $registered = $override->getCount1('clients', 'status', 1, 'site_id', $_GET['site_id']);
            $screened = $override->getCount1('history', 'status', 1, 'site_id', $_GET['site_id']);
            $eligible = $override->getCount1('history', 'eligible', 1, 'site_id', $_GET['site_id']);
            $enrolled = $override->getCount1('history', 'eligible', 1, 'site_id', $_GET['site_id']);
            $end = $override->getCount1('clients', 'status', 0, 'site_id', $_GET['site_id']);
        } else {
            // $kap = $override->getCount('kap', 'status', 1);
            // $history = $override->getCount('history', 'status', 1);
            // $kap = $override->getCount('results', 'status', 1);
            // $classification = $override->getCount('classification', 'status', 1);
            // $outcome = $override->getCount('outcome', 'status', 1);
            // $economic = $override->getCount('economic', 'status', 1);
            $visit = $override->getCount('visit', 'status', 1);

            $registered = $override->getCount('clients', 'status', 1);
            // $screened = $override->getCount('history', 'status', 1);
            // $eligible = $override->getCount('history', 'eligible', 1);
            // $enrolled = $override->getCount('history', 'eligible', 1);
            $end = $override->getCount('clients', 'status', 0);
        }
    } else {
        $kap = $override->getCount1('kap', 'status', 1, 'site_id', $user->data()->site_id);
        $histroy = $override->getCount1('history', 'status', 1, 'site_id', $user->data()->site_id);
        $kap = $override->getCount1('results', 'status', 1, 'site_id', $user->data()->site_id);
        $classification = $override->getCount1('classification', 'status', 1, 'site_id', $user->data()->site_id);
        $outcome = $override->getCount1('outcome', 'status', 1, 'site_id', $user->data()->site_id);
        $economic = $override->getCount1('economic', 'status', 1, 'site_id', $user->data()->site_id);
        $visit = $override->getCount1('visit', 'status', 1, 'site_id', $user->data()->site_id);

        $registered = $override->getCount1('clients', 'status', 1, 'site_id', $user->data()->site_id);
        $screened = $override->getCount1('history', 'status', 1, 'site_id', $user->data()->site_id);
        $eligible = $override->getCount1('history', 'eligible', 1, 'site_id', $user->data()->site_id);
        $enrolled = $override->getCount1('history', 'eligible', 1, 'site_id', $user->data()->site_id);
        $end = $override->getCount1('clients', 'status', 0, 'site_id', $user->data()->site_id);
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

        $form_name = '';
        $form_title = '';
        if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
            if ($_GET['site_id'] != null) {
                $pagNum = 0;
                if ($_GET['status'] == 1) {
                    $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 2) {
                    $pagNum = $override->countData('screening', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 3) {
                    $pagNum = $override->countData2('demographic', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 4) {
                    $pagNum = $override->countData('vital', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 5) {
                    $pagNum = $override->countData('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 6) {
                    $pagNum = $override->countData('history', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 7) {
                    $pagNum = $override->countData('symptoms', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 8) {
                    $pagNum = $override->countData('diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 9) {
                    $pagNum = $override->countData('diabetic', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 10) {
                    $pagNum = $override->countData('sickle_cell', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 11) {
                    $pagNum = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 12) {
                    $pagNum = $override->countData('results', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 13) {
                    $pagNum = $override->countData('hospitalization', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 14) {
                    $pagNum = $override->countData('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 15) {
                    $pagNum = $override->countData('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 16) {
                    $pagNum = $override->countData('treatment_plan', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 17) {
                    $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 18) {
                    $pagNum = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 19) {
                    $pagNum = $override->countData('risks', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 20) {
                    $pagNum = $override->countData('lab_details', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 21) {
                    $pagNum = $override->countData('lab_requests', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 22) {
                    $pagNum = $override->countData('test_list', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 23) {
                    $pagNum = $override->countData('summary', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 24) {
                    $pagNum = $override->countData('social_economic', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 25) {
                    $pagNum = $override->countData('visit', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 26) {
                    $pagNum = $override->countData('study_id', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 27) {
                    $pagNum = $override->countData('site', 'status', 1, 'site_id', $_GET['site_id']);
                } elseif ($_GET['status'] == 28) {
                    $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
                }
                $pages = ceil($pagNum / $numRec);
                if (!$_GET['page'] || $_GET['page'] == 1) {
                    $page = 0;
                } else {
                    $page = ($_GET['page'] * $numRec) - $numRec;
                }

                if ($_GET['status'] == 1) {
                    $form_name = 'clients';
                    $form_title = 'Clients';
                    $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 2) {
                    $form_name = 'screening';
                    $form_title = 'screening';
                    $clients = $override->getWithLimit1('screening', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 3) {
                    $form_name = 'demographic';
                    $form_title = 'demographic';
                    $clients = $override->getWithLimit1('demographic', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 4) {
                    $clients = $override->getWithLimit1('vital', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 5) {
                    $clients = $override->getWithLimit1('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 6) {
                    $clients = $override->getWithLimit1('history', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 7) {
                    $clients = $override->getWithLimit1('symptoms', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 8) {
                    $clients = $override->getWithLimit1('diagnosis', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 9) {
                    $clients = $override->getWithLimit1('diabetic', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 10) {
                    $clients = $override->getWithLimit1('sickle_cell', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 11) {
                    $clients = $override->getWithLimit1('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 12) {
                    $clients = $override->getWithLimit1('results', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 13) {
                    $clients = $override->getWithLimit1('hospitalization', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 14) {
                    $clients = $override->getWithLimit1('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 15) {
                    $clients = $override->getWithLimit1('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 16) {
                    $clients = $override->getWithLimit1('treatment_plan', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 17) {
                    $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 18) {
                    $clients = $override->getWithLimit1('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 19) {
                    $clients = $override->getWithLimit1('risks', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 20) {
                    $clients = $override->getWithLimit1('lab_details', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 21) {
                    $clients = $override->getWithLimit1('lab_requests', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 22) {
                    $clients = $override->getWithLimit1('test_list', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 23) {
                    $clients = $override->getWithLimit1('summary', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 24) {
                    $clients = $override->getWithLimit1('social_economic', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 25) {
                    $clients = $override->getWithLimit1('visit', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 26) {
                    $clients = $override->getWithLimit1('study_id', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 27) {
                    $clients = $override->getWithLimit1('site', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                } elseif ($_GET['status'] == 28) {
                    $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec);
                }
            } else {

                $pagNum = 0;
                if ($_GET['status'] == 1) {
                    $pagNum = $override->getCount('clients', 'status', 1);
                } elseif ($_GET['status'] == 2) {
                    $pagNum = $override->getCount('screening', 'status', 1);
                } elseif ($_GET['status'] == 3) {
                    $pagNum = $override->getCount('demographic', 'status', 1, 'enrolled', 1);
                } elseif ($_GET['status'] == 4) {
                    $pagNum = $override->getCount('vital', 'status', 1);
                } elseif ($_GET['status'] == 5) {
                    $pagNum = $override->getCount('main_diagnosis', 'status', 1);
                } elseif ($_GET['status'] == 6) {
                    $pagNum = $override->getCount('history', 'status', 1);
                } elseif ($_GET['status'] == 7) {
                    $pagNum = $override->getCount('symptoms', 'status', 1);
                } elseif ($_GET['status'] == 8) {
                    $pagNum = $override->getCount('diagnosis', 'status', 1);
                } elseif ($_GET['status'] == 9) {
                    $pagNum = $override->getCount('diabetic', 'status', 1);
                } elseif ($_GET['status'] == 10) {
                    $pagNum = $override->getCount('sickle_cell', 'status', 1);
                } elseif ($_GET['status'] == 11) {
                    $pagNum = $override->getCount('sickle_cell_status_table', 'status', 1);
                } elseif ($_GET['status'] == 12) {
                    $pagNum = $override->getCount('results', 'status', 1);
                } elseif ($_GET['status'] == 13) {
                    $pagNum = $override->getCount('hospitalization', 'status', 1);
                } elseif ($_GET['status'] == 14) {
                    $pagNum = $override->getCount('hospitalization_details', 'status', 1);
                } elseif ($_GET['status'] == 15) {
                    $pagNum = $override->getCount('hospitalization_table', 'status', 1);
                } elseif ($_GET['status'] == 16) {
                    $pagNum = $override->getCount('treatment_plan', 'status', 1);
                } elseif ($_GET['status'] == 17) {
                    $pagNum = $override->getCount('medication_treatments', 'status', 1);
                } elseif ($_GET['status'] == 18) {
                    $pagNum = $override->getCount('dgns_complctns_comorbdts', 'status', 1);
                } elseif ($_GET['status'] == 19) {
                    $pagNum = $override->getCount('risks', 'status', 1);
                } elseif ($_GET['status'] == 20) {
                    $pagNum = $override->getCount('lab_details', 'status', 1);
                } elseif ($_GET['status'] == 21) {
                    $pagNum = $override->getCount('lab_requests', 'status', 1);
                } elseif ($_GET['status'] == 22) {
                    $pagNum = $override->getCount('test_list', 'status', 1);
                } elseif ($_GET['status'] == 23) {
                    $pagNum = $override->getCount('summary', 'status', 1);
                } elseif ($_GET['status'] == 24) {
                    $pagNum = $override->getCount('social_economic', 'status', 1);
                } elseif ($_GET['status'] == 25) {
                    $pagNum = $override->getCount('visit', 'status', 1);
                } elseif ($_GET['status'] == 26) {
                    $pagNum = $override->getCount('study_id', 'status', 1);
                } elseif ($_GET['status'] == 27) {
                    $pagNum = $override->getCount('site', 'status', 1);
                } elseif ($_GET['status'] == 28) {
                    $pagNum = $override->getCount('medication_treatments', 'status', 1);
                }
                $pages = ceil($pagNum / $numRec);
                if (!$_GET['page'] || $_GET['page'] == 1) {
                    $page = 0;
                } else {
                    $page = ($_GET['page'] * $numRec) - $numRec;
                }

                if ($_GET['status'] == 1) {
                    $clients = $override->getWithLimit('clients', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 2) {
                //     $clients = $override->getWithLimit('screening', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 3) {
                //     $clients = $override->getWithLimit('demographic', 'status', 1, 'enrolled', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 4) {
                //     $clients = $override->getWithLimit('vital', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 5) {
                //     $clients = $override->getWithLimit('main_diagnosis', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 6) {
                //     $clients = $override->getWithLimit('history', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 7) {
                //     $clients = $override->getWithLimit('symptoms', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 8) {
                //     $clients = $override->getWithLimit('diagnosis', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 9) {
                //     $clients = $override->getWithLimit('diabetic', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 10) {
                //     $clients = $override->getWithLimit('sickle_cell', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 11) {
                //     $clients = $override->getWithLimit('sickle_cell_status_table', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 12) {
                //     $clients = $override->getWithLimit('results', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 13) {
                //     $clients = $override->getWithLimit('hospitalization', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 14) {
                //     $clients = $override->getWithLimit('hospitalization_details', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 15) {
                //     $clients = $override->getWithLimit('hospitalization_table', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 16) {
                //     $clients = $override->getWithLimit('treatment_plan', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 17) {
                //     $clients = $override->getWithLimit('medication_treatments', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 18) {
                //     $clients = $override->getWithLimit('dgns_complctns_comorbdts', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 19) {
                //     $clients = $override->getWithLimit('risks', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 20) {
                //     $clients = $override->getWithLimit('lab_details', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 21) {
                //     $clients = $override->getWithLimit('lab_requests', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 22) {
                //     $clients = $override->getWithLimit('test_list', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 23) {
                //     $clients = $override->getWithLimit('summary', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 24) {
                //     $clients = $override->getWithLimit('social_economic', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 25) {
                //     $clients = $override->getWithLimit('visit', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 26) {
                //     $clients = $override->getWithLimit('study_id', 'status', 1, $page, $numRec);
                // } elseif ($_GET['status'] == 27) {
                //     $clients = $override->getWithLimit('site', 'status', 1,  $page, $numRec);
                // } elseif ($_GET['status'] == 28) {
                //     $clients = $override->getWithLimit('medication_treatments', 'status', 1, $page, $numRec);
                }
            }
        } else {
            $pagNum = 0;
            if ($_GET['status'] == 1) {
                $pagNum = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 2) {
                $pagNum = $override->countData('screening', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 3) {
                $pagNum = $override->countData2('demographic', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 4) {
                $pagNum = $override->countData('vital', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 5) {
                $pagNum = $override->countData('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
            } elseif ($_GET['status'] == 6) {
                $pagNum = $override->countData('history', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 7) {
                $pagNum = $override->countData('symptoms', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 8) {
                $pagNum = $override->countData('diagnosis', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 9) {
                $pagNum = $override->countData('diabetic', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 10) {
                $pagNum = $override->countData('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 11) {
                $pagNum = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 12) {
                $pagNum = $override->countData('results', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 13) {
                $pagNum = $override->countData('hospitalization', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 14) {
                $pagNum = $override->countData('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 15) {
                $pagNum = $override->countData('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 16) {
                $pagNum = $override->countData('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 17) {
                $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 18) {
                $pagNum = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 19) {
                $pagNum = $override->countData('risks', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 20) {
                $pagNum = $override->countData('lab_details', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 21) {
                $pagNum = $override->countData('lab_requests', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 22) {
                $pagNum = $override->countData('test_list', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 23) {
                $pagNum = $override->countData('summary', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 24) {
                $pagNum = $override->countData('social_economic', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 25) {
                $pagNum = $override->countData('visit', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 26) {
                $pagNum = $override->countData('study_id', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 27) {
                $pagNum = $override->countData('site', 'status', 1, 'site_id', $user->data()->site_id);
            } elseif ($_GET['status'] == 28) {
                $pagNum = $override->countData('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
            }
            $pages = ceil($pagNum / $numRec);
            if (!$_GET['page'] || $_GET['page'] == 1) {
                $page = 0;
            } else {
                $page = ($_GET['page'] * $numRec) - $numRec;
            }

            if ($_GET['status'] == 1) {
                $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 2) {
                $clients = $override->getWithLimit1('screening', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 3) {
                $clients = $override->getWithLimit1('demographic', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 4) {
                $clients = $override->getWithLimit1('vital', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 5) {
                $clients = $override->getWithLimit1('main_diagnosis', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 6) {
                $clients = $override->getWithLimit1('history', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 7) {
                $clients = $override->getWithLimit1('symptoms', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 8) {
                $clients = $override->getWithLimit1('diagnosis', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 9) {
                $clients = $override->getWithLimit1('diabetic', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 10) {
                $clients = $override->getWithLimit1('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 11) {
                $clients = $override->getWithLimit1('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 12) {
                $clients = $override->getWithLimit1('results', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 13) {
                $clients = $override->getWithLimit1('hospitalization', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 14) {
                $clients = $override->getWithLimit1('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 15) {
                $clients = $override->getWithLimit1('hospitalization_table', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 16) {
                $clients = $override->getWithLimit1('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 17) {
                $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 18) {
                $clients = $override->getWithLimit1('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 19) {
                $clients = $override->getWithLimit1('risks', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 20) {
                $clients = $override->getWithLimit1('lab_details', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 21) {
                $clients = $override->getWithLimit1('lab_requests', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 22) {
                $clients = $override->getWithLimit1('test_list', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 23) {
                $clients = $override->getWithLimit1('summary', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 24) {
                $clients = $override->getWithLimit1('social_economic', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 25) {
                $clients = $override->getWithLimit1('visit', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 26) {
                $clients = $override->getWithLimit1('study_id', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 27) {
                $clients = $override->getWithLimit1('site', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            } elseif ($_GET['status'] == 28) {
                $clients = $override->getWithLimit1('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
            }
        }
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
                                    Clients
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Clients</li>
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
                                                <div class="col-sm-3">
                                                    <div class="card-header">
                                                        <h3 class="card-title">List of Clients</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $registered; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="site_id" style="width: 100%;" autocomplete="off">
                                                                                <option value="">Select Site</option>
                                                                                <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-primary">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="download_clients" value="Download" class="btn btn-info">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
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
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Category</th>
                                                    <th>age</th>
                                                    <th>sex</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($clients as $value) {
                                                    $sites = $override->getNews('site', 'status', 1, 'id', $value['site_id'])[0];
                                                ?>
                                                    <tr>
                                                        <td class="table-user">
                                                            <?= $value['study_id']; ?>
                                                        </td>
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
                                                        <td class="table-user">
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=4&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Category</th>
                                                    <th>age</th>
                                                    <th>sex</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
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
        <?php } elseif ($_GET['id'] == 2) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?= $form_title; ?>
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?= $form_title; ?></li>
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
                                                <div class="col-sm-3">
                                                    <div class="card-header">
                                                        <h3 class="card-title">List of <?= $form_title; ?></h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $form_title; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="site_id" style="width: 100%;" autocomplete="off">
                                                                                <option value="">Select Site</option>
                                                                                <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-primary">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="hidden" name="form_name" value="<?= $form_name; ?>">
                                                                            <input type="submit" name="download" value="Download" class="btn btn-info">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
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
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Category</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($clients as $value) {
                                                    $sites = $override->getNews('site', 'status', 1, 'id', $value['site_id'])[0];
                                                ?>
                                                    <tr>
                                                        <td class="table-user">
                                                            <?= $value['study_id']; ?>
                                                        </td>
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
                                                        <td class="table-user">
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=4&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Category</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
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
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                        if ($_GET['site_id'] != null) {
                                            $clients = $override->getDataDesc2('visit', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('visit', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('visit', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    visit
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">visit</li>
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
                                                <div class="col-sm-3">
                                                    <div class="card-header">
                                                        <h3 class="card-title">List of Visits</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $visit; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="site_id" style="width: 100%;" autocomplete="off">
                                                                                <option value="">Select Site</option>
                                                                                <?php foreach ($override->get('sites', 'status', 1) as $site) { ?>
                                                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-primary">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="download_visit" value="Download" class="btn btn-info">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
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
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Visit Name</th>
                                                    <th>Expected Date</th>
                                                    <th>Visit Date</th>
                                                    <th>Reason</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($clients as $value) {
                                                    $sites = $override->getNews('sites', 'status', 1, 'id', $value['site_id'])[0];
                                                ?>
                                                    <tr>
                                                        <td class="table-user">
                                                            <?= $value['study_id']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['visit_name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['expected_date']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['visit_date']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['comments']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?php if ($value['visit_status'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">Done</a>
                                                            <?php } else if ($value['visit_status'] == 2) { ?>
                                                                <a href="#" class="btn btn-warning">Missed</a>
                                                            <?php } else if ($value['visit_status'] == 0) { ?>
                                                                <a href="#" class="btn btn-danger">Not Eligible</a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-danger">Not Known</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Visit Name</th>
                                                    <th>Expected Date</th>
                                                    <th>Visit Date</th>
                                                    <th>Reason</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
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
        <?php } elseif ($_GET['id'] == 21) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php
                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                        if ($_GET['site_id'] != null) {
                                            $clients = $override->getDataDesc1('study_id', 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc('study_id', 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc1('visit', 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    Study ID
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Study ID</li>
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
                                                <div class="col-sm-3">
                                                    <div class="card-header">
                                                        <h3 class="card-title">List of Study ID's</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $visit; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="site_id" style="width: 100%;" autocomplete="off">
                                                                                <option value="">Select Site</option>
                                                                                <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="search_by_site" value="Search" class="btn btn-primary">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                                    ?>
                                                        <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="row-form clearfix">
                                                                        <div class="form-group">
                                                                            <input type="submit" name="download_study_id" value="Download" class="btn btn-info">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
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
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Client Id</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                foreach ($clients as $value) {
                                                    $sites = $override->getNews('site', 'status', 1, 'id', $value['site_id'])[0];
                                                ?>
                                                    <tr>
                                                        <td class="table-user">
                                                            <?= $value['study_id']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $value['client_id']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <?php if ($value['status'] == 1) { ?>
                                                                <a href="#" class="btn btn-success">Taken</a>
                                                            <?php } elseif ($value['status'] == 0) { ?>
                                                                <a href="#" class="btn btn-warning">Free</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
                                                    <th>Client Id</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
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