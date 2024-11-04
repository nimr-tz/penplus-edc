const scd_test1_1 = document.getElementById("scd_test1");
const scd_test2 = document.getElementById("scd_test2");
const scd_test3 = document.getElementById("scd_test3");
const scd_test4 = document.getElementById("scd_test4");
const scd_test5 = document.getElementById("scd_test5");
const scd_test6 = document.getElementById("scd_test6");
const scd_test96 = document.getElementById("scd_test96");

const scd_test_other = document.getElementById("scd_test_other");

const scd_test_other1 = document.getElementById("scd_test_other1");

function toggleElementVisibility() {
  if (scd_test96.checked) {
    scd_test_other1.style.display = "block";
    scd_test_other.setAttribute("required", "required");
  } else {
    scd_test_other1.style.display = "none";
    scd_test_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

scd_test1_1.addEventListener("change", toggleElementVisibility);
scd_test2.addEventListener("change", toggleElementVisibility);
scd_test3.addEventListener("change", toggleElementVisibility);
scd_test4.addEventListener("change", toggleElementVisibility);
scd_test5.addEventListener("change", toggleElementVisibility);
scd_test6.addEventListener("change", toggleElementVisibility);
scd_test96.addEventListener("change", toggleElementVisibility);


