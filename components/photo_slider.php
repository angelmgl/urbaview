<?php if (isset($property['images']) && !empty($property['images'])): ?>
    <div class="swiper photo-slider">
        <div class="swiper-wrapper">
            <?php foreach ($property['images'] as $image): ?>
                <div class="swiper-slide">
                    <a href="<?php echo BASE_URL . $image['image_path']; ?>" data-lightbox="gallery">
                        <img class="property-photo" src="<?php echo BASE_URL . $image['image_path']; ?>" alt="Imagen de la propiedad <?php echo $image['image_id']; ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
<?php else: ?>
    <p>Esta propiedad no tiene fotos...</p>
<?php endif; ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".photo-slider", {
        slidesPerView: 3,
        spaceBetween: 30,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>