<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/redistOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
	<?php include 'php/checkLogin.php';?>

	
    <title>Update Redistribution Item</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Update Redistribution Item</h1>
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database

		$sql = "SELECT itemName, price, aisle as weight
				FROM Item
				WHERE itemID=" . $_GET['id'];
		$itemInfo = queryDB($conn, $sql);
		
		// Close the connection as we've gotten all the information we should need
		closeDB($conn);
		
		if ($itemInfo === FALSE) {
			die("Unable to find item");
		}
	
		// ***********************************************************************
		// DISPLAY ITEM INFORMATION
		// Fill in all fields with values from the database
		
		$item = sqlFetch($itemInfo);
		
		echo "<form name='updateRedistItem' action='php/redistOps.php' onSubmit='return validateNewRedistItem()' method='post' >";
		
		echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
		
		// Item name (required)
		echo "<div id='itemName' class='required'><label>Item Name: </label>";
		echo "<input type='text' id='itemNameField' name='itemName' maxlength='45' value='" . $item['itemName'] . "' ></div>";
		
		// Price and weight
		echo "<div>Price: <input type='number' name='price' min=0 step='.01' value=" . $item['price'] . "></div>";
		echo "<div>Weight: <input type='number' name='weight' min=0 step='.01' value=" . $item['weight'] . "></div>";
	
		echo "<br><br>";
		echo "<input type='submit' name='submitUpdateRedistItem' value='Update'>";
		echo "</form>";	
	?>

</body>

</html>