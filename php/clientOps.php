<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
include 'utilities.php';
//debugEchoPOST();debugEchoGET();

// **************************************
// ** Function for toggling isDeleted flag for clients
function toggleClientActive($isDeleted=0) {
	if (!(ISSET($_GET['id']))) {
		die("Invalid!");
	}
	// Set up server connection
	$conn = connectDB();

  $loc = "location: " . $GLOBALS['basePath'] . "ap_co1.php";
	$loc .= ($isDeleted==0 ? "?ShowInactive=1" : ""); 
	// Create our update query, setting the flag appropriate
	$sql = "UPDATE client 
          SET isDeleted=" . $isDeleted . "
          WHERE clientID=" . $_GET['id'];

	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		// Now set all of the family members as well
		$sql = "UPDATE familymember 
				SET isDeleted=" . $isDeleted . "
				WHERE clientID=" . $_GET['id'];

		// Perform and test update
		if (queryDB($conn, $sql) === FALSE) {
			createCookie("clientDeleteFailBAD", 1, 30);
		}
	}
	else {
		createCookie("clientDeleteFail", 1, 30);
	}

	closeDB($conn);
  header ($loc);
}

// Go to specific client/member pages based on buttons pressed
if (isset($_POST['GoNewClient'])) {
	header ("location: " . $basePath . "ap_co2.php");
}
elseif (isset($_GET['GoUpdateClient'])) {
	header ("location: " . $basePath . "ap_co3.php?id=" . $_GET['id']);
}
// Go to various family member functions
elseif (isset($_GET['GoUpdateMember'])) {
	header ("location: " . $basePath . "ap_co5.php?memberID=" . $_GET['memberID'] . "&clientID=" . $_GET['clientID']);
}

// *******************************************************
// Start Client operations
// *******************************************************
// ***********************************
// Setting a client to 'inactive'
elseif(isset($_GET['InactiveClient'])){
	toggleClientActive(1);
}
// ***********************************
// Setting a client to 'active'
elseif(isset($_GET['ActiveClient'])) {
	toggleClientActive(0);
}

// ************************************
// Submitting a new client
elseif(isset($_POST['submitClient'])) {
  $error    = '';
  $clientID = 0;
  // Address fields
	$address  = fixInput($_POST['addressStreet']);
	$city     = fixInput($_POST['addressCity']);
	$state    = $_POST['addressState'];
	$zip      = fixInput($_POST['addressZip']);
	
	// Standard information
	$numAdults  = $_POST['numAdults'];
	$numKids    = $_POST['numKids'];
	$email      = fixInput($_POST['email']);
	$phoneNo    = $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];
	$foodStamps = $_POST['foodStamps'];
	$clientType = $_POST['clientType'];
	
	// Family Member Fields
	$clientFirstName  = fixInput($_POST['clientFirstName']);
	$clientLastName   = fixInput($_POST['clientLastName']);
	$birthDate        = $_POST['birthDate'];
	$gender           = $_POST['gender'];

  $pets = "";
  if (isset($_POST['pets'])) {
    $pets = implode("", $_POST['pets']);
  }

	// *************************
	// * Validate form
	if ((empty($numAdults)) || ($numAdults <= 0)) {
    $error .= "<p>Clients must have at least one adult.</p>";
  }
  if ((empty($clientFirstName)) || (empty($clientLastName))) {
    $error .= "<p>Clients must have a full name.</p>";
  }
  if ((empty($birthDate)) || (empty($clientLastName))) {
    $error .= "<p>Birthdate is required.</p>";
  }

  // If there were no form errors, create the client
  if ($error == '') {
    // Set up server connection
    $conn = connectDB();

    // Create insertion string
    $sql = "INSERT INTO client 
        (numOfAdults, NumOfKids, email, phoneNumber, 
          address, city, state, zip, foodStamps, clientType, pets,
          isDeleted, redistribution)
        VALUES 
        ({$numAdults}, {$numKids}, '{$email}', '{$phoneNo}',
          '{$address}', '{$city}', '{$state}', '{$zip}', {$foodStamps}, {$clientType}, '{$pets}',
          FALSE, FALSE)";
    
    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      // Get the ID Key of the client to create the family member
      $clientID = $conn->insert_id;
      // Create the insert string and perform the insertion
      $sql = "INSERT INTO familymember 
          (firstName, lastName, isHeadOfHousehold, birthDate, clientID, gender, isDeleted)
          VALUES ('{$clientFirstName}', '{$clientLastName}', TRUE, '{$birthDate}', {$clientID}, {$gender}, FALSE)";
      if (queryDB($conn, $sql) === FALSE) {
        $error = "There was an error adding the family member to the database (check with the programmer)<br>Query: " . $sql . "<br>Error: " . sqlError($conn) ;
        $sql = "DELETE FROM client
                WHERE clientID = $clientID";
        queryDB($conn, $sql);
      }
    } 
    else {
      $error = "There was an error adding the client to the database (Check with the programmer)<br>Query: " . $sql . "<br>Error: " . sqlError($conn) ;
    }
    closeDB($conn);
  }
  die( json_encode(array("error" => $error, "id" => $clientID)));
}


