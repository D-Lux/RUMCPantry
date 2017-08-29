<?php include 'php/utilities.php'; ?>
    <meta http-equiv="refresh" content="15" >
	<script>
		if (getCookie("processError") != "") {
			window.alert("There was an error setting this order to processed.");
			removeCookie("processError");
		}
	</script>
    <button id='btn_back' onclick="goBack()">Back</button>
	<h3>View Active Orders</h3>
	
	<div class="body_content">
	<?php 
		// Query the database for all active orders
		// Create list of all orders, selectable to view that order
		
		$sql = "SELECT invoiceID, visitTime, status, Invoice.clientID as CID,
						FMC_Join.lName as ln, FMC_Join.fName as fn, FMC_Join.FSize as FamilySize
				FROM Invoice
				JOIN (SELECT FamilyMember.lastName as lName, FamilyMember.firstName as fName, FamilyMember.clientID as JoinID, 
								(Client.numOfKids + Client.numOfAdults) as FSize
					  FROM FamilyMember
					  JOIN Client
					  ON Client.clientID=FamilyMember.clientID
					  AND isHeadOfHousehold=TRUE) as FMC_Join
				ON FMC_Join.JoinID=Invoice.clientID
				AND status>=" . GetActiveStatus() . "
				AND status<=" . GetHighestActiveStatus() . "
				ORDER BY visitTime, status ASC";
		
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$invoiceData = queryDB($conn, $sql);
		
		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "No appointments are currently active.";	
		}
		else {
			// Loop through our data and create a list of selectable active appointments
			while( $invoice = sqlFetch($invoiceData) ) {
				//echo "ID: " . $invoice['invoiceID'] . "<br>";
				//echo "Last Name: " . displaySingleQuote($invoice['ln']) . "<br>";
				//echo "Visit Time: " .  $invoice['visitTime'] . "<br>";
				echo "<form method='post' action='ap_oo4.php'>";
				echo "<input type='hidden' value='" . $invoice['invoiceID'] . "' name='invoiceID'>";
				echo "<input type='hidden' value='" . $invoice['CID'] . "' name='CID'>";
				echo "<input type='hidden' value='" . displaySingleQuote($invoice['ln']) . "' name='name'>";
				echo "<input type='hidden' value='" . $invoice['visitTime'] . "' name='visitTime'>";
				echo "<input type='hidden' value='" . $invoice['status'] . "' name='status'>";
				echo "<input type='hidden' value='" . $invoice['FamilySize'] . "' name='familySize'>";
				echo "<input type='submit' value='";
				// Display order information (firstname lastname visittime decodedstats)
				echo displaySingleQuote($invoice['fn']) . " ";
				echo displaySingleQuote($invoice['ln']) . " ";
				echo returnTime($invoice['visitTime']) . " ";
				echo visitStatusDecoder($invoice['status']);
				echo "' name='viewInvoice'>";
				echo "</form><br>";
				
				// Display order information for review ("Review: " firstname lastname visittime decodedstats)
				echo "<form method='post' action='rof.php'>";
				echo "<input type='hidden' value='" . $invoice['invoiceID'] . "' name='invoiceID'>";
				echo "<input type='hidden' value='" . $invoice['CID'] . "' name='clientID'>";
				echo "<input type='hidden' value='" . displaySingleQuote($invoice['ln']) . "' name='lname'>";
				echo "<input type='hidden' value='" . displaySingleQuote($invoice['fn']) . "' name='fname'>";
				echo "<input type='hidden' value='" . $invoice['FamilySize'] . "' name='familySize'>";
				echo "<input type='submit' value='Review: ";
				echo displaySingleQuote($invoice['fn']) . " ";
				echo displaySingleQuote($invoice['ln']) . " ";
				echo returnTime($invoice['visitTime']) . " ";
				echo visitStatusDecoder($invoice['status']);
				echo "' name='viewInvoice'>";
				echo "</form><br>";
			}
		}
	?>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
	

</body>

</html>