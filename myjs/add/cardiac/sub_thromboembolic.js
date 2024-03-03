const sub_thromboembolic = document.getElementById("sub_thromboembolic");
const thromboembolic_other = document.getElementById("thromboembolic_other");

function showElement() {
  if (sub_thromboembolic.value === "96") {
    thromboembolic_other.style.display = "block";
  } else {
    thromboembolic_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_thromboembolic.value);
}

// Check if there's a previously selected value in localStorage
const sub_thromboembolicValue = localStorage.getItem("selectedValue");

if (sub_thromboembolicValue) {
  sub_thromboembolic.value = sub_thromboembolicValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_thromboembolic.addEventListener("change", showElement);
