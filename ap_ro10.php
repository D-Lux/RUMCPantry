<?php include 'php/utilities.php'; ?>

	<button id='btn_back' onclick="goBack()">Back</button>
    <h3>View Redistribution</h3>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Create our queries
		$invoiceSql = "SELECT itemName, quantity, totalItemsPrice
						FROM InvoiceDescription
						JOIN Item
						ON Item.itemID=InvoiceDescription.itemId
						WHERE InvoiceDescription.invoiceID=" . $_GET['id'];
		$invoiceQuery = queryDB($conn, $invoiceSql);
		if ($invoiceQuery === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to locate invoice descriptions.", "red" );	
		}
	
		$nameSql = "SELECT pi.lastName as Name, pi.city as city, visitDate
					FROM Invoice
					JOIN (SELECT Client.clientID as ID, lastName, city
						  FROM Client
						  JOIN FamilyMember
						  ON FamilyMember.clientID=Client.clientID) as pi
					ON pi.ID=Invoice.clientID
					WHERE Invoice.invoiceID=" . $_GET['id'];
		$partnerQuery = queryDB($conn, $nameSql);
		
		if ($partnerQuery === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to get partner data.", "red" );	
		}
		$conn->close();
		
		
		if ($partnerQuery != null && $partnerQuery->num_rows > 0) {
			$partnerData = sqlFetch($partnerQuery);
			echo "Partner Name: " . $partnerData['Name'] . "<br>";
			echo "Partner City: " . $partnerData['city'] . "<br>";
			echo "Date: " . $partnerData['visitDate'] . "<br>";
		}
		echo "<br><br>";

		// loop through the query results
		if ($invoiceQuery!=null && $invoiceQuery->num_rows > 0) {
			echo "<table> <tr>";
			echo "<th>Item</th><th>Quantity</th><th>Price</th></tr>";
			
			while($row = sqlFetch($invoiceQuery)) {
				// Start Table row
				echo "<tr>";

				// Information Fields
				echo "<td>" . $row['itemName'] . "</td>";
				echo "<td>" . $row['quantity'] . "</td>";
				echo "<td>" . $row['totalItemsPrice'] . "</td>";
				
				// Close off the row and form
				echo "</tr>";
			}
			// Close off the table
			echo "</table><br>";
		} 
		else {
			echo "No redistribution invoices in database.";
		}
		
	?>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
</body>
</html>