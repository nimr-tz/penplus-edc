document.addEventListener("DOMContentLoaded", function () {
  const categories = document.querySelectorAll(".category");
  categories.forEach(function (category) {
    category.addEventListener("change", function () {
      const categoryName = category.value;
      const categoryItems = document.querySelectorAll("." + categoryName);
      if (category.checked) {
        categoryItems.forEach(function (item) {
          item.classList.remove("hidden");
        });
      } else {
        categoryItems.forEach(function (item) {
          item.classList.add("hidden");
        });
      }
    });
  });
});
