// *************************************
// ** Back functions

// To get url parameters using javascript, use this function
/*
Usage
Example URL:
.../index.php?id=1&image=awesome.jpg

Calling getQueryVariable("id") - would return "1".
Calling getQueryVariable("image") - would return "awesome.jpg".

Note: For multiple parameters, this currently requiers '&' be used as the separater.
Future scope should allow for ',' or other alternatives
*/
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&"); // TODO - make this check other options
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
// * For viewing tabs 
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