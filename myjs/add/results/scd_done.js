const scd_done1 = document.getElementById("scd_done1");
const scd_done2 = document.getElementById("scd_done2");

const scd_test = document.getElementById("scd_test");
const scd_test1 = document.getElementById("scd_test1");

const confirmatory_test1 = document.getElementById("confirmatory_test1");
const confirmatory_test = document.getElementById("confirmatory_test");

function toggleElementVisibility() {
  if (scd_done1.checked) {
    scd_test.style.display = "block";
    scd_test1.setAttribute("required", "required");
    confirmatory_test.style.display = "block";
    confirmatory_test1.setAttribute("required", "required");
  } else {
    scd_test.style.display = "none";
    scd_test1.removeAttribute("required");
    confirmatory_test.style.display = "block";
    confirmatory_test1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

scd_done1.addEventListener("change", toggleElementVisibility);
scd_done2.addEventListener("change", toggleElementVisibility);
