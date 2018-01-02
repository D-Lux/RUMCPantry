<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
<script src="js/clientOps.js"></script>

	<h3>Update Family Member Information</h3>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database
		$sql = "SELECT firstName, lastName, isHeadOfHousehold, notes, birthDate, gender, isDeleted, FamilyMemberID
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
		
			echo "<div class='inputDiv'>";
			// First Name Field - auto filled in with database data
			echo "<div id='memberFirstName' class='required'><label for='memberFirstNameField'>First Name:</label>
				<input type='text' id='memberFirstNameField' name='memberFirstName' maxlength='45' value='" . displaySingleQuote($familyRow['firstName']) . "'> </div>";
				
			// Last Name
			echo "<div id='memberLastName' class='required'><label for='memberLastNameField'>Last Name:</label>
				<input type='text' id='memberLastNameField' id='validateNewClientMember' name='memberLastName' maxlength='45' value='" . displaySingleQuote($familyRow['lastName']) . "'> </div>";

			echo "<label for='birthDateInput'>Birthday:</label>
				<input id='birthDateInput' type='date' name='birthDate' min='1900-01-01' value='" . $familyRow['birthDate'] . "'><br>";
			
			$gender = $familyRow['gender'];
			echo "<label for='genderInput'>Gender:</label>
				<select id='genderInput' name='gender'> 
				<option value=0 " . ($gender == 0 ? "selected" : "") . ">-</option>
				<option value=-1 " . ($gender == -1 ? "selected" : "") . ">Male</option>
				<option value=1 " . ($gender == 1 ? "selected" : "") . ">Female</option>
				</select> <br>";
			
			echo "<label for='notesInput'>Notes:</label>
				  <textarea class='notes' type='text' name='notes'>" . $familyRow['notes'] . "</textarea><br>";
			
			// If the person is already the head of the household, we just say that.
			// Otherwise, place a checkbox to change head of household
			if ($familyRow['isHeadOfHousehold']) {
				echo "<div id='head'>Member is Head of Household</div><br>";
			}
			else {
				echo "<div class='selectionBoxes'>";
				echo "<div id='head'>Head of Household: ";
				echo "<input type='checkbox' id='HoH' name='head' value='head'>";
				echo "<label for='HoH' ></label>";
				echo "</div></div><br>";
			}
			
			// </inputDiv>
			echo "</div>";
			
			// submit button
			echo "<input type='submit' name='UpdateMember' value='Update'>";
			echo "</form>";
		}
			

		else {
			echo "Family Member not found.";
		}
	?>
	
	

</body>

</html>