<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>


	<style>
		#addTimeSlot {
			float: left;
			height: 33px;
			width: 140px;
			font-size: 1.1rem;
			border-radius: 25px;
			border: solid 2px #AAA;
			margin-right: 30px;
		}
		#addTimeSlot:active {
			border: solid 2px #888;
			background-color: #AAA;
		}
		.rm_row {
			color: black !important;
		}
	
	</style>
	
	<h3>Create Appointment Date</h3>
	<div class="body_content">
		
		<div >
			<!-- Button to add a new time slot -->
			<input type="button" id="addTimeSlot" value="New Time Slot" onclick="addTimeSlot()">
		</div>
		
		<!-- Start the form for creating a new date -->
		<form id="AppointmentForm" name="AppointmentForm" 
				action="php/apptOps.php" method="post" onSubmit="return validateNewAppts()">
			<!-- Get the Date for this set of invoices -->
			<?php
				// Set up server connection
				$conn = connectDB();
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
					
				// Set the date to this coming saturday or the next saturday after the last appointment
				$defaultDate = date('Y-m-d', strtotime("next Saturday"));
				
				//Set the date to a week after the newest appointment time
				if ($result!=null && $result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$checkDate = date('Y-m-d', strtotime($row['visitDate'] . " next saturday"));
					if ($defaultDate <= $checkDate) {
						$defaultDate = $checkDate;
					}
				}
				
				// Date box
				echo "<h4>Date: <input type='date' name='appDate' id='appDate' value=$defaultDate></h4><br>";
			?>
			
			<!-- Show the appointment times and number for each time slot -->
			<table id="timeTable" name="timeTable" >
				<!-- Set up headers -->
				<tr><th>Time</th><th>Count</th><th></th></tr>
				<!-- Create time slots with 6 clients each, 30 minutes apart, starting at 9:30 -->
				<?php
					$timeSlot = date('H:i', strtotime('9:30'));	// Start time
					$endTime = date('H:i', strtotime('11:00'));	// End time
					$numSlots = 5;	// Defualt number of slots per time slot

					while ( $timeSlot <= $endTime ) {
						echo "<tr><td><input type='time' name='time[]' value='"
								. $timeSlot . "' step='900'></td>";
						echo "<td><input type='number' name='qty[]' value='" . $numSlots . "' min='1'></td>";
						echo "<td><a class='rm_row btn_icon' href='#' ><i class='fa fa-trash'></i></a></td>";
						echo "</tr>";
					
						$timeSlot =  date('H:i', strtotime($timeSlot) + (60*30));
					}
				?>
			</table>
			
			<br><br>
			<input type="submit" name="CreateInvoiceDate" value="Create Appointments">
		</form>
		
<?php include 'php/footer.php'; ?>
<script src="js/apptOps.js"></script>
<script type="text/javascript">
  // Bad date validation (after returning from apptOps.php)
  if (getCookie("badAppDate") != "") {
    window.alert("Date already exists in database");
    removeCookie("badAppDate");
  }
    
	$('.rm_row').on("click", function(e) {
		e.stopPropagation();
		e.preventDefault();
		deleteTimeTableRow(this);
	});
</script>