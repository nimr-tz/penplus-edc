const blood_transfusion1 = document.getElementById(`blood_transfusion1`);
const blood_transfusion2 = document.getElementById(`blood_transfusion2`);

const transfusion_born_label = document.getElementById(
  `transfusion_born_label`
);
const transfusion_born = document.getElementById(`transfusion_born`);
const transfusion_12months_label = document.getElementById(
  `transfusion_12months_label`
);
const transfusion_12months = document.getElementById(`transfusion_12months`);

function toggleElementVisibility() {
  if (blood_transfusion1.checked) {
    transfusion_born_label.style.display = "block";
    transfusion_born.style.display = "block";
    transfusion_born.setAttribute("required", "required");
    transfusion_12months_label.style.display = "block";
    transfusion_12months.style.display = "block";
    transfusion_12months.setAttribute("required", "required");
  } else {
    transfusion_born_label.style.display = "none";
    transfusion_born.style.display = "none";
    transfusion_born.removeAttribute("required");
    transfusion_12months_label.style.display = "none";
    transfusion_12months.style.display = "none";
    transfusion_12months.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

blood_transfusion1.addEventListener("change", toggleElementVisibility);
blood_transfusion2.addEventListener("change", toggleElementVisibility);
