<?php include 'php/header.php'; ?>
<script src="js/clientOps.js"></script>
    <button id='btn_back' onclick="goBack()">Back</button>
	<h3>Add New Client</h3>
	
	<div class="body_content">
	
		<form name="addClient" action="php/clientOps.php" onSubmit="return validateNewClient()" method="post">
		
			<!-- Required fields -->
			<div id="clientFirstName" class="required"><div class="tooltip"><label>First Name:</label>
				<div class="tooltiptext">This name should be for the head of the household</div>
				</div>
				<input type="text" id="clientFNameField" name="clientFirstName" maxlength="45">
			</div>
				
			</div><br>
			<div id="clientLastName" class="required"><label>Last Name:</label> <input type="text" id="clientLNameField" name="clientLastName" maxlength="45"></div><br>
			<div id="numAdults" class="required"><label>Number of Adults:</label> <input type="number" name="numAdults" id="numAdultsField" min=1 value=1></div>
			<div id="numKids">Number of Children: <input type="number" name="numKids" value="0"></div><br>
			<div id="birthDate" class="required"><label>Date of Birth:</label> <input type="date" id="birthDateField" name="birthDate" min="1900-01-01"></div><br>
			
			<!-- Gender Selection -->
			<div id="gender">Gender:
				<select name="gender"> <option value=0>-</option>
				<option value=-1>Male</option> <option value=1>Female</option> 
			</select> </div>
			
			<!-- Dropdown for client type -->
			<div id="clientType">Client Type:
				<select name="clientType"> <option value=0>Unknown</option>
				<option value=1>Constituent</option> <option value=2>Member</option> <option value=3>Resident</option> 
			</select> </div>
			
			<!-- Dropdown for food stamps -->
			<div id="foodStamps">Food Stamp Status:
				<select name="foodStamps"> <option value=-1>Unknown</option>
				<option value=1>Yes</option> <option value=0>No</option> 
			</select> </div>
			<div id="email">Email: <input type="email" name="email"></div>
			<div id="phoneNo">Phone Number: <input type="tel" name="phoneNo"></div><br>
			<div id="addressStreet">Street Address: <input type="text" name="addressStreet"></div>
			<div id="addressCity">City: <input type="text" name="addressCity"></div>
			
			<!-- Dropdown for state -->
			<div id="addressState">State:
				<select name="addressState"> <option value=""></option>
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
			</select> </div>
			<div id="addressZip">Zip Code: <input id="addressZipField" type="number" name="addressZip"></div>
			
			<br>
			<input type="submit" name="submitClient" value="Create" >
		</form>
		
	</div><!-- /body_content -->
	</div><!-- /content -->	

</body>

</html>