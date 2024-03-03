const congenital = document.getElementById("congenital");
const sub_congenital1 = document.getElementById("sub_congenital1");

function showElement() {
  if (congenital.value === "1") {
    sub_congenital1.style.display = "block";
  } else {
    sub_congenital1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", congenital.value);
}

// Check if there's a previously selected value in localStorage
const congenitalValue = localStorage.getItem("selectedValue");

if (congenitalValue) {
  congenital.value = congenitalValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
congenital.addEventListener("change", showElement);
