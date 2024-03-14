const risk_hiv1 = document.getElementById(`risk_hiv1`);
const risk_hiv2 = document.getElementById(`risk_hiv2`);
const risk_hiv3 = document.getElementById(`risk_hiv3`);

const risk_hiv_date = document.getElementById(`risk_hiv_date`);
const risk_hiv_date_label = document.getElementById(`risk_hiv_date_label`);
const risk_art1_1 = document.getElementById(`risk_art1`);
const risk_art = document.getElementById(`risk_art`);

function toggleElementVisibility() {
  if (risk_hiv1.checked) {
    risk_hiv_date_label.style.display = "block";
    risk_hiv_date.style.display = "block";
    risk_hiv_date.setAttribute("required", "required");
    risk_art.style.display = "block";
    risk_art1_1.setAttribute("required", "required");
  } else if (risk_hiv2.checked) {
    risk_hiv_date_label.style.display = "block";
    risk_hiv_date.style.display = "block";
    risk_hiv_date.setAttribute("required", "required");
    risk_art.style.display = "none";
    risk_art1_1.removeAttribute("required");
  } else {
    risk_hiv_date_label.style.display = "none";
    risk_hiv_date.style.display = "none";
    risk_hiv_date.removeAttribute("required");
    risk_art.style.display = "none";
    risk_art1_1.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

risk_hiv1.addEventListener("change", toggleElementVisibility);
risk_hiv2.addEventListener("change", toggleElementVisibility);
risk_hiv3.addEventListener("change", toggleElementVisibility);
