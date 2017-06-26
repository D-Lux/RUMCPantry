<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<!--<link href='style.css' rel='stylesheet'>-->
	<style>
	table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
	}

	td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #dddddd;
	}
	</style>
	</head>
	<body>
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Admin Order Form</h3>

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
			<button type="submit">Update Order Form</button>
		</form>

		<button onclick="goBack()">Back</button>
	</body>
</html>