const transport_mode1 = document.getElementById("transport_mode1");
const transport_mode2 = document.getElementById("transport_mode2");
const transport_mode3 = document.getElementById("transport_mode3");
const transport_mode4 = document.getElementById("transport_mode4");
const transport_mode5 = document.getElementById("transport_mode5");
const transport_mode6 = document.getElementById("transport_mode6");
const transport_mode7 = document.getElementById("transport_mode7");
const transport_mode96 = document.getElementById("transport_mode96");

const transport_mode_other = document.getElementById("transport_mode_other");

function toggleElementVisibility() {
  if (transport_mode96.checked) {
    transport_mode_other.style.display = "block";
  } else {
    transport_mode_other.style.display = "none";
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

