<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';

  $categoryID = $_GET['categoryID'];
  $name     = "";
  $small    = 0;
  $medium   = 0;
  $large    = 0;

     /* Create connection*/
  $conn = connectDB();
  /* Check connection*/
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT name, small, medium, large FROM category WHERE categoryID = ". $_GET['categoryID'] ;
  $result = runQueryForOne($conn, $sql);

  closeDB($conn);

    if (is_array($result)) {
      $name     = $result["name"];
      $small    = $result["small"];
      $medium   = $result["medium"];
      $large    = $result["large"];
    }
    else {
      die("Category was not found.");
    }
?>
<style>
  .input-number {
    width:3em;
  }
  input[type="text"] {
    width: 500px;
  }
</style>
<h3>Category Update</h3>
<div class="body-content">


<form name="addCategory" action="php/categoryOps.php" onSubmit="return validateCategoryAdd()" method="post">
	<div class="row">
		<input type="hidden" name="categoryID" value='<?= $categoryID ?>'>
		<div class="col-sm-4">Category:</div>
		<div class="col-sm-8">
      <input type="text" name="category" value='<?=$name?>'>
		</div>
	</div>
	<div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
		<div class="row">
			<div class="col-sm-4"><h4>Distribution Limits</h4></div>
		</div>
		<div class="row">
			<div class="col-sm-4">Small Families:</div>
			<div class="col-sm-8">
				<input type="text" class="input-number" value=<?= $small ?> maxlength=2 name="small">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Medium Families:</div>
			<div class="col-sm-8">
				<input type="text" class="input-number" value=<?= $medium ?> maxlength=2 name="medium">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Large Families:</div>
			<div class="col-sm-8">
				<input type="text" class="input-number" value=<?= $large ?> maxlength=2 name="large">
			</div>
		</div>
	</div>
	</br>
	<input type="submit" class="btn-nav" value="Update" name="UpdateCategoryIndividual">
</form>


<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>