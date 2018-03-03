// Function to update hidden ID attributes
function updateHiddenID(caller) {
	list = caller.getAttribute('list');
	options = document.querySelectorAll('#' + list + ' option');
	hiddenInput = document.getElementById(caller.getAttribute('id') + '-hidden');
	label = caller.value;
	hiddenInput.value = label;
	for(var i = 0; i < options.length; i++) {
		var option = options[i];
		if(option.innerText === label) {
			hiddenInput.value = option.getAttribute('data-value');
			break;
		}
	}
}

// Adding a new item to the invoice
var redistItemSlot = 1;
var orderCount = 0;
function addRedistItem() {
	// Get a reference to the item template and copy it
	var itm = document.getElementById("addRedistItemTemplate");
	var cln = itm.cloneNode(true);
	document.getElementById("newItems").appendChild(cln);
	
	// Go through the div and set appropriate information
	cln.style.display = "block";
	cln.id = "redistItemSlot" + redistItemSlot;
	
	var children = cln.childNodes;

	// Set all IDs and Names to the appropriate values
	for (i = 0; i < children.length; i++) {
		// Skip over text nodes (they crash when hasAttribute is called)
		if (children[i].nodeType !=	Node.TEXT_NODE) {
			if ( children[i].hasAttribute("id") ) {
				children[i].id = children[i].id.replace("_", redistItemSlot);	
			}
			if ( children[i].hasAttribute("name") ) {
				children[i].name = children[i].name.replace("_", redistItemSlot);
			}
		}
	}
	
	// Increment our slot counter
	redistItemSlot++;
	
	// Increment our order counter
	orderCount++;
}

// Removing an item from the invoice
function deleteRedistItem(ele)	{
	if (confirm("Are you sure you want to remove this item?")) {
		var idNum = ele.id.substring(6);
		document.getElementById("redistItemSlot" + idNum).remove();
		
		// Decrement our order counter
		orderCount--;
	}
}


function validateRedistribution() {
	var errorCount = 0;
	var errorMsg = "";
	
	var partner = document.getElementById("partnerID").value;
	if (partner == "" || partner.length == 0 || partner == null) {
		getElementAndColorIt("PartnerField", "red");
		errorCount++;
		errorMsg += "Partner must have a name \n";
    }
	
	
	// Check the item boxes for values
	// We start at index 1 because the hidden div is the first one and we don't want to check that one
	var items = document.getElementsByName("item[]");
	var itemNameError = 0;
	for(var i = 1; i < items.length; i++) {
		getElementAndColorIt("redistItemSlot" + i, "black");
		var item = items[i];
		if (item.value == "" || item.value.length == 0 || item.value == null){
			errorCount++;
			getElementAndColorIt("redistItemSlot" + i, "red");
			itemNameError++;
		}
	}
	if (itemNameError > 1) {
		errorMsg += itemNameError + " items are missing names\n";
	}
	else if (itemNameError == 1) {
		errorMsg += "An item is missing a name\n";
	}
	
	
	
	// Check the qty boxes for good values
	// We start at index 1 because the hidden div is the first one and we don't want to check that one
	var qtys = document.getElementsByName("qty[]");
	var qtyError = 0;
	for(var i = 1; i < qtys.length; i++) {
		var qty = qtys[i];
		if (qty.value <= 0){
			errorCount++;
			getElementAndColorIt("redistItemSlot" + i, "red");
			qtyError++;
		}
	}
	if (qtyError > 1) {
		errorMsg += qtyError + " items have bad quantities\n";
	}
	else if (qtyError == 1) {
		errorMsg += "An item has a bad quantity\n";
	}
	
	// Check if we have any items in the order
	if (orderCount <= 0) {
		errorCount++;
		errorMsg += "Redistribution needs at least one item\n";
	}
	
	
	if (errorCount > 0) {
		alert("There are " + errorCount + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + errorMsg);
		return false;
	}
	
	return true;
	
}