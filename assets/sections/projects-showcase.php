<?php
/**
 * Projects showcase: title, intro text and the featured projects (CMS > Projects, list "Featured").
 * Content = intro text below the title.
 * Preview: card-grid
 */
$featuredProjects = $projects->getProjectsList('featured');
?>
 <!-- start section -->
 <section class="bg-light-blue">
            <div class="container"<?= cms_anchor($fpc_obj) ?>>
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-4 col-lg-5 col-md-7 col-sm-9 text-center last-paragraph-no-margin margin-5-rem-bottom md-margin-3-rem-bottom wow animate__fadeIn">
                        <h4 class="alt-font font-weight-600 text-extra-dark-gray letter-spacing-minus-1px margin-20px-bottom sm-margin-10px-bottom"><?= cms_html($fpc_obj->getFptitle()) ?></h4>
                        <?= cms_content($fpc_obj->getFpcontent()) ?>
                    </div>
                </div>
            </div>
            <div class="container-fluid padding-6-rem-lr md-padding-2-rem-lr xs-padding-15px-lr">
                <div class="row">
                    <div class="col-12 filter-content">
                        <ul class="portfolio-classic portfolio-wrapper grid grid-loading grid-4col xl-grid-4col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
                            <li class="grid-sizer"></li>
                            <?php foreach ($featuredProjects as $projectIndex => $project_obj):
                                $projectImage = SERVERPATH . '/' . $project_obj->getProjectImagePath();
                                $projectUrl   = cms_url($project_obj->getProjectUrl() ?: '#');
                                $projectDelay = ($projectIndex % 4) * 0.2;
                            ?>
                            <!-- start portfolio item -->
                            <li class="grid-item wow animate__fadeIn"<?= $projectDelay > 0 ? ' data-wow-delay="' . $projectDelay . 's"' : '' ?>>
                                <div class="portfolio-box border-radius-6px box-shadow-large">
                                    <div class="portfolio-image bg-dark">
                                        <img src="<?= $projectImage ?>" alt="<?= cms_attr($project_obj->getProjectName()) ?>" />
                                        <div class="portfolio-hover align-items-center justify-content-center d-flex h-100">
                                            <div class="portfolio-icon">
                                                <a href="<?= $projectImage ?>" data-group="portfolio-items" class="lightbox-group-gallery-item text-slate-blue text-slate-blue-hover rounded-circle bg-white"><i class="fas fa-search icon-very-small" aria-hidden="true"></i></a>
                                                <a href="<?= $projectUrl ?>" target="_blank" class="text-slate-blue text-slate-blue-hover rounded-circle bg-white"><i class="fas fa-link icon-very-small" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portfolio-caption bg-white padding-30px-tb lg-padding-20px-tb">
                                        <a href="<?= $projectUrl ?>" target="_blank"><span class="alt-font text-extra-dark-gray font-weight-700"><?= $project_obj->getProjectName() ?></span></a>
                                        <span class="text-medium d-block margin-one-bottom"><?= $project_obj->getProjectCategory() ?></span>
                                    </div>
                                </div>
                            </li>
                            <!-- end portfolio item -->
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
