const other_sickle1 = document.getElementById("other_sickle1");
const other_sickle2 = document.getElementById("other_sickle2");
const other_sickle3 = document.getElementById("other_sickle3");

const sickle_specify = document.getElementById("sickle_specify");
const sickle_specify_label = document.getElementById("sickle_specify_label");

function toggleElementVisibility() {
  if (other_sickle1.checked) {
    sickle_specify_label.style.display = "block";
    sickle_specify.style.display = "block";
    sickle_specify.setAttribute("required", "required");
  } else {
    sickle_specify_label.style.display = "none";
    sickle_specify.removeAttribute("required");
    sickle_specify.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

other_sickle1.addEventListener("change", toggleElementVisibility);
other_sickle2.addEventListener("change", toggleElementVisibility);
other_sickle3.addEventListener("change", toggleElementVisibility);
