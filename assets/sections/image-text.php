<?php
/**
 * Image and text: image on the left, title and text on the right.
 * Title = heading next to the image. Content = text. Image = image on the left.
 * Preview: image-text
 */
?>
        <!-- start section -->
        <section class="big-section"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 p-sm-0">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6 sm-margin-30px-bottom wow animate__fadeIn" data-wow-delay="0.2s"><img src="<?= cms_image('frontendPagesContent', $fpc_obj->getFpimage()) ?>" alt="" /></div>
                            <div class="col-12 col-lg-5 offset-lg-1 col-md-6 last-paragraph-no-margin wow animate__fadeIn" data-wow-delay="0.4s">
                                <h5 class="alt-font text-extra-dark-gray margin-35px-bottom font-weight-500 xs-margin-25px-bottom"><?= cms_html($fpc_obj->getFptitle()) ?></h5>
                                <?= cms_content($fpc_obj->getFpcontent()) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
