<?php
require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();


$numRec = 3;

if (Input::exists('post')) {

    if (Input::get('search_by_site1')) {
        $validate = new validate();
        $validate = $validate->check($_POST, array(
            'site_id' => array(
                'required' => true,
            ),
        ));
        if ($validate->passed()) {

            $url = 'index1.php?&site_id=' . Input::get('site_id');
            Redirect::to($url);
            $pageError = $validate->errors();
        }
    }
}

if ($user->isLoggedIn()) {
    try {
        // $data = $override->getWithLimit1('symptoms', 'hba1c', 1, 'status', 1, $page, $numRec);

        // $Numerator = intval($override->getNo2('diabetic', 'diagnosis', 1, 'status', 1, 'visit_date', 6));
        $Numerator = intval($override->getNo1_1());
        $Denominator = intval($override->getNo2('diabetic', 'diagnosis', 1, 'status', 1));

        $propotion1 = intval(intval($Numerator) / intval($Denominator) * 100);

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

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diabetes | Reports</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <h1>
                                <?php
                                $Site = '';
                                if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
                                    $Site = ' ALL SITES';
                                    if ($_GET['site_id']) {
                                        $Site = ' ' . ' ' . $override->getNews('site', 'status', 1, 'id', $_GET['site_id'])[0]['name'];
                                    }
                                } else {
                                    $Site = ' ' . ' ' . $override->getNews('site', 'status', 1, 'id', $user->data()->site_id)[0]['name'];
                                }
                                ?>
                            </h1>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-center">
                                <div class="row">
                                    <form id="validation" enctype="multipart/form-data" method="post"
                                        autocomplete="off">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <select class="form-control" name="site_id" style="width: 100%;"
                                                            autocomplete="off">
                                                            <option value="">Select Site</option>
                                                            <!-- <option value="3">All</option> -->
                                                            <?php foreach ($override->get('site', 'status', 1) as $site) { ?>
                                                                <option value="<?= $site['id'] ?>"><?= $site['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <input type="submit" name="search_by_site1"
                                                            value="Search by Site" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </ol>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">ChartJS</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $propotion1 ?>%</h3>
                                    <p> people with T1D with an A1C checked within the last 6 months</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    Launch Extra Large Modal
                                </button> -->
                            </div>
                        </div>
                        <!-- /.col (LEFT) -->

                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>28%</h3>
                                    <p> Proportion of patients with T1D with HbA1C < 8 on last measure</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    Launch Extra Large Modal
                                </button> -->
                            </div>
                        </div>
                        <!-- /.col (LEFT) -->


                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>50%</h3>
                                    <p> Proportion of patients with T1D who have had DKA in the past 12 months</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl">
                                    Launch Extra Large Modal
                                </button> -->
                            </div>
                        </div>
                        <!-- /.col (LEFT) -->

                        <div class="modal fade" id="modal-xl">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Extra Large Modal</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- <p>One fine body&hellip;</p> -->
                                        <!-- PIE CHART -->
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="card card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Pie Chart</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                data-card-widget="collapse">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-tool"
                                                                data-card-widget="remove">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="hba1c_test"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            $data = $override->getWithLimit1('diabetic', 'diagnosis', 1, 'status', 1, $page, $numRec);

                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">people with T1D with an A1C checked
                                                            within the last 6 months</h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 10px">#</th>
                                                                    <th>Study ID</th>
                                                                    <th>Age</th>
                                                                    <th>Sex</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($data as $row) {
                                                                    $clients = $override->getNews('clients', 'status', 1, 'id', $row['patient_id'])[0];
                                                                    $sex = $override->getNews('sex', 'id', $clients['gender'], 'status', 1)[0];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $i ?>.</td>
                                                                        <td><?= $row['study_id'] ?></td>
                                                                        <td><?= $clients['age'] ?></td>
                                                                        <td><?= $sex['name'] ?></td>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <!-- <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <li class="page-item"><a class="page-link"
                                                                    href="#">&laquo;</a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link" href="#">1</a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="#">&raquo;</a>
                                                            </li>
                                                        </ul>
                                                    </div> -->

                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <li class="page-item">
                                                                <a class="page-link" href="reports3.php?page=<?php if (($_GET['page'] - 1) > 0) {
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
                                                                    } ?>" href="reports3.php?page=<?= $i ?>"><?= $i ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="page-item">
                                                                <a class="page-link" href="reports3.php?page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                                    echo $_GET['page'] + 1;
                                                                } else {
                                                                    echo $i - 1;
                                                                } ?>">&raquo;
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">PENPLUS ENROLLMENT PROGRESS AS OF <?= date('Y-m-d') ?>(
                                        DIABETES )</h3>
                                    <div class="card-tools">
                                        <a class="btn btn-default border btn-flat btn-sm" href="index1.php"><i
                                                class="fa fa-angle-left"></i> Back</a>
                                        <a class="btn btn-flat btn-sm btn-primary" href="reports3_1.php"><span
                                                class="fas fa-download text-default">&nbsp;&nbsp;</span>Download
                                            Report</a>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">#</th>
                                                <th rowspan="2">SITE</th>
                                                <th rowspan="2">ENROLLED</th>
                                                <th colspan="5" class="content-header text-center">Diabtes Type</th>
                                            </tr>
                                            <tr>
                                                <th>Type 1 DM</th>
                                                <th>Type 2 DM</th>
                                                <th>Gestational DM</th>
                                                <th>DM Not yet specified </th>
                                                <th>Other </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($site_data as $row) {
                                                $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $row['id']);
                                                $enrolled_Total = $override->countData('clients', 'status', 1, 'enrolled', 1);
                                                $diabetes1 = $override->countData2('diabetic', 'status', 1, 'diagnosis', 1, 'site_id', $row['id']);
                                                $diabetes_Total1 = $override->countData('diabetic', 'status', 1, 'diagnosis', 1);
                                                $diabetes2 = $override->countData2('diabetic', 'status', 1, 'diagnosis', 2, 'site_id', $row['id']);
                                                $diabetes_Total2 = $override->countData('diabetic', 'status', 1, 'diagnosis', 2);
                                                $diabetes3 = $override->countData2('diabetic', 'status', 1, 'diagnosis', 3, 'site_id', $row['id']);
                                                $diabetes_Total3 = $override->countData('diabetic', 'status', 1, 'diagnosis', 3);
                                                $diabetes4 = $override->countData2('diabetic', 'status', 1, 'diagnosis', 4, 'site_id', $row['id']);
                                                $diabetes_Total4 = $override->countData('diabetic', 'status', 1, 'diagnosis', 4);
                                                $diabetes5 = $override->countData2('diabetic', 'status', 1, 'diagnosis', 96, 'site_id', $row['id']);
                                                $diabetes_Total5 = $override->countData('diabetic', 'status', 1, 'diagnosis', 96);
                                                $diabetes_Total = $override->countData('clients', 'status', 1, 'diabetes', 1);
                                                $end_study = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $row['id']);
                                                $end_study_Total = $override->countData('clients', 'status', 1, 'end_study', 1);
                                                ?>
                                                <tr>
                                                    <td><?= $i ?>.</td>
                                                    <td><?= $row['name'] ?></td>
                                                    <td><?= $enrolled ?></td>
                                                    <td><?= $diabetes1 ?></td>
                                                    <td><?= $diabetes2 ?></td>
                                                    <td><?= $diabetes3 ?></td>
                                                    <td><?= $diabetes4 ?></td>
                                                    <td><?= $diabetes5 ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            } ?>
                                            <tr>
                                                <td align="right" colspan="2"><b>Total</b></td>
                                                <td align="right"><b><?= $enrolled_Total ?></b></td>
                                                <td align="right"><b><?= $diabetes_Total1 ?></b></td>
                                                <td align="right"><b><?= $diabetes_Total2 ?></b></td>
                                                <td align="right"><b><?= $diabetes_Total3 ?></b></td>
                                                <td align="right"><b><?= $diabetes_Total4 ?></b></td>
                                                <td align="right"><b><?= $diabetes_Total5 ?></b></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?php include 'footerBar.php'; ?>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- Page specific script -->
    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            // var donutChartCanvas = $('#donutChart').get(0).getContext('2d')

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var hba1c_test = $('#hba1c_test').get(0).getContext('2d')
            var hba1c_test_Data = {
                labels: [
                    'HBA1C > 8',
                    'HBA1C < 8',
                ],
                datasets: [
                    {
                        data: [700, 500],
                        backgroundColor: ['#00a65a', '#f39c12'],
                    }
                ]
            }

            var hba1c_test_Options = {
                maintainAspectRatio: false,
                responsive: true,
            }

            //Create pie or douhnut chart
            new Chart(hba1c_test, {
                type: 'pie',
                data: hba1c_test_Data,
                options: hba1c_test_Options
            })
        })
    </script>
</body>

</html>