<?php

include 'utilities.php';
debugEchoPOST();debugEchoGET();
// Go to specific client/member pages based on buttons pressed
if (isset($_POST['GoNewClient'])) {
	header ("location: /RUMCPantry/ap_co2.php");
}
elseif (isset($_GET['GoUpdateClient'])) {
	header ("location: /RUMCPantry/ap_co3.php?id=" . $_GET['id']);
}
// Go to various family member functions
elseif (isset($_GET['GoUpdateMember'])) {
	header ("location: /RUMCPantry/ap_co5.php?memberID=" . $_GET['memberID'] . "&clientID=" . $_GET['clientID']);
}

// *******************************************************
// Start Client operations
// *******************************************************
// ************************************
// Submitting a new client
elseif(isset($_POST['submitClient']))
{
	echo "<h1>Attempting to create a new client</h1>";
	// Address fields
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	
	// Standard information
	$numAdults = $_POST['numAdults'];
	$numKids = $_POST['numKids'];
	$email = makeString($_POST['email']);
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	$foodStamps = $_POST['foodStamps'];
	
	// Family Member Fields
	$clientFirstName = makeString(fixInput($_POST['clientFirstName']));
	$clientLastName = makeString(fixInput($_POST['clientLastName']));
	$birthDate = makeString($_POST['birthDate']);

	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create insertion string
	$sql = "INSERT INTO Client 
			(numOfAdults, NumOfKids, timestamp, email, phoneNumber, 
				address, city, state, zip, foodStamps, isDeleted)
			VALUES 
			($numAdults,$numKids,now(),$email,$phoneNo,
				$address,$city,$state,$zip,$foodStamps, FALSE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, birthDate, clientID, timestamp, isDeleted)
				VALUES ($clientFirstName, $clientLastName, TRUE, $birthDate, $clientID, now(), FALSE)";
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			// Successfully added client and family member (head of household) - go to update page
			// Set a cookie so we can display a 'new' message
			createCookie("newClient", 1, 30);
			
			// Pass along the clientID so we know which client to pull up on the update page
			header("location: /RUMCPantry/ap_co3.php?id=$clientID");
		}
		else {
			// delete the blank client we just made
			$sql = "DELETE FROM Client
					WHERE clientID = $clientID";
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to create family member.", "red" );
			
			if (queryDB($conn, $sql) === FALSE) {
				// This is a very bad error (created a blank client and couldn't remove it)
				echoDivWithColor("<h1>VERY BAD ERROR</h1>Check with developer.", "red" );
			}	
		}
	} 
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to connect to database.", "red" );	
	}
	closeDB($conn);
}


// ***********************************
// Updating a client
elseif(isset($_POST['UpdateClient']))
{
	// Address fields
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	
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
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create insertion string
	$sql = "UPDATE Client SET
			numOfAdults = $numAdults, numOfKids = $numKids, phoneNumber = $phoneNo,
			foodStamps = $foodStamps, email = $email, notes = $notes, timestamp = now(),
			zip = $zip, state = $state, address = $address, city = $city
			WHERE clientID = $clientID";
	
	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		// Update successful, Alert! - TODO: This doesn't fire
		echo "<script type='text/javascript'> alert('Update successful!'); </script>";
		
		// Close the Database
		closeDB($conn);
		
		// Go back to main admin client ops page
		header ("location: /RUMCPantry/ap_co1.php");
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
	closeDB($conn);
}
// ***********************************
// Setting a client to 'inactive'
elseif(isset($_GET['InactiveClient']))
{
	debugEchoPOST();debugEchoGET();
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "UPDATE Client SET isDeleted=1 WHERE clientID=" . $_GET['id'];

	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		// Now set all of the family members inactive as well
		$sql = "UPDATE FamilyMember 
				SET isDeleted=1 
				WHERE clientID=" . $_GET['id'];

		// Perform and test update
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			header ("location: /RUMCPantry/ap_co1.php");
		}
		else {
			echo "sql error: " . mysqli_error($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to set family members inactive.", "red" );
		}
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to set client inactive.", "red" );	
	}
	
	closeDB($conn);
}

