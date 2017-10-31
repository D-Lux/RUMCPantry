<?php include 'php/header.php'; ?>
<script src="js/clientOps.js"></script>
    <button id='btn_back' onclick="goBack()">Back</button>
	
	<h3>Update Client</h3>

	<script>
		if (getCookie("newClient") != "") {
			window.alert("New Client Added!");
			removeCookie("newClient");
		}
		if (getCookie("DelFam") != "") {
			window.alert("Family Member Removed!");
			removeCookie("DelFam");
		}
		if (getCookie("Err_DelFam") != "") {
			window.alert("Cannot Remove The Head of Household!");
			removeCookie("Err_DelFam");
		}
		
	</script>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database
		// It'll be easy to just grab the queries here for all the data we will display layer
		
		// Grab client information
		$sql = "SELECT numOfAdults, numOfKids, email, phoneNumber, address, city, state, zip, foodStamps, notes, clientType
				FROM Client
				WHERE clientID=" . $_GET['id'];
		$clientInfo = queryDB($conn, $sql);
		$familySize = 1;
		
		// Grab family member information
		$sql = "SELECT firstName, lastName, isHeadOfHousehold, notes, birthDate, gender, FamilyMemberID
				FROM FamilyMember
				WHERE clientID=" . $_GET['id'] . "
				AND isDeleted<>1";
		$familyInfo = queryDB($conn, $sql);
		
		// Grab invoice information
		$sql = "SELECT invoiceID, visitDate, status, visitTime
				FROM Invoice
				WHERE clientID=" . $_GET['id'];
		$invoiceInfo = queryDB($conn, $sql);
		
		// Grab the client family name
		$sql = "SELECT lastName 
				FROM FamilyMember
				JOIN Client
				ON FamilyMember.ClientID = Client.ClientID
				WHERE isHeadOfHousehold = TRUE
				AND FamilyMember.ClientID = " . $_GET['id'];
		$clientNameInfo = sqlFetch(queryDB($conn, $sql));
		$clientName = $clientNameInfo['lastName'];
		
		// Close the connection as we've gotten all the information we should need
		// Using function in utilities.php to close the database
		closeDB($conn);
	
		// ***********************************************************************
		// DISPLAY CLIENT INFORMATION
		// Fills in all fields with values from the database
		// "Update" button at end will take all values and update the client entry with those values
		
		if (($clientInfo->num_rows > 0) AND ($familyInfo->num_rows > 0)) {
			$clientRow = sqlFetch($clientInfo);
			echo "Client Family Name: $clientName <br><br><br>";
			
			echo "<form name='updateClient' action='php/clientOps.php' method='post' >";
			
			echo "<input type='hidden' name='id' value='" . $_GET['id'] . "'>";
			
			// Open a div to set spacing
			echo "<div class='inputDiv'>";
			
			echo "<div class='required'><label for='numAdultsInput'>Number of Adults:</label>";
			echo "<input id='numAdultsInput' type='number' min=1 name='numAdults' value=" . $clientRow['numOfAdults'] . "></div>";
			echo "<label for='numKidsInput'>Number of Children: </label>";
			echo "<input id='numKidsInput' type='number' name='numKids' value=" . $clientRow['numOfKids'] . "><br>";
			$familySize =  $clientRow['numOfKids'] + $clientRow['numOfAdults'];
			
			echo "<br>";
			
			$foodStampStatus = $clientRow['foodStamps'];
			// Auto select the correct food stamp selection based on database value
			echo "<label for='foodStampsInput'>Foodstamp Status:</label>
				<select id='foodStampsInput' name='foodStamps'> 
				<option value=-1 " . ($foodStampStatus == -1 ? "selected" : "") . ">Unknown</option>
				<option value=1 " . ($foodStampStatus == 1 ? "selected" : "") . ">Yes</option>
				<option value=0 " . ($foodStampStatus == 0 ? "selected" : "") . ">No</option>
				</select><br>";
				
			$clientType = $clientRow['clientType'];
			echo "<label id='clientTypeInput'>Client Type:</label>
				<select id='clientTypeInput' name='clientType'> 
				<option value=0 " . ($clientType == 0 ? "selected" : "") . ">Unknown</option>
				<option value=1 " . ($clientType == 1 ? "selected" : "") . ">Constituent</option>
				<option value=2 " . ($clientType == 2 ? "selected" : "") . ">Member</option>
				<option value=3 " . ($clientType == 3 ? "selected" : "") . ">Resident</option>
				</select><br>";
			
			echo "<label for='phoneNoInput'>Phone Number:</label>
				  <input type='tel' name='phoneNo' value=" . displayPhoneNo($clientRow['phoneNumber']) . "><br>";
			echo "<label for='emailInput'>Email:</label>
				  <input id='emailInput' type='email' name='email' value='" . $clientRow['email'] . "'><br>";
			echo "<label for='addressStreetInput'>Street Address:</label>
				  <input id='addressStreetInput' type='text' name='addressStreet' value='" . $clientRow['address'] . "' ><br>";
			echo "<label for='addressCityInput'>City:</label>
				  <input id='addressCityInput' type='text' name='addressCity' value='" . $clientRow['city'] . "'><br>";
			// Dropdown for state
			echo "<label for='addressStateInput'>State:</label>
				<select id='addressStateInput' name='addressState'> <option value=" . $clientRow['state'] . ">" . $clientRow['state'] . "</option>
				<option value='AL'>AL</option> <option value='AK'>AK</option> <option value='AZ'>AZ</option> <option value='AR'>AR</option>
				<option value='CA'>CA</option> <option value='CO'>CO</option> <option value='CT'>CT</option> <option value='DE'>DE</option>
				<option value='DC'>DC</option> <option value='FL'>FL</option> <option value='GA'>GA</option> <option value='HI'>HI</option>
				<option value='ID'>ID</option> <option value='IL'>IL</option> <option value='IN'>IN</option> <option value='IA'>IA</option>
				<option value='KS'>KS</option> <option value='KY'>KY</option> <option value='LA'>LA</option> <option value='ME'>ME</option>
				<option value='MD'>MD</option> <option value='MA'>MA</option> <option value='MI'>MI</option> <option value='MN'>MN</option>
				<option value='MS'>MS</option> <option value='MO'>MO</option> <option value='MT'>MT</option> <option value='NE'>NE</option>
				<option value='NV'>NV</option> <option value='NH'>NH</option> <option value='NJ'>NJ</option> <option value='NM'>NM</option>
				<option value='NY'>NY</option> <option value='NC'>NC</option> <option value='ND'>ND</option> <option value='OH'>OH</option>
				<option value='OK'>OK</option> <option value='OR'>OR</option> <option value='PA'>PA</option> <option value='RI'>RI</option>
				<option value='SC'>SC</option> <option value='SD'>SD</option> <option value='TN'>TN</option> <option value='TX'>TX</option>
				<option value='UT'>UT</option> <option value='VT'>VT</option> <option value='VA'>VA</option> <option value='WA'>WA</option>
				<option value='WV'>WV</option> <option value='WI'>WI</option> <option value='WY'>WY</option> 
			</select> <br>";
			echo "<label for='addressZipField'>Zip Code:</label>
				  <input type='number' id='addressZipField' name='addressZip' value=" . $clientRow['zip'] . "><br>";
			
			echo "<br><label for='notesInput'>Notes:</label>
				  <textarea id='notesInput' class='notes' type='text' name='notes'>" . $clientRow['notes'] . "</textarea><br>";
			echo "<br><br>";
			echo "<input type='submit' name='UpdateClient' value='Save'>";
			echo "</div>"; // </inputDiv>
			echo "</form>";
		
			
			// ***********************************************************************
			// DISPLAY FAMILY MEMBERS
			
			echo "<br><br><br><h3>Family Members</h3>";
			
			// Pulls all members of the family
			// firstName | lastName | isHeadOfHousehold | birthdate | notes | Delete
			// Create the family table and add in the headers
			echo "<table> <tr> <th></th>";
			echo "<th>First Name</th><th>Last Name</th>";
			echo "<th>Birth Date</th><th>Gender</th>";
			echo "<th>Head of Household</th><th>Notes</th>";
			
			$showDeleteColumn = ($familyInfo->num_rows > 1);
			if ($showDeleteColumn) {
				echo "<th></th>";
			}
			echo "</tr>";
			
			// Loop through our data and spit it out
			while( $row = sqlFetch($familyInfo) ) {
				// Start the form for this row (so buttons act correctly based on member)
				echo "<form action='php/clientOps.php' >";
				
				// List the member ID so we can do updates and inactivations
				echo "<input type='hidden' name='memberID' value=" . $row['FamilyMemberID'] . ">";
				
				// Pass along the client ID in case we fudge with the head of household later
				echo "<input type='hidden' name='clientID' value='" . $_GET['id'] . "'>";
				
				// Start Table row
				echo "<tr>";

					// Update button				
				echo "<td><input type='submit' name='GoUpdateMember' value='Edit'></td>";
				
				// Various basic information fields
				echo "<td>" . $row['firstName'] . "</td>";
				echo "<td>" . $row['lastName'] . "</td>";
				echo "<td>" . $row['birthDate'] . "</td>";
				echo "<td>" . genderDecoderShort($row['gender']) . "</td>";
				
				// Display 'isHeadOfHousehold' as a Checkmark or blank
				$head = ($row['isHeadOfHousehold'] ? "&#10004;" : ""); 
				
				echo "<td style='color:green;'>$head</td>";

				echo "<td>" . $row['notes'] . "</td>";
				
				// Delete button
				if ($showDeleteColumn) {
					echo "<td><input id='InactiveMember' value=' ' class='btn_trash' name='DeleteMember' ";
					if (!$row['isHeadOfHousehold']) {
						echo "type='submit' onclick=\"javascript: return confirm('Are you sure you want to remove this family member?');\")'></td>";	
					}
					else {
						echo "type='button' onclick=\"javascript: alert('Cannot delete the head of household.');\")'></td>";
					}
				}
				
				// Close off the row and form
				echo "</tr></form>";
			}
			echo "</table>";
			
			echo "<br><form action='ap_co4.php'>";
			
			// Send client ID so we know which family this belongs to
			echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
			
			// Send along the client last name so we can autofill the last name
			echo "<input type='hidden' name='lnamedefault' value='" . $clientName . "'>";
			
			echo "<input id='newMember' type='submit' name='newMember' value='New Family Member'>";	
			echo "</form>";
			
			// ***********************************************************************
			// SHOW VISITS
			// Show all of the visits (actionable to view further information)
			// Add a button to add an appointment
			
			echo "<br><br><br><h3>Visits</h3>";
			if ($invoiceInfo!=null && $invoiceInfo->num_rows > 0) {
		
				// Create the invoice table and add in the headers
				echo "<table> <tr> <th>Invoice Date</th><th>Status</th></tr>";
				
				while($row = sqlFetch($invoiceInfo)) {						
					
					// Start Table row
					echo "<tr>";
					echo "<form method='GET' action='ap_oo4e.php'>";
					echo "<input type='hidden' name='invoiceID' value=" . $row['invoiceID'] . ">";
					echo "<input type='hidden' name='name' value='" . $clientName . "'>";
					echo "<input type='hidden' name='visitTime' value='" . $row['visitTime'] . "'>";
					echo "<input type='hidden' name='familySize' value='" . $familySize . "'>";
					echo "<td><input type='submit' name='fromUpdate' value=" . $row['visitDate'] . "></td>";
					echo "</form>";
					$status = visitStatusDecoder($row['status']);
					echo "<td>" . $status . "</td>";
					
					// Close off the row and form
					echo "</tr></form>";
				}
				echo "</table>";
			} 
			else {
				echo "No Visits in Database.";
			}
		} 
		else {
			echo "Client was not found.";
		}	
	?>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>

</html>