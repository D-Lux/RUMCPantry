<?php
// Called as an AJAX request when a date is changed and automatically when reporting.php is loaded

function runReportQueries($startDate, $endDate) {
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		echo "Database Error";
	}
	
	//debug
	//echo "Echo Start Date: " . $startDate . "<br>Echo End Date: " . $endDate . "<br>";
	
	// Queries:
	// lbs of food donated to and from pantry
	// $ of food donated to and from pantry
	
	// Perform queries using above dates!
	// Queries:
	// number of families, number of kids, number of people, number of families with kids
	$sql = "SELECT COUNT(*) as totalFamilies, SUM(numOfKids) as totalKids, 
					SUM(numOfAdults + numOfKids) as totalAffected,
					SUM(CASE WHEN numOfKids>0 THEN 1 ELSE 0 END) as familiesWithKids
			FROM Invoice
			JOIN Client
			ON Invoice.clientID = Client.clientID
			WHERE Invoice.visitDate > '" . $startDate . "'
			AND Invoice.visitDate < '" . $endDate . "' 
			AND Invoice.status = " . GetCompletedStatus();

	// Test statement: replace .status blocks above
	/*
	AND Invoice.status > " . GetAvailableStatus() . "
			AND Invoice.status < " . GetRedistributionStatus();
	*/
	
	// Family query test
	$result = queryDB($conn, $sql);
	if ($result!=null && $result->num_rows > 0) {
		$familyQueryData = sqlFetch($result);
	}
	
	// get the total worth of all items donated to clients
	$sql = "SELECT sum(totalItemsPrice) as donationWorth
			FROM Invoice
			JOIN InvoiceDescription
			ON InvoiceDescription.InvoiceID = Invoice.InvoiceID
			WHERE Invoice.visitDate > '" . $startDate . "'
			AND Invoice.visitDate < '" . $endDate . "' 
			AND Invoice.status = " . GetCompletedStatus();
	
	// Test statement: change status blocks above
	// AND Invoice.status > '" . GetAvailableStatus() . "'";
			
	// Invoice query test for donation worth
	$result = queryDB($conn, $sql);
	if ($result!=null && $result->num_rows > 0) {
		$invoiceQueryData = sqlFetch($result);
	}
	
	// get information of items donated to the pantry
	$sql = "SELECT SUM(
				(refbakery * " . WEIGHT_BAKERY . ") +
				(refDairyAndDeli * " . WEIGHT_DAIRY . ") +
				(frozenMeat * " . WEIGHT_MEAT . ") +
				(dryShelfStable * " . WEIGHT_MIX . ") +
				(dryNonFood * " . WEIGHT_NONFOOD . ") +
				(frozenPrepared * " . WEIGHT_PREPARED . ") +
				(refProduce * " . WEIGHT_PRODUCE . ") +
				(frozenNonMeat * " . WEIGHT_FROZEN . ") +
				(dryFoodDrive * " . WEIGHT_FOODDRIVE . ") ) as donatedWeight
			FROM donation 
			WHERE dateOfPickup > '" . $startDate . "'
			AND dateOfPickup < '" . $endDate . "'";

			
	// Query test for donations from partners
	$result = queryDB($conn, $sql);
	if ($result!=null && $result->num_rows > 0) {
		$donationQueryData = sqlFetch($result);
	}
	
	// Close the database connection, we're done with it
	closeDB($conn);
	
	// Assign out our data to be more readable
	$totalFamilies = (isset($familyQueryData['totalFamilies'])) ? $familyQueryData['totalFamilies'] : 0;
	$totalKids = (isset($familyQueryData['totalKids'])) ? $familyQueryData['totalKids'] : 0;
	$totalAffected = (isset($familyQueryData['totalAffected'])) ? $familyQueryData['totalAffected'] : 0;
	$familiesWithKids = (isset($familyQueryData['familiesWithKids'])) ? $familyQueryData['familiesWithKids'] : 0;

	$donationWorth = (isset($invoiceQueryData['donationWorth'])) ? $invoiceQueryData['donationWorth'] : 0;
	
	$donatedWeight = (isset($donationQueryData['donatedWeight'])) ? $donationQueryData['donatedWeight'] : 0;
	
	// ****************************************************************
	// * Output block
	echo "Number of families: " . $totalFamilies . "<br>";
	echo "Number of families with children: " . $familiesWithKids . "<br>";
	echo "Number of people: " . $totalAffected . "<br>";
	echo "Number of children: " . $totalKids . "<br>";
	
	echo "<br>";
	echo "Total donated worth: $" . $donationWorth . "<br>";
	if ( $totalFamilies != 0 ) {
		echo "Average order worth: $" . round(($donationWorth / $totalFamilies), 2, PHP_ROUND_HALF_UP) . "<br>";
	}
	echo "<br>";
	echo "Weight of items donated by partners: " . $donatedWeight . " lbs<br>";

}
?>