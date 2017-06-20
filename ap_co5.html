<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/createClient.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css">
	<?php include 'php/utilities.php'; ?>
	
    <title>Update Family Member</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Update Family Member Information</h1>
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database
		$sql = "SELECT firstName, lastName, isHeadOfHousehold, notes, birthDate, isDeleted, FamilyMemberID
				FROM FamilyMember
				WHERE FamilyMemberID=" . $_GET['memberID'];
		$familyInfo = queryDB($conn, $sql);
	
		// Close the connection
		closeDB($conn);
	
		// ***********************************************************************
		// DISPLAY FAMILY MEMBER INFORMATION
		// Fills in all fields with values from the database
		// "Update" button at end will take all values and update the family member entry with those values
		if ($familyInfo->num_rows > 0) {
			$familyRow = $familyInfo->fetch_assoc();

			// Start the form with appropriate modifiers
			echo "<form name='UpdateMember' action='php/clientOps.php' method='post' onSubmit='return validateNewClientMember()' >";
			
			// Pass along the member ids for correct updating
			echo "<input type='hidden' name='clientID' value=" . $_GET['clientID'] . ">";
			echo "<input type='hidden' name='memberID' value=" . $_GET['memberID'] . ">";
		
			// First Name Field - auto filled in with database data
			echo "<div id='memberFirstName' class='required'><label>First Name:</label>
				<input type='text' name='memberFirstName' maxlength='45' value='" . $familyRow['firstName'] . "'> </div>";
				
			// Last Name
			echo "<div id='memberLastName' class='required'><label>Last Name:</label>
				<input type='text' name='memberLastName' maxlength='45' value='" . $familyRow['lastName'] . "'> </div>";
		
			// If the person is already the head of the household, we just say that.
			// Otherwise, place a checkbox to change head of household
			if ($familyRow['isHeadOfHousehold']) {
				echo "<div id='head'>Member is Head of Household</div><br>";
			}
			else {
				echo "<div id='head'>Head of Household: ";
				echo "<input type='checkbox' name='head' value='head'></div><br>";
			}

			echo "<div id='birthDate'>Birthday: <input type='date' name='birthDate' min='1900-01-01'></div><br>";
			echo "<div id='notes'>Notes: <input type='text' name='notes'></div><br>";
			
			echo "<input type='submit' name='UpdateMember' value='Update'>";
			echo "</form>";
		}
			

		else {
			echo "Family Member not found.";
		}
	?>

</body>

</html>