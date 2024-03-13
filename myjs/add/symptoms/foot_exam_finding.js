const foot_exam_finding1 = document.getElementById("foot_exam_finding1");
const foot_exam_finding2 = document.getElementById("foot_exam_finding2");

const foot_exam_other = document.getElementById("foot_exam_other");
const foot_exam_other_label = document.getElementById("foot_exam_other_label");

function toggleElementVisibility() {
  if (foot_exam_finding2.checked) {
    foot_exam_other_label.style.display = "block";
    foot_exam_other.style.display = "block";
    foot_exam_other.setAttribute("required", "required");
  } else {
    foot_exam_other_label.style.display = "none";
    foot_exam_other.style.display = "none";
    foot_exam_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

foot_exam_finding1.addEventListener("change", toggleElementVisibility);
foot_exam_finding2.addEventListener("change", toggleElementVisibility);


function unsetFootExam() {
  var unsetRadios = document.getElementsByName("foot_exam_finding");
  unsetRadios.forEach(function (unsetRadio) {
    unsetRadio.checked = false;
  });
}
