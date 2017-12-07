// JavaScript Document
function myXMLHttpObject(handler)
{	
//alert(handler)
   var myRequest = null
	try 
	{	myRequest = new XMLHttpRequest()
	}
	catch (trymicrosoft) 
	{	try
		{	myRequest = new ActiveXObject("Msxml2.XMLHTTP")
  		}
		catch (othermicrosoft)
		{	try
			{	myRequest = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (failed)
			{	myRequest = null
    		}
		}
	}
	if (myRequest==null)
		alert("Error initializing XMLHttpRequest!")
	else
	{	if(handler!=null)
			myRequest.onreadystatechange=handler 
		return myRequest
	}
}
