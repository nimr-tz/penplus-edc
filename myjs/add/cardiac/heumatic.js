const heumatic1 = document.getElementById("heumatic1");
const heumatic2 = document.getElementById("heumatic2");

const sub_heumatic = document.getElementById("sub_heumatic");
const sub_heumatic1_1 = document.getElementById("sub_heumatic1");

function toggleElementVisibility() {
  if (heumatic1.checked) {
    sub_heumatic.style.display = "block";
    sub_heumatic1_1.setAttribute("required", "required");
  } else {
    sub_heumatic.style.display = "none";
    sub_heumatic1_1.removeAttribute("required");
  }
}

heumatic1.addEventListener("change", toggleElementVisibility);
heumatic2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();


