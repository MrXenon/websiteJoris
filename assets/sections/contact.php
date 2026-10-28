<?php
/**
 * Contact: contact details on the left, contact form on the right.
 * Title = heading of the contact details. Content = contact details; placeholders are replaced by the
 * system settings: {phone:Algemeen}, {phone:Support}, {email:Algemeen}, {email:Support}, ...
 * The reCAPTCHA site key comes from the system settings.
 * Setting: form | group | Contact form
 * Setting: form.name | text | Name field
 * Setting: form.email | text | E-mail field
 * Setting: form.phone | text | Phone field
 * Setting: form.message | text | Message field
 * Setting: form.submit | text | Send button
 * Preview: contact
 */
$form = cms_extra($fpc_obj, 'form', []);
$contactDetails = preg_replace_callback('/\{(phone|email):([^}]+)\}/', function ($m) use ($phoneNumbers, $emails) {
    $list = $m[1] === 'phone' ? ($phoneNumbers ?? []) : ($emails ?? []);
    return htmlspecialchars($list[$m[2]] ?? '');
}, cms_content($fpc_obj->getFpcontent()));
?>
         <!-- start section -->
 <section class="big-section wow animate__fadeIn"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-4 contact-col">
                         <h6 class="font-weight-600 text-black"><?= cms_html($fpc_obj->getFptitle()) ?></h6>
                         <?= cms_add_class($contactDetails, 'p', 'text-black') ?>
                    </div>
                    <div class="col-8 contact-col">
                        <form id="project-contact-form" action="email-templates/contact-form.php" method="post" novalidate="">
                            <div class="row row-cols-1 row-cols-md-2">
                                <div class="col margin-4-rem-bottom sm-margin-25px-bottom">
                                    <input class="medium-input bg-white margin-25px-bottom required" type="text" name="name" placeholder="<?= htmlspecialchars($form['name'] ?? '') ?>">
                                    <input class="medium-input bg-white margin-25px-bottom required" type="email" name="email" placeholder="<?= htmlspecialchars($form['email'] ?? '') ?>">
                                    <input class="medium-input bg-white mb-0" type="tel" name="phone" placeholder="<?= htmlspecialchars($form['phone'] ?? '') ?>">
                                </div>
                                <div class="col margin-1-rem-bottom sm-margin-10px-bottom">
                                    <textarea class="medium-textarea bg-white h-200px" rows="6" name="comment" placeholder="<?= htmlspecialchars($form['message'] ?? '') ?>"></textarea>
                                </div>
                                <div class="col text-start sm-margin-10px-bottom">
                                </div>
                                <div class="col text-center text-md-end">
                                    <input type="hidden" name="redirect" value="">
                                    <button
                                    id="project-contact-us-button"
                                    class="btn btn-large btn-round-edge-small btn-box-shadow primary-bg secondary btn-box-shadow align-self-center"
                                    type="submit"
                                    data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"
                                    data-callback='onSubmit'
                                    data-action='submit'><?= htmlspecialchars($form['submit'] ?? '') ?></button>
                                </div>
                            </div>
                            <div class="form-results d-none"></div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
