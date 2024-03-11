// for (let i = 1; i <= 100; i++) {
//   const income_household2 = `income_household_${i}`;
//   const income_patient2 = `income_patient_${i}`;
//   const monthly_earn1 = `monthly_earn_${i}`;

//   const income_household11 = document.getElementById(income_household2);
//   const income_patient11 = document.getElementById(income_patient2);

//   const monthly_earn = document.getElementById(monthly_earn1);

//   if (income_household11.value === "5" && income_patient11.value === "5") {
//     var monthly_earn11 = 1;
//   } else {
//     var monthly_earn11 = 2;
//   }

//   function showElement() {
//     if (monthly_earn11.value === "1") {
//       monthly_earn.style.display = "none";
//     } else {
//       monthly_earn.style.display = "block";
//     }

//     // Save the selected value in localStorage
//     localStorage.setItem("selectedValue", monthly_earn11.value);
//   }

//   // Check if there's a previously selected value in localStorage
//   const monthly_earn11Value = localStorage.getItem("selectedValue");

//   if (monthly_earn11Value) {
//     monthly_earn11Value.value = monthly_earn11Value;
//   }

//   // Show element if Option 2 is selected
//   showElement();

//   // Listen for changes in the dropdown
//   monthly_earn11.addEventListener("change", showElement);
// }
