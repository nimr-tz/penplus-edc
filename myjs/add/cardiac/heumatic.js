const heumatic = document.getElementById("heumatic");
const sub_heumatic1 = document.getElementById("sub_heumatic1");

function showElement() {
  if (heumatic.value === "1") {
    sub_heumatic1.style.display = "block";
  } else {
    sub_heumatic1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", heumatic.value);
}

// Check if there's a previously selected value in localStorage
const heumaticValue = localStorage.getItem("selectedValue");

if (heumaticValue) {
  heumatic.value = heumaticValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
heumatic.addEventListener("change", showElement);
