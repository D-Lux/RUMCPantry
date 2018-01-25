<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php'; 
?>

<h3>Category Update</h3>
<div class="body-content">
<?php 
  $categoryID = $_GET['categoryID'];
  $name 		= "";
  $small 		= 0;
  $medium		= 0;
  $large		= 0;
   
     /* Create connection*/
 	$conn = connectDB();
  /* Check connection*/
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  $sql = "SELECT name, small, medium, large FROM Category WHERE categoryID = ". $_GET['categoryID'] ;
	$result = queryDB($conn, $sql);
	
	closeDB($conn);
	
    if ($result->num_rows > 0) {
      $row 		  = sqlFetch($result);
      $name		  = $row["name"];
      $small		= $row["small"];
      $medium		= $row["medium"];
      $large		= $row["large"];
    }
    else {
      // Add some kind of warning?
    }

?>
	
<form name="addCategory" action="php/categoryOps.php" onSubmit="return validateCategoryAdd()" method="post">
	<div class="row">
		<input type="hidden" name="categoryID" value='<?= $categoryID ?>'>
		<div class="col-sm-4"><label class="required nameField">Category: </label></div>
		<div class="col-sm-8">
      <input type="text" name="category" value=<?=$name?>>
		</div>
	</div>
	<div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
		<div class="row">
			<div class="col-sm-4"><h4>Distribution Limits</h4></div>
		</div>
		<div class="row">
			<div class="col-sm-4">Small Families:</div>
			<div class="col-sm-8">
				<input type="number" value=<?= $small ?> min=0 name="small">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Medium Families:</div>
			<div class="col-sm-8">
				<input type="number" value=<?= $medium ?> min=0 name="medium">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Large Families:</div>
			<div class="col-sm-8">
				<input type="number" value=<?= $large ?> min=0 name="large">
			</div>
		</div>
	</div>
	</br>
	<input type="submit" class="btn-nav" value="Update" name="UpdateCategoryIndividual">
</form>


<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>