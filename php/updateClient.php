<?php

include 'utilities.php';

if(isset($_POST['Update'])) /*when the button is pressed on post request*/
{
	// Address fields
	$address = fixInput($_POST['addressStreet']);
	$city = fixInput($_POST['addressCity']);
	$state = $_POST['addressState'];
	$zip = $_POST['addressZip'];
	
	// Standard information
	$clientID = $_POST['id'];
	$numAdults = $_POST['numAdults'];
	$numKids = $_POST['numKids'];
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	$foodStamps = $_POST['foodStamps'];
	
	// Problem children
	// A few issues to note, Updating to a Null value breaks SQL
	// Also, storing an email address without converting it to a string (as done below)
	// causes sql to break as well
	$email = "'" . $_POST['email'] . "'";
	$notes = "'" . $_POST['notes'] . "'";		// TODO: Make this safe
		
	// Set up server connection
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "foodpantry";

	// Create and check connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create insertion string
	$sql = "UPDATE Client SET
			numOfAdults = $numAdults, numOfKids = $numKids, phoneNumber = $phoneNo, foodStamps = $foodStamps,
			address = $address, zip = $zip, city = $city, state = $state, email = $email, notes = $notes
			WHERE clientID = $clientID";
	
	// Perform and test update
	if ($conn->query($sql) === TRUE) {
		// Update successful, Alert! - TODO: This doesn't fire
		echo "<script type='text/javascript'> alert('Update successful!'); </script>";
		
		// Close the Database
		closeDB($conn);
		
		// Go back to main admin client ops page
		header ("location: /RUMCPantry/ap_co1.html");
	}
	else {
			echo "sql error: " . mysqli_error($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update.", "red" );	
	}
	closeDB($conn);
}

else {
	echo "<script type='text/javascript'> alert('ERROR!'); </script>";
	header("location: /RUMCPantry/mainpage.html");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>