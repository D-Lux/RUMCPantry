<?php
  $pageRestriction = 10;
  include 'php/header.php';
  include 'php/backButton.php';
?>
    <h3>Main Navigation</h3>
	<div class="body-content">
	
	<?php if ($_SESSION['perms'] >= 99) { ?>
	<div style="float: right;">
		<form method="post" action="ap_io1.php">
			<input class="btn-nav" type="submit" value="Inventory operations">
		</form>
		<form method="post" action="ap_do1.php">
			<input class="btn-nav" type="submit" value="Donation operations">
		</form>
		<form method="post" action="ap_ro1.php">
			<input class="btn-nav" type="submit" value="Reallocation operations">
		</form>
		<form method="post" action="reporting.php">
			<input class="btn-nav" type="submit" value="Reporting">
		</form>
	</div>
  <?php } ?>
	
	<form method="post" action="checkIn.php">
        <input class="btn-nav" type="submit" value="Check in page">
    </form>
    <form method="post" action="ap_co1.php">
        <input class="btn-nav" type="submit" value="Client operations">
    </form>
    <form method="post" action="ap_ao1.php">
        <input class="btn-nav" type="submit" value="Appointment operations">
    </form>
	<form method="post" action="ap_oo3.php">
        <input class="btn-nav" type="submit" value="View Active Order Forms">
    </form>
	<!-- Removed because it can be accessed from the inventory ops page -->
	<!--
	<form method="post" action="ap_oo1.php">
        <input type="submit" value="Order Forms">
    </form>
	-->
<?php include 'php/footer.php'; ?>