const ecg11 = document.getElementById("ecg1");
const ecg22 = document.getElementById("ecg2");
const ecg33 = document.getElementById("ecg3");
const ecg44 = document.getElementById("ecg4");
const ecg966 = document.getElementById("ecg96");

const ecg_other = document.getElementById("ecg_other");

const ecg_other1 = document.getElementById("ecg_other1");

function toggleElementVisibility() {
  if (ecg966.checked) {
    ecg_other1.style.display = "block";
    ecg_other.setAttribute("required", "required");
  } else {
    ecg_other1.style.display = "none";
    ecg_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

ecg11.addEventListener("change", toggleElementVisibility);
ecg22.addEventListener("change", toggleElementVisibility);
ecg33.addEventListener("change", toggleElementVisibility);
ecg44.addEventListener("change", toggleElementVisibility);
ecg966.addEventListener("change", toggleElementVisibility);
