const other_complication1 = document.getElementById(`other_complication1`);
const other_complication2 = document.getElementById(`other_complication2`);

const specify_complication = document.getElementById(`specify_complication`);

function toggleElementVisibility() {
  if (other_complication1.checked) {
    specify_complication.style.display = "block";
    specify_complication.setAttribute("required", "required");
  } else {
    specify_complication.style.display = "none";
    specify_complication.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

other_complication1.addEventListener("change", toggleElementVisibility);
other_complication2.addEventListener("change", toggleElementVisibility);
