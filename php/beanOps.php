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
	echo "<h3>Beans</h3>";
	// TODO: If one or the other is empty, display the non-empty one and be done
	
	// Display extra information (only choose one bagged or X canned)
	echo "<h4><div id='CountBeans'>You may select up to " . $CQty . " cans or 1 bag" .
		" (" . $CQty . " remaining)</div></h4>";
	
	// Include hidden values so we can track the category
	echo "<input type='hidden' value=1 id='BagBeans'>";
	echo "<input type='hidden' value=" . $CQty . " id='CanBeans'>";
	
	// Display Canned bean options
	echo "<div id='CannedBeansSection'><b>Cans (Max " . $CQty . ")</b><br>";
	foreach ($Cans as $BeanID=>$BeanInfo) {
		echo $BeanInfo->Name;
		for ($i = 0; $i < $BeanInfo->QTY; $i++) {
			// Value is the item's ID | Name is the item's category[] (in array)
			echo "<input type='checkbox' value=" . $BeanID;
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
			
			//echo "<input type='checkbox' value=" . $BeanID . 
			//		" onclick='countCanBeans(this)' name='CanBeans[]'>";
		}
		echo "<br>";
	}
	echo "</div>";
	
	// Display Bagged bean options
	echo "<div id='BaggedBeansSection'><b>Bags (Max 1)</b><br>";
	foreach ($Bags as $BeanID=>$BeanInfo) {
		echo $BeanInfo->Name;
		for ($i = 0; $i < $BeanInfo->QTY; $i++) {
			// Value is the item's ID | Name is the item's category[] (in array)
			echo "<input type='checkbox' value=" . $BeanID;
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
			//echo "<input type='checkbox' value=" . $BeanID . 
			//		" onclick='countBagBeans(this)' name='BagBeans[]'>";
		}
		echo "<br>";
	}
	echo "</div>";
	
	echo "<br>";
}

?>