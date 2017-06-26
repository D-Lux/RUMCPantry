<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <?php include 'php/checkLogin.php';?>
    <title>ap_io1</title>

</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        Admin page inventory ops 1
    </h1>
    <form method="get" action="ap_io2.php">
        <input type="submit" value="Add an item">
    </form>
    <form method="get" action="ap_io3.php">
        <input type="submit" value="Edit an item">
    </form>
    <form method="get" action="aof.php">
        <input type="submit" value="Edit order form">
    </form>
    <form method="get" action="ap_io4.php">
        <input type="submit" value="Add a category">
    </form>

    <?php include 'php/displayItems.php';?>
    <?php include 'php/displayCategories.php';?>
</body>

</html>