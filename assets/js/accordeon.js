window.addEventListener("load", () => {
  const accordeon = document.getElementById("accordeon");
  const toggle = document.getElementById("toggle-accordeon");
  const toggleText = document.getElementById("toggle-text");
  const grid = document.getElementById("details-grid");

  toggle.addEventListener("click", (e) => {
    let isOpen = toggle.getAttribute("data-open") === "true";

    if (isOpen) {
      grid.classList.remove("open");
      accordeon.classList.remove("open");
      toggle.setAttribute("data-open", "false");
    } else {
      grid.classList.add("open");
      accordeon.classList.add("open");
      toggleText.textContent = "Menos info";
      toggle.setAttribute("data-open", "true");
    }
  });
});
