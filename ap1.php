<?php
  $pageRestriction = 10;
  include 'php/header.php';
  $btnText = "Logout";
  include 'php/backButton.php';
?>


  <h3 class="text-center">Main Navigation</h3>
	<div class="body-content">


	<div class="row text-right">
    <div class="col-sm-5">
      <a href= "<?=$basePath?>checkIn.php" class="button">Check in page</a>
    </div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-4">
        <a href="<?=$basePath?>ap_io1.php" class="button">Inventory operations</a>
      </div>
    <?php } ?>
  </div>

  <div class="row text-right">
    <div class="col-sm-5">
      <a href="<?=$basePath?>ap_co1.php" class="button">Client operations</a>
    </div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-4">
        <a href="<?=$basePath?>ap_do1.php" class="button">Donation operations</a>
      </div>
    <?php } ?>
  </div>

  <div class="row text-right">
    <div class="col-sm-5">
      <a href="<?=$basePath?>ap_ao1.php" class="button">Appointment operations</a>
    </div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-4">
        <a href="<?=$basePath?>ap_ro1.php" class="button">Reallocation operations</a>
      </div>
    <?php } ?>
  </div>

  <div class="row text-right">
    <div class="col-sm-5">
      <a href="<?=$basePath?>ap_oo3.php" class="button">View Active Order Forms</a>
    </div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-4">
        <a href="<?=$basePath?>reporting.php" class="button">Reporting</a>
      </div>
    <?php } ?>
  </div>

  <?php if ($_SESSION['perms'] >= 99) { ?>
    <div class="row text-right">
      <div class="col-sm-9">
        <a href="<?=$basePath?>adjustLogins.php" class="button">Permissions</a>
      </div>
    </div>
  <?php } ?>

  <div class="clearfix"></div>

<?php include 'php/footer.php'; ?>