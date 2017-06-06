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

// **********************
// Attempt at part of AJAX functionality (calling php functions)
// handles the click event for link 1, sends the query
/*
function getClientList(phpFileName) {
  getRequest(
		phpFileName, // URL for the PHP file
		drawOutput,  // handle successful request
		drawError    // handle error
  );
  return false;
}  
// handles drawing an error message
function drawError() {
    var container = document.getElementById('timeTable');
    container.innerHTML = 'Error';
}
// handles the response, adds the html
function drawOutput(responseText) {
    var container = document.getElementById('timeTable');
    container.innerHTML = responseText;
}
// helper function for cross-browser request object
function getRequest(url, success, error) {
    var req = false;
    try{
        // most browsers
        req = new XMLHttpRequest();
    } catch (e){
        // IE
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            // try an older version
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                return false;
            }
        }
    }
	if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error!= 'function') error = function () {};
    req.onreadystatechange = function(){
        if(req.readyState == 4) {
            return req.status === 200 ? 
                success(req.responseText) : error(req.status);
        }
    }
    req.open("GET", url, true);
    req.send(null);
    return req;
}
*/
// handles the click event for link 1, sends the query
function getSuccessOutput(phpAddress) {
  getRequest(
		phpAddress, // demo-only URL
       drawOutput,
       drawError
  );
  return false;
}

// handles the click event for link 2, sends the query
function getFailOutput() {
  getRequest(
      'invalid url will fail', // demo-only URL
       drawOutput,
       drawError
  );
  return false;
}

// handles drawing an error message
function drawError () {
    var container = document.getElementById('TestCell');
    container.innerHTML = 'Bummer: there was an error!';
}
// handles the response, adds the html
function drawOutput(responseText) {
    var container = document.getElementById('TestCell');
    container.innerHTML = responseText;
}
// helper function for cross-browser request object
function getRequest(url, success, error) {
    var req = false;
    try{
        // most browsers
        req = new XMLHttpRequest();
    } catch (e){
        // IE
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            // try an older version
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                return false;
            }
        }
    }
    if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error!= 'function') error = function () {};
    req.onreadystatechange = function(){
        if(req .readyState == 4){
            return req.status === 200 ? 
                success(req.responseText) : error(req.status);
        }
    }
    req.open("GET", url, true);
    req.send(null);
    return req;
}