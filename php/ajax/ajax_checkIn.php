<?php

	include '../utilities.php';

	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		$dataBlock['error'] = "Connection failed: " . $conn->connect_error;
		die();
	}
	
	$date = $_POST['field1'];
	
  // Query the database to get all of our client data for the passed date
	$sql = "SELECT  CONCAT(FamilyMember.LastName, ', ' , FamilyMember.firstName) as cName,
					(Client.numOfKids + numOfAdults) AS familySize,
					Invoice.invoiceID, Invoice.clientID, 
				    Invoice.visitTime, Invoice.status
			FROM Invoice
			JOIN Client 
				ON Invoice.clientID=Client.clientID
			JOIN FamilyMember 
				ON Client.clientID=FamilyMember.clientID
			WHERE Invoice.visitDate = '" . $date . "'
			AND Invoice.status BETWEEN " .  GetActiveStatus() . " AND " . GetCompletedStatus() . "
			AND FamilyMember.isHeadOfHousehold =true
			ORDER BY Invoice.visitTime ASC, Invoice.status ASC, FamilyMember.LastName ASC";
			
	$results = returnAssocArray(queryDB($conn, $sql));
	closeDB($conn);
	
  // Start our return array
	$dataBlock  = [];
  
  // Array for our status types
	$blockKeys  = array ('due','order','review','print','wait','complete');
	foreach ($blockKeys as $blockKey) {
    // Initialize counts and table headers
		$dataBlock[$blockKey . 'Count'] = 0;
		$dataBlock[$blockKey] = '<table style="margin-top:10px;" width="100%"><thead><tr><th>Client</th><th>Family Size</th>
								 <th>Appointment Time</th><th>Action</th></tr></thead>';
	}
	
	foreach ($results as $result) {
		if ($result['status'] == GetActiveStatus()) {
			$dataBlock['dueCount']++;
			$dataBlock['due'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button id='D" . $result['invoiceID'] . "' class='btn_checkIn'>Check-In</button></td></tr>";
		}
		else if (($result['status'] >= GetArrivedLow()) && ($result['status'] <= GetArrivedHigh())) {
			$dataBlock['orderCount']++;
			$dataBlock['order'] .= time() . " data for order page";
		}
		else if (($result['status'] >= GetReadyToReviewLow()) && ($result['status'] <= GetReadyToReviewHigh())) {
			$dataBlock['reviewCount']++;
			$dataBlock['review'] .= time() . " data for review page";
		}
		else if (($result['status'] >= GetReadyToPrintLow()) && ($result['status'] <= GetReadyToPrintHigh())) {
			$dataBlock['printCount']++;
			$dataBlock['print'] .= time() . " data for print page";
		}
		else if (($result['status'] >= GetPrintedLow()) && ($result['status'] <= GetPrintedHigh())) {
			$dataBlock['waitCount']++;
			$dataBlock['wait'] .= time() . " data for waiting page";
		}
		if ($result['status'] == GetCompletedStatus()) {
			$dataBlock['completeCount']++;
			$dataBlock['complete'] .= time() . " data for completed page";
		}
	}

	foreach ($blockKeys as $blockKey) {
		if ($dataBlock[$blockKey . 'Count'] == 0 ) {
			$dataBlock[$blockKey . 'Count'] = "";
			$dataBlock[$blockKey] = "";
		}
		else {
			$dataBlock[$blockKey . 'Count'] = "(" . $dataBlock[$blockKey . 'Count'] . ")";
			$dataBlock[$blockKey] .= "</table>";
		}	
	}
	
	
	echo (json_encode($dataBlock));
?>