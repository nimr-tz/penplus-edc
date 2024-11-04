const diagnosis_summary1 = document.getElementById("diagnosis_summary1");
const diagnosis_summary2 = document.getElementById("diagnosis_summary2");
const diagnosis_summary3 = document.getElementById("diagnosis_summary3");
const diagnosis_summary4 = document.getElementById("diagnosis_summary4");
const diagnosis_summary5 = document.getElementById("diagnosis_summary5");
const diagnosis_summary6 = document.getElementById("diagnosis_summary6");
const diagnosis_summary7 = document.getElementById("diagnosis_summary7");
const diagnosis_summary96 = document.getElementById("diagnosis_summary96");
const diagnosis_summary98 = document.getElementById("diagnosis_summary98");


const diagnosis_summary_other = document.getElementById(
  "diagnosis_summary_other"
);

function toggleElementVisibility() {
  if (diagnosis_summary96.checked) {
    diagnosis_summary_other.setAttribute("required", "required");
    diagnosis_summary_other.style.display = "block";
  } else {
    diagnosis_summary_other.style.display = "none";
    diagnosis_summary_other.removeAttribute("required");
  }
}

diagnosis_summary1.addEventListener("change", toggleElementVisibility);
diagnosis_summary2.addEventListener("change", toggleElementVisibility);
diagnosis_summary3.addEventListener("change", toggleElementVisibility);
diagnosis_summary4.addEventListener("change", toggleElementVisibility);
diagnosis_summary5.addEventListener("change", toggleElementVisibility);
diagnosis_summary6.addEventListener("change", toggleElementVisibility);
diagnosis_summary7.addEventListener("change", toggleElementVisibility);
diagnosis_summary96.addEventListener("change", toggleElementVisibility);
diagnosis_summary98.addEventListener("change", toggleElementVisibility);


// Initial check
toggleElementVisibility();
