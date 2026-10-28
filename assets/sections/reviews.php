<?php
/**
 * Reviews: 3D slider with the approved reviews (CMS > Reviews).
 * Title = heading above the slider (last word in the primary colour).
 * Preview: slider
 */
$reviewsTitleWords = explode(' ', cms_html($fpc_obj->getFptitle()));
$reviewsTitleLast  = array_pop($reviewsTitleWords);
?>
            <!-- start section -->
        <div class="wow animate__fadeIn"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center margin-six-bottom">
                        <h5 class="font-weight-600 text-black margin-5px-bottom"><?= implode(' ', $reviewsTitleWords) ?> <span class="primary"><?= $reviewsTitleLast ?></span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="swiper-container black-move swiper-horizontal-3d padding-7-rem-tb sm-padding-10-rem-top xs-padding-8-rem-top swiper-pagination-bottom"  data-slider-options='{ "loop": true, "slidesPerView": 1,"centeredSlides":true,"effect":"coverflow","coverflowEffect":{"rotate":0,"stretch":100,"depth":150,"modifier":1.5,"slideShadows":true}, "navigation": { "nextEl": ".swiper-button-next", "prevEl": ".swiper-button-prev" }, "autoplay": { "delay": 3000, "disableOnInteraction": false }, "pagination": { "el": ".swiper-pagination", "clickable": true, "dynamicBullets": true }, "breakpoints": { "768": { "slidesPerView": 2 } } }'>
                            <div class="swiper-wrapper">
                                <?php foreach($reviewList as $rl_obj): ?>
                                    <?php
                                        $image = $rl_obj->getReviewImage();
                                        $imageSrc = !empty($image) ? $image : 'review-logo.jpg';
                                        $cleanOutput = normalizeReviewText($rl_obj->getReviewText());
                                        $sanitized = strip_tags($cleanOutput, '<p><br><b><strong><i><ul><ol><li>');
                                        $safeTitle = htmlspecialchars(deepDecode($rl_obj->getReviewTitle()), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <!-- start testimonial item -->
                                    <div class="swiper-slide bg-white border-radius-4px">
                                        <div class="position-relative padding-5-rem-lr md-padding-3-rem-lr padding-4-half-rem-bottom padding-8-rem-top sm-padding-10-rem-top xs-padding-8-rem-top">
                                            <img
                                                alt="<?= htmlspecialchars($rl_obj->getReviewName()); ?>"
                                                src="<?= SERVERPATH ?>/images/reviewCompany/<?= htmlspecialchars($imageSrc); ?>"
                                                class="absolute-middle-center top-0px rounded-circle h-150px w-150px xs-w-100px border-all border-color-white box-shadow-extra-large border-width-8px"
                                            >
                                            <div class="testimonials-content">
                                                <span class="text-extra-medium text-extra-dark-gray margin-10px-bottom d-block font-weight-500">
                                                    <?= $safeTitle; ?>
                                                </span>
                                                <p class="margin-30px-bottom"><?= $sanitized; ?></p>
                                            </div>
                                            <div class="testimonials-author text-medium margin-5px-bottom primary text-uppercase font-weight-600 d-inline-block">
                                                <?php $content = $rl_obj->getReviewName(); ?>
                                                <?= isset($content) ? html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8') : '' ?>
                                            </div>
                                            <div class="testimonials-position text-medium">
                                                <?= htmlspecialchars($rl_obj->getReviewCompanyName()); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end testimonial item -->
                                <?php endforeach; ?>
                            </div>
                            <!-- start slider pagination -->
                            <div class="swiper-pagination"></div>
                            <!-- end slider pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section -->
