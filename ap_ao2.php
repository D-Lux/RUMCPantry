<html>
<head>
    <script src="js/utilities.js"></script>
	<script src="js/apptOps.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>

    
	<style>
		.newBtn {
			float: left;
			padding: 1em;
		}

	</style>
</head>
<body>
	<button onclick="goBack()">Go Back</button>
	<script>
		// Bad date validation (after returning from apptOps.php)
		if (getCookie("badAppDate") != "") {
			window.alert("Date already exists in database");
			removeCookie("badAppDate");
		}
	</script>
	
	<div class="newBtn">
		<!-- Button to add a new time slot -->
		<input type="button" id="addTimeSlot" value="New Time Slot" onclick="addTimeSlot()">
	</div>
	
	<!-- Start the form for creating a new date -->
	<form id="AppointmentForm" name="AppointmentForm" 
			action="php/apptOps.php" method="post" onSubmit="return validateNewAppts()">
		<!-- Get the Date for this set of invoices -->
		<?php 
			// Set up server connection
			$conn = createPantryDatabaseConnection();
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
				
			// Get the last appointment date in the database
			$sql = "SELECT visitDate
					FROM Invoice
					ORDER BY visitDate DESC
					LIMIT 1";
			$result = queryDB($conn, $sql);
			// Close the database connection
			closeDB($conn);
			
			//$defaultDate = date('Y-m-d',  strtotime('next saturday'));	// Starting at next saturday
			$defaultDate = date('Y-m-d', time());
			
			//Set the date to a week after the newest appointment time
			if ($result!=null && $result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo $row['visitDate'];
				$defaultDate = date('Y-m-d', strtotime($row['visitDate'] . " + 1 week"));
			}
			
			// Our header and Date box
			echo "<h2>Date: <input type='date' name='appDate' id='appDate' value=$defaultDate></h2><br>";
		?>
		
		<!-- Show the appointment times and number for each time slot -->
		<table id="timeTable" name="timeTable" >
			<!-- Set up headers -->
			<tr><th>Time</th><th>Count</th><th></th></tr>
			<!-- Create time slots with 6 clients each, 30 minutes apart, starting at 9:30 -->
			<?php
				$timeSlot = date('H:i', strtotime('9:30'));	// Start time
				$endTime = date('H:i', strtotime('11:00'));	// End time

				while ( $timeSlot <= $endTime ) {
					echo "<tr><td><input type='time' name='time[]' value='"
							. $timeSlot . "' step='900'></td>";
					echo "<td><input type='number' name='qty[]' value='6' min='1'></td>";
					echo "<td><input class='btn_trash' type='button' value=' ' onclick='deleteTimeTableRow(this)'></td>";
					echo "</tr>";
				
					$timeSlot =  date('H:i', strtotime($timeSlot) + (60*30));
				}
			?>
		</table>
		
		<br><br>
		<input type="submit" name="CreateInvoiceDate" value="Create Appointments">
	</form>
</body>

</html>