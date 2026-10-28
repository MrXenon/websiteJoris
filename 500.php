<?php
/* Copyright (C) Kevin Schuit - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Kevin Schuit <info@kevinschuit.com>, July 2022
 */
  session_start();
  include_once './php/callDatabaseConfig.php';
  require "./php/methods.php";


$page = basename(__FILE__);
$realPageName = ucfirst(basename($page, '.php'));
$title =  ucfirst(basename($page, '.php'));
$bannerPage = $banner->getPageIdByPageName($title);
$bannerData = $banner->getBannerListByPage($bannerPage ?? 0);
$banners = $banner->getBannersByPage($bannerPage ?? 0);
$bannerType = $bannerData['type'];
$bannerName = $banner->getBannerTypeByType($bannerType);
$meta   = $front_pages->getMetaTagsByTitle($title);
$title =   preg_replace('/[-_]+/', ' ', $title);
if ($title == 'Index') { $title = 'Home'; }
?>

<!DOCTYPE html>
<html class="no-js" lang="nl">
  <head>
    <title><?= SITENAME;?> | <?= $title;?></title>
    <?php foreach($meta as $m){ ?>
    <meta http-equiv="Cache-control" content="<?= $m->getFrontPageCache() .' '.$m->getFrontPageStore().' '.$m->getFrontPageRevalidate();?>">
    <meta http-equiv="Pragma" content="<?= $m->getFrontPageCache();?>">
    <meta http-equiv="Expires" content="<?= $m->getFrontPageExpires();?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="<?= $m->getFrontPageIndex() . ' '.$m->getFrontPageRobots();?>">
    <meta name="author" content="<?= $m->getFrontPageAuthor();?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
    <meta name="description" content="<?= $m->getFrontPageDescription();?>">
    <meta name="keywords" content="<?= $m->getFrontPageKeywords();?>">
   <?php }?>
<?php include_once 'assets/includes/headscripts.php'; ?><!-- CALL HEAD SCRIPTS -->
  </head>
    <body data-mobile-nav-style="classic" style="display: flex; flex-direction: column; min-height: 100vh;">
        <?php include_once 'assets/includes/header.php'; ?><!-- CALL HEADER -->
        <!-- start main -->
        <main style="flex: 1">
            <?php include "assets/banner/banner-type-$bannerName.php"; ?><!-- CALL BANNER -->

                    <?php foreach ($frontendPagesContentList as $fpc_obj):
                        if ($bannerPage === null || $fpc_obj->getFpname() != $bannerPage) continue;

                        // Content block with a front-end section layout (assets/sections/{layout}.php)
                        $layout = basename((string)$fpc_obj->getFplayout());
                        if ($layout !== '' && is_file(__DIR__ . '/assets/sections/' . $layout . '.php')) {
                            include __DIR__ . '/assets/sections/' . $layout . '.php';
                            continue;
                        }

                        // Default content block
                        $blockTitle = $fpc_obj->getFptitle();
                        $content = html_entity_decode(mb_convert_encoding($fpc_obj->getFpcontent(), 'HTML-ENTITIES', 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $image = $fpc_obj->getFpimage();
                        $columns = $fpc_obj->getNumberOfColumn();
                        $columnOrder = $fpc_obj->getColumnOrder();
                    ?>
                        <?php echo margin(50); ?>
                        <section class="padding-60px-top padding-60px-bottom">
                            <div class="container">
                                <div class="row">
                                    <?php if ($columns == 1): ?>
                                        <div class="col-md-12">
                                            <?php if (!empty($content)): ?>
                                                <h3 class="font-weight-600 text-black margin-5px-bottom">
                                                    <?php
                                                        $words = explode(' ', htmlspecialchars($blockTitle));
                                                        $last = array_pop($words);
                                                        echo implode(' ', $words) . ' <span class="primary">' . $last . '</span>';
                                                    ?>
                                                </h3>
                                                <div class="text-black"><?= $content; ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($image)): ?>
                                                <img class="margin-55px-top" src="<?= SERVERPATH ?>/images/frontendPagesContent/<?= htmlspecialchars($image); ?>">
                                            <?php endif; ?>
                                        </div>

                                    <?php elseif ($columns == 2): ?>
                                        <?php if($columnOrder == 1): ?>
                                        <div class="col-md-6">
                                            <?php if (!empty($blockTitle) || !empty($content)): ?>
                                                <h3 class="font-weight-600 text-black margin-5px-bottom">
                                                    <?php
                                                        $words = explode(' ', htmlspecialchars($blockTitle));
                                                        $last = array_pop($words);
                                                        echo implode(' ', $words) . ' <span class="primary">' . $last . '</span>';
                                                    ?>
                                                </h3>
                                                <div class="text-black"><?= $content; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if (!empty($image)): ?>
                                                <img class="margin-55px-top" src="<?= SERVERPATH ?>/images/frontendPagesContent/<?= htmlspecialchars($image); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($columnOrder == 2): ?>
                                        <div class="col-md-6">
                                            <?php if (!empty($image)): ?>
                                                <img class="margin-55px-top" src="<?= SERVERPATH ?>/images/frontendPagesContent/<?= htmlspecialchars($image); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if (!empty($blockTitle) || !empty($content)): ?>
                                                <h3 class="font-weight-600 text-black margin-5px-bottom">
                                                    <?php
                                                        $words = explode(' ', htmlspecialchars($blockTitle));
                                                        $last = array_pop($words);
                                                        echo implode(' ', $words) . ' <span class="primary">' . $last . '</span>';
                                                    ?>
                                                </h3>
                                                <div class="text-black"><?= $content; ?></div>
                                            <?php endif; ?>
                                        </div>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    <?php endforeach; ?>
                    <!-- end section -->
        </main>
        <!-- end main -->

        <?php include_once 'assets/includes/footer.php'; ?><!-- CALL FOOTER -->
        <?php include_once 'assets/includes/scripts.php'; ?><!-- CALL SCRIPTS -->
    <?php echo scroll_to(); ?>
  </body>
</html>
