function validateNewPartner() {
    var partnerLastName = document.getElementById("partnerNameField").value;;

    if (partnerLastName == "" || partnerLastName.length == 0 || partnerLastName == null) {
		getElementAndColorIt("partnerName", "red");
		alert("Partner must have a name");
        return false;
    } 
	else {
        return true;
    }
}

function validateNewRedistItem() {
    var itemName = document.getElementById("itemNameField").value;

    if (itemName == "" || itemName.length == 0 || itemName == null) {
		getElementAndColorIt("itemName", "red");
		alert("Item must have a name");
        return false;
    } 
	else {
        return true;
    }
}

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
var redistItemSlot = 0;
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
		if (children[i].nodeType != 3) {
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
}

// Removing an item from the invoice
function deleteRedistItem(ele)	{
	if (confirm("Are you sure you want to remove this item?")) {
		var idNum = ele.id.substring(6);
		document.getElementById("redistItemSlot" + idNum).remove();
	}
}
