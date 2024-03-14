const issue_injection1 = document.getElementById("issue_injection1");
const issue_injection2 = document.getElementById("issue_injection2");

const issue_injection_yes = document.getElementById("issue_injection_yes");
const issue_injection_yes1 = document.getElementById("issue_injection_yes1");


function toggleElementVisibility() {
  if (issue_injection1.checked) {
    issue_injection_yes.style.display = "block";
    issue_injection_yes1.setAttribute("required", "required");
  } else {
    issue_injection_yes.style.display = "none";
    issue_injection_yes1.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

issue_injection1.addEventListener("change", toggleElementVisibility);
issue_injection2.addEventListener("change", toggleElementVisibility);

function unsetIssue_injection() {
  var unsetIssue_injections = document.getElementsByName("issue_injection_yes");
  unsetIssue_injections.forEach(function (unsetIssue_injection) {
    unsetIssue_injection.checked = false;
  });
}
