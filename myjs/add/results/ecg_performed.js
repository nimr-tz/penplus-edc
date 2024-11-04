const ecg_performed1 = document.getElementById("ecg_performed1");
const ecg_performed2 = document.getElementById("ecg_performed2");

const ecg_performed = document.getElementById("ecg_performed");

const ecg_date = document.getElementById("ecg_date");
const ecg1 = document.getElementById("ecg1");

function toggleElementVisibility() {
  if (ecg_performed1.checked) {
    ecg_performed.style.display = "block";
    ecg_date.setAttribute("required", "required");
    ecg1.setAttribute("required", "required");
  } else {
    ecg_performed.style.display = "none";
    ecg_date.removeAttribute("required");
    ecg1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

ecg_performed1.addEventListener("change", toggleElementVisibility);
ecg_performed2.addEventListener("change", toggleElementVisibility);


