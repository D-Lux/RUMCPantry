<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/clientOps.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<link rel="stylesheet" type="text/css" href="css/tabs.css" />
	<?php include 'php/checkLogin.php';?>

    <title>Client List</title>
</head>

<body>
	<button onclick="goBack()">Go Back</button>
    <h1>
        Active Clients
    </h1>
	
	<script>
		if (getCookie("clientUpdated") != "") {
			window.alert("Client data updated!");
			removeCookie("clientUpdated");
		}		
	</script>
	
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
				Client.zip, Client.foodStamps, Client.clientType, Client.notes, 
				FamilyMember.firstName as fName, FamilyMember.lastName as lName
				FROM FamilyMember
				JOIN Client 
				ON Client.clientID=FamilyMember.clientID
				WHERE Client.isDeleted=0 AND
				Client.clientID<>" . $availID . " AND
				FamilyMember.isHeadOfHousehold=true AND
				Client.redistribution=0
				ORDER BY FamilyMember.lastName";
		$result = queryDB($conn, $sql);
		
		// tabStarted lets us know we started a tab, but didn't close the /table form
		$tabStarted = FALSE;
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
			// If tabsize has been updated from post, grab that, otherwise default to 20
			$TabSize = (isset($_POST['tabSize']) ? $_POST['tabSize'] : 20);
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
				echo "<th>email</th><th>phone number</th><th>Client Type</th><th>food stamps</th>";
				echo "<th></th></tr>";
			}
			while($row = sqlFetch($result)) {
				if ($makeTabs) {
					if ($clientCounter >= $TabSize) {
						$clientCounter = 0;
						$tabStarted = TRUE;
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
				echo "<td>" . $row['lName'] . ", " . $row['fName'] . "</td>";
				$familySize = $row["numOfAdults"] + $row["numOfKids"];
				echo "<td>$familySize</td>";
				
				// Display the email or '-' if not set
				echo "<td>" . (($row['email'] == NULL) ? "-" : $row['email']) . "</td>";

				// Display the phone number or '-' if not set
				echo "<td>" . (($row['phoneNumber'] == NULL) ? "-" : 
								displayPhoneNo($row['phoneNumber'])) . "</td>";

				// Display client type based off decoder
				$clientType = clientTypeDecoder($row['clientType']);
				echo "<td>" . $clientType . "</td>";
				
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
					$tabStarted = FALSE;
					echo "</table>";
				}
			}
			// Close off the table if we've finished and didn't make tabs, or didn't finish the table in the last tab
			if (( !$makeTabs ) || ( $tabStarted ) ) {
				echo "</table>";
			}
			
			echo "<br>";
			
			// Allow the user to adjust the tab size
			echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
			echo "<input type='submit' name='submit' value='Tab Size'>";
			echo "<select name='tabSize'>";
			for ($i=1; $i <= 100; $i+=($i<5 ? 1 : ($i<50 ? 5 : 10))) {
				echo "<option " . ($_POST['tabSize']!==NULL ? ($_POST['tabSize']==$i ? "selected" : "") : ($i == $TabSize) ? "selected" : "") . " value='" . $i . "'>" . $i . "</option>";
			}
			echo "</select>";
			echo "</form>";
		} 
		else {
			echo "No clients in database.";
		}
		
		closeDB($conn);
	?>
	<br><br>
	
	<!-- NEW Client -->
	<form action="ap_co2.php">
		<input id="CreateNew" type="submit" name="GoNewClient" value="New Client">
    </form>
	
	<br>
	<!-- View Inactive Clients -->
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