// Toggle to show and hide navbar menu
const navbarMenu = document.getElementById("menu");
const burgerMenu = document.getElementById("burger");

burgerMenu.addEventListener("click", () => {
  navbarMenu.classList.toggle("is-active");
  burgerMenu.classList.toggle("is-active");
});

// Toggle to show and hide dropdown menu
const dropdowns = document.querySelectorAll(".adropdown");

dropdowns.forEach((item) => {
  const dropdownToggle = item.querySelector(".adropdown-toggle");

  dropdownToggle.addEventListener("click", () => {
    const dropdownShow = document.querySelector(".adropdown-show");
    toggleDropdownItem(item);

    // Remove 'dropdown-show' class from other dropdown
    if (dropdownShow && dropdownShow !== item) {
      toggleDropdownItem(dropdownShow);
    }
  });
});

// Function to display the dropdown menu
const toggleDropdownItem = (item) => {
  const dropdownContent = item.querySelector(".adropdown-content");

  // Remove other dropdown that has 'dropdown-show' class
  if (item.classList.contains("adropdown-show")) {
    dropdownContent.removeAttribute("style");
    item.classList.remove("adropdown-show");
  } else {
    // Add max-height to the active 'dropdown-show' class
    dropdownContent.style.height = dropdownContent.scrollHeight + "px";
    item.classList.add("adropdown-show");
  }
};

// Fixed dropdown menu on window resizing
window.addEventListener("resize", () => {
  if (window.innerWidth > 992) {
    document.querySelectorAll(".adropdown-content").forEach((item) => {
      item.removeAttribute("style");
    });
    dropdowns.forEach((item) => {
      item.classList.remove("adropdown-show");
    });
  }
});

// Fixed navbar menu on window resizing
window.addEventListener("resize", () => {
  if (window.innerWidth > 992) {
    if (navbarMenu.classList.contains("is-active")) {
      navbarMenu.classList.remove("is-active");
      burgerMenu.classList.remove("is-active");
    }
  }
});
