<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<link href='css/toolTip.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>

	</head>
	<body>
		<button onclick="goBack()">Back</button>
		
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Client Order Form</h3>
<?php

	// Family size query
	$famSql = "SELECT (numKids + numAdults) as familySize
			   FROM client
			   WHERE clientID=" . $_POST['clientID'];
	
	//Run this query so we know what to grab from the item database
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
	
	$famQuery = queryDB($conn, $famSql);
	
	// Item databse query
	$itemSql = "SELECT "
	
	
// get family size (numkids+numadults)
// Pull all item data for my family size
// Check boxes limited by category quantity for family size

// Submit order should go to create appt
?>

		<form method="get" action="mainpage.php">
			<button type="submit">Submit Order</button>
		</form>

		
	</body>
</html>