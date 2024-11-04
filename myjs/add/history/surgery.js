const cardiac_surgery1 = document.getElementById("cardiac_surgery1");
const cardiac_surgery2 = document.getElementById("cardiac_surgery2");

const cardiac_surgery_type = document.getElementById(`cardiac_surgery_type`);
const cardiac_surgery_type1 = document.getElementById(`cardiac_surgery_type1`);


function toggleElementVisibility() {
  if (cardiac_surgery1.checked) {
    cardiac_surgery_type.style.display = "block";
    cardiac_surgery_type1.setAttribute("required", "required");
  } else {
    cardiac_surgery_type.style.display = "none";
    cardiac_surgery_type1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

cardiac_surgery1.addEventListener("change", toggleElementVisibility);
cardiac_surgery2.addEventListener("change", toggleElementVisibility);



