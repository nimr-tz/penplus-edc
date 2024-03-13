const basal_changed1 = document.getElementById("basal_changed1");
const basal_changed2 = document.getElementById("basal_changed2");

const basal_am = document.getElementById("basal_am");
const basal_am2 = document.getElementById(`basal_am2`);
const basal_pm = document.getElementById(`basal_pm`);
const basal_pm2 = document.getElementById(`basal_pm2`);


function toggleElementVisibility() {
  if (basal_changed1.checked) {
    basal_am.style.display = "block";
    basal_am2.setAttribute("required", "required");
    basal_pm.style.display = "block";
    basal_pm2.setAttribute("required", "required");
  } else {
    basal_am.style.display = "none";
    basal_am2.removeAttribute("required");
    basal_pm.style.display = "none";
    basal_pm2.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

basal_changed1.addEventListener("change", toggleElementVisibility);
basal_changed2.addEventListener("change", toggleElementVisibility);
