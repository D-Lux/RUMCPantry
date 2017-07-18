<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<meta http-equiv="refresh" content="15" >


	<title>Check In</title>
</head>

<body>
	<script>
		if (getCookie("newWalkIn") != "") {
			window.alert("Walk-In Client added!");
			removeCookie("newWalkIn");
		}		
	</script>
	
    <script src="js/destinationFunctions.js"></script>
    <input type="button" value="Go Back" onclick="checkInBack()">
    <button onclick="location.href = 'awc.php';">Add walk in client</button>
    <h1>
		Check in for Clients
    </h1>

	
	<?php include 'php/checkInOps.php';?>
	
</body>
</html>