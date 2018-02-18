<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
  
  $conn = connectDB();
  
  // Get our category information
  $sql = "SELECT categoryID, name, order, isDeleted
          FROM category
          ORDER BY order";
  if (($results = runQuery($conn, $query)) === FALSE) {
    die("No categories in the database.");
  };
  
  closeDB($conn);
?>

	<h3>Order Form: Category Ordering</h3>
	
	<div class="body-content">
	
	
<?php include 'php/footer.php'; ?>

<script type="text/javascript">



</script>