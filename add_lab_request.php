<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;

if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('add_lab_request')) {
            $validate = $validate->check($_POST, array(
                'lab_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                print_r($_POST);
                try {

                    // if (in_array($id, $checked_array)) {
                    //     $status = 1;
                    // }

                    $test_names = $override->getNews('lab_requests', 'status', 1, 'patient_id', $_GET['cid'])[0];
                    // if (Input::get('test_name')) {
                    // for ($i = 0; $i < count(Input::get('test_name')); $i++) {
                    $i = 0;
                    foreach (Input::get('test_name') as $test_name) {
                        if (in_array($test_name, $test_names)) {
                            // $user->updateRecord('lab_requests', array(
                            //     'visit_date' => Input::get('lab_date'),
                            //     'study_id' => $_GET['sid'],
                            //     'visit_code' => $_GET['vcode'],
                            //     'visit_day' => $_GET['vday'],
                            //     'seq_no' => $_GET['seq'],
                            //     'vid' => $_GET['vid'],
                            //     'lab_date' => Input::get('lab_date'),
                            //     'category' => Input::get('category')[$i],
                            //     // 'sub_category' => Input::get('sub_category')[$i],
                            //     'test_name' => Input::get('test_name')[$i],
                            //     'test_value' => '',
                            //     'patient_id' => $_GET['cid'],
                            //     'staff_id' => $user->data()->id,
                            //     'patient_id' => $_GET['cid'],
                            //     'staff_id' => $user->data()->id,
                            //     'status' => 1,
                            //     'created_on' => date('Y-m-d'),
                            //     'site_id' => $user->data()->site_id,
                            // ), $test_name['id']);
                        } else {
                            $user->createRecord('lab_requests', array(
                                'visit_date' => Input::get('lab_date'),
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'lab_date' => Input::get('lab_date'),
                                'category' => Input::get('category')[$i],
                                // 'sub_category' => Input::get('sub_category')[$i],
                                'test_name' => Input::get('test_name')[$i],
                                'test_value' => '',
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                        $i++;
                    }
                    // }

                    // foreach ($checked_array as $test_id) {
                    // $status = 0;
                    // if (in_array($test_name, $checked_array)) {
                    //     $status = 1;
                    // }
                    // $user->updateRecord('lab_requests', array(
                    //     'test_name' => $test_id,
                    //     'test_value' => '',
                    //     'staff_id' => $user->data()->id,
                    //     'status' => $status,
                    // ));
                    // $user->createRecord('lab_requests', array(
                    //     'visit_date' => Input::get('lab_date'),                            
                    //     'study_id' => $_GET['sid'],
                    //     'visit_code' => $_GET['vcode'],
                    //     'visit_day' => $_GET['vday'],
                    //     'seq_no' => $_GET['seq'],
                    //     'vid' => $_GET['vid'],
                    //     'lab_date' => Input::get('lab_date'),
                    //     'test_name' => $test_id,
                    //     'test_value' => '',
                    //     'patient_id' => $_GET['cid'],
                    //     'staff_id' => $user->data()->id,
                    //     'status' => $status,
                    // ));
                    // $i++;
                    // }

                    // $user->createRecord('history_list', array(
                    //     'appointment_id' => $appointment_list['id'],
                    //     'staff_id' => $user->data()->id,
                    //     'status' => 0,
                    //     'site_id' => $user->data()->site_id,
                    //     'date_created' => date("Y-m-d\TH:i", strtotime($date)),
                    //     'remarks' => 'Lab test requested',
                    //     'client_id' => $_GET['cid'],
                    // ));

                    // Redirect::to('add_results.php?status=' . $_GET['status']);
                    $successMessage = 'Lab Request added Successful';
                    // Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    // die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_results')) {
            $validate = $validate->check($_POST, array(
                // 'lab_date' => array(
                //     'required' => true,
                // ),

            ));
            if ($validate->passed()) {
                try {
                    $i = 0;
                    $checked_array = Input::get('status');
                    foreach (Input::get('id') as $id) {
                        $status = 0;
                        if (in_array($id, $checked_array)) {
                            $status = 1;
                        }
                        $user->updateRecord('lab_requests', array(
                            'test_value' => Input::get('test_value')[$i],
                            'staff_id' => $user->data()->id,
                            'status' => $status,
                        ), $id);
                        $i++;
                    }
                    Redirect::to('add_results.php?status=' . $_GET['status']);
                    $successMessage = 'Lab Request added Successful';
                    // Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_test_name')) {
            $user->updateRecord('lab_requests', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Test Deleted Successful';
        } elseif (Input::get('update_category')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('category', array(
                        'name' => Input::get('name'),
                        'status' => Input::get('status'),
                        'description' => Input::get('description'),
                    ), Input::get('id'));
                    $successMessage = 'New Test Category Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('deactivate_category')) {
            $user->updateRecord('category', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Category Deleted Successful';
        } elseif (Input::get('activate_category')) {
            $user->updateRecord('category', array(
                'status' => 1,
            ), Input::get('id'));
            $successMessage = 'Category Deleted Successful';
        } elseif (Input::get('delete_category')) {
            $user->deleteRecord('category', 'id', Input::get('id'));
            $successMessage = 'Category Deleted Successful';
        }
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
    <title>PenPlus DataBase | Lab Section</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <?php include 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidemenu.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <!-- <div class="container-fluid"> -->
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1>
                                <?php if ($errorMessage) { ?>
                                    <div class="block">
                                        <div class="alert alert-danger">
                                            <b>Error!</b> <?= $errorMessage ?>
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        </div>
                                    </div>
                                <?php } elseif ($pageError) { ?>
                                    <div class="block col-md-12">
                                        <div class="alert alert-danger">
                                            <b>Error!</b> <?php foreach ($pageError as $error) {
                                                                echo $error . ' , ';
                                                            } ?>
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        </div>
                                    </div>
                                <?php } elseif ($successMessage) { ?>
                                    <div class="block">
                                        <div class="alert alert-success">
                                            <b>Success!</b> <?= $successMessage ?>
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </h1>
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- /.container-fluid -->
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Request Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">Request Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <style>
                .img-thumb-path {
                    width: 100px;
                    height: 80px;
                    object-fit: scale-down;
                    object-position: center center;
                }
            </style>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Request New Tests</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post">
                    <?php
                    $lab_requests = $override->get('lab_requests', 'patient_id', $_GET['cid']);
                    ?>
                    <div class="card-body">
                        <!-- Date -->
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date requested:</label>
                                    <input type="date" name="lab_date" class="form-control" value="<?= $lab_requests[0]['visit_date']; ?>" required />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>Test Category</label>
                                <div class="form-group">
                                    <?php
                                    $x = 1;
                                    $categorys = $override->get("category", "status", 1);
                                    foreach ($categorys as $category1) { ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input myCheckbox" name="category[]" id="category_id<?= $category1['id']; ?>" value="<?= $category1['id']; ?>" <?php foreach ($lab_requests as $lab_request) {
                                                                                                                                                                                                        if ($category1['id'] == $lab_request['category']) {
                                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                                        }
                                                                                                                                                                                                    } ?> onclick="add_test(this)">
                                            <label class="form-check-label" for="category_id"><?= $category1['name']; ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <label>Test Name</label>
                                <div class="form-group">
                                    <div class="form-check" id="test_id">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>List of Lab requests for ( <?= $_GET['vday']; ?> )</label>
                                <div class="form-group">
                                    <table id="lab_table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Medication name </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            <?php $x = 1;
                                            foreach ($override->getNews('lab_requests', 'patient_id', $_GET['cid'], 'status', 1) as $treatment) {
                                                $batches = $override->getNews('category', 'status', 1, 'id', $treatment['category']);
                                                $medications = $override->getNews('test_list', 'status', 1, 'id', $treatment['test_name']);
                                            ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $medications[0]['name']; ?></td>
                                                    <td>
                                                        <span class="badge bg-danger">
                                                            <a href="#delete_med<?= $treatment['id'] ?>" role="button" data-toggle="modal">Delete</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php $x++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- /.form-group -->
                        </div>

                        <div class="row-form clearfix">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">LAB REQUESTS</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <!-- <table id="medication_table" class="table order-list"> -->
                                            <table id="medication_table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Entry Date </th>
                                                        <th> Medication name </th>
                                                        <th> Action </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody">
                                                    <?php $x = 1;
                                                    foreach ($override->getNews('lab_requests', 'patient_id', $_GET['cid'], 'status', 1) as $treatment) {
                                                        $batches = $override->getNews('category', 'status', 1, 'id', $treatment['category']);
                                                        $medications = $override->getNews('test_list', 'status', 1, 'id', $treatment['test_name']);
                                                    ?>
                                                        <tr>
                                                            <td><?= $x; ?></td>
                                                            <td><?= $treatment['visit_date'] ?></td>
                                                            <td><?= $medications[0]['name']; ?></td>
                                                            <td>
                                                                <?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1) { ?>
                                                                    <span class="badge bg-info">
                                                                        <a href="#update_med<?= $treatment['id'] ?>" role="button" data-toggle="modal">Update</a>
                                                                    </span>
                                                                <?php } ?>

                                                                <span class="badge bg-danger">
                                                                    <a href="#delete_med<?= $treatment['id'] ?>" role="button" data-toggle="modal">Delete</a>
                                                                </span>
                                                            </td>
                                                            <!-- <input type="button" class="ibtnDel1 btn btn-md btn-warning" value="Remove"> -->
                                                        </tr>
                                                        <!-- <div class="modal fade" id="update_med<?= $treatment['id'] ?>">
                                                            <div class="modal-dialog">
                                                                <form method="post">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Medication Form</h4><br><?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1) {
                                                                                                                                if ($batches) {
                                                                                                                                    echo ' Medication :- ' . $medications[0]['name'] . ' Batch : - (' . $batches[0]['serial_name'] . ')';
                                                                                                                                }
                                                                                                                            } ?>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>Date</label>
                                                                                            <input class="form-control" type="date" name="date" style="width: 100%;" value="<?php if ($treatment['date']) {
                                                                                                                                                                                print_r($treatment['date']);
                                                                                                                                                                            }  ?>" required />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>Start Date</label>
                                                                                            <input class="form-control" type="date" name="start_date" id="start_date" style="width: 100%;" value="<?php if ($treatment['start_date']) {
                                                                                                                                                                                                        print_r($treatment['start_date']);
                                                                                                                                                                                                    }  ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>Medication name</label>
                                                                                            <select name="medication_id" id="medication_id" class="form-control select2" style="width: 100%;" required>
                                                                                                <?php if ($medications[0]['name']) { ?>
                                                                                                    <option value="<?= $medications[0]['id'] ?>"><?= $medications[0]['name']; ?></option>
                                                                                                <?php } ?>
                                                                                                <?php foreach ($override->get('medications', 'status', 1) as $medication) { ?>
                                                                                                    <option value="<?= $medication['id'] ?>"><?= $medication['name']; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>Action</label>
                                                                                            <select name="medication_action" class="form-control" id="medication_action" style="width: 80%;" required>
                                                                                                <option value="<?= $treatment['medication_action'] ?>"><?php if ($treatment['medication_action']) {
                                                                                                                                                            if ($treatment['medication_action'] == 1) {
                                                                                                                                                                echo 'Continue';
                                                                                                                                                            } elseif ($treatment['medication_action'] == 2) {
                                                                                                                                                                echo 'Start';
                                                                                                                                                            } elseif ($treatment['medication_action'] == 3) {
                                                                                                                                                                echo 'Stop';
                                                                                                                                                            } elseif ($treatment['medication_action'] == 4) {
                                                                                                                                                                echo 'Not Eligible';
                                                                                                                                                            }
                                                                                                                                                        } else {
                                                                                                                                                            echo 'Select';
                                                                                                                                                        } ?>
                                                                                                </option>
                                                                                                <option value="1">Continue</option>
                                                                                                <option value="2">Start</option>
                                                                                                <option value="3">Stop</option>
                                                                                                <option value="4">Not Eligible</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>DOSE DESCRIPTION</label>
                                                                                            <textarea class="form-control" name="medication_units" id="medication_units" rows="3" placeholder="Type other medication dose here..." required>
                                                                                                                    <?php if ($treatment['units']) {
                                                                                                                        print_r($treatment['units']);
                                                                                                                    }  ?>
                                                                                                                </textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>Dose Duration</label>
                                                                                            <input class="form-control" type="number" min="0" max="1000" name="medication_dose" id="medication_dose" style="width: 100%;" value="<?php if ($treatment['medication_dose']) {
                                                                                                                                                                                                                                        print_r($treatment['medication_dose']);
                                                                                                                                                                                                                                    }  ?>" required />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6">
                                                                                    <div class="row-form clearfix">
                                                                                        <div class="form-group">
                                                                                            <label>End Date ( If Stop )</label>
                                                                                            <input class="form-control" type="date" name="end_date" id="end_date" style="width: 100%;" value="<?php if ($treatment['end_date']) {
                                                                                                                                                                                                    print_r($treatment['end_date']);
                                                                                                                                                                                                }  ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <input type="hidden" name="id" value="<?= $treatment['id'] ?>">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <input type="submit" name="update_medication" class="btn btn-primary" value="Save changes">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div> -->
                                                        <!-- /.modal -->
                                                        <!-- <div class="modal fade" id="delete_med<?= $treatment['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form method="post">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                            <h4>Delete this Medication</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <strong style="font-weight: bold;color: red">
                                                                                <p>Are you sure you want to delete this Medication ?</p>
                                                                            </strong>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="id" value="<?= $treatment['id'] ?>">
                                                                            <input type="submit" name="delete_medication" value="Delete" class="btn btn-danger">
                                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div> -->
                                                    <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <hr>
                                            <button type="button" class="btn btn-block btn-info" onclick="add_lab()"><ion-icon name='add-circle-outline'></ion-icon>Add New Tests</button>

                                            <!-- <input type="button" class="btn btn-lg btn-block btn-info" onclick="add_Medication()" value="Add New Medication" /> -->
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer clearfix">
                                            <ul class="pagination pagination-sm m-0 float-right">
                                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <hr>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <a href="info.php?id=3&status=1" class="btn btn-danger">Back</a>
                        <input type="submit" name="add_lab_request" value="Submit" class="btn btn-info">
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.content-wrapper -->
        <?php include 'footer.php'; ?>

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- dropzonejs -->
    <script src="plugins/dropzone/min/dropzone.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- Page specific script -->
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })


            // $('#category').change(function() {
            // var district_id = $(this).val();
            // $.ajax({
            //     url: "process.php?content=district_id",
            //     method: "GET",
            //     data: {
            //         district_id: district_id
            //     },
            //     dataType: "text",
            //     success: function(data) {
            //         $('#ward').html(data);
            //     }
            // });
            // });

            // for ($i = 1; $i <= 6; $i++) {
            // const category = `category_id${i}`;
            const category_id1 = document.getElementById('category_id1');
            const category_id2 = document.getElementById('category_id2');
            const category_id3 = document.getElementById('category_id3');
            const category_id4 = document.getElementById('category_id4');
            const category_id5 = document.getElementById('category_id5');
            const category_id6 = document.getElementById('category_id6');


            category_id1.addEventListener('change', function() {
                if (this.checked) {
                    const category_id = this.value;
                    $.ajax({
                        url: "process.php?content=category_id",
                        method: "GET",
                        data: {
                            category_id: category_id
                        },
                        dataType: "text",
                        success: function(data) {
                            $('#test_id').html(data);
                            // alert(data);
                        }
                    });
                    // console.log(value); // or pass the value to a function
                    // alert(value);
                } else {
                    $('#test_id').html('');
                }
            });
            // }

            // const checkboxes = document.querySelectorAll('.myCheckbox');

            // checkboxes.forEach(function(checkbox) {
            //     checkbox.addEventListener('change', function() {
            //         let checkedValues = [];
            //         checkboxes.forEach(function(cb) {
            //             if (cb.checked) {
            //                 checkedValues.push(cb.value);
            //             }
            //         });
            //         // console.log(checkedValues); // or pass the values to a function
            //         alert(checkedValues);
            //     });
            // });

        })

        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() {
                myDropzone.enqueueFile(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End

        var items = 0;

        function add_lab(name) {
            items++;
            test_name = name.value

            var html = "<tr>";
            html += "<td>" + items + "</td>";
            html += '<td>' + test_name + '</td>';
            html += "<td><button type='button' onclick='deleteRow(this);'>Remove</button></td>"
            html += "</tr>";



            var row = document.getElementById("tbody").insertRow();
            row.innerHTML = html;
        }

        function deleteRow(button) {
            items--
            button.parentElement.parentElement.remove();
            // first parentElement will be td and second will be tr.
        }
    </script>
</body>

</html>