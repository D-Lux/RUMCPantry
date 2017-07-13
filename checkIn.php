<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>



	<title>Check In</title>
</head>

<body>
	<script>
		if (getCookie("newWalkIn") != "") {
			window.alert("Walk-In Client added!");
			removeCookie("newWalkIn");
		}		
	</script>
	
    <button onclick="goBack()">Go Back</button> </br>
    <button onclick="location.href = 'awc.php';">Add walk in client</button>
    <h1>
		Check in for Clients
    </h1>

	
	<?php include 'php/checkInOps.php';?>
	
</body>
</html>