<?php include 'php/header.php'; ?>

		<button id='btn_back' onclick="goBack()">Back</button>
		
		
		<h3>Order Selection</h3>
		<div class='body_content'>
			<!-- Refresh button -->
			<button id='btn_back' onclick="location.reload()">Refresh</button>
			<?php
				$conn = createPantryDatabaseConnection();
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				
				// *******************************************
				// ** Generate the datalist for client drop down
				// ** Dropdown restricted to people with active appointments
				
				$sql = "SELECT clientInfo.fName as fName, clientInfo.lName as lName, Invoice.clientID as clientID, 
								status, invoiceID
						FROM Invoice
						JOIN (SELECT firstName AS fName, lastName AS lName, FamilyMember.clientID as clientID
							FROM FamilyMember
							JOIN Client
							ON FamilyMember.clientID = Client.clientID
							WHERE isHeadOfHousehold=1
							AND Client.isDeleted=0
							AND Client.redistribution=0
							AND firstName <> 'Available'
							AND lastName <> 'Available') as clientInfo
						ON clientInfo.clientID = Invoice.clientID
						GROUP BY Invoice.clientID";
				$clientInfo = queryDB($conn, $sql);

				// Close the connection
				closeDB($conn);
				
				if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
					echo "No clients in the database.";
				}
				else {
					$fixItFelix = true;
					echo "<table><tr><th>Client</th><th></th></tr>";
					while ($client = sqlFetch($clientInfo)) {
						if (IsActiveAppointment($client['status'])) {
							$fixItFelix = false;
							echo "<tr><td>" . $client['lName'] . ", " . $client['fName'] . "</td>";
							//echo "<td>";
							echo "<form action='cof.php' method='post' >";
							echo "<input type='hidden' name='clientID' value=" . $client['clientID'] .">";
							echo "<input type='hidden' name='invoiceID' value=" . $client['invoiceID'] .">";
							echo "<input type='hidden' name='clientFirstName' value=" .  $client['fName'] .">";
							echo "<input type='hidden' name='clientLastName' value=" .  $client['lName'] .">";
							echo "<td><input type='submit' name='createOrder' value='Begin Order'>";
							echo "</td></form></tr>";
						}
					}
					if ($fixItFelix) {
						echo "<tr><td>No clients at this time</td><td>-</td></tr>";
					}
					echo "</table>";
				}
				
					
				/*
				// Generate the string we'll need to display the client datalist
				$clientDataList = "<input type='select' name='clientName' list='Clients' autocomplete='off' id='clientID'";
				$clientDataList .= " onchange='updateHiddenData()'><datalist id='Clients' >";
				while($client = sqlFetch($clientInfo) ) {
					// Only show active appointments in the dropdown
					if (IsActiveAppointment($client['status'])) {
						$clientDataList .= "<option data-value=" . $client['clientID'] . ">";
						$clientDataList .= $client['lName'] . ", " . $client['fName'] . "</option>";
					}
				}
				$clientDataList .= "</datalist>";

				echo "<form action='cp2.php' method='post' >";
				echo "Name: ";
				echo $clientDataList . "<br><br>";
				echo "<input type='hidden' ID='clientID-hidden' name='clientID' value=0>";
				echo "<div id='invoiceHidden'>";
				//echo "<input type='hidden' ID='invoiceID-hidden' name='invoiceID' value=0>";
				echo "CurrID: 0";
				echo "</div>";
				echo "<input type='submit' value='Continue'>";
				echo "</form>";
				*/

			?>
		</div><!-- /body_content -->
	</div><!-- /content -->
	</body>
</html>