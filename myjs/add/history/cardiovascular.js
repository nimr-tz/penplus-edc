const cardiovascular1 = document.getElementById(`cardiovascular1`);
const cardiovascular2 = document.getElementById(`cardiovascular2`);

const cardiovascular_date_label = document.getElementById(
  `cardiovascular_date_label`
);
const cardiovascular_date = document.getElementById(`cardiovascular_date`);

function toggleElementVisibility() {
  if (cardiovascular1.checked) {
    cardiovascular_date_label.style.display = "block";
    cardiovascular_date.style.display = "block";
    cardiovascular_date.setAttribute("required", "required");
  } else {
    cardiovascular_date_label.style.display = "none";
    cardiovascular_date.style.display = "none";
    cardiovascular_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

cardiovascular1.addEventListener("change", toggleElementVisibility);
cardiovascular2.addEventListener("change", toggleElementVisibility);
