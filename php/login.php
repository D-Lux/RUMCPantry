<?php

	$loginErr = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = fixInput($_POST['login']);
		$pw = fixInput($_POST['password']);
		if ($name == "admin" and $pw == "rumc") {
			header ("location: /RUMCPantry/mainpage.html");
		}
		else {
			$loginErr = "Incorrect name or password.";
			header ("location: /RUMCPantry/login.html?err=1");
		}
	}
	
	
	function fixInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
	 return $data;
}
	?>