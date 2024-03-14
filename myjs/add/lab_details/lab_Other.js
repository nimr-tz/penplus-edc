const lab_Other1 = document.getElementById("lab_Other1");
const lab_Other2 = document.getElementById("lab_Other2");

const lab_specify1 = document.getElementById("lab_specify1");
const lab_specify = document.getElementById("lab_specify");

function toggleElementVisibility() {
  if (lab_Other1.checked) {
    lab_specify1.style.display = "block";
    lab_specify.setAttribute("required", "required");
  } else {
    lab_specify1.style.display = "none";
    lab_specify.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

lab_Other1.addEventListener("change", toggleElementVisibility);
lab_Other2.addEventListener("change", toggleElementVisibility);

