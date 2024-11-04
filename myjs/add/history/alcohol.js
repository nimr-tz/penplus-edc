const alcohol1 = document.getElementById(`alcohol1`);
const alcohol2 = document.getElementById(`alcohol2`);
const alcohol3 = document.getElementById(`alcohol3`);

const quantity1 = document.getElementById(`quantity1`);
const quantity = document.getElementById(`quantity`);
const alcohol_type = document.getElementById(`alcohol_type`);
const alcohol_type1 = document.getElementById(`alcohol_type1`);

function toggleElementVisibility() {
  if (alcohol1.checked) {
    alcohol_type.style.display = "block";
    alcohol_type1.setAttribute("required", "required");
    quantity1.style.display = "block";
    quantity.setAttribute("required", "required");
    quantity.style.display = "block";
  } else if (alcohol2.checked) {
    alcohol_type.style.display = "block";
    alcohol_type1.setAttribute("required", "required");
    quantity1.style.display = "block";
    quantity.setAttribute("required", "required");
    quantity.style.display = "block";
  } else {
    alcohol_type.style.display = "none";
    alcohol_type1.removeAttribute("required");
    quantity1.style.display = "none";
    quantity.removeAttribute("required");
    quantity.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

alcohol1.addEventListener("change", toggleElementVisibility);
alcohol2.addEventListener("change", toggleElementVisibility);
alcohol3.addEventListener("change", toggleElementVisibility);
