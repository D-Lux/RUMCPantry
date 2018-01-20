<?php include 'php/header.php';?>
  <div class='body-content'>
		<div id='main-page'>

			<form method="get" action="cp1.php">
				<button class='btn-nav' type="submit">Client</button>
			</form>
			<form method="get" action="ap1.php">
				<button class='btn-nav' type="submit">Admin</button>
			</form>
			<form method="post" action="<?=($_SERVER['PHP_SELF'])?>">
				<button class='btn-nav' type="submit">Log Out</button>
			</form>
		</div>
	
<?php
// show buttons based on permissions (remove this page, basically)
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    createCookie("loggedin", 0, -1);
    header ("location: /RUMCPantry/login.php");
  }
  
  include 'php/footer.php'; 
 ?>