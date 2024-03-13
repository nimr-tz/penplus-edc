const chest_pain1 = document.getElementById("chest_pain1");
const chest_pain2 = document.getElementById("chest_pain2");
const chest_pain3 = document.getElementById("chest_pain3");


const score_chest_pain = document.getElementById("score_chest_pain");
const score_chest_pain_labale = document.getElementById(
  `score_chest_pain_labale`
);
const score_chest_pain_span = document.getElementById(`score_chest_pain_span`);

function toggleElementVisibility() {
  if (chest_pain1.checked) {
    score_chest_pain_labale.style.display = "block";
    score_chest_pain.setAttribute("required", "required");
    score_chest_pain.style.display = "block";
    score_chest_pain_span.style.display = "block";
  } else {
    score_chest_pain_labale.style.display = "none";
    score_chest_pain.removeAttribute("required");
    score_chest_pain.style.display = "none";
    score_chest_pain_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

chest_pain1.addEventListener("change", toggleElementVisibility);
chest_pain2.addEventListener("change", toggleElementVisibility);
chest_pain3.addEventListener("change", toggleElementVisibility);

