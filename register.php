<?php
/* Copyright (C) Kevin Schuit - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Kevin Schuit <info@kevinschuit.com>, July 2022
 */
  session_start();
  include_once './php/callDatabaseConfig.php';
  require "./php/methods.php";

  if(isset($_SESSION['uid'])){
      $uid = $_SESSION['uid'];
      
      if ($user->get_session()){
          header("location:".SERVERPATH."/admin/index");
      }
      }
  if($user->getNrOfUsersRoles() < 1){    
  
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
  <!-- REGISTREREN
================================================== -->
<main style="flex: 1">
    <?php include "assets/banner/banner-type-$bannerName.php"; ?><!-- CALL BANNER -->
    <section  style="padding: 50px 0 100px; 0;">
        <div id="container" class="container">

            <?php
            if (isset($_POST['submit'])){
                extract($_POST);
                $register = $user->reg_user($fullname, $uname, $upass, $uemail);
                if ($register) {
                    // Registration Success
                    echo "<script>
                    
                    alert('Registration is succesfull');
                    window.location.assign('".SERVERPATH."/login'); </script>";
                } else {
                    // Registration Failed
                    echo "<div class='alert alert-danger text-center' >
                            <strong>Registratie mislukt</strong>, deze e-mail of gebruikersnaam bestaat al.
                        </div>";
                }
            }
            ?>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" name="reg" class="needs-validation" novalidate>
                    <small><span class='red'>*</span> = required</small>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fullname">Naam <span class='red'>*</span></label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="uname">Gebruikersnaam <span class='red'>*</span></label>
                            <input type="text" class="form-control" id="uname" name="uname" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="uemail">E-mail <span class='red'>*</span></label>
                            <input type="email" class="form-control" id="uemail" name="uemail" placeholder="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="upass">Wachtwoord <span class='red'>*</span></label>
                            <input type="password" class="form-control" id="upass" name="upass" placeholder="" required>
                        </div>
                    </div>
                    <input class="btn btn-primary" type="submit" name="submit" value="Registreren" onclick="return(submitreg());">
                </form>
                <br><br>
                <a href="<?=SERVERPATH?>/login">   Al een account? Klik hier!</a>
            </div>
        </div>
    </section>
</main>
<!-- end main -->
    <a class="scroll-top-arrow" href="javascript:void(0);"><i class="feather icon-feather-arrow-up"></i></a>
    <?php include_once 'assets/includes/footer.php'; ?><!-- CALL FOOTER -->
    <?php include_once 'assets/includes/scripts.php'; ?><!-- CALL SCRIPTS -->
    <script>
    function submitreg() {
        var form = document.reg;
        if (form.name.value == "") {
            alert("Vor naam in.");
            return false;
        } else if (form.uname.value == "") {
            alert("Voer gebruikersnaam in.");
            return false;
        } else if (form.upass.value == "") {
            alert("Voer wachtwoord in.");
            return false;
        } else if (form.uemail.value == "") {
            alert("Voer e-mail in.");
            return false;
        }
    }
</script>
  </body>
</html>
<?php
}else{
    header("location:".SERVERPATH."/login");
}?>

