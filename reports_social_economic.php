<?php
require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();


$numRec = 3;

if ($user->isLoggedIn()) {
    try {
        if (Input::exists('post')) {

            if (Input::get('search_by_site1')) {
                $validate = new validate();
                $validate = $validate->check($_POST, array(
                    'site_id' => array(
                        'required' => true,
                    ),
                ));
                if ($validate->passed()) {

                    $url = 'reports4.php?site_id=' . Input::get('site_id');
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



        // INDICATOR 1
        if (Input::get('site_id')) {
            $Numerator_scd_hydroxyurea = intval($override->Numerator_ncd_Limited_By_Site(Input::get('site_id')));
            $Denominator_Active_scd = intval($override->Denominator_ncd_Limited_By_Site(Input::get('site_id')));
        } else {
            $Numerator_scd_hydroxyurea = intval($override->Numerator_ncd_Limited());
            $Denominator_Active_scd = intval($override->Denominator_ncd_Limited());

        }
        $propotion_SCD_hydroxyurea = intval(intval($Numerator_scd_hydroxyurea) / intval($Denominator_Active_scd) * 100);
        // Prepare the data in PHP
        $data_propotion_SCD_hydroxyurea = [
            'labels' => ['SCD on hydroxyurea', 'SCD not on hydroxyurea'],
            'datasets' => [
                [
                    'data' => [$propotion_SCD_hydroxyurea, 100 - $$propotion_SCD_hydroxyurea], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];

        // Convert the data to JSON format
        $json_propotion_SCD_hydroxyurea = json_encode($data_propotion_SCD_hydroxyurea);



        // INDICATOR 2
        if (Input::get('site_id')) {
            $Numerator_SCD_5_penv = intval($override->Numerator_scd_5_penv_by_site(Input::get('site_id')));
            $Denominator__SCD_Under_5 = intval($override->Denominator_SCD_5_by_site(Input::get('site_id')));
        } else {
            $Numerator_SCD_5_penv = intval($override->Numerator_scd_5_penv());
            $Denominator__SCD_Under_5 = intval($override->Denominator_SCD_5());
        }
        $propotion_SCD_under_5_penv = intval(intval($Numerator_SCD_5_penv) / intval($Denominator__SCD_Under_5) * 100);
        // Prepare the data in PHP
        $data_propotion_SCD_under_5_penv = [
            'labels' => ['Proportion of patients with SCD<5 years on antibiotic prophylaxis', 'Proportion of patients with SCD<5 years not on antibiotic prophylaxis'],
            'datasets' => [
                [
                    'data' => [$propotion_SCD_under_5_penv, 100 - $propotion_SCD_under_5_penv], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_propotion_SCD_under_5_penv = json_encode($data_propotion_SCD_under_5_penv);


        // INDICATOR 3
        if (Input::get('site_id')) {
            $Numerator_scd_folic = intval($override->Numerator_scd_folic_by_site(Input::get('site_id')));
            $Denominator_scd = intval($override->Denominator_scd_by_site(Input::get('site_id')));
        } else {
            $Numerator_scd_folic = intval($override->Numerator_scd_folic());
            $Denominator_scd = intval($override->Denominator_scd());
        }
        $proportion_SCD_on_folic_acid = intval(intval($Numerator_scd_folic) / intval($Denominator_scd) * 100);
        // Prepare the data in PHP
        $data_proportion_SCD_on_folic_acid = [
            'labels' => ["Proportion of patients with SCD who are on folic acid", "Proportion of patients with SCD who are not on folic acid"],
            'datasets' => [
                [
                    'data' => [$proportion_SCD_on_folic_acid, 100 - $proportion_SCD_on_folic_acid], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_propotion_SCD_on_folic_acid = json_encode($data_proportion_SCD_on_folic_acid);


        // INDICATOR 4
        if (Input::get('site_id')) {
            $Numerator_scd_transfusion = intval($override->Numerator_scd_transfusion_by_site(Input::get('site_id')));
            $Denominator_scd_patient = intval($override->Denominator_scd_patient_by_Site(Input::get('site_id')));
        } else {
            $Numerator_scd_transfusion = intval($override->Numerator_scd_transfusion());
            $Denominator_scd_patient = intval($override->Denominator_scd_patient());
        }
        $proportion_SCD_transfusion_12_months = intval(intval($Numerator_scd_transfusion) / intval($Denominator_scd_patient) * 100);
        // Prepare the data in PHP
        $data_proportion_SCD_transfusion_12_months = [
            'labels' => ["% of patients with SCD needing transfusion within the last 12 months", "% of patients with SCD not needing transfusion within the last 12 months"],
            'datasets' => [
                [
                    'data' => [$proportion_SCD_transfusion_12_months, 100 - $proportion_SCD_transfusion_12_months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format       
        $json_proportion_SCD_transfusion_12_months = json_encode($data_proportion_SCD_transfusion_12_months);

        // INDICATOR 5
        if (Input::get('site_id')) {
            $Numerator_scd_hospitalised_12 = intval($override->Numerator_scd_hospitalised_12_by_site(Input::get('site_id')));
            $Denominator_scd_active = intval($override->Denominator_scd_active_by_Site(Input::get('site_id')));
        } else {
            $Numerator_scd_hospitalised_12 = intval($override->Numerator_scd_hospitalised_12());
            $Denominator_scd_active = intval($override->Denominator_scd_active());
        }
        $proportion_patients_SCD_hospitalization_12_months = intval(intval($Numerator_scd_hospitalised_12) / intval($Denominator_scd_active) * 100);
        // Prepare the data in PHP
        $data_proportion_patients_SCD_hospitalization_12_months = [
            'labels' => ["Proportion of patients with SCD who had a hospitalization within the last 12 months", "Proportion of patients with SCD who had no hospitalization within the last 12 months"],
            'datasets' => [
                [
                    'data' => [$proportion_patients_SCD_hospitalization_12_months, 100 - $proportion_patients_SCD_hospitalization_12_months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_proportion_patients_SCD_hospitalization_12_months = json_encode($data_proportion_patients_SCD_hospitalization_12_months);


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
                                                            <option value="">ALL SITES</option>
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
                                    </form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!-- card tools -->
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-info btn-sm daterange" title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-info btn-sm" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button> -->
                                    </div>
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
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?= $propotion_SCD_hydroxyurea ?>%</h3>
                                    <p>Proportion of patients for whom NCD has limited school attendance ever</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl1" data-value="1">
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
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3><?= $propotion_SCD_under_5_penv ?>%</h3>
                                    <p> Average days of missed school in the last month</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl2" data-value="2">
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
                                    <h3><?= $proportion_SCD_on_folic_acid ?>%</h3>
                                    <p>Patients facing food inscecurity</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl3" data-value="3">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                data-target="#modal-xl">
                                                                                Launch Extra Large Modal
                                                                            </button> -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $proportion_SCD_transfusion_12_months ?>%</h3>
                                    <p> Average distance to clinic in kms</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl4" data-value="4">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                data-target="#modal-xl">
                                                                                Launch Extra Large Modal
                                                                            </button> -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $proportion_patients_SCD_hospitalization_12_months ?>%</h3>
                                    <p>Average distance to clinic in minutes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl5" data-value="5">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                        data-target="#modal-xl">
                                                                                                        Launch Extra Large Modal
                                                                                                    </button> -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- small card -->
                            <div class="small-box bg-orange">
                                <div class="inner">
                                    <h3><?= $proportion_patients_SCD_hospitalization_12_months ?>%</h3>
                                    <p>Average cost of transportation</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl6" data-value="6">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                                                data-target="#modal-xl">
                                                                                                                                Launch Extra Large Modal
                                                                                                                            </button> -->
                            </div>
                        </div>

                        <div class="col-md-12">
                            <!-- small card -->
                            <div class="small-box bg-indigo">
                                <div class="inner">
                                    <h3><?= $proportion_patients_SCD_hospitalization_12_months ?>%</h3>
                                    <p>Proportion of patients who are provided with social support in a quarter</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl7" data-value="7">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                                                                                                data-target="#modal-xl">
                                                                                                                                Launch Extra Large Modal
                                                                                                                            </button> -->
                            </div>
                        </div>

                        <div class="modal fade" id="modal-xl1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD on hydroxyurea at last
                                            visit</h4>
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
                                                        <canvas id="hydroxyurea"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_hyroxyurea_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_hyroxyurea());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_hyroxyurea_data_rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_hyroxyurea_data_rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <form method="post">
                                                        <div class="col-sm-12">
                                                            <div class="row-form clearfix">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="indicator"
                                                                        id="indicator" style="width: 100%;"
                                                                        autocomplete="off">
                                                                        <option value="1">
                                                                            Proportion of patients with SCD on
                                                                            hydroxyurea at last visit
                                                                        </option>
                                                                        <option value="2">
                                                                            Proportion of patients with SCD not on
                                                                            hydroxyurea at last visit
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="row-form clearfix">
                                                                <div class="form-group">
                                                                    <input type="submit" name="search_by_indicator"
                                                                        value="Filter" class="btn btn-primary">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="card-header">
                                                        <h3 class="card-title"> Patients with SCD on
                                                            hydroxyurea at last visit
                                                        </h3>
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
                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                        <div class="modal fade" id="modal-xl2">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD less than 5 years old
                                            who are on antibiotic prophylaxis</h4>
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
                                                        <canvas id="antibiotic_prophylaxis"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_5_penv_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_5_penv());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_5_penv_data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_5_penv_data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Proportion of patients with SCD less than 5
                                                                        years old who are on antibiotic prophylaxis
                                                                    <option value="2">
                                                                        Proportion of patients with SCD less than 5
                                                                        years old who are not on antibiotic prophylaxis
                                                                    </option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD less than 5 years old
                                                            who are on antibiotic prophylaxis
                                                        </h3>
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
                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                        <div class="modal fade" id="modal-xl3">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD who are on folic acid
                                        </h4>
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
                                                        <canvas id="folic_acid"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_folic_by_Site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_folic());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_folic_data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_folic_data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with SCD who are on folic acid
                                                                    <option value="2">
                                                                        Patients with SCD who are not on folic acid
                                                                    </option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD who are on folic acid
                                                        </h3>
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

                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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

                        <div class="modal fade" id="modal-xl4">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">% of patients with SCD needing transfusion within the
                                            last 12 months</h4>
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
                                                        <canvas id="transfusion"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_transfusion_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_transfusion());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_transfusion_data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_transfusion_data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with SCD needing transfusion
                                                                        within the last 12 months
                                                                    <option value="2">
                                                                        Patients with SCD Not needing transfusion
                                                                        within the last 12 months </option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD needing transfusion
                                                            within the last 12 months
                                                        </h3>
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

                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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

                        <div class="modal fade" id="modal-xl5">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD who had a
                                            hospitalization within the last 12 months</h4>
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
                                                        <canvas id="hospitalization"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with SCD who had a hospitalization
                                                                        within the last 12 months
                                                                    <option value="2">
                                                                        Patients with SCD who hadn't a hospitalization
                                                                        within the last 12 month</option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD who had a
                                                            hospitalization
                                                            within the last 12 months
                                                        </h3>
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

                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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

                        <div class="modal fade" id="modal-xl6">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD who had a
                                            hospitalization within the last 12 months</h4>
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
                                                        <canvas id="hospitalization"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with SCD who had a hospitalization
                                                                        within the last 12 months
                                                                    <option value="2">
                                                                        Patients with SCD who hadn't a hospitalization
                                                                        within the last 12 month</option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD who had a
                                                            hospitalization
                                                            within the last 12 months
                                                        </h3>
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

                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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


                        <div class="modal fade" id="modal-xl7">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Proportion of patients with SCD who had a
                                            hospitalization within the last 12 months</h4>
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
                                                        <canvas id="hospitalization"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_scd_hospitalised_12());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows_by_Site(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_scd_hospitalised_12_Data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="indicator"
                                                                    id="indicator" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with SCD who had a hospitalization
                                                                        within the last 12 months
                                                                    <option value="2">
                                                                        Patients with SCD who hadn't a hospitalization
                                                                        within the last 12 month</option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with SCD who had a
                                                            hospitalization
                                                            within the last 12 months
                                                        </h3>
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

                                                    <?php
                                                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $currentSite = $_GET['site_id'];
                                                    // $pages = 10; // Total number of pages (replace with your actual calculation)
                                                    $range = 2; // Number of pages to show before and after the current page
                                                    
                                                    // Calculate start and end for the visible range
                                                    $start = max(1, $currentPage - $range);
                                                    $end = min($pages, $currentPage + $range);
                                                    ?>
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <!-- Previous Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=1">1</a>
                                                                </li>
                                                                <?php if ($start > 2): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- Visible Page Links -->
                                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                                <li
                                                                    class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>

                                                            <!-- Last Page (if outside the range) -->
                                                            <?php if ($end < $pages): ?>
                                                                <?php if ($end < $pages - 1): ?>
                                                                    <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports4.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                                    <!-- /.card-tools -->
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
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No.</th>
                                                <th rowspan="2">SITE</th>
                                                <th rowspan="2">ENROLLED</th>
                                                <th colspan="2"> Diabtes </th>
                                            </tr>
                                            <tr>
                                                <th>Sickle Cell Disease</th>
                                                <th>Other Hemoglobinopathy</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $i = 1;
                                            foreach ($site_data as $row) {
                                                $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $row['id']);
                                                $enrolled_Total = $override->countData('clients', 'status', 1, 'enrolled', 1);
                                                $sickle_cell1 = $override->countData2('sickle_cell', 'status', 1, 'diagnosis', 1, 'site_id', $row['id']);
                                                $sickle_cell_Total1 = $override->countData('sickle_cell', 'status', 1, 'diagnosis', 1);
                                                $sickle_cell2 = $override->countData2('sickle_cell', 'status', 1, 'diagnosis', 96, 'site_id', $row['id']);
                                                $sickle_cell_Total2 = $override->countData('sickle_cell', 'status', 1, 'diagnosis', 96);
                                                $diabetes_Total = $override->countData('clients', 'status', 1, 'diabetes', 1);
                                                $end_study = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $row['id']);
                                                $end_study_Total = $override->countData('clients', 'status', 1, 'end_study', 1);
                                                ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= $row['name'] ?></td>
                                                    <td align="right"><?= $enrolled ?></td>
                                                    <td align="right"><?= $sickle_cell1 ?></td>
                                                    <td align="right"><?= $sickle_cell2 ?></td>
                                                </tr>


                                                <?php
                                                $i++;
                                            } ?>

                                            <tr>
                                                <td align="right" colspan="2"><b>Total</b></td>
                                                <td align="right"><b><?= $enrolled_Total ?></b></td>
                                                <td align="right"><b><?= $sickle_cell_Total1 ?></b></td>
                                                <td align="right"><b><?= $sickle_cell_Total2 ?></b></td>
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
    <script src="dist/js/pages/dashboard.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            SCD_hydroxyurea_Data = <?php echo $json_propotion_SCD_hydroxyurea; ?>

            // Get the canvas element
            var hydroxyurea = $('#hydroxyurea').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var SCD_hydroxyurea_Options = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff', // Text color for data labels
                        font: {
                            weight: 'bold',
                            size: 14 // Font size for labels
                        },
                        formatter: function (value, context) {
                            return value + '%'; // Display value inside the pie chart
                        },
                        anchor: 'center', // Position the labels in the center
                        align: 'center', // Align the labels to the center
                    }
                }
            };

            // Register the datalabels plugin globally (if not already registered)
            Chart.register(ChartDataLabels);

            // Create pie chart
            new Chart(hydroxyurea, {
                type: 'pie', // Pie chart type
                data: SCD_hydroxyurea_Data,
                options: SCD_hydroxyurea_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>


    <!-- json_proportion_patients_warfarin_with_INR_last_3_months -->
    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            antibiotic_prophylaxis_Data = <?php echo $json_propotion_SCD_under_5_penv; ?>

            // Get the canvas element
            var antibiotic_prophylaxis = $('#antibiotic_prophylaxis').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var antibiotic_prophylaxis_Options = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff', // Text color for data labels
                        font: {
                            weight: 'bold',
                            size: 14 // Font size for labels
                        },
                        formatter: function (value, context) {
                            return value + '%'; // Display value inside the pie chart
                        },
                        anchor: 'center', // Position the labels in the center
                        align: 'center', // Align the labels to the center
                    }
                }
            };

            // Register the datalabels plugin globally (if not already registered)
            Chart.register(ChartDataLabels);

            // Create pie chart
            new Chart(antibiotic_prophylaxis, {
                type: 'pie', // Pie chart type
                data: antibiotic_prophylaxis_Data,
                options: antibiotic_prophylaxis_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>

    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            folic_acid_Data = <?php echo $json_propotion_SCD_on_folic_acid; ?>

            // Get the canvas element
            var folic_acid = $('#folic_acid').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var folic_acid_Options = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff', // Text color for data labels
                        font: {
                            weight: 'bold',
                            size: 14 // Font size for labels
                        },
                        formatter: function (value, context) {
                            return value + '%'; // Display value inside the pie chart
                        },
                        anchor: 'center', // Position the labels in the center
                        align: 'center', // Align the labels to the center
                    }
                }
            };

            // Register the datalabels plugin globally (if not already registered)
            Chart.register(ChartDataLabels);

            // Create pie chart
            new Chart(folic_acid, {
                type: 'pie', // Pie chart type
                data: folic_acid_Data,
                options: folic_acid_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>


    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            transfusion_Data = <?php echo $json_proportion_SCD_transfusion_12_months; ?>

            // Get the canvas element
            var transfusion = $('#transfusion').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var transfusion_Options = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff', // Text color for data labels
                        font: {
                            weight: 'bold',
                            size: 14 // Font size for labels
                        },
                        formatter: function (value, context) {
                            return value + '%'; // Display value inside the pie chart
                        },
                        anchor: 'center', // Position the labels in the center
                        align: 'center', // Align the labels to the center
                    }
                }
            };

            // Register the datalabels plugin globally (if not already registered)
            Chart.register(ChartDataLabels);

            // Create pie chart
            new Chart(transfusion, {
                type: 'pie', // Pie chart type
                data: transfusion_Data,
                options: transfusion_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>

    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            hospitalization_Data = <?php echo $json_proportion_patients_SCD_hospitalization_12_months; ?>

            // Get the canvas element
            var hospitalization = $('#hospitalization').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var hospitalization_Options = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff', // Text color for data labels
                        font: {
                            weight: 'bold',
                            size: 14 // Font size for labels
                        },
                        formatter: function (value, context) {
                            return value + '%'; // Display value inside the pie chart
                        },
                        anchor: 'center', // Position the labels in the center
                        align: 'center', // Align the labels to the center
                    }
                }
            };

            // Register the datalabels plugin globally (if not already registered)
            Chart.register(ChartDataLabels);

            // Create pie chart
            new Chart(hospitalization, {
                type: 'pie', // Pie chart type
                data: hospitalization_Data,
                options: hospitalization_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>

    <script>
        document.addEventListener('click', function (event) {
            if (event.target.closest('.small-box-footer')) {
                const value = event.target.closest('.small-box-footer').getAttribute('data-value');
                // console.log("Value passed to modal: ", value);

                // You can now send this value via AJAX or use it in the modal
                // document.getElementById('modal-content').innerHTML = `Value: ${value}`;
            }
        });
    </script>

</body>

</html>