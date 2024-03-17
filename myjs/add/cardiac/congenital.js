const congenital1 = document.getElementById("congenital1");
const congenital2 = document.getElementById("congenital2");

const sub_congenital = document.getElementById("sub_congenital");
const sub_congenital1_1 = document.getElementById("sub_congenital1");


function toggleElementVisibility() {
  if (congenital1.checked) {
    sub_congenital.style.display = "block";
    sub_congenital1_1.setAttribute("required", "required");
  } else {
    sub_congenital.style.display = "none";
    sub_congenital1_1.removeAttribute("required");
  }
}

congenital1.addEventListener("change", toggleElementVisibility);
congenital2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

