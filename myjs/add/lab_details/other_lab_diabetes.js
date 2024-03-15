const other_lab_diabetes1_1 = document.getElementById("other_lab_diabetes1");
const other_lab_diabetes2_2 = document.getElementById("other_lab_diabetes2");

const specify_lab_diabetes1 = document.getElementById("specify_lab_diabetes1");
const specify_lab_diabetes = document.getElementById("specify_lab_diabetes");

function toggleElementVisibility() {
  if (other_lab_diabetes1_1.checked) {
    specify_lab_diabetes1.style.display = "block";
    specify_lab_diabetes.setAttribute("required", "required");
  } else {
    specify_lab_diabetes1.style.display = "none";
    specify_lab_diabetes.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

other_lab_diabetes1_1.addEventListener("change", toggleElementVisibility);
other_lab_diabetes2_2.addEventListener("change", toggleElementVisibility);
