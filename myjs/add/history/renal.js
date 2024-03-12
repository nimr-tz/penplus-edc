const renal1 = document.getElementById(`renal1`);
const renal2 = document.getElementById(`renal2`);

const renal_date_label = document.getElementById(`renal_date_label`);
const renal_date = document.getElementById(`renal_date`);

function toggleElementVisibility() {
  if (renal1.checked) {
    renal_date_label.style.display = "block";
    renal_date.style.display = "block";
    renal_date.setAttribute("required", "required");
  } else {
    renal_date_label.style.display = "none";
    renal_date.style.display = "none";
    renal_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

renal1.addEventListener("change", toggleElementVisibility);
renal2.addEventListener("change", toggleElementVisibility);

