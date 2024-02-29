const thromboembolic = document.getElementById("thromboembolic");
const sub_thromboembolic1 = document.getElementById("sub_thromboembolic1");

function showElement() {
  if (thromboembolic.value === "1") {
    sub_thromboembolic1.style.display = "block";
  } else {
    sub_thromboembolic1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", thromboembolic.value);
}

// Check if there's a previously selected value in localStorage
const thromboembolicValue = localStorage.getItem("selectedValue");

if (thromboembolicValue) {
  thromboembolic.value = thromboembolicValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
thromboembolic.addEventListener("change", showElement);
