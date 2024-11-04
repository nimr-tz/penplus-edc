const chw_name1 = document.getElementById(`chw_name1`);
const chw_name2 = document.getElementById(`chw_name2`);

const chw = document.getElementById(`chw`);

function toggleElementVisibility() {
  if (chw_name1.checked) {
    chw.style.display = "block";
    chw.setAttribute("required", "required");
  } else {
    chw.style.display = "none";
    chw.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

chw_name1.addEventListener("change", toggleElementVisibility);
chw_name2.addEventListener("change", toggleElementVisibility);
