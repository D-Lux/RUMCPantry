<?php	
	if (($_SERVER["PHP_SELF"]) != "/RUMCPantry/login.html") {
		if ($GLOBALS['loggedin'] != true) {
			header ("location: /RUMCPantry/mainpage.html");
		}
	}
	
	elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = fixInput($_POST['login']);
		$pw = fixInput($_POST['password']);
		if ($name == "admin" and $pw == "rumc") {
			header ("location: /RUMCPantry/mainpage.html");
			$loggedin = true;
		}
		else {
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