const headache1 = document.getElementById("headache1");
const headache2 = document.getElementById("headache2");
const headache3 = document.getElementById("headache3");

const score_headache = document.getElementById("score_headache");
const score_headache_label = document.getElementById(`score_headache_label`);

const score_headache_span = document.getElementById(`score_headache_span`);

function toggleElementVisibility() {
  if (headache1.checked) {
    score_headache_label.style.display = "block";
    score_headache.style.display = "block";
    score_headache.setAttribute("required", "required");
    score_headache_span.style.display = "block";
  } else {
    score_headache_label.style.display = "none";
    score_headache.style.display = "none";
    score_headache.removeAttribute("required");
    score_headache_span.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

headache1.addEventListener("change", toggleElementVisibility);
headache2.addEventListener("change", toggleElementVisibility);
headache3.addEventListener("change", toggleElementVisibility);
