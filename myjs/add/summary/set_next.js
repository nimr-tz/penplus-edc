const set_next1 = document.getElementById("set_next1");
const set_next2 = document.getElementById("set_next2");

const next_notes = document.getElementById("next_notes");
const next_appointment = document.getElementById("next_appointment");

function toggleElementVisibility() {
  if (set_next1.checked) {
    next_notes.style.display = "none";
    next_notes.setAttribute("required", "required");
    next_appointment.style.display = "none";
    next_appointment.setAttribute("required", "required");
  } else {
    next_notes.style.display = "none";
    next_notes.removeAttribute("required");
    next_appointment.style.display = "none";
    next_appointment.removeAttribute("required");
  }
}
set_next1.addEventListener("change", toggleElementVisibility);
set_next2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetRadio() {
  var unsetRadios = document.getElementsByName("set_next");
  unsetRadios.forEach(function (unsetRadio) {
    unsetRadio.checked = false;
  });
}
