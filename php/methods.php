<?php
   // This front-end and the KBS.CMS that serves it (FRONT_ROOT, CMS_ROOT)
   require_once __DIR__ . '/cmsRoot.php';
   require(CMS_ROOT . "/core/Database.php");
   require(CMS_ROOT . "/core/defs.php");
   require(CMS_ROOT . "/core/classUser.php");
   require(CMS_ROOT . "/core/classBanner.php");
   require(CMS_ROOT . "/core/classFrontPages.php");
   require(CMS_ROOT . "/core/classCalendar.php");
   require(CMS_ROOT . "/core/classLinkDisplay.php");
   require(CMS_ROOT . "/core/classDailyRoster.php");
   require(CMS_ROOT . "/core/classCourses.php");
   require(CMS_ROOT . "/core/classPlanning.php");
   require(CMS_ROOT . "/core/classGallery.php");
   require(CMS_ROOT . "/core/classGalleryCategory.php");
   require(CMS_ROOT . "/core/classReview.php");
   require(CMS_ROOT . "/core/classServices.php");
   require(CMS_ROOT . "/core/classFrontendPagesContent.php");
   require(CMS_ROOT . "/core/classBlog.php");
   require(CMS_ROOT . "/core/classProjects.php");
   require(__DIR__ . "/content_methods.php");
   $banner = new Banner();
   $user = new User();
   $front_pages = new FrontPages();
   $calendar = new Calendar();
   $linkDisplay = new LinkDisplay();
   $dailyRoster = new dailyRoster();
   $courses = new Courses();
   $planning = new Planning();
   $gallery = new Gallery();
   $galleryCategory = new GalleryCategory();
   $review = new Review();
   $services = new Services();
   $frontendPagesContent = new FrontendPagesContent();
   $blog = new Blog();
   $projects = new Projects();

   $pageNameList                  = $front_pages->getFrontPagesList();
   $menuPageList                  = $front_pages->getMenuPagesList();
   $servicesList                  = $services->getServicesList();
   $servicesIconRowList           = $services->getServicesIconRowList();
   $servicesInMenuList            = $services->getServicesInMenuList();
   $frontendPagesContentList      = $frontendPagesContent->getFrontendPagesContentList();
   $blogPageContentList           = $blog->getBlogList();
   $linkDisplay_list              = $linkDisplay->getLinkDisplayList();
   $reviewList                    = $review->getReviewList();

// Pagination setup
$perPage = 8;
$totalPosts = count($blogPageContentList);
$totalPages = ceil($totalPosts / $perPage);
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $perPage;
$pagedBlogPosts = array_slice($blogPageContentList, $offset, $perPage);

// Dutch months
$maanden = [
    'January' => 'januari', 'February' => 'februari', 'March' => 'maart',
    'April' => 'april', 'May' => 'mei', 'June' => 'juni',
    'July' => 'juli', 'August' => 'augustus', 'September' => 'september',
    'October' => 'oktober', 'November' => 'november', 'December' => 'december'
];


// Verify a Google reCAPTCHA response with the secret key from the system settings
function reCaptcha($recaptcha){
  $postvars = array("secret" => RECAPTCHA_SECRET_KEY, "response" => $recaptcha, "remoteip" => $_SERVER['REMOTE_ADDR']);
  $url = "https://www.google.com/recaptcha/api/siteverify";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
  $data = curl_exec($ch);
  curl_close($ch);

  return json_decode($data, true);
}

function margin($px) {
	return "<div class='margin' style='margin-top: 0px; width:100%; padding:".($px/2)."px;'></div>";
}

function scroll_to() {
  if( isset($_GET['pos'])) {
    $jq = htmlspecialchars($_GET['pos'], ENT_QUOTES);
    return "
    <script>
      scroll2jQ('#$jq');
    </script>
    ";
  } else {
    return "";
  }
}

function equ($str1, $str2) {
  return (strcmp($str1,$str2)==0);
}

?>