// *******************************************************
// Start Family Member operations
// *******************************************************
// ***********************************
// Submitting a new family member
elseif(isset($_POST['submitMember']))
{
	// Required fields
	$memberFirstName = makeString(fixInput($_POST['memberFirstName']));
	$memberLastName = makeString(fixInput($_POST['memberLastName']));
		
	// Optional fields
	$birthDate = makeString($_POST['birthDate']);
	$notes = makeString(fixInput($_POST['notes']));
	
	// if this is true, we need to go through all other family members and set theirs to false
	$head=FALSE;
	if(isset($_POST['head'])) {
		$head=TRUE;
	}
	$clientID = $_POST['id'];
	
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// If this is the new head of household, set all others to false
	if ($head) {
		$sql = "UPDATE familyMember SET isHeadOfHousehold=0 WHERE clientID=$clientID";

		// Perform and test insertion
		if (queryDB($conn, $sql) === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update head of household.", "red" );
		}		
	}
	$head = makeString($head);
	// Create insertion string
	$sql = "INSERT INTO familyMember (firstName, lastName, isHeadOfHousehold, notes, birthDate, timestamp, clientID, isDeleted)
	VALUES ($memberFirstName,$memberLastName,$head,$notes,$birthDate, now(), $clientID, FALSE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		header("location: /RUMCPantry/ap_co3.php?id=$clientID");
	} 
	else {
		echo mysqli_errno($conn) . ": " . mysqli_error($conn). "\n";
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to insert family member.", "red" );	
	}

	$conn->close();
}

// ***********************************
// Updating a family member
elseif(isset($_POST['UpdateMember']))
{
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	// Put all of the information into variables for easier handling in query
	$memberID = $_POST['memberID'];
	$clientID = $_POST['clientID'];
	$firstName = makeString(fixInput($_POST['memberFirstName']));
	$lastName = makeString(fixInput($_POST['memberLastName']));
	$notes = makeString(fixInput($_POST['notes']));
	$birthDate = makeString($_POST['birthDate']);
	
	// Head of household is the most tricky one here
	$head = false;
	if (isset($_POST['head'])) {
		$head = true;
	}
	
	// If this is the new head of household, set all others to false
	if ($head) {
		$sql = "UPDATE familyMember 
				SET isHeadOfHousehold=0 
				WHERE clientID=$clientID";

		// Perform and test update
		if (queryDB($conn, $sql) === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update head of household.", "red" );
		}		
	}
	// If this is not the head of the household, go through the family members and make sure there is one
	// If not, go ahead and set this one to be the head of the house
	// (prevents an issue where there is no head of household)
	else {
		// Find another family member who is the head of house
		$sql = "SELECT firstName FROM FamilyMember 
				WHERE FamilyMemberID != $memberID
				AND clientID = $clientID
				AND isHeadOfHousehold = 1";

		// Perform the query and check if it is empty
		$headQuery = queryDB($conn, $sql);
		if ( ( $headQuery==NULL ) || ( $headQuery->num_rows <= 0 ) ){
			// If we are empty, it means that this was the head of household, so we can't set head to false
			// Force it to true
			$head = TRUE;
		}	
	}
	
	// Generate the update statement
	$sql = "UPDATE FamilyMember SET
			firstName = $firstName, lastName = $lastName, isHeadOfHousehold = $head, 
			notes = $notes, birthDate = $birthDate, timestamp = now()
			WHERE FamilyMemberID = $memberID";
	
	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		// Close the Database
		closeDB($conn);
		
		// Go back to the client update page
		header ("location: /RUMCPantry/ap_co3.php?id=$clientID");
	}
	else {
			echo "sql error: " . mysqli_error($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update.", "red" );	
	}
	closeDB($conn);
}

// ***********************************
// Setting a family member to 'isDeleted'
elseif(isset($_GET['DeleteMember']))
{
	debugEchoPOST();debugEchoGET();
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "UPDATE FamilyMember 
			SET isDeleted=1, timestamp=now() 
			WHERE FamilyMemberID=" . $_GET['memberID'];

	// Perform and test update
	if ($conn->query($sql) === TRUE) {
		closeDB($conn);
		createCookie("DelFam", 1, 30);
		header ("location: /RUMCPantry/ap_co3.php?id=" . $_GET['clientID'] );
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
	
	closeDB($conn);
}
else {
	echo "<h1>Nothing was set</h1><br>";
	debugEchoPOST();debugEchoGET();
	header("location: /RUMCPantry/mainpage.php");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>