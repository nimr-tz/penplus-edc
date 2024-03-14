const prandial_changed1 = document.getElementById("prandial_changed1");
const prandial_changed2 = document.getElementById("prandial_changed2");

const prandial_am = document.getElementById("prandial_am");
const prandial_am2 = document.getElementById(`prandial_am2`);
const prandial_lunch = document.getElementById(`prandial_lunch`);
const prandial_lunch2 = document.getElementById(`prandial_lunch2`);
const prandial_pm = document.getElementById(`prandial_pm`);
const prandial_pm2 = document.getElementById(`prandial_pm2`);

function toggleElementVisibility() {
  if (prandial_changed1.checked) {
    prandial_am.style.display = "block";
    prandial_am2.setAttribute("required", "required");
    prandial_lunch.style.display = "block";
    prandial_lunch2.setAttribute("required", "required");
    prandial_pm.style.display = "block";
    prandial_pm2.setAttribute("required", "required");
  } else {
    prandial_am.style.display = "none";
    prandial_am2.removeAttribute("required");
    prandial_lunch.style.display = "none";
    prandial_lunch2.removeAttribute("required");
    prandial_pm.style.display = "none";
    prandial_pm2.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

prandial_changed1.addEventListener("change", toggleElementVisibility);
prandial_changed2.addEventListener("change", toggleElementVisibility);
