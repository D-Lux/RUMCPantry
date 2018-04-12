<?php
session_start();

if (!isset($_SESSION['perms'])) {
  $_SESSION['perms'] = 0;
}
$basePath = "/";

if (strpos($_SERVER['REQUEST_URI'], "RUMCPantry")) {
  $basePath = "/RUMCPantry/";
}

// **********************************************
// * Handle redirect
function redirectPage($address) {
    echo "<script type='text/javascript'>window.top.location='" . $GLOBALS['basePath'] . $address . "';</script>"; 
    die();
}

// **********************************************
// * Debug functions

// TODO: Find all of these and replace with proper error messages for users
function echoDivWithColor($message, $color){
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
function debugEchoVar($v) {
	echo '<pre>';
	var_dump($v);
	echo '</pre>';
}
function decodeJsonError() {
  switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo ' - No errors';
    break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
    break;
    default:
        echo ' - Unknown error';
    break;
  }
}
// ************************************************************
// ** Setting default timezone
date_default_timezone_set('America/Chicago');


// ***********************************************************
// * Database Access Functions

// Creating a global connection function so that if we ever need to change the database information
// it's all in one place
$connectionActive = false;
function connectTestDB() {
  $servername = "server902.webhostingpad.com";
  $username   = "roselleu_fpadmin";
  $password   = "Luke3:11eggsontop";
  $dbname     = "roselleu_testpantry";

	// Create and check connection
	if (!$GLOBALS['connectionActive']){
		$GLOBALS['connectionActive'] = true;
		return( new mysqli($servername, $username, $password, $dbname) );
	}
}
function connectHomeDB() {
  $servername = "127.0.0.1";
  $username   = "root";
  $password   = "";
  $dbname     = "foodpantry";
 
	// Create and check connection
	if (!$GLOBALS['connectionActive']){
		$GLOBALS['connectionActive'] = true;
		return( new mysqli($servername, $username, $password, $dbname) );
	}
}
function connectDB() {
  	//Set up server connection
	 // $servername = "192.168.0.23";
	 // $username   = "root";
	 // $password   = "lgh598usa15";
	 // $dbname     = "foodpantry";
   
  $servername = "server902.webhostingpad.com";
	$username   = "roselleu_fpadmin";
	$password   = "Luke3:11eggsontop";
	$dbname     = "roselleu_foodpantry";
  
  if ($_SESSION['perms'] == 100 || $_SESSION['perms'] == 2 ) {
    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "";
    $dbname     = "foodpantry";
  }
  if ($_SESSION['perms'] == 101 || $_SESSION['perms'] == 3 ) {
    $servername = "server902.webhostingpad.com";
    $username   = "roselleu_fpadmin";
    $password   = "Luke3:11eggsontop";
    $dbname     = "roselleu_testpantry";
  }
  


	// Create and check connection
	if (!$GLOBALS['connectionActive']){
		$GLOBALS['connectionActive'] = true;
		return( new mysqli($servername, $username, $password, $dbname) );
	}
}
// This takes a database connection variable and closes it only if it is open still
function closeDB($conn){
	if ($GLOBALS['connectionActive']) {
    $conn->close();
		$GLOBALS['connectionActive'] = false;
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
function returnAssocArray($queryResult) {
	$returnArr = [];
  //if (mysqli_num_rows($queryResult) > 0) {
	if (($queryResult) && (mysqli_num_rows($queryResult) > 0)) {
    while( $row = sqlFetch($queryResult) ) {
      $returnArr[] = $row;
    }
    return $returnArr;
  }
  return false;
}

function runQuery($conn, $query) {
  return returnAssocArray(queryDB($conn, $query));
}

function runQueryForOne($conn, $query) {
  $queryResult = returnAssocArray(queryDB($conn, $query));
  if (is_array($queryResult)) {
    return current($queryResult);
  }
  return false;
}

function sqlError($conn) {
  return ( mysqli_error($conn) );
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
	if ($data === null) {
		return "-";
	}
	$len = strlen((string)$data);
	if($len == 10) {
		$firstThree = substr($data, 0, 3);
		$middleThree = substr($data, 3, 3);
		$finalFour = substr($data, 6, 4);
		return '(' . $firstThree . ') ' . $middleThree . '-' . $finalFour;
	} else {
		return $data;
	}
}

// Takes in a string and strips all spaces, dashes and non-integers and return the first 10 digits
// Inside single quotes to be stored properly
function storePhoneNo($data) {
	return "'" . substr((preg_replace('/[^0-9]/','',$data)), 0, 10) . "'";
}

// Truncate output to a specified number of characters
function displayForTable($item, $length, $cutLength=2) {
	if ( (! (is_string($item))) || ($item == NULL) ) {
		return "-";
	}
	if (strlen($item) > $length ) {
		return stripslashes((substr($item, 0, ($length - $cutLength)) . "..."));
	}
	return stripslashes($item);
}

function hashPassword($pw) {
  return password_hash($pw, PASSWORD_BCRYPT, ['cost' => 8]);
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
  
  $defaultVal = htmlspecialchars_decode($defaultVal);

	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT DISTINCT " . $attributeName . "
			FROM " . $tableName;

	if ($hasDeletedAttribute) {
		$sql .= " WHERE isDeleted=0";
	}

	$sqlQuery = queryDB($conn, $sql);

  $defaultVal = htmlspecialchars($defaultVal, ENT_QUOTES);
  
	echo "<input type='text' id='" . $inputName . "' list='" . $listName . "'
			value='" . $defaultVal . "' name='" . $inputName . "'>";

	echo "<datalist id='" . $listName . "'>";
	while($row = sqlFetch($sqlQuery)) {
		echo "<option value='" . $row[$attributeName] . "' >" . $row[$attributeName] . "</option>";
	}
	echo "</datalist>";
	closeDB($conn);

}

// Requires passing an open database connection and an SQL query
// Returns a single data point with the indicated keyName
function getSingleDataPoint($sql, $conn, $keyName) {
	$result = queryDB($conn, $sql);
	if ($result!=null && $result->num_rows > 0) {
		$queryData = sqlFetch($result);
		return $queryData[$keyName];
	}
	else {
		return false;
	}
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
	$conn = connectDB();

	$clientName = makeString("Available");

	// Find the 'available' client FROM FamilyMember fm
	$sql = "SELECT fm.clientID as id
			FROM familymember fm
			WHERE fm.firstName = " . $clientName . "
			AND fm.lastName = " . $clientName;

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
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Create insertion string
	$sql = "INSERT INTO client (numOfAdults, NumOfKids, timestamp, isDeleted, redistribution)
			VALUES ('0','0',now(), FALSE, FALSE)";

	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO familymember
				(firstName, lastName, isHeadOfHousehold, clientID, timestamp, isDeleted)
				VALUES ('Available', 'Available', TRUE, $clientID, now(), FALSE)";
		if (queryDB($conn, $sql) === TRUE) {
			// We've successfully made the 'Available' client
			closeDB($conn);
			return $clientID;
		}
		else {
			$sql = "DELETE FROM client
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
define("SV_READY_TO_REVIEW_LOW", 400);
define("SV_READY_TO_REVIEW_HIGH", 499);
define("SV_READY_TO_PRINT_LOW", 500);
define("SV_READY_TO_PRINT_HIGH", 599);
define("SV_PRINTED_LOW", 600);
define("SV_PRINTED_HIGH", 699);
define("SV_COMPLETED", 700);
define("SV_ADVANCE_STATUS", 100);

define("SV_PARTIAL_COMPLETION", 996);
define("SV_BAD_DOCUMENTATION", 997);
define("SV_CANCELED", 998);
define("SV_NO_SHOW", 999);

define("SV_REDISTRIBUTION", 9999);

function visitStatusDecoder($visitStatus){
	switch(true) {
		case ($visitStatus == SV_AVAILABLE): return 'Available';
		case ($visitStatus == SV_ASSIGNED): return 'Assigned';
		case ($visitStatus == SV_LOCKED): return 'Assigned, Locked';
		case ($visitStatus == SV_ACTIVE): return 'Active';
		case ($visitStatus >= SV_ARRIVED_LOW && $visitStatus < SV_ARRIVED_HIGH): return 'Arrived';
		case ($visitStatus >= SV_READY_TO_REVIEW_LOW && $visitStatus < SV_READY_TO_REVIEW_HIGH): return 'Ready for review';
		case ($visitStatus >= SV_READY_TO_PRINT_LOW && $visitStatus < SV_READY_TO_PRINT_HIGH): return 'Ready to print';
		case ($visitStatus >= SV_PRINTED_LOW && $visitStatus < SV_PRINTED_HIGH): return 'Printed';
		case ($visitStatus == SV_COMPLETED): return 'Completed';

		// special cases
		case ($visitStatus == SV_PARTIAL_COMPLETION): return 'Partial completion';
		case ($visitStatus == SV_BAD_DOCUMENTATION): return 'Bad documentation';
		case ($visitStatus == SV_CANCELED): return 'Client canceled';
		case ($visitStatus == SV_NO_SHOW): return 'Client did not show';

		case ($visitStatus == SV_REDISTRIBUTION): return 'Reallocation order';

		default: return 'Status not recognized';
	}
}

// *****
// Basic functions to get the status definitions if needed
// Return the status # for bad documentation
function GetBadDocumentationStatus() {
	return SV_BAD_DOCUMENTATION;
}
// Return the status # for cancelled
function GetCanceledStatus() {
	return SV_CANCELED;
}
// Return the status # for no show
function GetNoShowStatus() {
	return SV_NO_SHOW;
}
// Return the status # for partial completion
function GetPartialCompletionStatus() {
	return SV_PARTIAL_COMPLETION;
}

// Return the status # for available
function GetAvailableStatus() {
	return SV_AVAILABLE;
}
// Return the status # for a newly assigned appointment
function GetAssignedStatus() {
	return SV_ASSIGNED;
}
// Return the status # for an active appointment
function GetActiveStatus() {
	return SV_ACTIVE;
}
// Return the highest # an order could be and still be considered active
function GetHighestActiveStatus() {
	return SV_READY_TO_PRINT_HIGH;
}


// Return the status # for lowest arrived
function GetArrivedLow() {
	return SV_ARRIVED_LOW;
}
// Return the status # for highest arrived
function GetArrivedHigh() {
	return SV_ARRIVED_HIGH;
}
// Return the status # for lowest ready to review
function GetReadyToReviewLow() {
	return SV_READY_TO_REVIEW_LOW;
}
// Return the status # for highest ready to review
function GetReadyToReviewHigh() {
	return SV_READY_TO_REVIEW_HIGH;
}
// Return the status # for lowest ready to print
function GetReadyToPrintLow() {
	return SV_READY_TO_PRINT_LOW;
}
// Return the status # for highest ready to print
function GetReadyToPrintHigh() {
	return SV_READY_TO_PRINT_HIGH;
}

// Return the status # for lowest printed
function GetPrintedLow() {
	return SV_PRINTED_LOW;
}
// Return the status # for highest printed
function GetPrintedHigh() {
	return SV_PRINTED_HIGH;
}
// Return the status # for Completed orders
function GetCompletedStatus() {
	return SV_COMPLETED;
}

// *******
// Returns the value of the passed status elevated to the next status
function AdvanceInvoiceStatus($status) {
	// Debug echo
	//echo "<script>window.alert('CurrStatus: " . $status . " Advancing by: " . SV_ADVANCE_STATUS . "');</script>";
	return ($status + SV_ADVANCE_STATUS);
}

// **********
// Function to test status
// Returns a bool indicating whether the appointment is active or not
function IsActiveAppointment($status) {
	return ( ($status == SV_ACTIVE ) || ($status == SV_LOCKED) ||
			( ($status >= SV_ARRIVED_LOW) && ($status <= SV_ARRIVED_HIGH) ));
}
// Returns a bool indicating whether the appointment is available for fillout
function IsReadyToCreateOrder($status) {
	return ( ($status == SV_LOCKED) ||
			 ( ($status >= SV_ARRIVED_LOW) && ($status <= SV_ARRIVED_HIGH) ));
}
// Returns a bool indicating whether the appointment is ready to be reviewed
function IsReadyToReview($status) {
	return ( ($status >= SV_READY_TO_REVIEW_LOW) && ($status <= SV_READY_TO_REVIEW_HIGH) );
}
// Returns a bool indicating whether the appointment is ready to be printed
function IsReadyToPrint($status) {
	return ( ($status >= SV_READY_TO_PRINT_LOW) && ($status <= SV_READY_TO_PRINT_HIGH) );
}
// Returns a bool indicating whether the appointment is printed
function IsPrinted($status) {
	return ( ($status >= SV_PRINTED_LOW) && ($status <= SV_PRINTED_HIGH) );
}
// Returns a bool indicating whether the appointment is complete for any reason
function IsComplete($status) {
	return ( ($status == SV_BAD_DOCUMENTATION) ||
			 ($status == SV_PARTIAL_COMPLETION) ||
			 ($status == SV_SV_CANCELED) ||
			 ($status == SV_NO_SHOW)||
			 ($status == SV_COMPLETED) );
}

// Returns the Redistribution stats number
function GetRedistributionStatus() {
	return SV_REDISTRIBUTION;
}
function GetRedistributionStatuses() {
	return array(SV_REDISTRIBUTION);
}

// **********************************************************
// * Decoder for family sizes
define("FAMILY_SIZE_SM_LOW", 1);
define("FAMILY_SIZE_SM_HIGH", 2);
define("FAMILY_SIZE_MED_LOW", 3);
define("FAMILY_SIZE_MED_HIGH", 4);
define("FAMILY_SIZE_HIGH_THRESHOLD", 5);

function runFamilyDecoder($famSize, $valS, $valM, $valL){
	switch(true) {
		case ($famSize >= FAMILY_SIZE_SM_LOW && $famSize <= FAMILY_SIZE_SM_HIGH): return $valS;
		case ($famSize >= FAMILY_SIZE_MED_LOW && $famSize <= FAMILY_SIZE_MED_HIGH): return  $valM;
		case ($famSize >= FAMILY_SIZE_HIGH_THRESHOLD): return $valL;

		default: return 'Small';
	}
}

function familySizeDecoder($famSize){
	return (runFamilyDecoder($famSize, 'Small', 'Medium', 'Large'));
}

function orderFormNameTagLength($familySize) {
	return (runFamilyDecoder($familySize, 4, 5, 6));
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
// * Return all state options, with an optional 'selected' state

function getStateOptions($s = 'IL') {

	$stateOptions = array('AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA',
						 'KS','LA','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND',
						 'OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY');
	foreach ($stateOptions as $option) {
		echo "<option value='" . $option . "' " . (($option == $s) ? ' selected ' : ' ' ) . ">" . $option . "</option>";
	}

}

function getPetOptions($s = '') {
  $petOptions = array('A' => 'Small Dog', 'B' => 'Medium Dog', 'C' => 'Large Dog', 'D' => 'Cat');
  foreach ($petOptions as $letter => $option) {
    $selected = is_numeric(strpos($s, $letter)) ? " selected " : "" ;
    echo "<option value='" . $letter . "' " . $selected . ">" . $option . "</option>";
  }
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
		case ($gender == GENDER_UNKNOWN): return '?';
		case ($gender == GENDER_MALE): return 'Male';
		case ($gender == GENDER_FEMALE): return 'Female';

		default: return '-';
	}
}
function genderDecoderShort($gender){
	switch(true) {
		case ($gender == GENDER_UNKNOWN): return '?';
		case ($gender == GENDER_MALE): return 'M';
		case ($gender == GENDER_FEMALE): return 'F';

		default: return '-';
	}
}

// ************************************************************
// ** Decoder/Encoders for item locations
define("MIN_AISLE", 1);
define("MAX_AISLE", 20);
define("MIN_RACK" , 65);
define("MAX_RACK" , 90);
define("MIN_SHELF", 1);
define("MAX_SHELF", 20);
function aisleDecoder($aisle){ return (($aisle > 0) ? $aisle : "-"); }
function aisleEncoder($aisle){ return $aisle; }
// Adding this here in case we want to do something in the future
function shelfDecoder($shelf){ return (($shelf > 0) ? $shelf : "-"); }
function shelfEncoder($shelf){ return $shelf; }

function rackEncoder($rack){	return $rack; }

function rackDecoder($rack){
	return (($rack > 0) ? chr($rack) : "-");
}

// ************************************************************
// ** Get price information for items by ID
function getItemPrices($conn = null) {
	$closeOnExit = false;
	if ($conn == null) {
		$conn = connectDB();
		$closeOnExit = true;
	}
	$sql = "SELECT itemID, price
					FROM item
					WHERE item.isDeleted=0";
	$results = runQuery($conn, $sql);
	$itemPrices = [];
	if (is_array($results)) {
		foreach ($results as $result) {
			$itemPrices[$result['itemID']] = $result['price'];
		}
	}
	if ($closeOnExit) { closeDB($conn); }
	return $itemPrices;
}
// ************************************************************
// ** Disabled categories for walkIns

function showCategory($walkIn, $category){
	// If we need to make more in the future, just add them here
	$DisabledCategories = array("medicine","specials","redistribution");

	if (($walkIn) && (in_array(strtolower($category), $DisabledCategories)) ) {
		return false;
	}
	return true;
}

// **************************************
// ** Weight information for donations
define("WEIGHT_BAKERY"	 , 15);
define("WEIGHT_DAIRY"	   , 40);
define("WEIGHT_MEAT"	   , 40);
define("WEIGHT_MIX"		   , 25);
define("WEIGHT_NONFOOD"	 , 25);
define("WEIGHT_PREPARED" , 25);
define("WEIGHT_PRODUCE"	 , 30);
define("WEIGHT_FROZEN"	 , 25);
define("WEIGHT_FOODDRIVE", 25);

// ****************************************
// * AJAX defines
define("AJAX_REDIRECT", "!REDIRECT!");

// **************************************
// * Permission stuff
DEFINE("PERM_TESTHOME", 100);
DEFINE("PERM_TESTLIVE", 101);
DEFINE("PERM_MAX" , 99);
DEFINE("PERM_RR"  , 10);
DEFINE("PERM_BASE", 1);
function decodePermissionLevel($val) {
  switch ($val) {
    case PERM_TESTHOME:
     case PERM_TESTLIVE:
      return "Registration Level (Test)";
    case PERM_RR:
      return "Registration Level";
    case PERM_MAX:
      return "Admin Access";
    case PERM_BASE:
    default:
      return "Basic";
  }
}

?>