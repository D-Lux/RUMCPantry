<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/apptOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
	<?php include 'php/checkLogin.php';?>

    <title>View Appointment Date</title>
	

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>View Appointment Date: <?php echo $_GET['date']; ?></h1>
	
	<script>
		if (getCookie("newAppt") != "") {
			window.alert("New Date Added!");
			removeCookie("newAppt");
		}
		if (getCookie("newTimeSlots") != "") {
			window.alert("Time Slots Added!");
			removeCookie("newTimeSlots");
		}
	</script>
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database
		
		// Grab all of the information we need from Client, FamilyMember and Invoice
		$sql = "SELECT visitTime, status, invoiceID,
					fam.firstName AS fName, fam.lastName AS lName, ( fam.numOfAdults + fam.numOfKids) AS familySize,
					fam.phoneNumber as PhoneNo
				FROM Invoice
				JOIN (
					SELECT FamilyMember.clientID, FamilyMember.firstName, FamilyMember.lastName,
							Client.numOfAdults, Client.numOfKids, Client.phoneNumber 
					FROM FamilyMember
					JOIN Client
					ON FamilyMember.clientID = Client.clientID 
					WHERE FamilyMember.isHeadOfHousehold=1 ) AS fam
				ON fam.clientID=Invoice.clientID
				WHERE visitDate='" . $_GET['date'] . "'
				ORDER BY visitTime, invoiceID";

		$visitInfo = queryDB($conn, $sql);
		
		// *******************************************
		// ** Generate the datalist for client drop down
		
		$sql = "SELECT firstName AS fName, lastName AS lName, clientID
				FROM FamilyMember
				WHERE FamilyMember.isHeadOfHousehold=1
				AND isDeleted=0";
				
		$clientInfo = queryDB($conn, $sql);

		if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
			echo "Error, bad SQL Query";
		}
		
		$clientDataList = "<datalist id='Clients'>";
		while($client = sqlFetch($clientInfo)) {
			$clientDataList .= "<option value='" . $client['lName'] .
								($client['lName']=="Available" ? "" : ", " . $client['fName']) . "'><option/>";
		}
		$clientDataList .= "</datalist>";
		
		// Close the connection as we've gotten all the information we should need
		closeDB($conn);
		$timeSlot = 0;
		// ***********************************************************************
		// Invoices in time slots
		//                 [TIME]
		// Name | Family Size | Phone Number | Status | Delete
		if (($visitInfo != NULL) AND ($visitInfo->num_rows > 0)) {
			echo "<table><tr><th>Client Name</th>
					<th>Family Size</th><th>Phone Number</th><th>Status</th></tr>";
			
			// To generate a unique ID for each invoice in the list, use a counter
			$setID = 0;
			
			// Loop through all of the invoices for this date
			while($invoice = sqlFetch($visitInfo)) {
				$IIDTag = "InvoiceID" . $setID;
				echo "<input hidden id=" . $IIDTag . " value=" . $invoice['invoiceID'] . ">";
				
				// Set up the row and drop in appropriate information
				if ($timeSlot != $invoice['visitTime']) {
					$timeSlot = $invoice['visitTime'];
					echo "<tr><th colspan='4'>" . $invoice['visitTime'] . "</th></tr>";
				}
				echo "<tr><td>";
				
				// List appointments as Last Name, First Name if they are set
				$clientName = ($invoice['lName']=="Available" ? $invoice['lName'] 
							 : ($invoice['lName'] . ", " . $invoice['fName']) );

				$clientIDTag = "Clients" . $setID;
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
				echo "</td>";
			
				// These details change with the AJAX call so we need custom tags to locate them
				$familySizeIDTag = "famSize" . $setID;
				echo "<td id='" . $familySizeIDTag . "'>" . $invoice['familySize'] . "</td>";
				
				$phoneNoIDTag = "phoneNo" . $setID;
				echo "<td id='" . $phoneNoIDTag . "'>" . $invoice['PhoneNo'] . "</td>";
				
				$statusIDTag = "status" . $setID;
				$status = visitStatusDecoder($invoice['status']);
				echo "<td id='" . $statusIDTag . "'>" . $status . "</td>";

				// --==[*DELETE*]==-- Button start
				echo "<form action='php/apptOps.php' method='post'>";
				
				// Add the hidden invoice ID for easy deletion
				echo "<input type='hidden' name='invoiceID' value=" . $invoice['invoiceID'] . ">";
				// Add the date to return properly
				echo "<input type='hidden' name='returnDate' value=" . $_GET['date'] . ">";
				
				echo "<td><input id='deleteInvoice' value=' ' class='btn_trash' name='DeleteInvoice' type='submit' ";
				echo "onclick=\"javascript: return confirm('Are you sure you want to delete this time slot?');\")'></td>";
				
				echo "</form>";
				// --==[*DELETE*]==-- Button end
				
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
		
		// --==[*NEW TIME SLOT*]==--
		echo "<br><form action='ap_ao4.php' method='post'>";
		echo "<input type='hidden' name='date' value=" . $_GET['date'] . ">";	// Send the date we're adding to
		echo "<input id='newSlots' type='submit' name='newSlots' value='New Time Slots'>";	
		echo "</form>";
	?>
	

</body>

</html>