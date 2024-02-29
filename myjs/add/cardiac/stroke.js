const stroke = document.getElementById("stroke");
const sub_stroke = document.getElementById("sub_stroke");

function showElement() {
  if (stroke.value === "1") {
    sub_stroke.style.display = "block";
  } else {
    sub_stroke.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", stroke.value);
}

// Check if there's a previously selected value in localStorage
const strokeValue = localStorage.getItem("selectedValue");

if (strokeValue) {
  stroke.value = strokeValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
stroke.addEventListener("change", showElement);
