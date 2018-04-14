/*
* © 2018 Daniel Luxa ALL RIGHTS RESERVED
*/

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