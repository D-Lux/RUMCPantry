<?php include 'php/header.php'; ?>
<link rel="stylesheet" type="text/css" href="css/tabs.css" />
<script src="js/clientOps.js"></script>
    <button id='btn_back' onclick="goBack()">Back</button>
    <h3>Appointment Operations</h3><br>
	
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
		$tabStarted = FALSE;
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
		
			$TabSize = (isset($_POST['tabSize']) ? $_POST['tabSize'] : 10);

			$tabCounter = 0;
			$dateCounter = $TabSize;
			
			echo "<div class='tab'>";
			for ($i=0; $i< ceil($result->num_rows/$TabSize); $i++) {
				echo "<button class='tablinks' onclick='viewTab(event, dateList" . $i . ")'";
				echo ($i > 0 ? "" : "id='defaultOpen' ") . ">" . ($i + 1) . "</button>";
			}
			echo "</div>";
			
			while($row = sqlFetch($result) ) {
				if ($dateCounter >= $TabSize) {
					$dateCounter = 0;
					$tabStarted = TRUE;
					// Create the table and add in the headers
					echo "<table id='dateList" . $tabCounter . "' class='tabcontent'>";
					echo "<tr><th>Date</th><th># of Appointments</th><th># Available</th></tr>";						
					$tabCounter++;
				}
				$dateCounter++;
				
				// Start Table row
				echo "<tr>";
				
				// Dates as links to detail pages
				$date = date("F jS, Y", strtotime($row["visitDate"])); // Month (full spelling) day (+suffix), YYYY
				$invoiceLink = "/RUMCPantry/ap_ao3.php?date=" . $row['visitDate'];
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
				
				// Close off the tab if we've hit our peak
				if ( ( $dateCounter >= $TabSize ) ) {
					$tabStarted = FALSE;
					echo "</table>";
				}
			}
			if ( ( $tabStarted ) ) {
				echo "</table>";
			}
		} 
		else {
			echo "No Appointment Dates Exist.";
		}

		$conn->close();
	
		echo "<br>";
		// Allow the user to adjust the tab size
		echo "<form id='tabForm' action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
		echo "Tab Size: ";
		echo "<select name='tabSize' onchange='this.form.submit()' >";
		for ($i=1; $i <= 100; $i+=($i<5 ? 1 : ($i<50 ? 5 : 10))) {
			echo "<option " . ($_POST['tabSize']!==NULL ? ($_POST['tabSize']==$i ? "selected" : "") 
							: ($i == $TabSize) ? "selected" : "") . " value='" . $i . "'>" . $i . "</option>";
		}
		echo "</select>";
		echo "</form>";	
	?>
	
	<br><br>
	<!-- NEW Date -->
	<form action="ap_ao2.php">
		<input id="NewDate" type="submit" name="NewDate" value="New Appointment Date">
    </form>
	
	<script>
		// Open the default tab (if tabs exist)
		document.getElementById("defaultOpen").click();
	</script>
</body>
</html>