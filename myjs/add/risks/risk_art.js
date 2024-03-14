const risk_art1 = document.getElementById(`risk_art1`);
const risk_art2 = document.getElementById(`risk_art2`);

const risk_art_date_label = document.getElementById(`risk_art_date_label`);
const risk_art_date = document.getElementById(`risk_art_date`);

function toggleElementVisibility() {
  if (risk_art1.checked) {
    risk_art_date_label.style.display = "block";
    risk_art_date.style.display = "block";
    risk_art_date.setAttribute("required", "required");
  } else {
    risk_art_date_label.style.display = "none";
    risk_art_date.style.display = "none";
    risk_art_date.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

risk_art2.addEventListener("change", toggleElementVisibility);
risk_art2.addEventListener("change", toggleElementVisibility);

function unsetRiskArt() {
  var unsetRiskArts = document.getElementsByName("risk_art");
  unsetRiskArts.forEach(function (unsetRiskArt) {
    unsetRiskArt.checked = false;
  });
}
