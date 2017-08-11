<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>
    <title>Redistribution - Main</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        Redistribution Options
    </h1>
	
    <form method="post" action="ap_ro2.php">
        <input type="submit" value="View Redistribution Clients">
    </form><br>
    
	<form method="post" action="ap_ro5.php">
        <input type="submit" value="View Redistribution Items">
    </form><br> 
	
	<form method="post" action="ap_ro8.php">
        <input type="submit" value="View Redistributions">
    </form><br>

</body>

</html>