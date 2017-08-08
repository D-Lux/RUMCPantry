<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/clientOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
	<?php include 'php/checkLogin.php';?>

	
    <title>Update Client Information</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Update Client Information</h1>

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
			
			
			$clientType = $clientRow['clientType'];
			echo "<div id='clientType'>Foodstamp Status: <select name='clientType'> 
				<option value=0 " . ($clientType == 0 ? "selected" : "") . ">Unknown</option>
				<option value=1 " . ($clientType == 1 ? "selected" : "") . ">Constituent</option>
				<option value=2 " . ($clientType == 2 ? "selected" : "") . ">Member</option>
				<option value=3 " . ($clientType == 3 ? "selected" : "") . ">Resident</option>
				</select> </div>";
			
			
			echo "<input type='hidden' name='id' value='" . $_GET['id'] . "'>";
			echo "<div id='numAdults' class='required'><label>Number of Adults:</label> <input type='number' min=1 name='numAdults' value=" . $clientRow['numOfAdults'] . "></div>";
			echo "<div id='numKids'>Number of Children: <input type='number' name='numKids' value=" . $clientRow['numOfKids'] . "></div><br>";
			$familySize =  $clientRow['numOfKids'] + $clientRow['numOfAdults'];
			
			$foodStampStatus = $clientRow['foodStamps'];
			// Auto select the correct food stamp selection based on database value
			echo "<div id='foodStamps'>Foodstamp Status: <select name='foodStamps'> 
				<option value=-1 " . ($foodStampStatus == -1 ? "selected" : "") . ">Unknown</option>
				<option value=1 " . ($foodStampStatus == 1 ? "selected" : "") . ">Yes</option>
				<option value=0 " . ($foodStampStatus == 0 ? "selected" : "") . ">No</option>
				</select> </div>";
			
			echo "<div id='email'>Email: <input type='email' name='email' value='" . $clientRow['email'] . "'></div>";
			echo "<div id='phoneNo'>Phone Number: <input type='tel' name='phoneNo' value=" . displayPhoneNo($clientRow['phoneNumber']) . "></div>";
			echo "<div id='addressStreet'>Street Address: <input type='text' name='addressStreet' value='" . $clientRow['address'] . "' ></div>";
			echo "<div id='addressCity'>City: <input type='text' name='addressCity' value='" . $clientRow['city'] . "'></div>";
			// Dropdown for state
			echo "<div id='addressState'>State:
				<select name='addressState'> <option value=" . $clientRow['state'] . ">" . $clientRow['state'] . "</option>
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
			</select> </div>";
			echo "<div id='addressZip'>Zip Code: <input type='number' name='addressZip' value=" . $clientRow['zip'] . "></div>";
			
			// TODO: Get this looking like a proper 'notes' box
			echo "<br><div id='notes' >Notes: <input class='notes' type='text' maxlength='256' name='notes' value='" . $clientRow['notes'] . "'></div>";
			echo "<br><br>";
			echo "<input type='submit' name='UpdateClient' value='UpdateClient'>";
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
				echo "<td><input type='submit' name='GoUpdateMember' value='Update'></td>";
				
				// Various basic information fields
				echo "<td>" . $row['firstName'] . "</td>";
				echo "<td>" . $row['lastName'] . "</td>";
				echo "<td>" . $row['birthDate'] . "</td>";
				echo "<td>" . genderDecoderShort($row['gender']) . "</td>";
				
				// Display 'isHeadOfHousehold' as yes or no
				$head = ($row['isHeadOfHousehold'] ? "Yes" : "No"); 
				
				// TODO: Maybe make this a check mark if true, nothing if not
				echo "<td> $head </td>";

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
					echo "<form method='post' action='ap_oo4.php'>";
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

</body>

</html>