<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
if( !isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60 )
  $_SESSION['last_access'] = time();

if (!isset($pageRestriction)) {
  createCookie("badRestrictions", 1, 30);
  header ("location: " . $basePath . "mainpage.php");
}

if ($pageRestriction > -1) {
  if ((!isset($_SESSION['perms'] )) || ($_SESSION['perms'] < $pageRestriction)) {
    createCookie("noPermission", 1, 30);
    header ("location: " . $basePath . "mainpage.php");
  }
}

$permVal = isset($_SESSION['perms']) ? $_SESSION['perms'] : 0;

echo "<input type='hidden' value='" . $permVal . "' id='perms'>";


if ($_SESSION['perms'] == 101 || $_SESSION['perms'] == 3 ) { ?>
  <div class="testHover">TEST MODE</div>

<?php }

if ($_SESSION['perms'] == 100 || $_SESSION['perms'] == 2 ) { ?>
  <div class="testHover">LOCAL TEST MODE</div>

<?php }


$debug = false;//true;


if ($debug) {

?>
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