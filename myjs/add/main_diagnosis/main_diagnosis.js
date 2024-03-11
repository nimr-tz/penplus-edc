const other1 = document.getElementById(`other1`);
const other2 = document.getElementById(`other2`);

const other_diseases = document.getElementById(`other_diseases`);

function toggleElementVisibility() {
  if (other1.checked) {
    other_diseases.style.display = "block";
    other_diseases.setAttribute("required", "required");
  } else {
    other_diseases.style.display = "none";
    other_diseases.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

other1.addEventListener("change", toggleElementVisibility);
other2.addEventListener("change", toggleElementVisibility);
