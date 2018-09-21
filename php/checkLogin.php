<?php
session_start();
include 'utilities.php';
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
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


if ($_SESSION['perms'] == 101 || $_SESSION['perms'] == 3 ) { 
    $dbname     = "roselleu_foodpantry";
    if (ISSET($_SESSION['perms'])){
      if ($_SESSION['perms'] == 100 || $_SESSION['perms'] == 2) {
         $dbname     = "foodpantry";
       }
      if ($_SESSION['perms'] == 101 || $_SESSION['perms'] == 3 ) {
         $dbname     = "roselleu_testpantry";
      }
    }
?>
    <div class="testHover hide_for_print">
      <p>TEST MODE <?=$_SESSION['perms']?></p>
      <p>dbName: <?=$dbname?></p>
      <p>Login ID: <?=$_SESSION['permid']?></p>
    </div>

<?php }

if ($_SESSION['perms'] == 100 || $_SESSION['perms'] == 2 ) { ?>
  <div class="testHover hide_for_print">LOCAL TEST MODE - <?=$_SESSION['perms']?></div>

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

<script>
    console.log("<?=$_SESSION['perms']?>");
  </script>