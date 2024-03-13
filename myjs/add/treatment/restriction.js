const restriction_other1 = document.getElementById("restriction_other1");
const restriction_other2 = document.getElementById("restriction_other2");

const restriction_specify1 = document.getElementById("restriction_specify1");
const restriction_specify = document.getElementById(`restriction_specify`);

function toggleElementVisibility() {
  if (restriction_other1.checked) {
    restriction_specify1.style.display = "block";
    restriction_specify.setAttribute("required", "required");
  } else {
    restriction_specify1.style.display = "none";
    restriction_specify1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

restriction_other1.addEventListener("change", toggleElementVisibility);
restriction_other2.addEventListener("change", toggleElementVisibility);
