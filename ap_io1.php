<?php
  $pageRestriction = 99; 
  include 'php/header.php';
  include 'php/backButton.php';
?>   
<h3>
    Inventory Operations
</h3>
<div class="body-content">
  <form method="get" action="ap_io7.php">
      <input class='btn-nav' type="submit" value="Item Operations">
  </form>
  <form method="get" action="ap_io8.php">
      <input class='btn-nav' type="submit" value="Category Operations">
  </form>
  <form method="get" action="ap_oo1.php">
      <input class='btn-nav' type="submit" value="Edit order form">
  </form>
<?php include 'php/footer.php'; ?>