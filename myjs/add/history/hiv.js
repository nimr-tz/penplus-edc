const hiv1 = document.getElementById(`hiv1`);
const hiv2 = document.getElementById(`hiv2`);
const hiv3 = document.getElementById(`hiv3`);


const hiv_test = document.getElementById(`hiv_test`); ;
const hiv_test_label = document.getElementById(`hiv_test_label`);
const art = document.getElementById(`art`);

function toggleElementVisibility() {
  if (hiv1.checked) {
    hiv_test.style.display = "block";
    hiv_test_label.style.display = "block";
    hiv_test.setAttribute("required", "required");
    art.style.display = "block";
  }else if (hiv2.checked) {
    hiv_test.style.display = "block";
    hiv_test_label.style.display = "block";
    hiv_test.setAttribute("required", "required");
    art.style.display = "none";
  } else {
    hiv_test.style.display = "none";
    hiv_test_label.style.display = "none";
    hiv_test.removeAttribute("required");
    art.style.display = "none";
  }
}
// Initial check
toggleElementVisibility();

hiv1.addEventListener("change", toggleElementVisibility);
hiv2.addEventListener("change", toggleElementVisibility);
hiv3.addEventListener("change", toggleElementVisibility);

