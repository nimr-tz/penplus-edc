$(document).ready(function () {
  var counter = 0;
  $("#addrow2").on("click", function () {
    var newRow = $("<tr>");
    var cols = "";

    cols +=
      '<td><select class="form-control select2" name="medication_action[]" id="medication_action[]"><option value="">Select</option></select></td>';

    // cols +=
    //   '<td><input class="autocomplete form-control select2" type="text" name="medication_type[]" id="myInput" placeholder="Type medications name..." onkeyup="' +
    //   fetchData1() +
    //   '"</td>';
    cols +=
      '<td><select class="form-control" name="medication_action[]" id="medication_action[]" style="width: 80%;"><option value="">Select</option><option value="1">Continue</option><option value="2">Start</option><option value="3">Stop</option><option value="4">Not Eligible</option></select></td>';
    cols +=
      '<td><input type="number" min="0" max="1000" style="width: 80%;" class="form-control" name="medication_dose[]"></td>';

    cols +=
      '<td><input type="text" class="form-control" name="medication_units[]"></td>';

    cols +=
      '<td><input type="button" class="ibtnDel2 btn btn-md btn-danger"  value="Delete"></td>';
    newRow.append(cols);
    $("table.order-list").append(newRow);
    counter++;
  });

  $("table.order-list").on("click", ".ibtnDel2", function (event) {
    $(this).closest("tr").remove();
    counter -= 1;
  });
});

function fetchData1() {
  /*An array containing all the country names in the world:*/
  // var getUid = $(this).val();
  fetch("fetch_medications2.php")
    .then((response) => response.json())
    .then((data) => {
      // Process the data received from the PHP script
      // console.log(data);
      autocomplete(document.getElementById(myInput), data);
    })
    .catch((error) => {
      // Handle any errors that occurred during the fetch request
      console.error("Error:", error);
    });
}

// $(document).ready(function () {
//   $("#fl_wait").hide();

//   $("#medication_action").change(function () {
// var getUid = $(this).val();
$("#fl_wait").show();
$.ajax({
  url: "fetch_medications.php",
  method: "GET",
  data: {
    getUid: 1,
  },
  success: function (data) {
    $("#medication_action").html(data);
    $("#fl_wait").hide();
  },
});
//   });
// });
