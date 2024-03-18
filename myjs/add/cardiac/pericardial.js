const pericardial1 = document.getElementById("pericardial1");
const pericardial2 = document.getElementById("pericardial2");

const sub_pericardial1_1 = document.getElementById("sub_pericardial1");
const sub_pericardial = document.getElementById("sub_pericardial");


function toggleElementVisibility() {
  if (pericardial1.checked) {
    sub_pericardial.style.display = "block";
    sub_pericardial1_1.setAttribute("required", "required");
  } else {
    sub_pericardial.style.display = "none";
    sub_pericardial1_1.removeAttribute("required");
  }
}

pericardial1.addEventListener("change", toggleElementVisibility);
pericardial2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();


