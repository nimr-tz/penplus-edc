const hematology_test1 = document.getElementById("hematology_test1");
const hematology_test2 = document.getElementById("hematology_test2");

const hematology_test_hides = document.getElementById("hematology_test_hides");

const wbc = document.getElementById("wbc");
const hb = document.getElementById("hb");
const mcv = document.getElementById("mcv");
const plt = document.getElementById("plt");
const fe_studies = document.getElementById("fe_studies");
const lfts = document.getElementById("lfts");

function toggleElementVisibility() {
  if (hematology_test1.checked) {
    hide_chemistry_test8_8.style.display = "block";
    na_diabetes.setAttribute("required", "required");
    k_diabetes.setAttribute("required", "required");
    cre_diabetes.setAttribute("required", "required");
    proteinuria.setAttribute("required", "required");
    lipid_panel.setAttribute("required", "required");
    other_lab_diabetes.style.display = "block";
    other_lab_diabetes1.setAttribute("required", "required");
  } else {
    hide_chemistry_test8_8.style.display = "none";
    na_diabetes.removeAttribute("required");
    k_diabetes.removeAttribute("required");
    cre_diabetes.removeAttribute("required");
    proteinuria.removeAttribute("required");
    lipid_panel.removeAttribute("required");
    other_lab_diabetes.style.display = "none";
    other_lab_diabetes1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hematology_test1.addEventListener("change", toggleElementVisibility);
hematology_test2.addEventListener("change", toggleElementVisibility);