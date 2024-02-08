const sub_heumatic = document.getElementById("sub_heumatic");
const heumatic_other = document.getElementById("heumatic_other");

function showElement() {
  if (sub_heumatic.value === "1") {
    heumatic_other.style.display = "block";
  } else {
    heumatic_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_heumatic.value);
}

// Check if there's a previously selected value in localStorage
const sub_heumaticValue = localStorage.getItem("selectedValue");

if (sub_heumaticValue) {
  sub_heumatic.value = sub_heumaticValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_heumatic.addEventListener("change", showElement);
