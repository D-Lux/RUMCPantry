<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <?php include 'php/checkLogin.php';?>
    <title>ap_io1</title>

</head>

<body>

    <script src="js/destinationFunctions.js"></script>
    <input type="button" value="Go Back" onclick="ap_io1Back()">
    <h1>
        Admin page inventory ops 1
    </h1>
    <form method="get" action="ap_io2.php">
        <input type="submit" value="Add an item">
    </form>
   
    <form method="get" action="ap_oo1.php">
        <input type="submit" value="Edit order form">
    </form>
    <form method="get" action="ap_io4.php">
        <input type="submit" value="Add a category">
    </form>

    <?php include 'php/displayItems.php';?>
    <?php include 'php/displayCategories.php';?>
</body>

</html>