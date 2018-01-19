<?php 
  include 'php/header.php';
  include 'php/backButton.php';
?>   
<h3>
    Inventory Operations
</h3>
<div class="body_content">
    <form method="get" action="ap_io7.php">
        <input class='btn-nav' type="submit" value="Item Operations">
    </form>
    <form method="get" action="ap_io8.php">
        <input class='btn-nav' type="submit" value="Category Operations">
    </form>
    <form method="get" action="ap_oo1.php">
        <input class='btn-nav' type="submit" value="Edit order form">
    </form>

	<!-- temporarily disabled -->
	<!--
     <form method="get" action="ap_io6.php">
        <input type="submit" value="Deleted items and categories">
    </form>
-->
<?php include 'php/footer.php'; ?>