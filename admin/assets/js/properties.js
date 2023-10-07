/*
    función para mostrar el preview de las fotos destacadas
*/

const thumbnailContainer = document.getElementById("thumbnail-container");
const deleteThumbnail = document.getElementById("delete-thumbnail");
const thumbnailInput = document.getElementById("thumbnail");
const oldPhotoInput = document.getElementById("old_photo");

deleteThumbnail.addEventListener("click", (e) => {
    if (oldPhotoInput) {
        oldPhotoInput.value = "";
    }
    thumbnailContainer.classList.remove("show");
    thumbnailInput.classList.add("show");

    thumbnailInput.value = "";
});

thumbnailInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            // Establece la imagen cargada como fondo de div#preview
            thumbnailContainer.style.backgroundImage = `url(${event.target.result})`;
            thumbnailContainer.classList.add("show");
            thumbnailInput.classList.remove("show");
        };

        reader.readAsDataURL(file);
    }
});

/*
    función para seleccionar la moneda en que vamos a guardar el precio
*/

const currencySelect = document.getElementById("currency");
const priceInput = document.getElementById("price");
const priceLabel = document.getElementById("price-label");

currencySelect.addEventListener("change", function (event) {
    const isUsd = event.target.value === "usd";

    priceInput.name = isUsd ? "price_usd" : "price_gs";
    priceLabel.textContent = isUsd ? "Precio USD" : "Precio GS";
});
