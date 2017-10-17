<?php include 'php/header.php'; ?>

    <?php include 'php/checkLogin.php';?>



    <button id='btn_back' onclick="goBack()">Back</button>    <h3>
        Category Operations
    </h3>
  
    <form method="get" action="ap_io4.php">
        <input type="submit" value="Add a category">
    </form>

    <?php include 'php/displayCategories.php';?>


    </div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>