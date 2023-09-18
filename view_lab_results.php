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
                            <h1>Lab Results Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Lab Results Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="content py-5">
                        <div class="card card-outline card-primary rounded-0 shadow">
                            <div class="card-header">
                                <h4 class="card-title"><b>Booked Appointment Details</b></h4>
                                <div class="card-tools">
                                    <?php if (isset($status) && $status == 0) : ?>
                                        <button class="btn btn-danger bg-gradient-maroon btn-flat btn-sm" type="button" id="cancel_data"> Cancel Appointment</button>
                                    <?php endif; ?>
                                    <?php if (isset($status) && $status <= 1) : ?>
                                        <button class="btn btn-primary btn-flat btn-sm" type="button" id="edit_data"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-danger btn-flat btn-sm" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
                                    <?php endif; ?>
                                    <a class="btn btn-default border btn-flat btn-sm" href="add_results.php" id="delete_data"><i class="fa fa-angle-left"></i> Back</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid" id="outprint">
                                    <div class="row">
                                        <div class="col-2 border bg-gradient-primary text-light">Appointment Code</div>
                                        <div class="col-4 border"><?= isset($code) ? $code : "" ?></div>
                                        <div class="col-2 border bg-gradient-primary text-light">Schedule</div>
                                        <div class="col-4 border"><?= isset($schedule) ? date("M d, Y h:i A", strtotime($schedule)) : "" ?></div>
                                        <div class="col-2 border bg-gradient-primary text-light">Patient Name</div>
                                        <div class="col-10 border"><?= isset($fullname) ? $fullname : "" ?></div>
                                        <div class="col-1 border bg-gradient-primary text-light">Gender</div>
                                        <div class="col-3 border"><?= isset($gender) ? $gender : "" ?></div>
                                        <div class="col-1 border bg-gradient-primary text-light">Contact #</div>
                                        <div class="col-3 border"><?= isset($contact) ? $contact : "" ?></div>
                                        <div class="col-1 border bg-gradient-primary text-light">Email</div>
                                        <div class="col-3 border"><?= isset($email) ? $email : "" ?></div>
                                        <div class="col-2 border bg-gradient-primary text-light">Address</div>
                                        <div class="col-10 border"><?= isset($address) ? $address : "" ?></div>
                                        <div class="col-2 border bg-gradient-primary text-light">Status</div>
                                        <div class="col-4 border ">
                                            <?php
                                            switch ($status) {
                                                case 0:
                                                    echo '<span class="">Pending</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class">Approved</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class">Sample Collected</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="rounde">Delivered to Lab</span>';
                                                    break;
                                                case 4:
                                                    echo '<span class">Done</span>';
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
                                            if (isset($prescription_path)) {
                                                echo "<a href='" . base_url . $prescription_path . "' target='_blank' download='" . (explode('?', str_replace("uploads/prescriptions/", '', $prescription_path))[0]) . "'>" . (explode('?', str_replace("uploads/prescriptions/", '', $prescription_path))[0]) . "</a>";
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </div>
                                        <?php if (isset($status) && $status == 6) : ?>
                                            <div class="col-2 border bg-gradient-primary text-light">Uploaded Report</div>
                                            <div class="col-10 border ">
                                                <?php if (isset($code) && is_file(base_app . "uploads/reports/" . $code . ".pdf")) : ?>
                                                    <a href='<?= base_url . "uploads/reports/" . $code . ".pdf" ?>' target='_blank' download='<?= $code . ".pdf" ?>'><?= $code . ".pdf" ?></a>
                                                <?php else : ?>
                                                    N/A
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
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
                                                    <th>Ranges</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php foreach ($override->get('lab_requests', 'patient_id', $_GET['cid']) as $value) {

                                                    $category = $override->get('category', 'id', $value['category'])[0];
                                                    $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                                    $patient_name = $override->get('clients', 'id', $value['patient_id'])[0];
                                                    $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                                    $site_name = $override->get('site', 'id', $value['site_id'])[0];


                                                    $status = 'Pending';
                                                    if ($value['status'] == 1) {
                                                        $status = 'Done';
                                                    }


                                                ?>
                                                    <tr>
                                                        <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                                        <td class="py-1 px-2"><?= $row['name'] ?></td>
                                                        <td class="py-1 px-2"></td>
                                                        <td class="py-1 px-2"></td>
                                                        <td class="py-1 px-2 text-right"><?= number_format($row['cost'], 2) ?></td>
                                                    </tr>

                                            </tbody>
                                        </table>
                                    </fieldset>
                                    <hr>
                                    <fieldset>
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
                                                    if (isset($test_ids) && count($test_ids) > 0) :
                                                        $history = $conn->query("SELECT* FROM `history_list` where appointment_id = '{$id}' order by unix_timestamp(date_created) asc");
                                                        while ($row = $history->fetch_assoc()) :
                                                ?>
                                                        <tr>
                                                            <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                                            <td class="py-1 px-2"><?= date("M d, Y H:i", strtotime($row['date_created'])) ?></td>
                                                            <td class="py-1 px-2"><?= $row['remarks'] ?></td>
                                                            <td class="py-1 px-2">
                                                                <?php
                                                                switch ($row['status']) {
                                                                    case 0:
                                                                        echo '<span class="">Pending</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class">Approved</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class">Sample Collected</span>';
                                                                        break;
                                                                    case 3:
                                                                        echo '<span class="rounde">Delivered to Lab</span>';
                                                                        break;
                                                                    case 4:
                                                                        echo '<span class">Done</span>';
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
                                                    <?php endwhile; ?>
                                                    <?php if ($history->num_rows <= 0) : ?>
                                                        <tr>
                                                            <th class="py-1 text-center" colspan="4">No Data</th>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <th class="py-1 text-center" colspan="4">No Data</th>
                                                    </tr>
                                            <?php endif;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->

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
                    <h3 class="card-title">List of My Appointments</h3>
                    <div class="card-tools">
                        <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Book New Appointment</a>
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
                                        <th>Code</th>
                                        <th>Test</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $i = 1;
                                    // $qry = $conn->query("SELECT * from `appointment_list` where client_id ='{$_settings->userdata('id')}' order by unix_timestamp(date_created) desc ");
                                    // while ($row = $qry->fetch_assoc()) :
                                    //     $tests = $conn->query("SELECT * FROM `test_list` where id in (SELECT test_id FROM `appointment_test_list` where appointment_id = '{$row['id']}')");
                                    //     $test = "N/A";
                                    //     if ($tests->num_rows > 0) {
                                    //         $res = $tests->fetch_all(MYSQLI_ASSOC);
                                    //         $test_arr = array_column($res, 'name');
                                    //         $test = implode(", ", $test_arr);
                                    //     }

                                    foreach ($override->get('lab_requests', 'patient_id', $_GET['cid']) as $value) {

                                        $category = $override->get('category', 'id', $value['category'])[0];
                                        $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                        $patient_name = $override->get('clients', 'id', $value['patient_id'])[0];
                                        $test_name = $override->get('lab_tests', 'id', $value['test_name'])[0];
                                        $site_name = $override->get('site', 'id', $value['site_id'])[0];


                                        $status = 'Pending';
                                        if ($value['status'] == 1) {
                                            $status = 'Done';
                                        }
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id[]" value="<?= $value['id'] ?>">
                                                <input type="checkbox" name="status[]" value="<?= $value['id'] ?>" <?php if ($value['status'] == 1) {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                            </td>
                                            <td><?= $patient_name['firstname'] . ' - ' . $patient_name['lastname'] ?></td>
                                            <td><?= $site_name['name'] ?></td>
                                            <td><?= $category['name'] ?> </td>
                                            <td><?= $test_name['name'] ?> </td>
                                            <td>
                                                <input type="text" name="test_value[]" value="<?php if ($value['test_value']) {
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
                                                <a href="#" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>



                                        <tr>
                                            <td class="text-center"><?php echo $i++; ?>
                                                <input type="hidden" name="id[]" value="<?= $value['id'] ?>">
                                                <input type="checkbox" name="status[]" value="<?= $value['id'] ?>" <?php if ($value['status'] == 1) {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>

                                            </td>
                                            <td class=""><?php echo date("Y-m-d H:i", strtotime($value['created_on'])) ?></td>
                                            <td class=""><?= $value['patient_id'] ?></td>
                                            <td class="">
                                                <p class="m-0 truncate-1"><?= $test ?></p>
                                            </td>
                                            <td><?= $patient_name['firstname'] . ' - ' . $patient_name['lastname'] ?></td>

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
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
            </script>
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