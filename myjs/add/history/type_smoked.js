const type_smoked1_2 = document.getElementById(`type_smoked1`);
const type_smoked2 = document.getElementById(`type_smoked2`);

const packs_per_day = document.getElementById(`packs_per_day`);
const cigarette_per_day = document.getElementById(`cigarette_per_day`);
const packs_cigarette_day = document.getElementById(`packs_cigarette_day`);
const packs = document.getElementById(`packs`);

function toggleElementVisibility() {
  if (type_smoked1_2.checked) {
    packs_cigarette_day.style.display = "block";
    packs_cigarette_day.setAttribute("required", "required");
    packs_per_day.style.display = "block";
    cigarette_per_day.style.display = "none";
    packs.style.display = "block";
  } else if (type_smoked2.checked) {
    packs_cigarette_day.style.display = "block";
    packs_cigarette_day.setAttribute("required", "required");
    packs_per_day.style.display = "none";
    cigarette_per_day.style.display = "block";
    packs.style.display = "block";
  } else {
    packs_cigarette_day.style.display = "none";
    packs_cigarette_day.removeAttribute("required");
    packs_per_day.style.display = "none";
    cigarette_per_day.style.display = "none";
    packs.style.display = "none";
  }
}

// Initial check
toggleElementVisibility();

type_smoked1_2.addEventListener("change", toggleElementVisibility);
type_smoked2.addEventListener("change", toggleElementVisibility);


function unsetType_smoked() {
  var unsetType_smokeds = document.getElementsByName("type_smoked");
  unsetType_smokeds.forEach(function (unsetType_smoked) {
    unsetType_smoked.checked = false;
  });
}