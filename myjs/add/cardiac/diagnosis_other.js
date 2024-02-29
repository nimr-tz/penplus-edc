const diagnosis_other2 = document.getElementById("diagnosis_other2");
const diagnosis_specify2 = document.getElementById("diagnosis_specify2");

function showElement() {
  if (diagnosis_other2.value === "1") {
    diagnosis_specify2.style.display = "block";
  } else {
    diagnosis_specify2.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", diagnosis_other2.value);
}

// Check if there's a previously selected value in localStorage
const diagnosis_other2Value = localStorage.getItem("selectedValue");

if (diagnosis_other2Value) {
  diagnosis_other2.value = diagnosis_other2Value;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
diagnosis_other2.addEventListener("change", showElement);
