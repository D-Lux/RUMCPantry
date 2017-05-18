<?php

include 'utilities.php';

if(isset($_POST['submit'])) /*when the button is pressed on post request*/
{
	// Required fields
	$clientFirstName = fixInput($_POST['clientFirstName']);
	$clientLastName = fixInput($_POST['clientLastName']);
	$numAdults = $_POST['numAdults'];
		
	// Optional fields
	$birthDate = $_POST['birthDate'];
	$numKids = $_POST['numKids'];
	$email = fixInput($_POST['email']);
	$phoneNo = $_POST['phoneNo'];
		
	// Address fields
	$addressStreet = fixInput($_POST['addressStreet']);
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
	$sql = "INSERT INTO Client (numOfAdults, NumOfKids, timestamp, email, phoneNumber, address, city, state, zip)
	VALUES ('$numAdults','$numKids',now(),'$email','$phoneNo','$addressStreet','$addressCity','$addressState','$addressZip')";
	
	//TODO: go to client info update page with this client selected
	
	// Perform and test insertion
	if ($conn->query($sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, birthDate, $clientID, timestamp)
				VALUES ('$clientFirstName','$clientLastName', TRUE, '$birthDate', now())";
		if ($conn->query($sql) === TRUE) {
			// Successfully added client and family member (head of household)
			//TODO Change button to go to update client info page
			echoDivWithColor( '<button onclick="goToUpdate()">View Details</button>', "green");
			echoDivWithColor("Client added successfully", "green" );
			echoDivWithColor("First Name: $clientFirstName", "green" );
			echoDivWithColor("Last Name: $clientLastName", "green" );
			echoDivWithColor("Birth Date: $birthDate", "green" );
			
			echoDivWithColor("Email: $email", "green" );
			echoDivWithColor("Phone Number: $phoneNo", "green" );
			
			echoDivWithColor("Number of Adults: $numAdults", "green" );
			echoDivWithColor("Number of Kids: $numKids", "green" );
			
			echoDivWithColor("Address: $addressStreet <br>$addressCity, $addressState $addressZip", "green" );
		}   
	} 
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to connect to database.", "red" );	
	}

	$conn->close();
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}

function goToUpdate() {
    window.location.href = "/RUMCPantry/ap_co3.html";
}
   </script>