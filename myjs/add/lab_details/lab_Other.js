const other_chemistry4_4_4_4 = document.getElementById("other_chemistry4_4");
const other_chemistry5_5_5_5 = document.getElementById("other_chemistry5_5");

const lab_specify3_3_3_3 = document.getElementById("lab_specify3_3_3_3");
const lab_specify2_2_2_2 = document.getElementById("lab_specify2_2_2_2");

function toggleElementVisibility() {
  if (other_chemistry4_4_4_4.checked) {
    lab_specify3_3_3_3.style.display = "block";
    lab_specify2_2_2_2.setAttribute("required", "required");
  } else {
    lab_specify3_3_3_3.style.display = "none";
    lab_specify2_2_2_2.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

other_chemistry4_4_4_4.addEventListener("change", toggleElementVisibility);
other_chemistry5_5_5_5.addEventListener("change", toggleElementVisibility);

