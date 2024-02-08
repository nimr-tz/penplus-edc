const sub_cardiomyopathy = document.getElementById("sub_cardiomyopathy");
const cardiomyopathy_other = document.getElementById("cardiomyopathy_other");

function showElement() {
  if (sub_cardiomyopathy.value === "96") {
    cardiomyopathy_other.style.display = "block";
  } else {
    cardiomyopathy_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_cardiomyopathy.value);
}

// Check if there's a previously selected value in localStorage
const sub_cardiomyopathyValue = localStorage.getItem("selectedValue");

if (sub_cardiomyopathyValue) {
  sub_cardiomyopathy.value = sub_cardiomyopathyValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_cardiomyopathy.addEventListener("change", showElement);
