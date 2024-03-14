const ncd_hospitalizations1 = document.getElementById("ncd_hospitalizations1");
const ncd_hospitalizations2 = document.getElementById("ncd_hospitalizations2");

const hospitalization_number1 = document.getElementById(
  "hospitalization_number1"
);
const hospitalization_number = document.getElementById(
  "hospitalization_number"
);

function toggleElementVisibility() {
  if (ncd_hospitalizations1.checked) {
    hospitalization_number1.style.display = "block";
    hospitalization_number.setAttribute("required", "required");
  } else {
    hospitalization_number1.style.display = "none";
    hospitalization_number.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

ncd_hospitalizations1.addEventListener("change", toggleElementVisibility);
ncd_hospitalizations2.addEventListener("change", toggleElementVisibility);


function unsetNcd_hospitalizations() {
  var unsetNcd_hospitalizations = document.getElementsByName(
    "ncd_hospitalizations"
  );
  unsetNcd_hospitalizations.forEach(function (unsetNcd_hospitalizations) {
    unsetNcd_hospitalizations.checked = false;
  });
}


