const diagns_changed1 = document.getElementById("diagns_changed1");
const diagns_changed2 = document.getElementById("diagns_changed2");

const diagns_cardiac = document.getElementById(`diagns_cardiac`);
const diagns_cardiac1_1 = document.getElementById(`diagns_cardiac1`);

function toggleElementVisibility() {
  if (diagns_changed1.checked) {
    diagns_cardiac.style.display = "block";
    diagns_cardiac1_1.setAttribute("required", "required");
  } else {
    diagns_cardiac.style.display = "none";
    diagns_cardiac1_1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

diagns_changed1.addEventListener("change", toggleElementVisibility);
diagns_changed2.addEventListener("change", toggleElementVisibility);

