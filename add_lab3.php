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
            foreach (Input::get('test_name') as $value) {
                $test = $override->getNews('test_list', 'status', 1, 'id', $value)[0];
                $user->createRecord('lab_requests', array(
                    // 'visit_date' => Input::get('date_requested'),
                    'study_id' => $_GET['sid'],
                    'visit_code' => $_GET['vcode'],
                    'visit_day' => $_GET['vday'],
                    'seq_no' => $_GET['seq'],
                    'vid' => $_GET['vid'],
                    // 'lab_date' => Input::get('date_requested'),
                    // 'category' => $test['category'],
                    // 'sub_category' => $test['sub_category'],
                    'test_id' => $test['id'],
                    'test_name' => $test['name'],
                    'patient_id' => $_GET['cid'],
                    'staff_id' => $user->data()->id,
                    'status' => 1,
                    'request_status' => 0,
                    'site_id' => $user->data()->site_id,
                ));
            }
        } elseif (Input::get('add_test2')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    $test_category = $override->get('category', 'name', Input::get('name'));
                    if ($test_category) {
                        $errorMessage = 'New Test Category Already Added';
                    } else {
                        $user->createRecord('category', array(
                            'name' => Input::get('name'),
                            'status' => Input::get('status'),
                            'description' => Input::get('description'),
                        ));
                        $successMessage = 'New Test Category Added';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
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
// $client = $override->get('client', 'id', $_GET['position'])[0];
// $position = $override->get('position', 'id', $staff['position'])[0];

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
                            <h1>Category Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Category Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php
            $lab_requests = $override->getNews('lab_requests', 'status', 1, 'patient_id', $_GET['cid']);
            ?>

            <style>
                .img-thumb-path {
                    width: 100px;
                    height: 80px;
                    object-fit: scale-down;
                    object-position: center center;
                }
            </style>
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">List of My Tests Request</h3>
                    <div class="card-tools">
                        <a class="btn btn-flat btn-sm btn-primary" href="#add_new_request" role="button" data-toggle="modal"><span class="fas fa-plus text-primary">&nbsp;</span>Book New Test Request</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="container-fluid">
                            <table class="table table-bordered table-hover table-striped">
                                <colgroup>
                                    <col width="5%">
                                    <col width="20%">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="15%">
                                    <col width="15%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-gradient-primary text-light">
                                        <th>#</th>
                                        <th>Date Created</th>
                                        <th>Client Id</th>
                                        <th>Test</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if ($lab_requests) {
                                        foreach ($lab_requests as $value) {
                                            $requests_name = $override->get('test_list', 'id', $value['test_id'])['0'];
                                            $client_name = $override->get('clients', 'id', $value['patient_id'])['0'];

                                    ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i++; ?></td>
                                                <td class=""><?php echo date("Y-m-d H:i", strtotime($value['date_created'])) ?></td>
                                                <td class=""><?= $client_name['firstname'] . ' - ' . $client_name['lastname'] ?></td>
                                                <td class="">
                                                    <p class="m-0 truncate-1"><?= $requests_name['name'] ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    switch ($value['request_status']) {
                                                        case 0:
                                                            echo '<span class="rounded-pill badge badge-secondary ">Pending</span>';
                                                            break;
                                                        case 1:
                                                            echo '<span class="rounded-pill badge badge-primary ">Approved</span>';
                                                            break;
                                                        case 2:
                                                            echo '<span class="rounded-pill badge badge-warning ">Sample Collected</span>';
                                                            break;
                                                        case 3:
                                                            echo '<span class="rounded-pill badge badge-primary bg-teal ">Delivered to Lab</span>';
                                                            break;
                                                        case 4:
                                                            echo '<span class="rounded-pill badge badge-success ">Done</span>';
                                                            break;
                                                        case 5:
                                                            echo '<span class="rounded-pill badge badge-danger ">Cancelled</span>';
                                                            break;
                                                        case 6:
                                                            echo '<span class="rounded-pill badge-light badge border text-dark ">Report Uploaded</span>';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td align="center">
                                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        Action
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item" href="./?page=appointments/view_appointment&id=<?= $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                        <?php if ($row['status'] <= 1) : ?>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>

                                    <div class="modal fade" id="add_new_request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="add" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Request New Tests</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="date_requested" class="control-label">Date requested</label>
                                                                    <input type="datetime-local" name="date_requested" id="date_requested" class="form-control form-control-border" placeholder="Enter appointment Schedule" value="<?php echo isset($schedule) ? date("Y-m-d\TH:i", strtotime($schedule)) : '' ?>" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Test Name</label>
                                                                        <select class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="test_name[]">
                                                                            <?php
                                                                            $tests = $override->get("test_list", "status", 1);
                                                                            foreach ($tests as $test1) { ?>
                                                                                <option value="<?= $test1['id'] ?>"><?= $test1['name'] ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!-- /.form-group -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="add_test" value="Add New Test Request" class="btn btn-info">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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