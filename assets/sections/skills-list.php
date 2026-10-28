<?php
/**
 * Skills list: title with a list of skills in two columns and an image on the right.
 * Content = a bulleted list, one skill per list item. Image = mockup on the right.
 * Setting: imageAlt | text | Image description
 * Preview: list-image
 */
$skills      = cms_elements(cms_content($fpc_obj->getFpcontent()), 'li');
$skillColumns = $skills ? array_chunk($skills, (int)ceil(count($skills) / 2)) : [];
?>
 <!-- start section -->
 <section class="web-agency wow animate__fadeIn"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-xl-5 col-lg-6 col-md-9 md-margin-3-rem-bottom">
                        <div class="col-12 p-0 margin-5-rem-bottom wow animate__fadeIn">
                            <h4 class="alt-font font-weight-600 text-extra-dark-gray d-block letter-spacing-minus-1px"><?= cms_html($fpc_obj->getFptitle()) ?></h4>
                        </div>
                        <div class="row justify-content-center">
                        <?php foreach ($skillColumns as $skillColumn): ?>
                        <ul class="list-style-04 col-6">
                            <?php foreach ($skillColumn as $skillIndex => $skill):
                                $isLast = $skillIndex === count($skillColumn) - 1;
                            ?>
                            <li class="bg-white border-radius-6px<?= $isLast ? '' : ' margin-20px-bottom' ?>">
                                <i class="far fa-dot-circle text-fast-blue <?= $isLast ? 'margin-15px-right' : 'margin-20px-right' ?>" aria-hidden="true"></i>
                                <span class="text-extra-dark-gray"><?= $skill ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endforeach; ?>
                </div>
                    </div>
                    <?php if ($fpc_obj->getFpimage() !== ''): ?>
                    <div class="col-12 col-lg-6 offset-xl-1 wow animate__fadeInRight" data-wow-delay="0.5s">
                        <div class="outside-box-right position-relative">
                            <img src="<?= cms_image('frontendPagesContent', $fpc_obj->getFpimage()) ?>" class="overflow-hidden" alt="<?= htmlspecialchars((string)cms_extra($fpc_obj, 'imageAlt', '')) ?>" />
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- end section -->
