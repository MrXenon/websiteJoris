<?php
/**
 * Section heading: dark band with a centered heading.
 * Title = heading.
 * Setting: pattern | image | Background pattern
 * Preview: band
 */
$headingPattern = cms_extra($fpc_obj, 'pattern');
?>
        <!-- start section -->
        <section class="bg-222 overlap-height padding-80px-bottom sm-padding-50px-bottom"<?= $headingPattern ? " style=\"background-image: url('" . cms_image('frontendPagesContent', $headingPattern) . "');\"" : '' ?><?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-7 col-lg-8 col-md-10 text-center overlap-gap-section wow animate__fadeIn">
                        <h5 class="alt-font font-weight-500 text-white d-inline-block w-90 margin-10px-bottom"><?= cms_html($fpc_obj->getFptitle()) ?></h5>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
