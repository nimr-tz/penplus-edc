const opioid1 = document.getElementById("opioid1");
const opioid2 = document.getElementById("opioid2");
const opioid3 = document.getElementById("opioid3");

const opioid_type1 = document.getElementById("opioid_type1");
const opioid_type = document.getElementById("opioid_type");

const opioid_dose1 = document.getElementById("opioid_dose1");
const opioid_dose = document.getElementById("opioid_dose");

function toggleElementVisibility() {
  if (opioid1.checked) {
    opioid_dose1.style.display = "block";
    opioid_dose.setAttribute("required", "required");
    opioid_type1.style.display = "block";
    opioid_type.setAttribute("required", "required");
  } else {
    opioid_dose1.style.display = "none";
    opioid_dose.removeAttribute("required");
    opioid_type1.style.display = "none";
    opioid_type.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

opioid1.addEventListener("change", toggleElementVisibility);
opioid2.addEventListener("change", toggleElementVisibility);
opioid3.addEventListener("change", toggleElementVisibility);

