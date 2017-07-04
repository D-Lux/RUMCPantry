<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<script src="js/orderFormOps.js"></script>
		<link href='css/toolTip.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>

	<script>
		
	</script>
	</head>
	<body>
	
	
		<button onclick="goBack()">Back</button>
		
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Client Order Form</h3>
<?php
	// *******************************************************
	// * Run our SQL Queries
	// * 1.) Family size information
	// * 2.) Order form database
	
	
	// -= Family size query =-
	$famSql = "SELECT (numOfKids + numOfAdults) as familySize
			   FROM client
			   WHERE clientID=" . $_POST['clientID'];
	
	//Run this query so we know what to grab from the item database
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
	
	// Default to walkIn
	$familyType = "walkIn";
	
	$famQuery = queryDB($conn, $famSql);
	if ($famQuery === FALSE) {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
	}
	else {
		$famData = sqlFetch($famQuery);
		$familyType = familySizeDecoder($famData['familySize']);
	}
	
	// --== Item databse query ==--
	$sql = "SELECT itemID, displayName, Item." . $familyType . " as IQty, factor as fx, 
					Category.name as CName, Category." . $familyType . " as CQty, Item.categoryID as CID
			FROM Item
			JOIN Category
			ON Item.categoryID=Category.categoryID
			WHERE isDeleted=0
			AND Category.name<>'Specials'
			AND Category.name<>'redistribution'
			AND Item." . $familyType . ">0
			AND Category." . $familyType . ">0 
			ORDER BY Category.name";

	$itemList = queryDB($conn, $sql);
	
	if ($itemList === FALSE) {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
	}
	closeDB($conn);
	
	// ************************************************************
	// * Create the order form
	
	// Start the form and add a hidden field for client info
	echo "<form method='post' action='php/orderOps.php' name='CreateInvoiceDescriptions'>";
	echo "<input type='hidden' value=" . $_POST['clientID'] . " name='clientID'>";
	echo "<input type='hidden' value=" . $_POST['invoiceID'] . " name='invoiceID'>";
	
	// Set defaults
	$currCategory = "";
	
	// Roll through the items and create the order form
	while ($item = sqlFetch($itemList)) {
		if ($currCategory != $item['CName']) {
			echo "<h3>" . $item['CName'] . "</h3>";
			// Create a special div so the client can see an updated count of selected items
			echo "<h4><div id='Count" . $item['CName'] . "'>Selected: 0 / " . $item['CQty'] . "</div></h4>";
			// Include hidden values so we can track the category
			echo "<input type='hidden' value=" . $item['CQty'] . " id=" . $item['CName'] . ">";
			$currCategory = $item['CName'];
			$slotID = 0;
		}

		// Display the Item name
		echo $item['displayName'];
		// If the factor is above 1, indicate that here
		echo ($item['fx']>1 ? " (Counts as " . $item['fx'] . ")" : "");
		for ($i = 0; $i < $item['IQty']; $i++) {
			$slotID++;
			// ID is the Factor (only way to do it so post data works correctly)
			// Value is the item's ID
			// Name is the item's category[] (in array)
			echo "<input type='checkbox' id=" . $item['fx'] . "
					value=" . $item['itemID'] . " onclick='countOrder(this)' 
					name='" . $item['CName'] . "[]'>";
		}
		echo "<br>";
		
	}
?>
		<br>
			<button type="submit" name="CreateInvoiceDescriptions">Submit Order</button>
		</form>

		
	</body>
</html>