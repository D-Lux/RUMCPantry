<?php


function echoDivWithColor($message, $color)
{
    echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}


function debugEchoPOST()
{
	echo '<pre>';
	var_dump($_POST);
	echo '</pre>';
}

function debugEchoGET()
{
	echo '<pre>';
	var_dump($_GET);
	echo '</pre>';
}


function fixInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function createCookie($cookieName, $cookieValue, $duration) {
	setcookie($cookieName, $cookieValue, time() + $duration, "/");
}

// Displays a phone number as expected (###)-###-####
function displayPhoneNo($data) {
	$firstThree = substr($data, 0, 3);
	$middleThree = substr($data, 3, 3);
	$finalFour = substr($data, 6, 4);
	echo "($firstThree)-$middleThree-$finalFour";
}

// Takes in a string and strips all spaces, dashes and non-integers, then saves the first 10
function storePhoneNo($data) {
	return (preg_replace('/[^0-9]/','',$data));
}
?>