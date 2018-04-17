<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
<h3 class="text-center">
    Inventory Operations
</h3>
<div class="body-content">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
      <a href="<?=$basePath?>inventory_management.php" class="button">Inventory Management</a>
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
      <a href="<?=$basePath?>ap_oo1.php" class="button">Current Order Forms</a>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
      <a href="<?=$basePath?>ap_io7.php" class="button">Item Operations</a>
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
      <a href="<?=$basePath?>ap_oo6.php" class="button">Edit Category Ordering</a>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
      <a href="<?=$basePath?>ap_io8.php" class="button">Category Operations</a>
    </div>
  </div>

  <div class="clearfix"></div>
<?php include 'php/footer.php'; ?>