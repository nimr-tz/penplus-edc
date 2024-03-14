const echo_performed1trrrrrrr = document.getElementById(
  "echo_performed1111111111111"
);
const echo_performed1trrrrrss = document.getElementById(
  "echo_performed2322222"
);

const echo_performed23111111111144 = document.getElementById(
  "echo_performed23111111111144"
);

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
const test22 = document.getElementById("test22");

function toggleElementVisibility() {
  if (echo_performed1trrrrrrr.checked) {
    echo_performed23111111111144.style.display = "block";
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
    test22.style.display = "block";
  } else {
    echo_performed23111111111144.style.display = "none";
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
    test22.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

echo_performed1trrrrrrr.addEventListener("change", toggleElementVisibility);
echo_performed1trrrrrss.addEventListener("change", toggleElementVisibility);
