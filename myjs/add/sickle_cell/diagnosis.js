const diagnosis_sickle_cell1 = document.getElementById(
  "diagnosis_sickle_cell1"
);
const diagnosis_sickle_cell2 = document.getElementById(
  "diagnosis_sickle_cell2"
);

const diagnosis_other_sickcle_cell1 = document.getElementById(
  "diagnosis_other_sickcle_cell1"
);
const diagnosis_other_sickcle_cell = document.getElementById(
  "diagnosis_other_sickcle_cell"
);



function toggleElementVisibility() {
  if (diagnosis_sickle_cell2.checked) {
    diagnosis_other_sickcle_cell1.style.display = "block";
    diagnosis_other_sickcle_cell.setAttribute("required", "required");
  } else {
    diagnosis_other_sickcle_cell1.style.display = "none";
    diagnosis_other_sickcle_cell.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

diagnosis_sickle_cell1.addEventListener("change", toggleElementVisibility);
diagnosis_sickle_cell2.addEventListener("change", toggleElementVisibility);
