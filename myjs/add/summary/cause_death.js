const cause_death1 = document.getElementById("cause_death1");
const cause_death2 = document.getElementById("cause_death2");
const cause_death96 = document.getElementById("cause_death96");

const death_other = document.getElementById("death_other");

function toggleElementVisibility() {
  if (cause_death96.checked) {
    death_other.style.display = "block";
  } else {
    death_other.style.display = "none";
  }
}

cause_death1.addEventListener("change", toggleElementVisibility);
cause_death2.addEventListener("change", toggleElementVisibility);
cause_death96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
