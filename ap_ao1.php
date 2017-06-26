<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/clientOps.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />

	<title>ap_co1</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        First admin page for Appointment Operations
    </h1>

	<!-- TODO: Limit this by pages -->
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// TODO: show available appointments and total appointments
		// Create and execute our query string
		$sql = "SELECT visitDate, COUNT(*) as aCount 
				FROM Invoice
				GROUP BY visitDate
				ORDER BY visitDate DESC";
		$result = queryDB($conn, $sql);
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
		
			// Create the table and add in the headers
			echo "<table><tr><th>Date</th><th># of Appointments</th></tr>";
			
			while($row = sqlFetch($result) ) {
				// Start Table row
				echo "<tr>";
				
				// Dates as links to detail pages
				$date = $row["visitDate"];
				$invoiceLink = "/RUMCPantry/ap_ao3.php?date=" . $date;
				echo "<td><a href='" . $invoiceLink . "'>" . $date . "</a></td>";
				
				// Appointment Count
				echo "<td>" . $row['aCount'] . "</td>";
				
				// Close off the row 
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No Appointment Dates Exist.";
		}

		$conn->close();
	?>
	<br><br><br>
	
	<!-- NEW Date -->
	<form action="ap_ao2.php">
		<input id="NewDate" type="submit" name="NewDate" value="New Appointment Date">
    </form>
	
</body>
</html>