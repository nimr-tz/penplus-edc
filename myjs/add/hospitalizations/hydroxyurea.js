const hydroxyurea1 = document.getElementById("hydroxyurea1");
const hydroxyurea2 = document.getElementById("hydroxyurea2");
const hydroxyurea3 = document.getElementById("hydroxyurea3");

const hydroxyurea_date1 = document.getElementById("hydroxyurea_date1");
const hydroxyurea_date = document.getElementById("hydroxyurea_date");

const hydroxyurea_dose1 = document.getElementById("hydroxyurea_dose1");
const hydroxyurea_dose = document.getElementById("hydroxyurea_dose");

function toggleElementVisibility() {
  if (hydroxyurea1.checked) {
    hydroxyurea_dose1.style.display = "block";
    hydroxyurea_dose.setAttribute("required", "required");
    hydroxyurea_date1.style.display = "block";
    hydroxyurea_date.setAttribute("required", "required");
  } else {
    hydroxyurea_dose1.style.display = "none";
    hydroxyurea_dose.removeAttribute("required");
    hydroxyurea_date1.style.display = "none";
    hydroxyurea_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hydroxyurea1.addEventListener("change", toggleElementVisibility);
hydroxyurea2.addEventListener("change", toggleElementVisibility);
hydroxyurea3.addEventListener("change", toggleElementVisibility);


