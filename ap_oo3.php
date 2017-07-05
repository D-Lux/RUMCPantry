<!doctype html>
<html>

<head>
	<meta http-equiv="refresh" content="15" >
    <script src="js/utilities.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
    <title>View Active Orders</title>
	
</head>

<body>
	<script>
		if (getCookie("processError") != "") {
			window.alert("There was an error setting this order to processed.");
			removeCookie("processError");
		}
	</script>
    <button onclick="goBack()">Go Back</button>
	<br><br>
	
	<?php 
	// Query the database for all active orders
	// Create list of all orders, selectable to view that order
	
	$sql = "SELECT FMC_Join.lName as ln, invoiceID, visitTime, status, FMC_Join.FSize as FamilySize
			FROM Invoice
			JOIN (SELECT FamilyMember.lastName as lName, FamilyMember.clientID as JoinID, 
							(Client.numOfKids + Client.numOfAdults) as FSize
				  FROM FamilyMember
				  JOIN Client
				  ON Client.clientID=FamilyMember.clientID
				  AND isHeadOfHousehold=TRUE) as FMC_Join
			ON FMC_Join.JoinID=Invoice.clientID
			AND status>=" . GetActiveStatus() . "
			AND status<=" . HighestActiveStatus() . "
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
			echo "<input type='hidden' value='" . displaySingleQuote($invoice['ln']) . "' name='name'>";
			echo "<input type='hidden' value='" . $invoice['visitTime'] . "' name='visitTime'>";
			echo "<input type='hidden' value='" . $invoice['status'] . "' name='status'>";
			echo "<input type='hidden' value='" . $invoice['FamilySize'] . "' name='familySize'>";
			echo "<input type='submit' value='";
			echo displaySingleQuote($invoice['ln']) . " ";
			echo returnTime($invoice['visitTime']) . " ";
			echo visitStatusDecoder($invoice['status']);
			echo "' name='viewInvoice'>";
			echo "</form><br>";
		}
	}

	
	?>
	

</body>

</html>