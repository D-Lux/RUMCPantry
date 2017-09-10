<?php include 'php/header.php'; ?>
<script src="js/redistOps.js"></script>

    <button id='btn_back' onclick="goBack()">Back</button>
	<h3>Add New Reallocation Item</h3>
	
	<div class="body_content">
	
	<form name="submitNewRedistItem" action="php/redistOps.php" onSubmit="return validateNewRedistItem()" method="post">
		<div class="inputDiv">
			<div id="itemName" class="required"><label for="itemNameField">Item Name: </label>
				<input type="text" id="itemNameField" name="itemName" maxlength="45"></div>
			<label for="priceInput">Price:</label><input id="priceInput" type="number" name="price" min=0 step=".01"><br>
			<label for="weightInput">Weight:</label><input id="weightInput" type="number" name="weight" min=0 step=".01"><br>
		</div><br>
        <input type="submit" name="submitNewRedistItem" value="Create Item" >
    </form>
	
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>