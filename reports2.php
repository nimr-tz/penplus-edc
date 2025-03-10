<?php
require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();


$numRec = 3;

if ($user->isLoggedIn()) {
    try {
        $indicator = 1;
        if (Input::exists('post')) {
            if (Input::get('search_by_site1')) {
                $validate = new validate();
                $validate = $validate->check($_POST, array(
                    'site_id' => array(
                        'required' => true,
                    ),
                ));
                if ($validate->passed()) {
                    $url = 'reports2.php?&site_id=' . Input::get('site_id');
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
                    $url = 'reports2.php?&site_id=' . Input::get('site_id');
                    Redirect::to($url);
                    $pageError = $validate->errors();
                }
            }
        }

        // INDICATOR 1;
        if (Input::get('site_id')) {
            $Numerator_RHD_ON_PENADUR = intval($override->Numerator_Active_RHD_PENADUR_by_site(Input::get('site_id')));
            $Denominator_Active_RHD = intval($override->Denominator_Active_RHD_PENADUR_by_Site('cardiac', 'status', 1, 'heumatic', 1, 'site_id', Input::get('site_id')));
        } else {
            $Numerator_RHD_ON_PENADUR = intval($override->Numerator_Active_RHD_PENADUR());
            $Denominator_Active_RHD = intval($override->Denominator_Active_RHD_PENADUR('cardiac', 'status', 1, 'heumatic', 1));
        }
        $propotion_RHD_secondary_prophylaxis = intval(intval($Numerator_RHD_ON_PENADUR) / intval($Denominator_Active_RHD) * 100);
        // Prepare the data in PHP
        $data_propotion_RHD_secondary_prophylaxis = [
            'labels' => ['RHD on secondary prophylaxis', 'RHD not on secondary prophylaxis'],
            'datasets' => [
                [
                    'data' => [$propotion_RHD_secondary_prophylaxis, 100 - $propotion_RHD_secondary_prophylaxis], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];

        // Convert the data to JSON format
        $json_propotion_RHD_secondary_prophylaxis = json_encode($data_propotion_RHD_secondary_prophylaxis);



        // INDICATOR 2
        if (Input::get('site_id')) {
            $Numerator_active_warfarin_INR = intval($override->Numerator_active_cardiac_warfarin_INR_by_Site(Input::get('site_id')));
            $Denominator_active_cardiac_warfarin = intval($override->Denominator_active_cardiac_warfarin_by_Site(Input::get('site_id')));
        } else {
            $Numerator_active_warfarin_INR = intval($override->Numerator_active_cardiac_warfarin_INR());
            $Denominator_active_cardiac_warfarin = intval($override->Denominator_active_cardiac_warfarin());
        }

        $proportion_patients_warfarin_with_INR_last_3_months = intval(intval($$Numerator_active_warfarin_INR) / intval($Denominator_active_cardiac_warfarin) * 100);
        $data_proportion_patients_warfarin_with_INR_last_3months = [
            'labels' => ['Patients on Warfarin with INR checked', 'Patients on Warfarin with INR not checked'],
            'datasets' => [
                [
                    'data' => [$proportion_patients_warfarin_with_INR_last_3_months, 100 - $proportion_patients_warfarin_with_INR_last_3_months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];

        // Convert the data to JSON format
        $json_proportion_patients_warfarin_with_INR_last_3_months = json_encode($data_proportion_patients_warfarin_with_INR_last_3months);




        //Indicator 3-% of patients with suspected congenital or RHD referred for surgical evaluation
        if (Input::get('site_id')) {
            $cardiac_congenital_RHD_surgery_num = intval($override->Numerator_cardiac_congenital_RHD_surgery_num_by_site(Input::get('site_id')));
            $cardiac_RHD_congenital_den = intval($override->Denominator_cardiac_RHD_congenital_by_Site(Input::get('site_id')));
        } else {
            $cardiac_congenital_RHD_surgery_num = intval($override->Numerator_cardiac_congenital_RHD_surgery_num());
            $cardiac_RHD_congenital_den = intval($override->Denominator_cardiac_RHD_congenital());

        }

        $proprtion_congenital_RHD_surgical = intval(intval($cardiac_congenital_RHD_surgery_num) / intval($cardiac_RHD_congenital_den) * 100);
        $data_proportion_congenital_RHD_surgical_evaluation = [
            'labels' => ['% of congenital or RHD for surgical', '% of congenital or RHD not  surgical'],
            'datasets' => [
                [
                    'data' => [$proprtion_congenital_RHD_surgical, 100 - $proprtion_congenital_RHD_surgical], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]

        ];

        // Convert the data to JSON format
        $json_proportion_congenital_RHD_surgical_evaluation = json_encode($data_proportion_congenital_RHD_surgical_evaluation);


        //NYHA
        if (Input::get('site_id')) {

            $Active_cardiac_den = intval($override->Denominator_Active_cardiac_NYHA_By_Site('c.site_id', Input::get('site_id')));

            //Indicator 4-NYHA I
            $NYHA_1_num = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), 1));
            $proportion_marked_limitation_1 = intval(intval($NYHA_1_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA II
            $NYHA_2_num = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), 2));
            $proportion_marked_limitation_2 = intval(intval($NYHA_2_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA III
            $NYHA_3_num = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), 3));
            $proportion_marked_limitation_3 = intval(intval($NYHA_3_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA IV
            $NYHA_4_num = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), 4));
            $proportion_marked_limitation_4 = intval(intval($NYHA_4_num) / intval($Active_cardiac_den) * 100);

            //Indicator 5-NYHA V
            $NYHA_5_num = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), 5));
            $proportion_marked_limitation_5 = intval(intval($NYHA_5_num) / intval($Active_cardiac_den) * 100);
        } else {
            $Active_cardiac_den = intval($override->Denominator_Active_cardiac_NYHA());

            $NYHA_1_num = intval($override->Active_NYHA_num(1));
            $proportion_marked_limitation_1 = intval(intval($NYHA_1_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA II
            $NYHA_2_num = intval($override->Active_NYHA_num(2));
            $proportion_marked_limitation_2 = intval(intval($NYHA_2_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA III
            $NYHA_3_num = intval($override->Active_NYHA_num(3));
            $proportion_marked_limitation_3 = intval(intval($NYHA_3_num) / intval($Active_cardiac_den) * 100);

            //Indicator 4-NYHA IV
            $NYHA_4_num = intval($override->Active_NYHA_num(4));
            $proportion_marked_limitation_4 = intval(intval($NYHA_4_num) / intval($Active_cardiac_den) * 100);

            //Indicator 5-NYHA V
            $NYHA_5_num = intval($override->Active_NYHA_num(5));
            $proportion_marked_limitation_5 = intval(intval($NYHA_5_num) / intval($Active_cardiac_den) * 100);
        }

        $data_proportion_NYHA = [
            'labels' => ['% of NYHA I', '% of NYHA II', '% of NYHA III', '% of NYHA IV', '% of Not Determined'],
            'datasets' => [
                [
                    'data' => [$proportion_marked_limitation_1, $proportion_marked_limitation_2, $proportion_marked_limitation_3, $proportion_marked_limitation_4, $proportion_marked_limitation_5], // Calculate the second value dynamically
                    'backgroundColor' => ['#9b59b6', '#33ff57', '#3357ff', '#f1c40f', '#ff5733'],
                ]
            ]

        ];

        // Convert the data to JSON format
        $json_proportion_NYHA = json_encode($data_proportion_NYHA);

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
                        <div class="col-md-3">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $propotion_RHD_secondary_prophylaxis ?>%</h3>
                                    <p>Proportion of patients with RHD who are on secondary prophylaxis</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl1">
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
                                    <h3><?= $proportion_patients_warfarin_with_INR_last_3_months ?>%</h3>
                                    <p> % of patients on warfarin with an INR checked in the last 3 months</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl2">
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
                                    <h3><?= $proprtion_congenital_RHD_surgical ?>%</h3>
                                    <p> % of patients with suspected congential or RHD referred for surgical evaluation
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl3">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <!-- <button type="button" class="btn btn-default" data-toggle="modal"
                                                            data-target="#modal-xl">
                                                            Launch Extra Large Modal
                                                        </button> -->
                            </div>
                        </div>


                        <!-- <div class="col-md-3">
                            <div class="card card-widget widget-user">
                                <div class="widget-user-header bg-info">
                                    <h3 class="widget-user-username">Proportion of patients with none or mild limitation
                                        in activity (NYHA I and II and III and IV) at last visit</h3> -->
                        <!-- <h5 class="widget-user-desc">Founder & CEO</h5> -->
                        <!-- <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                        data-target="#modal-xl4">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div> -->
                        <!-- <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $proportion_marked_limitation_1 ?>%
                                                </h5>
                                                <span class="description-text">NYHA I</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $proportion_marked_limitation_1 ?>%
                                                </h5>
                                                <span class="description-text">NYHA I</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $proportion_marked_limitation_1 ?>%
                                                </h5>
                                                <span class="description-text">NYHA III</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $proportion_marked_limitation_1 ?>%
                                                </h5>
                                                <span class="description-text">NYHA IV</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                        <!-- </div>
                        </div> -->
                        <!-- /.col -->

                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <div style="display: inline-block; margin-right: 10px;">
                                        <p>NYHA I</p>
                                        <h3><?= $proportion_marked_limitation_1 ?>%</h3>
                                    </div>

                                    <div style="display: inline-block; margin-right: 10px;">
                                        <p>NYHA II</p>
                                        <h3><?= $proportion_marked_limitation_2 ?>%</h3>
                                    </div>

                                    <div style="display: inline-block; margin-right: 10px;">
                                        <p>NYHA III</p>
                                        <h3><?= $proportion_marked_limitation_3 ?>%</h3>
                                    </div>

                                    <div style="display: inline-block; margin-right: 10px;">
                                        <p>NYHA IV</p>
                                        <h3><?= $proportion_marked_limitation_4 ?>%</h3>
                                    </div>
                                    <!-- <div style="display: inline-block; margin-right: 10px;">
                                        <p>Not Determined</p>
                                        <h3><?= $proportion_marked_limitation_5 ?>%</h3>
                                    </div> -->
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl4">
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
                                        <h4 class="modal-title">Proportion of patients with RHD who are on secondary
                                            prophylaxis</h4>
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
                                                        <canvas id="prophylaxis"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_Active_RHD_PENADUR_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_Active_RHD_PENADUR());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_Active_RHD_PENADUR_by_site_Data_Rows(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_Active_RHD_PENADUR_data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="dk_12" id="dk_12_id"
                                                                    style="width: 100%;" autocomplete="off">
                                                                    <option value="1">
                                                                        Proportion of patients with RHD who are on
                                                                        secondary prophylaxis</option>
                                                                    <option value="2">
                                                                        Proportion of patients with RHD who are not on
                                                                        secondary prophylaxis
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">patients with RHD who are on secodary
                                                            prophylaxis
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
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                                        <h4 class="modal-title">% of patients on warfarin with an INR checked in the
                                            last 3 months</h4>
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
                                                        <canvas id="warfarin_inr"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_active_cardiac_warfarin_INR_by_Site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_active_cardiac_warfarin_INR());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_active_cardiac_warfarin_INR_by_Site_data_Rows(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_active_cardiac_warfarin_INR_data_Rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="dk_12" id="dk_12_id"
                                                                    style="width: 100%;" autocomplete="off">
                                                                    <option value="1">
                                                                        patients on warfarin with an INR checked in the
                                                                        last 3 months
                                                                    <option value="2">
                                                                        patients on warfarin with an INR Not checked in
                                                                        the last 3 months
                                                                    </option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients on warfarin with an INR checked
                                                            in the last 3 months
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
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                                        <h4 class="modal-title">% of patients with suspected congential or RHD referred
                                            for surgical evaluation</h4>
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
                                                        <canvas id="congenital_RHD_surgical_evaluation"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php

                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Numerator_cardiac_congenital_RHD_surgery_num_by_site(Input::get('site_id')));
                                            } else {
                                                $pagNum = intval($override->Numerator_cardiac_congenital_RHD_surgery_num());
                                            }
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Numerator_cardiac_congenital_RHD_surgery_num_by_site_data_rows(Input::get('site_id'), $page, $numRec);
                                            } else {
                                                $data = $override->Numerator_cardiac_congenital_RHD_surgery_num_data_rows($page, $numRec);
                                            }
                                            ?>
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-12">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="dk_12" id="dk_12_id"
                                                                    style="width: 100%;" autocomplete="off">
                                                                    <option value="1">
                                                                        Patients with suspected congential or RHD
                                                                        referred for surgical evaluation
                                                                    <option value="2">
                                                                        Patients with suspected congential or RHD
                                                                        not referred for surgical evaluation
                                                                    </option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Patients with suspected congential or RHD
                                                            referred for surgical evaluation
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
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
                                        <h4 class="modal-title">Proportion of patients with none or mild limitation in
                                            activity (NYHA I and II and III and IV) at last visit</h4>
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
                                                        <canvas id="nyha"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            if (Input::get('site_id')) {
                                                $pagNum = intval($override->Active_NYHA_num_by_Site(Input::get('site_id'), $indicator));
                                            } else {
                                                $pagNum = intval($override->Active_NYHA_num($indicator));
                                            }

                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }

                                            if (Input::get('site_id')) {
                                                $data = $override->Active_NYHA_num_Data_Rows_By_Site(Input::get('site_id'), $page, $numRec, $indicator);
                                            } else {
                                                $data = $override->Active_NYHA_num_Data_Rows($page, $numRec, $indicator);
                                            }
                                            // print_r($indicator);
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
                                                                            Proportion of patients with none or mild
                                                                            limitation in activity (NYHA I) at last
                                                                            visit
                                                                        </option>
                                                                        <option value="2">
                                                                            Proportion of patients witho none or mild
                                                                            limitation in activity (NYHA II) at last
                                                                            visit
                                                                        </option>
                                                                        <option value="3">
                                                                            Proportion of patients with none or mild
                                                                            limitation in activity (NYHA III) at last
                                                                            visit
                                                                        </option>
                                                                        <option value="4">
                                                                            Proportion of patients with none or mild
                                                                            limitation in activity (NYHA IV) at last
                                                                            visit
                                                                        </option>
                                                                        <option value="5">
                                                                            Not Determined
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
                                                        <h3 class="card-title"> Proportion of patients with none or mild
                                                            limitation in activity NYHA I at
                                                            last visit </option>

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
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports2.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
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
            propotion_RHD_secondary_prophylaxis_Data = <?php echo $json_propotion_RHD_secondary_prophylaxis; ?>

            // Get the canvas element
            var RHD_secondary_prophylaxis = $('#prophylaxis').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var RHD_secondary_prophylaxis_Options = {
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
            new Chart(RHD_secondary_prophylaxis, {
                type: 'pie', // Pie chart type
                data: propotion_RHD_secondary_prophylaxis_Data,
                options: RHD_secondary_prophylaxis_Options,
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
            patients_warfarin_with_INR_last_3_months_Data = <?php echo $json_proportion_patients_warfarin_with_INR_last_3_months; ?>

            // Get the canvas element
            var warfarin_with_INR_last_3_months = $('#warfarin_inr').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var patients_warfarin_with_INR_last_3_months_Options = {
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
            new Chart(warfarin_with_INR_last_3_months, {
                type: 'pie', // Pie chart type
                data: patients_warfarin_with_INR_last_3_months_Data,
                options: patients_warfarin_with_INR_last_3_months_Options,
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
            congenital_RHD_surgical_evaluation_Data = <?php echo $json_proportion_congenital_RHD_surgical_evaluation; ?>

            // Get the canvas element
            var congenital_RHD_surgical_evaluation = $('#congenital_RHD_surgical_evaluation').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var congenital_RHD_surgical_evaluation_Options = {
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
            new Chart(congenital_RHD_surgical_evaluation, {
                type: 'pie', // Pie chart type
                data: congenital_RHD_surgical_evaluation_Data,
                options: congenital_RHD_surgical_evaluation_Options,
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
            NYHA_Data = <?php echo $json_proportion_NYHA; ?>

            // Get the canvas element
            var nyha = $('#nyha').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var NYHA_Options = {
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
            new Chart(nyha, {
                type: 'pie', // Pie chart type
                data: NYHA_Data,
                options: NYHA_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>

</body>

</html>