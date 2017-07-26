<script src="/RUMCPantry/js/utilities.js"></script>

<?php

include 'utilities.php';

// Grab Invoice and Client data from POST
// Go through each category and store off counts of ID appearances to generate an SQL INSERT statement
// Create InvoiceDescriptions based on every item added
// Return the client to the login screen


// A better way to do this would be to create an array of all of the category names from category and compare there
function isCategory($data) {
	switch($data) {
		case ("invoiceID"):
		case ("clientID"):
		case ("CreateInvoiceDescriptions"):
		case ("walkInStatus"):
			return FALSE;
		
		default: 
			return TRUE;
	}
}

debugEchoPOST();
echo "<br>";

// **************************************************
// * Creating invoice descriptions and tying them together into one invoice
if (isset($_POST['CreateInvoiceDescriptions'])) {

	// Store off invoiceID and clientID
	$invoiceID = $_POST['invoiceID'];
	$clientID = $_POST['clientID'];

	// *********************************************
	// * --== Create our item ID / Price array ==--
	
	// Connect to the database and grab Item ID and Price information
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Our Query String
	$sql = "SELECT itemID, price
			FROM Item";
	$itemPriceQuery = queryDB($conn, $sql);
	
	$PriceID_Array = array();
	while( $itemRow = sqlFetch($itemPriceQuery) ) {
		$PriceID_Array[$itemRow['itemID']] = $itemRow['price'];
	}
	
	// ***********************************************
	// * --== Create our Insertion Query ==--
	
	// Create the array to track item IDs
	$orderIDs = array();
	$specialIDs = array();
	$runQuery = FALSE;
	
	// ************************************
	// * New hotness
	
	// Loop through our POST information to generate an array of itemIDs and quantities
	foreach ($_POST as $Category=>$C_Count){
		if ( isCategory($Category) ) {
			if (is_array($C_Count)) {
				foreach ($C_Count as $itemID){
					echo "checking on adding " . $itemID . "<br>";
					// count all items with a particular ID
					$orderIDs[$itemID]++;
					$runQuery = TRUE;
				}
			}
			else {
				// If the item is a special, it will not be an array
				echo "New item, adding.<br>";
				$specialIDs[$C_Count]++;
				$runQuery = TRUE;
			}
		}
	}
	
	// Generate our insertion string
	$insertionSql = "INSERT INTO InvoiceDescription (invoiceID, itemID, quantity, totalItemsPrice, special ) VALUES ";
	$firstInsert = TRUE;
	
	foreach ($orderIDs as $itemID=>$qty) {
		$totalPrice = $qty * $PriceID_Array[$itemID];
		$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion		
		$insertionSql .= "( $invoiceID, $itemID, $qty, $totalPrice, 0 )";
		$firstInsert = FALSE;				
	}
	foreach ($specialIDs as $itemID=>$qty) {
		$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion		
		$insertionSql .= "( $invoiceID, $itemID, $qty, $PriceID_Array[$itemID], 1 )";
		$firstInsert = FALSE;				
	}

	// Run our query
	if ($runQuery) {
		if (queryDB($conn, $insertionSql) === TRUE) {
			closeDB($conn);
			echo "Insertion successful";
			header("location: /RUMCPantry/cap.php?clientID=" . $_POST['clientID']);
		}
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to insert invoices.", "red" );	
			closeDB($conn);
		}
	}
	else {
		closeDB($conn);
		header("location: /RUMCPantry/cap.php?clientID=" . $_POST['clientID']);
	}
	/*
	// Loop through our POST information
	foreach ($_POST as $Category=>$C_Count){
		if ( isCategory($Category) ) {
			if (is_array($C_Count)) {
				foreach ($C_Count as $itemID){
					echo "checking on adding " . $itemID . "<br>";
					// Check if we've seen this ID before, if not, run what we need to on it
					if ( !array_key_exists($itemID, $hitIDs) ) {
						echo "New item, adding.<br>";
						// Add the item to our hit IDs so we don't call it again
						$hitIDs[] = $itemID;
						
						// Get our count and price for the insertion string
						$itemCount = returnCountOfItem($itemID, $C_Count);
						$totalPrice = $itemCount * $PriceID_Array[$itemID];
						
						// Update our insertion string
						$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion		
						$insertionSql .= "( $invoiceID, $itemID, $itemCount, $totalPrice)";
						$firstInsert = FALSE;
						$runQuery = TRUE;
					}
				}
			}
			else {
				// If the item is a special, it will not be an array
				if ( !in_array($C_Count, $hitIDs) ) {
					echo "New item, adding.<br>";
					// Add the item to our hit IDs so we don't call it again
					$hitIDs[] = $C_Count;
					
					// Get our price for the insertion string
					$totalPrice = $PriceID_Array[$C_Count];
					
					// Update our insertion string
					$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion		
					$insertionSql .= "( $invoiceID, $C_Count, 1, $totalPrice)";
					$firstInsert = FALSE;
					$runQuery = TRUE;
				}
				else {
				}
			}
		}
	}
	
	// Run our query
	if ($runQuery) {
		if (queryDB($conn, $insertionSql) === TRUE) {
			closeDB($conn);
			echo "Insertion successful";
			header("location: /RUMCPantry/cap.php?clientID=" . $_POST['clientID']);
		}
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to insert invoices.", "red" );	
			closeDB($conn);
		}
	}
	else {
		closeDB($conn);
		header("location: /RUMCPantry/cap.php?clientID=" . $_POST['clientID']);
	}
	*/
}

