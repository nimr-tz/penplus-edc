const chemistry_test98 = document.getElementById("chemistry_test98");
const chemistry_test99 = document.getElementById("chemistry_test99");

const hide_chemistry_test1 = document.getElementById("hide_chemistry_test1");

const na = document.getElementById("na");
const k = document.getElementById("k");
const bun = document.getElementById("bun");
const cre = document.getElementById("cre");
const bnp = document.getElementById("bnp");
const inr = document.getElementById("inr");
const other_chemistry7_7 = document.getElementById("other_chemistry7_7");
const other_chemistry4_4 = document.getElementById("other_chemistry4_4");

function toggleElementVisibility() {
  if (chemistry_test98.checked) {
    hide_chemistry_test1.style.display = "block";
    na.setAttribute("required", "required");
    k.setAttribute("required", "required");
    bun.setAttribute("required", "required");
    cre.setAttribute("required", "required");
    bnp.setAttribute("required", "required");
    inr.setAttribute("required", "required");
    other_chemistry7_7.style.display = "block";
    other_chemistry4_4.setAttribute("required", "required");
  } else {
    hide_chemistry_test1.style.display = "none";
    na.removeAttribute("required");
    k.removeAttribute("required");
    bun.removeAttribute("required");
    cre.removeAttribute("required");
    bnp.removeAttribute("required");
    inr.removeAttribute("required");
    other_chemistry7_7.style.display = "none";
    other_chemistry4_4.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

chemistry_test98.addEventListener("change", toggleElementVisibility);
chemistry_test99.addEventListener("change", toggleElementVisibility);
