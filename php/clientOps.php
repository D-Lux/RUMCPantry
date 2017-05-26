<?php

include 'utilities.php';


if (isset($_POST['GoNewClient'])) {
	header ("location: /RUMCPantry/ap_co2.html");
}
elseif (isset($_GET['GoUpdateClient'])) {
	header ("location: /RUMCPantry/ap_co3.html?id=" . $_GET['id']);
}
// Submitting a new client
elseif(isset($_POST['submitClient']))
{
	// Required fields
	$clientFirstName = fixInput($_POST['clientFirstName']);
	$clientLastName = fixInput($_POST['clientLastName']);
	$numAdults = $_POST['numAdults'];
		
	// Optional fields
	$birthDate = $_POST['birthDate'];
	$numKids = $_POST['numKids'];
	$email = fixInput($_POST['email']);
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	$foodstamps = $_POST['foodstamps'];
		
	// Address fields
	//$addressStreet = fixInput($_POST['addressStreet']);
	$addressStreet = $_POST['addressStreet'];
	$addressCity = fixInput($_POST['addressCity']);
	$addressState = $_POST['addressState'];
	$addressZip = $_POST['addressZip'];
		
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
	$sql = "INSERT INTO Client (numOfAdults, NumOfKids, timestamp, email, phoneNumber, address, city, state, zip, foodStamps)
	VALUES ('$numAdults','$numKids',now(),'$email','$phoneNo','$addressStreet','$addressCity','$addressState','$addressZip', '$foodstamps')";
	
	// Perform and test insertion
	if ($conn->query($sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, birthDate, clientID, timestamp)
				VALUES ('$clientFirstName','$clientLastName', TRUE, '$birthDate', '$clientID', now())";
		if ($conn->query($sql) === TRUE) {
			// Successfully added client and family member (head of household) - go to update page
			// Pass along a 'new' parameter so we can display a special message (this is the same as update client page normally)
			// Pass along the clientID so we know which client to pull up on the update page
			closeDB($conn);
			header("location: /RUMCPantry/ap_co3.html?new=1&id=$clientID");
		}
		else {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to create.", "red" );	
		}
	} 
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to connect to database.", "red" );	
	}
	closeDB($conn);
}

// ***********************************
// Submitting a new family member
elseif(isset($_POST['submitMember']))
{
	debugEchoPOST();
	debugEchoGET();

	// Required fields
	$memberFirstName = fixInput($_POST['memberFirstName']);
	$memberLastName = fixInput($_POST['memberLastName']);
		
	// Optional fields
	$birthDate = $_POST['birthDate'];
	$notes = fixInput($_POST['notes']);
	
	// if this is true, we need to go through all other family members and set theirs to false
	$head=FALSE;
	if(isset($_POST['head'])) {
		$head=TRUE;
	}
	$clientID = $_POST['id'];
	
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

	// If this is the new head of household, set all others to false
	if ($head) {
		$sql = "UPDATE familyMember SET isHeadOfHousehold=0 WHERE clientID=$clientID";

		// Perform and test insertion
		if ($conn->query($sql) === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update head of household.", "red" );
		}		
	}
	
	// Create insertion string
	$sql = "INSERT INTO familyMember (firstName, lastName, isHeadOfHousehold, notes, birthdate, timestamp, clientID)
	VALUES ('$memberFirstName','$memberLastName','$head','$notes','$birthDate', now(), '$clientID')";
	
	// Perform and test insertion
	if ($conn->query($sql) === TRUE) {
		closeDB($conn);
		header("location: /RUMCPantry/ap_co3.html?id=$clientID");
	} 
	else {
		echo mysqli_errno($conn) . ": " . mysqli_error($conn). "\n";
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to insert family member.", "red" );	
	}

	$conn->close();
}

// ***********************************
// Updating a client
elseif(isset($_POST['UpdateClient']))
{
	// Address fields
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
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
	$email = makeString($_POST['email']);
	$notes = makeString($_POST['notes']);
		
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
			zip = $zip, state = $state, address = $address, city = $city, email = $email, notes = $notes
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

// ***********************************
// Updating a family member
elseif(isset($_POST['UpdateMember'])) /*when the button is pressed on post request*/
{
	// Address fields
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
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
	$email = makeString($_POST['email']);
	$notes = makeString($_POST['notes']);
		
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
			zip = $zip, state = $state, address = $address, city = $city, email = $email, notes = $notes
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
	header("location: /RUMCPantry/mainpage.html");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>