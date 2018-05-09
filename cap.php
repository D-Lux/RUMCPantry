<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 1;
  include 'php/checkLogin.php';
  include 'php/header.php';
?>

<!-- No back button for this page! -->


	<div class="body-content">

	<?php
	// Disabled for now, until Vicki wants clients to select their own appointment times
/*
		echo "<h3>Next Appointment</h3>";
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
				FROM invoice
				WHERE MONTH(visitDate)=" . $selectMonth . "
				AND YEAR(visitDate)=" . $selectYear . "
				AND clientID=" . $availID . "
				GROUP BY visitTime";
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			echo "ERROR";
		}

		$result = queryDB($conn, $sql);

		// Warning if there are no available appointments
		if ( $result==null || $result->num_rows <= 0 ) {
			echo "No appointments are currently available next month.";
		}
		else {
			// Start off our table
			echo "<table><tr><th>Date</th><th>Time Slot</th><th></th></tr>";
			while ($timeslots = sqlFetch($result)) {
				// Create a human-readable date string
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

		echo "<form action='php/apptOps.php' method='post'>
			<input type='submit' name='SkipApt' value='Skip for now'>
			</form>";
			*/
		?>
		<div style="text-align:center">
			<h1>Thank you for your order!</h1>
			<h3>Please return this device and set up your next appointment with the registration desk.</h3>
			<a href="<?=$basePath?>cp1.php" class="button">Finish</a>
		</div>
		<!-- <form action='cp1.php' method='post'>
      <input style='position:relative; left:30%;' class="btn-nav" type='submit' name='NoApptSelection' value='Finish'>
		</form> -->

<?php include 'php/footer.php'; ?>