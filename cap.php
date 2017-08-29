<?php include 'php/utilities.php'; ?>

</head>

<body>
    <button id='btn_back' onclick="goBack()">Back</button>

    <h3>Client appointment page</h3>
	
	<div class="body_content">
	
	<?php 

		// NOTE To Developer:
		// The client should only get to this page right after they've completed an order form

		// TODO: Don't offer an appointment if they already have one next month
		
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
				echo "<input type='hidden' name='clientID' value=" . $_GET['clientID'] . ">";
				echo "<td><input type='submit' name='clientApptSelect' value='Select'></td></form>";
				
				// Close off the row
				echo "</tr>";
				
			}
			echo "</table>";
		}
		
		?>
		<form action='cp1.php'>
			<input type='submit' name='Return' value='Return'>
		</form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	

</body>

</html>