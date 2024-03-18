const cause_death1 = document.getElementById("cause_death1");
const cause_death2 = document.getElementById("cause_death2");
const cause_death96 = document.getElementById("cause_death96");

const death_other = document.getElementById("death_other");

function toggleElementVisibility() {
  if (cause_death96.checked) {
    death_other.style.display = "block";
    death_other.setAttribute("required", "required");
  } else {
    death_other.style.display = "none";
    death_other.removeAttribute("required");
  }
}

cause_death1.addEventListener("change", toggleElementVisibility);
cause_death2.addEventListener("change", toggleElementVisibility);
cause_death96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetCause_death() {
  var unsetCause_deaths = document.getElementsByName("cause_death");
  unsetCause_deaths.forEach(function (unsetCause_death) {
    unsetCause_death.checked = false;
  });
}
