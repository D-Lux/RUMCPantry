<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/apptOps.js"></script>
	<script src="js/orderFormOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
    <title>Order Form Configuration</title>
	
</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>
		Order Form: 
	<?php 
		$familyType = "";
		// Family Size 1-2
		if (isset($_GET["1to2"])) { echo "One to Two"; $familyType = "small"; }
		// Family Size 3-4
		if (isset($_GET["3to4"])) { echo "Three to Four"; $familyType = "medium"; }
		// Family Size 5+
		if (isset($_GET["5Plus"])) { echo "Five+"; $familyType = "large"; }
		// Walk-In Clients
		if (isset($_GET["Walkin"])) { echo "Walk-Ins"; $familyType = "walkIn"; }
		
		$familyToken = substr($familyType,0,1);
		
		echo "</h1>";
		
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// * Query the database
		
		// Create our query from the Item and Category tables
		// Item Name, factor, $familyType, category name, category family type, itemid
		$sql = "SELECT itemID, itemName, Item." . $familyType . " as IQty, factor as fx, 
					Category.name as CName, Category." . $familyType . " as CQty, Item.categoryID 
				FROM Item
				JOIN Category
				ON Item.categoryID=Category.categoryID
				WHERE isDeleted=0
				AND Category.name<>'Specials'
				AND Category.name<>'redistribution'
				ORDER BY Category.name";

		$itemList = queryDB($conn, $sql);
		
		if ($itemList === FALSE) {
			// Assignment failed, error back
			echo "sql error: " . mysqli_error($conn);
			closeDB($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Bad SQL query.", "red" );
		}
		
		// ******************************************************
		// ** Create our table of information

		$currCategory = "";
		while ($item = sqlFetch($itemList)) {
			// if this is a new category
			if ($currCategory != $item['CName']) {
				// If we were in a different category, close off that category's table
				if ($currCategory != "") {
					echo "</table>";
				}
				// Print out the category name, followed by the selection qty and start a new table
				echo $item['CName'] . "<br>";
				echo "Selection Quantity: ";
				/*	// Text field version
				echo "<input id='cid" . $familyToken . $item['categoryID'] . "' type='number' min=0 
						value=" . $item['CQty'] . " onchange='AJAX_UpdateCQty(this)'><br>";
				*/
				echo "<select id='cid" . $familyToken . $item['categoryID'] . "' onchange='AJAX_UpdateCQty(this)'>";
				for ($i = 0; $i < 9; $i++) {
					echo "<option value=$i " . ($i == $item['CQty'] ? "Selected" : "") . ">$i</option>";
				}
				echo "</select>";
				
				$currCategory = $item['CName'];
				echo "<table><tr><th>Item Name</th><th>Qty</th><th>Fx</th></tr>";
			}
			// Print this item's name and have two number fields/dropdowns
			echo "<tr><td>" . $item['itemName'] . "</td>";
			
			// *****************************************
			// This is the dropdown selection list
			echo "<td><select id='iqty" . $familyToken . $item['itemID'] . "' onchange='AJAX_UpdateQty(this)'>";
			
			for ($i = 0; $i < 11; $i++) {
				echo "<option value=$i " . ($i == $item['IQty'] ? "Selected" : "") . ">$i</option>";
			}
			echo "</select></td>";
			
			echo "<td><select id='ifx" . $item['itemID'] . "' onchange='AJAX_UpdateFX(this)'>";
			for ($i = 1; $i < 7; $i++) {
				echo "<option value=$i " . ($i == $item['fx'] ? "Selected" : "") . ">$i</option>";
			}
			echo "</select></td>";
						
			/*	// Text field version
			echo "<td><input id='iqty" . $familyToken . $item['itemID'] . "' type='number' min=0 
						value=" . $item['IQty'] . " onchange='AJAX_UpdateQty(this)'></td>";
			echo "<td><input id='ifx" . $item['itemID'] . "'type='number' min=0 
						value=" . $item['fx'] . " onchange='AJAX_UpdateFX(this)'></td></tr>";
			*/
		}
		
		echo "<br><div id='ErrorLog'></div>"
	?>
	

</body>

</html>