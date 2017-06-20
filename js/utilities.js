function goBack() {
    window.history.back();
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
function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");	// TODO - make this check other options
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

// Function to set a cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Function to remove a cookie
function removeCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime() - 1);
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=0;" + expires + ";path=/";
}

// Function to get a cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
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