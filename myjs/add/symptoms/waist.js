const waist1 = document.getElementById("waist1");
const waist2 = document.getElementById("waist2");
const waist3 = document.getElementById("waist3");

const score_waist = document.getElementById("score_waist");
const score_waist_label = document.getElementById(`score_waist_label`);

const score_waist_span = document.getElementById(`score_waist_span`);

function toggleElementVisibility() {
  if (waist1.checked) {
    score_waist_label.style.display = "block";
    score_waist.setAttribute("required", "required");
    score_waist.style.display = "block";
    score_waist_span.style.display = "block";
  } else {
    score_waist_label.style.display = "none";
    score_waist.removeAttribute("required");
    score_waist.style.display = "none";
    score_waist_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

waist1.addEventListener("change", toggleElementVisibility);
waist2.addEventListener("change", toggleElementVisibility);
waist3.addEventListener("change", toggleElementVisibility);