// **************************************************
// * Setting an invoice to processed

elseif (isset($_POST['SetInvoiceProcessed'])) {
	
	// *********************************************
	// * --== Create our update query ==--
	
	// Connect to the database
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "UPDATE Invoice
			SET status=" . (ReturnProcessedStatus($_POST['status'])) . "
			WHERE invoiceID=" . $_POST['invoiceID'];
	if (queryDB($conn, $sql) === TRUE) {
		// Success, return to ap_oo3 with no issues
		//header("location: /RUMCPantry/ap_oo3.php");
		header("location: /RUMCPantry/checkIn.php");
	}
	else {
		// There was an error, create a cookie to give a popup when we hit ap_oo3
		createCookie("processError", 1, 30);
		//header("location: /RUMCPantry/ap_oo3.php");
		header("location: /RUMCPantry/checkIn.php");
	}
}

// **************************************************
// * Creating Specials Order form (saved as a .txt file)
elseif (isset($_POST['SaveSpecials'])) {
	// go through the post and any time we have a post value that contains 'item' run through it
	// search each item in the db and get it's ID, append the ID to a line in the text file
	// itemID1,itemID2,itemID3, - use explode(",",getLine($filename)); to extract arrays of item IDs
	
	// Open our database connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Open our specials file
	$specialsFile = fopen("../specials.txt","w") or die("Unable to open specials!");
	
	$firstLine = TRUE;
	// Go through our post data and pull out the appropriate item strings
	foreach ($_POST as $varName=>$itemArr){
		// Skip SaveSpecials Post (it was created on the button press and isn't needed)
		if ( $varName != "SaveSpecials" ) {
			if (!$firstLine) {
				fwrite($specialsFile,  "\r\n");
			}
			else {
				$firstLine = FALSE;
			}
			foreach ($itemArr as $itemName){
				$sql = "SELECT itemID
						FROM Item
						WHERE itemName='" . $itemName . "'
						LIMIT 1";
						
				$itemIDGrab = queryDB($conn, $sql);
				
				if ($itemIDGrab == NULL || $itemIDGrab->num_rows <= 0){
					echo "sql error: " . mysqli_error($conn);	
				}
				else {
					$itemID = sqlFetch($itemIDGrab);
					fwrite($specialsFile,  $itemID['itemID'] . ",");
				}
			}
		}
	}
	
	closeDB($conn);
	fclose($specialsFile);
	createCookie("SpecialsSaved", 1, 30);
	header("location: /RUMCPantry/ap_oo1.php");
}

else {
	echo "<h1>Nothing was set</h1><br>";
	//header("location: /RUMCPantry/mainpage.php");
}

?>