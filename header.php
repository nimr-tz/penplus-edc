<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$users = $override->getData('user');
if ($user->isLoggedIn()) {
    if ($user->data()->power == 1) {
        $screened = $override->countData('clients','status', 1, 'screened', 1);
        $eligible = $override->countData('clients','status', 1, 'eligible', 1);
        $enrolled = $override->countData('clients','status', 1, 'enrolled', 1);
        $end = $override->countData('clients','status', 1, 'end_study', 1);
    } else {

        $screened = $override->countData2('clients','status', 1, 'screened', 1,'site_id', $user->data()->site_id);
        $eligible = $override->countData2('clients','status', 1, 'eligible', 1,'site_id', $user->data()->site_id);
        $enrolled = $override->countData2('clients','status', 1, 'enrolled', 1,'site_id', $user->data()->site_id);
        $end = $override->countData2('clients','status', 1, 'end_study', 1,'site_id', $user->data()->site_id);
    }


} else {
    Redirect::to('index.php');
}
?>

<div class="row">
    <div class="col-md-3">
        <div class="wBlock blue clearfix">
            <div class="dSpace">
                <a href="info.php?id=3&status=1">
                    <h3>Screened</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                        <!--130,190,260,230,290,400,340,360,390-->
                    </span>
                    <span class="number"><?= $screened ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="wBlock yellow clearfix">
            <div class="dSpace">
                <a href="info.php?id=3&status=2">
                    <h3>Eligible</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                        <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                    </span>
                    <span class="number"><?= $eligible ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="wBlock green clearfix">
            <div class="dSpace">
                <a href="info.php?id=3&status=3">
                    <h3>Enrolled</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                        <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                    </span>
                    <span class="number"><?= $enrolled ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="wBlock red clearfix">
            <div class="dSpace">
            <a href="info.php?id=3&status=4">
                    <h3>End of study</h3>
                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                    </span>
                    <span class="number"><?= $end ?></span>
                </a>
            </div>
        </div>
    </div>
</div>