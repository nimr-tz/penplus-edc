const vaccination1 = document.getElementById("vaccination1");
const vaccination2 = document.getElementById("vaccination2");

const vaccination_specify1 = document.getElementById(`vaccination_specify1`);
const vaccination_specify = document.getElementById(`vaccination_specify`);

function toggleElementVisibility() {
  if (vaccination1.checked) {
    vaccination_specify1.style.display = "block";
    vaccination_specify.setAttribute("required", "required");
  } else {
    vaccination_specify1.style.display = "none";
    vaccination_specify.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

vaccination1.addEventListener("change", toggleElementVisibility);
vaccination2.addEventListener("change", toggleElementVisibility);
