<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<?php include 'php/checkLogin.php';?>
    <title>Admin - Main</title>
</head>

<body>
    <script src="js/destinationFunctions.js"></script>
    <input type="button" value="Go Back" onclick="ap1Back()">
    <h1>
        Main Admin Page
    </h1>
    <form method="post" action="ap_co1.php">
        <input type="submit" value="Client operations">
    </form><br>
    <form method="post" action="ap_io1.php">
        <input type="submit" value="Inventory operations">
    </form><br>
    <form method="post" action="ap_ao1.php">
        <input type="submit" value="Appointment operations">
    </form><br>
	<form method="post" action="ap_oo1.php">
        <input type="submit" value="Create Order Forms">
    </form><br>
	<form method="post" action="ap_oo3.php">
        <input type="submit" value="View Active Order Forms">
    </form><br>
    <form method="post" action="ap_do1.php">
        <input type="submit" value="Donation operations">
    </form><br>
	<form method="post" action="ap_ro1.php">
        <input type="submit" value="Redistribution operations">
    </form><br>
    <form method="post" action="checkIn.php">
        <input type="submit" value="Check in page">
    </form><br>

    <form method="post" action="reporting.php">
        <input type="submit" value="Reporting">
    </form>
</body>

</html>