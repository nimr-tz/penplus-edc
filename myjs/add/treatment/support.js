const social_support1 = document.getElementById("social_support1");
const social_support2 = document.getElementById("social_support2");

const social_support_type = document.getElementById(`social_support_type`);
const social_support_type1 = document.getElementById(`social_support_type1`);

function toggleElementVisibility() {
  if (social_support1.checked) {
    social_support_type1.style.display = "block";
    social_support_type.setAttribute("required", "required");
  } else {
    social_support_type1.style.display = "none";
    social_support_type.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

social_support1.addEventListener("change", toggleElementVisibility);
social_support2.addEventListener("change", toggleElementVisibility);
