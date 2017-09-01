<?php include 'php/header.php'; ?>

	<script>
		if (getCookie("SpecialsSaved") != "") {
			window.alert("Specials Updated!");
			removeCookie("SpecialsSaved");
		}		
	</script>
    <button id='btn_back' onclick="goBack()">Back</button>
    <h3>Order Form Selection</h3>
	
	<div class="body_content">
	
		<form method="get" action="ap_oo2.php">
			<input type="submit" name="1to2" value="Order Form: 1-2 / Walk-In"><br>
			<input type="submit" name="3to4" value="Order Form: 3-4"><br>
			<input type="submit" name="5Plus" value="Order Form: 5+"><br>
		</form>
		<form method="post" action="ap_oo5.php">
			<input type="submit" name="Specials" value="Order Form: Specials">
		</form>
		
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
</body>

</html>