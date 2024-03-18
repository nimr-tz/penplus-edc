const sub_cardiomyopathy1 = document.getElementById("sub_cardiomyopathy1");
const sub_cardiomyopathy2 = document.getElementById("sub_cardiomyopathy2");
const sub_cardiomyopathy3 = document.getElementById("sub_cardiomyopathy3");
const sub_cardiomyopathy4 = document.getElementById("sub_cardiomyopathy4");
const sub_cardiomyopathy5 = document.getElementById("sub_cardiomyopathy5");
const sub_cardiomyopathy6 = document.getElementById("sub_cardiomyopathy6");
const sub_cardiomyopathy7 = document.getElementById("sub_cardiomyopathy7");
const sub_cardiomyopathy96 = document.getElementById("sub_cardiomyopathy96");

const cardiomyopathy_other = document.getElementById("cardiomyopathy_other");

function toggleElementVisibility() {
  if (sub_cardiomyopathy96.checked) {
    cardiomyopathy_other.style.display = "block";
    cardiomyopathy_other.setAttribute("required", "required");
  } else {
    cardiomyopathy_other.style.display = "none";
    cardiomyopathy_other.removeAttribute("required");
  }
}

sub_cardiomyopathy1.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy2.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy3.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy4.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy5.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy6.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy7.addEventListener("change", toggleElementVisibility);
sub_cardiomyopathy96.addEventListener("change", toggleElementVisibility);


// Initial check
toggleElementVisibility();


function unsetSub_cardiomyopathy() {
  var unsetSub_cardiomyopathys =
    document.getElementsByName("sub_cardiomyopathy");
  unsetSub_cardiomyopathys.forEach(function (unsetSub_cardiomyopathy) {
    unsetSub_cardiomyopathy.checked = false;
  });
}
