const primary_income_earner1 = document.getElementById(
  "primary_income_earner1"
);
const primary_income_earner2 = document.getElementById(
  "primary_income_earner2"
);
const primary_income_earner3 = document.getElementById(
  "primary_income_earner3"
);
const primary_income_earner4 = document.getElementById(
  "primary_income_earner4"
);
const primary_income_earner5 = document.getElementById(
  "primary_income_earner5"
);
const primary_income_earner96 = document.getElementById(
  "primary_income_earner96"
);

const primary_income_earner_other = document.getElementById(
  "primary_income_earner_other"
);

function toggleElementVisibility() {
  if (primary_income_earner96.checked) {
    primary_income_earner_other.style.display = "block";
  } else {
    primary_income_earner_other.style.display = "none";
  }
}

primary_income_earner1.addEventListener("change", toggleElementVisibility);
primary_income_earner2.addEventListener("change", toggleElementVisibility);
primary_income_earner3.addEventListener("change", toggleElementVisibility);
primary_income_earner4.addEventListener("change", toggleElementVisibility);
primary_income_earner5.addEventListener("change", toggleElementVisibility);
primary_income_earner96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
