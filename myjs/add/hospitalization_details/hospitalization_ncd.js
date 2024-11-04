const hospitalization_ncd1 = document.getElementById("hospitalization_ncd1");
const hospitalization_ncd2 = document.getElementById("hospitalization_ncd2");
const hospitalization_ncd3 = document.getElementById("hospitalization_ncd3");

const hospitalization_day1 = document.getElementById(`hospitalization_day1`);
const hospitalization_day = document.getElementById(`hospitalization_day`);
const hospitalization_year1 = document.getElementById(`hospitalization_year1`);
const hospitalization_year = document.getElementById(`hospitalization_year`);
const hospitalization_list = document.getElementById(`hospitalization_list`);

function toggleElementVisibility() {
  if (hospitalization_ncd1.checked) {
    hospitalization_list.style.display = "block";
    hospitalization_year1.style.display = "block";
    hospitalization_day.setAttribute("required", "required");
    hospitalization_day1.style.display = "block";
    hospitalization_year.setAttribute("required", "required");
  } else {
    hospitalization_list.style.display = "none";
    hospitalization_year1.style.display = "none";
    hospitalization_day.removeAttribute("required");
    hospitalization_day1.style.display = "none";
    hospitalization_year.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

hospitalization_ncd1.addEventListener("change", toggleElementVisibility);
hospitalization_ncd2.addEventListener("change", toggleElementVisibility);
hospitalization_ncd3.addEventListener("change", toggleElementVisibility);
