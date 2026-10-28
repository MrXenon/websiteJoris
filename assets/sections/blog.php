<?php
/**
 * Blog: grid of the blog posts (CMS > Blog) with pagination, 8 posts per page.
 * Setting: byLabel | text | Text before the author name | e.g. "Door"
 * Preview: card-grid
 */
$byLabel = (string)cms_extra($fpc_obj, 'byLabel', 'Door');
?>
       <!-- start section -->
        <section class="bg-light-gray pt-0 padding-eleven-lr xl-padding-two-lr xs-no-padding-lr"<?= cms_anchor($fpc_obj) ?>>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 blog-content">
                        <ul class="blog-grid blog-wrapper grid grid-loading grid-4col xl-grid-4col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large">
                            <li class="grid-sizer"></li>
                            <?php foreach($pagedBlogPosts as $bpc_obj ){
                                $date = new DateTime($bpc_obj->getBlogDate());
                                $datum_nl = $date->format('d') . ' ' . $maanden[$date->format('F')] . ' ' . $date->format('Y');
                                $postUrl = SERVERPATH . '/blogPage/' . str_replace(" ", "-", $bpc_obj->getBlogName());
                                ?>
                            <!-- start blog item -->
                            <li class="grid-item wow animate__fadeIn">
                                <div class="blog-post border-radius-5px bg-white box-shadow-medium">
                                    <div class="blog-post-image bg-medium-slate-blue">
                                        <a href="<?= $postUrl; ?>" title=""><img src="<?= SERVERPATH . "/images/" . $bpc_obj->getBlogFeaturedImage(); ?>" alt=""></a>
                                        <a href="<?= $postUrl; ?>" class="blog-category alt-font"><?= $bpc_obj->getBlogCategoryNameById($bpc_obj->getBlogCategory()); ?></a>
                                    </div>
                                    <div class="post-details padding-3-rem-lr padding-2-half-rem-tb">
                                        <a href="<?= $postUrl; ?>" class="alt-font text-small d-inline-block margin-10px-bottom"><?= $datum_nl; ?></a>
                                        <a href="<?= $postUrl; ?>" class="alt-font font-weight-500 text-extra-medium text-extra-dark-gray margin-15px-bottom d-block"><?= $bpc_obj->getBlogName(); ?></a>
                                        <p><?php $content = $bpc_obj->getBlogText(); echo truncateWithTooltip(nl2br(html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8')), 100); ?></p>
                                        <div class="d-flex align-items-center">
                                            <span class="alt-font text-small me-auto"><?= htmlspecialchars($byLabel) ?> <a href="<?= $postUrl; ?>"><?= $bpc_obj->getAuthorNameById($bpc_obj->getBlogUid()); ?></a></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- end blog item -->
                            <?php } ?>
                        </ul>
                        <!-- start pagination -->
                        <div class="col-12 d-flex justify-content-center margin-7-half-rem-top md-margin-5-rem-top wow animate__fadeIn">
                            <ul class="pagination pagination-style-01 text-small font-weight-500 align-items-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $currentPage - 1; ?>"><i class="feather icon-feather-arrow-left icon-extra-small d-xs-none"></i></a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($i === $currentPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $currentPage + 1; ?>"><i class="feather icon-feather-arrow-right icon-extra-small d-xs-none"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <!-- end pagination -->
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
