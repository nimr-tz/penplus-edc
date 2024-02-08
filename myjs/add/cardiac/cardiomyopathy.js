const cardiomyopathy = document.getElementById("cardiomyopathy");
const sub_cardiomyopathy1 = document.getElementById("sub_cardiomyopathy1");

function showElement() {
  if (cardiomyopathy.value === "1") {
    sub_cardiomyopathy1.style.display = "block";
  } else {
    sub_cardiomyopathy1.style.display = "none";
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
