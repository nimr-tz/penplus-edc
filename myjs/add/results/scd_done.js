const scd_done1 = document.getElementById("scd_done1");
const scd_done2 = document.getElementById("scd_done2");

const sc_test = document.getElementById("sc_test");
const sc_test1 = document.getElementById("sc_test1");

const confirmatory_test1 = document.getElementById("confirmatory_test1");
const confirmatory_test = document.getElementById("confirmatory_test");

function toggleElementVisibility() {
  if (scd_done1.checked) {
    sc_test.style.display = "block";
    sc_test1.setAttribute("required", "required");
    confirmatory_test.style.display = "block";
    confirmatory_test1.setAttribute("required", "required");
  } else {
    sc_test.style.display = "none";
    sc_test1.removeAttribute("required");
    confirmatory_test.style.display = "block";
    confirmatory_test1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

scd_done1.addEventListener("change", toggleElementVisibility);
scd_done2.addEventListener("change", toggleElementVisibility);
