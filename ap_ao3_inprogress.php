<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<style>
	.btn_lock {
		color: black !important;
	}
</style>

	<h3>Appointment Date: <?php echo date("F jS, Y", strtotime($_GET['date'])); ?></h3>
	<div class="body-content">

	<?php
		// Set up server connection
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// *************************************************
		// Query the database

		// Grab all of the information we need from Client, familymember and Invoice
		$sql = "SELECT visitTime, status, invoiceID,
					fam.firstName AS fName, fam.lastName AS lName, ( fam.numOfAdults + fam.numOfKids) AS familySize,
					fam.phoneNumber as PhoneNo
				FROM invoice
				JOIN (
					SELECT familymember.clientID, familymember.firstName, familymember.lastName,
							client.numOfAdults, client.numOfKids, client.phoneNumber
					FROM familymember
					JOIN client
					ON familymember.clientID = client.clientID
					WHERE familymember.isHeadOfHousehold=1 ) AS fam
				ON fam.clientID=invoice.clientID
				WHERE visitDate='" . $_GET['date'] . "'
				AND status NOT IN (" . implode ( ",", GetRedistributionStatuses()) . ")
				ORDER BY visitTime, invoiceID";

		$visitInfo = queryDB($conn, $sql);

		// *******************************************
		// ** Generate the datalist for client drop down

		$sql = "SELECT firstName AS fName, lastName AS lName, familymember.clientID
				FROM familymember
				JOIN client
				ON client.clientID=familymember.clientID
				WHERE familymember.isHeadOfHousehold=1
				AND familymember.isDeleted=0
				AND client.redistribution=0";

		//$clientInfo = queryDB($conn, $sql);
		$clientInfo = runQuery($conn, $sql);

		if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
			echo "No clients available";
		}


    // TODO: Change this to a chosen-select
    //$clientDataList = "<select class='chosen-select' id='Clients'>";
		$clientDataList = "<datalist id='Clients'>";
		while($client = sqlFetch($clientInfo)) {
			$clientLName = displaySingleQuote($client['lName']);
			$clientFName = displaySingleQuote($client['fName']);
			$clientDataList .= "<option value='" . $clientLName .
								($clientLName=="Available" ? "" : ", " . $clientFName) . "'></option>";
     // $clientDataList .= "<option value=" . $client['clientID'] . ">";
      //$clientDataList .= ($clientLName=="Available" ? "Available" : $clientLName . ", " . $clientFName);
		}
    //$clientDataList = "</select>";
		$clientDataList .= "</datalist>";

		// Close the connection as we've gotten all the information we should need
		closeDB($conn);
		$timeSlot = 0;
		// ***********************************************************************
		// Invoices in time slots
		//                 [TIME]
		// Name | Family Size | Phone Number | Status | Delete
		if (($visitInfo != NULL) AND ($visitInfo->num_rows > 0)) { ?>
			<table>
				<thead>
					<tr>
						<th>Client Name</th>
						<th>Family Size</th>
						<th>Phone Number</th>
						<th>Status</th>
					</tr>
				</thead>

			<?php
			// To generate a unique ID for each invoice in the list, use a counter
			$setID = 0;

			// Loop through all of the invoices for this date
			while($invoice = sqlFetch($visitInfo)) {
				$IIDTag = "InvoiceID" . $setID;
				echo "<input hidden id=" . $IIDTag . " value=" . $invoice['invoiceID'] . ">";

				// Set up the row and drop in appropriate information
				if ($timeSlot != $invoice['visitTime']) {
					$timeSlot = $invoice['visitTime'];
					echo "<tr><th colspan='4'>" . date('h:i a', strtotime($invoice['visitTime'])) . "</th></tr>";
				}
				echo "<tr><td>";

				// List appointments as Last Name, First Name if they are set
				$clientName = displaySingleQuote(($invoice['lName']=="Available" ? $invoice['lName']
							 : ($invoice['lName'] . ", " . $invoice['fName']) ));

				$clientIDTag = "Clients" . $setID;

				if ($invoice['status'] > GetActiveStatus()) {
					echo $clientName;
				}
				else {
					if ($clientName == "Available") {
						echo "<input type='text' list='Clients' id=" . $clientIDTag;
					}
					else {
						echo "<input type='text' list='Clients'  id=" . $clientIDTag . "
								value='" . $clientName . "'";
					}
					// AJAX Call
					echo " onchange='AJAX_SetAppointment(this)' >";

					// Dump out the client list we created on page load
					echo $clientDataList;
				}

				echo "</td>";

				// These details change with the AJAX call so we need custom tags to locate them
				$familySizeIDTag = "famSize" . $setID;
				echo "<td id='" . $familySizeIDTag . "'>" . $invoice['familySize'] . "</td>";

				$phoneNoIDTag = "phoneNo" . $setID;
				echo "<td id='" . $phoneNoIDTag . "'>" . displayPhoneNo($invoice['PhoneNo']) . "</td>";

				$statusIDTag = "status" . $setID;
				$status = visitStatusDecoder($invoice['status']);
				echo "<td id='" . $statusIDTag . "'>" . $status . "</td>";

				// --==[*DELETE*]==-- Button start
				echo "<form action='php/apptOps.php' method='post'>";
				// Add the hidden invoice ID for easy deletion
				echo "<input type='hidden' name='invoiceID' value=" . $invoice['invoiceID'] . ">";
				// Add the date to return properly
				echo "<input type='hidden' name='returnDate' value=" . $_GET['date'] . ">";


				if ($invoice['status'] < GetActiveStatus()) {
					// --==[*DELETE*]==-- Button
					echo "<td><button type='submit' id='deleteInvoice' class='btn-icon' name='DeleteInvoice' ";
					echo "onclick=\"javascript: return confirm('Are you sure you want to delete this time slot?');\")'>";
					echo "<i class='fa fa-trash'></i></button></td>";

					// --==[*Lock*]==-- Button
					echo "<td><button id='lock" . $setID . "' type='submit' class='btn_lock btn-icon'><i class='fa fa-lock'></i></button></td>";
				}
				echo "</form>";


				// close off the row
				echo "</tr>";

				// Increment our slot ID
				$setID++;
			}
			// Close off our table
			echo "</table>";

		}
		else {
			echo "Invoice date not found.<br><br>";
		}

		echo "<div id='ErrorLog'></div>";
		// --==[*NEW TIME SLOT*]==--
    if ($_SESSION['perms'] >= 99) {
      echo "<br><form action='ap_ao4.php' method='post'>";
      echo "<input type='hidden' name='date' value=" . $_GET['date'] . ">";	// Send the date we're adding to
      echo "<button id='newSlots' class='btn-nav' type='submit' name='newSlots' ><i class='fa fa-clock'></i> New Time Slots</button>";
      echo "</form>";
    }
	?>

<?php include 'php/footer.php'; ?>

<script src="js/apptOps.js"></script>
<script type="text/javascript">
  //$(".chosen-select").chosen();
  if (getCookie("newAppt") != "") {
		window.alert("New Date Added!");
		removeCookie("newAppt");
	}
	if (getCookie("newTimeSlots") != "") {
		window.alert("Time Slots Added!");
		removeCookie("newTimeSlots");
	}

	$('.btn_lock').on("click", function(e) {
		e.stopPropagation();
		e.preventDefault();
		AJAX_ActivateAppointment(this);
	});
</script>