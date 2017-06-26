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