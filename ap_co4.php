<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/createClient.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css">
	<?php include 'php/utilities.php'; ?>
	<?php include 'php/checkLogin.php';?>

    <title>Add Family Member</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Add New Family Member</h1>
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

		<div id="head">Head of Household: <input type="checkbox" name="head" value="head"></div><br>

		<div id="birthDate">Birthday: <input type="date" name="birthDate" min="1900-01-01"></div><br>
		<div id="notes">Notes: <input type="text" name="notes"></div><br>
		
		<!-- Submit button -->
        <input type="submit" value="Create" name="submitMember">
    </form>

</body>

</html>