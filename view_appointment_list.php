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
        if (Input::get('add_test')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $test_category = $override->get('test_list', 'name', Input::get('name'));
                    if ($test_category) {
                        $errorMessage = 'Test Already Added';
                    } else {
                        $user->createRecord('test_list', array(
                            'category' => Input::get('category'),
                            'sub_category' => Input::get('sub_category'),
                            'name' => Input::get('name'),
                            'status' => Input::get('status'),
                            'description' => Input::get('description'),
                            'units' => Input::get('units'),
                            'minimum' => Input::get('minimum'),
                            'maximum' => Input::get('maximum'),
                            'cost' => Input::get('cost'),
                            'delete_flag' => 0,
                        ));
                        $successMessage = 'New Test Added';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('update_test')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('test_list', array(
                        'category' => Input::get('category'),
                        'sub_category' => Input::get('sub_category'),
                        'name' => Input::get('name'),
                        'status' => Input::get('status'),
                        'description' => Input::get('description'),
                        'units' => Input::get('units'),
                        'minimum' => Input::get('minimum'),
                        'maximum' => Input::get('maximum'),
                        'cost' => Input::get('cost'),
                        'delete_flag' => 0,
                    ), Input::get('id'));
                    $successMessage = 'Test Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('deactivate_test')) {
            $user->updateRecord('test_list', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Test Deactivated Successful';
        } elseif (Input::get('activate_test')) {
            $user->updateRecord('test_list', array(
                'status' => 1,
            ), Input::get('id'));
            $successMessage = 'Test Activated Successful';
        } elseif (Input::get('delete_test')) {
            $user->deleteRecord('test_list', 'id', Input::get('id'));
            $successMessage = 'Test Deleted Successful';
        }
    }
} else {
    Redirect::to('index.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include 'headBar.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Test Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Test Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php
            $appointment_list = $override->getData('appointment_list', 'client_id', $_GET['cid'])[0];
            $clients = $override->getData('clients', 'id', $_GET['cid'])[0];

            if ($clients['gender'] = 1) {
                $gender = 'Male';
            } else {
                $gender = 'Male';
            }

            ?>

            <style>
                .img-thumb-path {
                    width: 100px;
                    height: 80px;
                    object-fit: scale-down;
                    object-position: center center;
                }
            </style>

            <!-- Main content -->
            <div class="content py-5">
                <div class="card card-outline card-primary rounded-0 shadow">
                    <div class="card-header">
                        <h4 class="card-title"><b>Booked Appointment Details</b></h4>
                        <div class="card-tools">
                            <?php if ($appointment_list['status'] == 0) : ?>
                                <button class="btn btn-danger bg-gradient-maroon btn-flat btn-sm" type="button" id="cancel_data"> Cancel Appointment</button>
                            <?php endif; ?>
                            <?php if ($appointment_list['status'] <= 1) : ?>
                                <!-- <a class="btn btn-default bg-gradient-navy btn-flat btn-sm" href="#update_results" role="button" data-toggle="modal"> Update Status</a> -->
                                <a class="btn btn-default bg-gradient-navy btn-flat btn-sm" href="update_results.php?id=<?= $appointment_list['id'] ?>&cid=<?= $_GET['cid'] ?>"> Update Status</a>
                                <!-- <button class="btn btn-primary btn-flat btn-sm" type="button" id="edit_data"><i class="fa fa-edit"></i> Edit</button> -->
                                <!-- <button class="btn btn-danger btn-flat btn-sm" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button> -->
                            <?php endif; ?>
                            <a class="btn btn-default border btn-flat btn-sm" href="appointments.php"><i class="fa fa-angle-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid" id="outprint">
                            <div class="row">
                                <div class="col-2 border bg-gradient-primary text-light">Appointment Code</div>
                                <div class="col-4 border"><?= $appointment_list['code']; ?></div>
                                <div class="col-2 border bg-gradient-primary text-light">Date Requested</div>
                                <div class="col-4 border"><?= $appointment_list['date_requested']; ?></div>
                                <div class="col-2 border bg-gradient-primary text-light">Patient Name</div>
                                <div class="col-10 border"><?= $clients['firstname'] . '-' . $clients['lastname']; ?></div>
                                <div class="col-1 border bg-gradient-primary text-light">Gender</div>
                                <div class="col-3 border"><?= $gender ?></div>
                                <div class="col-1 border bg-gradient-primary text-light">Contact #</div>
                                <div class="col-3 border"><?= $clients['phone_number'] ?></div>
                                <div class="col-1 border bg-gradient-primary text-light">Email</div>
                                <div class="col-3 border"><?= $clients['phone_number'] ?></div>
                                <div class="col-2 border bg-gradient-primary text-light">Address</div>
                                <div class="col-10 border"><?= $clients['physical_address'] ?></div>
                                <div class="col-2 border bg-gradient-primary text-light">Status</div>
                                <div class="col-4 border ">
                                    <?php
                                    switch ($appointment_list['status']) {
                                        case 0:
                                            echo '<span class="">Pending</span>';
                                            break;
                                        case 1:
                                            echo '<span class">Done</span>';

                                            break;
                                        case 2:
                                            echo '<span class">Sample Collected</span>';
                                            break;
                                        case 3:
                                            echo '<span class="rounde">Delivered to Lab</span>';
                                            break;
                                        case 4:
                                            echo '<span class">Approved</span>';
                                            break;
                                        case 5:
                                            echo '<span class">Cancelled</span>';
                                            break;
                                        case 6:
                                            echo '<span class">Report Uploaded</span>';
                                            break;
                                    }
                                    ?>
                                </div>
                                <div class="col-2 border bg-gradient-primary text-light">Prescription</div>
                                <div class="col-4 border ">
                                    <?php

                                    echo "N/A";

                                    ?>
                                </div>
                                <div class="col-2 border bg-gradient-primary text-light">Uploaded Report</div>
                                <div class="col-10 border ">
                                    N/A
                                </div>
                            </div>
                            <hr>
                            <fieldset>
                                <legend class="text-muted">List of Tests</legend>
                                <table class="table table-striped table-bordered">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="45%">
                                        <col width="45%">
                                    </colgroup>
                                    <thead>
                                        <tr class="bg-gradient-primary text-light">
                                            <th class="text-center">#</th>
                                            <th>Name</th>
                                            <th>Value</th>
                                            <th>Units</th>
                                            <th>Ranges</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($override->get('appointment_test_list', 'patient_id', $_GET['cid']) as $value) {
                                            $test = $override->get("test_list", "id", $value['test_id'])[0];
                                        ?>
                                            <tr>
                                                <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                                <td class="py-1 px-2"><?= $test['name'] ?></td>
                                                <td class="py-1 px-2"><?= $value['test_value'] ?></td>
                                                <td class="py-1 px-2"><?= $test['units'] ?></td>
                                                <td class="py-1 px-2"><?= $test['minimum'] . ' -' . $test['maximum']  ?></td>
                                                <td class="py-1 px-2 text-right"><?= number_format($test['cost'], 2) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </fieldset>
                            <hr>
                            <!-- <fieldset>
                                <legend class="text-muted">Update History</legend>
                                <table class="table table-striped table-bordered">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="20%">
                                        <col width="40%">
                                        <col width="30%">
                                    </colgroup>
                                    <thead>
                                        <tr class="bg-gradient-primary text-light">
                                            <th class="text-center">#</th>
                                            <th>Date</th>
                                            <th>Remarks</th>
                                            <th>New Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $history = $override->get('history_list', 'client_id', $_GET['cid']);
                                        if ($history) {
                                            foreach ($override->get('history_list', 'client_id', $_GET['cid']) as $value) {
                                        ?>
                                                <tr>
                                                    <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                                    <td class="py-1 px-2"><?= date("M d, Y H:i", strtotime($value['date_created'])) ?></td>
                                                    <td class="py-1 px-2"><?= $value['remarks'] ?></td>
                                                    <td class="py-1 px-2">
                                                        <?php
                                                        switch ($value['status']) {
                                                            case 0:
                                                                echo '<span class="">Pending</span>';
                                                                break;
                                                            case 1:
                                                                echo '<span class">Done</span>';
                                                                break;
                                                            case 2:
                                                                echo '<span class">Sample Collected</span>';
                                                                break;
                                                            case 3:
                                                                echo '<span class="rounde">Delivered to Lab</span>';
                                                                break;
                                                            case 4:
                                                                echo '<span class">Approved</span>';
                                                                break;
                                                            case 5:
                                                                echo '<span class">Cancelled</span>';
                                                                break;
                                                            case 6:
                                                                echo '<span class">Report Uploaded</span>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <th class="py-1 text-center" colspan="4">No Data</th>
                                            </tr>
                                        <?php
                                        } ?>

                                    </tbody>
                                </table>
                            </fieldset> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->
        <?php include 'footerBar.php'; ?>

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
        $(document).ready(function() {
            // $('#wait_ds').hide();
            $('#category').change(function() {
                var getUid = $(this).val();
                // $('#wait_ds').show();
                $.ajax({
                    url: "process.php?content=category",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#sub_category').html(data);
                        // console.log(data);
                        // $('#wait_ds').hide();
                    }
                });

            });

            $('#update_category').change(function() {
                var getUid = $(this).val();
                // $('#wait_ds').show();
                $.ajax({
                    url: "process.php?content=category",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#update_sub_category').html(data);
                        // console.log(data);
                        // $('#wait_ds').hide();
                    }
                });

            });
        });



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
    </script>
</body>

</html>