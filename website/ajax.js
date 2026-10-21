var nocache = 0;
/* Required: var nocache is a random number to add to request. This value solve an Internet Explorer cache issue */
var http = createObject();
function insert()
{
	// Optional: Show a waiting message in the layer with ID login_response
	document.getElementById('insert_response').innerHTML = "Just a second..."
	// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
	var site_url= encodeURI(document.getElementById('site_url').value);
	var site_name = encodeURI(document.getElementById('site_name').value);
	// Set te random number to add to URL request
	nocache = Math.random();
	// Pass the login variables like URL variable
	http.open('get', 'insert.php?site_url='+site_url+'&site_name=' +site_name+'&nocache = '+nocache);
	http.onreadystatechange = insertReply;
	http.send(null);
}

function insertReply()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		// else if login is ok show a message: "Site added+ site URL".
		document.getElementById('insert_response').innerHTML = 'Site added:'+response;
	}
}

function createObject()
{
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer")
	{
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else{
		request_type = new XMLHttpRequest();
	}
	return request_type;
}


//
function HttpRequest(url, target)
{
	var pageRequest = false; //variable to hold ajax object
	/*@cc_on
	@if (@_jscript_version >= 5)
	try {
		pageRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e)
	{
		try {
			pageRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e2)
		{
			pageRequest = false;
		}
	}
	@end
	@*/
	if (!pageRequest && typeof XMLHttpRequest != 'undefined')
		pageRequest = new XMLHttpRequest();
	if (pageRequest)
	{
		//if pageRequest is not false
		pageRequest.open('GET', url, false); //get page synchronously 
		pageRequest.send(null);
                if(target != ''){
                    embedpage(pageRequest, target);
                }
	}
}

function embedpage(request, target)
{
	//if viewing page offline or the document was successfully retrieved online (status code=2000)
		if (window.location.href.indexOf("http")==-1 || request.status==200)
		document.getElementById(target).innerHTML = request.responseText;
}
