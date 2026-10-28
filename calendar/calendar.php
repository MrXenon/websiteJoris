<?php
session_start();
// This front-end and the KBS.CMS that serves it (FRONT_ROOT, CMS_ROOT)
require_once __DIR__ . '/../php/cmsRoot.php';
require CMS_ROOT . '/core/Database.php';
require CMS_ROOT . '/core/classUser.php';
$user               = new User();
$teacherID          = $user->getTeacherIDBySubRoleById();
$teacherList        = $user->getTeacherBySubRoleById($teacherID);
$page = basename(__FILE__); 
$base_url = $page;
?>
<!DOCTYPE html>
<html>
 <head>
  <link rel="stylesheet" href="css/fullcalendar.css" />
  <link rel="stylesheet" href="css/bootstrap.css" />
  <script src="<?= SERVERPATH ?>/js/jquery.js?v=<?= SITE_VERSION ?>"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/moment.js"></script>
  <script src="js/fullcalendar.js"></script>
  <script>
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'prev,next today',
     center:'title',
     right:''
    },
    events: 'php/load.php',
    selectable:true,
    selectHelper:true,
   });
  });
  </script>
 </head>
 <body>
  <div class="container">
    <?php 
    $teacher = 0;
    if(isset($_POST['teacher'])){
        setcookie("SHAKDH#KKKDSAWDM", $_POST['teacher'], time()+3600);
        $teacher = $_POST['teacher'];
    } 
    ?>
  <form method="post" action="<?=$base_url;?>" style="width:190px; margin:20px 0;" id="frm">
        <select name="teacher" class="form-control" list="datalistOptions" id="exampleDataList" required onchange="onSelectChange();">
                <datalist id="datalistOptions">
                    <option value="0">Selecteer docent</option>
                </datalist>
            <?php foreach ($teacherList  as $tList) { ?>
                <datalist id="datalistOptions">
                    <option <?php if($tList->getUserId() == $teacher){echo ' selected ';} ?> value="<?= $tList->getUserId() ?>"> <?= $tList->getUserName(); ?> </option>
                </datalist>
            <?php } ?>
        </select>
  </form>
   <div id="calendar"></div>
  </div>
  <script>
    function onSelectChange(){
        document.getElementById('frm').submit();
        }
  </script>
 </body>
</html>