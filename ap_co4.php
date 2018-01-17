<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
	<h3>New Family Member</h3>
	
	<div class="body_content">
	
		<form name="addMember" action="php/clientOps.php" onSubmit="return validateNewClientMember()" method="post">
			<?php echo "<input type='hidden' name='id' value=". $_GET['id'] ." >" ?>
			
			<div class="inputDiv">
			
				<!-- Required fields -->
				<div id="memberFirstName" class="required"><label for="memberFirstNameField">First Name:</label>
					<input type="text" id='memberFirstNameField' name="memberFirstName" maxlength="45">
				</div>
				
				<?php
				// Autofill the last name field with the client's last name, leave editable, of course
				echo "<div id='memberLastName' class='required'><label for='memberLastNameField'>Last Name:</label>";
				echo "<input type='text' id='memberLastNameField' name='memberLastName' maxlength='45' value='" . $_GET['lnamedefault'] . "'>";
				?>
				</div>
				
				<label for="birthDateInput">Birthday:</label>
				<input id="birthDateInput" type="date" name="birthDate" min="1900-01-01"><br>
				<!-- Gender Selection -->
				<label for="genderInput">Gender:</label>
					<select id="genderInput" name="gender"> <option value=0>-</option>
					<option value=-1>Male</option> <option value=1>Female</option> 
				</select> <br>
				<label for="notesInput">Notes:</label>
				<textarea id="notesInput" class="notes" type="text" name="notes"></textarea></div><br>
				
				<div class="selectionBoxes">
					<div id="head">Head of Household:
					<input type="checkbox" id="HoH" name="head" value="head">
					<label for="HoH" ></label>
				</div></div><br>
			</div> <!-- /inputDiv -->
			<!-- Submit button -->
			<input type="submit" value="Create" name="submitMember">
		</form>
	
<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>