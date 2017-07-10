<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
    <title>Order Form Selection</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        Admin Order Form Selection
    </h1>
    <form method="get" action="ap_oo2.php">
        <input type="submit" name="1to2" value="Order Form: 1-2"><br>
		<input type="submit" name="3to4" value="Order Form: 3-4"><br>
		<input type="submit" name="5Plus" value="Order Form: 5+"><br>
		<input type="submit" name="Walkin" value="Order Form: Walk-In"><br><br>
	</form>
	<form method="post" action="ap_oo5.php">
		<input type="submit" name="Specials" value="Order Form: Specials">
    </form>
	
</body>

</html>