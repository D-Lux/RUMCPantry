<?php include 'php/header.php'; ?>

    <?php include 'php/checkLogin.php';?>



    <button id='btn_back' onclick="goBack()">Back</button>    <h3>
        Inventory Operations
    </h3>
    <form method="get" action="ap_io2.php">
        <input type="submit" value="Add an item">
    </form>
   
    <form method="get" action="ap_oo1.php">
        <input type="submit" value="Edit order form">
    </form>
    <form method="get" action="ap_io4.php">
        <input type="submit" value="Add a category">
    </form>
     <form method="get" action="ap_io6.php">
        <input type="submit" value="Deleted items and categories">
    </form>

    <?php include 'php/displayItems.php';?>
    <?php include 'php/displayCategories.php';?>

    </div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>