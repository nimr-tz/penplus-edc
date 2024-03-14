const risk_tb1 = document.getElementById(`risk_tb1`);
const risk_tb2 = document.getElementById(`risk_tb2`);
const risk_tb3 = document.getElementById(`risk_tb3`);
const risk_tb4 = document.getElementById(`risk_tb4`);
const risk_tb5 = document.getElementById(`risk_tb5`);

const risk_tb_date_label = document.getElementById(`risk_tb_date_label`);
const risk_tb_date = document.getElementById(`risk_tb_date`);

function toggleElementVisibility() {
  if (risk_tb1.checked || risk_tb2.checked || risk_tb2.checked) {
    risk_tb_date_label.style.display = "block";
    risk_tb_date.style.display = "block";
    risk_tb_date.setAttribute("required", "required");
  } else {
    risk_tb_date_label.style.display = "none";
    risk_tb_date.style.display = "none";
    risk_tb_date.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

risk_tb1.addEventListener("change", toggleElementVisibility);
risk_tb2.addEventListener("change", toggleElementVisibility);
risk_tb3.addEventListener("change", toggleElementVisibility);
risk_tb4.addEventListener("change", toggleElementVisibility);
risk_tb5.addEventListener("change", toggleElementVisibility);


