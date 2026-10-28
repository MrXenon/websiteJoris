<?php
/* Copyright (C) Kevin Schuit - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Kevin Schuit <info@kevinschuit.com>, July 2022
 */
  session_start();
  include_once './php/callDatabaseConfig.php';
  require "./php/methods.php";

    $postArray = $user->getPostValues();
    $success = '';
    $error = '';

    if (isset($_POST['submit'])) {
        $result = $user->requestPasswordReset($postArray);

        if (isset($result['success'])) {
            $success = $result['success'];
        } else {
            $error = $result['error'];
        }
    }

$page = basename(__FILE__); 
$realPageName = ucfirst(basename($page, '.php'));
$title =  ucfirst(basename($page, '.php'));
$bannerPage = $banner->getPageIdByPageName($title);
$bannerData = $banner->getBannerListByPage($bannerPage);
$banners = $banner->getBannersByPage($bannerPage);
$bannerType = $bannerData['type'];
$bannerName = $banner->getBannerTypeByType($bannerType);
$meta   = $front_pages->getMetaTagsByTitle($title);
$title =   preg_replace('/-+/', ' ', $title);
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?= SITENAME;?> | <?= $title;?></title>
    <!-- Meta -->
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
<main>
      <?php include "assets/banner/banner-type-$bannerName.php"; ?><!-- CALL BANNER -->
        <section id="login" style="padding: 50px 0 100px; 0;">
          <div id="container" class="container">
              <?= $error;
              ?>
              <div class="row">
                  <div class="col-md-12">
                      <form action="" method="post" name="forgot-password" class="needs-validation" novalidate>
                          <small><span class='red'>*</span> = required</small>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label for="emailusername">E-mail <span class='red'>*</span></label>
                                  <input type="email" class="form-control" id="emailusername" name="email" placeholder="" required>
                              </div>
                          </div>
                          <input class="btn btn-primary" type="submit" name="submit" value="Verstuur reset link">
                      </form>
                      <br>
                      <?= $error . $success ?>
                  </div>
              </div>
          </div>
      </section>
</main>
        <!-- start scroll to top -->
        <a class="scroll-top-arrow" href="javascript:void(0);"><i class="feather icon-feather-arrow-up"></i></a>
        <?php include_once 'assets/includes/footer.php'; ?><!-- CALL FOOTER -->
        <?php include_once 'assets/includes/scripts.php'; ?><!-- CALL SCRIPTS -->
  </body>
</html>

