const sub_arrhythmia1 = document.getElementById("sub_arrhythmia1");
const sub_arrhythmia96 = document.getElementById("sub_arrhythmia96");

const arrhythmia_other = document.getElementById("arrhythmia_other");

function toggleElementVisibility() {
  if (sub_arrhythmia96.checked) {
    arrhythmia_other.style.display = "block";
    arrhythmia_other.setAttribute("required", "required");
  } else {
    arrhythmia_other.style.display = "none";
    arrhythmia_other.removeAttribute("required");
  }
}

sub_arrhythmia1.addEventListener("change", toggleElementVisibility);
sub_arrhythmia96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_arrhythmia() {
  var unsetSub_arrhythmias = document.getElementsByName("sub_arrhythmia");
  unsetSub_arrhythmias.forEach(function (unsetSub_arrhythmia) {
    unsetSub_arrhythmia.checked = false;
  });
}


