<?php
  $pageRestriction = 10;
  include 'php/header.php'; 
  include 'php/backButton.php'; 
?>
    <meta http-equiv="refresh" content="15" >
   
	<h3>View Active Orders</h3>
	
	<div class="body-content">
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
				AND status<=" . GetPrintedHigh() . "
				ORDER BY visitTime, status ASC";
		
		$conn = connectDB();
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
				// Display options to be printed
				if (IsReadyToPrint($invoice['status'])) {
					echo "<form method='get' action='ap_oo4.php'>";
					echo "<input type='hidden' value='" . $invoice['invoiceID'] . "' name='invoiceID'>";
					echo "<input type='submit' class='btn-nav' value='View: ";
					echo displaySingleQuote($invoice['ln']) . " ";
					echo returnTime($invoice['visitTime']) . " ";
					echo "' name='viewInvoice'>";
					echo "</form><br>";
				}
				
				// Display order information for review ("Review: " firstname lastname visittime decodedstats)
				if (IsReadyToReview($invoice['status'])) {
					echo "<form method='get' action='rof.php'>";
					echo "<input type='hidden' value='" . $invoice['invoiceID'] . "' name='invoiceID'>";
					echo "<input type='submit' class='btn-nav' value='Review: ";
					echo displaySingleQuote($invoice['ln']) . " ";
					echo returnTime($invoice['visitTime']) . " ";
					echo "' name='viewInvoice'>";
					echo "</form><br>";
				}
			}
		}
	?>
	
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  if (getCookie("processError") != "") {
    window.alert("There was an error setting this order to processed.");
    removeCookie("processError");
  }
</script>