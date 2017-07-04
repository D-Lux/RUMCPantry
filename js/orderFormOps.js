//This is done this way since the family size string is longer or shorter depending on which one it is
//So we only pass the first letter, then restore the string
function familyTypeExtractor(famType) {
	switch(famType) {
		case 's':
			return "small";
		case 'm':
			return "medium";
		case 'l':
			return "large";
		case 'w':
			return "walkin";
		default:
			return "walkin";
	}
}

// Ajax commands for the admin's order form creation command
// Updates item quantities
function AJAX_UpdateQty(callingSlot) {
	// Get the item ID and the new quantity from the page
	// ID = 'iqty' + familyKey + itemID
	var newQty = callingSlot.value;
	var familyType = familyTypeExtractor(callingSlot.id.substring(4,5));
	var itemID = callingSlot.id.substring(5,6);

	// Run the AJAX stuff
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("ErrorLog").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setOrderForm.php?" +
				 "itemID=" + itemID + "&" +
				 "newQty=" + newQty + "&" +
				 "familyType=" + familyType, true);
	xmlhttp.send();
}

// Updates item factors
function AJAX_UpdateFX(callingSlot) {
	// Get the item ID and the new factor from the page
	var newFx = callingSlot.value;
	var itemID = callingSlot.id.substring(3,4);

	//window.alert("Factor: " + newFx);
	// Run the AJAX stuff
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("ErrorLog").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setOrderForm.php?" +
				 "itemID=" + itemID + "&" +
				 "newFx=" + newFx, true);
	xmlhttp.send();
}

// Updates category quantity values
function AJAX_UpdateCQty(callingSlot) {
	// Get the category ID and the new quantity from the page
	var newQty = callingSlot.value;
	var categoryID = callingSlot.id.substring(4,5);
	var familyType = familyTypeExtractor(callingSlot.id.substring(3,4));

	// Run the AJAX stuff
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("ErrorLog").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setOrderForm.php?" +
				 "CID=" + categoryID + "&" +
				 "cQty=" + newQty + "&" +
				 "familyType=" + familyType, true);
	xmlhttp.send();
}

// *********************************************
// * For the client order form to track selected options
function countOrder(callingSlot)	{
	// Get the name field (without the [])
	var boxName = callingSlot.name;
	var nameField = boxName.substr(0, ( boxName.length - 2 ));
	
	// Get the total number of items selectable in this item's category
	var Category = document.getElementById(nameField);
	var MaxCount = Category.value;
	
	// Find all other elements in the page that have the same name and tally up the check boxes
	//var name = document.getElementsByName(callingSlot.name);
	
	var name = document.getElementsByName(boxName);
	
	var runningTotal = 0;
	for (var i=0; i < name.length; i++) {
		if (name[i].checked) {
			var numToAdd = Number(name[i].id);
			// If we exceed our max count, don't let the box be checked and display a warning
			if ( (runningTotal + numToAdd) > MaxCount) {
				callingSlot.checked = false;
				window.alert("Cannot select more in this category");
				// Break out, since we know we're done here
				return;
			}
			runningTotal += numToAdd;
		}
	}
	
	// Update the selected quantity and color it if we're at maximum
	document.getElementById("Count" + nameField).innerHTML = 
		"Selected: " + runningTotal + " / " + MaxCount;
	if (runningTotal == MaxCount) {
		document.getElementById("Count" + nameField).style.color = "LawnGreen";
	}
	else {
		document.getElementById("Count" + nameField).style.color = "Black";
	}
	
}