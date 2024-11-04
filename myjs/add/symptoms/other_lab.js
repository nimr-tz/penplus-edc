const labs1 = document.getElementById("labs1");
const labs2 = document.getElementById("labs2");

const labs_other = document.getElementById("labs_other");
const labs_other_label = document.getElementById("labs_other_label");

function toggleElementVisibility() {
  if (labs1.checked) {
    labs_other_label.style.display = "block";
    labs_other.style.display = "block";
    labs_other.setAttribute("required", "required");
  } else {
    labs_other_label.style.display = "none";
    labs_other.removeAttribute("required");
    labs_other.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

labs1.addEventListener("change", toggleElementVisibility);
labs2.addEventListener("change", toggleElementVisibility);
