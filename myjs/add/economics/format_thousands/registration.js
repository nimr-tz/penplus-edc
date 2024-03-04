const registration = document.getElementById("registration");

registration.addEventListener("input", function () {
  // Remove non-numeric characters from the input value
  let value = this.value.replace(/\D/g, "");

  // Add thousands separators
  value = addThousandsSeparator(value);

  // Update the input field value
  this.value = value;
});

// Function to add thousands separator to a number
function addThousandsSeparator(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
