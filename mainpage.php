<?php include 'php/utilities.php';?>
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
 
	</div><!-- closing .content -->
	</body>
	
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			createCookie("loggedin", 0, -1);
			header ("location: /RUMCPantry/login.php");
		}
	?>
</html>