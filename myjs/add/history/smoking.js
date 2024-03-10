const smoking1 = document.getElementById(`smoking1`);
const smoking2 = document.getElementById(`smoking2`);
const smoking3 = document.getElementById(`smoking3`);

const start_smoking1 = document.getElementById(`start_smoking1`);
const start_smoking = document.getElementById(`start_smoking`);
const active_smoker = document.getElementById(`active_smoker`);
const active_smoker1 = document.getElementById(`active_smoker1`);
const type_smoked = document.getElementById(`type_smoked`);
const type_smoked1 = document.getElementById(`type_smoked1`);


function toggleElementVisibility() {
  if (smoking1.checked) {
    start_smoking1.style.display = "block";
    start_smoking.style.display = "block";
    start_smoking.setAttribute("required", "required");
    active_smoker.style.display = "block";
    active_smoker1.setAttribute("required", "required");
    type_smoked.style.display = "block";
    type_smoked1.setAttribute("required", "required");
  } else {
    start_smoking1.style.display = "none";
    start_smoking.style.display = "none";
    start_smoking.removeAttribute("required");
    active_smoker.style.display = "none";
    active_smoker1.removeAttribute("required");
    type_smoked.style.display = "none";
    type_smoked1.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

smoking1.addEventListener("change", toggleElementVisibility);
smoking2.addEventListener("change", toggleElementVisibility);
smoking3.addEventListener("change", toggleElementVisibility);
