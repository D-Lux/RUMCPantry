<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php

// *****************************
// * Special case functions for beans
class CLASS_BeanInfo {
	public $Name;
	public $QTY;
	
	function __construct($iName, $iQty) { 
        $this->Name = $iName;
		$this->QTY = $iQty;
    }
}

function showBeanCategory($Cans, $Bags, $CQty, $Order=Null) {
	// Display category header 
	echo "<div class='orderSection'>";
	echo "<h4 class='text-center'>Beans</h4>";
	
	// Display extra information (only choose one bagged or X canned)
	echo "<h5 class='text-center'><div id='CountBeans'>You may select up to " . $CQty . " cans or 1 bag" .
		" (" . $CQty . " remaining)</div></h5>";
	
	// Include hidden values so we can track the category
	echo "<input type='hidden' value=1 id='BagBeans'>";
	echo "<input type='hidden' value=" . $CQty . " id='CanBeans'>";
	
	// Display Canned bean options
	echo "<div id='CannedBeansSection'><h6 class='text-center'>Cans (Max " . $CQty . ")</h6>";
	// Display a string if no canned beans are available
	if (count($Cans) <= 0) {
		echo "&#8226; No canned beans available today";
	}
	foreach ($Cans as $BeanID=>$BeanInfo) {
    echo "<div class='row'>";
		echo "<div class='col-sm text-right' style='margin:auto;'>" . $BeanInfo->Name . "</div>";
		echo "<div class='selectionBoxes col-sm'>";
		for ($i = 0; $i < $BeanInfo->QTY; $i++) {
			// Value is the item's ID | Name is the item's category[] (in array)
			$customID = "box" . $BeanID . "n" . $i;
			echo "<input type='checkbox' class='align-top' id=$customID value=" . $BeanID;
			echo " onclick='countCanBeans(this)' name='CanBeans[]' ";
					
			// If this item was selected, check it and reduce our count
			if ( $Order != Null ) {
				if ( (isset($Order[$BeanID])) && ($Order[$BeanID] > 0) ) {
					$Order[$BeanID]--;
					echo " checked ";
				}
			}
			// Close off the html input tag
			echo ">";
			
			echo "<label for=$customID ></label>";
		}
		echo "</div>";	// closing off selectionBoxes
    echo "</div>"; // close off the row
	}
	echo "</div>";
	
	// Display Bagged bean options
	echo "<div id='BaggedBeansSection'><h6 class='text-center'>Bags (Max 1)</h6>";
	// Display a string if no bagged beans are available
	if (count($Bags) <= 0) {
		echo "&#8226; No bagged beans available today";
	}
	foreach ($Bags as $BeanID=>$BeanInfo) {
    echo "<div class='row'>";
		echo "<div class='col-sm text-right' style='margin:auto;'>" . $BeanInfo->Name . "</div>";
		echo "<div class='selectionBoxes col-sm'>";
		for ($i = 0; $i < $BeanInfo->QTY; $i++) {
			// Value is the item's ID | Name is the item's category[] (in array)
			$customID = "box" . $BeanID . "n" . $i;
			echo "<input type='checkbox' id=$customID value=" . $BeanID;
			echo " onclick='countBagBeans(this)' name='BagBeans[]' ";
					
			// If this item was selected, check it and reduce our count
			if ( $Order != Null ) {
				if ( (isset($Order[$BeanID])) && ($Order[$BeanID] > 0) ) {
					$Order[$BeanID]--;
					echo " checked ";
				}
			}
			// Close off the html input tag
			echo ">";
			echo "<label for=$customID ></label>";
		}
		echo "</div>";	// closing off selectionBoxes
    echo "</div>"; // closing the row
		//echo "<br>";
	}
	echo "</div>";

	echo "</div>"; // /orderSection
}

?>