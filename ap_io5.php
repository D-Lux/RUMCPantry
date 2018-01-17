<?php 
include 'php/header.php';
include 'php/backButton.php'; 
?>
<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">

<h3>Category Update</h3>
<div class="body_content">
<?php 
    $categoryID = $_GET['categoryID'];
    $name 		= "";
    $small 		= 0;
    $medium		= 0;
    $large		= 0;
   
     /* Create connection*/
 	$conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT name, small, medium, large FROM Category WHERE categoryID = ". $_GET['categoryID'] ;
	$result = queryDB($conn, $sql);
	
	closeDB($conn);
	
    if ($result->num_rows > 0) {
        $row 		= sqlFetch($result);
		$name		= $row["name"];
		$small		= $row["small"];
		$medium		= $row["medium"];
		$large		= $row["large"];
    }
    else {
        echoDivWithColor("<h1><b>Category does not exist!</h1></b>","red");
    }

?>
	
<form name="addCategory" action="php/itemOps.php" onSubmit="return validateCategoryAdd()" method="post">
	<div class="row">
		<input type="hidden" name="categoryID" value='<?= $categoryID ?>'>
		<div class="col-sm-4"><label class="required nameField">Category: </label></div>
		<div class="col-sm-8">
			<?php createDatalist_i($name,"categories","category","name","category", false); ?>
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
	<input type="submit" value="Update" name="UpdateCategoryIndividual">
</form>


<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>