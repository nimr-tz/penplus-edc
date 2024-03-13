const other_support1 = document.getElementById("other_support1");
const other_support2 = document.getElementById("other_support2");

const support_specify1 = document.getElementById(`support_specify1`);
const support_specify = document.getElementById(`support_specify`);

function toggleElementVisibility() {
  if (other_support1.checked) {
    support_specify1.style.display = "block";
    support_specify.setAttribute("required", "required");
  } else {
    support_specify1.style.display = "none";
    support_specify.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

other_support1.addEventListener("change", toggleElementVisibility);
other_support2.addEventListener("change", toggleElementVisibility);
