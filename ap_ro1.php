<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
  <h3>Reallocation Main</h3>

	<div class="body-content">

  <form method="get" action="ap_ro2.php">
    <input class="btn-nav" type="submit" value="View Reallocation Partners">
  </form>

	<form method="get" action="ap_ro5.php">
    <input class="btn-nav" type="submit" value="View Reallocation Items">
  </form>

	<form method="get" action="ap_ro8.php">
    <input class="btn-nav" type="submit" value="View Reallocations">
  </form>


<?php include 'php/footer.php'; ?>