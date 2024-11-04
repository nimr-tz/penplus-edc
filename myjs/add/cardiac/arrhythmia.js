const arrhythmia1 = document.getElementById("arrhythmia1");
const arrhythmia2 = document.getElementById("arrhythmia2");

const sub_arrhythmia1_1 = document.getElementById("sub_arrhythmia1");
const sub_arrhythmia = document.getElementById("sub_arrhythmia");

function toggleElementVisibility() {
  if (arrhythmia1.checked) {
    sub_arrhythmia.style.display = "block";
    sub_arrhythmia1_1.setAttribute("required", "required");
  } else {
    sub_arrhythmia.style.display = "none";
    sub_arrhythmia1_1.removeAttribute("required");
  }
}

arrhythmia1.addEventListener("change", toggleElementVisibility);
arrhythmia2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();




