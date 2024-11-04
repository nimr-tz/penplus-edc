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
        if (Input::get('add_test')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                // try {
                //     $user->createRecord('position', array(
                //         'name' => Input::get('name'),
                //     ));
                //     $successMessage = 'Position Successful Added';
                // } catch (Exception $e) {
                //     die($e->getMessage());
                // }
                // $test = $override->get('test_list', 'status', 1);
                // print_r($test);
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}

?>





<!DOCTYPE html>
<html lang="en">
<?php include 'headBar.php'; ?>

<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navBar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sideBar.php'; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- Main content -->

            <div class="card-header">
                <h3 class="card-title">List of My Appointments</h3>
                <div class="card-tools">
                    <td><a href="#modal-lg" role="button" class="btn btn-flat btn-sm btn-primary" data-toggle="modal">Book New Appointmen</a></td>
                </div>
            </div>
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
                        <th>Code</th>
                        <th>Test</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td class=""><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td class=""><?= $row['code'] ?></td>
                        <td class="">
                            <p class="m-0 truncate-1"><?= $test ?></p>
                        </td>
                        <td class="text-center">
                            <?php
                            switch ($row['status']) {
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

                </tbody>
            </table>

            <div class="modal fade" id="modal-lg">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Large Modal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <div class="container-fluid"> -->
                            <!-- <form method="post" id="appointment-form"> -->
                            <form id="validation" method="post">
                                <input type="hidden" name="id" value="<?php
                                                                        // echo isset($id) ? $id : '' 
                                                                        ?>">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="schedule" class="control-label">Schedule</label>
                                        <input type="datetime-local" name="schedule" id="schedule" class="form-control form-control-border" placeholder="Enter appointment Schedule" value="<?php
                                                                                                                                                                                            // echo isset($schedule) ? date("Y-m-d\TH:i", strtotime($schedule)) : ''
                                                                                                                                                                                            ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="test_ids" class="control-label">Test</label>
                                        <select name="test_ids[]" id="test_ids" class="form-control form-control-border select2" placeholder="Enter appointment Name" multiple>
                                            <?php
                                            $test = $override->getNews('test_list', 'delete_flag', 0, 'status', 1);
                                            ?>
                                            <option value="">Select Tests</option>
                                            <?php foreach ($test as $values) { ?>
                                                <option value="<?= $values['id'] ?>"><?= $values['name']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="prescription" class="control-label">Prescription <small><em>(If Any)</em></small></label>
                                        <input type="file" name="prescription" accept="application/msword, .doc, .docx, .txt, application/pdf" id="prescription" class="form-control form-control-border">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" name="add_test" value="Submit" class="btn btn-primary">
                                </div>
                            </form>
                            <!-- </div> -->

                            <!-- <p>One fine body&hellip;</p> -->
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            </section>
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
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {
            $('#create_new').click(function() {
                uni_modal("Add New Appointment", "appointments/manage_appointment.php", 'mid-large')
            })
            $('.view_data').click(function() {
                uni_modal("Appointment Details", "appointments/view_appointment.php?id=" + $(this).attr('data-id'))
            })
            $('.edit_data').click(function() {
                uni_modal("Update Appointment Details", "appointments/manage_appointment.php?id=" + $(this).attr('data-id'), 'mid-large')
            })
            $('.delete_data').click(function() {
                _conf("Are you sure to delete this Appointment permanently?", "delete_appointment", [$(this).attr('data-id')])
            })
            $('.table td, .table th').addClass('py-1 px-2 align-middle')
            $('.table').dataTable({
                columnDefs: [{
                    orderable: false,
                    targets: 5
                }],
            });
        })

        function delete_appointment($id) {
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=delete_appointment",
                method: "POST",
                data: {
                    id: $id
                },
                dataType: "json",
                error: err => {
                    console.log(err)
                    alert_toast("An error occured.", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.reload();
                    } else {
                        alert_toast("An error occured.", 'error');
                        end_loader();
                    }
                }
            })
        }




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