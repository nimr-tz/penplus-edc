const alcohol_type1_1 = document.getElementById("alcohol_type1");
const alcohol_type2_1 = document.getElementById("alcohol_type2");
const alcohol_type3_1 = document.getElementById("alcohol_type3");
const alcohol_type4_1 = document.getElementById("alcohol_type96");

const alcohol_other = document.getElementById(`alcohol_other`);

function toggleElementVisibility() {
  if (alcohol_type4_1.checked) {
    alcohol_other.style.display = "block";
    alcohol_other.setAttribute("required", "required");
  } else {
    alcohol_other.style.display = "none";
    alcohol_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

alcohol_type1_1.addEventListener("change", toggleElementVisibility);
alcohol_type2_1.addEventListener("change", toggleElementVisibility);
alcohol_type3_1.addEventListener("change", toggleElementVisibility);
alcohol_type4_1.addEventListener("change", toggleElementVisibility);

function unsetAlcohol_type() {
  var unsetAlcohol_types = document.getElementsByName("alcohol_type");
  unsetAlcohol_types.forEach(function (unsetAlcohol_type) {
    unsetAlcohol_type.checked = false;
  });
}
