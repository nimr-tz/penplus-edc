const hospitalization_ncd1 = document.getElementById("hospitalization_ncd1");
const hospitalization_ncd2 = document.getElementById("hospitalization_ncd2");
const hospitalization_ncd3 = document.getElementById("hospitalization_ncd3");


const hospitalization_ncd_hides = document.getElementById(
  `hospitalization_ncd_hides`
);

const hospitalization_day = document.getElementById(`hospitalization_day`);
const hospitalization_year = document.getElementById(`hospitalization_year`);


function toggleElementVisibility() {
  if (diagns_changed1.checked) {
    hospitalization_ncd_hides.style.display = "block";
    hospitalization_day.setAttribute("required", "required");
    hospitalization_year.setAttribute("required", "required");
  } else {
    hospitalization_ncd_hides.style.display = "none";
    hospitalization_day.removeAttribute("required");
    hospitalization_year.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hospitalization_ncd1.addEventListener("change", toggleElementVisibility);
hospitalization_ncd2.addEventListener("change", toggleElementVisibility);
hospitalization_ncd3.addEventListener("change", toggleElementVisibility);

