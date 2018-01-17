<?php 
  include 'php/header.php';
  include 'php/backButton.php';
?>

	<h3>Add New Client</h3>
	
	<div class="body_content">
	
		<form name="addClient" action="php/clientOps.php" onSubmit="return validateNewClient()" method="post">
		
			<div class="inputDiv">
				<!-- Required fields -->
				<div id="clientFirstName" class="required"><label for="clientFNameField" >First Name:</label>
					<input type="text" id="clientFNameField" name="clientFirstName" maxlength="45">
				</div>
				
				<div id="clientLastName" class="required"><label for="clientLNameField">Last Name:</label> <input type="text" id="clientLNameField" name="clientLastName" maxlength="45"></div>
				
				<div id="numAdults" class="required"><label for="numAdultsField">Number of Adults:</label> <input type="number" name="numAdults" id="numAdultsField" min=1 value=1></div>
				<label for="numKidsInput">Number of Children:</label><input id="numKidsInput" type="number" name="numKids" value="0"><br>
				<div id="birthDate" class="required"><label for="birthDateField">Date of Birth:</label> <input type="date" id="birthDateField" name="birthDate" min="1900-01-01"></div><br>
				
				<!-- Gender Selection -->
				<label for="genderInput">Gender:</label>
					<select id="genderInput" name="gender"> <option value=0>-</option>
					<option value=-1>Male</option> <option value=1>Female</option> 
				</select><br><br>
				
				<!-- Dropdown for client type -->
				<label for="clientTypeInput">Client Type:</label>
					<select id="clientTypeInput" name="clientType"> <option value=0>Unknown</option>
					<option value=1>Constituent</option> <option value=2>Member</option> <option value=3>Resident</option> 
				</select><br>
				
				<!-- Dropdown for food stamps -->
				<label for="foodStampsInput">Food Stamp Status:</label>
					<select id="foodStampsInput" name="foodStamps"> <option value=-1>Unknown</option>
					<option value=1>Yes</option> <option value=0>No</option> 
				</select><br>
				<label for="emailInput">Email:</label><input id="emailInput" type="email" name="email"><br>
				<label for="phoneNoInput">Phone Number:</label><input id="phoneNoInput" type="tel" name="phoneNo"><br>
				<label for="addressStreetInput">Street Address:</label><input id="addressStreetInput" type="text" name="addressStreet"><br>
				<label for="addressCityInput">City:</label><input id="addressCityInput" type="text" name="addressCity"><br>
				
				<!-- Dropdown for state -->
				<label for="addressStateInput">State:</label>
					<select id="addressStateInput" name="addressState"> <option value=""></option>
					<option value="AL">AL</option> <option value="AK">AK</option> <option value="AZ">AZ</option> <option value="AR">AR</option>
					<option value="CA">CA</option> <option value="CO">CO</option> <option value="CT">CT</option> <option value="DE">DE</option>
					<option value="DC">DC</option> <option value="FL">FL</option> <option value="GA">GA</option> <option value="HI">HI</option>
					<option value="ID">ID</option> <option selected value="IL">IL</option> <option value="IN">IN</option> <option value="IA">IA</option>
					<option value="KS">KS</option> <option value="KY">KY</option> <option value="LA">LA</option> <option value="ME">ME</option>
					<option value="MD">MD</option> <option value="MA">MA</option> <option value="MI">MI</option> <option value="MN">MN</option>
					<option value="MS">MS</option> <option value="MO">MO</option> <option value="MT">MT</option> <option value="NE">NE</option>
					<option value="NV">NV</option> <option value="NH">NH</option> <option value="NJ">NJ</option> <option value="NM">NM</option>
					<option value="NY">NY</option> <option value="NC">NC</option> <option value="ND">ND</option> <option value="OH">OH</option>
					<option value="OK">OK</option> <option value="OR">OR</option> <option value="PA">PA</option> <option value="RI">RI</option>
					<option value="SC">SC</option> <option value="SD">SD</option> <option value="TN">TN</option> <option value="TX">TX</option>
					<option value="UT">UT</option> <option value="VT">VT</option> <option value="VA">VA</option> <option value="WA">WA</option>
					<option value="WV">WV</option> <option value="WI">WI</option> <option value="WY">WY</option> 
				</select> <br>
				<label for="addressZipField">Zip Code:</label><input id="addressZipField" type="number" name="addressZip"><br>
			
			<!-- </inputDiv> -->
			</div>
			
			<br>
			<input type="submit" name="submitClient" value="Create" >
		</form>

<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>    
