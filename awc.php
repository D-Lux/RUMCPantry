<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/walkInOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
    <title>Add Walk-In</title>
	
</head>

<body>
	<style>
		.awc_hidden {
			display: none;
			padding-top: 10px;
		}
	</style>
	
    <button onclick="goBack()">Go Back</button>
	<br>
	<h3>Add Walk-In</h3>
	<br>
	
	<?php
	// Get a datalist for the clients set up
	// Button for Existing or new client
	// New client shows new client div
	// Existing shows existing div
	
	// New client div matches new client creation
	
		// *******************************************
		// ** Generate the datalist for client drop down
		
		$conn = createPantryDatabaseConnection();
		$sql = "SELECT firstName AS fName, lastName AS lName, FamilyMember.clientID as clientID
				FROM FamilyMember
				JOIN Client
				ON Client.clientID=FamilyMember.clientID
				WHERE isHeadOfHousehold=1
				AND Client.redistribution=0
				AND Client.isDeleted=0
				AND (firstName <> 'Available'
				AND lastName <> 'Available')";
		$clientInfo = queryDB($conn, $sql);

		if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
			//echo "No clients in the database.";
			//echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		}
		
		// Generate the string we'll need to display the client datalist
		$clientDataList = "<input type='text' name='clientName' list='Clients' autocomplete='off' id='clientID'";
		$clientDataList .= " onchange='updateHiddenClientID()'><datalist id='Clients' >";
		while($client = sqlFetch($clientInfo) ) {
			$clientDataList .= "<option data-value=" . $client['clientID'] . ">";
			$clientDataList .= $client['lName'] . ", " . $client['fName'] . "</option>";

		}
		$clientDataList .= "</datalist>";
		
		// Close the connection
		closeDB($conn);
		
		echo "<div id='awc_options'>";
			echo "<input type='button' onclick='toggleOption(this)' name='existingOption' value='Existing Client'>";
			echo "<input type='button' onclick='toggleOption(this)' name='newOption' value='New Client'>";
		echo "</div>";
	
	
		echo "<div id='existingOption' class='awc_hidden' >";
			echo "<form action='php/apptOps.php' method='post' >";
			echo "Name: ";
			echo $clientDataList . "<br><br>";
			echo "<input type='hidden' ID='clientID-hidden' name='clientID' value=0>";
			echo "<input type='submit' name='existingWalkIn' value='Add Walk-In'>";
			echo "</form>";
		echo "</div>";
		
		echo "<div id='newOption' class='awc_hidden' >";

		?>
			<form name="addClient" onSubmit="return validateNewWalkIn()" action="php/clientOps.php" method="post">
	
			<!-- Required fields -->
			<div id="clientFNameField">First Name:<input type="text" id="newWalkinFName" name="clientFirstName" maxlength="45"></div>
			<div id="clientLNameField">Last Name:<input type="text" id="newWalkinLName" name="clientLastName" maxlength="45"></div><br>
			Date of Birth: <input type="date" name="birthDate" min="1900-01-01"><br>
			
			<div id="gender">Gender:
				<select name="gender"> <option value=0>-</option>
				<option value=-1>Male</option> <option value=1>Female</option> 
			</select> </div><br>
		
			Number of Adults:<input type="number" name="numAdults" min=1 value=1><br>
			Number of Children: <input type="number" name="numKids" value="0"><br><br>
			
			<!-- Dropdown for client type -->
			<div id="clientType">Client Type:
				<select name="clientType"> <option value=0>Unknown</option>
				<option value=1>Constituent</option> <option value=2>Member</option> <option value=3>Resident</option> 
			</select> </div>
		
			<!-- Dropdown for food stamps -->
			Food Stamp Status:
			<select name="foodStamps"> <option value=-1>Unknown</option>
				<option value=1>Yes</option> <option value=0>No</option> 
			</select><br>
			Email: <input type="email" name="email"><br>
			Phone Number: <input type="tel" name="phoneNo"><br>
			Street Address: <input type="text" name="addressStreet">
			City: <input type="text" name="addressCity"><br>
			
			<!-- Dropdown for state -->
			State:<select name="addressState"><option value=""></option>
				<option value="AL">AL</option> <option value="AK">AK</option> <option value="AZ">AZ</option> <option value="AR">AR</option>
				<option value="CA">CA</option> <option value="CO">CO</option> <option value="CT">CT</option> <option value="DE">DE</option>
				<option value="DC">DC</option> <option value="FL">FL</option> <option value="GA">GA</option> <option value="HI">HI</option>
				<option value="ID">ID</option> <option value="IL">IL</option> <option value="IN">IN</option> <option value="IA">IA</option>
				<option value="KS">KS</option> <option value="KY">KY</option> <option value="LA">LA</option> <option value="ME">ME</option>
				<option value="MD">MD</option> <option value="MA">MA</option> <option value="MI">MI</option> <option value="MN">MN</option>
				<option value="MS">MS</option> <option value="MO">MO</option> <option value="MT">MT</option> <option value="NE">NE</option>
				<option value="NV">NV</option> <option value="NH">NH</option> <option value="NJ">NJ</option> <option value="NM">NM</option>
				<option value="NY">NY</option> <option value="NC">NC</option> <option value="ND">ND</option> <option value="OH">OH</option>
				<option value="OK">OK</option> <option value="OR">OR</option> <option value="PA">PA</option> <option value="RI">RI</option>
				<option value="SC">SC</option> <option value="SD">SD</option> <option value="TN">TN</option> <option value="TX">TX</option>
				<option value="UT">UT</option> <option value="VT">VT</option> <option value="VA">VA</option> <option value="WA">WA</option>
				<option value="WV">WV</option> <option value="WI">WI</option> <option value="WY">WY</option> 
			</select>
			Zip Code: <input type="number" name="addressZip">
			
			<input type="hidden" name="newWalkIn" value=1>
			<br><br>
			<input type="submit" name="submitClient" value="Add Walk-In" >
			</form>
		</div>
		
		<div id='errorLog'></div>

</body>

</html>