const stroke1 = document.getElementById("stroke1");
const stroke2 = document.getElementById("stroke2");

const sub_stroke1_1 = document.getElementById("sub_stroke1");
const sub_stroke = document.getElementById("sub_stroke");

function toggleElementVisibility() {
  if (stroke1.checked) {
    sub_stroke.style.display = "block";
    sub_stroke1_1.setAttribute("required", "required");
  } else {
    sub_stroke.style.display = "none";
    sub_stroke1_1.removeAttribute("required");
  }
}

stroke1.addEventListener("change", toggleElementVisibility);
stroke2.addEventListener("change", toggleElementVisibility);

// Initial check
toggleElementVisibility();

function unsetSub_stroke() {
  var unsetSub_strokes = document.getElementsByName("sub_stroke");
  unsetSub_strokes.forEach(function (unsetSub_stroke) {
    unsetSub_stroke.checked = false;
  });
}

