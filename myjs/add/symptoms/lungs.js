const lungs1 = document.getElementById("lungs1");
const lungs2 = document.getElementById(`lungs2`);
const lungs3 = document.getElementById("lungs3");
const lungs4 = document.getElementById("lungs4");
const lungs96 = document.getElementById("lungs96");

const lungs_other = document.getElementById("lungs_other");


function toggleElementVisibility() {
  if (lungs96.checked) {
    lungs_other.setAttribute("required", "required");
    lungs_other.style.display = "block";
  } else {
    lungs_other.style.display = "none";
    lungs_other.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

lungs1.addEventListener("change", toggleElementVisibility);
lungs2.addEventListener("change", toggleElementVisibility);
lungs3.addEventListener("change", toggleElementVisibility);
lungs4.addEventListener("change", toggleElementVisibility);
lungs96.addEventListener("change", toggleElementVisibility);

