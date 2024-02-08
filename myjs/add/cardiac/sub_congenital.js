const sub_congenital = document.getElementById("sub_congenital");
const congenital_other = document.getElementById("congenital_other");

function showElement() {
  if (sub_congenital.value === "96") {
    congenital_other.style.display = "block";
  } else {
    congenital_other.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", sub_congenital.value);
}

// Check if there's a previously selected value in localStorage
const sub_congenitalValue = localStorage.getItem("selectedValue");

if (sub_congenitalValue) {
  sub_congenital.value = sub_congenitalValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
sub_congenital.addEventListener("change", showElement);
