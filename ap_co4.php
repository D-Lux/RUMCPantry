<?php include 'php/header.php'; ?>
<script src="js/clientOps.js"></script>

    <button id='btn_back' onclick="goBack()">Back</button>
	<h3>New Family Member</h3>
	
	<div class="body_content">
	
		<form name="addMember" action="php/clientOps.php" onSubmit="return validateNewClientMember()" method="post">
			<?php echo "<input type='hidden' name='id' value=". $_GET['id'] ." >" ?>
			
			
			<!-- Required fields -->
			<div id="memberFirstName" class="required"><label>First Name:</label>
				<input type="text" id='memberFirstNameField' name="memberFirstName" maxlength="45">
			</div>
			
			<?php
			// Autofill the last name field with the client's last name, leave editable, of course
			echo "<div id='memberLastName' class='required'><label>Last Name:</label>";
			echo "<input type='text' id='memberLastNameField' name='memberLastName' maxlength='45' value='" . $_GET['lnamedefault'] . "'>";
			?>
			</div>
			
			<div class="selectionBoxes">
				<div id="head">Head of Household:
				<input type="checkbox" id="HoH" name="head" value="head">
				<label for="HoH" ></label>
			</div></div>
			
			
			<div id="birthDate">Birthday: <input type="date" name="birthDate" min="1900-01-01"></div>
			<!-- Gender Selection -->
			<div id="gender">Gender:
				<select name="gender"> <option value=0>-</option>
				<option value=-1>Male</option> <option value=1>Female</option> 
			</select> </div>
			<div id="notes">Notes: <textarea class="notes" type="text" name="notes"></textarea></div><br>
			
			<!-- Submit button -->
			<input type="submit" value="Create" name="submitMember">
		</form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
</body>

</html>