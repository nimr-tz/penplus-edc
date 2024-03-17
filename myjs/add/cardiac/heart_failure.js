const heart_failure1 = document.getElementById("heart_failure1");
const heart_failure2 = document.getElementById("heart_failure2");

const sub_heart_failure = document.getElementById("sub_heart_failure");
const sub_heart_failure1_1 = document.getElementById("sub_heart_failure1");


function toggleElementVisibility() {
  if (heart_failure1.checked) {
    sub_heart_failure.style.display = "block";
    sub_heart_failure1_1.setAttribute("required", "required");
  } else {
    sub_heart_failure.style.display = "none";
    sub_heart_failure1_1.removeAttribute("required");
  }
}

heart_failure1.addEventListener("change", toggleElementVisibility);
heart_failure2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_heart_failure() {
  var unsetSub_heart_failures = document.getElementsByName("sub_heart_failure");
  unsetSub_heart_failures.forEach(function (unsetSub_heart_failure) {
    unsetSub_heart_failure.checked = false;
  });
}
