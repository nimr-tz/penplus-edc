const joints1 = document.getElementById("joints1");
const joints2 = document.getElementById("joints2");
const joints3 = document.getElementById("joints3");

const spescify_joints1 = document.getElementById("spescify_joints1");
const spescify_joints = document.getElementById("spescify_joints");
const score_joints = document.getElementById("score_joints");
const score_joints_label = document.getElementById(`score_joints_label`);

const score_joints_span = document.getElementById(`score_joints_span`);

function toggleElementVisibility() {
  if (joints1.checked) {
    spescify_joints1.style.display = "block";
    spescify_joints.setAttribute("required", "required");
    score_joints_label.style.display = "block";
    score_joints.style.display = "block";
    score_joints.setAttribute("required", "required");
    score_joints_span.style.display = "block";
  } else {
    spescify_joints1.style.display = "none";
    spescify_joints.removeAttribute("required");
    score_joints_label.style.display = "none";
    score_joints.style.display = "none";
    score_joints.removeAttribute("required");
    score_joints_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

joints1.addEventListener("change", toggleElementVisibility);
joints2.addEventListener("change", toggleElementVisibility);
joints3.addEventListener("change", toggleElementVisibility);
