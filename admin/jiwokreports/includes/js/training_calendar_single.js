var req;

function navigate(month,year) {
        var url = "training_calendar_single.php?month="+month+"&year="+year;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}

function callback() {        
        obj = document.getElementById("calendarsingle");
          
		if(req.readyState == 4) {
                if(req.status == 200) {
                        response = req.responseText;
                        obj.innerHTML = response;
                        
                } else {
                        alert("There was a problem retrieving the data:\n" + req.statusText);
                }
        }
}
