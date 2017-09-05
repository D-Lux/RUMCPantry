<?php include 'php/header.php'; ?>

	<script>
		if (getCookie("SpecialsSaved") != "") {
			window.alert("Specials Updated!");
			removeCookie("SpecialsSaved");
		}		
	</script>
    <button id='btn_back' onclick="goBack()">Back</button>
    <h3>Order Forms</h3>
	
	<div class="body_content">
	<!-- View order forms -->
		<div style="float: right;">
			<form method="get" action="cof.php">
				<input type="submit" name="Small" value="View Form: 1-2 / Walk-In"><br>
				<input type="submit" name="Medium" value="View Form: 3-4"><br>
				<input type="submit" name="Large" value="View Form: 5+">
			</form>
		</div>
		<!-- Setup order forms -->
		<form method="get" action="ap_oo2.php">
			<input type="submit" name="1to2" value="Edit Form: 1-2 / Walk-In">
			<input type="submit" name="3to4" value="Edit Form: 3-4">
			<input type="submit" name="5Plus" value="Edit Form: 5+">
		</form>
		<form method="post" action="ap_oo5.php">
			<input type="submit" name="Specials" value="Edit Form: Specials">
		</form>
		
		
		
		
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
</body>

</html>