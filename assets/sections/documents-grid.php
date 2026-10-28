<?php
/**
 * Documents grid: cards with an image and a title, each linking to a document (e.g. a PDF).
 * Content = a bulleted list of links, one card per link: <a href="images/...pdf">Title</a>.
 * Image = image shown on every card.
 * Preview: card-grid
 */
$documents = [];
foreach (cms_elements(cms_content($fpc_obj->getFpcontent()), 'li') as $documentItem) {
    if (preg_match('/<a\s[^>]*href=("|\')(.*?)\1[^>]*>(.*?)<\/a>/is', $documentItem, $documentLink)) {
        $documents[] = ['url' => $documentLink[2], 'title' => trim(strip_tags($documentLink[3]))];
    }
}
$documentImage = cms_image('frontendPagesContent', $fpc_obj->getFpimage());
?>
        <style>
            .post-details{background-color: #262b35;}
        </style>
        <!-- start section -->
        <section class="bg-light-gray"<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row justify-content-center">
                </div>
                <div class="row">
                    <div class="col-12 blog-content">
                        <ul class="blog-clean blog-wrapper grid grid-loading grid-3col xl-grid-3col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large">
                            <li class="grid-sizer"></li>
                            <?php foreach ($documents as $documentIndex => $document):
                                $documentUrl   = cms_url($document['url']);
                                $documentDelay = min($documentIndex * 0.2, 0.4);
                            ?>
                            <!-- start blog item -->
                            <li class="grid-item wow animate__fadeIn"<?= $documentDelay > 0 ? ' data-wow-delay="' . $documentDelay . 's"' : '' ?>>
                                <div class="blog-post text-center border-radius-6px bg-white box-shadow box-shadow-large-hover">
                                    <div class="blog-post-image bg-gradient-fast-blue-purple">
                                        <a href="<?= $documentUrl ?>" target="_blank"><img src="<?= $documentImage ?>" alt="">
                                            <div class="blog-rounded-icon bg-white border-color-white absolute-middle-center">
                                                <i class="feather icon-feather-arrow-right text-extra-dark-gray icon-extra-small"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="post-details padding-30px-all xl-padding-25px-lr">
                                        <a href="<?= $documentUrl ?>" target="_blank" class="text-white font-weight-500 alt-font d-block"><?= $document['title'] ?></a>
                                    </div>
                                </div>
                            </li>
                            <!-- end blog item -->
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
