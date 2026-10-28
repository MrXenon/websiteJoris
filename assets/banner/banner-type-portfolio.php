<?php
/*
 * Banner type "portfolio": full screen hero with the banner title and rotating words.
 * Banner name  = title (simple HTML such as <br> is allowed)
 * Banner text  = rotating words, one per line / paragraph
 * The scroll icon links to the first content block on this page that has an "anchor" extra setting.
 */
$heroBanner = $banners[0] ?? null;
$heroAnchor = '';
foreach ($frontendPagesContentList as $anchorBlock) {
    if ($anchorBlock->getFpname() == $bannerPage && cms_extra($anchorBlock, 'anchor')) {
        $heroAnchor = cms_extra($anchorBlock, 'anchor');
        break;
    }
}
?>
        <!-- start hero section -->
        <?php if (!empty($heroBanner['image'])) { ?>
        <section class="p-0 cover-background" style="background-image: url('<?= cms_image('banners', $heroBanner['image']) ?>');">
            <div class="opacity-extra-medium bg-dark-slate-blue"></div>
            <div class="container">
                <div class="row align-items-stretch justify-content-center full-screen">
                    <div class="col-12 col-xl-6 col-lg-7 col-sm-8 position-relative page-title-extra-small text-center d-flex justify-content-center flex-column">
                        <h2 class="text-white alt-font font-weight-600 text-shadow-extra-large letter-spacing-minus-1px line-height-50 sm-no-text-shadow sm-line-height-45 xs-line-height-30 no-margin-bottom">
                           <?= cms_html($heroBanner['title']) ?>
                        </h2>
                        <?php $rotatingWords = cms_lines($heroBanner['text']); ?>
                        <?php if (!empty($rotatingWords)) { ?>
                        <h6 class="alt-font cd-headline letters rotate-2 text-white margin-seven-bottom">
                            <span></span>
                            <span class="cd-words-wrapper text-white font-weight-500">
                                <?php foreach ($rotatingWords as $wordIndex => $word) { ?>
                                <b<?= $wordIndex === 0 ? ' class="is-visible"' : '' ?>><?= str_replace(' ', ' &nbsp; ', htmlspecialchars($word)) ?></b>
                                <?php } ?>
                            </span>
                        </h6>
                        <?php } ?>
                    </div>
                    <div class="down-section text-center"><a href="#<?= htmlspecialchars($heroAnchor) ?>" class="section-link up-down-ani"><i class="ti-mouse icon-small text-white"></i></a></div>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- end hero section -->
