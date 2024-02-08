const outcome = document.getElementById("outcome");
const transfer_out1 = document.getElementById("transfer_out1");
const cause_death1 = document.getElementById("cause_death1");

function showElement() {
  if (outcome.value === "4") {
    transfer_out1.style.display = "block";
    cause_death1.style.display = "none";
  } else if (outcome.value === "5") {
    transfer_out1.style.display = "none";
    cause_death1.style.display = "block";
  } else {
    transfer_out1.style.display = "none";
    cause_death1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", outcome.value);
}

// Check if there's a previously selected value in localStorage
const outcomeValue = localStorage.getItem("selectedValue");

if (outcomeValue) {
  outcome.value = outcomeValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
outcome.addEventListener("change", showElement);
