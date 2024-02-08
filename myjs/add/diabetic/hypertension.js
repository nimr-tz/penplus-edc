const hypertension1 = document.getElementById("hypertension1");
const hypertension_date1 = document.getElementById("hypertension_date1");

function showElement() {
  if (hypertension1.value === "96") {
    hypertension_date1.style.display = "block";
  } else {
    hypertension_date1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", hypertension1.value);
}

// Check if there's a previously selected value in localStorage
const hypertension1Value = localStorage.getItem("selectedValue");

if (hypertension1Value) {
  hypertension1.value = hypertension1Value;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
hypertension1.addEventListener("change", showElement);
