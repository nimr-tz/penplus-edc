const foot_exam1 = document.getElementById("foot_exam1");
const foot_exam2 = document.getElementById("foot_exam2");

const foot_exam_category = document.getElementById("foot_exam_category");
const foot_exam_finding1_1 = document.getElementById("foot_exam_finding1");

function toggleElementVisibility() {
  if (foot_exam1.checked) {
    foot_exam_category.style.display = "block";
    foot_exam_finding1_1.setAttribute("required", "required");
  } else {
    foot_exam_category.style.display = "none";
    foot_exam_finding1_1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

foot_exam1.addEventListener("change", toggleElementVisibility);
foot_exam2.addEventListener("change", toggleElementVisibility);
