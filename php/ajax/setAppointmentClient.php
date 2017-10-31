<?php
	include('../utilities.php');

	if (isset($_GET['activate'])) {
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$statusSql = "SELECT status
					  FROM Invoice
					  WHERE invoiceID=" . $_GET['invoiceID'];
		$statusQuery = queryDB($conn, $statusSql);
		$invoiceStatus = sqlFetch($statusQuery);
			
		// Swap enabling the invoice, only if it is appropriate to do either
		$newStatus = -1;
		if ($invoiceStatus['status'] == GetAssignedStatus()) {
			$newStatus = GetActiveStatus();
		}
		elseif ($invoiceStatus['status'] == GetActiveStatus()) {
			$newStatus = GetAssignedStatus();
		}
			
		// Do the update if appropriate
		if ( $newStatus > 0 ) {
				
			// Update the invoice status
			$updateSql = "UPDATE Invoice
						  SET status=" . $newStatus . "
						  WHERE invoiceID=" . $_GET['invoiceID'];
			if (queryDB($conn, $updateSql) === TRUE) {
				echo visitStatusDecoder($newStatus);
			}
		}
		else {
			echo visitStatusDecoder($invoiceStatus['status']);
		}

		// Close the Database
		closeDB($conn);
	}
	else {
		// Grab our GET Data
		$clientFirstName = revertSingleQuote($_GET['fName']);
		$clientLastName = revertSingleQuote($_GET['lName']);
		$invoiceID = intval($_GET['invoiceID']);

		if ( ($clientFirstName == "Available") && ($clientLastName == "Available") ) {
			$clientID = getAvailableClient();
			// Assign invoice to this client id
			$conn = createPantryDatabaseConnection();
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$newStatus =  GetAvailableStatus();
			$updateInvoice = "UPDATE Invoice
							  SET clientID=" . $clientID . ",
							  status=" . $newStatus . "
							  WHERE invoiceID=" . $invoiceID;
			if (queryDB($conn, $updateInvoice) === FALSE) {
				echoDivWithColor("Error!", "red" );	
			}
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

			$fetchedID = getSingleDataPoint($findClientQuery, $conn, "clientID");
			
			if (!$fetchedID) {
				// Data replacement output
				echo "<td> 0 </td>";
				echo "!PHONENO!";
				echo "<td> Invalid </td>";
				echo "!STATUS!";
				echo "<td> Invalid </td>";
			}
			else {
				// Update the invoice with the new client ID
				// TODO: A warning if the client already has an appointment in this month
				// TODO: A warning if another client has the same address this day
				// Assigned Status
				$newStatus = GetAssignedStatus();
				$updateInvoice = "UPDATE Invoice
								  SET clientID=" . $fetchedID . ",
								  status=" . $newStatus . "
								  WHERE invoiceID=" . $invoiceID;
				if (queryDB($conn, $updateInvoice) === FALSE) {
					echoDivWithColor("Error!", "red" );	
				}
				
				// Get the appropriate family size
				$famSizeSQL = "SELECT (numOfAdults + numOfKids) AS familySize, phoneNumber 
							   FROM Client
							   WHERE clientID=" . $fetchedID;
				$sizeResult = queryDB($conn, $famSizeSQL);
				$famData = sqlFetch($sizeResult);
				
				// Data replacement output
				echo "<td>" . $famData['familySize'] . "</td>";
				echo "!PHONENO!";
				echo "<td>" . displayPhoneNo($famData['phoneNumber']) . "</td>";
				echo "!STATUS!";
				$status = visitStatusDecoder($newStatus);
				echo "<td>" . $status . "</td>";
			}
			
			closeDB($conn);
		}
	}
?>