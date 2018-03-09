<?php
  $pageRestriction = 99;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<h3>Add Item</h3>
<div class="body-content">
<form name="addItem" action="php/itemOps.php" onSubmit="return validateItemAdd()" method="post">
	<!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
	<!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->
	<div class="row">
		<div class="col-sm-4"><label class="required categoryField">Category: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i("","categories","category","name","category", false);
			?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4"><label class="required itemField">Item Name: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i("","itemNames","item","itemName","itemName", true);
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4"><label class="required displayField">Display Name: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i("","displayNames","item","displayName","displayName", true);
			?>
		</div>
	</div>
	<div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
		<div class="row">
			<div class="col-sm-2">Location:</div>
		</div>
		<div class="row">
			<!-- Aisle -->
			<div class="col-sm-1">Aisle:</div>
			<div class="col-sm-2">
				<select name="aisle">
					<option value=0>-</option>
					<?php
						for ($i = MIN_AISLE; $i <= MAX_AISLE; $i++) {
							echo "<option value=" . $i . ">" . aisleDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
			<!-- Rack -->
			<div class="col-sm-1">Rack:</div>
			<div class="col-sm-2">
				<select name="rack">
					<option value=0>-</option>
					<?php
						for ($i = MIN_RACK; $i <= MAX_RACK; $i++) {
							echo "<option value=" . $i . ">" . rackDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
			<div class="col-sm-1">Shelf:</div>
			<div class="col-sm-2">
				<select name="shelf">
					<option value=0>-</option>
					<?php
						for ($i = MIN_SHELF; $i <= MAX_SHELF; $i++) {
							echo "<option value=" . $i . ">" . shelfDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-4">Price:</div>
		<div class="col-sm-8">
			<span>$</span>
			<input  style="width: 8em;" type="text" class="input-number" maxlength=6 value="0" name="price">
		</div>
	</div>
	<br>

	<!-- QTY to take -->
	<div style="border: 2px solid #499BD6; padding:5px;">
		<div class="row">
			<div class="col-sm-4">Order Form Quantities:</div>
		</div>
		<div class="row">
			<div class="col-sm-1">Small:</div>
			<div class="col-sm-2">
				<input  style="width: 2em;" type="text" class="input-number" maxlength=2 value="0" name="small">
			</div>
			<div class="col-sm-1">Medium:</div>
			<div class="col-sm-2">
				<input  style="width: 2em;" type="text" class="input-number" maxlength=2 value="0" name="medium">
			</div>
			<div class="col-sm-1">Large:</div>
			<div class="col-sm-2">
				<input  style="width: 2em;" type="text" class="input-number" maxlength=2 value="0" name="large">
			</div>
		</div>
	</div>

	</br>
	<input type="submit" class='btn-nav' value="Create" name="createItem">
</form>

<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>