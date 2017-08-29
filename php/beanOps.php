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
	echo "<h4>Beans</h4>";
	// TODO TODO: If one or the other is empty, display the non-empty one and be done
	
	// Display extra information (only choose one bagged or X canned)
	echo "<h5><div id='CountBeans' >You may select up to " . $CQty . " cans or 1 bag" .
		" (" . $CQty . " remaining)</div></h5>";
	
	// Include hidden values so we can track the category
	echo "<input type='hidden' value=1 id='BagBeans'>";
	echo "<input type='hidden' value=" . $CQty . " id='CanBeans'>";
	
	// Display Canned bean options
	echo "<div id='CannedBeansSection'><h6>Cans (Max " . $CQty . ")</h6>";
	foreach ($Cans as $BeanID=>$BeanInfo) {
		echo $BeanInfo->Name;
		echo "<div class='selectionBoxes'>";
		for ($i = 0; $i < $BeanInfo->QTY; $i++) {
			// Value is the item's ID | Name is the item's category[] (in array)
			$customID = "box" . $BeanID . "n" . $i;
			echo "<input type='checkbox' id=$customID value=" . $BeanID;
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
		echo "<br>";
	}
	echo "</div>";
	
	// Display Bagged bean options
	echo "<div id='BaggedBeansSection'><h6>Bags (Max 1)</h6>";
	foreach ($Bags as $BeanID=>$BeanInfo) {
		echo $BeanInfo->Name;
		echo "<div class='selectionBoxes'>";
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
		echo "<br>";
	}
	echo "</div></div>"; // Closing CountBeans and orderSection
}

?>