<?php

// **********************************************
// * CSS

echo "<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Community Food Pantry</title>
		<script src='js/utilities.js'></script>
		<link rel='stylesheet' href='css/style.css'>
	</head>
	<body>
		<h1>Roselle United Methodist Community Food Pantry</h1>";


// **********************************************
// * Debug functions

function echoDivWithColor($message, $color){
   // echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
	echo  '<div>';
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

// ************************************************************
// ** Setting default timezone
date_default_timezone_set('America/Chicago');


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

	echo "<input list=$listName name=$inputName value='$defaultVal' autocomplete='off'>";

	echo "<datalist id=$listName>"; //this id must be the same as the list = above
	
	while ($row = mysql_fetch_array($result)) {
		echo "<option value='" . $row[$attributeName] . "' >" . $row[$attributeName] . "</option>";
	}
	echo "</datalist>";
}

function createDatalist_i($defaultVal, $listName, $tableName, $attributeName, $inputName, $hasDeletedAttribute) {
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
	
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT DISTINCT " . $attributeName . "
			FROM " . $tableName;
	
	if ($hasDeletedAttribute) {
		$sql .= " WHERE isDeleted=0";
	}
	
	$sqlQuery = queryDB($conn, $sql);

	echo "<input type='text' id='" . $inputName . "' list='" . $listName . "' 
			value='" . $defaultVal . "' name='" . $inputName . "'>";
	
	echo "<datalist id='" . $listName . "'>";
	while($row = sqlFetch($sqlQuery)) {
		echo "<option value='" . $row[$attributeName] . "' >" . $row[$attributeName] . "</option>";
	}
	echo "</datalist>";
	closeDB($conn);
	
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
	$sql = "INSERT INTO Client (numOfAdults, NumOfKids, timestamp, isDeleted, redistribution)
			VALUES ('0','0',now(), FALSE, FALSE)";
	
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
// SV = Status Value
define("SV_AVAILABLE", 0);
define("SV_ASSIGNED", 100);
define("SV_LOCKED", 101);
define("SV_ACTIVE", 200);
define("SV_ARRIVED_LOW", 300);
define("SV_ARRIVED_HIGH", 399);
define("SV_ARRIVED_TO_PROCESSED", 100);
define("SV_PRINTED_LOW", 400);
define("SV_PRINTED_HIGH", 499);
define("SV_PROCESSED_LOW", 500);
define("SV_PROCESSED_HIGH", 599);
define("SV_CANCELED", 888);
define("SV_NO_SHOW", 999);
define("SV_REDISTRIBUTION", 9999);

function visitStatusDecoder($visitStatus){
	switch(true) {
		case ($visitStatus == SV_AVAILABLE): return 'Available';
		case ($visitStatus == SV_ASSIGNED): return 'Assigned';
		case ($visitStatus == SV_LOCKED): return 'Assigned, Locked';
		case ($visitStatus == SV_ACTIVE): return 'Active';
		case ($visitStatus >= SV_ARRIVED_LOW && $visitStatus < SV_ARRIVED_HIGH): return 'Arrived';
		case ($visitStatus >= SV_PRINTED_LOW && $visitStatus < SV_PRINTED_HIGH): return 'Printed';
		case ($visitStatus >= SV_PROCESSED_LOW && $visitStatus < SV_PROCESSED_HIGH): return 'Processed';
		
		// special cases
		case ($visitStatus == SV_CANCELED): return 'Client canceled';
		case ($visitStatus == SV_NO_SHOW): return 'Client did not show';
		
		case ($visitStatus == SV_REDISTRIBUTION): return 'Redistribution Order';
		
		default: return 'Status not recognized';
	}		
}

// Return the status # for available
function GetAvailableStatus() {
	return SV_AVAILABLE;
}
// Return the status # for lowest arrived
function GetArrivedLow() {
	return SV_ARRIVED_LOW;
}
// Return the status # for highest arrived
function GetArrivedHigh() {
	return SV_ARRIVED_HIGH;
}
// Return the status # for lowest printed
function GetPrintedLow() {
	return SV_PRINTED_LOW;
}
// Return the status # for highest printed
function GetPrintedHigh() {
	return SV_PRINTED_HIGH;
}
// Return the status # for lowest processed
function GetProcessedLow() {
	return SV_PROCESSED_LOW;
}
// Return the status # for highest processed
function GetProcessedHigh() {
	return SV_PROCESSED_HIGH;
}

// Return the status # for a newly assigned appointment
function GetAssignedStatus() {
	return SV_ASSIGNED;
}
// Return the status # for an active appointment
function GetActiveStatus() {
	return SV_ACTIVE;
}
// Returns a bool indicating whether the appointment is active or not
function IsActiveAppointment($status) {
	return ( ($status >= SV_ACTIVE) && ($status <= SV_ARRIVED_HIGH) );
}
// Returns the highest active status value
function GetHighestActiveStatus() {
	return SV_ARRIVED_HIGH;
}

// Return an appropriate status value to mark the status completed
function ReturnProcessedStatus($status) {
	if ($status >= SV_ARRIVED_LOW && $status <= SV_ARRIVED_HIGH) {
		return ($status + SV_ARRIVED_TO_PROCESSED);
	}
	else {
		return SV_PROCESSED_LOW;
	}
}

// Returns the Redistribution stats number
function GetRedistributionStatus() {
	return SV_REDISTRIBUTION;
}

// **********************************************************
// * Decoder for family sizes
define("FAMILY_SIZE_SM_LOW", 1);
define("FAMILY_SIZE_SM_HIGH", 2);
define("FAMILY_SIZE_MED_LOW", 3);
define("FAMILY_SIZE_MED_HIGH", 4);
define("FAMILY_SIZE_HIGH_THRESHOLD", 5);


function familySizeDecoder($famSize){
	switch(true) {
		case ($famSize >= FAMILY_SIZE_SM_LOW && $famSize <= FAMILY_SIZE_SM_HIGH): return 'Small';
		case ($famSize >= FAMILY_SIZE_MED_LOW && $famSize <= FAMILY_SIZE_MED_HIGH): return 'Medium';
		case ($famSize >= FAMILY_SIZE_HIGH_THRESHOLD): return 'Large';
		
		default: return 'Small';
	}		
}

// ***********************************************************
// * Simple function to return the number of occurrences of a value in an array

function returnCountOfItem($item, $data) {
    $counts = array_count_values($data);
    return $counts[$item];
}

// ***********************************************************
// * Returns a string of a time value of the form hh:mm:ss to hh:mm

function returnTime($time) {
	return DateTime::createFromFormat('g:i:s',$time)->format('g:i a');
}

// ************************************************************
// ** Decoder for client type

define("CLIENT_TYPE_UNKNOWN", 0);
define("CLIENT_TYPE_CONSTIT", 1);
define("CLIENT_TYPE_MEMBER", 2);
define("CLIENT_TYPE_RESIDENT", 3);

function clientTypeDecoder($clientType){
	switch(true) {
		case ($clientType == CLIENT_TYPE_UNKNOWN): return 'Unknown';
		case ($clientType == CLIENT_TYPE_CONSTIT): return 'Constituent';
		case ($clientType == CLIENT_TYPE_MEMBER): return 'Member';
		case ($clientType == CLIENT_TYPE_RESIDENT): return 'Resident';
		
		default: return 'Unknown';
	}		
}

// ************************************************************
// ** Decoder for gender type

define("GENDER_UNKNOWN", 0);
define("GENDER_MALE", -1);
define("GENDER_FEMALE", 1);

function genderDecoder($gender){
	switch(true) {
		case ($gender == GENDER_UNKNOWN): return '-';
		case ($gender == GENDER_MALE): return 'Male';
		case ($gender == GENDER_FEMALE): return 'Female';
		
		default: return '-';
	}		
}
function genderDecoderShort($gender){
	switch(true) {
		case ($gender == GENDER_UNKNOWN): return '-';
		case ($gender == GENDER_MALE): return 'M';
		case ($gender == GENDER_FEMALE): return 'F';
		
		default: return '-';
	}		
}

// ************************************************************
// ** Decoder for aisle (Takes in a number and returns a letter (ascii code)

function aisleDecoder($aisle){
	if ($aisle >= 65) {
		return chr($aisle);
	}
	return chr($aisle + 65);
}

// ************************************************************
// ** Disabled categories for walkIns

function showCategory($walkIn, $category){
	// If we need to make more in the future, just add them here
	$DisabledCategories = array("medicine","specials","redistribution");

	if (!$walkIn) {
		return true;
	}	
	if (in_array(strtolower($category), $DisabledCategories)) {
		return false;
	}
	return true;
}


?>