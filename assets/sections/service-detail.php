<?php
/**
 * Service detail: the services linked to this page (CMS > Services): title, text and image.
 * The content block itself only positions the services on the page.
 * Setting: certificatesTitle | text | Title above the certificates
 * Setting: certificates | list | Certificates | Shown below the service
 * Setting: certificates[].image | image | Logo
 * Setting: certificates[].alt | text | Description
 * Preview: text-image
 */
$certificates      = cms_extra($fpc_obj, 'certificates', []);
$certificatesTitle = cms_extra($fpc_obj, 'certificatesTitle', '');

foreach (cms_services_for_page($servicesList, $bannerPage) as $sl_obj) { ?>
               <section class="padding-60px-top padding-60px-bottom"<?= cms_anchor($fpc_obj) ?>>
                    <div class="container">
                         <div class="row">
                              <div class="col-md-6">
                                   <h5 class="font-weight-600 text-black margin-5px-bottom"><?= $sl_obj->getStitle(); ?></h5>
                                   <div class="text-black"><?php $content = $sl_obj->getScontent(); echo nl2br(html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8')); ?></div>
                              </div>
                              <div class="col-md-6">
                                   <img class="margin-55px-top" src="<?= SERVERPATH ?>/images/services/<?= $sl_obj->getSimage();?>">
                              </div>
                         </div>
                    <?php if (!empty($certificates)) { ?>
                        <div class="row">
                              <div class="col-md-10">
                                   <h5 class="font-weight-600 text-black margin-25px-bottom"><?= htmlspecialchars($certificatesTitle) ?></h5>
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="row">
                                                    <?php foreach ($certificates as $certificate) { ?>
                                                    <div class="col-md-4">
                                                        <img src="<?= cms_image('frontendPagesContent', $certificate['image'] ?? '') ?>" class="img-fluid" alt="<?= htmlspecialchars($certificate['alt'] ?? '') ?>" style="max-width: 250px;">
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                              </div>
                         </div>
                    <?php } ?>
                    </div>
               </section>
<?php } ?>
