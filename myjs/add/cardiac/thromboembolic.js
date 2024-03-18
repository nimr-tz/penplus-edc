const thromboembolic1 = document.getElementById("thromboembolic1");
const thromboembolic2 = document.getElementById("thromboembolic2");

const sub_thromboembolic = document.getElementById("sub_thromboembolic");
const sub_thromboembolic1_1 = document.getElementById("sub_thromboembolic1");

function toggleElementVisibility() {
  if (thromboembolic1.checked) {
    sub_thromboembolic.style.display = "block";
    sub_thromboembolic1_1.setAttribute("required", "required");
  } else {
    sub_thromboembolic.style.display = "none";
    sub_thromboembolic1_1.removeAttribute("required");
  }
}

thromboembolic1.addEventListener("change", toggleElementVisibility);
thromboembolic2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();