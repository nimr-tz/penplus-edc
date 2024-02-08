const diagnosis5 = document.getElementById("diagnosis5");
const diagnosis_other5 = document.getElementById("diagnosis_other5");

function showElement() {
  if (diagnosis5.value === "2") {
    diagnosis_other5.style.display = "block";
  } else {
    diagnosis_other5.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", diagnosis5.value);
}

// Check if there's a previously selected value in localStorage
const diagnosis5Value = localStorage.getItem("selectedValue");

if (diagnosis5Value) {
  diagnosis5.value = diagnosis5Value;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
diagnosis5.addEventListener("change", showElement);
