<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();

?>

<?php
// include_once('config.php');
if ($_POST['action'] == "addDataRow") {
?>
    <tr>
        <td align="center" class="text-danger"><button type="button" data-toggle="tooltip" data-placement="right" title="Click To Remove" onclick="if(confirm('Are you sure to remove?')){$(this).closest('tr').remove();}" class="btn btn-danger"><i class="fa fa-fw fa-trash-alt"></i></button></td>
        <td align="center"><?php echo date('Y-m-d H:i:s'); ?></td>
        <td><input type="text" name="username[]" class="form-control" required="required"></td>
        <td>
            <select name="usercountry[]" id="usercountry" class="form-control selectpicker" data-live-search="true" data-size="10" required="required">
                <option value="">Select</option>
                <?php
                $result = $override->get('medication_treatments', 'status', 1);
                foreach ($result as $val) { ?>
                    <option value="<?php echo $val['id'] ?>" data-subtext="(<?php echo $val['id'] ?>)"><?php echo mb_strtoupper($val['name'], 'UTF-8') ?></option>
                <?php } ?>
            </select>
        </td>
        <td><input type="email" name="useremail[]" class="form-control" required="required"></td>
        <td><input type="text" name="userphone[]" class="form-control" required="required"></td>
    </tr>
<?php
    echo '|***|addmore';
}

//Submit data or extra rows
if ($_POST['action'] == "saveAddMore") {
    extract($_REQUEST);
    foreach ($username as $key => $un) {
        $user->createRecord('medication_treatments', array(
            'study_id' => 1,
            'visit_code' => 2,
            'visit_day' => 3,
            'seq_no' => 4,
            'vid' => 5,
            'medication_type' => 1,
            'medication_action' => 1,
            'medication_dose' => 1,
            'units' =>1,
            'patient_id' => 1,
            'staff_id' => $user->data()->id,
            'status' => 1,
            'created_on' => date('Y-m-d'),
            'site_id' => $user->data()->site_id,
        ));
    }
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}
