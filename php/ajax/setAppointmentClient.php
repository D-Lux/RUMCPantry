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
		if ($invoiceStatus['status'] == 100) {
			$newStatus = 200;
		}
		elseif ($invoiceStatus['status'] == 200) {
			$newStatus = 100;
		}
			
		// Do the update if appropriate
		if ( $newStatus > 0 ) {
				
			// Find the client ID using the first and last name
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
			// TODO: A warning if the client already has an appointment in this month
			// Assigned Status
			$newStatus = 100;
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
			$famSizeSQL = "SELECT (numOfAdults + numOfKids) AS familySize, phoneNumber 
						   FROM Client
						   WHERE clientID=" . $fetchData['clientID'];
			$sizeResult = queryDB($conn, $famSizeSQL);
			$famData = sqlFetch($sizeResult);
			
			// Data replacement output
			echo "<td>" . $famData['familySize'] . "</td>";
			echo "!PHONENO!";
			echo "<td>" . displayPhoneNo($famData['phoneNumber']) . "</td>";
			echo "!STATUS!";
			$status = visitStatusDecoder($newStatus);
			echo "<td>" . $status . "</td>";
			
			closeDB($conn);
		}
	}
?>