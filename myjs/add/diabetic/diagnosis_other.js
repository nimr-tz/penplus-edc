const diagnosis_diabetic1 = document.getElementById("diagnosis_diabetic1");
const diagnosis_diabetic2 = document.getElementById("diagnosis_diabetic2");
const diagnosis_diabetic3 = document.getElementById("diagnosis_diabetic3");
const diagnosis_diabetic4 = document.getElementById("diagnosis_diabetic4");
const diagnosis_diabetic96 = document.getElementById("diagnosis_diabetic96");

const diagnosis_other_diabetic = document.getElementById(
  "diagnosis_other_diabetic"
);

function toggleElementVisibility() {
  if (diagnosis_diabetic96.checked) {
    diagnosis_other_diabetic.style.display = "block";
    diagnosis_other_diabetic.setAttribute("required", "required");
  } else {
    diagnosis_other_diabetic.style.display = "none";
    diagnosis_other_diabetic.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();
diagnosis_diabetic1.addEventListener("change", toggleElementVisibility);
diagnosis_diabetic2.addEventListener("change", toggleElementVisibility);
diagnosis_diabetic3.addEventListener("change", toggleElementVisibility);
diagnosis_diabetic4.addEventListener("change", toggleElementVisibility);
diagnosis_diabetic96.addEventListener("change", toggleElementVisibility);



