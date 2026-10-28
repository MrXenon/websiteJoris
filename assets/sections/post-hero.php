<?php
/**
 * Post hero: large background image with an overlapping (tilting) title box.
 * Title = title in the box. Image = background image.
 * Subtitle = line below the title; {author} and {date} are replaced by the extra settings below.
 * Setting: author | text | Author | Replaces {author} in the subtitle
 * Setting: date | text | Date | Replaces {date} in the subtitle
 * Setting: pattern | image | Background pattern of the title box
 * Preview: hero
 */
$postMetaLink = function ($text) {
    return '<a href="#" class="d-inline-block text-white-transparent text-white-hover">' . htmlspecialchars((string)$text) . '</a>';
};
$postMeta = strtr(cms_html($fpc_obj->getFpsubtitle()), [
    '{author}' => $postMetaLink(cms_extra($fpc_obj, 'author', '')),
    '{date}'   => $postMetaLink(cms_extra($fpc_obj, 'date', '')),
]);
$postPattern = cms_extra($fpc_obj, 'pattern');
?>
        <!-- start section-->
        <section class="h-700px sm-h-400px xs-h-300px overlap-height cover-background" style="background: url(<?= cms_image('frontendPagesContent', $fpc_obj->getFpimage()) ?>);"<?= cms_anchor($fpc_obj) ?>>
        <div class="opacity-extra-medium bg-dark-slate-blue"></div>
</section>
        <!-- end section -->
        <!-- start section-->
        <section class="pb-0 pt-md-0 overflow-visible">
            <div class="container">
                <div class="row justify-content-center overlap-section z-index-0">
                    <div class="col-12 col-lg-10 alt-font text-center bg-222 text-white padding-7-rem-tb position-relative tilt-box" data-tilt-options='{ "maxTilt": 20, "perspective": 1000, "easing": "cubic-bezier(.03,.98,.52,.99)", "scale": 1, "speed": 500, "transition": true, "reset": true, "glare": false, "maxGlare": 1 }'<?= $postPattern ? " style=\"background-image: url('" . cms_image('frontendPagesContent', $postPattern) . "');\"" : '' ?>>
                        <div class="w-1px h-90px bg-white mx-auto absolute-middle-center top-0px"></div>
                        <h3 class="font-weight-600 w-50 mx-auto md-w-70 xs-w-90"><?= cms_html($fpc_obj->getFptitle()) ?></h3>
                        <?php if ($postMeta !== ''): ?>
                        <div class="d-inline-block text-white-transparent margin-5px-top"><?= $postMeta ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
