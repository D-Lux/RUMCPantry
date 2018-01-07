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

	xmlhttp = newAJAXObj();
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("ErrorLog").innerHTML = this.responseText;
			checkQuantitySelections();			
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setOrderForm.php?" +
				 "itemID=" + itemID + "&" +
				 "newQty=" + newQty + "&" +
				 "familyType=" + familyType, true);
	xmlhttp.send();
}

// **
// Updates item factors (Deprecated)
/*
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
*/

// **
// Updates category quantity values
function AJAX_UpdateCQty(callingSlot) {
	// Get the category ID and the new quantity from the page
	var newQty = callingSlot.value;
	var categoryID = callingSlot.id.substring(4);
	var familyType = familyTypeExtractor(callingSlot.id.substring(3,4));

	xmlhttp = newAJAXObj();
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("ErrorLog").innerHTML = this.responseText;
			checkQuantitySelections();
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setOrderForm.php?" +
				 "CID=" + categoryID + "&" +
				 "cQty=" + newQty + "&" +
				 "familyType=" + familyType, true);
	xmlhttp.send();
}

// *********************************
// * Checking Order form quantities

function checkQuantitySelections() {
	// Get the count of the category
	var categoryOptions = document.querySelectorAll(".CQty");
	var currCategoryCount = 0;
	for (i = 0; i < categoryOptions.length; i++) {
		var elem = categoryOptions[i];
		if (!isHiddenElement(elem)) {
			currCategoryCount = parseInt(elem.value);
		}
	}
	
	var categoryOptions = document.querySelectorAll(".IQty");
	var totalItemCount = 0;
	for (i = 0; i < categoryOptions.length; i++) {
		var elem = categoryOptions[i];
		if (!isHiddenElement(elem)) {
			var currItemCount = parseInt(elem.value);
			if (currItemCount > currCategoryCount) {
				window.alert("An item's allowed quantity should not be greater than the category quantity.");
			}
			totalItemCount += currItemCount;
		}
	}
	
	if (totalItemCount < currCategoryCount) {
		window.alert("There are not enough items in this category to match the selection quantity.");
	}
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

// * For the client order specifically to track beans (bagged vs canned)
function countBeans(callingSlot, uncheckName, showID, hideID, categoryID, counterName, Selection) {
	// Go through all bagged beans, unselect them and grey them out
	var name = document.getElementsByName(uncheckName);
	for (var i=0; i < name.length; i++) {
		name[i].checked = false;
	}
	var cannedBeanSection = document.getElementById(showID);
	cannedBeanSection.style.opacity = 1;
	var baggedBeanSection = document.getElementById(hideID);
	baggedBeanSection.style.opacity = 0.3;

	// Get the total number of items selectable in this item's category
	if (categoryID == 'BagBeans') {
		var MaxCount = 1;
	}
	else {
		var MaxCount = document.getElementById(categoryID).value;
	}
	
	// Find all other elements in the page that have the same name and tally up the check boxes	
	var beanCounter = document.getElementsByName(counterName);
	
	var runningTotal = 0;
	for (var i=0; i < beanCounter.length; i++) {
		if (beanCounter[i].checked) {
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
	var e = document.getElementById("CountBeans");
	if (runningTotal == MaxCount) {
		e.innerHTML = "Selections complete " + Selection;
		e.style.color = "DodgerBlue";
	}
	else {
		var maxCanCount = document.getElementById("CanBeans").value;
		e.innerHTML = "You may select up to " + maxCanCount + " cans, or 1 bag (" + (maxCanCount - runningTotal) + " remaining)";
		if (runningTotal == 0) {
			baggedBeanSection.style.opacity = 1;
		}
		e.style.color = "Black";
	}
}
function countCanBeans(callingSlot)	{
	countBeans(callingSlot, 'BagBeans[]', 'CannedBeansSection', 'BaggedBeansSection', 
				'CanBeans', 'CanBeans[]', '(Cans)');
}

function countBagBeans(callingSlot)	{
	countBeans(callingSlot, 'CanBeans[]', 'BaggedBeansSection', 'CannedBeansSection',
				'BagBeans', 'BagBeans[]', '(Bag)');
}

// **************************************
// * Runs at the end of rof.php to update selection quantities
function updateCheckedQuantities()	{
	var inputs = document.getElementsByTagName('input');

	for(var i = 0; i < inputs.length; i++) {
		if (( inputs[i].type.toLowerCase() == 'checkbox') &&  ( inputs[i].checked == true ) ){
			var eName = inputs[i].getAttribute('name');
			if (eName.toLowerCase().includes('canbeans')) {
				countCanBeans(inputs[i]);
			}
			else if (eName.toLowerCase().includes('bagbeans')){
				countBagBeans(inputs[i]);
			}
			else {
				countOrder(inputs[i]);
			}
			
		}
	}
}

function showSpecials() {
    document.getElementById("specialsSection").style.display = "block";
}

// ***************************************************
// * Validation of adding an item to a client's order form
function validateAddItemToInvoice() {
	var itemToAdd = document.getElementById("addItem");
	if (itemToAdd.value == "" || itemToAdd.value == null) {
		window.alert("Please select an item to be added");
		return false
	}
	var qtyToAdd = document.getElementById("addQty");
	if (qtyToAdd.value <= 0 || qtyToAdd.value == null) {
		window.alert("Please set a quantity greater than or equal to 1.");
		return false
	}
	return true
}

// **
// Delete an item from an invoice
function AJAX_RemoveFromInvoice(callingSlot) {
	// Calling slot is named to match the invoiceDescID that we want to remove
	var invoiceDescID = callingSlot.name;

	if (confirm("Do you want to remove this item?")) {
		// Run the AJAX stuff
		xmlhttp = newAJAXObj();

		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById('orderTable').deleteRow(callingSlot.parentNode.parentNode.rowIndex);
			}
		}
		xmlhttp.open("GET","/RUMCPantry/php/ajax/removeItemFromOrder.php?" +
					 "invoiceDescID=" + invoiceDescID, true);
		xmlhttp.send();
	}
}

// *********************************************************
// * Set an order to 'printed' when the print button is hit
function AJAX_SetInvoicePrinted(invoiceID) {
	// Run the AJAX stuff
	xmlhttp = newAJAXObj();
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText == "0") {
				window.print();
			}
			else {
				// Give a small alert if this was already printed, but bring up the window anyway
				window.alert("This order has already been printed");
				window.print();
			}
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setToPrinted.php?" +
				 "invoiceID=" + invoiceID, true);
	xmlhttp.send();
	
	
}