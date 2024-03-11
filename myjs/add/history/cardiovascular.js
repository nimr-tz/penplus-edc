const cardiovascular1 = document.getElementById(`cardiovascular1`);
const cardiovascular2 = document.getElementById(`cardiovascular2`);

const cardiovascular_date = document.getElementById(`cardiovascular_date`);

function toggleElementVisibility() {
  if (alcohol1.checked) {
    cardiovascular_date.style.display = "block";
    cardiovascular_date.setAttribute("required", "required");    
  } else {
    cardiovascular_date.style.display = "block";
    cardiovascular_date.setAttribute("required", "required");
  }
}

// Initial check
toggleElementVisibility();

cardiovascular1.addEventListener("change", toggleElementVisibility);
cardiovascular1.addEventListener("change", toggleElementVisibility);
