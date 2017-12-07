<script language="javascript" type="text/javascript">
function selectCountry(myvar){
	
	var myVar = myvar;
	if(myVar=='')
	{
		
	 document.getElementById('countrydiv').style.display='none';
	}
	else
	{
		
		document.getElementById('countrydiv').style.display='block';
	}
	return true;
}

function selectBrand(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('branddiv').style.display='none';
	else  document.getElementById('branddiv').style.display='block';
	return true;
}

function selectOrigin(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('origindiv').style.display='none';
	else  document.getElementById('origindiv').style.display='block';
	return true;
}
function selectType(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('typediv').style.display='none';
	else  document.getElementById('typediv').style.display='block';
	return true;
}
function selectProgram(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('programdiv').style.display='none';
	else  document.getElementById('programdiv').style.display='block';
	return true;
}
function selectCmp(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('cmpdiv').style.display='none';
	else  document.getElementById('cmpdiv').style.display='block';
	return true;
}

function selectSports(myvar)
{
	var myVar = myvar;
	var xmlhttp;
	if(myVar.value=='')
	 document.getElementById('sportsdiv').style.display='none';
	else  
	{
	  
		if (window.XMLHttpRequest)
  		{// code for IE7+, Firefox, Chrome, Opera, Safari
  			xmlhttp=new XMLHttpRequest();
  		}
		else
  		{// code for IE6, IE5
  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
  		{
  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    		document.getElementById('sportsdiv').style.display='block';
			document.getElementById("sportsdiv").innerHTML=xmlhttp.responseText;
    		}
  		}
		xmlhttp.open("GET","getSports.php?lan="+myVar.value,true);
		xmlhttp.send();
	
	}
	return true;
}

function selectLanguage(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('languagediv').style.display='none';
	else  document.getElementById('languagediv').style.display='block';
	return true;
}
function prevSubmit(myvar)
{
	var myVar = myvar;
	myvar = parseInt(myVar);
	if(myvar==1)
	{
	document.getElementById("pageNo").value=myvar;
	}
	else
	{
	document.getElementById("pageNo").value=myvar-1;
	}
	document.forms["reportFrm"].submit();
	 
}
function nextSubmit(myvar)
{
	var myVar = myvar;
	myvar = parseInt(myVar);
	document.getElementById("pageNo").value=myvar+1;
	document.forms["reportFrm"].submit();
	 
}
function dateValidation()
{
var frM = document.reportFrm.frM.value;
var toM = document.reportFrm.toM.value;
var frY = document.reportFrm.frY.value;
var toY = document.reportFrm.toY.value;
if((frY > toY) || ((frY == toY) && (frM > toM)))
{
	alert('Please Check the Date Range');
	return false;	
}
}
</script>