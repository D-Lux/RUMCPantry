<?php include 'php/header.php'; ?>

    <?php include 'php/backButton.php'; ?>
    <h3>Main Administrator Navigation</h3>
	<div class="body_content">
	
	
	<div style="float: right;">
		<form method="post" action="ap_io1.php">
			<input type="submit" value="Inventory operations">
		</form>
		<form method="post" action="ap_do1.php">
			<input type="submit" value="Donation operations">
		</form>
		<form method="post" action="ap_ro1.php">
			<input type="submit" value="Reallocation operations">
		</form>
		<form method="post" action="reporting.php">
			<input type="submit" value="Reporting">
		</form>
	</div>
	
	<form method="post" action="checkIn.php">
        <input type="submit" value="Check in page">
    </form>
    <form method="post" action="ap_co1.php">
        <input type="submit" value="Client operations">
    </form>
    <form method="post" action="ap_ao1.php">
        <input type="submit" value="Appointment operations">
    </form>
	<form method="post" action="ap_oo3.php">
        <input type="submit" value="View Active Order Forms">
    </form>
	<!-- Removed because it can be accessed from the inventory ops page -->
	<!--
	<form method="post" action="ap_oo1.php">
        <input type="submit" value="Order Forms">
    </form>
	-->
	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>

</html>