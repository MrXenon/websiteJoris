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

$token = $_GET['token'] ?? '';
$resetResult = [];
// If the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $resetResult = $user->handlePasswordReset($_POST, $token);
}
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

        <!-- start main -->
        <main style="flex: 1">
            <?php include "assets/banner/banner-type-$bannerName.php"; ?><!-- CALL BANNER -->
                <section class="padding-60px-top padding-60px-bottom">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <h5 class="font-weight-600 text-black margin-5px-bottom">
                                    Wachtwoord <span class="primary">Resetten</span>
                                </h5>

                                <?php if (!empty($resetResult['error'])): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($resetResult['error']) ?></div>
                                <?php elseif (!empty($resetResult['success'])): ?>
                                    <div class="alert alert-success"><?= $resetResult['success'] ?></div>
                                <?php endif; ?>

                                <?php if (empty($resetResult['success'])): ?>
                                    <form method="post" class="reset-password-form">
                                        <div class="form-group">
                                            <label for="password">Nieuw wachtwoord</label>
                                            <input type="password" name="password" id="password" class="form-control" required minlength="8">
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirm">Bevestig wachtwoord</label>
                                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" required minlength="8">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Reset Wachtwoord</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
        </main>
        <!-- end main -->
        <a class="scroll-top-arrow" href="javascript:void(0);"><i class="feather icon-feather-arrow-up"></i></a>
        <?php include_once 'assets/includes/footer.php'; ?><!-- CALL FOOTER -->
        <?php include_once 'assets/includes/scripts.php'; ?><!-- CALL SCRIPTS -->
         <script>
            document.querySelector('.reset-password-form')?.addEventListener('submit', function (e) {
                const pass = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirm').value;

                if (pass !== confirm) {
                    e.preventDefault();
                    alert('Wachtwoorden komen niet overeen.');
                }
            });
        </script>
  </body>
</html>