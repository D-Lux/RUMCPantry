<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST['login'];
		$pw = $_POST['password'];
		if ($name == "admin" and $pw == "rumc") {
			header ("location: /RUMCPantry/mainpage.html");
		}
		else {
			header ("location: /RUMCPantry/login.html");
		}
	}
	?>