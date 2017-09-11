<?php include 'php/header.php';?>
<script src="js/reportingOps.js"></script>

	<button id='btn_back' onclick="goBack()">Back</button>
	<h3>Reporting</h3>
	
	<?php
		echo "<div class='body_content'>";
		echo "<div class='inputDiv'>";
		
		$startDate = date('Y-m-d', strtotime('first day of last month'));
		$endDate = date('Y-m-d', strtotime('last day of last month'));
		
		// Start and end date boxes for AJAX calls
		echo "<label for='startDate'>Start Date:</label>
				<input id='startDate' type='Date' value='" . $startDate . "' onchange='AJAX_UpdateReport()'><br>";
		echo "<label for='endDate'>End Date:</label>
				<input id='endDate' type='date' value='" . $endDate . "' onchange='AJAX_UpdateReport()'><br>";
		
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Queries:
		// # of families impacted
		// # of people impacted
		// # of children
		// lbs of food donated
		// $ of food dontaed
		
		// Perform queries using above dates!
		$sql = "SELECT SUM(numOfKids) as totalKids, (SUM(numOfAdults) + SUM(numOfKids)) as totalAffected
				FROM Invoice
				JOIN Client
				ON Invoice.clientID = Client.clientID
				WHERE Invoice.visitDate > '" . $startDate . "'
				AND Invoice.visitDate < '" . $endDate . "'";
		
		// returnProcessedLow()
		// returnProcessedHigh()
		
		$result = queryDB($conn, $sql);
		if ($result!=null && $result->num_rows > 0) {
			$queryData = sqlFetch($result);
			echo "<div id='reportData'>";
			echo "Num kids: " . $queryData['totalKids'] . "<br>";
			echo "Num people: " . $queryData['totalAffected'] . "<br>";
			echo "</div>";
		}
		
		echo "</div>"; // </inputDiv>
		echo "</div></div>"; // </body_content> </content>
	?>
	</body>
</html>