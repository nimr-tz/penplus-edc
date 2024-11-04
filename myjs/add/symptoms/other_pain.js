const other_pain1 = document.getElementById("other_pain1");
const other_pain2 = document.getElementById("other_pain2");
const other_pain3 = document.getElementById("other_pain3");

const spescify_other_pain1 = document.getElementById("spescify_other_pain1");
const spescify_other_pain = document.getElementById("spescify_other_pain");
const score_other_pain = document.getElementById("score_other_pain");
const score_other_pain_label = document.getElementById(
  `score_other_pain_label`
);

const score_other_pain_span = document.getElementById(`score_other_pain_span`);

function toggleElementVisibility() {
  if (other_pain1.checked) {
    spescify_other_pain1.style.display = "block";
    spescify_other_pain.setAttribute("required", "required");
    score_other_pain_label.style.display = "block";
    score_other_pain.style.display = "block";
    score_other_pain.setAttribute("required", "required");
    score_other_pain_span.style.display = "block";
  } else {
    spescify_other_pain1.style.display = "none";
    spescify_other_pain.removeAttribute("required");
    score_other_pain_label.style.display = "none";
    score_other_pain.style.display = "none";
    score_other_pain.removeAttribute("required");
    score_other_pain_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

other_pain1.addEventListener("change", toggleElementVisibility);
other_pain2.addEventListener("change", toggleElementVisibility);
other_pain3.addEventListener("change", toggleElementVisibility);
