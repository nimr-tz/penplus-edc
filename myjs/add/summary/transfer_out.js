const transfer_out1 = document.getElementById("transfer_out1");
const transfer_out2 = document.getElementById("transfer_out2");
const transfer_out961 = document.getElementById("transfer_out96");

const transfer_other = document.getElementById("transfer_other");

function toggleElementVisibility() {
  if (transfer_out961.checked) {
    transfer_other.style.display = "block";
    transfer_other.setAttribute("required", "required");
  } else {
    transfer_other.style.display = "none";
    transfer_other.removeAttribute("required");
  }
}

transfer_out1.addEventListener("change", toggleElementVisibility);
transfer_out2.addEventListener("change", toggleElementVisibility);
transfer_out961.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();


function unsetRadio() {
  var unsetRadios = document.getElementsByName("transfer_out");
  unsetRadios.forEach(function (unsetRadio) {
    unsetRadio.checked = false;
  });
}
