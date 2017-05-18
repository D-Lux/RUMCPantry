<?php include 'utilities.php';?>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = fixInput($_POST['login']);
		$pw = fixInput($_POST['password']);
		echo "<script>alert('cookie created');</script>";
		if ($name == "admin" and $pw == "rumc") {
			createCookie("loggedin", 1, (86400 * 30)); // 30 days for now
			//createCookie("loggedin", 1, 14400); // 4 hours (change later)
			header ("location: /RUMCPantry/mainpage.html");
		}
		else {
			header ("location: /RUMCPantry/login.html?err=1");
		}
	}
?>