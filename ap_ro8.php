<?php include 'php/header.php'; ?>
	<button id='btn_back' onclick="goBack()">Back</button>
	
    <h3>Reallocations</h3>

	<script>
		if (getCookie("newRedistribution") != "") {
			window.alert("New Reallocation added!");
			removeCookie("newRedistribution");
		}
		if (getCookie("redistributionDeleted") != "") {
			window.alert("Reallocation removed!");
			removeCookie("redistributionDeleted");
		}
	</script>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Create our query string (Triple check we're getting only redistribution invoices)
		$sql = "SELECT Invoice.invoiceID as ID, C.name as name, C.city as city, visitDate
				FROM Invoice
				JOIN (SELECT Client.clientID as ID, city, FamilyMember.lastName as name
					  FROM Client
					  JOIN FamilyMember
					  ON Client.clientID=FamilyMember.clientID
					  WHERE Client.redistribution=1 
					  AND FamilyMember.FirstName='REDISTRIBUTION'
					  ) as C
				ON C.ID=Invoice.clientID
				WHERE Invoice.status=" . GetRedistributionStatus();
		$invoices = queryDB($conn, $sql);
		
		// loop through the query results
		if ($invoices!=null && $invoices->num_rows > 0) {
			echo "<table> <tr> <th></th>";
			echo "<th>Partner</th><th>City</th><th>Date</th><th></th></tr>";
			
			// (view button) | (hidden) InvoiceID | name | city | visitDate | (delete button)
			while($row = sqlFetch($invoices)) {
				// Start Table row
				echo "<tr>";
				
				// Start the form for this row (so buttons act correctly based on client)
				echo "<form action='php/redistOps.php' method='post'>";
				
				// pass along hidden data
				echo "<input type='hidden' name='id' value=" . $row["ID"] . ">";

				// View button				
				echo "<td><input type='submit' name='viewRedistribution' value='View'></td>";
				
				// Information Fields
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . $row['city'] . "</td>";
				echo "<td>" . $row['visitDate'] . "</td>";
				
				// Delete Button
				?>
				<td><input type="submit" class="btn_trash" name="deleteRedistInvoice" value=" "
				onclick="return confirm('Are you sure you want to delete this Invoice?')"></td>
				<?php
				// Close off the row and form
				echo "</form></tr>";
			}
			// Close off the table
			echo "</table><br>";
		} 
		else {
			echo "No Reallocation invoices in database.";
		}
		
		$conn->close();
	?>
	
	<!-- NEW Redistribution Invoice -->
	<form action="php/redistOps.php" method="post" >
		<input type="submit" name="newRedistInvoice" value="New Reallocation">
    </form>
	

	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>
</html>