<script src="/RUMCPantry/js/utilities.js"></script>

<?php

include 'utilities.php';

function addWalkIn($clientID) {
	// Walk ins are always day-of (so grab today's date)
	$walkInDate = makeString(date('Y-m-d', time()));
	// This is an arbitrary time, noon
	$walkInTime = makeString("12:00");
	$sql = "INSERT INTO Invoice (visitDate, visitTime, clientID, Status, walkIn) VALUES ";
	$sql .= "( " . $walkInDate . ", " . $walkInTime . ", " . $clientID . ", " . SV_ASSIGNED . ", 1)";
	
	// Open database connection and perform insertion
	$conn = createPantryDatabaseConnection();
	
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		createCookie("newWalkIn", 1, 30);
		header("location: /RUMCPantry/checkIn.php");
	} 
	else {
		closeDB($conn);
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to insert invoice.", "red" );	
	}
}

// Perform specified function as needed

// **************************************************
// * Creating a new invoice date (along with the appropriate number of slots)
if (isset($_POST['CreateInvoiceDate'])) {
	// Validate date first!
	// Get the date from POST and append zero hour marks (this feels awful)
	$validateDate = makeString($_POST['appDate'] . " 00:00:00");
	
	
	// Generate our sql query with the new date to see if it exists already
	$sql = "SELECT visitDate
			FROM Invoice
			WHERE visitDate=" . $validateDate;
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$invoiceInfo = queryDB($conn, $sql);
	closeDB($conn);
	
	// If the date already exists, return to the previous page
	if ($invoiceInfo->num_rows > 0) {
		// Set a cookie to tell the user the date exists
		// Done this way instead of with url params so reloading the page doesn't cause the warning
		createCookie("badAppDate", 1, 30);
		header("location: /RUMCPantry/ap_ao2.php");
	}
	else {
		// Find the available client ID
		$availID = getAvailableClient();
		
		// Generate the SQL statement to insert all of the invoices
		// There is only one database call
		$sql = "INSERT INTO Invoice (visitDate, visitTime, clientID, Status) VALUES ";
		$firstInsert = TRUE;
		$qtySlot = 0;
		foreach ($_POST["time"] as $timeSlot) {
			for ($i = 0; $i <  $_POST["qty"][$qtySlot]; $i++) {
				$sql .= (!$firstInsert ? "," : "");
				$firstInsert = FALSE;
				$sql .= "( " . $validateDate . ", '" . $timeSlot . "', $availID, 0)";
			}
			$qtySlot++;
		}
		
		// perform insertion
		$conn = createPantryDatabaseConnection();
		
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			// go to view page with date parameter
			createCookie("newAppt", 1, 30);
			header("location: /RUMCPantry/ap_ao3.php?date=" . $_POST['appDate']);
		} 
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to insert invoices.", "red" );	
		}
		closeDB($conn);
	}
}
elseif (isset($_POST['CreateInvoiceTimeSlot'])) {
	// Validate date first!
	// Get the date from POST and append zero hour marks (this feels awful)
	$validateDate = makeString($_POST['appDate'] . " 00:00:00");
	$timeSlot = $_POST['time'];
	
	$availID = getAvailableClient();
		
	// Generate the SQL statement to insert all of the invoices
	// There is only one database call
	$sql = "INSERT INTO Invoice (visitDate, visitTime, clientID, Status, walkIn) VALUES ";
	$firstInsert = TRUE;
	
	for ($i = 0; $i < $_POST['qty'] ; $i++) {
		$sql .= (!$firstInsert ? "," : "");
		$firstInsert = FALSE;
		
		$sql .= "( " . $validateDate . ", '" . $timeSlot . "', $availID, 0, 0)";
	}
	// open the database connection and perform insertion
	$conn = createPantryDatabaseConnection();
	
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		// go to view page with date parameter
		createCookie("newTimeSlots", 1, 30);
		header("location: /RUMCPantry/ap_ao3.php?date=" . $_POST['appDate']);
		
	} 
	else {
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to insert invoices.", "red" );	
	}
	closeDB($conn);
}
elseif (isset($_POST['DeleteInvoice'])) {
	// Generate our sql query to delete the invoice date in question
	$sql = "DELETE FROM Invoice
			WHERE invoiceID=" . $_POST['invoiceID'];
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if (queryDB($conn, $sql) === TRUE) {
		// Close the Database
		closeDB($conn);
		
		// Go back to the previous page main admin client ops page
		header ("location: /RUMCPantry/ap_ao3.php?date=" . $_POST['returnDate']);
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
}

elseif (isset($_POST['clientApptSelect'])) {
	// POST VARS: visitDate visitTime clientID
	// Find all appointments at the time selected, we will look through them to find an open one
	$sql = "SELECT status, invoiceID
			FROM Invoice
			WHERE visitDate='" . $_POST['visitDate'] . "'
			AND visitTime='" . $_POST['visitTime'] . "'";
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$apptQuery = queryDB($conn, $sql);
	if ($apptQuery == NULL || $apptQuery->num_rows <= 0){
		// Bad error, invoice date wasn't found, return to previous page
		echo "sql error: " . mysqli_error($conn);
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("I'm sorry, that appointment time is no longer available.", "red" );	
	}
	else {
		// Loop through the options until we find one
		while ($appt = sqlFetch($apptQuery)) {
			if ($appt['status'] == 0) {
				// Open appointment time, give it to this client
				$sql = "UPDATE Invoice
						SET status =" . GetAssignedStatus() . ", clientID = " . $_POST['clientID'] . "
						WHERE invoiceID=" . $appt['invoiceID'];
				if ( queryDB($conn, $sql) === TRUE ){
					// Assignment was successful, victory!
					closeDB($conn);
					createCookie("clientApptSet", 1, 30);
					// Take us back to login page
					header("location: /RUMCPantry/login.php");
				}
				else {
					// Assignment failed, error back
					closeDB($conn);
					echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
					echoDivWithColor("I'm sorry, that appointment time is no longer available.", "red" );
				}
			}
		}
		
		// If we get this far, we didn't find an available appointment at this time slot
		// Close the db connection and give them a back button
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("I'm sorry, that appointment time is no longer available.", "red" );	
	}
}
// If the client skipped selecting an appointment or there were no appointments to select
elseif (isset($_POST['SkipApt'])) {
	createCookie("clientSkippedAppt", 1, 30);
	// Take us back to login page
	header("location: /RUMCPantry/login.php");
}
// Walk-in appointment creation
elseif (isset($_GET['newWalkIn'])) {
	debugEchoPOST();debugEchoGET();
	addWalkIn($_GET['clientID']);
}
elseif (isset($_POST['existingWalkIn'])) {
	addWalkIn($_POST['clientID']);
}
else {
	echo "<h1>Nothing was set</h1><br>";
	debugEchoPOST();debugEchoGET();
	header("location: /RUMCPantry/mainpage.php");
}

?>