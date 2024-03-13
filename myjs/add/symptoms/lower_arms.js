const lower_arms1 = document.getElementById("lower_arms1");
const lower_arms2 = document.getElementById("lower_arms2");
const lower_arms3 = document.getElementById("lower_arms3");

const score_lower_arms = document.getElementById("score_lower_arms");
const score_lower_arms_label = document.getElementById(
  `score_lower_arms_label`
);

const score_lower_arms_span = document.getElementById(`score_lower_arms_span`);

function toggleElementVisibility() {
  if (lower_arms1.checked) {
    score_lower_arms_label.style.display = "block";
    score_lower_arms.setAttribute("required", "required");
    score_lower_arms.style.display = "block";
    score_lower_arms_span.style.display = "block";
  } else {
    score_lower_arms_label.style.display = "none";
    score_lower_arms.removeAttribute("required");
    score_lower_arms.style.display = "none";
    score_lower_arms_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

lower_arms1.addEventListener("change", toggleElementVisibility);
lower_arms2.addEventListener("change", toggleElementVisibility);
lower_arms3.addEventListener("change", toggleElementVisibility);
