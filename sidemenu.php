<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$users = $override->getData('user');
if ($user->isLoggedIn()) {
    if ($user->data()->power == 1) {
        // $registered = $override->getCount('clients', 'status', 1);
        $not_screened = $override->countData('clients', 'status', 1, 'screened', 0);
        $all = $override->getNo('clients');
        $deleted = $override->getCount('clients', 'status', 0);
    } else {
        // $registered = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
        $not_screened = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id);
        $all = $override->getCount('clients', 'site_id', $user->data()->site_id);
        $deleted = $override->countData('clients', 'status', 0, 'site_id', $user->data()->site_id);
    }


    if (Input::exists('post')) {

        if (Input::get('search_by_site')) {
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


    $staff_all_active = $override->getCount('user', 'status', 1);
    $staff_all = $override->getNo('user');
    $staff_active = $override->getDataStaffCount('user', 'status', 1, 'power', 0, 'count', 4, 'id');
    $staff_inactive = $override->getDataStaffCount('user', 'status', 0, 'power', 0, 'count', 4, 'id');
    $staff_lock_active = $override->getDataStaff1Count('user', 'status', 1, 'power', 0, 'count', 4, 'id');
    $staff_lock_inactive = $override->getDataStaff1Count('user', 'status', 0, 'power', 0, 'count', 4, 'id');

    if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) {
        if ($_GET['site_id'] != null) {

            // DISEASE ALL
            $diseases = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1, 'site_id', $_GET['site_id']);
            $cardiac = $override->countData1('main_diagnosis', 'status', 1, 'cardiac', 1, 'site_id', $_GET['site_id']);
            $diabetes = $override->countData1('main_diagnosis', 'status', 1, 'diabetes', 1, 'site_id', $_GET['site_id']);
            $sickle_cell = $override->countData1('main_diagnosis', 'status', 1, 'sickle_cell', 1, 'site_id', $_GET['site_id']);
            $other_diagnosis = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0, 'site_id', $_GET['site_id']);

            // DIEASES CARDIAC
            $cardiomyopathy = $override->countData1('cardiac', 'status', 1, 'cardiomyopathy', 1, 'site_id', $_GET['site_id']);
            $heumatic = $override->countData1('cardiac', 'status', 1, 'heumatic', 1, 'site_id', $_GET['site_id']);
            $congenital = $override->countData1('cardiac', 'status', 1, 'congenital', 1, 'site_id', $_GET['site_id']);
            $heart_failure = $override->countData1('cardiac', 'status', 1, 'heart_failure', 1, 'site_id', $_GET['site_id']);
            $pericardial = $override->countData1('cardiac', 'status', 1, 'pericardial', 1, 'site_id', $_GET['site_id']);
            $stroke = $override->countData1('cardiac', 'status', 1, 'stroke', 1, 'site_id', $_GET['site_id']);
            $arrhythmia = $override->countData1('cardiac', 'status', 1, 'arrhythmia', 1, 'site_id', $_GET['site_id']);
            $thromboembolic = $override->countData1('cardiac', 'status', 1, 'thromboembolic', 1, 'site_id', $_GET['site_id']);


            // DATA
            $clients = $override->countData('clients', 'status', 1, 'site_id', $_GET['site_id']);
            $screening = $override->countData('screening', 'status', 1, 'site_id', $_GET['site_id']);
            $demographic = $override->countData('demographic', 'status', 1, 'site_id', $_GET['site_id']);
            $vital = $override->countData('vital', 'status', 1, 'site_id', $_GET['site_id']);
            $main_diagnosis = $override->countData('main_diagnosis', 'status', 1, 'site_id', $_GET['site_id']);
            $history = $override->countData('history', 'status', 1, 'site_id', $_GET['site_id']);
            $symptoms = $override->countData('symptoms', 'status', 1, 'site_id', $_GET['site_id']);
            $cardiac = $override->countData('cardiac', 'status', 1, 'site_id', $_GET['site_id']);
            $diabetic = $override->countData('diabetic', 'status', 1, 'site_id', $_GET['site_id']);
            $sickle_cell = $override->countData('sickle_cell', 'status', 1, 'site_id', $_GET['site_id']);
            $siblings = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $_GET['site_id']);
            $results = $override->countData('results', 'status', 1, 'site_id', $_GET['site_id']);
            $hospitalization = $override->countData('hospitalization', 'status', 1, 'site_id', $_GET['site_id']);
            $hospitalization_details = $override->countData('hospitalization_details', 'status', 1, 'site_id', $_GET['site_id']);
            $admissions = $override->countData('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
            $treatment_plan = $override->countData('treatment_plan', 'status', 1, 'site_id', $_GET['site_id']);
            $medications = $override->countData('medication_treatments', 'status', 1, 'site_id', $_GET['site_id']);
            $dgns_complctns_comorbdts = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $_GET['site_id']);
            $risks = $override->countData('risks', 'status', 1, 'site_id', $_GET['site_id']);
            $lab_details = $override->countData('lab_details', 'status', 1, 'site_id', $_GET['site_id']);
            $lab_requests = $override->countData('lab_requests', 'status', 1, 'site_id', $_GET['site_id']);
            $test_list = $override->getCount('test_list', 'status', 1);
            $summary = $override->countData('summary', 'status', 1, 'site_id', $_GET['site_id']);
            $social_economic = $override->countData('social_economic', 'status', 1, 'site_id', $_GET['site_id']);
            $schedule = $override->countData('visit', 'status', 1, 'site_id', $_GET['site_id']);
            $study_id = $override->countData('study_id', 'status', 1, 'site_id', $_GET['site_id']);
            $sites = $override->countData('site', 'status', 1, 'id', $_GET['site_id']);

            $hb1ac = $override->countData('vital', 'status', 1, 'site_id', $_GET['site_id']);

            // REPORTS

            $registered = $override->getCount1('clients', 'status', 1, 'site_id', $_GET['site_id']);
            $screened = $override->countData1('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id']);
            $eligible = $override->countData1('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id']);
            $enrolled = $override->countData1('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
            $end = $override->countData1('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id']);
        } else {
            // DIEASES ALL

            $diseases = $override->countData5('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1);
            $cardiac = $override->countData('main_diagnosis', 'status', 1, 'cardiac', 1);
            $diabetes = $override->countData('main_diagnosis', 'status', 1, 'diabetes', 1);
            $sickle_cell = $override->countData('main_diagnosis', 'status', 1, 'sickle_cell', 1);
            $other_diagnosis = $override->countData5('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0);

            // DIEASES CARDIAC

            $cardiomyopathy = $override->countData('cardiac', 'status', 1, 'cardiomyopathy', 1);
            $heumatic = $override->countData('cardiac', 'status', 1, 'heumatic', 1);
            $congenital = $override->countData('cardiac', 'status', 1, 'congenital', 1);
            $heart_failure = $override->countData('cardiac', 'status', 1, 'heart_failure', 1);
            $pericardial = $override->countData('cardiac', 'status', 1, 'pericardial', 1);
            $stroke = $override->countData('cardiac', 'status', 1, 'stroke', 1);
            $arrhythmia = $override->countData('cardiac', 'status', 1, 'arrhythmia', 1);
            $thromboembolic = $override->countData('cardiac', 'status', 1, 'thromboembolic', 1);


            // DATA
            $clients = $override->getCount('clients', 'status', 1);
            $screening = $override->getCount('screening', 'status', 1);
            $demographic = $override->getCount('demographic', 'status', 1);
            $vital = $override->getCount('vital', 'status', 1);
            $main_diagnosis = $override->getCount('main_diagnosis', 'status', 1);
            $history = $override->getCount('history', 'status', 1);
            $symptoms = $override->getCount('symptoms', 'status', 1);
            $cardiac = $override->getCount('cardiac', 'status', 1);
            $diabetic = $override->getCount('diabetic', 'status', 1);
            $sickle_cell = $override->getCount('sickle_cell', 'status', 1);
            $siblings = $override->getCount('sickle_cell_status_table', 'status', 1);
            $results = $override->getCount('results', 'status', 1);
            $hospitalization = $override->getCount('hospitalization', 'status', 1);
            $hospitalization_details = $override->getCount('hospitalization_details', 'status', 1);
            $admissions = $override->getCount('hospitalization_table', 'status', 1);
            $treatment_plan = $override->getCount('treatment_plan', 'status', 1);
            $medications = $override->getCount('medication_treatments', 'status', 1);
            $dgns_complctns_comorbdts = $override->getCount('dgns_complctns_comorbdts', 'status', 1);
            $risks = $override->getCount('risks', 'status', 1);
            $lab_details = $override->getCount('lab_details', 'status', 1);
            $lab_requests = $override->getCount('lab_requests', 'status', 1);
            $test_list = $override->getCount('test_list', 'status', 1);
            $summary = $override->getCount('summary', 'status', 1);
            $social_economic = $override->getCount('social_economic', 'status', 1);
            $schedule = $override->getCount('visit', 'status', 1);
            $study_id = $override->getCount('study_id', 'status', 1);
            $sites = $override->getCount('site', 'status', 1);

            // REPORTS

            $registered = $override->getCount('clients', 'status', 1);
            $screened = $override->countData('clients', 'status', 1, 'screened', 1);
            $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
            $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
            $end = $override->countData('clients', 'status', 1, 'end_study', 1);
        }
    } else {
        // DIEASES ALL

        $diseases = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1, 'site_id', $user->data()->site_id);
        $cardiac = $override->countData1('main_diagnosis', 'status', 1, 'cardiac', 1, 'site_id', $user->data()->site_id);
        $diabetes = $override->countData1('main_diagnosis', 'status', 1, 'diabetes', 1, 'site_id', $user->data()->site_id);
        $sickle_cell = $override->countData1('main_diagnosis', 'status', 1, 'sickle_cell', 1, 'site_id', $user->data()->site_id);
        $other_diagnosis = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0, 'site_id', $user->data()->site_id);

        // DIEASES CARDIAC

        $cardiomyopathy = $override->countData1('cardiac', 'status', 1, 'cardiomyopathy', 1, 'site_id', $user->data()->site_id);
        $heumatic = $override->countData1('cardiac', 'status', 1, 'heumatic', 1, 'site_id', $user->data()->site_id);
        $congenital = $override->countData1('cardiac', 'status', 1, 'congenital', 1, 'site_id', $user->data()->site_id);
        $heart_failure = $override->countData1('cardiac', 'status', 1, 'heart_failure', 1, 'site_id', $user->data()->site_id);
        $pericardial = $override->countData1('cardiac', 'status', 1, 'pericardial', 1, 'site_id', $user->data()->site_id);
        $stroke = $override->countData1('cardiac', 'status', 1, 'stroke', 1, 'site_id', $user->data()->site_id);
        $arrhythmia = $override->countData1('cardiac', 'status', 1, 'arrhythmia', 1, 'site_id', $user->data()->site_id);
        $thromboembolic = $override->countData1('cardiac', 'status', 1, 'thromboembolic', 1, 'site_id', $user->data()->site_id);

        // DATA
        $clients = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
        $screening = $override->countData('screening', 'status', 1, 'site_id', $user->data()->site_id);
        $demographic = $override->countData('demographic', 'status', 1, 'site_id', $user->data()->site_id);
        $vital = $override->countData('vital', 'status', 1, 'site_id', $user->data()->site_id);
        $main_diagnosis = $override->countData('main_diagnosis', 'status', 1, 'site_id', $user->data()->site_id);
        $history = $override->countData('history', 'status', 1, 'site_id', $user->data()->site_id);
        $symptoms = $override->countData('symptoms', 'status', 1, 'site_id', $user->data()->site_id);
        $cardiac = $override->countData('cardiac', 'status', 1, 'site_id', $user->data()->site_id);
        $diabetic = $override->countData('diabetic', 'status', 1, 'site_id', $user->data()->site_id);
        $sickle_cell = $override->countData('sickle_cell', 'status', 1, 'site_id', $user->data()->site_id);
        $siblings = $override->countData('sickle_cell_status_table', 'status', 1, 'site_id', $user->data()->site_id);
        $results = $override->countData('results', 'status', 1, 'site_id', $user->data()->site_id);
        $hospitalization = $override->countData('hospitalization', 'status', 1, 'site_id', $user->data()->site_id);
        $hospitalization_details = $override->countData('hospitalization_details', 'status', 1, 'site_id', $user->data()->site_id);
        $admissions = $override->countData('hospitalization_table', 'status', 1, 'site_id', $_GET['site_id']);
        $treatment_plan = $override->countData('treatment_plan', 'status', 1, 'site_id', $user->data()->site_id);
        $medications = $override->countData('medication_treatments', 'status', 1, 'site_id', $user->data()->site_id);
        $dgns_complctns_comorbdts = $override->countData('dgns_complctns_comorbdts', 'status', 1, 'site_id', $user->data()->site_id);
        $risks = $override->countData('risks', 'status', 1, 'site_id', $user->data()->site_id);
        $lab_details = $override->countData('lab_details', 'status', 1, 'site_id', $user->data()->site_id);
        $lab_requests = $override->countData('lab_requests', 'status', 1, 'site_id', $user->data()->site_id);
        $test_list = $override->getCount('test_list', 'status', 1);
        $summary = $override->countData('summary', 'status', 1, 'site_id', $user->data()->site_id);
        $social_economic = $override->countData('social_economic', 'status', 1, 'site_id', $user->data()->site_id);
        $schedule = $override->countData('visit', 'status', 1, 'site_id', $user->data()->site_id);
        $study_id = $override->countData('study_id', 'status', 1, 'site_id', $user->data()->site_id);
        $sites = $override->countData('site', 'status', 1, 'id', $user->data()->site_id);

        // REPORTS

        $registered = $override->getCount1('clients', 'status', 1, 'site_id', $user->data()->site_id);
        $screened = $override->countData1('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id);
        $eligible = $override->countData1('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
        $enrolled = $override->countData1('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
        $end = $override->countData1('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id);
    }
} else {
    Redirect::to('index.php');
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index1.php" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PenPlus Database</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user->data()->firstname . ' - ' . $user->data()->lastname ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
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
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index1.php" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p> <?= $Site; ?></p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="./index2.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index3.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li> -->
                    </ul>
                </li>
                <?php if ($user->data()->accessLevel == 1) { ?>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <span class="badge badge-info right"><?= $staff_all; ?></span>
                            <p>
                                Staff <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="add.php?id=1" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Register
                                        <span class="right badge badge-danger">New Staff</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=1" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $staff_active; ?></span>
                                    <p>Active</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=2" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $staff_inactive; ?></span>
                                    <p>Inactive</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=3" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $staff_lock_active; ?></span>
                                    <p>Locked And Active</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=4" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $staff_lock_inactive; ?></span>
                                    <p>Locked And Inactive</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=5" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $staff_all_active; ?></span>
                                    <p>All Staff</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <span class="badge badge-info right"><?= $Position; ?></span>
                            <p>
                                Positions <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="add.php?id=2" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Add
                                        <span class="right badge badge-danger">New Position</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=2" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $Position; ?></span>
                                    <p>List of Positions</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <span class="badge badge-info right"><?= $registered; ?></span>
                        <p>
                            Registration <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>

                            <li class="nav-item">
                                <a href="add.php?id=4" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Register
                                        <span class="right badge badge-danger">New Client</span>
                                    </p>
                                </a>
                            </li>
                        <?php } ?>

                        <li class="nav-item">
                            <!-- <a href="info.php?id=3&site_id=<?= $user->data()->site_id; ?>&status=5" class="nav-link"> -->
                            <a href="info.php?id=3&status=5" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <span class="badge badge-info right"><?= $registered; ?></span>
                                <p>Registered</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>

                    <li class="nav-item">
                        <a href="info.php?id=8" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Medications <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="add.php?id=5&btn=Add" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New Name</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add.php?id=6&btn=Add" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New Batch</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List of Names
                                        <span class="badge badge-info right"><?= $override->getCount('medications', 'status', 1) ?></span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=10" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List of Batches
                                        <span class="badge badge-info right"><?= $override->getCount('batch', 'status', 1) ?></span>
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expired Batches</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <!-- <a href="info.php?id=3&status=5" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Tests
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $override->getCount('test_list', 'status', 1) ?></span>
                        </p>
                    </a> -->
                        <ul class="nav nav-treeview">
                            <?php foreach ($override->getData('site') as $test_list) { ?>
                                <li class="nav-item">
                                    <a href="info.php?id=3&status=5&site_id=<?= $test_list['id']; ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?= $test_list['name'] ?></p>
                                        <span class="badge badge-info right">
                                            <?= $override->getCount('test_list', 'status', 1) ?>
                                        </span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <!-- <a href="info.php?id=3&status=5" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Log book
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $override->getCount('test_list', 'status', 1) ?></span>
                        </p>
                    </a> -->
                        <ul class="nav nav-treeview">
                            <?php foreach ($override->getData('site') as $log_book) { ?>
                                <li class="nav-item">
                                    <a href="info.php?id=3&status=5&site_id=<?= $log_book['id']; ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?= $log_book['name'] ?></p>
                                        <span class="badge badge-info right">
                                            <?= $override->getCount('test_list', 'status', 1) ?>
                                        </span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>


                <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>

                    <li class="nav-item">
                        <a href="info.php?id=8" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Lab Section <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="add_test_category.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->countData('category', 'status', 1, 'delete_flag', 0) ?>
                                    </span>
                                    <p>List of Category </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add_test_sub_category.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->countData('sub_category', 'status', 1, 'delete_flag', 0) ?>
                                    </span>
                                    <p>List of Sub Category </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add_test_list.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->countData('test_list', 'status', 1, 'delete_flag', 0) ?>
                                    </span>
                                    <p>List of Tests
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pending_doctor_confirmation.php?status=1" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->getCount1('screening', 'ncd', 2, 'doctor_confirm', 0) ?>
                                    </span>
                                    <p>Pending Doctor Confirmations
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="appointments.php?status=1" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->getCount('appointment_list', 'status', 1) ?> </span>
                                    <p>View Completed Lab Requests</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="appointments.php?status=0" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                        <?= $override->getCount('appointment_list', 'status', 1) ?> </span>
                                    <p>View Pending Lab Requests </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <!-- <a href="info.php?id=3&status=5" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Log book
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $override->getCount('test_list', 'status', 1) ?></span>
                        </p>
                    </a> -->
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="info.php?id=3&status=5&site_id=<?= $log_book['id']; ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p><?= $log_book['name'] ?></p>
                                <span class="badge badge-info right">
                                    <?= $override->getCount('test_list', 'status', 1) ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 3) { ?>

                    <li class="nav-item">
                        <a href="info.php?id=8" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Reports <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>
                                        Vitals && Hb1AC<i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports_vital.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $vital ?></span>
                                            <p>Vital Signs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports_hb1ac.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $hb1ac ?></span>
                                            <p>Hb1AC</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>
                                        Recruitments <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $registered ?></span>
                                            <p>Registered</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $screened ?></span>
                                            <p>Screened</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $eligible ?></span>
                                            <p>Eligible</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $enrolled ?></span>
                                            <p>Enrolled</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $end ?></span>
                                            <p>Termintaed</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>
                                        Diseases <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports1.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $diseases ?></span>
                                            <p>All Diseases </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon fas fa-circle"></i>
                                            <span class="badge badge-info right"><?= $cardiac ?></span>
                                            <p>
                                                Cardiac
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="reports2.php" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $cardiac ?></span>
                                                    <p>All Cardiac</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports5.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $cardiomyopathy ?></span>
                                                    <p>Cardiomyopathy</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports6.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $heumatic ?></span>
                                                    <p> Rheumatic Heart disease </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports7.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $congenital ?></span>
                                                    <p>Congenital heart disease</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports8.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $heart_failure ?></span>
                                                    <p>Right Heart Failure</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports9.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $pericardial ?></span>
                                                    <p>Pericardial disease</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports10.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $stroke ?></span>
                                                    <p>Stroke</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports11.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $arrhythmia ?></span>
                                                    <p>Arrhythmia</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="reports12.php" class="nav-link">
                                                    <i class="far fa-dot-circle nav-icon"></i>
                                                    <span class="badge badge-info right"><?= $thromboembolic ?></span>
                                                    <p>Thromboembolic</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports3.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $diabetes ?></span>
                                            <p>Diabetes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports4.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $sickle_cell ?></span>
                                            <p>Sickle Cell</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="reports13.php" class="nav-link">
                                            <i class="fas fa-circle nav-icon"></i>
                                            <span class="badge badge-info right"><?= $other_diagnosis ?></span>
                                            <p>Other Diagnosis</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($user->data()->accessLevel == 1) { ?>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Data <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="data.php?id=1" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <!-- <span class="badge badge-info right"> -->
                                    <!-- <?= $all; ?> -->
                                    <!-- </span> -->
                                    <p>Download Data</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="data.php?id=1&status=1&data=1" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $clients; ?></span>
                                    <p>Registration</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=2&data=2" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $screening; ?></span>
                                    <p>Screening</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=3&data=3" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $demographic; ?></span>
                                    <p>Demographic </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=4&data=4" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $vital; ?></span>
                                    <p>Vital Sign</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=5&data=5" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $main_diagnosis; ?></span>
                                    <p>Patient Categories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=6&data=6" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Patient & Family History & Complication</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=7&data=7" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $symptoms; ?></span>
                                    <p>Symtom & Exam</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="data.php?id=2&status=8&data=8" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $cardiac; ?></span>
                                    <p>Main diagnosis 1 ( Cardiac )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=9&data=9" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $diabetic; ?></span>
                                    <p>Main diagnosis 2 ( Diabetes )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=10&data=10" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $sickle_cell; ?></span>
                                    <p>Main diagnosis 3 ( Sickle Cell )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=11&data=11" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $siblings; ?></span>
                                    <p>Siblings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=12&data=12" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $results; ?></span>
                                    <p>Results</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=13&data=13" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $hospitalization; ?></span>
                                    <p>Hospitalization</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=14&data=14" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $hospitalization_details; ?></span>
                                    <p>Hospitalization Details </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=15&data=15" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $admissions; ?></span>
                                    <p>Admission</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=16&data=16" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $treatment_plan; ?></span>
                                    <p>Treatment Plan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=17&data=17" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $medications; ?></span>
                                    <p>Medications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=18&data=18" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $dgns_complctns_comorbdts; ?></span>
                                    <p>Diagnosis, Complications, & Comorbidities</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=19&data=19" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $risks; ?></span>
                                    <p>RISK</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=20&data=20" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $lab_details; ?></span>
                                    <p>Lab Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=21&data=21" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $lab_requests; ?></span>
                                    <p>Lab Requests</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=22&data=22" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $test_list; ?></span>
                                    <p>Test Lists</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=23&data=23" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $social_economic; ?></span>
                                    <p>Socioeconomic Status </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=24&data=24" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $summary; ?></span>
                                    <p>Summary</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=3&status=25&data=25" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $schedule; ?></span>
                                    <p>Visits</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=2&status=26&data=26" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $study_id; ?></span>
                                    <p>Study IDs</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=4&status=27&data=27" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $sites; ?></span>
                                    <p>Sites</p>
                                </a>
                            </li> -->
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Study ID <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php
                            if ($user->data()->power == 1) {
                            ?>
                                <li class="nav-item">
                                    <a href="info.php?id=5" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Set Study Id</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="info.php?id=6" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>UnSet Study Id</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>

                                <li class="nav-item">
                                    <a href="info.php?id=5" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Update Study Id</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Extra <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="add.php?id=24" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Regions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add.php?id=25" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Disricts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add.php?id=26" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Wards</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Tables <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=30" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Medications List</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="add.php?id=25" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Disricts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add.php?id=26" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Wards</p>
                                </a>
                            </li> -->
                        </ul>
                    </li>

                  <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Queries <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=11" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>DM Patients With No Hb1AC Results For Baseline</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="add.php?id=25" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Disricts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add.php?id=26" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Wards</p>
                                </a>
                            </li> -->
                        </ul>
                    </li>

                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
