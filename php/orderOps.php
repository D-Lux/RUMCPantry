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

	// Store off invoiceID and clientID then remove them from POST, we should be left with just our items
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
	$hitIDs = array();
	
	// Start our insertion string
	$insertionSql = "INSERT INTO InvoiceDescription (invoiceID, itemID, quantity, totalItemsPrice) VALUES ";
	$firstInsert = TRUE;
	$runQuery = FALSE;
	
	// Loop through our POST information
	foreach ($_POST as $Category=>$C_Count){
		if ( isCategory($Category) ) {
			foreach ($C_Count as $itemID){
				// Check if we've seen this ID before, if not, run what we need to on it
				if ( !in_array($itemID, $hitIDs) ) {
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
}
else {
	echo "<h1>Nothing was set</h1><br>";
	//header("location: /RUMCPantry/mainpage.php");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>