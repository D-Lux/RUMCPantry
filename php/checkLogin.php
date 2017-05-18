<?php

if (($_COOKIE["loggedin"]) != 1) {
	header ("location: /RUMCPantry/login.html?err=2");
}

?>