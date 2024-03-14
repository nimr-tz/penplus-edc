const diagns_cardiac1 = document.getElementById("diagns_cardiac1");
const diagns_cardiac2 = document.getElementById("diagns_cardiac2");
const diagns_cardiac3 = document.getElementById("diagns_cardiac3");
const diagns_cardiac4 = document.getElementById("diagns_cardiac4");
const diagns_cardiac5 = document.getElementById("diagns_cardiac5");
const diagns_cardiac6 = document.getElementById("diagns_cardiac6");
const diagns_cardiac7 = document.getElementById("diagns_cardiac7");
const diagns_cardiac8 = document.getElementById("diagns_cardiac8");
const diagns_cardiac9 = document.getElementById("diagns_cardiac9");
const diagns_cardiac10 = document.getElementById("diagns_cardiac10");
const diagns_cardiac11 = document.getElementById("diagns_cardiac11");
const diagns_cardiac96 = document.getElementById("diagns_cardiac96");

const diagns_diabetes1 = document.getElementById("diagns_diabetes1");
const diagns_diabetes2 = document.getElementById("diagns_diabetes2");
const diagns_diabetes3 = document.getElementById("diagns_diabetes3");
const diagns_diabetes4 = document.getElementById("diagns_diabetes4");
const diagns_diabetes5 = document.getElementById("diagns_diabetes5");
const diagns_diabetes96 = document.getElementById("diagns_diabetes96");

const diagns_sickle1 = document.getElementById("diagns_sickle1");
const diagns_sickle2 = document.getElementById("diagns_sickle2");
const diagns_sickle96 = document.getElementById("diagns_sickle96");

const diagns_specify1 = document.getElementById("diagns_specify1");
const diagns_specify = document.getElementById("diagns_specify");

function toggleElementVisibility() {
  if (diagns_cardiac96.checked) {
    diagns_specify1.style.display = "block";
    diagns_specify.setAttribute("required", "required");
  } else if (diagns_diabetes96.checked) {
    diagns_specify1.style.display = "block";
    diagns_specify.setAttribute("required", "required");
  } else if (diagns_sickle96.checked) {
    diagns_specify1.style.display = "block";
    diagns_specify.setAttribute("required", "required");
  } else {
    diagns_specify1.style.display = "none";
    diagns_specify.removeAttribute("required");
  }
}

// Initial check
toggleElementVisibility();

diagns_cardiac1.addEventListener("change", toggleElementVisibility);
diagns_cardiac2.addEventListener("change", toggleElementVisibility);
diagns_cardiac3.addEventListener("change", toggleElementVisibility);
diagns_cardiac4.addEventListener("change", toggleElementVisibility);
diagns_cardiac5.addEventListener("change", toggleElementVisibility);
diagns_cardiac6.addEventListener("change", toggleElementVisibility);
diagns_cardiac7.addEventListener("change", toggleElementVisibility);
diagns_cardiac8.addEventListener("change", toggleElementVisibility);
diagns_cardiac9.addEventListener("change", toggleElementVisibility);
diagns_cardiac10.addEventListener("change", toggleElementVisibility);
diagns_cardiac11.addEventListener("change", toggleElementVisibility);
diagns_cardiac96.addEventListener("change", toggleElementVisibility);

diagns_diabetes1.addEventListener("change", toggleElementVisibility);
diagns_diabetes2.addEventListener("change", toggleElementVisibility);
diagns_diabetes3.addEventListener("change", toggleElementVisibility);
diagns_diabetes4.addEventListener("change", toggleElementVisibility);
diagns_diabetes5.addEventListener("change", toggleElementVisibility);
diagns_diabetes96.addEventListener("change", toggleElementVisibility);

diagns_sickle1.addEventListener("change", toggleElementVisibility);
diagns_sickle2.addEventListener("change", toggleElementVisibility);
diagns_sickle96.addEventListener("change", toggleElementVisibility);
