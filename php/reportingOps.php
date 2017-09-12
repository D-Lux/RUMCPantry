<?php

function runReportQueries($startDate, $endDate) {
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		echo "Database Error";
	}
		
	// Queries:
	// # of families impacted
	// # of people impacted
	// # of children
	// lbs of food donated
	// $ of food dontaed
	
	// Perform queries using above dates!
	$sql = "SELECT COUNT(*) as totalFamilies, SUM(numOfKids) as totalKids, 
					(SUM(numOfAdults) + SUM(numOfKids)) as totalAffected
			FROM Invoice
			JOIN Client
			ON Invoice.clientID = Client.clientID
			WHERE Invoice.visitDate > '" . $startDate . "'
			AND Invoice.visitDate < '" . $endDate . "'"; /*
			AND Invoice.status > " . returnProcessedLow() . "
			AND Invoice.status < " . returnProcessedHigh(); */
	
	$result = queryDB($conn, $sql);
	if ($result!=null && $result->num_rows > 0) {
		$queryData = sqlFetch($result);
		
		echo "Num families: " . $queryData['totalFamilies'] . "<br>";
		echo "Num kids: " . $queryData['totalKids'] . "<br>";
		echo "Num people: " . $queryData['totalAffected'] . "<br>";
		
	}
	else {
		echo "No data is available for that time frame.";
	}
	
	closeDB($conn);
}
?>