const cardiac_surgery_type1_1 = document.getElementById("cardiac_surgery_type1");
const cardiac_surgery_type2_1 = document.getElementById("cardiac_surgery_type2");
const cardiac_surgery_type96 = document.getElementById(
  `cardiac_surgery_type96`
);

const surgery_other = document.getElementById(`surgery_other`);

function toggleElementVisibility() {
  if (cardiac_surgery_type96.checked) {
    surgery_other.style.display = "block";
    surgery_other.setAttribute("required", "required");
  } else {
    surgery_other.style.display = "none";
    surgery_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

cardiac_surgery_type1_1.addEventListener("change", toggleElementVisibility);
cardiac_surgery_type2_1.addEventListener("change", toggleElementVisibility);
cardiac_surgery_type96.addEventListener("change", toggleElementVisibility);


function unsetCardiac_surgery_type() {
  var unsetCardiac_surgery_types = document.getElementsByName(
    "cardiac_surgery_type"
  );
  unsetCardiac_surgery_types.forEach(function (unsetCardiac_surgery_type) {
    unsetCardiac_surgery_type.checked = false;
  });
}




