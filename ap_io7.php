<?php include 'php/header.php'; ?>

    <?php include 'php/checkLogin.php';?>



    <button id='btn_back' onclick="goBack()">Back</button>    <h3>
        Item Operations
    </h3>
   
    <form method="get" action="ap_io2.php">
    <input type="submit" value="Add an item">
</form>

<?php include 'php/displayItems.php';?>



    </div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>