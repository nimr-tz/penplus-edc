const hepatitis_test1 = document.getElementById(`hepatitis_test1`);
const hepatitis_test2 = document.getElementById(`hepatitis_test2`);

const hepatitis_date_label = document.getElementById(`hepatitis_date_label`);
const hepatitis_date = document.getElementById(`hepatitis_date`);
const hepatitis_results = document.getElementById(`hepatitis_results`);
const hepatitis_results1 = document.getElementById(`hepatitis_results1`);

function toggleElementVisibility() {
  if (hepatitis_test1.checked) {
    hepatitis_date_label.style.display = "block";
    hepatitis_date.style.display = "block";
    hepatitis_date.setAttribute("required", "required");
    hepatitis_results.style.display = "block";
    hepatitis_results1.setAttribute("required", "required");
  } else {
    hepatitis_date_label.style.display = "none";
    hepatitis_date.style.display = "none";
    hepatitis_date.removeAttribute("required");
    hepatitis_results.style.display = "none";
    hepatitis_results1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hepatitis_test1.addEventListener("change", toggleElementVisibility);
hepatitis_test2.addEventListener("change", toggleElementVisibility);


function unsetHepatitis_result() {
  var unsetHepatitis_results = document.getElementsByName("hepatitis_results");
  unsetHepatitis_results.forEach(function (unsetHepatitis_result) {
    unsetHepatitis_result.checked = false;
  });
}
