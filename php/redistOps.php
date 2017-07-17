<?php

include 'utilities.php';
debugEchoPOST();debugEchoGET();
// Go to specific client/member pages based on buttons pressed
if (isset($_POST['newRedist'])) {
	header ("location: /RUMCPantry/ap_ro3.php");
}
elseif (isset($_POST['updateRedist'])) {
	header ("location: /RUMCPantry/ap_ro4.php?id=" . $_POST['id']);
}


// *******************************************************
// Start Redistribution operations
// *******************************************************
// ************************************
// Submitting a new partner
elseif(isset($_POST['submitNewRedist']))
{
	echo "<h1>Attempting to create a new partner</h1>";

	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	$email = makeString($_POST['email']);
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	
	// Family Member Fields
	$clientFirstName = makeString("REDISTRIBUTION");
	$clientLastName = makeString(fixInput($_POST['partnerName']));

	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create insertion string
	$sql = "INSERT INTO Client 
			(numOfAdults, NumOfKids, timestamp, email, phoneNumber, 
				address, city, state, zip, foodStamps, isDeleted, redistribution)
			VALUES 
			(0,0,now(),$email,$phoneNo,
				$address,$city,$state,$zip, FALSE, FALSE, TRUE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, clientID, timestamp, isDeleted)
				VALUES ($clientFirstName, $clientLastName, TRUE, $clientID, now(), FALSE)";
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			createCookie("newPartner", 1, 30);
			header("location: /RUMCPantry/ap_ro4.php?id=$clientID");
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
				echoDivWithColor("<h1>VERY BAD ERROR</h1>Check with developer. - ID: " . $clientID, "red" );
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
// Updating a partner
elseif(isset($_POST['submitUpdateRedist'])) {
	// grab and fix post data
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	$partnerID = $_POST['partnerID'];
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	$email = makeString($_POST['email']);
	$notes = makeString($_POST['notes']);
	$partnerName = makeString(fixInput($_POST['partnerName']));
		
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create update string for basic data
	$dataUpdate =  "UPDATE Client 
					SET	phoneNumber=$phoneNo, email=$email, notes=$notes, timestamp=now(),
						zip=$zip, state=$state, address=$address, city=$city
					WHERE clientID = $partnerID";
	// Create update string for name data
	$nameUpdate =  "UPDATE FamilyMember
					SET FamilyMember.lastName=$partnerName, timestamp=now()
					WHERE clientID=$partnerID";
	
	// Perform and test updates
	if (queryDB($conn, $dataUpdate) === TRUE) {
		if (queryDB($conn, $nameUpdate) === TRUE) {
			closeDB($conn);
			createCookie("updatePartner", 1, 30);
			header("location: /RUMCPantry/ap_ro2.php");
		}
		else {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to update.", "red" );	
		}		
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
	closeDB($conn);
}

// ***********************************
// Setting a partner to 'isDeleted'
elseif(isset($_POST['deleteRedist'])) {
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	// Create 'delete' string
	$dataUpdate =  "UPDATE Client 
					SET	isDeleted=1
					WHERE clientID=" . $_POST['id'];
	
	// Perform and test deactivation
	if (queryDB($conn, $dataUpdate) === TRUE) {
		closeDB($conn);
		createCookie("partnerDeactivated", 1, 30);
		header("location: /RUMCPantry/ap_ro2.php");
	}
	else {
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to update.", "red" );	
	}
}
// ***********************************
// Setting a partner to 'isDeleted' FALSE (reactivating)
elseif(isset($_POST['activateRedist']))
{
	debugEchoPOST();debugEchoGET();
	// Set up server connection
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "UPDATE Client SET isDeleted=0 WHERE clientID=" . $_POST['id'];

	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		header ("location: /RUMCPantry/ap_ro2i.php");
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to set partner active.", "red" );	
	}
	
	closeDB($conn);
}
else {
	echo "<h1>Nothing was set</h1><br>";
	debugEchoPOST();debugEchoGET();
	//header("location: /RUMCPantry/mainpage.php");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>