function showChangePassword()
{
document.getElementById('changePassword').style.display='block';
document.getElementById('showChange1').style.display='block';
document.getElementById('showChange2').style.display='none';
document.getElementById('unsubscribePgm').style.display='none';
document.getElementById('oldpass').value = '';
document.getElementById('newpass').value = '';
document.getElementById('confirmpass').value = '';
document.getElementById('showerror').innerHTML='';

}
function UpdatePassword()
{
	document.getElementById('changePassword').style.display='block';
	document.getElementById('showChange1').style.display='block';
	document.getElementById('showChange2').style.display='none';
	document.getElementById('showerror').style.visibility='hidden';
	document.getElementById('showerror').innerHTML='';
	var oldpass = document.getElementById('oldpass').value;
	var newpass = document.getElementById('newpass').value;
	var confirmpass = document.getElementById('confirmpass').value;
	xmlHttp3=createAjaxFn();
	if (xmlHttp3==null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}
	url="change_password.php?oldpass="+oldpass+"&newpass="+newpass+"&confirmpass="+confirmpass;
	xmlHttp3.onreadystatechange=function()
	{
		if (xmlHttp3.readyState==4)
		{
			if(xmlHttp3.responseText=="success"){
			 document.getElementById('showChange2').style.display='block'; 
			 document.getElementById('showerror').style.visibility='hidden';
			 document.getElementById('showChange1').style.display='none';
			 }
			 else
			 {
			 document.getElementById('showerror').style.visibility='visible';
			 document.getElementById('showerror').innerHTML=xmlHttp3.responseText;
			 }
			
		}
	}
	xmlHttp3.open("GET",url,true);
	xmlHttp3.send(null);

}
function unsubscribe()
{
document.getElementById('unsubscribePgm').style.display='block';
document.getElementById('changePassword').style.display='none';
}
function hideUnsubscribe(styleid)
{
document.getElementById(styleid).style.display='none';
}
function unsubscribeMembership()
{
 	//alert(membershipId)
	document.getElementById('unsubscribePgm').innerHTML='<h2><img src=\"http://www.jiwok.com/templates/kalenji/images/close-button.gif\" onclick=\"hideUnsubscribe(\'unsubscribePgm\');\" alt=\"close\" title=\"close\" style=\"cursor:pointer;\" border=\"0\"\/><\/h2><h1>Please Wait</h1>';
	//document.getElementById('produiOverlayBox1').style.display='none';
	//document.getElementById('produiOverlayBox2').style.display='none';
	xmlHttp3=createAjaxFn();
	if (xmlHttp3==null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}
	url="membership_unsubscribe.php";
	xmlHttp3.onreadystatechange=function()
	{
		if (xmlHttp3.readyState==4)
		{
	
			if(xmlHttp3.responseText=="success")
			document.getElementById('unsubscribePgm').innerHTML='<h2><img src=\"http://www.jiwok.com/templates/kalenji/images/close-button.gif\" onclick=\"hideUnsubscribe(\'unsubscribePgm\');\" alt=\"close\" title=\"close\" style=\"cursor:pointer;\" border=\"0\"\/><\/h2><h1>&nbsp;</h1><p>Sans rancune :), votre abonnement se terminera le 05 Jun, 2011<\/p><p>&nbsp;<\/p><h3><input class=\"overlayBtn\" name=\"Yes\" type=\"button\" value=\"Fermer\" onclick=\"hideUnsubscribe(\'unsubscribePgm\')\" \/><\/h3>';
		}
	}
	xmlHttp3.open("GET",url,true);
	xmlHttp3.send(null);

}
function createAjaxFn()
{
  var xmlHttp;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
return xmlHttp;

}
function renewSubscriptionDisplay(display_style)
{
	document.getElementById('renewSubscriptionId').style.display	= display_style;
}
