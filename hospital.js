// Add row chemotherapy
document.getElementById("add-medication").addEventListener("click", function () {
    var table = document.getElementById("medication_list").getElementsByTagName("tbody")[0];
    var newRow = table.insertRow(table.rows.length);
    var medication_type = newRow.insertCell(0);
    var medication_action = newRow.insertCell(1);
    var medication_dose = newRow.insertCell(2);
    var actionCell = newRow.insertCell(3);
    medication_type.innerHTML = '<input type="text" name="medication_type[]" placeholder="Type medications name...">';
    medication_action.innerHTML = '<select name="medication_action[]" id="medication_action[]" style="width: 100%;"><option value="">Select</option><option value="1">Continue</option><option value="2">Start</option><option value="3">Stop</option><option value="4">Not Eligible</option></select>';
    medication_dose.innerHTML = '<input type="text" name="medication_dose[]">';
    actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
});

// Add row chemotherapy
document.getElementById("add-hospitalization-details").addEventListener("click", function () {
    var table = document.getElementById("hospitalization_details_table").getElementsByTagName("tbody")[0];
    var newRow = table.insertRow(table.rows.length);
    var admission_date = newRow.insertCell(0);
    var admission_reason = newRow.insertCell(1);
    var discharge_diagnosis = newRow.insertCell(2);
    var actionCell = newRow.insertCell(3);
    admission_date.innerHTML = '<input type="text" name="admission_date[]"><span>(Example: 2010-12-01)</span>';
    admission_reason.innerHTML = '<input type="text" name="admission_reason[]">';
    discharge_diagnosis.innerHTML = '<input type="text" name="discharge_diagnosis[]">';
    actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
});


// Add row surgery
document.getElementById("add-sickle-cell-status").addEventListener("click", function () {
    var table = document.getElementById("sickle_cell_table").getElementsByTagName("tbody")[0];
    var newRow = table.insertRow(table.rows.length);
    var age = newRow.insertCell(0);
    var sex = newRow.insertCell(1);
    var status = newRow.insertCell(2);
    var actionCell = newRow.insertCell(3);
    age.innerHTML = '<input type="text" name="age[]">';
    sex.innerHTML = '<select name="sex[]" id="sex[]" style="width: 100%;"><option value="">Select</option><option value="1">Male</option><option value="2">Female</option></select>';
    status.innerHTML = '<input type="text" name="sickle_status[]">';
    actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
});

// Remove row
document.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("remove-row")) {
        var row = e.target.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
});


$(document).ready(function () {
    alert('hi');

    // $("#techForm").submit(function (e) {
    //     var url = "call_ans.php";
    // })
})