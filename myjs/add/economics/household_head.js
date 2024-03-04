const household_head1 = document.getElementById("household_head1");
const household_head2 = document.getElementById("household_head2");
const household_head3 = document.getElementById("household_head3");
const household_head96 = document.getElementById("household_head96");

const household_head_other = document.getElementById("household_head_other");

function toggleElementVisibility() {
  if (household_head96.checked) {
    household_head_other.style.display = "block";
  } else {
    household_head_other.style.display = "none";
  }
}

household_head1.addEventListener("change", toggleElementVisibility);
household_head2.addEventListener("change", toggleElementVisibility);
household_head3.addEventListener("change", toggleElementVisibility);
transport_mode96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
