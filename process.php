<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();




if ($_GET['content'] == 'all_generic2') {
    $all_generic = $override->get('generic', 'status', $_GET['getUid_status'],'name');
    ?>
    <option value="">Select Brands</option>
    <?php foreach ($all_generic as $batch) { ?>
        <option value="<?= $batch['id'] ?>"><?= $batch['name'] ?></option>
<?php }
}