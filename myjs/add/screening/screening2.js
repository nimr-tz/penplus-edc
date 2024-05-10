const lab_request1 = document.getElementById("lab_request1");
const lab_request2 = document.getElementById("lab_request2");

const lab_request_date1 = document.getElementById("lab_request_date1");
const lab_request_date = document.getElementById("lab_request_date");

function toggleElementVisibility() {
  if (lab_request1.checked) {
    lab_request_date1.style.display = "block";
    lab_request_date.setAttribute("required", "required");
  } else {
    lab_request_date.removeAttribute("required");
    lab_request_date1.style.display = "none";
  }
}
lab_request1.addEventListener("change", toggleElementVisibility);
lab_request2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
