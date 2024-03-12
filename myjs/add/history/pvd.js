const pvd1 = document.getElementById(`pvd1`);
const pvd2 = document.getElementById(`pvd2`);

const pvd_date_label = document.getElementById(`pvd_date_label`);
const pvd_date = document.getElementById(`pvd_date`);

function toggleElementVisibility() {
  if (pvd1.checked) {
    pvd_date_label.style.display = "block";
    pvd_date.style.display = "block";
    pvd_date.setAttribute("required", "required");
  } else {
    pvd_date_label.style.display = "none";
    pvd_date.style.display = "none";
    pvd_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

pvd1.addEventListener("change", toggleElementVisibility);
pvd2.addEventListener("change", toggleElementVisibility);


