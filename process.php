<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
header('Content-Type: application/json');


$output = array();
$all_generic = $override->getcolumns('clients', 'id', 'clinic_date', 'firstname', 'age');
foreach ($all_generic as $name) {
    $output[] = $name;
}
echo json_encode($output);


if ($_GET['content'] == 'category') {
    $sub_category = $override->get('sub_category', 'category', $_GET['getUid']); ?>
    <option value="">Select</option>
    <?php foreach ($sub_category as $value) { ?>
        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
    <?php }
} elseif ($_GET['content'] == 'all_generic2') {
    $all_generic = $override->get('generic', 'status', $_GET['getUid_status'], 'name');
    ?>
    <option value="">Select Brands</option>
    <?php foreach ($all_generic as $batch) { ?>
        <option value="<?= $batch['id'] ?>"><?= $batch['name'] ?></option>
    <?php }
} elseif ($_GET['content'] == 'region_id') {
    if ($_GET['region_id']) {
        $districts = $override->getNews('districts', 'status', 1, 'region_id', $_GET['region_id']);
    ?>
        <option value="">Select Districts</option>
        <?php foreach ($districts as $district) { ?>
            <option value="<?= $district['id'] ?>"><?= $district['name'] ?></option>
        <?php }
    }
} elseif ($_GET['content'] == 'district_id') {
    if ($_GET['district_id']) {
        $wards = $override->getNews('wards', 'status', 1, 'district_id', $_GET['district_id']);
        ?>
        <option value="">Select Wards</option>
        <?php foreach ($wards as $ward) { ?>
            <option value="<?= $ward['id'] ?>"><?= $ward['name'] ?></option>
<?php }
    }
}
