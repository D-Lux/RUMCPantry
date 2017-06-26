<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/clientOps.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>

	
</head>

<body>


    <title>ap_co1</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        First admin page for client operations
    </h1>
	
	<!-- Search for a name TODO
	<form method="get" action="<?php //echo "$_SERVER['PHP_SELF']";?>">
        Last name: <input type="search" name="lname">
        </br>
		<input id="Search" type="submit" name="submit" value="Search">
    </form> 
	// See SQL Wildcards for this query
	-->
	
	<!-- Display all of the clients in a table format -->
	<!-- This generates a table with all clients in one place -->
	<!-- TODO: Limit this by pages -->
	<!-- see this: https://www.w3schools.com/howto/howto_js_tabs.asp -->
	<!-- or this: https://www.w3schools.com/php/php_mysql_select_limit.asp (using parameters) -->
	
	<?php
		// Get the available client ID so we can hide it on the update list
		$availID = getAvailableClient();
		
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query string
		$sql = "SELECT Client.clientID, Client.numOfAdults, Client.numOfKids, Client.isDeleted,
				Client.email, Client.phoneNumber, Client.address, Client.city, Client.state, 
				Client.zip, Client.foodStamps, Client.notes, FamilyMember.lastName
				FROM FamilyMember
				JOIN Client 
				WHERE Client.clientID=FamilyMember.clientID AND
				Client.isDeleted=0 AND
				Client.clientID<>" . $availID . " AND
				FamilyMember.isHeadOfHousehold=true";
		$result = queryDB($conn, $sql);
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
		
			// Create the client table and add in the headers
			// Future scope: Set options to change what displays
			echo "<table> <tr> <th></th>";
			echo "<th>Client Name</th><th>Family Size</th>";
			echo "<th>email</th><th>phone number</th><th>food stamps</th>";
			echo "<th></th></tr>";
			
		// TODO: Limit rows based off a searched name (will be in the address bar)
			while($row = sqlFetch($result)) {
				// Start the form for this row (so buttons act correctly based on client)
				echo "<form action='php/clientOps.php' >";
				
				// Get the client ID so we can properly do the update and delete operations
				$id = $row["clientID"];
				echo "<input type='hidden' name='id' value='$id'>";
				
				// Start Table row
				echo "<tr>";

				// Update button				
				echo "<td><input type='submit' name='GoUpdateClient' value='Update'></td>";
				
				// Various basic information fields
				echo "<td>" . $row['lastName'] . "</td>";
				$familySize = $row["numOfAdults"] + $row["numOfKids"];
				echo "<td>$familySize</td>";
				
				// Display the email or '-' if not set
				if ( $row['email'] == NULL ) {
					echo "<td>-</td>";
				}
				else {
					echo "<td>" . $row['email'] . "</td>";
				}
				
				// Display the phone number or '-' if not set
				if ( $row['phoneNumber'] == NULL ) {
					echo "<td>-</td>";
				}
				else {
					echo "<td>" . displayPhoneNo($row['phoneNumber']) . "</td>";
				}
				// Display Yes/No/Unknown for foodstamps based on 1/0/-1
				$foodStampStatus = ($row['foodStamps'] == 0 ? "No" : $row['foodStamps'] == 1 ? "Yes" : "Unknown");
				echo "<td>" . $foodStampStatus . "</td>";
				
				// Switch to html to do the javascript for the inactivate button
				?>
				<td><input id="InactiveClient" type="submit" class="btn_trash" name="InactiveClient" value=" "
					onclick="return confirm('Are you sure you want to set this client inactive?')"></td>

				<?php
				// Close off the row and form
				echo "</tr></form>";
			}
			echo "</table>";
		} else {
			echo "No clients in database.";
		}
		
		
		$conn->close();
	?>
	<br><br><br>
	
	<!-- NEW Client -->
	<form action="ap_co2.php">
		<input id="CreateNew" type="submit" name="GoNewClient" value="New Client">
    </form>
	
</body>
</html>