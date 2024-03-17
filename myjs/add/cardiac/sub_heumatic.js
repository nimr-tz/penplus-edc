const sub_heumatic1 = document.getElementById("sub_heumatic1");
const sub_heumatic2 = document.getElementById("sub_heumatic2");
const sub_heumatic3 = document.getElementById("sub_heumatic3");
const sub_heumatic4 = document.getElementById("sub_heumatic4");
const sub_heumatic5 = document.getElementById("sub_heumatic5");
const sub_heumatic96 = document.getElementById("sub_heumatic96");

const heumatic_other = document.getElementById("heumatic_other");

function toggleElementVisibility() {
  if (sub_heumatic96.checked) {
    heumatic_other.style.display = "block";
    heumatic_other.setAttribute("required", "required");
  } else {
    heumatic_other.style.display = "none";
    heumatic_other.removeAttribute("required");
  }
}

sub_heumatic1.addEventListener("change", toggleElementVisibility);
sub_heumatic2.addEventListener("change", toggleElementVisibility);
sub_heumatic3.addEventListener("change", toggleElementVisibility);
sub_heumatic4.addEventListener("change", toggleElementVisibility);
sub_heumatic5.addEventListener("change", toggleElementVisibility);
sub_heumatic96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_heumatic() {
  var unsetSub_heumatics = document.getElementsByName("sub_heumatic");
  unsetSub_heumatics.forEach(function (unsetSub_heumatic) {
    unsetSub_heumatic.checked = false;
  });
}

