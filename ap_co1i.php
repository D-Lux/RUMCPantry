<?php include 'php/header.php'; ?>
<script src="js/clientOps.js"></script>
<link rel="stylesheet" type="text/css" href="css/tabs.css" />
	<button id='btn_back' onclick="goBack()">Back</button>
    <h3>Inactive Clients</h3>
	
	<div class="body_content">
	
	<?php		
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query string
		$sql = "SELECT Client.clientID, (Client.numOfAdults + Client.numOfKids) as familySize, Client.isDeleted,
				Client.email, Client.phoneNumber, Client.address, Client.city, Client.state, 
				Client.zip, Client.foodStamps, Client.notes, FamilyMember.lastName
				FROM FamilyMember
				JOIN Client 
				ON Client.clientID=FamilyMember.clientID
				WHERE Client.isDeleted=1 AND
				FamilyMember.isHeadOfHousehold=true AND
				Client.redistribution=0
				ORDER BY FamilyMember.lastName";
		$result = queryDB($conn, $sql);
		// loop through the query results
		
		// tabStarted lets us know we started a tab, but didn't close the /table form
		$tabStarted = FALSE;
		
		if ($result!=null && $result->num_rows > 0) {
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
				echo "<th>Name</th><th>Size</th>";
				echo "<th>Email</th><th>Phone Number</th>";
				echo "<th></th></tr>";
			}
			while($row = sqlFetch($result)) {
				if ($makeTabs) {
					if ($clientCounter >= $TabSize) {
						$clientCounter = 0;
						$tabStarted = TRUE;
						// Create the client table and add in the headers
						echo "<table id='clientList" . $tabCounter . "' class='tabcontent'> <tr> <th></th>";
						echo "<th>Client Name</th><th>Size</th>";
						echo "<th>Email</th><th>Phone Number</th>";
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
				echo "<td>" . $row["familySize"] . "</td>";
				
				// Display the email or '-' if not set
				echo "<td>" . (($row['email'] == NULL) ? "-" : $row['email']) . "</td>";

				// Display the phone number or '-' if not set
				echo "<td>" . (($row['phoneNumber'] == NULL) ? "-" : 
								displayPhoneNo($row['phoneNumber'])) . "</td>";

				// Display Yes/No/Unknown for foodstamps based on 1/0/-1
				//$foodStampStatus = ($row['foodStamps'] == 0 ? "No" : $row['foodStamps'] == 1 ? "Yes" : "Unknown");
				//echo "<td>" . $foodStampStatus . "</td>";
				
				// Switch to html to do the javascript for the inactivate button
				?>
				
				<td><input id="ActiveClient" type="submit" class="btn_reactivate" name="ActiveClient" value=" "
					onclick="return confirm('Do you want to reactivate this client?')"></td>
					
				<?php
				// Close off the row and form
				echo "</tr></form>";
				
				// Close off the tab if we've hit our peak
				if ( ( $makeTabs ) && ( $clientCounter >= $TabSize) ) {
					echo "</table>";
				}
			}
			if (( !$makeTabs ) || ( $tabStarted ) ) {
				echo "</table>";
			}
			
			echo "<br>";
			// Allow the user to adjust the tab size
			echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
			echo "<input type='submit' id='btn_tabSize' name='submit' value='Tab Size'>";
			echo "<select name='tabSize'>";
			for ($i=1; $i <= 100; $i+=($i<5 ? 1 : ($i<50 ? 5 : 10))) {
				echo "<option " . ($_POST['tabSize']!==NULL ? ($_POST['tabSize']==$i ? "selected" : "") : ($i == 20) ? "selected" : "") . " value='" . $i . "'>" . $i . "</option>";
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
	
	<!-- View Active Clients -->
	<form action="ap_co1.php">
		<input type="submit" name="ShowActive" value="View Active Clients">
    </form>
	
	<script>
		// Open the default tab (if tabs exist)
		document.getElementById("defaultOpen").click();
	</script>
	<div id="errorLog"></div>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>
</html>