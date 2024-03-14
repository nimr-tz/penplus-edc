const cardiology1 = document.getElementById("cardiology1");
const cardiology2 = document.getElementById("cardiology2");

const completed = document.getElementById("completed");
const completed1 = document.getElementById(`completed`);
const cardiology_date1 = document.getElementById(`cardiology_date1`);
const cardiology_date = document.getElementById(`cardiology_date`);
const cardiology_reason1 = document.getElementById(`cardiology_reason1`);
const cardiology_reason = document.getElementById(`cardiology_reason`);

function toggleElementVisibility() {
  if (cardiology1.checked) {
    completed.style.display = "block";
    completed1.setAttribute("required", "required");
    cardiology_date1.style.display = "block";
    cardiology_date.setAttribute("required", "required");
    cardiology_reason1.style.display = "block";
    cardiology_reason.setAttribute("required", "required");
  } else {
    completed.style.display = "none";
    completed1.removeAttribute("required");
    cardiology_date1.style.display = "none";
    cardiology_date.removeAttribute("required");
    cardiology_reason1.style.display = "none";
    cardiology_reason.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

cardiology1.addEventListener("change", toggleElementVisibility);
cardiology2.addEventListener("change", toggleElementVisibility);
