const history_other1 = document.getElementById("history_other1");
const history_other2 = document.getElementById("history_other2");

const history_specify = document.getElementById(`history_specify`);

function toggleElementVisibility() {
  if (history_other1.checked) {
    history_specify.style.display = "block";
    history_specify.setAttribute("required", "required");
  } else {
    history_specify.style.display = "none";
    history_specify.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

history_other1.addEventListener("change", toggleElementVisibility);
history_other2.addEventListener("change", toggleElementVisibility);




