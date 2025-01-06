<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$users = $override->getData('user');
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

    if (Input::get('search_by_indicator')) {
        $validate = new validate();
        $validate = $validate->check($_POST, array(
            'indicator' => array(
                'required' => true,
            ),
        ));
        if ($validate->passed()) {
            $indicator = Input::get('indicator');
            $url = 'reports4.php?&site_id=' . Input::get('site_id');
            Redirect::to($url);
            $pageError = $validate->errors();
        }
    }
}


if ($user->isLoggedIn()) {
    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
        if ($_GET['site_id'] != null) {
            $screened = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id']);
            $eligible = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id']);
            $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
            $end = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id']);
        } else {
            $screened = $override->countData('clients', 'status', 1, 'screened', 1);
            $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
            $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
            $end = $override->countData('clients', 'status', 1, 'end_study', 1);
        }
    } else {
        $screened = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id);
        $eligible = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
        $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
        $end = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id);
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
    <title>Penplus Database | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
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
            <div class="content-header">
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
                <!-- Content Header (Page header) -->
                <!-- <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Widgets</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Widgets</li>
                                </ol>
                            </div>
                        </div>
                    </div> -->
                <!-- /.container-fluid -->
                <!-- </section> -->
                <!-- <hr> -->
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <h1 class="m-0"> <?= $Site; ?></h1>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Widgets</h1>
                            </div> -->
                        <!-- <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Widgets</li>
                                </ol>
                            </div> -->
                        <!-- </div> -->
                        <!-- /.col -->
                        <div class="col-sm-3">

                            <?php
                            if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
                                ?>
                                <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <div class="form-group">
                                                    <select class="form-control" name="site_id" style="width: 100%;"
                                                        autocomplete="off">
                                                        <option value="">Select Site</option>
                                                        <!-- <option value="3">All</option> -->
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
                                                    <input type="submit" name="search_by_site1" value="Search by Site"
                                                        class="btn btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"> <?= $Site; ?></li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <hr>

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12 text-center">
                            <h1>List Of Indicators</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <hr>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>3</h3>
                                    <p>Diabetics</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="reports3.php" class="small-box-footer" class="btn btn-default">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                            data-target="#modal-xl">
                                                                                                            Launch Extra Large Modal
                                                                                                        </button> -->
                            </div>
                        </div>
                        <!-- /.col (LEFT) -->

                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>4</h3>
                                    <p>Cardiac</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="reports2.php" class="small-box-footer" class="btn btn-default">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                            data-target="#modal-xl">
                                                                                                            Launch Extra Large Modal
                                                                                                        </button> -->
                            </div>
                        </div>
                        <!-- /.col (LEFT) -->

                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>5</h3>
                                    <p>Sickle Cell</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="reports4.php" class="small-box-footer" class="btn btn-default">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                            data-target="#modal-xl">
                                                                                                            Launch Extra Large Modal
                                                                                                        </button> -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>7</h3>
                                    <p>Sociodemographic</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="reports_social_economic.php" class="small-box-footer" class="btn btn-default">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                            data-target="#modal-xl">
                                                                                                            Launch Extra Large Modal
                                                                                                        </button> -->
                            </div>
                        </div>


                    </div>

                    <hr>

                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-12 text-center">
                                    <h1>Recruitments,Screening and Enrollment Progress</h1>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>
                    <hr> <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-orange">
                                <div class="inner">
                                    <h3><?= $screened ?></h3>

                                    <p>Screened</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                <a href="reports.php" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $screened ?></h3>

                                    <p>Screened</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                <a href="reports.php" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $eligible ?><sup style="font-size: 20px"></sup></h3>

                                    <p>Eligible</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=2" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                <a href="reports.php" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-6 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $enrolled ?></h3>

                                    <p>Enrolled</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=3" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                <a href="reports.php" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-6 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= $end ?></h3>

                                    <p>End of study</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=4" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                <a href="reports.php" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->


                    <hr>
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-12 text-center">
                                    <h1>Data Visualizations</h1>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>
                    <hr>
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Registration up to <?= date('Y-m-d'); ?>
                                    </h3>
                                    <!-- card tools -->
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-secondary btn-sm daterange"
                                            title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#registration_bar"
                                                    data-toggle="tab">Bar</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#registration_donat"
                                                    data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="registration_bar"
                                            style="position: relative; height: 300px;">
                                            <canvas id="registration" height="300" style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="registration_donat"
                                            style="position: relative; height: 300px;">
                                            <canvas id="registration2" height="300" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Screening up to <?= date('Y-m-d'); ?>
                                    </h3>
                                    <!-- card tools -->
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-secondary btn-sm daterange"
                                            title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#screening1" data-toggle="tab">Bar</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#screening2" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="screening1"
                                            style="position: relative; height: 300px;">
                                            <canvas id="screening" height="300" style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="screening2"
                                            style="position: relative; height: 300px;">
                                            <canvas id="screening2" height="300" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- right col -->
                        <!-- Left col -->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Eligible up to <?= date('Y-m-d'); ?>
                                    </h3>
                                    <!-- card tools -->
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-secondary btn-sm daterange"
                                            title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#eligible_bar"
                                                    data-toggle="tab">Bar</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#eligible_pie" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="eligible_bar"
                                            style="position: relative; height: 300px;">
                                            <canvas id="eligible" height="300" style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="eligible_pie"
                                            style="position: relative; height: 300px;">
                                            <canvas id="eligible2" height="300" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Enrolled up to <?= date('Y-m-d'); ?>
                                    </h3>
                                    <!-- card tools -->
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-secondary btn-sm daterange"
                                            title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#enrolled_bar"
                                                    data-toggle="tab">Bar</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#enrolled_pie" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="enrolled_bar"
                                            style="position: relative; height: 300px;">
                                            <canvas id="enrolled" height="300" style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="enrolled_pie"
                                            style="position: relative; height: 300px;">
                                            <canvas id="enrolled2" height="300" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- right col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Terminated up to <?= date('Y-m-d'); ?>
                                    </h3>
                                    <!-- card tools -->
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-secondary btn-sm daterange"
                                            title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#end_bar" data-toggle="tab">Bar</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#end_pie" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="end_bar"
                                            style="position: relative; height: 300px;">
                                            <canvas id="end" height="300" style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="end_pie"
                                            style="position: relative; height: 300px;">
                                            <canvas id="end2" height="300" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- right col -->
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


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
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>


    <!-- MY LINKS TO CHAARTS JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard.js"></script> -->
    <script src="dist/js/pages/dashboard1_1.js"></script>
    <script src="dist/js/pages/dashboard1_2.js"></script>
    <script src="dist/js/pages/dashboard1_3.js"></script>
    <script src="dist/js/pages/dashboard1_4.js"></script>
    <script src="dist/js/pages/dashboard1_5.js"></script>
    <script src="dist/js/pages/dashboard1_6.js"></script>





    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->


</body>

</html>