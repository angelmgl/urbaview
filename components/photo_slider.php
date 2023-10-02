<div class="swiper photo-slider">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
        <div class="swiper-slide">
            <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
        </div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

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