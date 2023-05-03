<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

// $successMessage = null;
// $pageError = null;
// $errorMessage = null;
// $noE = 0;
// $noC = 0;
// $noD = 0;
$users = $override->getData('user');
if ($user->isLoggedIn()) {
    if ($user->data()->power == 1) {
        $screened = $override->getCount('clients', 'status', 1);
        $eligible = $override->countData('clients', 'status', 1, 'eligible', 1);
        $enrolled = $override->countData('clients', 'status', 1, 'enrolled', 1);
        $end = $override->countData('clients', 'status', 0, 'enrolled', 1);
    } else {
        $screened = $override->countData('clients', 'site_id', $user->data()->site_id, 'status', 1);
        $eligible = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'eligible', 1);
        $enrolled = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 1, 'enrolled', 1);
        $end = $override->countData1('clients', 'site_id', $user->data()->site_id, 'status', 0, 'enrolled', 1);
    }
} else {
    Redirect::to('index.php');
}
?>




<div class="row">

    <div class="col-md-3">

        <div class="wBlock yellow clearfix">
            <div class="dSpace">
                <h3>Screened</h3>
                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!--130,190,260,230,290,400,340,360,390-->
                </span>
                <a href="info.php?id=3&status=1">
                    <span class="number"><?= $screened ?></span>
                </a>
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="wBlock blue clearfix">
            <div class="dSpace">
                <h3>Eligible</h3>
                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                </span>
                <a href="info.php?id=3&status=2">
                    <span class="number"><?= $eligible ?></span>
                </a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="wBlock green clearfix">
            <div class="dSpace">
                <h3>Enrolled</h3>
                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                </span>
                <a href="info.php?id=3&status=3">
                    <span class="number"><?= $enrolled ?></span>
                </a>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="wBlock red clearfix">
            <div class="dSpace">
                <h3>End of study</h3>
                <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                    <!-- 240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190 -->
                </span>
                <a href="info.php?id=3&status=4">
                    <span class="number"><?= $end ?></span>
                </a>
            </div>

        </div>

    </div>

</div>