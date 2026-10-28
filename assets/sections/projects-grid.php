<?php
/**
 * Projects grid: page title and a filterable grid with all projects (CMS > Projects, list "Projects overview").
 * Title = page title above the grid.
 * Content = a bulleted list with the filter tabs. The first item shows all projects, the other items
 *           filter on the first word of the project category (e.g. "Statische websites" -> "Statische Website").
 * Setting: emptyText | text | Text when there are no projects
 * Preview: card-grid
 */
$overviewProjects = $projects->getProjectsList('projects');
$projectFilters   = cms_elements(cms_content($fpc_obj->getFpcontent()), 'li');
?>
<!-- start page title -->
<section class="half-section bg-light-gray"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row align-items-stretch justify-content-center extra-small-screen">
                    <div class="col-12 col-xl-6 col-lg-7 col-md-8 page-title-extra-small text-center d-flex justify-content-center flex-column">
                        <h2 class="text-extra-dark-gray alt-font font-weight-500 letter-spacing-minus-1px line-height-50 sm-line-height-45 xs-line-height-30 no-margin-bottom"><?= cms_html($fpc_obj->getFptitle()) ?></h2>
                    </div>
                </div>
            </div>
        </section>
        <!-- end page title -->
        <!-- start section -->
        <section class="padding-four-lr bg-light-gray no-padding-top lg-padding-two-lr sm-no-padding-lr">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <!-- start filter navigation -->
                        <ul class="portfolio-filter grid-filter nav nav-tabs justify-content-center border-0 text-uppercase font-weight-500 alt-font padding-6-rem-bottom md-padding-4-half-rem-bottom sm-padding-2-rem-bottom">
                            <?php foreach ($projectFilters as $filterIndex => $projectFilter):
                                $filterLabel = trim(strip_tags($projectFilter));
                                $filterWords = preg_split('/\s+/', html_entity_decode($filterLabel, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                                $filterValue = $filterIndex === 0 ? '*' : '.' . preg_replace('/[^\w-]/u', '', $filterWords[0]);
                            ?>
                            <li class="nav<?= $filterIndex === 0 ? ' active' : '' ?>"><a data-filter="<?= htmlspecialchars($filterValue) ?>" href="#"><?= $filterLabel ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <!-- end filter navigation -->
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 filter-content">
                        <ul class="portfolio-classic portfolio-wrapper grid grid-loading grid-4col xl-grid-4col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
                            <li class="grid-sizer"></li>
                            <?php if (empty($overviewProjects)): ?>
                                <div class="alert text-center alert-danger"><?= htmlspecialchars((string)cms_extra($fpc_obj, 'emptyText', '')) ?></div>
                            <?php endif; ?>
                            <?php foreach ($overviewProjects as $project_obj):
                                $projectImage = SERVERPATH . '/' . $project_obj->getProjectImagePath() . '?v=1';
                                $projectUrl   = cms_url($project_obj->getProjectUrl() ?: '#');
                            ?>
                            <!-- start portfolio item -->
                            <li class="grid-item <?= cms_attr($project_obj->getProjectCategory()) ?> wow animate__fadeIn">
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
                                        <a href="<?= $projectUrl ?>" target="_blank"><span class="alt-font text-extra-dark-gray font-weight-500"><?= $project_obj->getProjectName() ?></span></a>
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
