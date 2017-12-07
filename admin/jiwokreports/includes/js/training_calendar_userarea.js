
function createNewAjax()
{
  var xmlHttpa;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttpa=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttpa=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttpa=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
return xmlHttpa;

}

function navigate(month,year) {
	  var reqa = createNewAjax();
        var url = "training_calendar_userarea.php?month="+month+"&year="+year;
		reqa.onreadystatechange=function(){
		if (reqa.readyState==4)
		{
			document.getElementById('calendar').innerHTML=reqa.responseText;
		}
		}
		
        reqa.open("GET", url, true);
        reqa.send(null);
}

/*var req;

function navigate(month,year) {
	  var req = createNewAjax();
        var url = "training_calendar_userarea.php?month="+month+"&year="+year;
		req.onreadystatechange=function(){
		if (req.readyState==4)
		{
			document.getElementById('calendar').innerHTML=req.responseText;
		}
		}
		
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}

function callback() {        
        var myobj = document.getElementById("calendar");
          
		if(req.readyState == 4) {
                if(req.status == 200) {
                        var response = req.responseText;
                        myobj.innerHTML = response;
                        
                } else {
                        alert("There was a problem retrieving the data:\n" + req.statusText);
                }
        }
}

*/