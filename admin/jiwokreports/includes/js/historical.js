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

function selectWorkout(curid,num,workoutflexid,workdate)
{
    
  if(curid=='b')
   	document.getElementById(curid+num).className='day_Orange1today';
   else
    document.getElementById(curid+num).className='day_Orange1';	
   
   if(curid=='a')
   {
   for(var i=1;i<=31;i++)
  	{ 
	  if(i!= num)
	  {
		if(document.getElementById(curid+i) != null)
	      document.getElementById(curid+i).className = 'day_Tick';
	 }	 
	 
  	}
	callToday();callOrange2();
	}
	
	if(curid=='b')
   {
   for(var i=1;i<=31;i++)
  	{ 
	  if(i!= num)
	  {
		if(document.getElementById(curid+i) != null)
	      document.getElementById(curid+i).className = 'day_Today';
	 }	 
	 
  	}
	callTick();callOrange2();
	}
	
	if(curid=='c')
   {
   for(var i=1;i<=31;i++)
  	{ 
	  if(i!= num)
	  {
		if(document.getElementById(curid+i) != null)
	      document.getElementById(curid+i).className = 'day_Orange2';
	 }	 
	 
  	}
	callTick();callToday();
	}

}
function callOrange2()
{
for(var i=1;i<=31;i++)
  	{ 
	  	if(document.getElementById('c'+i) != null)
	      document.getElementById('c'+i).className = 'day_Orange2';
	}

}
function callTick()
{
for(var i=1;i<=31;i++)
  	{ 
	  	if(document.getElementById('a'+i) != null)
	      document.getElementById('a'+i).className = 'day_Tick';
	}

}
function callToday()
{
for(var i=1;i<=31;i++)
  	{ 
	  	if(document.getElementById('b'+i) != null)
	      document.getElementById('b'+i).className = 'day_Today';
	}

}
function showProgram(workoutFlexId,pgmid,workoutOrder,workDate,workoutFlexId_cal)
{
xmlHttp3=createAjaxFn();
if (xmlHttp3==null)
{
alert ("Browser does not support HTTP Request");
return;
}
url="showProgram1.php?workFlex="+workoutFlexId+"&pgmid="+pgmid+"&workOrder="+workoutOrder+"&workDate="+workDate+"&workoutFlexId_cal="+workoutFlexId_cal;
xmlHttp3.onreadystatechange=function(){
if (xmlHttp3.readyState==4)
{
  document.getElementById('produitLeftnew').style.display="block";
  document.getElementById('produitLeftnew').innerHTML=xmlHttp3.responseText;
}
}
xmlHttp3.open("GET",url,true);
xmlHttp3.send(null);

}
function showProgramEdit(workoutFlexId,pgmid,workoutOrder,workDate,workoutFlexId_cal,edit)
{
xmlHttp3=createAjaxFn();
if (xmlHttp3==null)
{
alert ("Browser does not support HTTP Request");
return;
}
url="showProgram1.php?workFlex="+workoutFlexId+"&pgmid="+pgmid+"&workOrder="+workoutOrder+"&workDate="+workDate+"&workoutFlexId_cal="+workoutFlexId_cal+"&feed="+feed;
xmlHttp3.onreadystatechange=function(){
if (xmlHttp3.readyState==4)
{
  document.getElementById('produitLeftnew').style.display="block";
  document.getElementById('produitLeftnew').innerHTML=xmlHttp3.responseText;
}
}
xmlHttp3.open("GET",url,true);
xmlHttp3.send(null);

}

function showEditComment(workoutFlex,pgmid,workoutOrder,work_date,work_Flex,feed)
{
document.getElementById('updateComment').style.display="block";
showProgramEdit(workoutFlex,pgmid,workoutOrder,work_date,work_Flex,feed);
}

function addComment1()
{
 var commentText = document.getElementById('comment_text1').value;
 var program_id = document.getElementById('program_id').value;
 commentText = commentText.replace(/^\s+|\s+$/g,'');
  program_id = program_id.replace(/^\s+|\s+$/g,'');
  if(program_id=="")
{
 alert("Please select program from calendar ");
  return false;
}
  if(commentText=="")
{
  alert("Please enter comment ");
  return false;
}

}
function addComment2()
{
 var commentText = document.getElementById('comment_text2').value;
  commentText = commentText.replace(/^\s+|\s+$/g,'');
  if(commentText=="")
{
  alert("Please enter comment ");
  return false;
}

}
