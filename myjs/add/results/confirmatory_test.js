const confirmatory_test1 = document.getElementById("confirmatory_test1");
const confirmatory_test2 = document.getElementById("confirmatory_test2");

const confirmatory_test_type1 = document.getElementById(
  "confirmatory_test_type1"
);

const confirmatory_test_type = document.getElementById(
  "confirmatory_test_type"
);

function toggleElementVisibility() {
  if (scd_test96.checked) {
    confirmatory_test_type1.style.display = "block";
    confirmatory_test_type.setAttribute("required", "required");
  } else {
    confirmatory_test_type1.style.display = "none";
    confirmatory_test_type.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

confirmatory_test1.addEventListener("change", toggleElementVisibility);
confirmatory_test2.addEventListener("change", toggleElementVisibility);



