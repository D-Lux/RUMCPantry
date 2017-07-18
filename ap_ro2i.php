<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>

    <title>Redistribution Client List</title>
	
</head>

<body>
	<button onclick="goBack()">Go Back</button>
    <h1>
        Redistribution Partners (inactive)
    </h1>

	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query string
		$sql = "SELECT Client.clientID, Client.email, Client.phoneNumber, Client.address,
				Client.city, Client.state, Client.zip, Client.notes, FamilyMember.lastName as Name
				FROM FamilyMember
				JOIN Client 
				ON Client.clientID=FamilyMember.clientID
				WHERE Client.isDeleted=1
				AND redistribution=1
				ORDER BY FamilyMember.lastName";
		$result = queryDB($conn, $sql);
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
			echo "<table> <tr> <th></th>";
			echo "<th>Partner</th><th>email</th><th>phone number</th>";
			echo "<th></th></tr>";
			
			while($row = sqlFetch($result)) {
				// Start the form for this row (so buttons act correctly based on client)
				echo "<form action='php/redistOps.php' method='post'>";
				
				// Get the client ID so we can properly do the update and delete operations
				$id = $row["clientID"];
				echo "<input type='hidden' name='id' value='$id'>";
				
				// Start Table row
				echo "<tr>";

				// Update button				
				echo "<td><input type='submit' name='updateRedist' value='Update'></td>";
				
				// Information Fields
				echo "<td>" . $row['Name'] . "</td>";
				
				// Display the email or '-' if not set
				echo "<td>" . (($row['email'] == NULL) ? "-" : $row['email']) . "</td>";

				// Display the phone number or '-' if not set
				echo "<td>" . (($row['phoneNumber'] == NULL) ? "-" : 
								displayPhoneNo($row['phoneNumber'])) . "</td>";

				// Set Active button
				?>
				<td><input type="submit" class="btn_reactivate" name="activateRedist" value=" "
				onclick="return confirm('Do you want to reactivate this partner?')"></td>
				<?php
				// Close off the row and form
				echo "</form>";
			}
			// Close off the table
			echo "</table><br>";
		} 
		else {
			echo "No Partners in database.";
		}
		
		$conn->close();
	?>
	<br><br>

	<!-- View Active Clients -->
	<form action="ap_ro2.php" method="post">
		<input type="submit" name="ShowInactive" value="View Active Partners">
    </form>

	<div id="errorLog"></div>
</body>
</html>