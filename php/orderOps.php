<?php

include 'utilities.php';
debugEchoPOST();
echo "<br>";

// **************************************************
// * Creating invoice descriptions and tying them together into one invoice
if (isset($_POST['CreateInvoiceDescriptions'])) {
	// TODO ALL OF THIS
	foreach ($_POST as $name => $val)
	{
		echo htmlspecialchars($name . ': ' . $val) . "\n";
	}
	/*
	foreach ($_POST["time"] as $timeSlot) {
		for ($i = 0; $i <  $_POST["qty"][$qtySlot]; $i++) {
			$sql .= (!$firstInsert ? "," : "");
			$firstInsert = FALSE;
			$sql .= "( " . $validateDate . ", '" . $timeSlot . "', $availID, 0)";
		}
		$qtySlot++;
	}
	*/
	// Go through all item categories and check if they are set
	// If they are, go through their 'length'. If any match, increase a count
		// Create invoice descriptions with appropriate counts
	$name = $_POST['Beans'];

	// optional
	echo "<br>";
	foreach ($name as $color){
		echo $color . "<br />";
	}
	
	
	////header("location: /RUMCPantry/cap.php");
}
else {
	echo "<h1>Nothing was set</h1><br>";
	//header("location: /RUMCPantry/mainpage.php");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>