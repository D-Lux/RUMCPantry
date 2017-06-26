<!doctype html>
<html>

<head>
    <title>Roselle United Methodist Church Food Pantry - Client Appointment Page</title>
	<script src="js/utilities.js"></script>
	<link href='css/toolTip.css' rel='stylesheet'>
	<?php include 'php/utilities.php'; ?>

</head>

<body>
    <button onclick="goBack()">Go Back</button>

    <h1>Client appointment page</h1>
	<?php 
	
		echo "<h4>Client: " . $_POST['clientName'] . "</h4>";	
		
		// NOTE To Developer:
		// The client should only get to this page right after they've completed an order form

		// Find our trusty 'available' client ID
		$availID = getAvailableClient();
		
		// Get our month and year for date selections
		$selectMonth = (date("m") == 12) ? 1 : date("m") + 1;
		$selectYear = (date("m") == 12) ? date("Y") + 1 : date("Y");
		
		// Find available appointments for this client to select from
		$sql = "SELECT visitTime, visitDate
				FROM Invoice
				WHERE MONTH(visitDate)=" . $selectMonth . "
				AND YEAR(visitDate)=" . $selectYear . "
				AND clientID=" . $availID . "
				GROUP BY visitTime";
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			echo "ERROR";
		}
		
		$result = queryDB($conn, $sql);
		
		// Warning if there are no available appointments
		if ( $result==null || $result->num_rows <= 0 ) {
			echo "No appointments are currently available next month";
			echo "<br>Please see administration for your next appointment";
		}
		else {
			// Start off our table
			echo "<table><tr><th>Date</th><th>Time Slot</th><th></th></tr>";
			while ($timeslots = sqlFetch($result)) {
				// Create a human-readale date string
				$dateString = date_format(date_create($timeslots['visitDate']), 'F jS\, Y');
				echo "<tr><td>" . $dateString . "</td>";

				echo "<td>" . $timeslots['visitTime'] . "</td>";
				
				// Select button
				echo "<form action='php/apptOps.php' method='post'>";
				echo "<input type='hidden' name='visitDate' value=" . $timeslots['visitDate'] . ">";
				echo "<input type='hidden' name='visitTime' value=" . $timeslots['visitTime'] . ">";
				echo "<input type='hidden' name='clientID' value=" . $_POST['clientID'] . ">";
				echo "<td><input type='submit' name='clientApptSelect' value='Select'></td></form>";
				
				// Close off the row
				echo "</tr>";
				
			}
			echo "</table>";
		}
		
		?>
		<!-- Button to return 'home' -->
		<br><br><br><br>
		<form action='cp1.php'>
		<input type='submit' name='Return' value='Return'>
		</form>
		

</body>

</html>