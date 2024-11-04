const relation1 = document.getElementById("relation1");
const relation2 = document.getElementById("relation2");
const relation3 = document.getElementById("relation3");
const relation4 = document.getElementById("relation4");
const relation5 = document.getElementById("relation5");
const relation96 = document.getElementById("relation96");


const relation_patient_other = document.getElementById("relation_patient");

function toggleElementVisibility() {
  if (relation96.checked) {
    relation_patient_other.style.display = "block";
    // addRequiredAttribute();
  } else {
    relation_patient_other.style.display = "none";
  }
}

relation1.addEventListener("change", toggleElementVisibility);
relation2.addEventListener("change", toggleElementVisibility);
relation3.addEventListener("change", toggleElementVisibility);
relation4.addEventListener("change", toggleElementVisibility);
relation5.addEventListener("change", toggleElementVisibility);
relation96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();


