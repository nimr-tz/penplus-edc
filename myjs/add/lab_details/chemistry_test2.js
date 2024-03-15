const chemistry_test6_6 = document.getElementById("chemistry_test6_6");
const chemistry_test7_7 = document.getElementById("chemistry_test7_7");

const hide_chemistry_test8_8 = document.getElementById(
  "hide_chemistry_test8_8"
);

const na_diabetes = document.getElementById("na_diabetes");
const k_diabetes = document.getElementById("k_diabetes");
const cre_diabetes = document.getElementById("cre_diabetes");
const proteinuria = document.getElementById("proteinuria");
const lipid_panel = document.getElementById("lipid_panel");
const other_lab_diabetes9_9_9 = document.getElementById("other_lab_diabetes9_9_9");
const other_lab_diabetes1_1_1_1_1T = document.getElementById(
  "other_lab_diabetes1_1_1_1_1"
);

function toggleElementVisibility() {
  if (chemistry_test6_6.checked) {
    hide_chemistry_test8_8.style.display = "block";
    na_diabetes.setAttribute("required", "required");
    k_diabetes.setAttribute("required", "required");
    cre_diabetes.setAttribute("required", "required");
    proteinuria.setAttribute("required", "required");
    lipid_panel.setAttribute("required", "required");
    other_lab_diabetes9_9_9.style.display = "block";
    other_lab_diabetes1_1_1_1_1T.setAttribute("required", "required");
  } else {
    hide_chemistry_test8_8.style.display = "none";
    na_diabetes.removeAttribute("required");
    k_diabetes.removeAttribute("required");
    cre_diabetes.removeAttribute("required");
    proteinuria.removeAttribute("required");
    lipid_panel.removeAttribute("required");
    other_lab_diabetes9_9_9.style.display = "none";
    other_lab_diabetes1_1_1_1_1T.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

chemistry_test6_6.addEventListener("change", toggleElementVisibility);
chemistry_test7_7.addEventListener("change", toggleElementVisibility);

