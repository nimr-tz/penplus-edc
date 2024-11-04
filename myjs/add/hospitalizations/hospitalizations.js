const hospitalizations1 = document.getElementById("hospitalizations1");
const hospitalizations2 = document.getElementById("hospitalizations2");


const total_hospitalization_number1 = document.getElementById(
  "total_hospitalization_number1"
);
const total_hospitalization_number = document.getElementById(
  "total_hospitalization_number"
);
const ncd_hospitalizations = document.getElementById(`ncd_hospitalizations`);
const ncd_hospitalizations1_1 = document.getElementById(`ncd_hospitalizations1`);

function toggleElementVisibility() {
  if (hospitalizations1.checked) {
    total_hospitalization_number1.style.display = "block";
    total_hospitalization_number.setAttribute("required", "required");
    ncd_hospitalizations.style.display = "block";
    ncd_hospitalizations1_1.setAttribute("required", "required");
  } else {
    total_hospitalization_number1.style.display = "none";
    total_hospitalization_number.removeAttribute("required");
    ncd_hospitalizations.style.display = "none";
    ncd_hospitalizations1_1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hospitalizations1.addEventListener("change", toggleElementVisibility);
hospitalizations2.addEventListener("change", toggleElementVisibility);



