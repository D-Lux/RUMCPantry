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

// ****************************************************************
// ** SPECIALS

var specialSlot = 0;
function addSpecialItem() {
	specialSlot++;
	
	// Get a reference to the item template and copy it
	var itm = document.getElementById("specialTemplate");
	var cln = itm.cloneNode(true);
	document.getElementById("newItems").appendChild(cln);
	
	// Go through the div and set appropriate information
	cln.style.display = "block";
	cln.id = "specialSlot" + specialSlot;
	
	var children = document.getElementById('specialSlot' + specialSlot).childNodes;
		
	// Set all IDs and Names to the appropriate values
	for (i = 0; i < children.length; i++) {
		if ( children[i].hasAttribute("id") ) {
			children[i].id = children[i].id.replace("_", specialSlot);
		}
		if ( children[i].hasAttribute("name") ) {
			children[i].name = children[i].name.replace("_", specialSlot);
		}
	}
}

var OrSlot = 0;
function addSpecialOrItem(ele) {
	OrSlot++;
	// Copy the datalist box only this time
	var itm = document.getElementById("specialOrTemplate");
	var cln = itm.cloneNode(true);
	idNum = ele.id.substring(5);
	
	// Append to the appropriate selection
	document.getElementById("OrSlot" + idNum).appendChild(cln);

	// Set the new box to be visible
	cln.style.display = 'block';
	
	// Update ID to something unique
	cln.id = cln.id = cln.id + idNum + "SLOT" + OrSlot;
	
	var children = document.getElementById(cln.id).childNodes;
		
	// Set names to the appropriate values
	for (i = 0; i < children.length; i++) {
		if ( children[i].hasAttribute("name") ) {
			children[i].name = children[i].name.replace("_", idNum);
		}
	}
}

function deleteSpecials(ele)	{
	if (confirm("Are you sure you want to remove these Specials options?")) {
		var idNum = ele.id.substring(6);
		document.getElementById("specialSlot" + idNum).remove();
	}
}

function deleteSavedSpecials(ele)	{
	if (confirm("Are you sure you want to remove these Specials options?")) {
		var idNum = ele.id.substring(11);
		document.getElementById("savedSpecialSlot" + idNum).remove();
	}
}


// *****************************************************************
// ** AJAX FUNCTIONS

// **
// Updates item quantities
function AJAX_UpdateQty(callingSlot) {
	// Get the item ID and the new quantity from the page
	// ID = 'iqty' + familyKey + itemID
	var newQty = callingSlot.value;
	var familyType = familyTypeExtractor(callingSlot.id.substring(4,5));
	var itemID = callingSlot.id.substring(5);

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

// **
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

// **
// Updates category quantity values
function AJAX_UpdateCQty(callingSlot) {
	// Get the category ID and the new quantity from the page
	var newQty = callingSlot.value;
	var categoryID = callingSlot.id.substring(4);
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
	var name = document.getElementsByName(boxName);
	
	var runningTotal = 0;
	for (var i=0; i < name.length; i++) {
		if (name[i].checked) {
			// If we exceed our max count, don't let the box be checked and display a warning
			if (runningTotal >= MaxCount) {
				callingSlot.checked = false;
				window.alert("Cannot select more in this category");
				// Break out, since we know we're done here
				return;
			}
			runningTotal++;
		}
	}
	
	// Update the selected quantity and color it if we're at maximum
	var e = document.getElementById("Count" + nameField);
	if (runningTotal == MaxCount) {
		e.innerHTML = "Selections complete";
		e.style.color = "DodgerBlue";
	}
	else {
		e.innerHTML = "You may select up to " + MaxCount + " (" + (MaxCount - runningTotal) + " remaining)";
		e.style.color = "Black";
	}
}

// **************************************
// * Runs at the end of rof to update selection quantities
function updateCheckedQuantities()	{
	var inputs = document.getElementsByTagName('input');

	for(var i = 0; i < inputs.length; i++) {
		if(inputs[i].type.toLowerCase() == 'checkbox') {
			countOrder(inputs[i]);
		}
	}
}

// ********************************************
// * For creating tabs in the order form creation
function viewTab(evt, tabID) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabID.id).style.display = "block";
    evt.currentTarget.className += " active";
}