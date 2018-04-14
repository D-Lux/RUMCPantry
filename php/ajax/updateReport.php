<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
	include('../utilities.php');
	
	// AJAX call to update reporting with new dates
	if (!isset($_GET['startDate']) ||  !isset($_GET['endDate'])) {
		DIE("Please Select Dates");
	}
  $startDate = $_GET['startDate'];
  $endDate   = $_GET['endDate'];
	$conn      = connectDB();
	
	// Queries:
	// lbs of food donated to and from pantry
	// $ of food donated to and from pantry
	
	// Perform queries using above dates!
	// Queries:
	// number of families, number of kids, number of people, number of families with kids
	$sql = "SELECT COUNT(*) as totalFamilies, SUM(numOfKids) as totalKids, 
					SUM(numOfAdults + numOfKids) as totalAffected,
					SUM(CASE WHEN numOfKids>0 THEN 1 ELSE 0 END) as familiesWithKids
			FROM invoice
			JOIN client
			ON invoice.clientID = client.clientID
			WHERE invoice.visitDate > '" . $startDate . "'
			AND invoice.visitDate < '" . $endDate . "' 
			AND invoice.status = " . GetCompletedStatus();

	// Family query
	$familyQueryData = runQueryForOne($conn, $sql);
	
	// get the total worth of all items donated to clients
	$sql = "SELECT sum(totalItemsPrice) as donationWorth
			FROM invoice
			JOIN invoicedescription
			ON invoicedescription.InvoiceID = invoice.InvoiceID
			WHERE invoice.visitDate > '" . $startDate . "'
			AND invoice.visitDate < '" . $endDate . "' 
			AND invoice.status = " . GetCompletedStatus();
			
	// Invoice query test for donation worth
	$invoiceQueryData = runQueryForOne($conn, $sql);
	
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
	$donationQueryData = runQueryForOne($conn, $sql);
	
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
?>