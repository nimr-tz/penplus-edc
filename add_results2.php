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
        if (Input::get('add_results')) {
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

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->


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
                    <?php
                    // $lab_details = $override->get3('lab_requests', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    $lab_requests = $override->get('lab_requests',  'status', 1)[0];


                    $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                    // $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                    // $category = $override->get('category', 'id', $lab_requests['category'])[0];
                    // $lab_tests = $override->get('lab_tests', 'id', $lab_requests['category'])[0];



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


                    <div class="col-md-offset-1 col-md-8">
                        <div class="container">
                            <h4>Lab Test Results</h4>
                            <hr>

                            <!-- <form method="post" action="checkbox-db.php"> -->
                            <form id="validation" method="post">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Site</th>
                                            <th>Test Category</th>
                                            <th>Test Name</th>
                                            <th>Value</th>
                                            <th>Units</th>
                                            <th>Ranges</th>
                                            <th>Sample Type</th>
                                            <th>Blood Number</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($override->get3('clients', 'status', 1, 'screened', 1, 'lab_status', $_GET['status']) as $value) {

                                            $lab_requests = $override->get('lab_requests', 'id', $value['category'])[0];

                                            $category = $override->get('category', 'id', $value['category'])[0];
                                            $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                            $patient_name = $override->get('clients', 'id', $value['patient_id'])[0];
                                            $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                            $site_name = $override->get('site', 'id', $value['site_id'])[0];

                                            $status = 'Pending';
                                            if ($value['lab_status'] == 1) {
                                                $status = 'Done';
                                            }


                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="id[]" value="<?= $value['id'] ?>">
                                                    <input type="checkbox" name="status[]" value="<?= $value['id'] ?>" <?php if ($value['lab_status'] == 1) {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>>
                                                </td>
                                                <td><?= $patient_name['firstname'] . ' - ' . $patient_name['lastname'] ?></td>
                                                <td><?= $site_name['name'] ?></td>
                                                <td><?= $category['name'] ?> </td>
                                                <td><?= $test_name['name'] ?> </td>
                                                <td> <input type="text" name="test_value[]" value="<?php if ($value['test_value']) {
                                                                                                        print_r($value['test_value']);
                                                                                                    }  ?>" <?php if ($user->data()->position != 1) {
                                                                                                                echo 'readonly';
                                                                                                            } ?>>
                                                </td>
                                                <td><?= $site_name[''] ?></td>
                                                <td><?= $site_name[''] ?></td>
                                                <td><?= $site_name[''] ?></td>
                                                <td><?= $status ?></td>
                                                <td><?= $site_name[''] ?></td>
                                                <td><?= $site_name[''] ?></td>
                                                <td>
                                                    <a href="view_lab_results.php?cid=<?= $value['id'] ?>" class="btn btn-info">view</a>
                                                </td>
                                                <td>
                                                    <a href="#delete<?= $value['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="delete<?= $value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Delete Test</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <strong style="font-weight: bold;color: red">
                                                                    <p>Are you sure you want to delete this Test</p>
                                                                </strong>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                <input type="submit" name="delete_test_name" value="Delete" class="btn btn-danger">
                                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                    </tbody>
                                <?php } ?>
                                </table>
                                <div class="text-center">
                                    <input type="submit" name="add_results" class="btn btn-success" value="Add / Update Lab Results">
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="dr"><span></span></div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>