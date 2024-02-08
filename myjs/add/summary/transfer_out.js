const transfer_out = document.getElementById("transfer_out");
const transfer_other1 = document.getElementById("transfer_other1");

function showElement() {
  if (transfer_out.value === "96") {
    transfer_other1.style.display = "block";
  } else {
    transfer_other1.style.display = "none";
  }

  // Save the selected value in localStorage
  localStorage.setItem("selectedValue", transfer_out.value);
}

// Check if there's a previously selected value in localStorage
const transfer_outValue = localStorage.getItem("selectedValue");

if (transfer_outValue) {
  transfer_out.value = transfer_outValue;
}

// Show element if Option 2 is selected
showElement();

// Listen for changes in the dropdown
transfer_out.addEventListener("change", showElement);
