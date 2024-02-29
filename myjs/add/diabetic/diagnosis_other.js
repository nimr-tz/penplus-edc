const diagnosis4 = document.getElementById("diagnosis4");
const diagnosis_other4 = document.getElementById("diagnosis_other4");

function showElement() {
  if (diagnosis4.value === "96") {
    diagnosis_other4.style.display = "block";
  } else {
    diagnosis_other4.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", diagnosis4.value);
}

// Check if there's a previously selected value in localStorage
const diagnosis4Value = localStorage.getItem("selectedValue");

if (diagnosis4Value) {
  diagnosis4.value = diagnosis4Value;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
diagnosis4.addEventListener("change", showElement);
