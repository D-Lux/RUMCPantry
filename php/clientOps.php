<?php
include 'utilities.php';

// DEBUG
echo "Post:<br>";
debugEchoPOST();
echo "<br><br>Get:<br>";
debugEchoGET();

/*
if (isset($_POST['action'])) {
	$btnPressed = $_POST['submit'];
	if ($btnPressed == "Create New") {
		header ("location: /RUMCPantry/ap_co2.html");
	}
	else {
		//TODO: Functions for update info and add appointment
		echo "<br>This button does not work yet";
	}
}*/
if (isset($_POST['submit'])) {
	header ("location: /RUMCPantry/ap_co2.html");
}
elseif (isset($_GET['Update'])) {
	// Go to update for record in $_GET['id']
	echo "This will update the client: ". $_GET['id'];
}
elseif (isset($_GET['Delete'])) {
	// Delete warning for record in $_GET['id']
	echo "This will ask to delete the client: ". $_GET['id'];
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
