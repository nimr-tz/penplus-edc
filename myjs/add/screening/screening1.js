const consent1 = document.getElementById("consent1");
const consent2 = document.getElementById("consent2");

const conset_date1 = document.getElementById("conset_date1");
const conset_date = document.getElementById("conset_date");

function toggleElementVisibility() {
  if (consent1.checked) {
    conset_date1.style.display = "block";
    conset_date.setAttribute("required", "required");
  } else {
    conset_date.removeAttribute("required");
    conset_date1.style.display = "none";
  }
}
consent1.addEventListener("change", toggleElementVisibility);
consent2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
