const active_smoker1_1 = document.getElementById(`active_smoker1`);
const active_smoker2_1 = document.getElementById(`active_smoker2`);

const quit_smoking = document.getElementById(`quit_smoking`);
const quit_smoking_label = document.getElementById(`quit_smoking_label`);

function toggleElementVisibility() {
  if (active_smoker2_1.checked) {
    quit_smoking_label.style.display = "block";
    quit_smoking.setAttribute("required", "required");
    quit_smoking.style.display = "block";
  } else {
    quit_smoking_label.style.display = "none";
    quit_smoking.removeAttribute("required");
    quit_smoking.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

active_smoker1_1.addEventListener("change", toggleElementVisibility);
active_smoker2_1.addEventListener("change", toggleElementVisibility);
