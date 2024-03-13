const new_referrals1 = document.getElementById("new_referrals1");
const new_referrals2 = document.getElementById("new_referrals2");

const new_referrals_type1 = document.getElementById(`new_referrals_type1`);
const new_referrals_type = document.getElementById(`new_referrals_type`);

function toggleElementVisibility() {
  if (new_referrals1.checked) {
    new_referrals_type1.style.display = "block";
    new_referrals_type.setAttribute("required", "required");
  } else {
    new_referrals_type1.style.display = "none";
    new_referrals_type.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

new_referrals1.addEventListener("change", toggleElementVisibility);
new_referrals2.addEventListener("change", toggleElementVisibility);
