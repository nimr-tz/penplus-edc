const referred = document.getElementById("referred");
const referred_other = document.getElementById("referred_other");

function showElement() {
  if (referred.value === "96") {
    referred_other.style.display = "block";
  } else {
    referred_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", referred.value);

//   // Check if the elementToShow is hidden and the selected value is the hidden value
//   if (elementToShow.style.display === "none" && referred.value === "96") {
//     // Reset dropdown value to default or another appropriate action
//     referred.value = "2";
//     alert("Other is not available.");
//   }
}

// Check if there's a previously selected value in localStorage
const referredValue = localStorage.getItem("selectedValue");

if (referredValue) {
  referred.value = referredValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
referred.addEventListener("change", showElement);
