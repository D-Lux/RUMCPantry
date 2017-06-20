<?php
	include('../utilities.php');
	
	// Grab our GET Data
	$clientFirstName = $_GET['fName'];
	$clientLastName = $_GET['lName'];
	$invoiceID = intval($_GET['invoiceID']);

	if ( ($clientFirstName == "Available") && ($clientLastName == "Available") ) {
		$clientID = getAvailableClient();
		// Assign invoice to this client id
		// update status to 0
	}
	else {
		// open a connection to the database
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Find the client ID using the first and last name
		$findClientQuery = "SELECT clientID 
							FROM FamilyMember
							WHERE firstName='" . $clientFirstName . "'
							AND lastName='" . $clientLastName . "'";
		$findResult = queryDB($conn, $findClientQuery);
		$fetchData = sqlFetch($findResult);
		
		// Update the invoice with the new client ID
		// TODO: Some date checking to make sure status 1 is good
		// TODO: Some error checking to make sure this client doesn't already have an appointment on that date?
		$newStatus = 1;
		$updateInvoice = "UPDATE Invoice
						  SET clientID=" . $fetchData['clientID'] . ",
						  status=" . $newStatus . "
						  WHERE invoiceID=" . $invoiceID;
		if (queryDB($conn, $updateInvoice) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to set client inactive.", "red" );	
		}
		
		// Get the appropriate family size
		$famSizeSQL = "SELECT (numOfAdults + numOfKids) AS familySize
				FROM Client
				WHERE clientID=" . $fetchData['clientID'];
		$sizeResult = queryDB($conn, $famSizeSQL);
		$famData = sqlFetch($sizeResult);
		
		// Data replacement output
		echo "<td>" . $famData['familySize'] . "</td>";
		echo "!BREAK!";
		$status = visitStatusDecoder($newStatus);
		echo "<td>" . $status . "</td>";
		
		closeDB($conn);
	}
?>