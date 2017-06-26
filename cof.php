<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<link href='css/toolTip.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>

	</head>
	<body>
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Client Order Form</h3>

<table>
  <tr>
    <th>Category</th>
    <th>Item</th>
    <th>QTY</th>
  </tr>
  <tr>
    <td>Beans</td>
    <td>Pinto (Bag)</td>
    <td>1</td>
  </tr>
  <tr>
    <td>Beans</td>
    <td>Black</td>
    <td>2</td>
  </tr>
  <tr>
    <td>Beans</td>
    <td>Pinto (Can)</td>
    <td>2</td>
  </tr>
</table>
	

		<form method="get" action="mainpage.php">
			<button type="submit">Submit Order</button>
		</form>

		<button onclick="goBack()">Back</button>
	</body>
</html>