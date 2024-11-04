const cardiac_surgery1_1_1 = document.getElementById("cardiac_surgery1_1_1");
const cardiac_surgery2_2_2 = document.getElementById("cardiac_surgery2_2_2");

const cardiac_surgery_type3_3_3 = document.getElementById(
  "cardiac_surgery_type3_3_3"
);
const cardiac_surgery_type2_2_2 = document.getElementById(
  "cardiac_surgery_type2_2_2"
);

function toggleElementVisibility() {
  if (cardiac_surgery1_1_1.checked) {
    cardiac_surgery_type3_3_3.style.display = "block";
    cardiac_surgery_type2_2_2.setAttribute("required", "required");
  } else {
    cardiac_surgery_type3_3_3.style.display = "none";
    cardiac_surgery_type2_2_2.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

cardiac_surgery1_1_1.addEventListener("change", toggleElementVisibility);
cardiac_surgery2_2_2.addEventListener("change", toggleElementVisibility);
