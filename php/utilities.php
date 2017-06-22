<?php

function echoDivWithColor($message, $color)
{
    echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}

// Creating a global connection function so that if we ever need to change the database information
// it's all in one place
$connectionActive = false;
function createPantryDatabaseConnection() {
	// Set up server connection
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "foodpantry";

	// Create and check connection
	if (!$GLOBALS['connectionActive']){
		$GLOBALS['connectionActive'] = true;
		return( new mysqli($servername, $username, $password, $dbname) );
	}
}
// This takes a database connection variable and closes it only if it is open still
function closeDB($conn){
	if ($GLOBALS['connectionActive']) {
		$GLOBALS['connectionActive'] = false;
		if ($conn->ping()) {
			$conn->close();
		}
	}
}
// This is just for uniformity, as there is an OO way to query a database and a function call way
function queryDB($conn, $query) {
	return mysqli_query($conn, $query);
	// return $conn->$query;
}
// Again for uniformity
function sqlFetch($queryResult) {
	return (mysqli_fetch_assoc($queryResult));
	// return ($queryResult->fetch_assoc());
}


function debugEchoPOST() {
	echo '<b>POST:</b><pre>';
	var_dump($_POST);
	echo '</pre>';
}

function debugEchoGET() {
	echo '<b>GET:</b><pre>';
	var_dump($_GET);
	echo '</pre>';
}


function fixInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Made this function to turn data into a single-quote string for storing and viewing
function makeString($data) {
	return "'" . $data . "'";
}

function createCookie($cookieName, $cookieValue, $duration) {
	setcookie($cookieName, $cookieValue, time() + $duration, "/");
}

function removeCookie($cookieName) {
	setcookie($cookieName, 0, time() -1, "/");
}

// Displays a phone number as expected (###)-###-####
// If it is not 10 digits long, just return as is
function displayPhoneNo($data) {
	$len = strlen((string)$data);
	if($len == 10) {
		$firstThree = substr($data, 0, 3);
		$middleThree = substr($data, 3, 3);
		$finalFour = substr($data, 6, 4);
		return '(' . $firstThree . ')-' . $middleThree . '-' . $finalFour;
	} else {
		return $data;
	}
	
}

// Takes in a string and strips all spaces, dashes and non-integers and return the first 10 digits
// Inside single quotes to be stored properly
function storePhoneNo($data) {
	return "'" . substr((preg_replace('/[^0-9]/','',$data)), 0, 10) . "'";
}

function createDatalist($defaultVal, $listName, $tableName, $attributeName, $inputName ,$hasDeletedAttribute)
{
	/*
	argument explanations:
	$defaultVal - default value you would like to appear in the datalist 
	$listName - Name of the list make plural (ex categories or itemNames)
	$tableName - table you wish to pull the attribute from (ex item)
	$attributeName - name of the attribute (ex displayName)
	$inputName - the name of the actual input, this is what a post request can grab
	$hasDeletedAttribute - whether the isDeleted attribute is in the table or not, this will allow it to filter
		out all that has been deleted.
	*/
	
            $servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "foodpantry";
	/* previous lines set up the strings for connextion*/

	mysql_connect($servername, $username, $password);
	mysql_select_db($dbname);
	//standard DB stuff up to here
	if($hasDeletedAttribute == true) {
		$sql = "SELECT DISTINCT " . $attributeName .
				" FROM " .  $tableName . 
				" WHERE isDeleted=0" ;//select distinct values from the collumn in this table
	}
	else {
		$sql = "SELECT DISTINCT " . $attributeName .
				" FROM " .  $tableName ;//select distinct values from the collumn in this table
	
	}

	$result = mysql_query($sql);

	echo "<input list=$listName name=$inputName value=$defaultVal >";
	
	echo "<datalist id=$listName>"; //this id must be the same as the list = above
	
	while ($row = mysql_fetch_array($result)) {
		echo "<option value='" . $row[$attributeName] . "'>" . $row[$attributeName] . "</option>";
	}
	echo "</datalist>";
}

// We need a custom client named 'available' to put into invoices that don't have real clients yet
// This function will check the database for this client, and create it if it doesn't exist yet
// Returns the appropriate clientID
function getAvailableClient() {
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$clientName = makeString("Available");
	
	// Find the 'available' client
	$sql = "SELECT FamilyMember.clientID as id
			FROM FamilyMember
			WHERE FamilyMember.firstName = " . $clientName . " 
			AND FamilyMember.lastName = " . $clientName;
	
	$availableClient = queryDB($conn, $sql);
	closeDB($conn);
	
	// If we didn't get a match, we need to create the 'Available' client
	if ( $availableClient==null || $availableClient->num_rows <= 0 ) {
		return createAvailableClient();
	}
	// If we found a match, close the database and return the client ID
	else {
		$getID = $availableClient->fetch_assoc();
		return $getID['id'];
	}
}

function createAvailableClient(){
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Create insertion string
	$sql = "INSERT INTO Client (numOfAdults, NumOfKids, timestamp, isDeleted)
			VALUES ('0','0',now(), FALSE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO FamilyMember 
				(firstName, lastName, isHeadOfHousehold, clientID, timestamp, isDeleted)
				VALUES (" . $clientName . ", " . $clientName . ", TRUE, $clientID, now(), FALSE)";
		if (queryDB($conn, $sql) === TRUE) {
			// We've successfully made the 'Available' client
			closeDB($conn);
			return $clientID;
		}
		else {
			$sql = "DELETE FROM Client
					WHERE clientID = $clientID";
			queryDB($conn, $sql);
			closeDB($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to create family member.", "red" );	
		}
	} 
	else {
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to connect to database.", "red" );	
	}
}

// **********************************************************
// * Status decoder for invoices

function visitStatusDecoder($visitStatus){
	switch($visitStatus) {
		case 0: return 'Available';
		case 1: return 'Assigned, future event';
		case 2: return 'Day of, not arrived';
		case 3: return 'Day of, arrived';
		case 4: return 'Day of, order form completed';
		case 5: return 'Day of, order form being processed';
		case 6: return 'Day of, order form processed';
		case 7: return 'Post visit, success';
		
		// special cases
		case 99: return 'Client did not show';
		
		default: return 'Status not recognized';
	}		
}


?>