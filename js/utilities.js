// *************************************
// ** Back functions

function goBack() {
    switch (true) {
        case (location.pathname.includes("ap_ro2.php")):
        case (location.pathname.includes("ap_ro2i.php")):
        case (location.pathname.includes("ap_ro3.php")):
        case (location.pathname.includes("ap_ro4.php")):
        case (location.pathname.includes("ap_ro5.php")):
		case (location.pathname.includes("ap_ro5i.php")):
		case (location.pathname.includes("ap_ro8.php")):
            window.location.assign("/RUMCPantry/ap_ro1.php");
            break;
		case (location.pathname.includes("ap_ro9.php")):
		case (location.pathname.includes("ap_ro10.php")):
			window.location.assign("/RUMCPantry/ap_ro8.php");
			break;
		case (location.pathname.includes("ap_ro6.php")):
        case (location.pathname.includes("ap_ro7.php")):
            window.location.assign("/RUMCPantry/ap_ro5.php");
            break;

        case (location.pathname.includes("ap_ao2.php")):
        case (location.pathname.includes("ap_ao3.php")):
            window.location.assign("/RUMCPantry/ap_ao1.php");
            break;

        case (location.pathname.includes("ap_io1.php")):
        case (location.pathname.includes("ap_ao1.php")):
        case (location.pathname.includes("ap_co1.php")):
        case (location.pathname.includes("ap_co1i.php")):
        case (location.pathname.includes("ap_do1.php")):
        case (location.pathname.includes("ap_oo3.php")):
        case (location.pathname.includes("ap_ro1.php")):
        case (location.pathname.includes("checkIn.php")):
        case (location.pathname.includes("ap_oo1.php")):
            window.location.assign("/RUMCPantry/ap1.php");
            break;

        case (location.pathname.includes("ap_co2.php")):
        case (location.pathname.includes("ap_co3.php")):
            window.location.assign("/RUMCPantry/ap_co1.php");
            break;

        case (location.pathname.includes("ap_do2.php")):
        case (location.pathname.includes("ap_do3.php")):
        case (location.pathname.includes("ap_do4.php")):
        case (location.pathname.includes("ap_do5.php")):
        case (location.pathname.includes("donationOps.php")):
            window.location.assign("/RUMCPantry/ap_do1.php");
            break;

        case (location.pathname.includes("ap_io2.php")):
        case (location.pathname.includes("ap_io3.php")):
        case (location.pathname.includes("ap_io4.php")):
        case (location.pathname.includes("ap_io5.php")):
        case (location.pathname.includes("itemOps.php")):
        case (location.pathname.includes("ap_io6.php")):
            window.location.assign("/RUMCPantry/ap_io1.php");
            break;

        case (location.pathname.includes("ap_oo2.php")):
        case (location.pathname.includes("ap_oo5.php")):
            window.location.assign("/RUMCPantry/ap_oo1.php");
            break;

        case (location.pathname.includes("ap1.php")):
        case (location.pathname.includes("cap.php")):
        case (location.pathname.includes("ciup.php")):
        case (location.pathname.includes("cp1.php")):
            window.location.assign("/RUMCPantry/mainpage.php");
            break;

        case (location.pathname.includes("login.php")):
        case (location.pathname.includes("mainpage.php")):
            window.location.assign("/RUMCPantry/login.php");
            break;

        case (location.pathname.includes("cp2.php")):
        case (location.pathname.includes("cof.php")):
            window.location.assign("/RUMCPantry/cp1.php");
            break;

        case (location.pathname.includes("ap_oo4.php")):
            window.location.assign("/RUMCPantry/ap_oo3.php");
            break;
        case (location.pathname.includes("awc.php")):
            window.location.assign("/RUMCPantry/checkIn.php");
            break;

            //case (location.pathname.includes("ap_ao4.php")) :
            //case (location.pathname.includes("ap_co4.php")) :
            //case (location.pathname.includes("ap_co5.php")) :
        default:
            window.history.back();
    }
}

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