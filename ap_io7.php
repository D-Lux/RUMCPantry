<?php include 'php/header.php'; ?>

<button id='btn_back' onclick="goBack()">Back</button>

<h3>Item Operations</h3>
<br>

<?php include 'php/displayItems.php';?>

<br>
<form method="get" action="ap_io2.php">
	<input type="submit" value="Add an item">
</form>

</div><!-- /body_content -->
</div><!-- /content -->

</body>
</html>