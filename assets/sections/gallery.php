<?php
/**
 * Gallery: filterable photo grid of all gallery images (CMS > Gallery), with a filter per
 * gallery category that has images.
 * Setting: allLabel | text | Label of the "all photos" filter | e.g. "All"
 * Preview: card-grid
 */
$galleryList         = $gallery->getGalleryList();
$galleryCategoryList = $galleryCategory->getGalleryCategoryList();

// Collect all categories that are actually used in galleryList
$usedCategoryIds = [];
foreach ($galleryList as $galleryItem) {
    $usedCategoryIds[] = $galleryItem->getGalleryCategory();
}
$usedCategoryIds = array_unique($usedCategoryIds);
?>
        <section class="bg-light-gray pt-0"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <!-- start filter navigation -->
                        <ul class="portfolio-filter grid-filter nav nav-tabs justify-content-center border-0 text-uppercase font-weight-500 alt-font padding-6-rem-bottom md-padding-4-half-rem-bottom sm-padding-2-rem-bottom padding-6-rem-top md-padding-4-half-rem-top sm-padding-2-rem-top">
                            <li class="nav active"><a data-filter="*" href="#"><?= htmlspecialchars((string)cms_extra($fpc_obj, 'allLabel', 'All')) ?></a></li>
                            <?php foreach ($galleryCategoryList as $gcln): ?>
                                <?php if (in_array($gcln->getGalleryCategoryId(), $usedCategoryIds)): ?>
                                    <li class="nav">
                                        <a data-filter=".<?= htmlspecialchars($gcln->getGalleryCategoryDataType()); ?>" href="#">
                                            <?= htmlspecialchars($gcln->getGalleryCategoryName()); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <!-- end filter navigation -->
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 filter-content">
                        <ul class="portfolio-classic portfolio-wrapper grid grid-loading grid-4col xl-grid-4col lg-grid-4col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
                            <li class="grid-sizer"></li>

                            <!-- start portfolio items -->
                            <?php foreach ($galleryList as $glnp): ?>
                                <?php
                                $categoryId = $glnp->getGalleryCategory();
                                $categoryObj = $gallery->getGalleryCategoryById($categoryId);
                                $dataTypeClass = $categoryObj ? $categoryObj->getGalleryCategoryDataType() : 'uncategorized';
                                ?>
                                <li class="grid-item <?= htmlspecialchars($dataTypeClass); ?> wow animate__fadeIn">
                                    <div class="portfolio-box border-radius-6px">
                                        <div class="portfolio-image bg-nero-gray">
                                            <img src="<?= SERVERPATH ?>/images/galerij/<?= htmlspecialchars($glnp->getGalleryImage()); ?>" alt="" />
                                            <div class="portfolio-hover align-items-center justify-content-center d-flex h-100">
                                                <div class="portfolio-icon">
                                                    <a href="<?= SERVERPATH ?>/images/galerij/<?= htmlspecialchars($glnp->getGalleryImage()); ?>" data-group="portfolio-items" class="lightbox-group-gallery-item text-slate-blue text-slate-blue-hover rounded-circle bg-white">
                                                        <i class="fas fa-search icon-very-small" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            <!-- end portfolio items -->
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
