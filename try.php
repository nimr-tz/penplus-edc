<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;

$numRec = 50;
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <?php
                        if ($_GET['status'] == 1) {
                            echo $title = 'Screening';
                        ?>
                        <?php
                        } elseif ($_GET['status'] == 2) {
                            echo $title = 'Eligibility';
                        ?>
                        <?php
                        } elseif ($_GET['status'] == 3) {
                            echo  $title = 'Enrollment';
                        ?>
                        <?php
                        } elseif ($_GET['status'] == 4) {
                            echo $title = 'Termination';
                        ?>
                        <?php
                        } elseif ($_GET['status'] == 5) {
                            echo  $title = 'Registration'; ?>
                        <?php
                        } ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                        <li class="breadcrumb-item active"><?= $title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($user->data()->power == 1 || $user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) {
                        if ($_GET['site_id'] != null) {

                            $pagNum = 0;
                            if ($_GET['status'] == 1) {
                                $pagNum = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 2) {
                                $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 3) {
                                $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 4) {
                                $pagNum = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 5) {
                                $pagNum = $override->countData('clients', 'status', 1, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 6) {
                                $pagNum = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 7) {
                                $pagNum = $override->getCount('clients', 'site_id', $_GET['sid']);
                            } elseif ($_GET['status'] == 8) {
                                $pagNum = $override->countData('clients', 'status', 0, 'site_id', $_GET['sid']);
                            }
                            $pages = ceil($pagNum / $numRec);
                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                $page = 0;
                            } else {
                                $page = ($_GET['page'] * $numRec) - $numRec;
                            }

                            if ($_GET['status'] == 1) {
                                $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 2) {
                                $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 3) {
                                $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 4) {
                                $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 5) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 6) {
                                $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 7) {
                                $clients = $override->getWithLimit('clients', 'site_id', $_GET['sid'], $page, $numRec);
                            } elseif ($_GET['status'] == 8) {
                                $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $_GET['sid'], $page, $numRec);
                            }


                            // if ($_GET['status'] == 1) {
                            //     $clients = $override->getDataDesc3('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 2) {
                            //     $clients = $override->getDataDesc3('clients', 'status', 1, 'eligible', 1, 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 3) {
                            //     $clients = $override->getDataDesc3('clients', 'status', 1, 'enrolled', 1, 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 4) {
                            //     $clients = $override->getDataDesc3('clients', 'status', 1, 'end_study', 1, 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 5) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 1, 'site_id', $_GET['site_id'],  'id');
                            // } elseif ($_GET['status'] == 6) {
                            //     $clients = $override->getDataDesc3('clients', 'status', 1, 'screened', 1, 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 7) {
                            //     $clients = $override->getDataDesc1('clients', 'site_id', $_GET['site_id'], 'id');
                            // } elseif ($_GET['status'] == 8) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 0, 'site_id', $_GET['site_id'],  'id');
                            // }
                        } else {

                            $pagNum = 0;
                            if ($_GET['status'] == 1) {
                                $pagNum = $override->getCount1('clients', 'status', 1, 'screened', 1);
                            } elseif ($_GET['status'] == 2) {
                                $pagNum = $override->getCount1('clients', 'status', 1, 'eligible', 1);
                            } elseif ($_GET['status'] == 3) {
                                $pagNum = $override->getCount1('clients', 'status', 1, 'enrolled', 1);
                            } elseif ($_GET['status'] == 4) {
                                $pagNum = $override->getCount1('clients', 'status', 1, 'end_study', 1);
                            } elseif ($_GET['status'] == 5) {
                                $pagNum = $override->getCount('clients', 'status', 1);
                            } elseif ($_GET['status'] == 6) {
                                $pagNum = $override->getCount1('clients', 'status', 1, 'screened', 0);
                            } elseif ($_GET['status'] == 7) {
                                $clients = $override->getNo('clients');
                            } elseif ($_GET['status'] == 8) {
                                $pagNum = $override->getCount('clients', 'status', 0);
                            }
                            $pages = ceil($pagNum / $numRec);
                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                $page = 0;
                            } else {
                                $page = ($_GET['page'] * $numRec) - $numRec;
                            }

                            if ($_GET['status'] == 1) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 1, $page, $numRec);
                            } elseif ($_GET['status'] == 2) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'eligible', 1, $page, $numRec);
                            } elseif ($_GET['status'] == 3) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'enrolled', 1, $page, $numRec);
                            } elseif ($_GET['status'] == 4) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'end_study', 1, $page, $numRec);
                            } elseif ($_GET['status'] == 5) {
                                $clients = $override->getWithLimit('clients', 'status', 1, $page, $numRec);
                            } elseif ($_GET['status'] == 6) {
                                $clients = $override->getWithLimit1('clients', 'status', 1, 'screened', 0, $page, $numRec);
                            } elseif ($_GET['status'] == 7) {
                                $clients = $override->getDataLimit('clients', $page, $numRec);
                            } elseif ($_GET['status'] == 8) {
                                $clients = $override->getWithLimit('clients', 'status', 0, $page, $numRec);
                            }

                            // if ($_GET['status'] == 1) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 1, 'screened', 1, 'id');
                            // } elseif ($_GET['status'] == 2) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 1, 'eligible', 1, 'id');
                            // } elseif ($_GET['status'] == 3) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 1, 'enrolled', 1, 'id');
                            // } elseif ($_GET['status'] == 4) {
                            //     $clients = $override->getDataDesc2('clients', 'status', 1, 'end_study', 1, 'id');
                            // } elseif ($_GET['status'] == 5) {
                            //     $clients = $override->getDataDesc1('clients', 'status', 1, 'id');
                            // } elseif ($_GET['status'] == 6) {
                            //     $clients = $override->getDataDesc2(
                            //         'clients',
                            //         'status',
                            //         1,
                            //         'screened',
                            //         0,
                            //         'id'
                            //     );
                            // } elseif (
                            //     $_GET['status'] == 7
                            // ) {
                            //     $clients = $override->getDataDesc('clients', 'id');
                            // } elseif ($_GET['status'] == 8) {
                            //     $clients = $override->getDataDesc1('clients', 'status', 0, 'id');
                            // }
                        }
                    } else {
                        $pagNum = 0;
                        if ($_GET['status'] == 1) {
                            $pagNum = $override->countData2('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 2) {
                            $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 3) {
                            $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 4) {
                            $pagNum = $override->countData2('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 5) {
                            $pagNum = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 6) {
                            $pagNum = $override->countData2('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 7) {
                            $pagNum = $override->getCount('clients', 'site_id', $user->data()->site_id);
                        } elseif ($_GET['status'] == 8) {
                            $pagNum = $override->countData('clients', 'status', 0, 'site_id', $user->data()->site_id);
                        }
                        $pages = ceil($pagNum / $numRec);
                        if (!$_GET['page'] || $_GET['page'] == 1) {
                            $page = 0;
                        } else {
                            $page = ($_GET['page'] * $numRec) - $numRec;
                        }
                        if ($_GET['status'] == 1) {
                            $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 2) {
                            $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 3) {
                            $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 4) {
                            $clients = $override->getWithLimit3('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 5) {
                            $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 6) {
                            $clients = $override->getWithLimit3('clients', 'status', 1, 'screened', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 7) {
                            $clients = $override->getWithLimit('clients', 'site_id', $user->data()->site_id, $page, $numRec);
                        } elseif ($_GET['status'] == 8) {
                            $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                        }

                        // if ($_GET['status'] == 1) {
                        //     $clients = $override->getDataDesc3('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 2) {
                        //     $clients = $override->getDataDesc3('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 3) {
                        //     $clients = $override->getDataDesc3('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 4) {
                        //     $clients = $override->getDataDesc3('clients', 'status', 1, 'end_study', 1, 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 5) {
                        //     $clients = $override->getDataDesc2('clients', 'status', 1, 'site_id', $user->data()->site_id,  'id');
                        // } elseif ($_GET['status'] == 6) {
                        //     $clients = $override->getDataDesc3('clients', 'status', 1, 'screened', 1, 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 7) {
                        //     $clients = $override->getDataDesc1('clients', 'site_id', $user->data()->site_id, 'id');
                        // } elseif ($_GET['status'] == 8) {
                        //     $clients = $override->getDataDesc2('clients', 'status', 0, 'site_id', $user->data()->site_id,  'id');
                        // }
                    }
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Task</th>
                                        <th>Progress</th>
                                        <th style="width: 40px">Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">55%</span></td>
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
                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Condensed Full Width Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Task</th>
                                        <th>Progress</th>
                                        <th style="width: 40px">Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Clean database</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-warning" style="width: 70%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">70%</span></td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Cron job running</td>
                                        <td>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar bg-primary" style="width: 30%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">30%</span></td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Fix and squish bugs</td>
                                        <td>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar bg-success" style="width: 90%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">90%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->