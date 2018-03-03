<?php
  $pageRestriction = 10;
  include 'php/header.php';
  include 'php/backButton.php';
?>

  
  <h3>Main Navigation</h3>
	<div class="body-content">
	
	
	<div class="row">
    <div class="col-sm-5">
      <a href="/RUMCPantry/checkIn.php" class="button">Check in page</a>
    </div>
    <div class="col-sm-1"></div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-5">
        <a href="/RUMCPantry/ap_io1.php" class="button">Inventory operations</a>
      </div>
    <?php } ?>
  </div>
  
  <div class="row">
    <div class="col-sm-5">
      <a href="/RUMCPantry/ap_co1.php" class="button">Client operations</a>
    </div>
    <div class="col-sm-1"></div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-5">
        <a href="/RUMCPantry/ap_do1.php" class="button">Donation operations</a>
      </div>
    <?php } ?>
  </div>
  
  <div class="row">
    <div class="col-sm-5">
      <a href="/RUMCPantry/ap_ao1.php" class="button">Appointment operations</a>
    </div>
    <div class="col-sm-1"></div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-5">
        <a href="/RUMCPantry/ap_ro1.php" class="button">Reallocation operations</a>
      </div>
    <?php } ?>
  </div>
  
  <div class="row">
    <div class="col-sm-5">
      <a href="/RUMCPantry/ap_oo3.php" class="button">View Active Order Forms</a>
    </div>
    <div class="col-sm-1"></div>
    <?php if ($_SESSION['perms'] >= 99) { ?>
      <div class="col-sm-5">
        <a href="/RUMCPantry/reporting.php" class="button">Reporting</a>
      </div>
    <?php } ?>
  </div>
  
  <?php if ($_SESSION['perms'] >= 99) { ?>
    <div class="row">
      <div class="col-sm-6"></div>
      <div class="col-sm-5">
        <a href="/RUMCPantry/adjustLogins.php" class="button">Permissions</a>
      </div>
    </div>
  <?php } ?>
  
  <div class="clearfix"></div>

<?php include 'php/footer.php'; ?>