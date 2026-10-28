<?php
session_start();
include_once './php/callDatabaseConfig.php';
require './php/methods.php';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($token)) {
    header('Location: ' . SERVERPATH . '/404');
    exit;
}

$reviewData = $review->getSetTokenAndUnsused($token);

if (empty($reviewData)) {
    header('Location: ' . SERVERPATH . '/404');
    exit;
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$success, $message] = $review->processFrontendReviewSubmission($_POST, $_FILES);
}

$bannerPage = $banner->getPageIdByPageName('login');
$bannerData = $banner->getBannerListByPage($bannerPage);
$banners    = $banner->getBannersByPage($bannerPage);
$banners    = array_map(fn($b) => array_merge($b, ['title' => 'Review invullen']), $banners);
$bannerType = $bannerData['type'] ?? '';
$bannerName = $banner->getBannerTypeByType($bannerType);

$page  = basename(__FILE__);
$title = 'Review invullen';
$meta  = $front_pages->getMetaTagsByTitle('login');

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= SITENAME; ?> | <?= $title; ?></title>
    <?php foreach ($meta as $m) { ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="author" content="<?= $m->getFrontPageAuthor(); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
    <?php } ?>
    <?php include_once 'assets/includes/headscripts.php'; ?>
</head>
<body data-mobile-nav-style="classic" style="display: flex; flex-direction: column; min-height: 100vh;">
    <?php include_once 'assets/includes/header.php'; ?>

    <main style="flex: 1">
        <?php include "assets/banner/banner-type-$bannerName.php"; ?>

        <section>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">

                        <h2 class="alt-font font-weight-500 text-extra-dark-gray margin-4-rem-bottom">Review invullen</h2>

                        <?php if ($success): ?>
                            <div class="alert alert-success text-center">
                                <?= htmlspecialchars($message); ?>
                            </div>
                        <?php else: ?>

                            <?php if (!empty($message)): ?>
                                <div class="alert alert-danger text-center">
                                    <?= htmlspecialchars($message); ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">

                                <div class="form-group margin-30px-bottom">
                                    <label for="rname">Uw naam <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="rname" name="rname" required>
                                </div>

                                <div class="form-group margin-30px-bottom">
                                    <label for="rcname">Bedrijfsnaam</label>
                                    <input type="text" class="form-control" id="rcname" name="rcname">
                                </div>

                                <div class="form-group margin-30px-bottom">
                                    <label for="rtitle">Titel <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="rtitle" name="rtitle" required>
                                </div>

                                <div class="form-group margin-30px-bottom">
                                    <label for="rtext">Uw review <span class="text-red">*</span></label>
                                    <textarea class="form-control" id="rtext" name="rtext" rows="5" required></textarea>
                                </div>

                                <div class="form-group margin-30px-bottom">
                                    <label for="rclogo">Bedrijfslogo (optioneel)</label>
                                    <input type="file" class="form-control-file" id="rclogo" name="rclogo">
                                </div>

                                <button type="submit" class="btn btn-primary">Review versturen</button>
                            </form>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'assets/includes/footer.php'; ?>
    <?php include_once 'assets/includes/scripts.php'; ?>
    <script src="<?= SERVERPATH ?>/js/jquery.js?v=<?= SITE_VERSION ?>"></script>
    <script type="text/javascript" src="<?= SERVERPATH ?>/js/init_rest.js?v=<?= SITE_VERSION ?>"></script>
    <?php echo scroll_to(); ?>
</body>
</html>
