var basePath = "/";

if ($("#perms").val() == 100) {
  basePath = "/RUMCPantry/";
}
// *********************************************************
// To get url parameters using javascript, use this function
/*
Usage
Example URL:
.../index.php?id=1&image=awesome.jpg

Calling getQueryVariable("id") - would return "1".
Calling getQueryVariable("image") - would return "awesome.jpg".
*/
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
}

// Function to set a cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Function to remove a cookie
function removeCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime() - 1);
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=0;" + expires + ";path=/";
}

// Function to get a cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(cname, ctext) {
  if (getCookie(cname) != "") {
			window.alert(ctext);
			removeCookie(cname);
	}
}

// To color an element by ID
function getElementAndColorIt(elementID, color) {
    var element = document.getElementById(elementID);
    element.style.color = color;
}

// For creating an AJAX object
function newAJAXObj() {
    // Run the AJAX stuff
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return (new XMLHttpRequest());
    } else {
        // code for IE6, IE5
        return (new ActiveXObject("Microsoft.XMLHTTP"));
    }
}

// For checking if an object is hidden
function isHiddenElement(e) {
    return (e.offsetHeight === 0 && e.offsetWidth === 0);
}


// ********************************************
// * For dealing with number inputs
$(document).on("keypress", ".input-number", function(key) {
  // Allow for tab/backspace/delete
  if (key.keyCode === 9 || key.keyCode === 8 || key.keyCode === 46) return true;

  // Allow for arrow keys 37-40 arrow keys
  if (key.keyCode >= 37 && key.keyCode <= 40) return true;

  // Only allow numeric values
  if(key.charCode < 48 || key.charCode > 57) return false;
});

// ********************************************
// * For dealing with number inputs
$(document).on("keypress", ".input-number-price", function(key) {
  // Allow for tab/backspace/delete/period
  if (key.keyCode === 9 || key.keyCode === 8 || key.keyCode === 46) return true;

  // Allow for arrow keys 37-40 arrow keys
  if (key.keyCode >= 37 && key.keyCode <= 40) return true;

  // Only allow numeric values
  if(key.charCode < 48 || key.charCode > 57) return false;
});

// *************************
// * Data table defaults
$.extend( $.fn.dataTable.defaults, {
  "info"          : true,
  "paging"        : true,
  "destroy"       : true,
  "searching"     : true,
  "processing"    : true,
  "serverSide"    : true,
  "ordering"      : true,
  "stateSave"     : true,
  "orderClasses"  : false,
  "autoWidth"     : false,
  "pagingType"    : "full_numbers",
	"lengthMenu"    : [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]

} );

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