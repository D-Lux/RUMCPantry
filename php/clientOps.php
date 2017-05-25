<?php
include 'utilities.php';


if (isset($_POST['submit'])) {
	header ("location: /RUMCPantry/ap_co2.html");
}
elseif (isset($_GET['Update'])) {
	header ("location: /RUMCPantry/ap_co3.html?id=" . $_GET['id']);
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
