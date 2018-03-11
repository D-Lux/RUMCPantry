<?php
	include('../utilities.php');

	if (isset($_GET['activate'])) {
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$statusSql = "SELECT status
					  FROM invoice
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
			$updateSql = "UPDATE invoice
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
		$clientID = intval($_GET['client']);
		$invoiceID = intval($_GET['invoice']);
    $availClient = getAvailableClient();
    
    $newStatus = ($availClient == $clientID) ? GetAvailableStatus() : GetAssignedStatus();

    
		$sql = "UPDATE invoice
						SET clientID=" . $clientID . ",
						status=" . $newStatus . "
            WHERE invoiceID=" . $invoiceID;
    
    // TODO: Make sure this client doesn't have another appointment this month
    // TODO: Make sure no other clients on this day have the same address
    $conn = connectDB();
		if (queryDB($conn, $sql)) {
      $sql = "SELECT (numOfAdults + numOfKids) AS familySize, phoneNumber
              FROM client
              WHERE clientID=" . $clientID;
      $info = runQueryForOne($conn, $sql);
      closeDB($conn);
      $returnArr['phone']      = displayPhoneNo($info['phoneNumber']);
      $returnArr['familySize'] = $info['familySize'];
      $returnArr['status']     = visitStatusDecoder($newStatus);
      $returnArr['err']        = (int)0;
      die(json_encode($returnArr));
			// Do updates
		}
		else {
      closeDB($conn);
      $returnArr['msg'] = "Failed to set appointment.";
      $returnArr['err'] = 1;
      die(json_encode($returnArr));
    }
	}
?>