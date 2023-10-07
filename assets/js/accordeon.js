window.addEventListener("load", () => {
    const accordeon = document.getElementById("accordeon");
    const toggle = document.getElementById("toggle-accordeon");
    const toggleText = document.getElementById("toggle-text");
    const container = document.getElementById("details-container");
    const isMobile = window.innerWidth <= 600;

    toggle.addEventListener("click", (e) => {
        let isOpen = toggle.getAttribute("data-open") === "true";

        if (isOpen) {
            // Cerrar accordeon y container
            container.style.height = isMobile ? "60px" : "160px";
            accordeon.classList.remove("open");
            container.classList.remove("open");
            toggleText.textContent = "Más info";
            toggle.setAttribute("data-open", "false");
        } else {
            // Expandir accordeon y container
            container.style.height = "auto";
            const fullHeight = container.offsetHeight;
            container.style.height = isMobile ? "60px" : "160px";
            container.offsetHeight; // Forzar el reflujo del navegador
            container.style.height = fullHeight + "px";

            accordeon.classList.add("open");
            container.classList.add("open");
            toggleText.textContent = "Menos info";
            toggle.setAttribute("data-open", "true");
        }
    });
});