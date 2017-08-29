<?php include 'php/utilities.php'; ?>
<script src="js/clientOps.js"></script>

    <button onclick="goBack()">Go Back</button>
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
			echo "<input type='text' id='memberLastNameField' name='memberLastName' maxlength='45' value='" . $_GET['lnamedefault'] . "'";
			?>
			</div><br>
			
			<div class="selectionBoxes">
			<div id="head">Head of Household: <input type="checkbox" id="HoH" name="head" value="head">
			<label for="HoH" ></label><br>
			</div></div>
			
			<div id="birthDate">Birthday: <input type="date" name="birthDate" min="1900-01-01"></div><br>
			<!-- Gender Selection -->
			<div id="gender">Gender:
				<select name="gender"> <option value=0>-</option>
				<option value=-1>Male</option> <option value=1>Female</option> 
			</select> </div>
			<div id="notes">Notes: <input type="text" name="notes"></div><br>
			
			<!-- Submit button -->
			<input type="submit" value="Create" name="submitMember">
		</form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
</body>

</html>