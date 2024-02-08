const cause_death = document.getElementById("cause_death");
const death_other1 = document.getElementById("death_other1");

function showElement() {
  if (cause_death.value === "96") {
    death_other1.style.display = "block";
  } else {
    death_other1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", cause_death.value);
}

// Check if there's a previously selected value in localStorage
const cause_deathValue = localStorage.getItem("selectedValue");

if (cause_deathValue) {
  cause_death.value = cause_deathValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
cause_death.addEventListener("change", showElement);
