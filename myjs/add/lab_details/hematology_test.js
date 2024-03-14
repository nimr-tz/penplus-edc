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
    hematology_test_hides.style.display = "block";
    wbc.setAttribute("required", "required");
    hb.setAttribute("required", "required");
    mcv.setAttribute("required", "required");
    plt.setAttribute("required", "required");
    fe_studies.setAttribute("required", "required");
    lfts.setAttribute("required", "required");
  } else {
    hematology_test_hides.style.display = "none";
    wbc.removeAttribute("required");
    hb.removeAttribute("required");
    mcv.removeAttribute("required");
    plt.removeAttribute("required");
    fe_studies.removeAttribute("required");
    lfts.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hematology_test1.addEventListener("change", toggleElementVisibility);
hematology_test2.addEventListener("change", toggleElementVisibility);