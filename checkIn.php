<?php include 'php/header.php';?>

	<meta http-equiv="refresh" content="5" >


	<script>
		if (getCookie("newWalkIn") != "") {
			window.alert("Walk-In Client added!");
			removeCookie("newWalkIn");
		}		
	</script>
		<h3>Check In</h3>
	<button id='btn_back' onclick="goBack()">Back</button>	
    <button onclick="location.href = 'awc.php';">Add walk in client</button>
   

	
	<?php include 'php/checkInOps.php';?>

	<button onclick="location.href = 'endOfDay.php';">End of day</button>
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>
</html>