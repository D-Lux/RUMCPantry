<?php include 'php/utilities.php'; ?>
	
	<style>
	p {
		color : red;
	}
	</style>
	<h3>Log In</h3>
	
	<div class="body_content">
		
		<form method = "POST" action="php/login.php">
		  Log In: <input type="text" name="login" value="">
		  <br>
		  Password: <input type="password" name="password" value="" autocomplete="off">
		  <br>
		  <?php
			if (isset($_GET['err'])) {
				if ($_GET['err'] == 1) {
					echo "<p>Incorrect login or password</p>";
				}
				else if ($_GET['err'] == 2) {
					echo "<p>Please log in</p>";
				}
			}
		  ?>
		  <input type="submit" value="Submit">
		</form>

		<br><br><br>
		or continue as client
		<form method="get" action="mainpage.php">
			<input type="submit" value="continue">
    	</form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->		

	</body>
</html>