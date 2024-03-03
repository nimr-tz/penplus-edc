// const economic_form = document.getElementById("economic");

// economic_form.addEventListener("submit", function (event) {
//   const economic_questions = ["income_household", "income_patient"];

//   let isValid = true;

//   economic_questions.forEach(function (question) {
//     const economic_radios = document.querySelectorAll(
//       'input[name="' + economic_questions + '"]'
//     );
//     let checked = false;
//     for (let i = 0; i < economic_radios.length; i++) {
//       if (economic_radios[i].checked) {
//         checked = true;
//         break;
//       }
//     }
//     if (!checked) {
//       isValid = false;
//       alert("Please select an option for " + economic_questions + ".");
//     }
//   });

//   if (!isValid) {
//     event.preventDefault();
//   }
// });
