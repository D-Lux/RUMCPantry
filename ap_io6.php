<!doctype html>
<html>

<head>

    <script src="js/utilities.js"></script>

    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
  
    <?php include 'php/checkLogin.php';?>

    <title>ap_io6</title>
</head>

<body>
<button onclick="goBack()">Back</button>
    <h1>
        Deleted items and categories
    </h1>

    <?php include 'php/displayDeletedItems.php';?>
    <?php include 'php/displayDeletedCategories.php';?>
</body>

</html>