<?php
/* Copyright (C) Kevin Schuit - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Kevin Schuit <info@kevinschuit.com>, July 2022
 */
  session_start();
  include_once './php/callDatabaseConfig.php';
  require "./php/methods.php";

//Check if user session exists, if true, send user to admin panel, if false, continue to login screen.
if(isset($_SESSION['uid'])){
$uid = $_SESSION['uid'];

if ($user->get_session()){
    header("location:".SERVERPATH."/admin/index");
    exit;
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

$error = '';
if (isset($_POST['submit'])) {
    extract($_POST);
    $login = $user->check_login($emailusername, $password);
    if ($login) {
        // Registration Success
        header("location:".SERVERPATH."/admin/index");
    } else {
        // Registration Failed
        $error = "<div class='alert alert-danger text-center' >
                        <strong>Inloggen mislukt</strong>, de gebruikersnaam/email en wachtwoord komen niet overeen of bestaan niet.
                      </div>";
    }
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

              <section id="login" style="padding: 50px 0 100px; 0;">
                <div id="container" class="container">
                    <?= $error;
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post" name="login" class="needs-validation" novalidate>
                                <small><span class='red'>*</span> = required</small>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="emailusername">E-mail <span class='red'>*</span></label>
                                        <input type="text" class="form-control" id="emailusername" name="emailusername" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="password">Wachtwoord <span class='red'>*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="" required>
                                    </div>
                                </div>
                                <input class="btn btn-primary" type="submit" name="submit" value="Inloggen" onclick="return(submitlogin());">
                            </form>
                            <br>
                            <a href="<?= SERVERPATH ?>/forgot-password">   Wachtwoord vergeten? Klik hier!</a>
                            <?php if($user->getNrOfUsersRoles() < 1){ ?>
                                <br>
                            <a href="<?= SERVERPATH ?>/register">   Er is nog geen admin account? Klik hier!</a>
                            <?php }else{} ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- end main -->
    <a class="scroll-top-arrow" href="javascript:void(0);"><i class="feather icon-feather-arrow-up"></i></a>
    <?php include_once 'assets/includes/footer.php'; ?><!-- CALL FOOTER -->
    <?php include_once 'assets/includes/scripts.php'; ?><!-- CALL SCRIPTS -->
  </body>
</html>

