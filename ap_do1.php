<!DOCTYPE html>

<html>

<head>
    <title>Roselle United Methodist Church Food Pantry</title>
    <script src="js/utilities.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">



</head>

<body>
    <h1>Roselle United Methodist Church</h1>
    <h2>Food Pantry</h2>
    <h3>Admin Page Donation Ops1</h3>


    <button onclick="goBack()">Back</button>

    <form method="get" action="ap_do2.php">
        <input type="submit" value="Add a donation">
    </form>

    <form method="get" action="ap_do3.php">
        <input type="submit" value="Add a donation partner">
    </form>

    <?php include 'php/displayDonations.php';?>
    <?php include 'php/displayDonationPartners.php';?>

</body>

</html>