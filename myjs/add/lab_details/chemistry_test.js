const chemistry_test1 = document.getElementById("chemistry_test1");
const chemistry_test2 = document.getElementById("chemistry_test2");

const hide_chemistry_test = document.getElementById("hide_chemistry_test");

const na = document.getElementById("na");
const k = document.getElementById("k");
const bun = document.getElementById("bun");
const cre = document.getElementById("cre");
const bnp = document.getElementById("bnp");
const inr = document.getElementById("inr");
const other_chemistry = document.getElementById("other_chemistry");
const other_chemistry1 = document.getElementById("other_chemistry1");
const other_chemistry2 = document.getElementById("other_chemistry2");

function toggleElementVisibility() {
  if (chemistry_test1.checked) {
    hide_chemistry_test.style.display = "block";
    na.setAttribute("required", "required");
    k.setAttribute("required", "required");
    bun.setAttribute("required", "required");
    cre.setAttribute("required", "required");
    bnp.setAttribute("required", "required");
    inr.setAttribute("required", "required");
    other_chemistry.style.display = "block";
    other_chemistry1.setAttribute("required", "required");
  } else {
    hide_chemistry_test.style.display = "none";
    na.removeAttribute("required");
    k.removeAttribute("required");
    bun.removeAttribute("required");
    cre.removeAttribute("required");
    bnp.removeAttribute("required");
    inr.removeAttribute("required");
    other_chemistry.style.display = "none";
    other_chemistry1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

chemistry_test1.addEventListener("change", toggleElementVisibility);
chemistry_test2.addEventListener("change", toggleElementVisibility);
