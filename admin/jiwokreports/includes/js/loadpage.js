function createAjaxFn()	{
	var xmlHttp;
	try{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e){
		// Internet Explorer
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e){
			try {
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {
				alert("Your browser does not support AJAX!");
				return false;
			}
		}
	}
	return xmlHttp;
}
function loadPage(url,qStr,pgmIds){

	var url 	= url;
	var qStr 	= qStr;
	var pgmIds 	= pgmIds;
	if (url.length==0){
		document.getElementById('listContainer').innerHTML="";
		return;
	}
	xmlHttp1=createAjaxFn();
	if (xmlHttp1==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	url=url+"?"+qStr+"&"+pgmIds;
	//alert(url);
		xmlHttp1.onreadystatechange=function(){
		if (xmlHttp1.readyState==4)	{
		    //alert(xmlHttp1.responseText);
			document.getElementById('listContainer').innerHTML=xmlHttp1.responseText;
		}
	}
	xmlHttp1.open("GET",url,true);
	xmlHttp1.send(null);	
}