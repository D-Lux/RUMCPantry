<?php
include 'php/header.php'; 
include 'php/backButton.php';
?>
<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">

  
<h3>Add Category</h3>
<div class="body_content">
<br>
<form name="addCategory" action="php/itemOps.php" onSubmit="return validateCategoryAdd()" method="post">
	<div class="row">
		<div class="col-sm-4"><label class="required nameField">Category: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i("","categories","category","name","category", false);
			?>
		</div>
	</div>
	<div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
		<div class="row">
			<div class="col-sm-4"><h4>Distribution Limits</h4></div>
		</div>
		<div class="row">
			<div class="col-sm-4">Small Families:</div>
			<div class="col-sm-8">
				<input type="number" value=0 min=0 name="small">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Medium Families:</div>
			<div class="col-sm-8">
				<input type="number" value=0 min=0 name="medium">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">Large Families:</div>
			<div class="col-sm-8">
				<input type="number" value=0 min=0 name="large">
			</div>
		</div>
	</div>
	</br>
	<input type="submit" value="Create" name="createCategory">
</form>

<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>