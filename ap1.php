<?php include 'php/header.php'; ?>

    <button id='btn_back' onclick="goBack()">Back</button>
    <h3>Main Administrator Navigation</h3>
	<div class="body_content">
	
    <form method="post" action="ap_co1.php">
        <input type="submit" value="Client operations">
    </form>
    <form method="post" action="ap_io1.php">
        <input type="submit" value="Inventory operations">
    </form>
    <form method="post" action="ap_ao1.php">
        <input type="submit" value="Appointment operations">
    </form>
	<form method="post" action="ap_oo1.php">
        <input type="submit" value="Order Forms">
    </form>
	<form method="post" action="ap_oo3.php">
        <input type="submit" value="View Active Order Forms">
    </form>
    <form method="post" action="ap_do1.php">
        <input type="submit" value="Donation operations">
    </form>
	<form method="post" action="ap_ro1.php">
        <input type="submit" value="Redistribution operations">
    </form>
    <form method="post" action="checkIn.php">
        <input type="submit" value="Check in page">
    </form>

    <form method="post" action="reporting.php">
        <input type="submit" value="Reporting">
    </form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>

</html>