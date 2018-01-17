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
	$blockKeys  = array ('due','order','review','print','wait','completed');
	foreach ($blockKeys as $blockKey) {
    // Initialize counts and table headers
		$dataBlock[$blockKey . 'Count'] = 0;
		$dataBlock[$blockKey] = '<table style="margin-top:10px;" width="100%"><thead><tr><th>Client</th><th>Size</th>
								 <th>Time Slot</th><th>Action</th></tr></thead>';
	}
	
	foreach ($results as $result) {
    // If the client has yet to arrive, build their row
		if ($result['status'] == GetActiveStatus()) {
			$dataBlock['dueCount']++;
			$dataBlock['due'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button id='D" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-sign-in'></i> Check-In</button></td></tr>";
		}
		else if (($result['status'] >= GetArrivedLow()) && ($result['status'] <= GetArrivedHigh())) {
			$dataBlock['orderCount']++;
      $dataBlock['order'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button type='button' class='btn_Action' disabled><i class='fa fa-wpforms'> Client Ordering...</i></button></td></tr>";
		}
		else if (($result['status'] >= GetReadyToReviewLow()) && ($result['status'] <= GetReadyToReviewHigh())) {
			$dataBlock['reviewCount']++;
      $dataBlock['review'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button id='R" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-edit'></i> Review</button></td></tr>";
		}
		else if (($result['status'] >= GetReadyToPrintLow()) && ($result['status'] <= GetReadyToPrintHigh())) {
			$dataBlock['printCount']++;
			$dataBlock['print'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button id='P" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-print'></i> Print</button></td></tr>";
		}
		else if (($result['status'] >= GetPrintedLow()) && ($result['status'] <= GetPrintedHigh())) {
			$dataBlock['waitCount']++;
      $dataBlock['wait'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button id='W" . $result['invoiceID'] . "' type='button' class='btn_Action'><i class='fa fa-shopping-cart'> To Produce</i></button></td></tr>";
		}
		if ($result['status'] == GetCompletedStatus()) {
			$dataBlock['completedCount']++;
      $dataBlock['completed'] .= "<tr><td>" . $result['cName'] . "</td><td>" . $result['familySize'] . 
								 "</td><td>" . returnTime($result['visitTime']) . "</td><td><button type='button' class='btn_Action' disabled><i class='fa fa-check '> No Action</i></button></td></tr>";
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