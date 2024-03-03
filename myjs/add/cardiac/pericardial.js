const pericardial = document.getElementById("pericardial");
const sub_pericardial1 = document.getElementById("sub_pericardial1");

function showElement() {
  if (pericardial.value === "1") {
    sub_pericardial1.style.display = "block";
  } else {
    sub_pericardial1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", pericardial.value);
}

// Check if there's a previously selected value in localStorage
const pericardialValue = localStorage.getItem("selectedValue");

if (pericardialValue) {
  pericardial.value = pericardialValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
pericardial.addEventListener("change", showElement);
