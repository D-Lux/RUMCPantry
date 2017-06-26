<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<link href='css/toolTip.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>
		
		<?php 
			$hiddenFields = "<input type='hidden' value='" . $_POST['clientName'] . "' name='clientName'>";
			$hiddenFields .= "<input type='hidden' value='" . $_POST['clientID'] . "' name='clientID'>";
		?>

	</head>
	<body>
		<button onclick="goBack()">Back</button>
		
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Client Main Page</h3>
		<?php echo "<h4>Client: " . $_POST['clientName'] . "</h4>";	?>
		
		<form method="post" action="cof.php">
			<button type="submit">Create an Order</button>
			<?php echo $hiddenFields; ?>
		</form>
		<form method="post" action="ciup.php">
			<button type="submit">Update Information</button>
			<?php echo $hiddenFields; ?>
		</form>
		<form method="post" action="cap.php">
			<button type="submit">Make an Appointment</button>
			<?php echo $hiddenFields; ?>
		</form>
		
		<?php 
	/*
	// Grab invoice information
		$sql = "SELECT invoiceID, visitDate, status, clientID
				FROM Invoice
				WHERE clientID=" . $_POST['clientID'];
		$invoiceInfo = queryDB($conn, $sql);
		
		
		// ***********************************************************************
			// SHOW VISITS
			// Show all of the visits (actionable to view further information)
			// Add a button to add an appointment
			
			echo "<br><br><br><h3>Visits</h3>";
			if ($invoiceInfo!=null && $invoiceInfo->num_rows > 0) {
		
				// Create the invoice table and add in the headers
				echo "<table> <tr> <th>Invoice Date</th><th>Status</th></tr>";
				
				while($row = sqlFetch($invoiceInfo)) {						
					
					// Start Table row
					echo "<tr>";

					// Date, as a link to a view invoice page
					$invoiceLink = "/RUMCPantry/ap_ao4.php?id=" . $row["invoiceID"];;
					$date = $row["visitDate"];
					echo "<td><a href='" . $invoiceLink . "'>" . $date . "</a></td>";
					
					$status = visitStatusDecoder($row['status']);
					echo "<td>" . $status . "</td>";
					
					// Close off the row and form
					echo "</tr></form>";
				}
				echo "</table>";
			} 
			else {
				echo "No Visits in Database.";
			}
		*/
		?>
		
	</body>
</html>