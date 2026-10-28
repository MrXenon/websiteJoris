<?php
/**
 * About: image on the left, subtitle, title, text and buttons on the right (dark background).
 * Subtitle = small text above the title. Content = paragraphs. Image = portrait.
 * Setting: buttons | list | Buttons
 * Setting: buttons[].label | text | Button text
 * Setting: buttons[].url | url | Link | #contact-form opens the contact form pop-up
 * Setting: form | group | Contact form pop-up | Leave empty when the site has no contact form
 * Setting: form.title | text | Title
 * Setting: form.name | text | Name field
 * Setting: form.email | text | E-mail field
 * Setting: form.phone | text | Phone field
 * Setting: form.message | text | Message field
 * Setting: form.submit | text | Send button
 * Preview: image-text
 */
$form    = cms_extra($fpc_obj, 'form');
$buttons = cms_extra($fpc_obj, 'buttons', []);
?>
 <!-- start section -->
    <section class="p-0 bg-extra-dark-gray wow animate__fadeIn"<?= cms_anchor($fpc_obj) ?>>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-4 ks-bg md-h-850px sm-h-850px xs-h-650px wow animate__fadeInLeft" data-wow-delay="0.2s" style="background-image: url('<?= cms_image('frontendPagesContent', $fpc_obj->getFpimage()) ?>');"></div>
                    <div class="col-12 col-lg-8 padding-5-half-rem-tb padding-5-half-rem-lr xl-padding-3-half-rem-all lg-padding-4-half-rem-all md-padding-5-half-rem-all xs-padding-5-rem-lr wow animate__fadeIn" data-wow-delay="0.6s">
                        <?php if ($fpc_obj->getFpsubtitle() !== ''): ?>
                        <span class="alt-font font-weight-500 text-extra-medium text-uppercase letter-spacing-minus-1-half d-block margin-25px-bottom"><?= cms_html($fpc_obj->getFpsubtitle()) ?></span>
                        <?php endif; ?>
                        <h4 class="alt-font font-weight-600 text-white letter-spacing-minus-1px w-65 margin-2-half-rem-bottom xl-w-100"><?= cms_html($fpc_obj->getFptitle()) ?></h4>
                        <?= cms_add_class(cms_content($fpc_obj->getFpcontent()), 'p', 'w-60 xl-w-100') ?>

                        <?php foreach ($buttons as $button):
                            $isPopup = ($button['url'] ?? '') === '#contact-form' && is_array($form);
                        ?>
                        <a class="btn btn-fancy btn-large btn-white margin-20px-top<?= $isPopup ? ' popup-with-move-anim' : '' ?>" href="<?= cms_url($button['url'] ?? '#') ?>"><?= htmlspecialchars($button['label'] ?? '') ?></a>
                        <?php endforeach; ?>

                        <?php if (is_array($form)): ?>
                        <!-- start modal pop-up -->
                        <form id="contact-form" action="<?= SERVERPATH ?>/email-templates/contact-form.php" method="post" class="zoom-anim-dialog col-11 col-xl-8 col-lg-8 col-md-8 col-sm-9 mx-auto bg-white text-center modal-popup-main padding-4-half-rem-all mfp-hide border-radius-6px sm-padding-2-half-rem-lr">
                            <div class="padding-fifteen-all bg-white border-radius-6px xs-padding-six-all">
                                <h6 class="text-extra-dark-gray font-weight-500 margin-35px-bottom xs-margin-15px-bottom"><?= htmlspecialchars($form['title'] ?? '') ?></h6>
                                <div>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="text" name="name" placeholder="<?= htmlspecialchars($form['name'] ?? '') ?>">
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="email" name="email" placeholder="<?= htmlspecialchars($form['email'] ?? '') ?>">
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom" type="tel" name="phone" placeholder="<?= htmlspecialchars($form['phone'] ?? '') ?>">
                                    <textarea class="medium-textarea xs-h-100px xs-margin-10px-bottom" rows="6" name="comment" placeholder="<?= htmlspecialchars($form['message'] ?? '') ?>"></textarea>
                                    <input type="hidden" name="redirect" value="">
                                    <?php if (RECAPTCHA_SITE_KEY !== ''): ?>
                                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></div>
                                    <?php endif; ?>
                                    <button class="btn btn-fancy btn-small btn-transparent-light-gray mb-0 submit" type="submit"><?= htmlspecialchars($form['submit'] ?? '') ?></button>
                                    <div class="form-results d-none"></div>
                                </div>
                            </div>
                        </form>
                        <!-- end modal pop-up -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    </section>
<!-- end section -->
