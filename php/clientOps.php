<?php
include 'utilities.php';


echo "this button doesn't work yet";

if (isset($_POST['action'])) {
	$btnPressed = $_POST['submit'];
	if ($btnPressed == "Create New") {
		header ("location: /RUMCPantry/ap_co2.html");
	}
	else {
		//TODO: Functions for update info and add appointment
		echo "<br />This button does not work yet";
	}
}
?>

<html>

<head>
	<script src="/RUMCPantry/js/utilities.js"></script>
</head>

<body>
    <br><br><button onclick="goBack()">Go Back</button>
</body>
</html>
