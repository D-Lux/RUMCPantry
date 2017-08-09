<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/redistOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css">
	<?php include 'php/checkLogin.php';?>

	
    <title>Add New Redistribution Item</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>Add New Redistribution Item</h1>
	<form name="submitNewRedistItem" action="php/redistOps.php" onSubmit="return validateNewRedistItem()" method="post">
        <div id="itemName" class="required"><label>Item Name: </label> <input type="text" id="itemNameField" name="itemName" maxlength="45"></div><br>
		<div>Price: <input type="number" name="price" min=0 step=".01"></div>
		<div>Weight: <input type="number" name="weight" min=0 step=".01"></div>
		<br>
        <input type="submit" name="submitNewRedistItem" value="Create Item" >
    </form>

</body>

</html>