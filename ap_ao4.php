<html>
<head>
	 <script src="js/utilities.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>


</head>
<body>
	<button onclick="goBack()">Go Back</button>
	<!-- Start the form for creating a new date -->
	<form id="AppointmentForm" name="AppointmentForm" 
			action="php/apptOps.php" method="post" >
		<!-- Get the Date for this set of invoices -->
		<?php
			if ( (isset($_POST['newSlots'])) && (isset($_POST['date'])) ) {
				// Our header Date box
				echo "<h2>Date: " . $_POST['date'] . "</h2><br>";
			
				// Our short table with one time slot and one quantity
				echo "<table id='timeTable' name='timeTable' >";
				echo "<tr><th>Time</th><th>Count</th></tr>";
				
				echo "<tr><td><input type='time' name='time' value='10:00' step='900'></td>";
				echo "<td><input type='number' name='qty' value='1' min='1'></td></tr>";
			}
			else {
				echo "Error";
			}

			echo "</table>";
			echo "<input type='hidden' name='appDate' value=" . $_POST['date'] . ">";
		?>
		<br><br>
		<input type="submit" name="CreateInvoiceTimeSlot" value="Create Time Slots">
	</form>
</body>

</html>