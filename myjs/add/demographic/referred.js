const referred1 = document.getElementById(`referred1`);
const referred2 = document.getElementById(`referred2`);
const referred3 = document.getElementById(`referred3`);
const referred4 = document.getElementById(`referred4`);
const referred5 = document.getElementById(`referred5`);
const referred6 = document.getElementById(`referred6`);
const referred96 = document.getElementById(`referred96`);

// const referred = document.getElementById(`referred${i}`);

const referred_other = document.getElementById(`referred_other`);

function toggleElementVisibility() {
  if (referred96.checked) {
    referred_other.style.display = "block";
    referred_other.setAttribute("required", "required");
  } else {
    referred_other.style.display = "none";
    referred_other.removeAttribute("required");
  }
}
// Initial check
toggleElementVisibility();

referred1.addEventListener("change", toggleElementVisibility);
referred2.addEventListener("change", toggleElementVisibility);
referred3.addEventListener("change", toggleElementVisibility);
referred4.addEventListener("change", toggleElementVisibility);
referred5.addEventListener("change", toggleElementVisibility);
referred6.addEventListener("change", toggleElementVisibility);
referred96.addEventListener("change", toggleElementVisibility);
