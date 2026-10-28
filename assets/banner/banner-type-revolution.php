    <?php if (!empty($banner)) : ?>
    <!-- start slider section -->
    <section class="p-0" >
            <div class="container-fluid position-relative">
                <div class="row">
                    <div class="swiper-container primary-border-bottom small-screen p-0 md-h-500px sm-h-500px white-move swiper-light-pagination" data-slider-options='{ "slidesPerView": 1, "loop": true, "autoplay": { "delay": 4500, "disableOnInteraction": false },  "pagination": { "el": ".swiper-pagination", "clickable": true }, "navigation": { "nextEl": ".swiper-button-next-nav", "prevEl": ".swiper-button-previous-nav" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
                        <div class="swiper-wrapper">
                            
                        <!-- start slider item -->
                         <?php foreach ($banners as $bannersData): ?>
                            <div class="opacity-extra-medium bg-extra-dark-gray"></div>
                            <div class="swiper-slide cover-background" style="background-image:url('<?= SERVERPATH ?>/images/banners/<?= $bannersData['image']; ?>');">
                                
                                <div class="container h-100">
                                    <div class="row justify-content-center h-100">
                                        <div class="col-12 col-xl-6 col-sm-7 position-relative d-flex flex-column justify-content-center h-100 text-center">
                                            <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0"><?= $bannersData['title']; ?></h3>
                                             <?= $bannersData['text']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <!-- end slider item -->

                        </div>
                        <!-- start slider pagination -->
                        <!--<div class="swiper-pagination swiper-light-pagination"></div>-->
                        <!-- end slider pagination -->
                        <!-- start slider navigation -->
                        <div class="swiper-button-previous-nav swiper-button-prev slider-navigation-style-05"><i class="line-icon-Arrow-OutLeft icon-large"></i></div>
                        <div class="swiper-button-next-nav swiper-button-next slider-navigation-style-05"><i class="line-icon-Arrow-OutRight icon-large"></i></div>
                        <!-- end slider navigation -->
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
         <?php endif; ?>