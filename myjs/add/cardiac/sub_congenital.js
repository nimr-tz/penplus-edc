const sub_congenital1 = document.getElementById("sub_congenital1");
const sub_congenital2 = document.getElementById("sub_congenital2");
const sub_congenital3 = document.getElementById("sub_congenital3");
const sub_congenital4 = document.getElementById("sub_congenital4");
const sub_congenital5 = document.getElementById("sub_congenital5");
const sub_congenital96 = document.getElementById("sub_congenital96");

const congenital_other = document.getElementById("congenital_other");

function toggleElementVisibility() {
  if (sub_congenital96.checked) {
    congenital_other.style.display = "block";
    congenital_other.setAttribute("required", "required");
  } else {
    congenital_other.style.display = "none";
    congenital_other.removeAttribute("required");
  }
}

sub_congenital1.addEventListener("change", toggleElementVisibility);
sub_congenital2.addEventListener("change", toggleElementVisibility);
sub_congenital3.addEventListener("change", toggleElementVisibility);
sub_congenital4.addEventListener("change", toggleElementVisibility);
sub_congenital5.addEventListener("change", toggleElementVisibility);
sub_congenital96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_congenital() {
  var unsetSub_congenitals = document.getElementsByName("sub_congenital");
  unsetSub_congenitals.forEach(function (unsetSub_congenital) {
    unsetSub_congenital.checked = false;
  });
}
