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
		<h3>Review Order Form</h3>
<?php

	/*
	Pull up the invoice and invoice descriptions
	Build a key->qty relationship (key is itemID)
	
	rebuild order form, if an item exists in the key->qty array, subtract 1 from qty and check the box
	
	On submit, erase item descriptions and rebuild from this page
	
	*/

	// *******************************************************
	// * Run our SQL Queries
	
	// Open the database connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
	
	
	// --== familySize query (for walkin, Post should tell us family size) ==--
	$walkinSql = "SELECT walkIn
			   FROM Invoice
			   WHERE invoiceID=" . $_POST['invoiceID'];
	
	// Set the family size, forced to small if this is a walkin client (using our walkin query)
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
	
	$familyType = ( ($walkIn == 1) ? "Small" : 
					(ISSET($_POST['familySize']) ? familySizeDecoder($_POST['familySize']) : "Small" ));

	// ************************************
	// --== Client current order query ==--
	$orderSql = "SELECT itemID, quantity, special
				 FROM InvoiceDescription
				 WHERE invoiceID=" . $_POST['invoiceID'];
	$orderData = queryDB($conn, $orderSql);
	if ($orderData === FALSE) { die("Order could not be found or has not yet been completed"); }
	
	$clientOrder = FALSE;
	$clientSpecials = FALSE;
	while ($desc = sqlFetch($orderData)) {
		// if this item is a special, save it as a special
		// To avoid issues where items appear on both the order form and the specials list
		if ($desc['special']) {
			$clientSpecials[$desc['itemID']] = $desc['quantity'];
		}
		else {
			$clientOrder[$desc['itemID']] = $desc['quantity'];
		}
	}

	if (!$clientOrder) { die("Order could not be found or has not yet been completed"); }
	//foreach ($clientOrder as $itemID=>$i_count){
	//	echo "Item ID: " . $itemID . " Count: " . $i_count . "<br>";
	//}
	
	// *****************************
	// --== Item database query ==--
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
				echo "<h4><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] . 
					" (" . ($item['CQty']) . " remaining)</div></h4>";
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
				echo "<input type='checkbox' value=" . $item['itemID'];
				echo " onclick='countOrder(this)' name='" . $item['CName'] . "[]' ";
						
				// If this item was selected, check it and reduce our count
				if ( ISSET($clientOrder[$item['itemID']]) ) {
					if ($clientOrder[$item['itemID']] > 0) {
						$clientOrder[$item['itemID']]--;
						echo " checked ";
					}
				}
				// Close off the html input tag
				echo ">";
			}
			echo "<br>";
		}
	}
	
	// *************************************************
	// * Specials
	
	if (!$walkIn) {
		$specialsFile = fopen("specials.txt","r") or die();
		echo "<hr><h2>Specials</h2><h3>Please select one from each section</h3><hr>";
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

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
							echo "<input type='radio' value=" . $itemLine[$i];
							echo " name='savedItem" . $specialItemNum . "' ";
							
							// Check if the item was selected
							if ( ISSET($clientSpecials[$itemLine[$i]]) ) {
								if ($clientSpecials[$itemLine[$i]] > 0) {
									$clientSpecials[$itemLine[$i]]--;
									echo " checked ";
								}
							}
							echo " >" . $itemInfo['displayName'];

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
	
	// ***********************************
	// * Run a javascript function to update selection quantity strings
	echo "<script type='text/javascript'> updateCheckedQuantities(); </script>";
	
?>
		<br>
			<button type="submit" name="CreateReviewedInvoiceDescriptions">Submit Order</button>
		</form>

		
	</body>
</html>