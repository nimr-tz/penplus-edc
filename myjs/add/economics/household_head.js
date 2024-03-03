const household_head1 = document.getElementById("household_head1");
const household_head2 = document.getElementById("household_head2");
const household_head3 = document.getElementById("household_head3");
const transport_mode96 = document.getElementById("transport_mode96");

const household_head_other = document.getElementById("household_head_other");

function toggleElementVisibility() {
  if (transport_mode96.checked) {
    household_head_other.style.display = "block";
  } else {
    household_head_other.style.display = "none";
  }
}

transport_mode1.addEventListener("change", toggleElementVisibility);
transport_mode2.addEventListener("change", toggleElementVisibility);
transport_mode3.addEventListener("change", toggleElementVisibility);
transport_mode4.addEventListener("change", toggleElementVisibility);
transport_mode5.addEventListener("change", toggleElementVisibility);
transport_mode6.addEventListener("change", toggleElementVisibility);
transport_mode7.addEventListener("change", toggleElementVisibility);
transport_mode96.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();
