const popup = document.getElementById("share-popup");
const openBtn = document.getElementById("open-popup");
const closeBtn = document.getElementById("close-popup");

openBtn.addEventListener("click", () => {
    popup.classList.add("open");
});

closeBtn.addEventListener("click", () => {
    popup.classList.remove("open");
});

const copyBtn = document.querySelector(".copy");
const copyIcon = document.getElementById("copy-icon");
const checkIcon = document.getElementById("check-icon");

copyBtn.addEventListener("click", function() {
    // Obtener el valor del atributo data-url del botón
    let urlToCopy = this.getAttribute('data-url');

    // Usar el API de Clipboard para copiar el texto
    navigator.clipboard.writeText(urlToCopy).then(() => {
        console.log('Texto copiado al portapapeles');

        // Ocultar el ícono de copia y mostrar el ícono de check
        copyIcon.style.display = "none";
        checkIcon.style.display = "inline";

        // Cambiar el color de fondo del botón a verde claro
        copyBtn.style.background = "#a8e6cf"; // Puedes ajustar este valor al verde claro que prefieras

        // Llama a la función revertChanges luego de 3 segundos
        setTimeout(revertChanges, 1000);

    }).catch((err) => {
        console.error('No se pudo copiar el texto: ', err);
    });
});

function revertChanges() {
    // Revertir los íconos a su estado original
    copyIcon.style.display = "inline";
    checkIcon.style.display = "none";

    // Revertir el color de fondo del botón a su color original
    copyBtn.style.background = "#e2efff";
}
