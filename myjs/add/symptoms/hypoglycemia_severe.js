const hypoglycemia_severe1 = document.getElementById("hypoglycemia_severe1");
const hypoglycemia_severe2 = document.getElementById("hypoglycemia_severe2");
const hypoglycemia_severe3 = document.getElementById("hypoglycemia_severe3");

const hypoglycemia__number = document.getElementById("hypoglycemia__number");
const hypoglycemia__number_label = document.getElementById(
  `hypoglycemia__number_label`
);

function toggleElementVisibility() {
  if (hypoglycemia_severe1.checked) {
    hypoglycemia__number_label.style.display = "block";
    hypoglycemia__number.setAttribute("required", "required");
    hypoglycemia__number.style.display = "block";
  } else {
    hypoglycemia__number_label.style.display = "none";
    hypoglycemia__number.removeAttribute("required");
    hypoglycemia__number.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

hypoglycemia_severe1.addEventListener("change", toggleElementVisibility);
hypoglycemia_severe2.addEventListener("change", toggleElementVisibility);
hypoglycemia_severe3.addEventListener("change", toggleElementVisibility);


