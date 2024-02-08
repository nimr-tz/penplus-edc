const arrhythmia = document.getElementById("arrhythmia");
const sub_arrhythmia1 = document.getElementById("sub_arrhythmia1");

function showElement() {
  if (arrhythmia.value === "1") {
    sub_arrhythmia1.style.display = "block";
  } else {
    sub_arrhythmia1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", arrhythmia.value);
}

// Check if there's a previously selected value in localStorage
const arrhythmiaValue = localStorage.getItem("selectedValue");

if (arrhythmiaValue) {
  arrhythmia.value = arrhythmiaValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
arrhythmia.addEventListener("change", showElement);
