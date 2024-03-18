const outcome1 = document.getElementById("outcome1");
const outcome2 = document.getElementById("outcome2");
const outcome3 = document.getElementById("outcome3");
const outcome4 = document.getElementById("outcome4");
const outcome5 = document.getElementById("outcome5");
const outcome98 = document.getElementById("outcome98");


const transfer_out1_1 = document.getElementById("transfer_out1");
const transfer_out = document.getElementById("transfer_out");
const cause_death1_1 = document.getElementById("cause_death1");
const cause_death = document.getElementById("cause_death");
const comments = document.getElementById("comments");
const death_date_label = document.getElementById("death_date_label");
const death_date = document.getElementById("death_date");
const transfer_out_date_label = document.getElementById(
  "transfer_out_date_label"
);
const transfer_out_date = document.getElementById("transfer_out_date");


function toggleElementVisibility() {
  if (outcome4.checked) {
    transfer_out1_1.setAttribute("required", "required");
    transfer_out.style.display = "block";
    cause_death1_1.setAttribute("required", "required");
    cause_death.style.display = "none";
    // comments.style.display = "block";
    // comments.setAttribute("required", "required");
    transfer_out_date_label.style.display = "block";
    transfer_out_date.style.display = "block";
    transfer_out_date.setAttribute("required", "required");
    death_date_label.style.display = "none";
    death_date.style.display = "none";
    death_date.removeAttribute("required");
  } else if (outcome5.checked) {
    transfer_out1_1.removeAttribute("required");
    transfer_out.style.display = "none";
    cause_death1_1.removeAttribute("required");
    cause_death.style.display = "block";
    // comments.style.display = "block";
    // comments.setAttribute("required", "required");
    transfer_out_date_label.style.display = "none";
    transfer_out_date.style.display = "none";
    transfer_out_date.removeAttribute("required");
    death_date_label.style.display = "block";
    death_date.style.display = "block";
    death_date.setAttribute("required", "required");
  } else {
    transfer_out1_1.removeAttribute("required");
    transfer_out.style.display = "none";
    cause_death1_1.removeAttribute("required");
    cause_death.style.display = "none";
    // comments.style.display = "none";
    transfer_out_date_label.style.display = "none";
    transfer_out_date.style.display = "none";
    transfer_out_date.removeAttribute("required");
    death_date_label.style.display = "none";
    death_date.style.display = "none";
    death_date.removeAttribute("required");
  }
}
outcome1.addEventListener("change", toggleElementVisibility);
outcome2.addEventListener("change", toggleElementVisibility);
outcome3.addEventListener("change", toggleElementVisibility);
outcome4.addEventListener("change", toggleElementVisibility);
outcome5.addEventListener("change", toggleElementVisibility);
outcome98.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
