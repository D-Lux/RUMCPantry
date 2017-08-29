<?php include 'php/utilities.php'; ?>
<script src="js/redistOps.js"></script>

    <button id='btn_back' onclick="goBack()">Back</button>
	<h3>Add New Redistribution Item</h3>
	
	<div class="body_content">
	
	<form name="submitNewRedistItem" action="php/redistOps.php" onSubmit="return validateNewRedistItem()" method="post">
        <div id="itemName" class="required"><label>Item Name: </label> <input type="text" id="itemNameField" name="itemName" maxlength="45"></div><br>
		<div>Price: <input type="number" name="price" min=0 step=".01"></div>
		<div>Weight: <input type="number" name="weight" min=0 step=".01"></div>
		<br>
        <input type="submit" name="submitNewRedistItem" value="Create Item" >
    </form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>