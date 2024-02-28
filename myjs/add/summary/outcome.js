const outcome1 = document.getElementById("outcome1");
const outcome2 = document.getElementById("outcome2");
const outcome3 = document.getElementById("outcome3");
const outcome4 = document.getElementById("outcome4");
const outcome5 = document.getElementById("outcome5");

const transfer_out = document.getElementById("transfer_out");
const cause_death = document.getElementById("cause_death");
const next_notes = document.getElementById("next_notes");
const next_appointment = document.getElementById("next_appointment");
const comments = document.getElementById("comments");

function toggleElementVisibility() {
  if (outcome4.checked) {
    transfer_out.style.display = "block";
    cause_death.style.display = "none";
    next_notes.style.display = "block";
    next_appointment.style.display = "block";
    comments.style.display = "none";
  } else if (outcome5.checked) {
    transfer_out.style.display = "none";
    cause_death.style.display = "block";
    next_notes.style.display = "none";
    next_appointment.style.display = "none";
    comments.style.display = "block";
  } else {
    transfer_out.style.display = "none";
    cause_death.style.display = "none";
    next_notes.style.display = "block";
    next_appointment.style.display = "block";
    comments.style.display = "none";
  }
}
outcome1.addEventListener("change", toggleElementVisibility);
outcome2.addEventListener("change", toggleElementVisibility);
outcome3.addEventListener("change", toggleElementVisibility);
outcome4.addEventListener("change", toggleElementVisibility);
outcome5.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
