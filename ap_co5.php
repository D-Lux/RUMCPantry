<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
	<h3>Update Family Member Information</h3>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = connectDB();
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
      ?>
			<!-- Start the form with appropriate modifiers -->
			<form name='UpdateMember' action='php/clientOps.php' method='post' onSubmit='return validateNewClientMember()' >
			
			<!-- Pass along the member ids for correct updating -->
			<input type='hidden' name='clientID' value=<?=$_GET['clientID']?>>
			<input type='hidden' name='memberID' value=<?=$_GET['memberID']?>>
		
			<!--Fields autofilled with form data -->
      <div class="row">
				<div class="col-sm-4"><label for="memberFirstNameField" class="required">First Name:</label></div>
        <div class="col-sm-8">
          <input type="text" id="memberFirstNameField" name="memberFirstName" maxlength="45" value='<?=displaySingleQuote($familyRow['firstName'])?>'>
        </div>
      </div>
      <div class="row">
				<div class="col-sm-4"><label for="memberLastNameField" class="required">First Name:</label></div>
        <div class="col-sm-8">
          <input type="text" id="memberLastNameField" name="memberLastName" maxlength="45" 
            value="<?=displaySingleQuote($familyRow['lastName'])?>">
        </div>
      </div>
      <div class="row">
				<div class="col-sm-4"><label for="birthDateInput">Birthday:</label></div>
        <div class="col-sm-8">
          <input id='birthDateInput' type='date' name='birthDate' min='1900-01-01' value='<?=$familyRow['birthDate']?>'>
        </div>
      </div>
      
			<br>
      
      <div class="row">
        <?php
          if ($familyRow['isHeadOfHousehold']) {
            echo "<div class='col-sm-4' id='head'>Member is Head of Household</div>";
          }
          else {
            echo "<div class='selectionBoxes'>";
            echo "<div id='head'>Head of Household: ";
            echo "<input type='checkbox' id='HoH' name='head' value='head'>";
            echo "<label for='HoH' ></label>";
            echo "</div></div>";
          }
        ?>
        <div class="col-sm-2"></div>
				<div class="col-sm-2"><label for="genderInput">Gender:</label></div>
        <div class="col-sm-2">
          <?php
            $gender = $familyRow['gender'];
            echo "<select id='genderInput' name='gender'> 
            <option value=0 " . ($gender == 0 ? "selected" : "") . ">-</option>
            <option value=-1 " . ($gender == -1 ? "selected" : "") . ">Male</option>
            <option value=1 " . ($gender == 1 ? "selected" : "") . ">Female</option>
            </select>";
          ?>
        </div>
      </div>
			
      <div class="row">
				<div class="col-sm-4"><label for='notesInput'>Notes:</label></div>
        <div class="col-sm-8">
          <textarea class='notes' type='text' name='notes'><?=$familyRow['notes']?></textarea>
        </div>
      </div>
			
			
			<input type='submit' class='btn-nav' name='UpdateMember' value='Update'>
		</form>
    <?php
	}
			

	else {
		echo "Family Member not found.";
	}
	?>

<?php include 'php/footer.php'; ?>  
<script src="js/clientOps.js"></script>
