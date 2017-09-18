
	<?php include 'php/header.php';?>

  
    <?php include 'php/checkLogin.php';?>


</head>

<body>
<button id='btn_back' onclick="goBack()">Back</button>    <h1>
       
       <h3> Deleted items and categories
    </h3>

    <?php include 'php/displayDeletedItems.php';?>
    <?php include 'php/displayDeletedCategories.php';?>
    </div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>