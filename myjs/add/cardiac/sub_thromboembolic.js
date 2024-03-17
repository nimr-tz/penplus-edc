const sub_thromboembolic1 = document.getElementById("sub_thromboembolic1");
const sub_thromboembolic2 = document.getElementById("sub_thromboembolic2");
const sub_thromboembolic96 = document.getElementById("sub_thromboembolic96");

const thromboembolic_other = document.getElementById("thromboembolic_other");

function toggleElementVisibility() {
  if (sub_thromboembolic96.checked) {
    thromboembolic_other.style.display = "block";
    thromboembolic_other.setAttribute("required", "required");
  } else {
    thromboembolic_other.style.display = "none";
    thromboembolic_other.removeAttribute("required");
  }
}

sub_thromboembolic1.addEventListener("change", toggleElementVisibility);
sub_thromboembolic2.addEventListener("change", toggleElementVisibility);
sub_thromboembolic96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_thromboembolic() {
  var unsetSub_thromboembolics =
    document.getElementsByName("sub_thromboembolic");
  unsetSub_thromboembolics.forEach(function (unsetSub_thromboembolic) {
    unsetSub_thromboembolic.checked = false;
  });
}



