const sub_pericardial1 = document.getElementById("sub_pericardial1");
const sub_pericardial2 = document.getElementById("sub_pericardial2");
const sub_pericardial3 = document.getElementById("sub_pericardial3");
const sub_pericardial96 = document.getElementById("sub_pericardial96");

const pericardial_other = document.getElementById("pericardial_other");

function toggleElementVisibility() {
  if (sub_pericardial96.checked) {
    pericardial_other.style.display = "block";
    pericardial_other.setAttribute("required", "required");
  } else {
    pericardial_other.style.display = "none";
    pericardial_other.removeAttribute("required");
  }
}

sub_pericardial1.addEventListener("change", toggleElementVisibility);
sub_pericardial2.addEventListener("change", toggleElementVisibility);
sub_pericardial3.addEventListener("change", toggleElementVisibility);
sub_pericardial96.addEventListener("change", toggleElementVisibility);


// Initial check
toggleElementVisibility();

function unsetSub_pericardial() {
  var unsetSub_pericardials = document.getElementsByName("sub_pericardial");
  unsetSub_pericardials.forEach(function (unsetSub_pericardial) {
    unsetSub_pericardial.checked = false;
  });
}

