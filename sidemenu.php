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


    $staff_all = $override->getNo('user');
    $staff_active = $override->getDataStaffCount('user', 'status', 1, 'power', 0, 'count', 4, 'id');
    $staff_inactive = $override->getDataStaffCount('user', 'status', 0, 'power', 0, 'count', 4, 'id');
    $staff_lock_active = $override->getDataStaff1Count('user', 'status', 1, 'power', 0, 'count', 4, 'id');
    $staff_lock_inactive = $override->getDataStaff1Count('user', 'status', 0, 'power', 0, 'count', 4, 'id');



    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
        if ($_GET['site_id'] != null) {
            $diseases = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1, 'site_id', $_GET['site_id']);
            $cardiac = $override->countData1('main_diagnosis', 'status', 1, 'cardiac', 1, 'site_id', $_GET['site_id']);
            $diabetes = $override->countData1('main_diagnosis', 'status', 1, 'diabetes', 1, 'site_id', $_GET['site_id']);
            $sickle_cell = $override->countData1('main_diagnosis', 'status', 1, 'sickle_cell', 1, 'site_id', $_GET['site_id']);
            $other_diagnosis = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0, 'site_id', $_GET['site_id']);


            $cardiomyopathy = $override->countData1('cardiac', 'status', 1, 'cardiomyopathy', 1, 'site_id', $_GET['site_id']);
            $heumatic = $override->countData1('cardiac', 'status', 1, 'heumatic', 1, 'site_id', $_GET['site_id']);
            $congenital = $override->countData1('cardiac', 'status', 1, 'congenital', 1, 'site_id', $_GET['site_id']);
            $heart_failure = $override->countData1('cardiac', 'status', 1, 'heart_failure', 1, 'site_id', $_GET['site_id']);
            $pericardial = $override->countData1('cardiac', 'status', 1, 'pericardial', 1, 'site_id', $_GET['site_id']);
            $stroke = $override->countData1('cardiac', 'status', 1, 'stroke', 1, 'site_id', $_GET['site_id']);
            $arrhythmia = $override->countData1('cardiac', 'status', 1, 'arrhythmia', 1, 'site_id', $_GET['site_id']);
            $thromboembolic = $override->countData1('cardiac', 'status', 1, 'thromboembolic', 1, 'site_id', $_GET['site_id']);



            // $histroy = $override->getCount1('history', 'status', 1, 'site_id', $_GET['site_id']);
            // $results = $override->getCount1('results', 'status', 1, 'site_id', $_GET['site_id']);
            // $classification = $override->getCount1('classification', 'status', 1, 'site_id', $_GET['site_id']);
            // $outcome = $override->getCount1('outcome', 'status', 1, 'site_id', $_GET['site_id']);
            // $economic = $override->getCount1('economic', 'status', 1, 'site_id', $_GET['site_id']);
            $visit = $override->getCount1('visit', 'status', 1, 'site_id', $_GET['site_id']);

            $registered = $override->getCount1('clients', 'status', 1, 'site_id', $_GET['site_id']);
            $screened = $override->countData1('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id']);
            $eligible = $override->countData1('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id']);
            $enrolled = $override->countData1('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id']);
            $end = $override->countData1('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id']);
        } else {
            $diseases = $override->countData5('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1);
            $cardiac = $override->countData('main_diagnosis', 'status', 1, 'cardiac', 1);
            $diabetes = $override->countData('main_diagnosis', 'status', 1, 'diabetes', 1);
            $sickle_cell = $override->countData('main_diagnosis', 'status', 1, 'sickle_cell', 1);
            $other_diagnosis = $override->countData5('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0);


            $cardiomyopathy = $override->countData('cardiac', 'status', 1, 'cardiomyopathy', 1);
            $heumatic = $override->countData('cardiac', 'status', 1, 'heumatic', 1);
            $congenital = $override->countData('cardiac', 'status', 1, 'congenital', 1);
            $heart_failure = $override->countData('cardiac', 'status', 1, 'heart_failure', 1);
            $pericardial = $override->countData('cardiac', 'status', 1, 'pericardial', 1);
            $stroke = $override->countData('cardiac', 'status', 1, 'stroke', 1);
            $arrhythmia = $override->countData('cardiac', 'status', 1, 'arrhythmia', 1);
            $thromboembolic = $override->countData('cardiac', 'status', 1, 'thromboembolic', 1);



            // $kap = $override->getCount('kap', 'status', 1);
            // $history = $override->getCount('history', 'status', 1);
            // $results = $override->getCount('results', 'status', 1);
            // $classification = $override->getCount('classification', 'status', 1);
            // $outcome = $override->getCount('outcome', 'status', 1);
            // $economic = $override->getCount('economic', 'status', 1);
            $visit = $override->getCount('visit', 'status', 1);

            $registered = $override->getCount('clients', 'status', 1);
            $screened = $override->countData('clients', 'status', 1, 'screened', 1);
            $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
            $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
            $end = $override->countData('clients', 'status', 1, 'end_study', 1);
        }
    } else {
        $diseases = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 1, 'diabetes', 1, 'sickle_cell', 1, 'site_id', $user->data()->site_id);
        $cardiac = $override->countData1('main_diagnosis', 'status', 1, 'cardiac', 1, 'site_id', $user->data()->site_id);
        $diabetes = $override->countData1('main_diagnosis', 'status', 1, 'diabetes', 1, 'site_id', $user->data()->site_id);
        $sickle_cell = $override->countData1('main_diagnosis', 'status', 1, 'sickle_cell', 1, 'site_id', $user->data()->site_id);
        $other_diagnosis = $override->countData4('main_diagnosis', 'status', 1, 'cardiac', 0, 'diabetes', 0, 'sickle_cell', 0, 'site_id', $user->data()->site_id);


        $cardiomyopathy = $override->countData1('cardiac', 'status', 1, 'cardiomyopathy', 1, 'site_id', $user->data()->site_id);
        $heumatic = $override->countData1('cardiac', 'status', 1, 'heumatic', 1, 'site_id', $user->data()->site_id);
        $congenital = $override->countData1('cardiac', 'status', 1, 'congenital', 1, 'site_id', $user->data()->site_id);
        $heart_failure = $override->countData1('cardiac', 'status', 1, 'heart_failure', 1, 'site_id', $user->data()->site_id);
        $pericardial = $override->countData1('cardiac', 'status', 1, 'pericardial', 1, 'site_id', $user->data()->site_id);
        $stroke = $override->countData1('cardiac', 'status', 1, 'stroke', 1, 'site_id', $user->data()->site_id);
        $arrhythmia = $override->countData1('cardiac', 'status', 1, 'arrhythmia', 1, 'site_id', $user->data()->site_id);
        $thromboembolic = $override->countData1('cardiac', 'status', 1, 'thromboembolic', 1, 'site_id', $user->data()->site_id);

        // $kap = $override->getCount1('kap', 'status', 1, 'site_id', $user->data()->site_id);
        // $histroy = $override->getCount1('history', 'status', 1, 'site_id', $user->data()->site_id);
        // $results = $override->getCount1('results', 'status', 1, 'site_id', $user->data()->site_id);
        // $classification = $override->getCount1('classification', 'status', 1, 'site_id', $user->data()->site_id);
        // $outcome = $override->getCount1('outcome', 'status', 1, 'site_id', $user->data()->site_id);
        // $economic = $override->getCount1('economic', 'status', 1, 'site_id', $user->data()->site_id);
        $visit = $override->getCount1('visit', 'status', 1, 'site_id', $user->data()->site_id);

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
                                <p>Dashboard v1</p>
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
                <?php if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                ?>

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
                        <li class="nav-item">
                            <a href="add.php?id=4" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Register
                                    <span class="right badge badge-danger">New Client</span>
                                </p>
                            </a>
                        </li>
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

                <?php
                if ($user->data()->position == 1 || $user->data()->position == 2) {
                ?>

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

                <?php
                if ($user->data()->position == 1 || $user->data()->position == 2) {
                ?>
                    <li class="nav-item">
                        <a href="info.php?id=8" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Summary Reports <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=8" class="nav-link">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        Recruitments Reports <i class="fas fa-angle-left right"></i>
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
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        Diseases Reports <i class="fas fa-angle-left right"></i>
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

                <?php
                if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Data <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $registered; ?></span>
                                    <p>Registration</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $screened; ?></span>
                                    <p>Screening</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Vital Sign</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Patient Categories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Patient & Family History & Complication</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Symtom & Exam</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Main diagnosis 1 ( Cardiac )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Main diagnosis 2 ( Diabetes )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $history; ?></span>
                                    <p>Main diagnosis 3 ( Sickle Cell )</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $results; ?></span>
                                    <p>Siblings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $results; ?></span>
                                    <p>Results</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $results; ?></span>
                                    <p>Hospitalization</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $classification; ?></span>
                                    <p>Hospitalization Details </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Admission</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $economic; ?></span>
                                    <p>Treatment Plan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Medications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Diagnosis, Complications, & Comorbidities</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>RISK</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Lab Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Socioeconomic Status </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=21&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $outcome; ?></span>
                                    <p>Study IDs</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="data.php?id=1&status=data" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right"><?= $visit; ?></span>
                                    <p>Visits</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Clear Data <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=14" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List of Tables</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="nav-item">
                        <!-- <a href="info.php?id=15" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Unset Study ID <i class="fas fa-angle-left right"></i>

                            </p>
                        </a> -->
                        <!-- <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=15" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List of Tables</p>
                                </a>
                            </li>
                        </ul> -->
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>