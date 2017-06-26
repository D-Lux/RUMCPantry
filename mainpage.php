<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<!--<link href='style.css' rel='stylesheet'>-->
		<?php include 'php/utilities.php';?>
		<?php include 'php/checkLogin.php';?>

	</head>
	<body>
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Main Page</h3>
	
		<form method="get" action="cp1.php">
			<button type="submit">Client</button>
		</form>
		<form method="get" action="ap1.php">
			<button type="submit">Admin</button>
		</form>
		<form method="post" action="<?=($_SERVER['PHP_SELF'])?>">
			<button type="submit">Log Out</button>
		</form>
 

	</body>
	
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			createCookie("loggedin", 0, -1);
			header ("location: /RUMCPantry/login.php");
		}
	?>
</html>