const sub_pericardial = document.getElementById("sub_pericardial");
const pericardial_other = document.getElementById("pericardial_other");

function showElement() {
  if (sub_pericardial.value === "96") {
    pericardial_other.style.display = "block";
  } else {
    pericardial_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_pericardial.value);
}

// Check if there's a previously selected value in localStorage
const sub_pericardialValue = localStorage.getItem("selectedValue");

if (sub_pericardialValue) {
  sub_pericardial.value = sub_pericardialValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_pericardial.addEventListener("change", showElement);
