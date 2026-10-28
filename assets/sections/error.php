<?php
/**
 * Error message: subtitle, big title, message and a button (used on the 401, 403, 404 and 500 pages).
 * Subtitle = small text above the title (e.g. "Oeps!"). Title = big title (empty = page name, e.g. "404").
 * Content = message.
 * Setting: button | group | Button
 * Setting: button.label | text | Button text
 * Setting: button.url | url | Link
 * Preview: message
 */
$errorButton = cms_extra($fpc_obj, 'button');
?>
        <!-- start section -->
        <section class=" wow animate__fadeIn"<?= cms_anchor($fpc_obj) ?>>
                    <div id="container" class="container">
                        <div class="row align-items-stretch justify-content-center error-page">
                            <div class="col-12 text-center d-flex align-items-center justify-content-center flex-column">
                                <h6 class="alt-font text-fast-blue font-weight-600 letter-spacing-minus-1px margin-10px-bottom text-uppercase"><?= cms_html($fpc_obj->getFpsubtitle()) ?></h6>
                                <h1 class="alt-font text-big font-weight-700 letter-spacing-minus-5px text-extra-dark-gray margin-6-rem-bottom md-margin-4-rem-bottom"><?= $fpc_obj->getFptitle() !== '' ? cms_html($fpc_obj->getFptitle()) : htmlspecialchars($title) ?></h1>
                                <span class="alt-font font-weight-500 text-extra-dark-gray d-block margin-20px-bottom"><?= strip_tags(cms_content($fpc_obj->getFpcontent()), '<br><a><strong><em>') ?></span><br><br><br>
                                <?php if (is_array($errorButton)): ?>
                                <a href="<?= cms_url($errorButton['url'] ?? '/') ?>" class="btn btn-large btn-fast-blue d-table d-lg-inline-block lg-margin-15px-bottom md-margin-auto-lr"><i class="fa fa-arrow-left left-icon"></i><?= htmlspecialchars($errorButton['label'] ?? '') ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- end section -->
