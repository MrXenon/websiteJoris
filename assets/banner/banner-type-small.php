<?php if (!empty($banner)) : ?>
  <!-- start slider section -->
  <section class="p-0" >
            <div class="container-fluid position-relative">
                <div class="row">
                    <div class="swiper-container primary-border-bottom  small-screen p-0 md-h-500px sm-h-500px white-move swiper-light-pagination" >
                        <div class="swiper-wrapper">
                            <!-- start slider item -->
                             <?php foreach ($banners as $bannersData): ?>
                            <div class="swiper-slide cover-background" style="background-image:url('<?= SERVERPATH?>/images/banners/<?= $bannersData['image']; ?>');">
                                <div class="opacity-extra-medium bg-extra-dark-gray"></div>

                                <div class="container h-100">
                                    <div class="row justify-content-center h-100">
                                        <div class="col-12 col-xl-6 col-sm-7 position-relative d-flex flex-column justify-content-center h-100 text-center">
                                            <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0"><?= $bannersData['title']; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <!-- end slider item -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
         <?php endif; ?>