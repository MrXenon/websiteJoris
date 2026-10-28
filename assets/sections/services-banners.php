<?php
/**
 * Services banners: interactive image banners of the services linked to this page (CMS > Services).
 * Per service: title (simple HTML such as <br /> allowed), content (text on hover), image and icon.
 * The content block itself only positions the row, title and content are not used.
 * Preview: tiles
 */
$pageServices = cms_services_for_page($servicesList, $bannerPage);
$serviceDelays = ['0.2s', '0.8s', '0.4s', '0.6s'];
?>
<!-- start section -->
<section class="py-0 lg-padding-20px-lr xs-no-padding-lr"<?= cms_anchor($fpc_obj) ?>>
            <div class="container-fluid padding-30px-lr xs-padding-15px-lr">
                <div class="row justify-content-center">
                    <?php foreach ($pageServices as $serviceIndex => $service):
                        $isLastService = $serviceIndex === count($pageServices) - 1;
                    ?>
                    <!-- start interactive banner item -->
                    <div class="col-12 col-xl-3 col-md-6 col-sm-8 interactive-banners-style-09<?= $isLastService ? '' : ' lg-margin-30px-bottom xs-margin-15px-bottom' ?> wow animate__fadeIn" data-wow-delay="<?= $serviceDelays[$serviceIndex % count($serviceDelays)] ?>">
                        <figure class="m-0">
                            <img class="w-100" src="<?= cms_image('services', $service->getSimage()) ?>" alt="<?= cms_attr($service->getStitle()) ?>" />
                            <div class="opacity-light bg-extra-dark-gray"></div>
                            <div class="interactive-banners-overlay bg-dark"></div>
                            <figcaption>
                                <div class="interactive-banners-content align-items-start padding-4-half-rem-lr padding-5-rem-tb last-paragraph-no-margin xl-padding-2-rem-all lg-padding-4-rem-all xs-padding-5-rem-all">
                                    <span class="text-extra-medium text-white opacity-6 d-block margin-10px-bottom position-relative z-index-1"></span>
                                    <h5 class="alt-font font-weight-600 text-white position-relative z-index-1"><?= cms_html($service->getStitle()) ?></h5>
                                    <span class="interactive-banners-hover-icon"><i class="<?= cms_attr($service->getSicon()) ?> text-white icon-large"></i></span>
                                </div>
                                <div class="interactive-banners-hover-action align-items-end d-flex">
                                    <div class="padding-4-half-rem-lr padding-5-rem-tb last-paragraph-no-margin lg-padding-4-rem-all xl-padding-3-rem-all xs-padding-5-rem-all">
                                        <?= cms_add_class(cms_content($service->getScontent()), 'p', 'w-80 text-white opacity-6 lg-w-70') ?>
                                    </div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                    <!-- end interactive banner item -->
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- end section -->
