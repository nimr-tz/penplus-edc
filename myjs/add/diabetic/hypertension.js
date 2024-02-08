const hypertension6 = document.getElementById("hypertension6");
const hypertension_date6 = document.getElementById("hypertension_date6");

function showElement() {
  if (hypertension6.value === "1") {
    hypertension_date6.style.display = "block";
  } else {
    hypertension_date6.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", hypertension6.value);
}

// Check if there's a previously selected value in localStorage
const hypertension6Value = localStorage.getItem("selectedValue");

if (hypertension6Value) {
  hypertension6.value = hypertension6Value;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
hypertension6.addEventListener("change", showElement);
