const art1 = document.getElementById(`art1`);
const art2 = document.getElementById(`art2`);

const art_date_label = document.getElementById(`art_date_label`);
const art_date = document.getElementById(`art_date`);

function toggleElementVisibility() {
  if (art1.checked) {
    art_date_label.style.display = "block";
    art_date.style.display = "block";
    art_date.setAttribute("required", "required");
  } else {
    art_date_label.style.display = "none";
    art_date.style.display = "none";
    art_date.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

art1.addEventListener("change", toggleElementVisibility);
art2.addEventListener("change", toggleElementVisibility);


function unsetArt() {
  var unsetArts = document.getElementsByName("art");
  unsetArts.forEach(function (unsetArt) {
    unsetArt.checked = false;
  });
}