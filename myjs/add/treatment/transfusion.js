const transfusion_needed1 = document.getElementById("transfusion_needed1");
const transfusion_needed2 = document.getElementById("transfusion_needed2");

const transfusion_units1 = document.getElementById(`transfusion_units1`);
const transfusion_units = document.getElementById(`transfusion_units`);

function toggleElementVisibility() {
  if (transfusion_needed1.checked) {
    transfusion_units1.style.display = "block";
    transfusion_units.setAttribute("required", "required");
  } else {
    transfusion_units1.style.display = "none";
    transfusion_units.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

transfusion_needed1.addEventListener("change", toggleElementVisibility);
transfusion_needed2.addEventListener("change", toggleElementVisibility);
