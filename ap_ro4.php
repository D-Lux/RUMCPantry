<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/redistOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
	<?php include 'php/checkLogin.php';?>

	
    <title>Update Partner Information</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Update Partner Information</h1>
	
	<script>
		if (getCookie("newPartner") != "") {
			window.alert("New Parnter Added!");
			removeCookie("newPartner");
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

		$sql = "SELECT email, phoneNumber, address, city, state, zip, Client.notes, FamilyMember.lastName as name
				FROM Client
				JOIN FamilyMember
				ON FamilyMember.clientID=Client.clientID
				WHERE Client.clientID=" . $_GET['id'];
		$partnerInfo = queryDB($conn, $sql);
		
		// Grab invoice information
		$sql = "SELECT invoiceID, visitDate, clientID
				FROM Invoice
				WHERE clientID=" . $_GET['id'];
		$invoiceInfo = queryDB($conn, $sql);
		
		// Close the connection as we've gotten all the information we should need
		closeDB($conn);
	
		// 
		// ***********************************************************************
		// DISPLAY CLIENT INFORMATION
		// Fill in all fields with values from the database
		
		if ($partnerInfo->num_rows > 0) {
			$partnerRow = sqlFetch($partnerInfo);
			
			echo "<form name='updateClient' action='php/redistOps.php' onSubmit='return validateNewPartner()' method='post' >";
			echo "<div id='partnerName' class='required'><label>Partner Name:</label>  <input type='text' name='partnerName' value='" . displaySingleQuote($partnerRow['name']) . "'><br>";
			echo "<input type='hidden' name='partnerID' value='" . $_GET['id'] . "'>";

			echo "Email: <input type='email' name='email' value='" . $partnerRow['email'] . "'><br>";
			echo "Phone Number: <input type='tel' name='phoneNo' value=" . displayPhoneNo($partnerRow['phoneNumber']) . "><br>";
			echo "Street Address: <input type='text' name='addressStreet' value='" . $partnerRow['address'] . "' >";
			echo "City: <input type='text' name='addressCity' value='" . $partnerRow['city'] . "'>";
			// Dropdown for state
			echo "State:
				<select name='addressState'> <option value=" . $partnerRow['state'] . ">" . $partnerRow['state'] . "</option>
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
			</select><br>";
			echo "Zip Code: <input type='number' name='addressZip' value=" . $partnerRow['zip'] . ">";
			
			// TODO: Get this looking like a proper 'notes' box
			echo "<br><div id='notes'>Notes: <input class='notes' type='text' maxlength='256' name='notes' value='" . $partnerRow['notes'] . "'></div>";
			echo "<br><br>";
			echo "<input type='submit' name='submitUpdateRedist' value='Update'>";
			echo "</form>";
		
			
			// ***********************************************************************
			// SHOW Invoices
			
			echo "<br><br><h3>Redistributions</h3>";
			if ($invoiceInfo!=null && $invoiceInfo->num_rows > 0) {
		
				// Create the invoice table and add in the headers
				echo "<table> <tr> <th>Invoice Date</th><th>Status</th></tr>";
				
				while($row = sqlFetch($invoiceInfo)) {						
					
					// Start Table row
					echo "<tr>";

					// TODO: Fix link to invoice view page // Date, as a link to a view invoice page
					$invoiceLink = "/RUMCPantry/ap_ao4.php?id=" . $row["invoiceID"];;
					$date = $row["visitDate"];
					echo "<td><a href='" . $invoiceLink . "'>" . $date . "</a></td>";
					
					$status = visitStatusDecoder($row['status']);
					echo "<td>" . $status . "</td>";
					
					// Close off the row and form
					echo "</tr></form>";
				}
				echo "</table>";
			} 
			else {
				echo "No Redistributions in Database.";
			}
		} 
		else {
			echo "Partner was not found.";
		}	
	?>

</body>

</html>