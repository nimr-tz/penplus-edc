const retinopathy1 = document.getElementById(`retinopathy1`);
const retinopathy2 = document.getElementById(`retinopathy2`);

const retinopathy_date_label = document.getElementById(
  `retinopathy_date_label`
);
const retinopathy_date = document.getElementById(`retinopathy_date`);

function toggleElementVisibility() {
  if (retinopathy1.checked) {
    retinopathy_date_label.style.display = "block";
    retinopathy_date.style.display = "block";
    retinopathy_date.setAttribute("required", "required");
  } else {
    retinopathy_date_label.style.display = "none";
    retinopathy_date.style.display = "none";
    retinopathy_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

retinopathy1.addEventListener("change", toggleElementVisibility);
retinopathy2.addEventListener("change", toggleElementVisibility);
