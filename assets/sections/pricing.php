<?php
/**
 * Pricing: heading, tab and pricing tables of the services linked to this page (CMS > Services), plus a request form.
 * Title = heading. Subtitle = tab label (e.g. "Jaarlijks"). Content = highlighted lines below the heading (paragraphs).
 * Per service: title = plan name, icon = plan icon, content = price as heading (h4) followed by a bulleted list with
 * the features; bold text in a feature is the value (e.g. "<strong>1</strong> Domein").
 * With an odd number of plans the middle plan is highlighted.
 * Setting: form | group | Request form
 * Setting: form.title | text | Title
 * Setting: form.intro | textarea | Intro text
 * Setting: form.name | text | Name field
 * Setting: form.email | text | E-mail field
 * Setting: form.plan | text | Plan choice (first option)
 * Setting: form.city | text | City field
 * Setting: form.postcode | text | Postcode field
 * Setting: form.company | text | Company field
 * Setting: form.submit | text | Send button
 * Preview: pricing
 */
$plans       = cms_services_for_page($servicesList, $bannerPage);
$planCount   = count($plans);
$popularPlan = ($planCount % 2 === 1) ? intdiv($planCount, 2) : -1;
$form        = cms_extra($fpc_obj, 'form');
?>
<section class="padding-40px-top md-no-padding-top"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center wow animate__fadeIn">
                    <div class="col-12 col-xl-5 col-lg-6 col-sm-7 text-center margin-5-rem-bottom md-margin-3-rem-bottom">
                        <h5 class="alt-font font-weight-600 text-extra-dark-gray d-inline-block letter-spacing-minus-1-half"><?= cms_html($fpc_obj->getFptitle()) ?></h5>
                        <?php foreach (cms_elements(cms_content($fpc_obj->getFpcontent()), 'p') as $highlight): ?>
                        <span class="alt-font font-weight-800 text-green text-extra-medium d-block margin-20px-bottom sm-margin-10px-bottom"><?= $highlight ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10 col-lg-11 tab-style-04">
                        <ul class="nav nav-tabs text-uppercase justify-content-center text-center alt-font margin-7-half-rem-bottom sm-margin-20px-bottom wow animate__fadeIn">
                            <li class="nav-item bg-white border-color-extra-light-gray"><a class="nav-link active" data-bs-toggle="tab" href="#yearly-tab"><?= cms_html($fpc_obj->getFpsubtitle()) ?></a><span class="tab-bg-active bg-green"></span></li>
                        </ul>
                        <div class="tab-content">
                            <div id="yearly-tab" class="tab-pane fade in active show">
                                <div class="row row-cols-1 row-cols-md-<?= max(1, $planCount) ?> align-items-center">
                                    <?php foreach ($plans as $planIndex => $plan):
                                        $planContent = cms_content($plan->getScontent());
                                        $planPrice   = cms_elements($planContent, 'h4')[0] ?? '';
                                        $planRows    = cms_elements($planContent, 'li');
                                        $isPopular   = $planIndex === $popularPlan;
                                        $isLastPlan  = $planIndex === $planCount - 1;

                                        if ($isPopular) {
                                            $planAnimation = 'wow animate__fadeIn z-index-1';
                                            $planDelay     = '';
                                        } else {
                                            $planAnimation = ($popularPlan === -1 || $planIndex < $popularPlan) ? 'wow animate__fadeInRight' : 'wow animate__fadeInLeft';
                                            $planDelay     = ' data-wow-delay="0.4s"';
                                        }
                                    ?>
                                    <div class="col pricing-table-style-02 text-center px-md-0<?= $isLastPlan ? '' : ' sm-margin-30px-bottom xs-margin-15px-bottom' ?> <?= $planAnimation ?>"<?= $planDelay ?>>
                                        <!-- start pricing table -->
                                        <div class="pricing-table <?= $isPopular ? 'pricing-popular bg-white box-shadow-large' : 'bg-white border-all border-color-medium-gray' ?> border-radius-6px">
                                            <!-- start pricing header -->
                                            <div class="pricing-header <?= $isPopular ? 'bg-extra-dark-gray' : 'bg-light-gray' ?> padding-20px-tb">
                                                <div class="alt-font font-weight-800 text-small <?= $isPopular ? 'text-white' : 'text-extra-dark-gray' ?> text-uppercase letter-spacing-minus-1-half"><?= cms_html($plan->getStitle()) ?></div>
                                            </div>
                                            <!-- end pricing header -->
                                            <!-- start pricing body -->
                                            <div class="pricing-body <?= $isPopular ? 'padding-60px-tb' : 'padding-40px-tb' ?>">
                                                <i class="<?= cms_attr($plan->getSicon()) ?> icon-medium text-green margin-20px-bottom"></i>
                                                <h4 class="font-weight-800 text-extra-dark-gray letter-spacing-minus-2px margin-15px-bottom"><?= $planPrice ?></h4>
                                                <ul class="list-style-03">
                                                    <?php foreach ($planRows as $rowIndex => $planRow): ?>
                                                    <li<?= $rowIndex === count($planRows) - 1 ? '' : ' class="border-color-medium-gray"' ?>><?= cms_strong_to_span($planRow, 'text-extra-dark-gray font-weight-800') ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <!-- end pricing body -->
                                        </div>
                                        <!-- end pricing table -->
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (is_array($form)): ?>
                        <form id="contact-form" action="<?= SERVERPATH ?>/email-templates/package.php" method="post" class="zoom-anim-dialog col-11 col-xl-8 col-lg-8 col-md-8 col-sm-9 mx-auto bg-white text-center modal-popup-main border-radius-6px sm-padding-2-half-rem-lr">
                            <div class="padding-fifteen-all bg-white border-radius-6px xs-padding-six-all">
                                <h6 class="text-extra-dark-gray font-weight-800 margin-35px-bottom xs-margin-15px-bottom"><?= htmlspecialchars($form['title'] ?? '') ?></h6>
                                <?php if (!empty($form['intro'])): ?>
                                <span class="alt-font font-weight-800 text-green text-extra-medium d-block margin-20px-bottom sm-margin-10px-bottom"><?= htmlspecialchars($form['intro']) ?></span>
                                <?php endif; ?>
                                <div>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="text" name="name" placeholder="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="email" name="email" placeholder="<?= htmlspecialchars($form['email'] ?? '') ?>" required>
                                    <select name="plan" class="medium-input margin-25px-bottom xs-margin-10px-bottom required">
                                        <option><?= htmlspecialchars($form['plan'] ?? '') ?></option>
                                        <?php foreach ($plans as $plan): ?>
                                        <option value="<?= cms_attr($plan->getStitle()) ?>"><?= cms_attr($plan->getStitle()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="text" name="woonplaats" placeholder="<?= htmlspecialchars($form['city'] ?? '') ?>" required>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom required" type="text" name="postcode" placeholder="<?= htmlspecialchars($form['postcode'] ?? '') ?>" required>
                                    <input class="medium-input margin-25px-bottom xs-margin-10px-bottom" type="text" name="bedrijf" placeholder="<?= htmlspecialchars($form['company'] ?? '') ?>">
                                    <input type="hidden" name="redirect" value="">
                                    <?php if (RECAPTCHA_SITE_KEY !== ''): ?>
                                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></div>
                                    <?php endif; ?>
                                    <br>
                                    <button class="btn btn-fancy btn-small btn-transparent-light-gray mb-0 submit" type="submit"><?= htmlspecialchars($form['submit'] ?? '') ?></button>
                                    <div class="form-results d-none"></div>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
