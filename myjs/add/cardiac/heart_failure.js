const heart_failure = document.getElementById("heart_failure");
const sub_heart_failure = document.getElementById("sub_heart_failure");

function showElement() {
  if (heart_failure.value === "1") {
    sub_heart_failure.style.display = "block";
  } else {
    sub_heart_failure.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", heart_failure.value);
}

// Check if there's a previously selected value in localStorage
const heart_failureValue = localStorage.getItem("selectedValue");

if (heart_failureValue) {
  heart_failure.value = heart_failureValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
heart_failure.addEventListener("change", showElement);
