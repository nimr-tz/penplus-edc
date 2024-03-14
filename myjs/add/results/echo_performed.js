const echo_performed1 = document.getElementById("echo_performed1");
const echo_performed2 = document.getElementById("echo_performed2");

const echo_performed = document.getElementById("echo_performed");

const echo_date = document.getElementById("echo_date");
const echo = document.getElementById("echo");
const lv = document.getElementById("lv");
const mitral = document.getElementById("plt");
const rv = document.getElementById("rv");
const pericardial = document.getElementById("pericardial");
const ivc = document.getElementById("ivc");
const thrombus = document.getElementById("thrombus");
const congenital_defect = document.getElementById("congenital_defect");
const echo_other = document.getElementById("echo_other");
const echo_other1 = document.getElementById("echo_other1");

function toggleElementVisibility() {
  if (echo_performed1.checked) {
    echo_performed.style.display = "block";
    echo_date.setAttribute("required", "required");
    echo.setAttribute("required", "required");
    lv.setAttribute("required", "required");
    mitral.setAttribute("required", "required");
    rv.setAttribute("required", "required");
    pericardial.setAttribute("required", "required");
    ivc.setAttribute("required", "required");
    thrombus.setAttribute("required", "required");
    congenital_defect.setAttribute("required", "required");
    echo_other.style.display = "block";
    echo_other1.setAttribute("required", "required");
  } else {
    echo_performed.style.display = "none";
    echo_date.removeAttribute("required");
    echo.removeAttribute("required");
    lv.removeAttribute("required");
    mitral.removeAttribute("required");
    rv.removeAttribute("required");
    pericardial.removeAttribute("required");
    ivc.removeAttribute("required");
    thrombus.removeAttribute("required");
    congenital_defect.removeAttribute("required");
    echo_other.style.display = "block";
    echo_other1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

echo_performed1.addEventListener("change", toggleElementVisibility);
echo_performed2.addEventListener("change", toggleElementVisibility);
