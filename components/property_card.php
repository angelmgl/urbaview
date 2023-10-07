<article class="property-card">
    <div class="card-header">
        <h2 class="property-title"><?php echo $property["title"] ?></h2>
        <p class="property-code"><span class="semibold">ID:</span> <?php echo $property["code_ref"] ?></p>
    </div>
    <div class="card-body">

        <div class="property-thumbnail" style="background-image: url(<?php echo get_thumbnail($property) ?>);">
            <a href="<?php echo BASE_URL . "/tour/" . $property["slug"] ?>">
                <div class="play">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                        <path fill="currentColor" d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" />
                    </svg>
                </div>
            </a>
        </div>

        <div class="property-details">
            <div class="details-container">
                <div class="detail price">
                    <h3 class="detail-title">Precio</h3>
                    <p class="detail-content">
                        <?php echo get_price($property) ?>
                    </p>
                </div>
                <div class="detail rooms">
                    <h3 class="detail-title">Habitaciones</h3>
                    <p class="detail-content"><?php echo $property["rooms"] ?></p>
                </div>
                <div class="detail bathrooms">
                    <h3 class="detail-title">Baños</h3>
                    <p class="detail-content"><?php echo $property["bathrooms"] ?></p>
                </div>
            </div>
            <div class="photos-container">
                <h3 class="detail-title">Fotografías</h3>
                <a href="<?php echo BASE_URL . "/tour/" . $property["slug"] ?>">
                    <div class="photos-grid">
                        <?php
                        // Verificar si existen imágenes en la propiedad
                        if (isset($property['images']) && count($property['images']) > 0) {

                            // Cantidad total de imágenes
                            $totalImages = count($property['images']);
                            $displayedImagesCount = 3;
                            $overflowCount = $totalImages - $displayedImagesCount;

                            // Muestra las primeras 3 imágenes (o menos si no hay 3)
                            for ($i = 0; $i < min($displayedImagesCount, $totalImages); $i++) {
                                echo '<img class="property-photo" src="' . BASE_URL . $property['images'][$i]['image_path'] . '" alt="' . $property["title"] . '">';
                            }

                            // Muestra el photos-overflow solo si hay más de 3 imágenes
                            if ($overflowCount > 0) : ?>
                                <div class="photos-overflow">

                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="...[tu SVG aquí]...">
                                    </svg>
                                    <span class="overflow-quantity">+<?php echo $overflowCount; ?></span>

                                </div>
                        <?php endif;
                        } else {
                            echo "<p>Esta propiedad no tiene fotos...</p>";
                        }
                        ?>
                    </div>
                </a>
            </div>
            <div class="see-more">
                <a href="<?php echo BASE_URL . "/tour/" . $property["slug"] ?>">
                    <span>Ver más</span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                        <path fill="currentColor" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</article>