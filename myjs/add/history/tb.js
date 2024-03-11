const tb1 = document.getElementById(`tb1`);
const tb2 = document.getElementById(`tb2`);
const tb3 = document.getElementById(`tb3`);
const tb4 = document.getElementById(`tb4`);
const tb5 = document.getElementById(`tb5`);


const tb_year_label = document.getElementById(`tb_year_label`);
const tb_year = document.getElementById(`tb_year`);

function toggleElementVisibility() {
  if (tb1.checked || tb2.checked || tb3.checked) {
    tb_year_label.style.display = "block";
    tb_year.style.display = "block";
    tb_year.setAttribute("required", "required");
  } else {
    tb_year_label.style.display = "none";
    tb_year.style.display = "none";
    tb_year.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

tb1.addEventListener("change", toggleElementVisibility);
tb2.addEventListener("change", toggleElementVisibility);
tb3.addEventListener("change", toggleElementVisibility);
tb4.addEventListener("change", toggleElementVisibility);
tb5.addEventListener("change", toggleElementVisibility);

