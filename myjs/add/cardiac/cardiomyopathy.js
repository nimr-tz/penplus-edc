const cardiomyopathy1 = document.getElementById("cardiomyopathy1");
const cardiomyopathy2 = document.getElementById("cardiomyopathy2");

const sub_cardiomyopathy = document.getElementById("sub_cardiomyopathy");
const sub_cardiomyopathy1_1 = document.getElementById("sub_cardiomyopathy1");

function toggleElementVisibility() {
  if (cardiomyopathy1.checked) {
    sub_cardiomyopathy.style.display = "block";
    sub_cardiomyopathy1_1.setAttribute("required", "required");
  } else {
    sub_cardiomyopathy.style.display = "none";
    sub_cardiomyopathy1_1.removeAttribute("required");
  }
}

cardiomyopathy1.addEventListener("change", toggleElementVisibility);
cardiomyopathy2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
