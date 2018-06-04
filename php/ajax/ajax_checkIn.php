<?php
  session_start();
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
	include '../utilities.php';

	$conn = connectDB();
	if ($conn->connect_error) {
		$dataBlock['error'] = "Connection failed: " . $conn->connect_error;
		die();
	}

	$date = $_POST['field1'];

  // Query the database to get all of our client data for the passed date
	$sql = "SELECT  CONCAT(fm.LastName, ', ' , fm.firstName) as cName,
					(c.numOfKids + numOfAdults) AS familySize, i.invoiceID, i.clientID,
				    i.visitTime, i.status, i2.firstVisit
			FROM invoice i
			JOIN client c
				ON i.clientID=c.clientID
			JOIN familymember fm
				ON c.clientID=fm.clientID
			JOIN (SELECT MIN(visitDate) as firstVisit, clientID FROM invoice GROUP BY clientID) i2
				ON i2.clientID = i.clientID
			WHERE i.visitDate = '" . $date . "'
			AND i.status BETWEEN " .  GetActiveStatus() . " AND " . GetCompletedStatus() . "
			AND fm.isHeadOfHousehold =true
			ORDER BY i.visitTime ASC, i.status ASC, fm.LastName ASC";

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

	if (count($results) < 1) { die(); }

	foreach ($results as $result) {
		$fv = $result['firstVisit'] == date("Y-m-d", strtotime("today")) ? "<i class='fa fa-star' style='color:blue;'></i> " : "";
    // If the client has yet to arrive, build their row
		if ($result['status'] == GetActiveStatus()) {
			$dataBlock['dueCount']++;
			$dataBlock['due'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) .
								 "</td><td><button id='D" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-sign-in'></i> Check-In</button></td></tr>";
		}
		else if (($result['status'] >= GetArrivedLow()) && ($result['status'] <= GetArrivedHigh())) {
			$dataBlock['orderCount']++;
      $dataBlock['order'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) .
								 "</td><td><button type='button' class='btn_Action' disabled><i class='fa fa-wpforms'> Client Ordering...</i></button></td></tr>";
		}
		else if (($result['status'] >= GetReadyToReviewLow()) && ($result['status'] <= GetReadyToReviewHigh())) {
			$dataBlock['reviewCount']++;
      $dataBlock['review'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) . " - " . ($result['status'] % 100 + 1) .
								 "</td><td><button id='R" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-edit'></i> Review</button></td></tr>";
		}
		else if (($result['status'] >= GetReadyToPrintLow()) && ($result['status'] <= GetReadyToPrintHigh())) {
			$dataBlock['printCount']++;
			$dataBlock['print'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) . " - " . ($result['status'] % 100 + 1) .
								 "</td><td><button id='P" . $result['invoiceID'] . "' class='btn_Action'><i class='fa fa-print'></i> Print</button></td></tr>";
		}
		else if (($result['status'] >= GetPrintedLow()) && ($result['status'] <= GetPrintedHigh())) {
			$dataBlock['waitCount']++;
      $dataBlock['wait'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) . " - " . ($result['status'] % 100 + 1) .
								 "</td><td><button id='W" . $result['invoiceID'] . "' type='button' class='btn_Action'><i class='fa fa-shopping-cart'> To Produce</i></button></td></tr>";
		}
		if ($result['status'] == GetCompletedStatus()) {
			$dataBlock['completedCount']++;
      $dataBlock['completed'] .= "<tr><td>" . $fv . $result['cName'] . "</td><td>" . $result['familySize'] .
								 "</td><td>" . returnTime($result['visitTime']) .
								 "</td><td><button type='button' class='btn_Action' disabled><i class='fa fa-check '> No Action</i></button></td></tr>";
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