<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<script src="js/orderFormOps.js"></script>
		<link href='css/toolTip.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>

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
	$walkinSql = "SELECT walkIn
			   FROM Invoice
			   WHERE invoiceID=" . $_POST['invoiceID'];
	
	//Run this query so we know what to grab from the item database
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
	
	// Default to walkIn
	$familyType = "Small";
	
	$famQuery = queryDB($conn, $famSql);
	if ($famQuery === FALSE) {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
	}
	else {
		$famData = sqlFetch($famQuery);
		$familyType = familySizeDecoder($famData['familySize']);
	}
	
	// Force family type to small if this is a walkin client
	$WIQuery = queryDB($conn, $walkinSql);
	$walkIn = 0;
	if ($WIQuery === FALSE) {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
	}
	else {
		$walkInData = sqlFetch($WIQuery);
		$walkIn = $walkInData['walkIn'];
	}
	if ($walkIn == 1) {
		$familyType = "Small";
	}
	
	// --== Item databse query ==--
	$sql = "SELECT itemID, displayName, Item." . $familyType . " as IQty, Category.name as CName, 
			Category." . $familyType . " as CQty, Item.categoryID as CID
			FROM Item
			JOIN Category
			ON Item.categoryID=Category.categoryID
			WHERE Item.isDeleted=0
			AND Category.isDeleted=0
			AND Category.name<>'Specials'
			AND Category.name<>'redistribution'
			AND Item." . $familyType . ">0
			AND Category." . $familyType . ">0 
			ORDER BY Category.name, Item.displayName";

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
	echo "<input type='hidden' value=" . $walkIn . " name='walkInStatus'>";
	
	// Set defaults
	$currCategory = "";
	
	// *************************************************
	// * Normal Items
	
	// Roll through the items and create the order form
	while ($item = sqlFetch($itemList)) {
		// Skip medicine if we're 
		if ( showCategory($walkIn, $item['CName']) ){
			if ($currCategory != $item['CName']) {
				echo "<h3>" . $item['CName'] . "</h3>";
				// Create a special div so the client can see an updated count of selected items
				echo "<h4><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] . " (0/" . $item['CQty'] . ")</div></h4>";
				// Include hidden values so we can track the category
				echo "<input type='hidden' value=" . $item['CQty'] . " id=" . $item['CName'] . ">";
				$currCategory = $item['CName'];
				$slotID = 0;
			}

			// TODO: Deal with special case beans
			// Display the Item name
			echo $item['displayName'];
			for ($i = 0; $i < $item['IQty']; $i++) {
				$slotID++;
				// Value is the item's ID
				// Name is the item's category[] (in array)
				echo "<input type='checkbox' id=1
						value=" . $item['itemID'] . " onclick='countOrder(this)' 
						name='" . $item['CName'] . "[]'>";
			}
			echo "<br>";
		}
		
	}
	
	// *************************************************
	// * Specials
	
	if (!$walkIn) {;
		echo "<h2>Specials</h2>";
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$specialsFile = fopen("specials.txt","r") or die("Unable to open specials!");
		
		$specialItemNum = 1;
		while(!feof($specialsFile)) {
			$itemLine = explode(",", fgets($specialsFile));
			if (sizeof($itemLine) > 1) {
				for ($i = 0; $i < sizeof($itemLine); $i++) {
					// Only create a box if we've grabbed a numeric value (the eol character appears in the array)
					if (is_numeric($itemLine[$i])) {
						$sql = "SELECT displayName
								FROM Item
								WHERE itemID='" . $itemLine[$i] . "'
								LIMIT 1";
						$itemQuery = queryDB($conn, $sql);
						
						if ($itemQuery == NULL || $itemQuery->num_rows <= 0){
							echo "sql error: " . mysqli_error($conn);	
						}
						else {
							$itemInfo = sqlFetch($itemQuery);
							echo "<input type='radio' value=" . $itemLine[$i] . "
									name='savedItem" . $specialItemNum . "'>" . $itemInfo['displayName'];

							echo "<br>";
						}
					}
				}
				$specialItemNum++;
			}
			// Put some sort of separator between specials
			echo "<hr>";
		}
		closeDB($conn);
	}
?>
		<br>
			<button type="submit" name="CreateInvoiceDescriptions">Submit Order</button>
		</form>

		
	</body>
</html>