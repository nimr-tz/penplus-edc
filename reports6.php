<?php
require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

if ($user->isLoggedIn()) {
    try {
        $site_data = $override->getData('site');
        $Total = $override->getCount('clients', 'status', 1);
        $data_enrolled = $override->getCount1('clients', 'status', 1, 'enrolled', 1);

        $successMessage = 'Report Successful Created';
    } catch (Exception $e) {
        die($e->getMessage());
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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>RECRUITMENTS STATUS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">RECRUITMENTS STATUS</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php
            $test_list = $override->get('test_list', 'delete_flag', 0);
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
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">PENPLUS RECRUITMENTS STATUS AS OF <?= date('Y-m-d') ?></h3>
                    <div class="card-tools">
                        <a class="btn btn-default border btn-flat btn-sm" href="index1.php"><i class="fa fa-angle-left"></i> Back</a>
                        <a class="btn btn-flat btn-sm btn-primary" href="reports6_1.php"><span class="fas fa-download text-default">&nbsp;&nbsp;</span>Download Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="container-fluid">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No.</th>
                                        <th rowspan="2">SITE</th>
                                        <th rowspan="2">ENROLLED</th>
                                        <th colspan="6"> Rheumatic Heart Disease </th>
                                    </tr>
                                    <tr>
                                        <th>Pure mitral stenosis</th>
                                        <th>Pure mitral regurgitation</th>
                                        <th>Mixed mitral valve disease (MS + MR)</th>
                                        <th>Isolated aortic valve disease (AVD)</th>
                                        <th>mixed mitral and aortic valve disease (MMAVD)</th>
                                        <th>Other</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    foreach ($site_data as $row) {
                                        $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $row['id']);
                                        $enrolled_Total = $override->countData('clients', 'status', 1, 'enrolled', 1);
                                        $cardiac1 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 1, 'site_id', $row['id']);
                                        $cardiac_Total1 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 1);
                                        $cardiac2 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 2, 'site_id', $row['id']);
                                        $cardiac_Total2 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 2);
                                        $cardiac3 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 3, 'site_id', $row['id']);
                                        $cardiac_Total3 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 3);
                                        $cardiac4 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 4, 'site_id', $row['id']);
                                        $cardiac_Total4 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 4);
                                        $cardiac5 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 5, 'site_id', $row['id']);
                                        $cardiac_Total5 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 5);
                                        // $cardiac6 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 6, 'site_id', $row['id']);
                                        // $cardiac_Total6 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 6);
                                        $cardiac7 = $override->countData2('cardiac', 'status', 1, 'sub_heumatic', 96, 'site_id', $row['id']);
                                        $cardiac_Total7 = $override->countData('cardiac', 'status', 1, 'sub_heumatic', 96);
                                        // $diabetes_Total = $override->countData('cardiac', 'status', 1, 'diabetes', 1);
                                        // $end_study = $override->countData2('cardiac', 'status', 1, 'end_study', 1, 'site_id', $row['id']);
                                        // $end_study_Total = $override->countData('cardiac', 'status', 1, 'end_study', 1);
                                    ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td align="right"><?= $enrolled ?></td>
                                            <td align="right"><?= $cardiac1 ?></td>
                                            <td align="right"><?= $cardiac2 ?></td>
                                            <td align="right"><?= $cardiac3 ?></td>
                                            <td align="right"><?= $cardiac4 ?></td>
                                            <td align="right"><?= $cardiac5 ?></td>
                                            <td align="right"><?= $cardiac6 ?></td>
                                            <td align="right"><?= $cardiac7 ?></td>
                                        </tr>


                                    <?php
                                        $i++;
                                    } ?>

                                    <tr>
                                        <td align="right" colspan="2"><b>Total</b></td>
                                        <td align="right"><b><?= $enrolled_Total ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total1 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total2 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total3 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total4 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total5 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total6 ?></b></td>
                                        <td align="right"><b><?= $cardiac_Total7 ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
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