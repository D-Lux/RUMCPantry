<?php

if (($_COOKIE["loggedin"]) != 1) {
	header ("location: /RUMCPantry/login.php?err=2");
}

?>