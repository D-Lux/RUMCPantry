<?php

// **********************************************
// * Debug functions

function echoDivWithColor($message, $color){
    echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
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

// ***********************************************************
// * Database Access Functions

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

// *****************************************************************
// * Display and Query functions

// Fix input to prevent injection errors
function fixInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	// Escape any single-quotes we find for sql queries
	$data = str_replace ( "'", "''", $data);	
	return $data;
}

// This is for data lists to display names of objects with a single quote in the name
function displaySingleQuote($data) {
	return str_replace("'", "&#39", $data);
}
function revertSingleQuote($data) {
	return str_replace("'", "''", $data);	
}

// Made this function to turn data into a single-quote string for storing and viewing
function makeString($data) {
	return "'" . $data . "'";
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

function createDatalist($defaultVal, $listName, $tableName, $attributeName, $inputName, $hasDeletedAttribute) {
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
	$sql = "SELECT DISTINCT " . $attributeName .
			" FROM " .  $tableName ;//select distinct values from the collumn in this table
	if($hasDeletedAttribute) {
		$sql .= " WHERE isDeleted=0" ;//select distinct values from the collumn in this table
	}

	$result = mysql_query($sql);

	echo "<input list=$listName name=$inputName value=$defaultVal autocomplete='off'>";
	
	echo "<datalist id=$listName>"; //this id must be the same as the list = above
	
	while ($row = mysql_fetch_array($result)) {
		echo "<option value='" . $row[$attributeName] . "'>" . $row[$attributeName] . "</option>";
	}
	echo "</datalist>";
}

// **********************************************
// * Cookie functions
function createCookie($cookieName, $cookieValue, $duration) {
	setcookie($cookieName, $cookieValue, time() + $duration, "/");
}

function removeCookie($cookieName) {
	setcookie($cookieName, 0, time() -1, "/");
}

// ***********************************************************************
// * "Available" Client functions

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
				VALUES ('Available', 'Available', TRUE, $clientID, now(), FALSE)";
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
	switch(true) {
		case ($visitStatus == 0): return 'Available';
		case ($visitStatus == 100): return 'Assigned';
		case ($visitStatus == 101): return 'Assigned, Locked';
		case ($visitStatus == 200): return 'Active';
		case ($visitStatus >= 300 && $visitStatus < 400): return 'Arrived';
		case ($visitStatus >= 400 && $visitStatus < 500): return 'Processed';
		
		// special cases
		case ($visitStatus == 999): return 'Client did not show';
		
		default: return 'Status not recognized';
	}		
}

// Return the status # for a newly assigned appointment
function GetAssignedStatus() {
	return 100;
}
// Return the status # for an active appointment
function GetActiveStatus() {
	return 200;
}

function IsActiveAppointment($status) {
	return ( ($status >= 200) && ($status <= 400) );
}

function HighestActiveStatus() {
	return 400;
}

// **********************************************************
// * Decoder for family sizes

function familySizeDecoder($famSize){
	switch(true) {
		case ($famSize >= 0 && $famSize <= 2): return 'Small';
		case ($famSize >= 3 && $famSize <= 4): return 'Medium';
		case ($famSize >= 5): return 'Large';
		
		default: return 'WalkIn';
	}		
}

// ***********************************************************
// * Simple function to return the number of occurrences of a value in an array

function returnCountOfItem($item, $data) {
    $counts = array_count_values($data);
    return $counts[$item];
}


?>