const abnorminal_pain1 = document.getElementById("abnorminal_pain1");
const abnorminal_pain2 = document.getElementById("abnorminal_pain2");
const abnorminal_pain3 = document.getElementById("abnorminal_pain3");

const score_abnorminal_pain = document.getElementById("score_abnorminal_pain");
const score_abnorminal_pain_label = document.getElementById(
  `score_abnorminal_pain_label`
);
const score_abnorminal_span = document.getElementById(`score_abnorminal_span`);

function toggleElementVisibility() {
  if (abnorminal_pain1.checked) {
    score_abnorminal_pain_label.style.display = "block";
    score_abnorminal_pain.setAttribute("required", "required");
    score_abnorminal_pain.style.display = "block";
    score_abnorminal_span.style.display = "block";
  } else {
    score_abnorminal_pain_label.style.display = "none";
    score_abnorminal_pain.removeAttribute("required");
    score_abnorminal_pain.style.display = "none";
    score_abnorminal_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

abnorminal_pain1.addEventListener("change", toggleElementVisibility);
abnorminal_pain2.addEventListener("change", toggleElementVisibility);
abnorminal_pain3.addEventListener("change", toggleElementVisibility);
