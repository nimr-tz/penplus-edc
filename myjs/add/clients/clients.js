const occupation1 = document.getElementById("occupation1");
const occupation2 = document.getElementById("occupation2");
const occupation3 = document.getElementById("occupation3");

const exposure = document.getElementById("exposure");

function toggleElementVisibility() {
  if (occupation1.checked) {
    exposure.style.display = "block";
  } else {
    exposure.style.display = "none";
  }
}

occupation1.addEventListener("change", toggleElementVisibility);
occupation2.addEventListener("change", toggleElementVisibility);
occupation3.addEventListener("change", toggleElementVisibility);


// Initial check
toggleElementVisibility();

function unsetOccupation() {
  var radioButtons = document.getElementsByName("occupation");
  radioButtons.forEach(function (radioButton) {
    radioButton.checked = false;
  });
}

function validateClientsForm() {
  var occupationOptions = document.getElementsByName("occupation");

  var occupationSelected = false;

  for (var i = 0; i < occupationOptions.length; i++) {
    if (occupationOptions[i].checked) {
      occupationSelected = true;
      break;
    }
  }

  if (!occupationSelected) {
    alert("Please select Option for 'Occupation Exposure'");
    return false;
  }

  return true;
}
