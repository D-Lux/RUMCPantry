<?php


function echoDivWithColor($message, $color)
{
    echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}

// Creating a global connection function so that if we ever need to change the database information
// it's all in one place
function createPantryDatabaseConnection()
{
	// Set up server connection
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "foodpantry";

	// Create and check connection
	return( new mysqli($servername, $username, $password, $dbname) );
}

function debugEchoPOST()
{
	echo '<b>POST:</b><pre>';
	var_dump($_POST);
	echo '</pre>';
}

function debugEchoGET()
{
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

// This takes a database connection variable and closes it only if it is open still
function closeDB($conn){
	if ($conn->ping()) {
		$conn->close();
	}
}
?>