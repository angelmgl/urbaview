<?php if (isset($property['images']) && !empty($property['images'])) : ?>
    <div class="swiper photo-slider" id="gallery">
        <div class="swiper-wrapper">
            <?php foreach ($property['images'] as $image) : ?>
                <div class="swiper-slide">
                    <a data-pswp-src="<?php echo BASE_URL . $image['image_path']; ?>" data-pswp-width=<?php echo $image['width']; ?> data-pswp-height=<?php echo $image['height']; ?>>
                        <img class="property-photo" src="<?php echo BASE_URL . $image['image_path']; ?>" alt="Imagen de la propiedad <?php echo $image['image_id']; ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
<?php else : ?>
    <p>Esta propiedad no tiene fotos...</p>
<?php endif; ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Initialize Swiper and PhotoSwipe -->
<script type="module">
    var swiper = new Swiper(".photo-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: { // cuando el ancho de la ventana es >= 640px
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: { // cuando el ancho de la ventana es >= 768px
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });

    import PhotoSwipeLightbox from '<?php echo BASE_URL ?>/assets/js/photoswipe-lightbox.esm.js';
    import PhotoSwipe from '<?php echo BASE_URL ?>/assets/js/photoswipe.esm.js';

    const lightbox = new PhotoSwipeLightbox({
        gallery: '#gallery',
        children: 'a',
        pswpModule: PhotoSwipe
    });

    lightbox.init();
</script>