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
		
		<?php
			// show invoices
			// If a visit is status 200 then make selectable (to cof.php)
			$sql = "SELECT status, invoiceID, visitDate, visitTime
					FROM Invoice
					WHERE clientID=" . $_POST['clientID'];
	
			//Run this query so we know what to grab from the item database
			$conn = createPantryDatabaseConnection();
			if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
			
			$invoiceQuery = queryDB($conn, $sql);
			if ($invoiceQuery!=null && $invoiceQuery->num_rows > 0) {
		
				// Create the invoice table and add in the headers
				echo "<table> <tr> <th>Date</th><th>Time</th><th></th></tr>";
				
				while($row = sqlFetch($invoiceQuery)) {
					// Start Table row
					if ($row['status'] == 200) {
						echo "<form method='post' action='cof.php'>";
						echo "<input type='hidden' name='clientID' value=" . $_POST['clientID'] . ">";
						echo "<input type='hidden' name='invoiceID' value=" . $row['invoiceID'] . ">";
					}
					
					echo "<tr>";
					echo "<td>" . $row['visitDate'] . "</td><td>" . $row['visitTime'] . "</td>";
					
					// Close off the row and form
					echo "</tr>";
					
					if ($row['status'] == 200) {
						echo "<input type='submit' name='selectApptDate' value='Select Appointment'>";
						echo "</form>";
					}
					
					
				}
				echo "</table>";
			} 
			else {
				//echo "No Visits in Database.";
			}
		?>
		
		<form method="post" action="cof.php">
			<button type="submit">Create an Order</button>
			<?php echo $hiddenFields; ?>
		</form>
		<!--
		<form method="post" action="ciup.php">
			<button type="submit">Update Information</button>
			<?php //echo $hiddenFields; ?>
		</form>
		-->
		<form method="post" action="cap.php">
			<button type="submit">Make an Appointment</button>
			<?php echo $hiddenFields; ?>
		</form>
		
	</body>
</html>