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

$numRec = 35;

// require 'vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// $spreadsheet = new Spreadsheet();
// $sheet = $spreadsheet->getActiveSheet();


if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();

        if (Input::get('delete_record')) {
            $user->updateRecord($_GET['table'], array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Recored Deleted Successful';
        }

        if (Input::get('restore_record')) {
            $user->updateRecord($_GET['table'], array(
                'status' => 1,
            ), Input::get('id'));
            $successMessage = 'Recored Restored Successful';
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

        // if (Input::get('download_xls')) {
        //     $validate = new validate();
        //     $validate = $validate->check($_POST, array(
        //         'table' => array(
        //             'required' => true,
        //         ),
        //     ));
        //     if ($validate->passed()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['download_xls'])) {
                $ext = 'xls';
                $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
                Redirect::to($url);
                $pageError = $validate->errors();
            } else if (isset($_POST['download_xlsx'])) {
                $ext = 'xlsx';
                $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
                Redirect::to($url);
                $pageError = $validate->errors();
            } else if (isset($_POST['download_csv'])) {
                $ext = 'csv';
                $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
                Redirect::to($url);
                $pageError = $validate->errors();
            } else if (isset($_POST['download_stata'])) {
                $ext = 'dta';
                $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
                Redirect::to($url);
                $pageError = $validate->errors();
            }
        }
        //     }
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

        <?php if ($_GET['id'] == 1) { ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
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
                                                        $tables['Tables_in_penplus'] == 'lab_requests' || $tables['Tables_in_penplus'] == 'user'
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
                                                                <form method="post">
                                                                    <input type="hidden" name="data" value="<?= $x; ?>">
                                                                    <input type="hidden" name="table" value="<?= $tables['Tables_in_penplus']; ?>">
                                                                    <button type="submit" name="download_xls">Download xls</button>&nbsp;&nbsp;&nbsp;
                                                                    <button type="submit" name="download_xlsx">Download xlsx</button>&nbsp;&nbsp;&nbsp;
                                                                    <button type="submit" name="download_csv">Download csv</button>&nbsp;&nbsp;&nbsp;
                                                                    <!-- <button type="submit" name="download_stata">Download stata Data</button>&nbsp;&nbsp;&nbsp; -->
                                                                    <hr>
                                                                    <a href="data.php?id=2&table=<?= $tables['Tables_in_penplus'] ?>" role=" button" class="btn btn-info"> View Recoreds </a>
                                                                </form>
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
                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item">
                                                <a class="page-link" href="data.php?id=1&table=<?= $_GET['table'] ?>&page=<?php if (($_GET['page'] - 1) > 0) {
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
                                                                        } ?>" href="data.php?id=1&table=<?= $_GET['table'] ?>&page=<?= $i ?>"><?= $i ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="page-item">
                                                <a class="page-link" href="data.php?id=1&table=<?= $_GET['table'] ?>&page=<?php if (($_GET['page'] + 1) <= $pages) {
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
            //     $form_id = 24;
            // }
            elseif ($table_name == 'summary') {
                $form_id = 22;
            }
            $form_name = $table_name;
            $form_title = $table_name;
            $form_id = $form_id;

            if ($user->data()->power == 1) {
                if ($_GET['search_item']) {
                    $searchTerm = $_GET['search_item'];
                    $pagNum = 0;
                    if ($table_name == 'clients') {
                        $pagNum = $override->getWithLimit0SearchCount($table_name, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                    } elseif ($table_name == 'visit') {
                        $pagNum = $override->getWithLimit0SearchCount($table_name, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                    } else {
                        $pagNum = $override->getWithLimit0SearchCount($table_name, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    }
                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }
                    if ($table_name == 'clients') {
                        $data = $override->getWithLimitSearch0($table_name,  $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                    } elseif ($table_name == 'visit') {
                        $data = $override->getWithLimitSearch0($table_name,  $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                    } else {
                        $data = $override->getWithLimitSearch0($table_name,  $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    }
                } else {
                    $pagNum = 0;
                    $pagNum = $override->getNo($table_name);
                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }
                    $data = $override->getWithLimit0($table_name, $page, $numRec);
                }
            } else if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                if ($_GET['site_id'] != null) {
                    $pagNum = 0;

                    if ($table_name == 'clients') {
                        $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                    } elseif ($table_name == 'visit') {
                        $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                    } else {
                        $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    }
                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }

                    if ($_GET['search_item']) {
                        $searchTerm = $_GET['search_item'];
                        if ($table_name == 'clients') {
                            $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                        } elseif ($table_name == 'visit') {
                            $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                        } else {
                            $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $_GET['site_id'], $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                        }
                    } else {
                        if ($table_name == 'clients') {
                            $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } else {
                            $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        }
                    }
                } else {

                    $pagNum = 0;
                    if ($table_name == 'clients') {
                        $pagNum = $override->getWithLimitSearchCount($table_name, 'status', 1, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                    } elseif ($table_name == 'visit') {
                        $pagNum = $override->getWithLimitSearchCount($table_name, 'status', 1, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                    } else {
                        $pagNum = $override->getWithLimitSearchCount($table_name, 'status', 1, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    }
                    $pages = ceil($pagNum / $numRec);
                    if (!$_GET['page'] || $_GET['page'] == 1) {
                        $page = 0;
                    } else {
                        $page = ($_GET['page'] * $numRec) - $numRec;
                    }

                    if ($_GET['search_item']) {
                        $searchTerm = $_GET['search_item'];
                        if ($table_name == 'clients') {
                            $data = $override->getWithLimitSearch($table_name, 'status', 1, $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                        } elseif ($table_name == 'visit') {
                            $data = $override->getWithLimitSearch($table_name, 'status', 1, $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                        } else {
                            $data = $override->getWithLimitSearch($table_name, 'status', 1, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                        }
                    } else {
                        if ($table_name == 'clients') {
                            $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($table_name == 'visit') {
                            $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } else {
                            $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        }
                    }
                }
            } else {
                $pagNum = 0;
                if ($table_name == 'clients') {
                    $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                } elseif ($table_name == 'visit') {
                    $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                } else {
                    $pagNum = $override->getWithLimit1SearchCount($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                }
                $pages = ceil($pagNum / $numRec);
                if (!$_GET['page'] || $_GET['page'] == 1) {
                    $page = 0;
                } else {
                    $page = ($_GET['page'] * $numRec) - $numRec;
                }

                if ($_GET['search_item']) {
                    $searchTerm = $_GET['search_item'];
                    if ($table_name == 'clients') {
                        $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'site_id', 'study_id', 'site_id');
                    } elseif ($table_name == 'visit') {
                        $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'client_id', 'study_id', 'site_id');
                    } else {
                        $data = $override->getWithLimit1Search($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec, $searchTerm, 'id', 'patient_id', 'study_id', 'site_id');
                    }
                } else {
                    if ($table_name == 'clients') {
                        $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    } elseif ($table_name == 'visit') {
                        $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    } else {
                        $data = $override->getWithLimit1($table_name, 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                    }
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
                                                    <form method="get" action="">
                                                        <div class="form-inline">
                                                            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                                            <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
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
                                                <th>Name</th>
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
                                                $name = $override->get('clients', 'id', $value['patient_id'])[0];
                                                $name2 = $override->get('clients', 'id', $value['client_id'])[0];
                                            ?>
                                                <tr>
                                                    <td class="table-user">
                                                        <?= $value['id']; ?>
                                                    </td>
                                                    <?php if ($_GET['table'] == 'clients') { ?>
                                                        <td class="table-user">
                                                            <?= $value['firstname'].' '.$value['middlename'].' '.$value['lastname']; ?>
                                                        </td>
                                                    <?php } elseif ($_GET['table'] == 'visit') { ?>
                                                        <td class="table-user">
                                                            <?= $name2['firstname'].' '.$name2['middlename'].' '.$name2['lastname']; ?>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td class="table-user">
                                                            <?= $name['firstname'].' '.$name['middlename'].' '.$name['lastname']; ?>
                                                        </td>
                                                    <?php } ?>
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
                                                        <?php if ($table_name == 'clients') { ?>
                                                            <td class="table-user text-center">
                                                                <?= $value['id']; ?>
                                                            </td>
                                                        <?php } else if ($table_name == 'visit') { ?>
                                                            <td class="table-user text-center">
                                                                <?= $value['client_id']; ?>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td class="table-user text-center">
                                                                <?= $value['patient_id']; ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <td class="table-user">
                                                        <?= $sites['name']; ?>
                                                    </td>
                                                    <td class="table-user text-center">
                                                        <?php if ($value['status'] == 1) { ?>
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        <?php } else { ?>
                                                            <a href="#" class="btn btn-danger">Deleted</a>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="table-user text-center">
                                                        <a href="add.php?id=<?= $form_id ?>&cid=<?= $value['patient_id'] ?>&vid=<?= $value['vid'] ?>&vcode=<?= $value['visit_code'] ?>&seq=<?= $value['seq_no'] ?>&sid=<?= $value['study_id'] ?>&vday=<?= $value['visit_day'] ?>&status=3" class="btn btn-info">Update Record</a>
                                                        <a href="#delete_record<?= $value['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete Record</a>
                                                        <a href="#restore_record<?= $value['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">Restore Record</a>
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
                                                <div class="modal fade" id="restore_record<?= $value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                    <h4>Restore Record</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <strong style="font-weight: bold;color: green">
                                                                        <p>Are you sure you want to Restore this Record ?</p>
                                                                    </strong>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                    <?php if ($user->data()->accessLevel == 1) { ?>
                                                                        <input type="submit" name="restore_record" value="Restore" class="btn btn-warning">
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
