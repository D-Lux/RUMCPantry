<?php include 'php/header.php';?>

	<meta http-equiv="refresh" content="5" >


	<script>
		if (getCookie("newWalkIn") != "") {
			window.alert("Walk-In Client added!");
			removeCookie("newWalkIn");
		}		
	</script>
		<!-- <h2>Check In</h2> -->
	<button id='btn_back' onclick="goBack()">Back</button>	
    <button class='btn_walkIn' onclick="location.href = 'awc.php';">Add walk in client</button><br>
   

	
	<?php include 'php/checkInOps.php';?>

	<br><br>
	<button class='btn_walkIn' onclick="location.href = 'endOfDay.php';">End of day</button>
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>
</html>