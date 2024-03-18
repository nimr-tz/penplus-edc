const diagnosis_other_cardiac1 = document.getElementById(
  "diagnosis_other_cardiac1"
);
const diagnosis_other_cardiac2 = document.getElementById(
  "diagnosis_other_cardiac2"
);

const diagnosis_specify111111 = document.getElementById(
  "diagnosis_specify111111"
);
const diagnosis_specify22222 = document.getElementById(
  "diagnosis_specify22222"
);

function toggleElementVisibility() {
  if (diagnosis_other_cardiac1.checked) {
    diagnosis_specify111111.style.display = "block";
    diagnosis_specify22222.setAttribute("required", "required");
  } else {
    diagnosis_specify111111.style.display = "none";
    diagnosis_specify22222.removeAttribute("required");
  }
}

diagnosis_other_cardiac1.addEventListener("change", toggleElementVisibility);
diagnosis_other_cardiac2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
