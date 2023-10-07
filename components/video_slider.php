<?php if (isset($property['videos']) && !empty($property['videos'])) : ?>
    <div class="swiper video-slider" id="videos">
        <div class="swiper-wrapper">
            <?php foreach ($property['videos'] as $video) :
                $video_id =  get_video_id($video["youtube_url"]);
            ?>
                <div class="swiper-slide">
                    <a class="video" href="<?php echo $video["youtube_url"] ?>" target="_BLANK">
                        <iframe width="320" class="no-pointer" src="https://www.youtube.com/embed/<?php echo $video_id ?>&amp;controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
<?php else : ?>
    <p>Esta propiedad no tiene videos...</p>
<?php endif; ?>

<!-- Initialize Swiper  -->
<script type="module">
    var swiper = new Swiper(".video-slider", {
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
</script>