<?php include 'php/header.php'; ?>
	
	<style>
	p {
		color : red;
	}
	
	</style>
	<h3>Welcome</h3>
	
	<div class="body_content">
		<script>
		if (getCookie("clientApptSet") != "") {
			window.alert("Appointment set! Thank you, see you next time!");
			removeCookie("clientApptSet");
		}
		if (getCookie("clientSkippedAppt") != "") {
			window.alert("Thank you for your order, please give us a call to set up another appointment!");
			removeCookie("clientSkippedAppt");
		}		
		</script>
		
		<form method = "POST" action="php/login.php">
			<div class="inputDiv">
				<label for="loginTextBox">Log In: </label>
				<input id="loginTextBox" type="text" name="login" value="">
				<br>
				<label for="passwordTextBox">Password: </label>
				<input id="passwordTextBox" type="password" name="password" value="" autocomplete="off">
				<br>
			</div>
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
		  <input class='btn-nav' type="submit" value="Submit">
		</form>

		<br><br><br>
		or continue as client
		<form method="get" action="cp1.php">
			<input class='btn-nav' type="submit" value="continue">
    	</form>
	
<?php include 'php/footer.php'; ?>