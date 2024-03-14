const hypertension1 = document.getElementById("hypertension1");
const hypertension2 = document.getElementById("hypertension2");
const hypertension3 = document.getElementById("hypertension3");

const hypertension_date = document.getElementById("hypertension_date");

const hypertension_date1 = document.getElementById("hypertension_date1");

function toggleElementVisibility() {
  if (hypertension1.checked) {
    hypertension_date1.style.display = "block";
    hypertension_date.setAttribute("required", "required");
  } else {
    hypertension_date1.style.display = "none";
    hypertension_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hypertension1.addEventListener("change", toggleElementVisibility);
hypertension2.addEventListener("change", toggleElementVisibility);
hypertension3.addEventListener("change", toggleElementVisibility);


