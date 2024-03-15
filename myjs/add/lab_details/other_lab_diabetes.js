const other_lab_diabetes1_1_1_1_1 = document.getElementById(
  "other_lab_diabetes1_1_1_1_1"
);
const other_lab_diabetes2_2_2_2_2 = document.getElementById(
  "other_lab_diabetes2_2_2_2_2"
);

const specify_lab_diabetes3_3_3_3_3 = document.getElementById(
  "specify_lab_diabetes3_3_3_3_3"
);
const specify_lab_diabetes2_2_2_2_2 = document.getElementById(
  "specify_lab_diabetes2_2_2_2_2"
);

function toggleElementVisibility() {
  if (other_lab_diabetes1_1_1_1_1.checked) {
    specify_lab_diabetes3_3_3_3_3.style.display = "block";
    specify_lab_diabetes2_2_2_2_2.setAttribute("required", "required");
  } else {
    specify_lab_diabetes3_3_3_3_3.style.display = "none";
    specify_lab_diabetes2_2_2_2_2.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

other_lab_diabetes1_1_1_1_1.addEventListener("change", toggleElementVisibility);
other_lab_diabetes2_2_2_2_2.addEventListener("change", toggleElementVisibility);
