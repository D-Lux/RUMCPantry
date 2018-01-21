<?php


if( !isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60 ) 
  $_SESSION['last_access'] = time(); 

if (!isset($pageRestriction)) {
  createCookie("badRestrictions", 1, 30);
  header ("location: /RUMCPantry/mainpage.php");
}

if ($pageRestriction > -1) {
  if ($_SESSION['perms'] < $pageRestriction) {
    createCookie("noPermission", 1, 30);
    header ("location: /RUMCPantry/mainpage.php");
  }
}


$debug = false; //true; //false


if ($debug) {

?>

  <style>
    .testHover {
      position: fixed;
      left: 5%;
      top: 5%;
      z-index: 10;
      background-color: black;
      color: white;
    }
  </style>

  <div class="testHover">
    Permissions: <?=$_SESSION['perms']?><br>
    Restrictions: <?=$pageRestriction?><br>
    <?php 
      if ($_SESSION['perms'] >= $pageRestriction) {
        echo "Page Access Allowed";
      }
      else {
        echo "Page Access Denied";
      }
    ?>

  </div>

<?php } ?>