// ***********************************
// Updating a client
elseif(isset($_POST['UpdateClient'])) {
  $error    = '';
  $clientID = $_POST['UpdateClient'];
  // Address fields
	$address  = fixInput($_POST['addressStreet']);
	$city     = fixInput($_POST['addressCity']);
	$state    = $_POST['addressState'];
	$zip      = fixInput($_POST['addressZip']);
	
	// Standard information
	$numAdults  = $_POST['numAdults'];
	$numKids    = $_POST['numKids'];
	$email      = fixInput($_POST['email']);
	$phoneNo    = $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];
	$foodStamps = $_POST['foodStamps'];
	$clientType = $_POST['clientType'];
  $notes      = empty($_POST['notes']) ? " " : fixInput($_POST['notes']);

  $pets = "";
  if (isset($_POST['pets'])) {
    $pets = implode("", $_POST['pets']);
  }

	// *************************
	// * Validate form
	if ((empty($numAdults)) || ($numAdults <= 0)) {
    $error .= "<p>Clients must have at least one adult.</p>";
  }

  // If there were no form errors, create the client
  if ($error == '') {
    // Set up server connection
    $conn = connectDB();
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 

    // Create insertion string
    $sql = "UPDATE client SET
        numOfAdults = {$numAdults}, numOfKids = {$numKids}, phoneNumber = '{$phoneNo}',
        foodStamps = {$foodStamps}, email = '{$email}', notes = '{$notes}', timestamp = now(), pets = '{$pets}',
        zip = '{$zip}', state = '{$state}', address = '{$address}', city = '{$city}', clientType = {$clientType}
        WHERE clientID = {$clientID}";
    
    // Perform and test update
    if (queryDB($conn, $sql) === FALSE) {
      $error = "There was an error updating<br>Query: " . $sql . "<br>Error: " . sqlError($conn);
    }
    closeDB($conn);
  }
  die( json_encode(array("error" => $error)));
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
	$gender = $_POST['gender'];
	$notes = makeString(fixInput($_POST['notes']));
	
	// if this is true, we need to go through all other family members and set theirs to false
	$head=FALSE;
	if(isset($_POST['head'])) {
		$head=TRUE;
	}
	$clientID = $_POST['id'];
	
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// If this is the new head of household, set all others to false
	if ($head) {
		$sql = "UPDATE familymember SET isHeadOfHousehold=0 WHERE clientID=$clientID";

		// Perform and test insertion
		if (queryDB($conn, $sql) === FALSE) {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error, failed to update head of household.", "red" );
		}		
	}
	$head = makeString($head);
	// Create insertion string
	$sql = "INSERT INTO familymember 
			(firstName, lastName, isHeadOfHousehold, notes, birthDate, gender,
			 timestamp, clientID, isDeleted)
			VALUES 
			($memberFirstName,$memberLastName,$head,$notes,$birthDate, $gender,
			 now(), $clientID, FALSE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		redirectPage("ap_co3.php?id=$clientID");
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
	$conn = connectDB();
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
	$gender = $_POST['gender'];
	
	// Head of household is the most tricky one here
	$head = 0;
	if (isset($_POST['head'])) {
		$head = 1;
	}
	
	// If this is the new head of household, set all others to false
	if ($head) {
		$sql = "UPDATE familymember 
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
		$sql = "SELECT firstName FROM familymember 
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
	$sql = "UPDATE familymember SET
			firstName = $firstName, lastName = $lastName, isHeadOfHousehold = $head, 
			notes = $notes, birthDate = $birthDate, gender = $gender, timestamp = now()
			WHERE FamilyMemberID = $memberID";
	
	// Perform and test update
	if (queryDB($conn, $sql) === TRUE) {
		// Close the Database
		closeDB($conn);
		
		// Go back to the client update page
		header ("location: " . $basePath . "ap_co3.php?id=$clientID");
	}
	else {
			echo "sql error (update): " . mysqli_error($conn);
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
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "UPDATE familymember 
			SET isDeleted=1, timestamp=now() 
			WHERE FamilyMemberID=" . $_GET['memberID'];

	// Perform and test update
	if ($conn->query($sql) === TRUE) {
		closeDB($conn);
		createCookie("DelFam", 1, 30);
		header ("location: " . $basePath . "ap_co3.php?id=" . $_GET['clientID'] );
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
	redirectPage("mainpage.php");
}

?>