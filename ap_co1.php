<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/clientOps.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>

    <title>ap_co1</title>
	
	<style>
		div.tab {
			overflow: hidden;
		}

		div.tab button {
			background-color: inherit;
			float: left;
			border: none;
			outline: none;
			cursor: pointer;
			padding: 3px 6px;
			transition: 0.3s;
		}

		div.tab button:hover {
			background-color: #ccc;
		}

		div.tab button.active {
			background-color: #000;
			color: white;
		}

		.tabcontent {
			display: none;
			padding-top: 10px;
		}
	</style>
</head>

<body>
	<button onclick="goBack()">Go Back</button>
    <h1>
        Active Clients
    </h1>
	
	<!-- Search for a name TODO
	<form method="get" action="<?php //echo "$_SERVER['PHP_SELF']";?>">
        Last name: <input type="search" name="lname">
        </br>
		<input id="Search" type="submit" name="submit" value="Search">
    </form> 
	// See SQL Wildcards for this query
	-->
	
	<?php
		// TODO: Limit rows based off a searched name (will be in the address bar)
		
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
				FamilyMember.isHeadOfHousehold=true
				ORDER BY FamilyMember.lastName";
		$result = queryDB($conn, $sql);
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
			$TabSize = 20;
			$makeTabs = ( ($result->num_rows) > $TabSize);
			$tabCounter = 0;
			$clientCounter = $TabSize;
			if ($makeTabs) {
				echo "<div class='tab'>";
				for ($i=0; $i< ceil($result->num_rows/$TabSize); $i++) {
					echo "<button class='tablinks' onclick='viewTab(event, clientList" . $i . ")'";
					echo ($i > 0 ? "" : "id='defaultOpen' ") . ">" . ($i + 1) . "</button>";
				}
				echo "</div>";
			}
			else {
				echo "<table> <tr> <th></th>";
				echo "<th>Client Name</th><th>Family Size</th>";
				echo "<th>email</th><th>phone number</th><th>food stamps</th>";
				echo "<th></th></tr>";
			}
			while($row = sqlFetch($result)) {
				if ($makeTabs) {
					if ($clientCounter >= $TabSize) {
						$clientCounter = 0;
						// Create the client table and add in the headers
						echo "<table id='clientList" . $tabCounter . "' class='tabcontent'> <tr> <th></th>";
						echo "<th>Client Name</th><th>Family Size</th>";
						echo "<th>email</th><th>phone number</th><th>food stamps</th>";
						echo "<th></th></tr>";							
						$tabCounter++;
					}
					$clientCounter++;
				}
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
				echo "<td>" . (($row['email'] == NULL) ? "-" : $row['email']) . "</td>";

				// Display the phone number or '-' if not set
				echo "<td>" . (($row['phoneNumber'] == NULL) ? "-" : 
								displayPhoneNo($row['phoneNumber'])) . "</td>";

				// Display Yes/No/Unknown for foodstamps based on 1/0/-1
				$foodStampStatus = ($row['foodStamps'] == 0 ? "No" : $row['foodStamps'] == 1 ? "Yes" : "Unknown");
				echo "<td>" . $foodStampStatus . "</td>";
				//echo " " . $foodStampStatus . " ";
				
				// Switch to html to do the javascript for the inactivate button
				?>
				
				<td><input id="InactiveClient" type="submit" class="btn_trash" name="InactiveClient" value=" "
					onclick="return confirm('Are you sure you want to set this client inactive?')"></td>
					
				<?php
				// Close off the row and form
				echo "</form>";
				
				// Close off the tab if we've hit our peak
				if ( ( $makeTabs ) && ( $clientCounter >= $TabSize) ) {
					echo "</table>";
				}
			}
			if ( !$makeTabs ) {
				echo "</table>";
			}
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
	
	<br>
	<!-- View Inactive Clients Client -->
	<form action="ap_co1i.php">
		<input type="submit" name="ShowInactive" value="View Inactive Clients">
    </form>
	
	<script>
		// Open the default tab (if tabs exist)
		document.getElementById("defaultOpen").click();
	</script>
	<div id="errorLog"></div>
</body>
</html>