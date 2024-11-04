<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage = null;
$pageError = null;
$errorMessage = null;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_treatment_plan')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    // $treatment_plan = $override->get3('treatment_plan', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    // $medication_id = $override->get3('medication_treatments', 'id', Input::get('medication_id')[$i], 'status', 1, 'patient_id', $_GET['cid']);

                    print_r(count(Input::get('medication_type')));
                    // print_r(count(Input::get('medication_type')));
                    for ($i = 0; $i < count(Input::get('medication_type')); $i++) {
                        if (Input::get('medication_id')[$i]) {
                            if (Input::get('seq_no')[$i] == $_GET['seq']) {
                                $user->updateRecord('medication_treatments', array(
                                    'study_id' => $_GET['sid'],
                                    'visit_code' => $_GET['vcode'],
                                    'visit_day' => $_GET['vday'],
                                    'seq_no' => $_GET['seq'],
                                    'vid' => $_GET['vid'],
                                    'medication_type' => Input::get('medication_type')[$i],
                                    'medication_action' => Input::get('medication_action')[$i],
                                    'medication_dose' => Input::get('medication_dose')[$i],
                                    'units' => Input::get('medication_units')[$i],
                                    'units' => Input::get('medication_units')[$i],
                                ), Input::get('medication_id')[$i]);
                            }
                        } else {
                            $user->createRecord('medication_treatments', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'medication_type' => Input::get('medication_type')[$i],
                                'medication_action' => Input::get('medication_action')[$i],
                                'medication_dose' => Input::get('medication_dose')[$i],
                                'units' => Input::get('medication_units')[$i],
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}


$conn = mysqli_connect("localhost:8889", "root", "root", "tutorials");

if (isset($_POST["addInvoice"])) {
    $customerName = $_POST["customerName"];

    $sql = "INSERT INTO invoices (customerName) VALUES ('$customerName')";
    mysqli_query($conn, $sql);
    $invoiceId = mysqli_insert_id($conn);

    for ($a = 0; $a < count($_POST["itemName"]); $a++) {
        $sql = "INSERT INTO items (invoiceId, itemName, itemQuantity) VALUES ('$invoiceId', '" . $_POST["itemName"][$a] . "', '" . $_POST["itemQuantity"][$a] . "')";
        mysqli_query($conn, $sql);
    }
    echo "<p>Invoice has been added.</p>";
} ?>



<style type="text/css">
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table tr td,
    table tr th {
        border: 1px solid black;
        padding: 25px;
    }
</style>



<form method="POST" action="index.php">
    <input type="text" name="customerName" placeholder="Enter customer name" required>

    <table>
        <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Item Quantity</th>
        </tr>
        <tbody id="tbody"></tbody>
    </table>

    <button type="button" onclick="addItem();">Add Item</button>
    <input type="submit" name="addInvoice" value="Add Invoice">
</form>



<script>
    var items = 0;

    function addItem() {
        items++;

        var html = "<tr>";
        html += "<td>" + items + "</td>";
        html += "<td><input type='text' name='medication_type[]'></td>";
        html += "<td><input type='text' name='medication_action[]'></td>";
        html += "<td><input type='text' name='medication_dose[]'></td>";
        html += "<td><input type='text' name='medication_units[]'></td>";
        html += "<td><button type='button' onclick='deleteRow(this);'>Delete</button></td>"
        html += "</tr>";

        var row = document.getElementById("tbody").insertRow();
        row.innerHTML = html;
    }

    function deleteRow(button) {
        items--
        button.parentElement.parentElement.remove();
        // first parentElement will be td and second will be tr.
    }
</script>