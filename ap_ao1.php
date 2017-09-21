<?php include 'php/header.php'; ?>
<script src="js/clientOps.js"></script>
    <button id='btn_back' onclick="goBack()">Back</button>
    <h3>Appointment Operations</h3>

	<!-- TODO: Limit this by pages -->
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Get our available appointment counts stored off so we can grab them as needed
		$sql = "SELECT COUNT(*) as availCount, visitDate
				FROM Invoice
				WHERE status=" . GetAvailableStatus() . "
				GROUP BY visitDate
				ORDER BY visitDate DESC";
		$result = queryDB($conn, $sql);
		
		$AvailableDates = array();
		// Loop through our query results
		if ($result!=null && $result->num_rows > 0) {
			while($row = sqlFetch($result) ) {
				$availableDates[$row['visitDate']] = $row['availCount'];
			}
		}
		
		// Create and execute our query string
		$sql = "SELECT visitDate, COUNT(*) as numApp 
				FROM Invoice
				WHERE status!=" . GetRedistributionStatus() . "
				GROUP BY visitDate
				ORDER BY visitDate DESC";
		
		
		$result = queryDB($conn, $sql);
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
		
			// Create the table and add in the headers
			echo "<table><tr><th>Date</th><th># of Appointments</th><th># Available</th></tr>";
			
			while($row = sqlFetch($result) ) {
				// Start Table row
				echo "<tr>";
				
				// Dates as links to detail pages
				$date = $row["visitDate"];
				$invoiceLink = "/RUMCPantry/ap_ao3.php?date=" . $date;
				echo "<td><a href='" . $invoiceLink . "'>" . $date . "</a></td>";
				
				// Appointment Count
				echo "<td>" . $row['numApp'] . "</td>";
				
				// Available Count
				if (isset($availableDates[$row["visitDate"]])) {
					echo "<td>" . $availableDates[$row["visitDate"]] . "</td>";
				}
				else {
					echo "<td>0</td>";
				}
				
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