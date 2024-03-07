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
        if (Input::get('reset_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Reset Successful';
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
            $successMessage = 'User Restored Successful';
        } elseif (Input::get('restore_staff')) {
            $user->updateRecord('user', array(
                'status' => 1,
            ), Input::get('id'));
            $successMessage = 'User Deleted Successful';
        } elseif (Input::get('add_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_status' => array(
                    'required' => true,
                ),
            ));

            if ($validate->passed()) {
                $user->updateRecord('visit', array(
                    'visit_date' => Input::get('visit_date'),
                    'visit_status' => Input::get('visit_status'),
                    'comments' => Input::get('comments'),
                    'patient_id' => Input::get('cid'),
                    'update_on' => date('Y-m-d H:i:s'),
                    'update_id' => $user->data()->id,
                ), Input::get('id'));

                $successMessage = 'Visit Updates  Successful';
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('search_by_site')) {

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
        } elseif (Input::get('clear_data')) {

            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('name')) {
                        if (Input::get('name') == 'user' || Input::get('name') == 'study_id' || Input::get('name') == 'sites' || Input::get('name') == 'position' || Input::get('name') == 'household' || Input::get('name') == 'insurance' || Input::get('name') == 'district' || Input::get('name') == 'education' || Input::get('name') == 'lung_rads' || Input::get('name') == 'occupation' || Input::get('name') == 'payments' || Input::get('name') == 'relation') {
                            $errorMessage = 'Table ' . '"' . Input::get('name') . '"' . '  can not be Cleared';
                        } else {
                            $clearData = $override->clearDataTable(Input::get('name'));
                            $successMessage = 'Table ' . '"' . Input::get('name') . '"' . ' Cleared Successfull';
                        }
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
        } elseif (Input::get('unset_study_id')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('name') == 'study_id') {
                        $study_id = $override->getData('study_id');
                        foreach ($study_id as $row) {
                            $user->updateRecord('study_id', array(
                                'client_id' => 0,
                                'status' => 0,
                            ), $row['id']);
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }


        if ($_GET['status'] == 'data') {
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
            } elseif (Input::get('download_kap')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('kap', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('kap', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('kap', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Kap Data';
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
                $filename = 'Kap history';
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
            } elseif (Input::get('download_classification')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('classification', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('classification', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('classification', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Classification Data';
            } elseif (Input::get('download_outcome')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('outcome', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('outcome', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('outcome', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Outcome Data';
            } elseif (Input::get('download_economic')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('economic', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('economic', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('economic', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Economic Data';
            } elseif (Input::get('download_visit')) {
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                    if ($_GET['site_id'] != null) {
                        $data = $override->getNews('visit', 'status', 1, 'site_id', $_GET['site_id']);
                    } else {
                        $data = $override->get('visit', 'status', 1);
                    }
                } else {
                    $data = $override->getNews('visit', 'status', 1, 'site_id', $user->data()->site_id);
                }
                $filename = 'Study Id';
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
                $filename = 'Study Id Data';
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
    <title>Lung Cancer Database | Info</title>

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
                                    <?php
                                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                                        if ($_GET['site_id'] != null) {
                                            $clients = $override->getDataDesc2('clients', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('clients', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('clients', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
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
                                                    <th>Interview Type</th>
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
                                                    <th>Interview Type</th>
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
        <?php } elseif ($_GET['id'] == 6) { ?>
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
                                            $clients = $override->getDataDesc2('kap', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('kap', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('kap', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    Kap
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">Kap</li>
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
                                                        <h3 class="card-title">List of kap</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $kap; ?></span>
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
                                                                            <input type="submit" name="download_kap" value="Download" class="btn btn-info">
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=5&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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
        <?php } elseif ($_GET['id'] == 7) { ?>
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
                                            $clients = $override->getDataDesc2('history', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('history', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('history', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    history
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">history</li>
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
                                                        <h3 class="card-title">List of history</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $history; ?></span>
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
                                                                            <input type="submit" name="download_history" value="Download" class="btn btn-info">
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=6&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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

        <?php } elseif ($_GET['id'] == 8) { ?>
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
                                            $clients = $override->getDataDesc2('results', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('results', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('results', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    results
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">results</li>
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
                                                        <h3 class="card-title">List of results</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $results; ?></span>
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
                                                                            <input type="submit" name="download_results" value="Download" class="btn btn-info">
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=7&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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

        <?php } elseif ($_GET['id'] == 9) { ?>
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
                                            $clients = $override->getDataDesc2('classification', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('classification', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('classification', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    classification
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">classification</li>
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
                                                        <h3 class="card-title">List of classification</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $classification; ?></span>
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
                                                                            <input type="submit" name="download_classifiaction" value="Download" class="btn btn-info">
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=8&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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

        <?php } elseif ($_GET['id'] == 10) { ?>
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
                                            $clients = $override->getDataDesc2('outcome', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('outcome', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('outcome', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    } ?>
                                    outcome
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">outcome</li>
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
                                                        <h3 class="card-title">List of Outcomes</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $outcome; ?></span>
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
                                                                            <input type="submit" name="download_outcome" value="Download" class="btn btn-info">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php } ?>
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
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Study Id</th>
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=10&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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
        <?php } elseif ($_GET['id'] == 11) { ?>
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
                                            $clients = $override->getDataDesc2('economic', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                                        } else {
                                            $clients = $override->getDataDesc1('economic', 'status', 1, 'id');
                                        }
                                    } else {
                                        $clients = $override->getDataDesc2('economic', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                                    }
                                    ?>
                                    eonomic
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                    <li class="breadcrumb-item active">eonomic</li>
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
                                                        <h3 class="card-title">List of eonomic</h3>&nbsp;&nbsp;
                                                        <span class="badge badge-info right"><?= $economic; ?></span>
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
                                                                            <input type="submit" name="download_economic" value="Download" class="btn btn-info">
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
                                                            <?= $sites['name']; ?>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="#" class="btn btn-success">Active</a>
                                                        </td>
                                                        <td class="table-user">
                                                            <a href="add.php?id=9&cid=<?= $value['id'] ?>" class="btn btn-info">Update</a>
                                                        </td>
                                                    </tr>
                                                <?php $x++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Study Id</th>
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
                                                    $sites = $override->getNews('sites', 'status', 1, 'id', $value['site_id'])[0];
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