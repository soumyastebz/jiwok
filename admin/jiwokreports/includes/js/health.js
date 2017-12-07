	//Returns the target of the event.
	function findTarget(e) {
		var element,e;
		if (!e) e = window.event;
		if (e.target) element = e.target;
		else if (e.srcElement) element = e.srcElement;
		if (element.nodeType == 3) element = element.parentNode;// defeat Safari bug
		return element;
	}
	//Copied from http://www.oreillynet.com/pub/a/javascript/excerpt/JSDHTMLCkbk_chap13/index6.html
	//Get the absolute position of any element.
	function getElementPosition(offsetTrail) {
		var offsetLeft = 0;
		var offsetTop = 0;
		while (offsetTrail) {
			offsetLeft += offsetTrail.offsetLeft;
			offsetTop += offsetTrail.offsetTop;
			offsetTrail = offsetTrail.offsetParent;
		}
		if (navigator.userAgent.indexOf("Mac") != -1 && 
			typeof document.body.leftMargin != "undefined") {
			offsetLeft += document.body.leftMargin;
			offsetTop += document.body.topMargin;
		}
	
		var xy = new Array(offsetLeft,offsetTop);
		return xy;
	}

	function $(id) 
	{ 
		return document.getElementById(id); 
	}	
	function showPopUnder(id,origin) {
	
		var width = 0;
		var element = $(id);
		element.style.display = "block";
		width = Number($(id).offsetWidth) - 55;
		if(width >= 200)
			width = 200;
		element.style.width = "0px";
		
		element.style.top = getElementPosition(origin)[1];
		element.style.left = getElementPosition(origin)[0];
		slideShow(id,width)
	}
	function hidePopUnder(id) {
		$(id).style.display = "none";
	}
	
	function slideClose(id) {
		var width = Number($(id).offsetWidth);
		$(id).style.width = (width - 5) + "px";
		
		if(width > 0) {
			//alert($("popup_one").offsetWidth + " : " + width);
			window.setTimeout("slideClose('"+id+"')",30);
		} else {
			$(id).style.display = "none";
		}
	}
	function slideShow(id,first_width) {
		var width = Number($(id).offsetWidth);
		$(id).style.width = (width + 55) + "px";
		
		if(width < first_width) {
			window.setTimeout("slideShow('"+id+"',"+first_width+")",30);
		}
	}

	//Code for ajax data retrieval
	function handleHttpResponseAddress() {	
		
	  if (http.readyState == 4) {
	  		
		// Split the comma delimited response into an array
	
		results = http.responseText;
		document.getElementById("popupforgot").style.display = "none";
		if(parseInt(results) == 2){
			var strHtml = "<div class=\"close\" align=\"center\"><a href=\"javascript:hidePopUnder('errMessage')\" class=\"blackwhite\"><b>X</b></a> </div>";
			strHtml+="<table width=\"100%\"><tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>";
			strHtml+= "<tr><td colspan=\"2\" align=\"center\">Your login details will be mailed to the provided e-mail address</td></tr>";
			strHtml+="<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr></table>";
			document.getElementById("errMessage").style.display = "block";
			document.getElementById("errMessage").innerHTML = strHtml;
		}else{
			var strHtml = "<div class=\"close\" align=\"center\"><a href=\"javascript:hidePopUnder('errMessage')\" class=\"blackwhite\"><b>X</b></a> </div>";
			strHtml+="<table width=\"100%\"><tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>";
			strHtml+= "<tr><td colspan=\"2\" align=\"center\" >The email address does not match any record in our database!!</td></tr>";
			strHtml+="<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr></table>";
			document.getElementById("errMessage").style.display = "block";
			document.getElementById("errMessage").innerHTML = strHtml;			
		}			
	  }
	
	}



	function retrievePwd(urlString,textBoxName) {
	  if(document.frmlogin.cust_email.value == ""){
	  	alert("Please enter your Email Address");
		document.frmlogin.cust_email.focus();
		exit();
	  }else{
	  	var re = new RegExp(/^[\w\.\-]+\@[\w\.\-]+\.[a-z\.]{2,6}$/);
		var str=document.frmlogin.cust_email.value;
		if(str.search(re)==-1)
		{
			alert("Please enter valid Email Address");		
			document.frmlogin.cust_email.focus();
			exit();
		}
	  }
	  var url = urlString+"?email";
	  var emailId = document.frmlogin.cust_email.value;	
	     
	  http.open("GET", url +"="+escape(emailId), true);
	
	  http.onreadystatechange = handleHttpResponseAddress;	
	  http.send(null);	
	}

function getHTTPObject() {

	  var xmlhttp;
	
	
	  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
	
		try {
	
		  xmlhttp = new XMLHttpRequest();
	
		} catch (e) {
	
		  xmlhttp = false;
	
		}

 	 }

  	return xmlhttp;
}

var http = getHTTPObject(); // We create the HTTP Object