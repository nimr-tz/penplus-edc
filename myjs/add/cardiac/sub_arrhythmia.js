const sub_arrhythmia = document.getElementById("sub_arrhythmia");
const arrhythmia_other = document.getElementById("arrhythmia_other");

function showElement() {
  if (sub_arrhythmia.value === "96") {
    arrhythmia_other.style.display = "block";
  } else {
    arrhythmia_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_arrhythmia.value);
}

// Check if there's a previously selected value in localStorage
const sub_arrhythmiaValue = localStorage.getItem("selectedValue");

if (sub_arrhythmiaValue) {
  sub_arrhythmia.value = sub_arrhythmiaValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_arrhythmia.addEventListener("change", showElement);
