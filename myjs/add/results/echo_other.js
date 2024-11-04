const echo_other1 = document.getElementById("echo_other1");
const echo_other2 = document.getElementById("echo_other2");

const echo_specify1 = document.getElementById("echo_specify1");

const echo_specify = document.getElementById("echo_specify");

const echo_other2_1_1 = document.getElementById("echo_other2_1_1");
const echo_specify222 = document.getElementById("echo_specify222");

function toggleElementVisibility() {
  if (echo_other1.checked) {
    echo_specify1.style.display = "block";
    echo_specify.setAttribute("required", "required");
    echo_specify222.style.display = "block";
    echo_other2_1_1.setAttribute("required", "required");
  } else {
    echo_specify1.style.display = "none";
    echo_specify.removeAttribute("required");
    echo_specify222.style.display = "none";
    echo_other2_1_1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

echo_other1.addEventListener("change", toggleElementVisibility);
echo_other2.addEventListener("change", toggleElementVisibility);
