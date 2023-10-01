const thumbnailContainer = document.getElementById("thumbnail-container");
const deleteThumbnail = document.getElementById("delete-thumbnail");
const thumbnailInput = document.getElementById("thumbnail");
const oldPhotoInput = document.getElementById("old_photo");

deleteThumbnail.addEventListener("click", e => {
    if(oldPhotoInput) {
        oldPhotoInput.value = "";
    }
    thumbnailContainer.classList.remove("show");
    thumbnailInput.classList.add("show");

    thumbnailInput.value = "";
})

thumbnailInput.addEventListener("change", function() {
    const file = this.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            // Establece la imagen cargada como fondo de div#preview
            thumbnailContainer.style.backgroundImage = `url(${event.target.result})`;
            thumbnailContainer.classList.add("show");
            thumbnailInput.classList.remove("show");
        };

        reader.readAsDataURL(file);
    }
});
