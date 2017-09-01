<?php include 'php/header.php'; ?>
		<button id='btn_back' onclick="goBack()">Back</button>
		<?php echo "<h3>Client: " . $_POST['clientName'] . "</h3>";	?>
		<div class="body_content">
			<?php
				// show invoices
				// If a visit is status active (pulled from utilities) then make selectable (to cof.php)
				$sql = "SELECT status, invoiceID, visitDate, visitTime
						FROM Invoice
						WHERE clientID=" . $_POST['clientID'];
		
				//Run this query so we know what to grab from the item database
				$conn = createPantryDatabaseConnection();
				if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
				
				$invoiceQuery = queryDB($conn, $sql);
				if ($invoiceQuery!=null && $invoiceQuery->num_rows > 0) {
			
					// Create the invoice table and add in the headers
					echo "<table> <tr> <th>Date</th><th>Time</th></tr>";
					
					while($row = sqlFetch($invoiceQuery)) {
						// Start Table row
						echo "<tr>";
						echo "<td>" . date('F n, Y', strtotime($row['visitDate'])) . "</td>";
						echo "<td>" . date('g:i A' , strtotime($row['visitTime'])) . "</td>";
						
						if (IsActiveAppointment($row['status'])) {
							echo "<form method='post' action='cof.php'>";
							echo "<input type='hidden' value='" . $_POST['clientName'] . "' name='clientName'>";
							echo "<input type='hidden' name='clientID' value=" . $_POST['clientID'] . ">";
							echo "<input type='hidden' name='invoiceID' value=" . $row['invoiceID'] . ">";
							echo "<td><input type='submit' name='createOrder' value='Create Order'></td>";
							echo "</form>";
						}
						
						// Close off the row and form
						echo "</tr>";
						
						
					}
					echo "</table>";
				} 
				else {
					echo "No Appointments in Database.";
				}
			?>
		</div><!-- /body_content -->
	</div><!-- /content -->		
	</body>
</html>