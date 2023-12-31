<?php 

if(isset($property)) { 
    // si existe usuario estamos en edit-property.php
    $show_photo = $property["thumbnail"] ? "show" : "";
    $show_input = !$property["thumbnail"] ? "show" : "";
    $photo_url = get_thumbnail($property);
    $default_background = "background-image: url($photo_url)";
} else { 
    // sino existe estamos en add-property.php
    $show_input = "show";
    $default_background = "";
}

?>

<div class="input-wrapper file-input">
    <label for="thumbnail">Foto destacada:</label>
    <div id="thumbnail-container" class="<?php echo $show_photo; ?>" style="<?php echo $default_background; ?>">
        <button type="button" id="delete-thumbnail" class="action delete">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                <path fill="currentColor" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
            </svg>
        </button>
    </div>
    <input type="file" id="thumbnail" name="thumbnail" class="<?php echo $show_input; ?>" accept=".jpg, .jpeg, .png, .webp">
</div>