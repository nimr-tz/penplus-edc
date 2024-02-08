const cardiomyopathy = document.getElementById("cardiomyopathy");
const arrhythmia_other = document.getElementById("arrhythmia_other");

function showElement() {
  if (cardiomyopathy.value === "1") {
    arrhythmia_other.style.display = "block";
  } else {
    arrhythmia_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", cardiomyopathy.value);
}

// Check if there's a previously selected value in localStorage
const cardiomyopathyValue = localStorage.getItem("selectedValue");

if (cardiomyopathyValue) {
  cardiomyopathy.value = cardiomyopathyValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
cardiomyopathy.addEventListener("change", showElement);
