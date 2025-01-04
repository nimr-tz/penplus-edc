
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
        }

        // INDICATOR 1
        if (Input::get('site_id')) {
            $Numerator_scd_hydroxyurea = intval($override->scd_hyroxyurea_num_by_site(Input::get('site_id')));
            $Denominator_Active_scd= intval($override->Active_SCD_den_by_site(Input::get('site_id')));
        } 
        else {
            $Numerator_scd_hydroxyurea= intval($override->scd_hyroxyurea_num_all());
            $Denominator_Active_scd = intval($override->Active_SCD_den());

        }
        $propotion_SCD_hydroxyurea = intval(intval($Numerator_scd_hydroxyurea) / intval($Denominator_Active_scd ) * 100);
        // Prepare the data in PHP
        $data_propotion_SCD_hydroxyurea= [
            'labels' => ['SCD on hydroxyurea', 'SCD not on hydroxyurea'],
            'datasets' => [
                [
                    'data' => [ $propotion_SCD_hydroxyurea, 100 - $ $propotion_SCD_hydroxyurea], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
     
        // Convert the data to JSON format
        $json_propotion_SCD_hydroxyurea = json_encode($data_propotion_SCD_hydroxyurea);

        // INDICATOR 2
        if (Input::get('site_id')) {
            $Numerator_SCD_5_penv= intval($override->scd_5_penv_num_by_site(Input::get('site_id')));
            $Denominator__SCD_Under_5 = intval($override->SCD_5_den_by_site(Input::get('site_id')));
        } else {
            $Numerator_SCD_5_penv= intval($override->scd_5_penv_num_all());
            $Denominator__SCD_Under_5= intval($override->SCD_5_den());
        }
        $propotion_SCD_under_5_penv= intval(intval( $Numerator_SCD_5_penv) / intval($Denominator__SCD_Under_5) * 100);
        // Prepare the data in PHP
        $data_propotion_SCD_under_5_penv= [
            'labels' => ['Proportion of patients with SCD<5 years on antibiotic prophylaxis', 'Proportion of patients with SCD<5 years not on antibiotic prophylaxis'],
            'datasets' => [
                [
                    'data' => [$propotion_SCD_under_5_penv, 100 -$propotion_SCD_under_5_penv], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_propotion_SCD_under_5_penv = json_encode($data_propotion_SCD_under_5_penv);


        // INDICATOR 3
        if (Input::get('site_id')) {
            $Numerator_scd_folic= intval($override->scd_folic_num_by_site(Input::get('site_id')));
            $Denominator_scd= intval($override->scd_den_by_site(Input::get('site_id')));
        } else {
            $Numerator_scd_folic = intval($override->scd_folic_num_all());
            $Denominator_scd= intval($override->scd_den());
        }
        $proportion_SCD_on_folic_acid= intval(intval( $Numerator_scd_folic) / intval($Denominator_scd) * 100);
        // Prepare the data in PHP
        $data_proportion_SCD_on_folic_acid= [
            'labels' => ["Proportion of patients with SCD who are on folic acid", "Proportion of patients with SCD who are not on folic acid"],
            'datasets' => [
                [
                    'data' => [ $proportion_SCD_on_folic_acid, 100 -  $proportion_SCD_on_folic_acid], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_propotion_SCD_on_folic_acid = json_encode($data_propotion_SCD_under_5_penv);
        // INDICATOR 4
        if (Input::get('site_id')) {
            $Numerator_scd_transfusion= intval($override->scd_transfusion_num_by_site(Input::get('site_id')));
            $Denominator_scd_patient= intval($override->scd_patient_den_by_site(Input::get('site_id')));
        } else {
            $Numerator_scd_transfusion = intval($override->scd_transfusion_num_all());
            $Denominator_scd_patient= intval($override->scd_patient_den());
        }
        $proportion_SCD_transfusion_12_months= intval(intval($Numerator_scd_transfusion) / intval($Denominator_scd_patient) * 100);
        // Prepare the data in PHP
        $data_proportion_SCD_transfusion_12_months= [
            'labels' => ["% of patients with SCD needing transfusion within the last 12 months", "% of patients with SCD not needing transfusion within the last 12 months"],
            'datasets' => [
                [
                    'data' => [ $proportion_SCD_transfusion_12_months, 100 - $proportion_SCD_transfusion_12_months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format       
        $json_proportion_SCD_transfusion_12_months= json_encode($data_proportion_SCD_transfusion_12_months);

        // INDICATOR 5
        if (Input::get('site_id')) {
            $Numerator_scd_hospitalised_12= intval($override->scd_hospitalised_12_num_by_site(Input::get('site_id')));
            $Denominator_scd_active= intval($override->scd_active_den_by_site(Input::get('site_id')));
        } else {
            $Numerator_scd_hospitalised_12= intval($override->scd_hospitalised_12_num_all());
            $Denominator_scd_active= intval($override->scd_active_den());
        }
        $proportion_patients_SCD_hospitalization_12_months= intval(intval($Numerator_scd_hospitalised_12) / intval($Denominator_scd_active) * 100);
        // Prepare the data in PHP
        $data_proportion_patients_SCD_hospitalization_12_months= [
            'labels' => ["Proportion of patients with SCD who had a hospitalization within the last 12 months", "Proportion of patients with SCD who had no hospitalization within the last 12 months"],
            'datasets' => [
                [
                    'data' => [  $proportion_patients_SCD_hospitalization_12_months, 100 -   $proportion_patients_SCD_hospitalization_12_months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_proportion_patients_SCD_hospitalization_12_months= json_encode($data_proportion_patients_SCD_hospitalization_12_months);


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
                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $propotion_SCD_hydroxyurea ?>%</h3>
                                    <p>Proportion of patients with SCD on hydroxyurea at last visit</p>
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

                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $propotion_SCD_under_5_penv ?>%</h3>
                                    <p> Proportion of patients with SCD less than 5 years old who are on antibiotic prophylaxis</p>
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

                                 

                          <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $proportion_SCD_on_folic_acid ?>%</h3>
                                    <p> Proportion of patients with SCD who are on folic acid</p>
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
                                          <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                <h3><?=  $proportion_SCD_transfusion_12_months ?>%</h3>
                                <p> % of patients with SCD needing transfusion within the last 12 months</p>
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
                             </div>

                     <div class="row">
                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?=  $proportion_patients_SCD_hospitalization_12_months ?>%</h3>
                                    <p>Proportion of patients with SCD who had a hospitalization within the last 12 months</p>
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
                    </div>
                        
                             <!-- /.col (LEFT) -->

                       
                     </div>
                        <!-- /.col (LEFT) -->

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
                                                        <h3 class="card-title">patients with RHD who are on secodary prophylaxis</h3>
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
                                                                <a class="page-link" href="reports4.php?page=<?php if (($_GET['page'] - 1) > 0) {
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
                                                                    } ?>" href="reports4.php?page=<?= $i ?>"><?= $i ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="page-item">
                                                                <a class="page-link" href="reports4.php?page=<?php if (($_GET['page'] + 1) <= $pages) {
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
                    <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">PENPLUS RECRUITMENTS STATUS AS OF <?= date('Y-m-d') ?></h3>
                    <div class="card-tools">
                        <a class="btn btn-default border btn-flat btn-sm" href="index1.php"><i class="fa fa-angle-left"></i> Back</a>
                        <a class="btn btn-flat btn-sm btn-primary" href="reports4_1.php"><span class="fas fa-download text-default">&nbsp;&nbsp;</span>Download Report</a>
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
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- Page specific script -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> -->
    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            hba1c_test_Data = <?php echo $json_propotion_T1D_HBA1C_6_Months; ?>
            // Get the canvas element
            var hba1c_test = $('#hba1c_test').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var hba1c_test_Options = {
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
                            return value; // Display value inside the pie chart
                        }
                    }
                }
            };

            // Create pie chart
            new Chart(hba1c_test, {
                type: 'pie', // Pie chart type
                data: hba1c_test_Data,
                options: hba1c_test_Options
            });

        })
    </script>
</body>

</html>
