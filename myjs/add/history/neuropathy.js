const neuropathy1 = document.getElementById(`neuropathy1`);
const neuropathy2 = document.getElementById(`neuropathy2`);

const neuropathy_date_label = document.getElementById(`neuropathy_date_label`);
const neuropathy_date = document.getElementById(`neuropathy_date`);

function toggleElementVisibility() {
  if (neuropathy1.checked) {
    neuropathy_date_label.style.display = "block";
    neuropathy_date.style.display = "block";
    neuropathy_date.setAttribute("required", "required");
  } else {
    neuropathy_date_label.style.display = "none";
    neuropathy_date.style.display = "none";
    neuropathy_date.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

neuropathy1.addEventListener("change", toggleElementVisibility);
neuropathy2.addEventListener("change", toggleElementVisibility);

