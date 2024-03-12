const sexual_dysfunction1 = document.getElementById("sexual_dysfunction1");
const sexual_dysfunction2 = document.getElementById("sexual_dysfunction2");


const sexual_dysfunction_date_label = document.getElementById(
  `sexual_dysfunction_date_label`
);
const sexual_dysfunction_date = document.getElementById(
  `sexual_dysfunction_date`
);

function toggleElementVisibility() {
  if (sexual_dysfunction1.checked) {
    sexual_dysfunction_date_label.style.display = "block";
    sexual_dysfunction_date.style.display = "block";
    sexual_dysfunction_date.setAttribute("required", "required");
  } else {
    sexual_dysfunction_date_label.style.display = "none";
    sexual_dysfunction_date.style.display = "none";
    sexual_dysfunction_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

sexual_dysfunction1.addEventListener("change", toggleElementVisibility);
sexual_dysfunction2.addEventListener("change", toggleElementVisibility);

