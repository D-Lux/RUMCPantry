

<?php include 'utilities.php'; ?>

<?php

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

// *****************************************************
// * Function to delete invoice descriptions so we can remake them with reviewed data
function deleteInvoiceDesc() {
	$invoiceID = $_POST['invoiceID'];

	// Open up the database and delete all invoice descriptions that match the invoice ID

	// Connect to the database
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM invoicedescription
			WHERE invoiceID=" . $invoiceID;

	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		return true;
	}
	closeDB($conn);
	return false;
}

// *******************************************************************************************
// * Function to create invoice descriptions (since both the client and reviewer create them)
function createInvoiceDesc() {
	// Store off invoiceID and clientID
	$invoiceID = $_POST['invoiceID'];
	$clientID = $_POST['clientID'];

	// *********************************************
	// * --== Create our item ID / Price array ==--
	$PriceID_Array = getItemPrices();
	// Connect to the database and grab Item ID and Price information
	$conn = connectDB();

	// ***********************************************
	// * --== Create our Insertion Query ==--

	// Create the array to track item IDs
	$orderIDs   = [];
	$specialIDs = [];
	$runQuery   = FALSE;

	// Loop through our POST information to generate an array of itemIDs and quantities
	foreach ($_POST as $Category=>$C_Count){
		if ( isCategory($Category) ) {
			if (is_array($C_Count)) {
				foreach ($C_Count as $itemID){
					//echo "Adding: " . $itemID . "<br>";
					// count all items with a particular ID
					if (!isset($orderIDs[$itemID])) {
						$orderIDs[$itemID] = null;
					}
					$orderIDs[$itemID]++;
					$runQuery = TRUE;
				}
			}
			else {
				// If the item is a special, it will not be an array
				// Make sure we're looking at a number and not a space
				if (is_numeric($C_Count)) {
					//echo "Special, adding: " . $C_Count . "<br>";
					if (!isset($specialIDs[$C_Count])) {
						$specialIDs[$C_Count] = null;
					}
					$specialIDs[$C_Count]++;
					$runQuery = TRUE;
				}
			}
		}
	}

	// Generate our insertion string
	$insertionSql = "INSERT INTO invoicedescription (invoiceID, itemID, quantity, totalItemsPrice, special ) VALUES ";
	$firstInsert = TRUE;

	foreach ($orderIDs as $itemID=>$qty) {
		$totalPrice = $qty * (isset($PriceID_Array[$itemID]) ? $PriceID_Array[$itemID] : 0);
		$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion
		$insertionSql .= "( $invoiceID, $itemID, $qty, $totalPrice, 0 )";
		$firstInsert = FALSE;
	}
	foreach ($specialIDs as $itemID=>$qty) {
		$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion
		$insertionSql .= "( $invoiceID, $itemID, $qty, ";
		$insertionSql .= (isset($PriceID_Array[$itemID]) ? $PriceID_Array[$itemID] : 0) . ", 1 )";
		$firstInsert = FALSE;
	}

	//echo "Insertion query: ";
	//echo $insertionSql . "<br>";
	// Run our query
	if ($runQuery) {
		if (queryDB($conn, $insertionSql) === TRUE) {
			closeDB($conn);
			return true;
		}
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to insert invoices.", "red" );
			closeDB($conn);
		}
	}
	else {
		closeDB($conn);
    redirectPage("cap.php?clientID=" . $_POST['clientID']);
	}
}

// ***************************************************************
// * Function to set the status of an invoice to the correct value
function updateStatusFromOrder() {
	// Connect to the database
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Generate our query to get our current status
	$sql = "SELECT status
			FROM invoice
			WHERE invoiceID=" . $_POST['invoiceID'] . "
			LIMIT 1";

	$result = queryDB($conn, $sql);
	if ($result === false) {
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to get current status.", "red" );
		closeDB($conn);
	}
	else {
		// Now we advance our status variable
		$fetchData = sqlFetch($result);

		// Get the status, if the order status isn't set to arrived at least, set it now
		$currStatus = ($fetchData['status'] >= GetArrivedLow()) ? $fetchData['status'] : GetArrivedLow();
		$newStatus = AdvanceInvoiceStatus($currStatus) ;

		// Generate our update query with the new status
		$sql = "UPDATE invoice
				SET status=" . $newStatus . "
				WHERE invoiceID=" . $_POST['invoiceID'];

		// Run the query, if we're good, return success
		if (queryDB($conn, $sql)) {
			closeDB($conn);
			return true;
		}
		else {
			closeDB($conn);
			return false;
		}
	}
}


// **************************************************
// * Creating invoice descriptions and tying them together into one invoice
if (isset($_POST['CreateInvoiceDescriptions'])) {
	if (createInvoiceDesc()) {
		//echo "Insertion successful";
		if (updateStatusFromOrder()) {
			redirectPage("cap.php?clientID=" . $_POST['clientID']);
		}
		else {
			echo "There was an error creating your order, please try again";
		}
	}
}

