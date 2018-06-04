<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
  session_start();
	include('../utilities.php');

	if (isset($_GET['activate'])) {
		$conn = connectDB();
		if ($conn->connect_error) {
      $returnArr['msg'] = "Connection failed: " . $conn->connect_error;
      $returnArr['err'] = 1;
      die(json_encode($returnArr));
		}
		$statusSql = "SELECT status
					  FROM invoice
					  WHERE invoiceID=" . $_GET['invoiceID'];

		$invoiceStatus = runQueryForOne($conn, $statusSql);

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

    $setAvail = $availClient == $clientID;
    $newStatus = $setAvail ? GetAvailableStatus() : GetAssignedStatus();

    $conn = connectDB();
    if ($conn->connect_error) {
      $returnArr['msg'] = "<i style='color:red;' class='fa fa-exclamation-triangle'></i>  Database Connection failed: " . $conn->connect_error;
      $returnArr['err'] = 1;
      die(json_encode($returnArr));
    }


    // Make sure this client doesn't already have an appointment this month
    if (!$setAvail) {
      $apptYM = date("Ym", strtotime($_GET['date']));
      $sql = "SELECT COUNT(*) as visitNum
              FROM invoice
              WHERE DATE_FORMAT(visitDate, '%Y%m') = '{$apptYM}'
              AND clientID = {$clientID}
              AND status NOT IN (" . GetCanceledStatus() . "," . GetNoShowStatus() . "," . GetBadDocumentationStatus() . ") ";
      if (runQueryForOne($conn, $sql)['visitNum'] > 0) {
        closeDB($conn);
        $returnArr['msg'] = "<i style='color:red;' class='fa fa-exclamation-triangle'></i>  Client already has an appointment this month.";
        $returnArr['err'] = 1;
        die(json_encode($returnArr));
      }
    }

		$sql = "UPDATE invoice
						SET clientID=" . $clientID . ",
						status=" . $newStatus . "
            WHERE invoiceID=" . $invoiceID;

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
		}
		else {
      closeDB($conn);
      $returnArr['msg'] = "<i style='color:red;' class='fa fa-exclamation-triangle'></i> Failed to set appointment.";
      $returnArr['err'] = 1;
      die(json_encode($returnArr));
    }
	}
?>