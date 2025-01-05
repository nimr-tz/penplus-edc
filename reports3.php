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

                    $url = 'reports3.php?&site_id=' . Input::get('site_id');
                    Redirect::to($url);
                    $pageError = $validate->errors();
                }
            }
        }

        // INDICATOR 1
        if (Input::get('site_id')) {
            $NumeratorT1D_Hba1c_6Months = intval($override->getNo_Numerator_TID_Hba1c_6Months_By_Site(Input::get('site_id')));
            $Denominator_TID = intval($override->getNo_Denominator_TID_Hba1c_6Months_By_Site('diabetic', 'diagnosis', 1, 'status', 1, 'site_id', Input::get('site_id')));
        } else {
            $NumeratorT1D_Hba1c_6Months = intval($override->getNo_Numerator_TID_Hba1c_6Months());
            $Denominator_TID = intval($override->getNo_Denominator_TID_Hba1c_6Months('diabetic', 'diagnosis', 1, 'status', 1));

        }
        $propotion_T1D_HBA1C_6_Months = intval(intval($NumeratorT1D_Hba1c_6Months) / intval($Denominator_TID) * 100);
        // Prepare the data in PHP
        $data_propotion_T1D_HBA1C_6_Months = [
            'labels' => ['T1D Hba1c Checked within last 6 months', 'T1D Hba1c Not Checked within last 6 months'],
            'datasets' => [
                [
                    'data' => [$propotion_T1D_HBA1C_6_Months, 100 - $propotion_T1D_HBA1C_6_Months], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];

        // Convert the data to JSON format
        $json_propotion_T1D_HBA1C_6_Months = json_encode($data_propotion_T1D_HBA1C_6_Months);


        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     if (isset($_POST['download_xls'])) {
        //         $ext = 'xls';
        //         $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
        //         Redirect::to($url);
        //         $pageError = $validate->errors();
        //     } else if (isset($_POST['download_xlsx'])) {
        //         print_r('HI2');
        //         $ext = 'xlsx';
        //         $url = 'downloads_reports.php?site_id=' . 1 .'&ext=' . $ext;
        //         Redirect::to($url);
        //         $pageError = $validate->errors();
        //     } else if (isset($_POST['download_csv'])) {
        //         $ext = 'csv';
        //         $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
        //         Redirect::to($url);
        //         $pageError = $validate->errors();
        //     } else if (isset($_POST['download_stata'])) {
        //         $ext = 'dta';
        //         $url = 'downloads.php?table=' . Input::get('table') . '&ext=' . $ext;
        //         Redirect::to($url);
        //         $pageError = $validate->errors();
        //     }
        // }



        // INDICATOR 2
        if (Input::get('site_id')) {
            $Numerator_T1D_HBA1C_LESS_8_LAST = intval($override->getNo_Numerator_T1D_HBA1C_LESS_8_LAST_By_Site(Input::get('site_id')));
            $Denominator__T1D_HBA1C_LESS_8_LAST_MEASURE = intval($override->getNo_Denominator__T1D_HBA1C_LESS_8_LAST_MEASURE_By_Site(Input::get('site_id')));
        } else {
            $Numerator_T1D_HBA1C_LESS_8_LAST = intval($override->getNo3_1());
            $Denominator__T1D_HBA1C_LESS_8_LAST_MEASURE = intval($override->getNo3_2());
        }
        $propotion_T1D_HBA1C_LESS_8_LAST_MEASURE = intval(intval($Numerator_T1D_HBA1C_LESS_8_LAST) / intval($Denominator__T1D_HBA1C_LESS_8_LAST_MEASURE) * 100);
        // Prepare the data in PHP
        $data_propotion_T1D_HBA1C_LESS_8_LAST_MEASURE = [
            'labels' => ['Proportion of patients with T1D with HbA1C < 8 on last measure', 'Proportion of patients with T1D with HbA1C > 8 on last measure'],
            'datasets' => [
                [
                    'data' => [$propotion_T1D_HBA1C_LESS_8_LAST_MEASURE, 100 - $propotion_T1D_HBA1C_LESS_8_LAST_MEASURE], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format
        $json_propotion_T1D_HBA1C_LESS_8_LAST_MEASURE = json_encode($data_propotion_T1D_HBA1C_LESS_8_LAST_MEASURE);




        // INDICATOR 3
        if (Input::get('site_id')) {
            $Numerator_T1D_DK_12_MONTHS = intval($override->getNo_Numerator_T1D_DK_12_MONTHS_By_Site(Input::get('site_id')));
            $Denominator_T1D_DK_12_MONTHS = intval($override->getNo_Denominator_T1D_DK_12_MONTHS_By_Site('diabetic', 'diagnosis', 1, 'status', 1, 'site_id', Input::get('site_id')));
        } else {
            $Numerator_T1D_DK_12_MONTHS = intval($override->getNo_Numerator_T1D_DK_12_MONTHS());
            $Denominator_T1D_DK_12_MONTHS = intval($override->getNo_Denominator_T1D_DK_12_MONTH('diabetic', 'diagnosis', 1, 'status', 1));
        }
        $propotion_T1D_DK_12_MONTHS = intval(intval($Numerator_T1D_DK_12_MONTHS) / intval($Denominator_T1D_DK_12_MONTHS) * 100);
        // Prepare the data in PHP
        $data_propotion_T1D_DK_12_MONTHS = [
            'labels' => ["Proportion of patients with T1D who have had DKA in the past 12 months", "Proportion of patients with T1D who haven't had DKA in the past 12 months"],
            'datasets' => [
                [
                    'data' => [$propotion_T1D_DK_12_MONTHS, 100 - $propotion_T1D_DK_12_MONTHS], // Calculate the second value dynamically
                    'backgroundColor' => ['#00a65a', '#f39c12'],
                ]
            ]
        ];
        // Convert the data to JSON format       
        $json_propotion_T1D_DK_12_MONTHS = json_encode($data_propotion_T1D_DK_12_MONTHS);


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
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $propotion_T1D_HBA1C_6_Months ?>%</h3>
                                    <p>% People with T1D with an A1C checked within the last 6 months</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl" data-id="item1" onclick="handleModalClick(this)">
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
                                    <h3><?= $propotion_T1D_HBA1C_LESS_8_LAST_MEASURE ?>%</h3>
                                    <p> Proportion of patients with T1D with HbA1C < 8 on last measure</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl2" data-id="item2" onclick="handleModalClick(this)">
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
                                    <h3><?= $propotion_T1D_DK_12_MONTHS ?>%</h3>
                                    <p> Proportion of patients with T1D who have had DKA in the past 12 months</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal-xl3" data-id="item3" onclick="handleModalClick(this)">
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
                                        <h4 class="modal-title">% People with T1D with an A1C checked within the last 6
                                            months.</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modal-body-content">
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
                                            $pagNum = $override->getNo_Numerator_TID_Hba1c_6Months();
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }
                                            $data = $override->getNo_Numerator_TID_Hba1c_6Months_Data_Rows($page, $numRec);
                                            ?>

                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-6">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="hba1c_checked"
                                                                    id="hba1c_checked" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        People with T1D with an A1C checked within the
                                                                        last 6 months
                                                                    </option>
                                                                    <option value="2">
                                                                        People with T1D with an A1C Not checked within
                                                                        the
                                                                        last 6 months
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                                    <th>HbA1C</th>
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
                                                                        <td><?= $clients['study_id'] ?></td>
                                                                        <td><?= $clients['age'] ?></td>
                                                                        <td><?= $sex['name'] ?></td>
                                                                        <td><?= $row['hba1c'] ?></td>
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
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>

                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <form method="post">
                                        <div class="modal-footer justify-content-between">
                                            <!-- <form method="post"> -->
                                            <!-- <input type="hidden" name="data" value="<?= $x; ?>">
                                            <input type="hidden" name="table"
                                                value="<?= $tables['Tables_in_penplus']; ?>"> -->
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="download_xlsx" class="btn btn-primary">Download
                                                XLSX</button>
                                            <!-- <a href="data.php?id=2&table=<?= $tables['Tables_in_penplus'] ?>"
                                                role=" button" class="btn btn-info"> View Recoreds
                                            </a> -->
                                            <!-- </form> -->
                                        </div>
                                    </form>
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
                                        <h4 class="modal-title">Proportion of patients with T1D with HbA1C < 8 on last
                                                measure</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                    </div>
                                    <div class="modal-body" id="modal-body-content2">
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
                                                        <canvas id="hba1c_less_8"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            $pagNum = $override->getNo3_1();
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }
                                            $data = $override->getNo3_1_ROWS_DATA($page, $numRec);
                                            ?>

                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-6">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="hba1c_checked"
                                                                    id="hba1c_checked" style="width: 100%;"
                                                                    autocomplete="off">
                                                                    <option value="1">
                                                                        Proportion of patients with T1D with HbA1C < 8
                                                                            on last measure </option>
                                                                    <option value="2">
                                                                        Proportion of patients with T1D with HbA1C > 8
                                                                        on last measure
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                                    <th>HbA1C</th>
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
                                                                        <td><?= $clients['study_id'] ?></td>
                                                                        <td><?= $clients['age'] ?></td>
                                                                        <td><?= $sex['name'] ?></td>
                                                                        <td><?= $row['hba1c'] ?></td>
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
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>

                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <form method="post">
                                        <div class="modal-footer justify-content-between">
                                            <!-- <form method="post"> -->
                                            <!-- <input type="hidden" name="data" value="<?= $x; ?>">
                                                                        <input type="hidden" name="table"
                                                                            value="<?= $tables['Tables_in_penplus']; ?>"> -->
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="download_xlsx" class="btn btn-primary">Download
                                                XLSX</button>
                                            <!-- <a href="data.php?id=2&table=<?= $tables['Tables_in_penplus'] ?>"
                                                                            role=" button" class="btn btn-info"> View Recoreds
                                                                        </a> -->
                                            <!-- </form> -->
                                        </div>
                                    </form>
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
                                        <h4 class="modal-title">Proportion of patients with T1D who have had DKA in the
                                            past 12 months</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modal-body-content3">
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
                                                        <canvas id="dk_12"
                                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                            <?php
                                            $pagNum = 0;
                                            $pagNum = $override->getNo_Numerator_T1D_DK_12_MONTHS();
                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }
                                            $data = $override->getNo_Numerator_T1D_DK_12_MONTHS_ROWS_DATA($page, $numRec);
                                            ?>

                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="col-sm-6">
                                                        <div class="row-form clearfix">
                                                            <div class="form-group">
                                                                <select class="form-control" name="dk_12" id="dk_12_id"
                                                                    style="width: 100%;" autocomplete="off">
                                                                    <option value="1">
                                                                        Proportion of patients with T1D who have had DKA
                                                                        in the past 12 months </option>
                                                                    <option value="2">
                                                                        Proportion of patients with T1D who have haven't
                                                                        DKA in the past 12 months
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">Proportion of patients with T1D who have
                                                            had DKA in the past 12 months</h3>
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
                                                                    <th>DKA</th>
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
                                                                        <td><?= $clients['study_id'] ?></td>
                                                                        <td><?= $clients['age'] ?></td>
                                                                        <td><?= $sex['name'] ?></td>
                                                                        <td><?= $row['dka_number'] ?></td>
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
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo max($currentPage - 1, 1); ?>">&laquo;</a>
                                                            </li>

                                                            <!-- First Page (if outside the range) -->
                                                            <?php if ($start > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=1">1</a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                                                                        href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
                                                                </li>
                                                            <?php endif; ?>

                                                            <!-- Next Page -->
                                                            <li
                                                                class="page-item <?php echo ($currentPage >= $pages) ? 'disabled' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="reports3.php?site_id=<?= $currentSite; ?>&page=<?php echo min($currentPage + 1, $pages); ?>">&raquo;</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <form method="post">
                                        <div class="modal-footer justify-content-between">
                                            <!-- <form method="post"> -->
                                            <!-- <input type="hidden" name="data" value="<?= $x; ?>">
                                                                                                                        <input type="hidden" name="table"
                                                                                                                            value="<?= $tables['Tables_in_penplus']; ?>"> -->
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="download_xlsx" class="btn btn-primary">Download
                                                XLSX</button>
                                            <!-- <a href="data.php?id=2&table=<?= $tables['Tables_in_penplus'] ?>"
                                                                                                                            role=" button" class="btn btn-info"> View Recoreds
                                                                                                                        </a> -->
                                            <!-- </form> -->
                                        </div>
                                    </form>
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
            new Chart(hba1c_test, {
                type: 'pie', // Pie chart type
                data: hba1c_test_Data,
                options: hba1c_test_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>
    <script>
        // function handleModalClick(element) {
        //     // Get the ID from the clicked link
        //     const id = element.getAttribute('data-id');

        //     // Log the ID (for debugging purposes)
        //     console.log("ID passed to modal:", id);

        //     // Update modal content dynamically based on the ID
        //     const modalBody = document.getElementById('modal-body-content');
        //     modalBody.innerHTML = `Dynamic content for ID: ${id}`;

        //     // Optionally update modal title or other elements
        //     const modalTitle = document.getElementById('modal-xl-label');
        //     modalTitle.innerText = `Details for ${id}`;
        // }
    </script>

    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            hba1c_less_8_Data = <?php echo $json_propotion_T1D_HBA1C_LESS_8_LAST_MEASURE; ?>
            // Get the canvas element
            var hba1c_less_8 = $('#hba1c_less_8').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var hba1c_less_8_Options = {
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
            new Chart(hba1c_less_8, {
                type: 'pie', // Pie chart type
                data: hba1c_less_8_Data,
                options: hba1c_less_8_Options,
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

            dk_12_Data = <?php echo $json_propotion_T1D_DK_12_MONTHS; ?>
            // Get the canvas element
            var dk_12 = $('#dk_12').get(0).getContext('2d');

            // Options to include data labels inside the chart
            var dk_12_Options = {
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
            new Chart(dk_12, {
                type: 'pie', // Pie chart type
                data: dk_12_Data,
                options: dk_12_Options,
                plugins: [ChartDataLabels] // Include the datalabels plugin in the chart
            });

        })
    </script>

</body>

</html>