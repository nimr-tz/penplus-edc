const upper_arms1 = document.getElementById("upper_arms1");
const upper_arms2 = document.getElementById("upper_arms2");
const upper_arms3 = document.getElementById("upper_arms3");



const score_upper_arms = document.getElementById("score_upper_arms");
const score_upper_arms_label = document.getElementById(
  `score_upper_arms_label`
);

const score_upper_arms_span = document.getElementById(`score_upper_arms_span`);

function toggleElementVisibility() {
  if (upper_arms1.checked) {
    score_upper_arms_label.style.display = "block";
    score_upper_arms.setAttribute("required", "required");
    score_upper_arms.style.display = "block";
    score_upper_arms_span.style.display = "block";
  } else {
    score_upper_arms_label.style.display = "none";
    score_upper_arms.removeAttribute("required");
    score_upper_arms.style.display = "none";
    score_upper_arms_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

upper_arms1.addEventListener("change", toggleElementVisibility);
upper_arms2.addEventListener("change", toggleElementVisibility);
upper_arms3.addEventListener("change", toggleElementVisibility);