// *****************************************
// * Reviewing invoices
elseif (isset($_POST['CreateReviewedInvoiceDescriptions'])) {
	if (deleteInvoiceDesc()) {
		if (createInvoiceDesc()) {
			if (updateStatusFromOrder()) {
				//echo "Review Successful!";
				redirectPage("checkIn.php");
			}
			else {
				echo "There was an error reviewing the order, please try again";
			}
		}
	}
	echo "There was an error reviewing the order, please try again";
}



// **************************************************
// * Setting an invoice to processed
elseif (isset($_POST['SetInvoiceProcessed'])) {

	// *********************************************
	// * --== Create our update query ==--

	// Connect to the database
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE invoice
			SET status=" . (ReturnProcessedStatus($_POST['status'])) . "
			WHERE invoiceID=" . $_POST['invoiceID'];
	if (queryDB($conn, $sql) === TRUE) {
		// Success, return to checkin with no issues
		redirectPage("checkIn.php");
	}
	else {
		// There was an error, create a cookie to give a popup when we hit checkIn
		createCookie("processError", 1, 30);
		//redirectPage("ap_oo3.php");
		redirectPage("checkIn.php");
	}
}

// **************************************************
// * Creating Specials Order form (saved as a .txt file)
elseif (isset($_POST['SaveSpecials'])) {
	// go through the post and any time we have a post value that contains 'item' run through it
	// search each item in the db and get it's ID, append the ID to a line in the text file
	// itemID1,itemID2,itemID3, - use explode(",",getLine($filename)); to extract arrays of item IDs

	// Open our database connection
	$conn = connectDB();
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
						FROM item
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
	redirectPage("ap_oo1.php");
}

// ***************************************
// * Adding a single item to a client's order
elseif (isset($_POST['addItemToOrder'])) {
	// POST Data to use: invoiceID, addItem, qty
	// POST Data to pass back using 'get' global: name, visitTime, familySize,

	// Create our return header to go back to the correct order
	$returnHeader = "ap_oo4e.php?invoiceID=" . $_POST['invoiceID'] . "&name=" . $_POST['name'] .
					"&visitTime=" . $_POST['visitTime'] . "&familySize=" . $_POST['familySize'];

	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// If they already have the item in their invoice, just update the quantity
	// If they don't, add a new invoice description

	$sql = "SELECT quantity, invoiceDescID as ID, price, totalItemsPrice
			FROM invoicedescription
			JOIN item
			ON item.itemID = invoicedescription.itemID
			WHERE invoiceID=" . $_POST['invoiceID'] . "
			AND item.itemName=" . makeString($_POST['addItem']);

	$descQuery = queryDB($conn, $sql);

	if ($descQuery!=null && $descQuery->num_rows > 0) {
		$oldData = sqlFetch($descQuery);

		$newQty = $oldData['quantity'] + $_POST['qty'];
		$newPrice = $oldData['totalItemsPrice'] + ($_POST['qty'] * $oldData['price']);

		$sql = "UPDATE invoicedescription
				SET quantity=" . $newQty . ", totalItemsPrice=" . $newPrice . "
				WHERE invoiceDescID=" . $oldData['ID'];
		if (queryDB($conn, $sql) === TRUE) {
			// Successfully updated item quantity, return to the order form!
			closeDB($conn);
			redirectPage($returnHeader);
		}
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to update existing item count.", "red" );
			closeDB($conn);
		}
	}
	else {
		// The item wasn't already in the invoice, that's okay, we can add it now
		// First, we find the item's ID
		$sql = "SELECT itemID, price
				FROM item
				WHERE itemName=" . makeString($_POST['addItem']) . "
				LIMIT 1";
		$idQuery = queryDB($conn, $sql);

		// Check our query to see if we got the item
		if ($idQuery != null) {
			// We've got our ID now, just insert it with it's quantity
			$itemIdFetch = sqlFetch($idQuery);
			$itemPrice = $itemIdFetch['price'] * $_POST['qty'];
			$sql = "INSERT INTO invoicedescription
					(invoiceID, itemID, quantity, totalItemsPrice, special)
					VALUES
					(" . $_POST['invoiceID'] . ", " . $itemIdFetch['itemID'] . ", " .
					 $_POST['qty'] . ", " . $itemPrice . ", 0)";
			if (queryDB($conn, $sql) === TRUE) {
				// Success! We've added the new item description values
				closeDB($conn);
				redirectPage($returnHeader);
			}
			else {
				echoDivWithColor("Error description: " . mysqli_error($conn), "red");
				echoDivWithColor("Error, failed to add new item.", "red" );
				closeDB($conn);
			}
		}
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to find item ID to add.", "red" );
			closeDB($conn);
		}




	$descQuery = queryDB($conn, $sql);
	}

}

else {
	redirectPage("mainpage.php");
}

?>