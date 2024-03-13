const diagns_changed1 = document.getElementById("diagns_changed1");
const diagns_changed2 = document.getElementById("diagns_changed2");

const diagns_diabetes2_2 = document.getElementById(`diagns_diabetes`);
const diagns_diabetes1_1 = document.getElementById(`diagns_diabetes1`);

const diagns_cardiac2_2 = document.getElementById(`diagns_cardiac`);
const diagns_cardiac1_1 = document.getElementById(`diagns_cardiac1`);

const diagns_sickle2_2 = document.getElementById(`diagns_sickle`);
const diagns_sickle1_1 = document.getElementById(`diagns_sickle1`);

function toggleElementVisibility() {
  if (diagns_changed1.checked) {
    diagns_cardiac2_2.style.display = "block";
    diagns_cardiac1_1.setAttribute("required", "required");
    diagns_diabetes2_2.style.display = "block";
    diagns_diabetes1_1.setAttribute("required", "required");
    diagns_sickle2_2.style.display = "block";
    diagns_sickle1_1.setAttribute("required", "required");
  } else {
    diagns_cardiac2_2.style.display = "none";
    diagns_cardiac1_1.removeAttribute("required");
    diagns_diabetes2_2.style.display = "none";
    diagns_diabetes1_1.removeAttribute("required");
    diagns_sickle2_2.style.display = "none";
    diagns_sickle1_1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

diagns_changed1.addEventListener("change", toggleElementVisibility);
diagns_changed2.addEventListener("change", toggleElementVisibility);
