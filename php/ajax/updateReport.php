<?php
	include('../utilities.php');
	
	// AJAX call to update reporting
	if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
		// Grab our GET Data
		$startDate = date('Y-m-d', strtotime($_GET['startDate']));
		$endDate = date('Y-m-d', strtotime($_GET['endDate']));

		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			echo "Database Error";
		}
		
		// Get updated date data
		$sql = "SELECT SUM(numOfKids) as totalKids, (SUM(numOfAdults) + SUM(numOfKids)) as totalAffected
				FROM Invoice
				JOIN Client
				ON Invoice.clientID = Client.clientID
				WHERE Invoice.visitDate > '" . $startDate . "'
				AND Invoice.visitDate < '" . $endDate . "'";
				
		$result = queryDB($conn, $sql);
		if ($result!=null && $result->num_rows > 0) {
			$queryData = sqlFetch($result);
			echo "Num kids: " . $queryData['totalKids'] . "<br>";
			echo "Num people: " . $queryData['totalAffected'] . "<br>";
		}
		else {
			echo "No data for this time frame";
		}
	}
	
	closeDB($conn);
?>