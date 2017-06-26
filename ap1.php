<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
    <title>ap1</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        Main Admin Page
    </h1>
    <form method="get" action="ap_co1.php">
        <input type="submit" value="Client operations">
    </form><br>
    <form method="get" action="ap_io1.php">
        <input type="submit" value="Inventory operations">
    </form><br>
    <form method="get" action="ap_ao1.php">
        <input type="submit" value="Appointment operations">
    </form><br>
	<form method="get" action="ap_oo1.php">
        <input type="submit" value="Create Order Forms">
    </form><br>
    <form method="get" action="ap_do1.php">
        <input type="submit" value="Donation operations">
    </form><br>

    <form method="get" action="reporting.php">
        <input type="submit" value="Reporting">
    </form>
</body>

</html>