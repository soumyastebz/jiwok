function toggle(displayText,toggleText) {

	var ele = document.getElementById(toggleText);

	var text = document.getElementById(displayText);

	if(ele.style.display == "block") {

    		ele.style.display = "none";

	}

	else {

		ele.style.display = "block";

	}

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

function htmlData(url,qStr,pgm_flexid,pgmid)

{

var url = url;

var qStr = qStr;

var tmpArr=qStr.split('=');

var pageNum	= parseInt(tmpArr[1],10);

document.getElementById('pageNum').value=pageNum;

var pgm_flexid = pgm_flexid;

var pgmid = pgmid;

if (url.length==0)

{

document.getElementById('produiMidBoxRight').innerHTML="";

return;

}

xmlHttp2=createAjaxFn();

if (xmlHttp2==null)

{

alert ("Browser does not support HTTP Request");

return;

}

url=url+"?"+qStr+"&pgm_flexid="+pgm_flexid+"&pgmid="+pgmid;



xmlHttp2.onreadystatechange=function(){

if (xmlHttp2.readyState==4)

{

document.getElementById('produiMidBoxRight').innerHTML=xmlHttp2.responseText;

}

}

xmlHttp2.open("GET",url,true);

xmlHttp2.send(null);

}



function htmlComment(url,qStr,pgmid,workoutflex)

{



var url = url;

var qStr = qStr;

var pgmid = pgmid;

var workoutflex = workoutflex;

if (url.length==0)

{

document.getElementById('produiRight2').innerHTML="";

return;

}

xmlHttp3=createAjaxFn();

if (xmlHttp3==null)

{

alert ("Browser does not support HTTP Request");

return;

}

url=url+"?"+qStr+"&pgmid="+pgmid+"&workoutflex="+workoutflex;



xmlHttp3.onreadystatechange=function(){

if (xmlHttp3.readyState==4)

{

document.getElementById('produiRight2').innerHTML=xmlHttp3.responseText;

}

}

xmlHttp3.open("GET",url,true);

xmlHttp3.send(null);

}

function loadNavs()

{

htmlData('workout_pagination_cal.php','p=<?=$workout_page?>','<?=$flexid?>','<?=$program_id?>');

htmlComment('comment_pagination_cal.php','pg=1','<?=$program_id?>','<?=$workoutflex?>');

navigate_1('<?=$mon?>','<?=$yr?>','<?=$a?>','<?=$day?>','<?=$workoutflex?>','<?=$workoutOrder_nav?>');

}

function unsubscribe()

{

document.getElementById('unsubscribePgm').style.display='block';

document.getElementById('produiOverlayBox1').style.display='none';

document.getElementById('produiOverlayBox2').style.display='none';

}

function hideUnsubscribe(styleid)

{

document.getElementById(styleid).style.display='none';

document.getElementById('genreSelect').style.display='none';

document.getElementById('generateSuccess').style.display='none';

for(var i=0; i < document.generate.genre.length; i++){

 document.generate.genre[i].checked = false;

}

document.generate.random1.checked = false;

document.generate.remchoice.checked = true;

}



function hideUnsubscribe1(styleid)

{

document.getElementById(styleid).style.display='none';

document.getElementById('genreSelect').style.display='none';

document.getElementById('generateSuccess').style.display='none';

document.getElementById('songsConfirm').style.display='none';

//window.location.reload(true);



}

function hideUnsubscribet(styleid)

{

document.getElementById(styleid).style.display='none';

}

function hideUnsubscribeTag(styleid)

{

document.getElementById(styleid).style.display='none';

document.getElementById('genreSelect').style.display='none';

document.getElementById('generateSuccess').style.display='none';

document.getElementById('songsConfirm').style.display='none';

document.getElementById('produiOverlayBox2').style.display='none';

//window.location.reload(true);



}

function unsubscribeProgram(subscribeId,pgmflexid)

{

document.getElementById('unsubscribePgm').style.display='none';

document.getElementById('produiOverlayBox1').style.display='none';

document.getElementById('produiOverlayBox2').style.display='none';

xmlHttp3=createAjaxFn();

if (xmlHttp3==null)

{

alert ("Browser does not support HTTP Request");

return;

}

url="training_unsubscribe.php?subscribeId="+subscribeId+"&flexid="+pgmflexid;

xmlHttp3.onreadystatechange=function(){

if (xmlHttp3.readyState==4)

{

if(xmlHttp3.responseText=="success")

  document.getElementById('otherSearch').style.display='block';

}

}

xmlHttp3.open("GET",url,true);

xmlHttp3.send(null);



}

function goHistoricalPage(pgmId,msg)

{

var workoutFlexId = document.getElementById('workoutcal_flexid').value;

workoutFlexId = workoutFlexId.replace(/^\s+|\s+$/g,'');

if(workoutFlexId=="")

{

  alert("Please select workout from calendar to comment");

  return false;

}

else

{

 window.location.href="historical.php?pgm_id="+pgmId+"&workoutFlexId="+workoutFlexId+"&ccess="+msg;

 return true;

}

}



function setWorkoutFlexId(workout_flex)

{

  if(document.getElementById('produiOverlayBox1').style.display!='block')

  {

  document.getElementById('produiOverlayBox1').style.display='block';

  }

  document.getElementById('unsubscribePgm').style.display='none';

	document.getElementById('produiOverlayBox2').style.display='none';

  document.getElementById('workoutFlex').value=workout_flex;



}



function addWorkoutComment()

{

  document.getElementById('produiOverlayBox1').style.display='block';

   document.getElementById('unsubscribePgm').style.display='none';

	document.getElementById('produiOverlayBox2').style.display='none';

  var commentText = document.getElementById('commentText').value;

  commentText = commentText.replace(/^\s+|\s+$/g,'');

  if(commentText=="")

{

  alert("Please enter comment for workout");

  return false;

}



}



function uncheckAllGenre(generate) {

var theForm = generate.form, z = 0;

 for(z=0; z<theForm.length;z++){

  if(theForm[z].type == 'checkbox' && theForm[z].name != 'random1' && theForm[z].name != 'remchoice' && theForm[z].name != 'vocal_type'){

  if(theForm[z].checked)

  	{theForm[z].checked= false;}

  }

 }

}



function uncheckRandom(generate) {

var theForm = generate.form, z = 0;

generate.form.random1.checked=false;

 }

 function cancelConfirm(pgm_flexid)

 {

 	document.getElementById('songsConfirm').style.display='none';

  	document.getElementById('genreSelect').style.display='block';

  	document.getElementById('showRefresh').style.display='block';

 	document.getElementById('remember').value='';

	document.getElementById('vocal_type_h').value='';

    document.getElementById('genList').value='';

 }

 

 function confirmSongs(pgm_flexid,checkvalue,totalFile)

 {

 

 document.getElementById('songsConfirm').style.display='none';

  document.getElementById('genreSelect').style.display='block';

  document.getElementById('showRefresh').style.display='block';

	var remchoice = 0;

 if(document.getElementById('remchoice').checked)

     remchoice=1;

 var workout_Flex = document.getElementById('workout_Flex').value;
 
 var pageNum=document.getElementById('pageNum').value;

 var vocal_type	= 2;

 if(document.getElementById('vocal_type').checked) {

 	vocal_type = 1;

 }

 var genreArray = new Array();

 var numfiles = new Array();

 var j=0; 

 var file_count = 0;

 if(checkvalue!='')

 {

 if(document.generate.genre.checked)

	{

		var gen = document.generate.genre.value;

		numfiles = gen.split("_");

		genreArray[j] = numfiles[0];

		file_count = (file_count*1)+ (numfiles[1]*1);

		j++;

	}

 

 }

 else{


for(var i=0; i < document.generate.genre.length; i++){

if(document.generate.genre[i].checked)

{

var gen=document.generate.genre[i].value;

numfiles = gen.split("_");

genreArray[j] = numfiles[0];

file_count = (file_count*1)+ (numfiles[1]*1);

j++;

}

}

}



if((j==0) && !(document.generate.random1.checked))

 { 

  document.getElementById('genreError').style.display='block';

 }

 else if((document.generate.random1.checked) && (totalFile<20))

 { 

  var genreList = genreArray.join(','); 

  document.getElementById('songsConfirm').style.display='block';

  document.getElementById('genreSelect').style.display='none';

  document.getElementById('showRefresh').style.display='none';

  document.getElementById('genreError').style.display='none';

  document.getElementById('remember').value=remchoice;

  document.getElementById('vocal_type_h').value=vocal_type;

  document.getElementById('genList').value=genreList;

 }

 else if(!(document.generate.random1.checked) && file_count<20)

 { 

  var genreList = genreArray.join(','); 

  document.getElementById('songsConfirm').style.display='block';

  document.getElementById('genreSelect').style.display='none';

  document.getElementById('showRefresh').style.display='none';

  document.getElementById('genreError').style.display='none';

  document.getElementById('remember').value=remchoice;

  document.getElementById('vocal_type_h').value=vocal_type;

  document.getElementById('genList').value=genreList;

 }

 else

{

    document.getElementById('songsConfirm').style.display='none';

	document.getElementById('genreError').style.display='none';

	var genreList = genreArray.join(','); 

	xmlHttp31=createAjaxFn();
	//alert(pageNum);
	url="generateMP3.php?pgmFlex="+pgm_flexid+"&workFlex="+workout_Flex+"&genList="+genreList+"&rem="+remchoice+"&vocal="+vocal_type+'&pageNum='+pageNum;
	
	//alert(url);

	xmlHttp31.onreadystatechange=function(){

	if (xmlHttp31.readyState==4)

	{

		if(xmlHttp31.responseText=="success")

		{

		    document.getElementById('genreSelect').style.display='none';

			document.getElementById('showRefresh').style.display='none';

			document.getElementById('generateSuccess').style.display='block';

		}	

	}

	}

xmlHttp31.open("GET",url,true);

xmlHttp31.send(null);



} 



 }

 

 function insertGenres(pgm_flexid) {

  var workout_Flex = document.getElementById('workout_Flex').value;

  var remember = document.getElementById('remember').value;

  var genreList = document.getElementById('genList').value;

  var vocal_type_h = document.getElementById('vocal_type_h').value;
  
  var pageNum=document.getElementById('pageNum').value;

 xmlHttp32=createAjaxFn();

	url="generateMP3.php?pgmFlex="+pgm_flexid+"&workFlex="+workout_Flex+"&genList="+genreList+"&rem="+remember+"&vocal="+vocal_type_h+"&pageNum="+pageNum;


	xmlHttp32.onreadystatechange=function(){

	if (xmlHttp32.readyState==4)

	{

		if(xmlHttp32.responseText=="success")

		{

		    document.getElementById('genreSelect').style.display='none';

			document.getElementById('showRefresh').style.display='none';

			document.getElementById('songsConfirm').style.display='none';

			document.getElementById('genreError').style.display='none';

			document.getElementById('generateSuccess').style.display='block';

		}	

	}

	}

xmlHttp32.open("GET",url,true);

xmlHttp32.send(null);



} 

 

function showGenres(flexid)

{



 xmlHttp33=createAjaxFn();

var workout_Flex = document.getElementById('workout_Flex').value;

url="showGenres.php?flexid="+flexid+"&workFlex="+workout_Flex;

xmlHttp33.onreadystatechange=function(){

if (xmlHttp33.readyState==4)

{

document.getElementById('genreSelect').innerHTML=xmlHttp33.responseText;

}

}

xmlHttp33.open("GET",url,true);

xmlHttp33.send(null);

}



function selectWorkout(curid,num,workoutflexid,work_order)

{

   document.getElementById('workoutcal_flexid').value = workoutflexid+"@"+work_order;  

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



function showGenerateType(workoutFlex)
{
 if(document.getElementById('produiOverlayBoxType').style.display!='block')
 {
 document.getElementById('produiOverlayBoxType').style.display='block';
 document.getElementById('produiOverlayBox2').style.display='none';
 document.getElementById('genreSelect').style.display='block';
 document.getElementById('songsConfirm').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('errorPayment').style.display='none';
 document.getElementById('alreadyGenerated').style.display='none';
 document.getElementById('giftCodeUserMp3Alert').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
 document.getElementById('generateTypeError').style.display='none';
 document.getElementById('workout_Id_Flex').value = workoutFlex;
 }
}



function showGenerateOverlay(workoutFlex)
{
 if(document.getElementById('produiOverlayBox2').style.display!='block')
 {
 document.getElementById('produiOverlayBox2').style.display='block';
 document.getElementById('produiOverlayBox2').style.zIndex="1";
 document.getElementById('genreSelect').style.display='block';
 document.getElementById('songsConfirm').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('errorPayment').style.display='none';
 document.getElementById('alreadyGenerated').style.display='none';
 document.getElementById('giftCodeUserMp3Alert').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
 document.getElementById('workout_Flex').value = workoutFlex;
 document.getElementById('genList').value = '';
 document.getElementById('remember').value = '';
 document.getElementById('vocal_type_h').value = '';
}
}

function showGenerateOverlay1(workoutFlex)
{
 if(document.getElementById('produiOverlayBox2').style.display!='block')
 {
 document.getElementById('produiOverlayBox2').style.display='block';
 document.getElementById('produiOverlayBox2').style.zIndex="1";
 document.getElementById('errorPayment').style.display='block';
 document.getElementById('refrsh').style.display='none';
 document.getElementById('alreadyGenerated').style.display='none';
 document.getElementById('giftCodeUserMp3Alert').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('genreSelect').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
 document.getElementById('workout_Flex').value = workoutFlex;
 document.getElementById('genList').value = '';
 document.getElementById('remember').value = '';
 document.getElementById('vocal_type_h').value = '';
}
}

function showGenerateOverlay2()
{
 if(document.getElementById('produiOverlayBox2').style.display!='block')
 {
 document.getElementById('produiOverlayBox2').style.display='block';
 document.getElementById('produiOverlayBox2').style.zIndex="1";
 document.getElementById('alreadyGenerated').style.display='block';
 document.getElementById('giftCodeUserMp3Alert').style.display='none';
 document.getElementById('showRefresh').style.display='none';
 document.getElementById('songsConfirm').style.display='none';
 document.getElementById('errorPayment').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('genreSelect').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
}
}

function thrityDayValidationGiftCodeUsers()
{
 if(document.getElementById('produiOverlayBox2').style.display!='block')
 {
 document.getElementById('produiOverlayBox2').style.display='block';
 document.getElementById('produiOverlayBox2').style.zIndex="1";
 document.getElementById('giftCodeUserMp3Alert').style.display='block';
 document.getElementById('alreadyGenerated').style.display='none';
 document.getElementById('showRefresh').style.display='none';
 document.getElementById('songsConfirm').style.display='none';
 document.getElementById('errorPayment').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('genreSelect').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
}
}

function loadTagOfflineTest()
{
 document.getElementById('produiOverlayBox2').style.display='none';
 document.getElementById('tagOfflineDetect').style.display='none';
}

function showGenerateOverlayTag()
{
 if(document.getElementById('produiOverlayBox2').style.display!='block')
 {
 document.getElementById('produiOverlayBoxType').style.display='none';
 document.getElementById('produiOverlayBox2').style.display='block';
 document.getElementById('produiOverlayBox2').style.zIndex="1";
 document.getElementById('tagOfflineDetect').style.display='block';
 document.getElementById('alreadyGenerated').style.display='none';
 document.getElementById('giftCodeUserMp3Alert').style.display='none';
 document.getElementById('showRefresh').style.display='none';
 document.getElementById('songsConfirm').style.display='none';
 document.getElementById('errorPayment').style.display='none';
 document.getElementById('generateSuccess').style.display='none';
 document.getElementById('genreSelect').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
}
}





function showGenerateOverlayTag2()
{
var valueGenreTmp = '';
for(var i=0; i < document.generatetype.genreType.length; i++){
		if(document.generatetype.genreType[i].checked)
		{
	 		valueGenreTmp = valueGenreTmp + document.generatetype.genreType[i].value;
     	}
	}

if(valueGenreTmp == 1){
confirmGenreType('<?=$flexid?>');
}

if(valueGenreTmp == 2){
showGenerateOverlayTag();
}
}



function showGenerateOverlay3(workoutFlex)
{
 if(document.getElementById('produiOverlayBox3').style.display!='block')
 {
 document.getElementById('produiOverlayBox3').style.display='block';
 document.getElementById('jiwokGenreSelect').style.display='block';
 document.getElementById('downloadSuccess').style.display='none';
 document.getElementById('unsubscribePgm').style.display='none';
 document.getElementById('produiOverlayBox1').style.display='none';
 document.getElementById('produiOverlayBox2').style.display='none';
 document.getElementById('workout_Fid').value = workoutFlex;
 document.getElementById('jiwokGenList').value = '';
 }
}



function confirmGenreType(pgm_flexid)
 {
    var workout_Flex = document.getElementById('workout_Id_Flex').value;
	var j=0;
	var valueGenre = '';
	for(var i=0; i < document.generatetype.genreType.length; i++){
		if(document.generatetype.genreType[i].checked)
		{
	 		valueGenre = valueGenre + document.generatetype.genreType[i].value;
			j++;
     	}
	}	

	if(j>0)
	{
		if(valueGenre==1)
			{ document.getElementById('produiOverlayBoxType').style.display='none';showGenerateOverlay3(workout_Flex); }
			else { document.getElementById('produiOverlayBoxType').style.display='none';showGenerateOverlay(workout_Flex); }		
	}
	else
	{
	document.getElementById('generateTypeError').style.display='block';
	}
 }

 function downloadFile(pgm_flexid)
 {
 document.getElementById('jiwokGenreSelect').style.display='block';
 var workout_Flex = document.getElementById('workout_Fid').value;
 var genreArray = new Array();
 var j=0;
 for(var i=0; i < document.downloadmp3.jiwokGenre.length; i++){
	if(document.downloadmp3.jiwokGenre[i].checked)
	{
  		genreArray[i] = '1';
		j++;
	}
	else
		genreArray[i] = '0';
}

if((j==0) ||(j>1))
 { 
  document.getElementById('jiwokGenreError').style.display='block';
 }
 else
{
    document.getElementById('jiwokGenreSelect').style.display='none';
	document.getElementById('jiwokGenreError').style.display='none';
	var pgmid = document.getElementById('program_id').value;
	var genreList = genreArray.join(','); 
	xmlHttp31=createAjaxFn();
	url="downloadFile.php?pgmFlex="+pgm_flexid+"&workFlex="+workout_Flex+"&jiwokGenList="+genreList+"&pgmid="+pgmid;
	xmlHttp31.onreadystatechange=function(){
	if (xmlHttp31.readyState==4)
	{
		document.getElementById('downloadSuccess').style.display='block';
		document.getElementById('downloadSuccess').innerHTML=xmlHttp31.responseText;
		downloadOriginForceFile2();
	}
	}
	xmlHttp31.open("GET",url,true);
	xmlHttp31.send(null);
} 
 }

 

 function showOriginForceDownload(workoutid)
 {
 	xmlHttp31=createAjaxFn();
	url="downloadOriginFile.php?workoutid="+workoutid;
	xmlHttp31.onreadystatechange=function(){
	if (xmlHttp31.readyState==4)
	{
	   document.getElementById('produiOverlayBox4').style.display='block';
	   document.getElementById('downloadSuccessOrigin').style.display='block';
	   document.getElementById('downloadSuccessOrigin').innerHTML=xmlHttp31.responseText;
	}
	}
	xmlHttp31.open("GET",url,true);
	xmlHttp31.send(null);
} 

function downloadOriginForceFile() {
 document.getElementById('download_btn').style.display = 'none';
 document.getElementById('download_btn').style.visibility = 'hidden';
 document.getElementById('loader_image').style.display = 'block';
 document.getElementById('loader_image').style.visibility = 'visible';
 setTimeout("startDownload()",20000);
}

function startDownload() {

	document.getElementById('loader_image').style.visibility = 'hidden';
	document.getElementById('loader_image').style.display = 'none';
	try{
	document.getElementById('download_btn').style.visibility = 'none';
	document.getElementById('download_btn').style.display = 'hidden';
	}catch(e){
	}

	document.getElementById('dwnmsg').style.display = 'none';
 	document.getElementById('dwnmsg').style.visibility = 'hidden';
	document.getElementById('alter_download').style.visibility = 'visible';
	document.getElementById('alter_download').style.display = 'block';
	document.getElementById('alter_download2').style.visibility = 'visible';
	document.getElementById('alter_download2').style.display = 'block';
	var id = document.getElementById('download_id').value;
	if (id!=null || id!='') {
		var url='http://www.jiwok.com/downloadOriginMP3.php?work=' + id;
		location.href = url;
	}
	return false;
}



function downloadOriginForceFile2(){
 document.getElementById('download_btn2').style.display = 'none';
 document.getElementById('download_btn2').style.visibility = 'hidden';
 document.getElementById('loader_image2').style.display = 'block';
 document.getElementById('loader_image2').style.visibility = 'visible';
 setTimeout("startDownload2()",20000);
}

function startDownload2() {
	document.getElementById('loader_image2').style.visibility = 'hidden';
	document.getElementById('loader_image2').style.display = 'none';
	document.getElementById('download_btn2').style.visibility = 'visible';
	document.getElementById('download_btn2').style.display = 'block';
	document.getElementById('alter_download').style.visibility = 'visible';
	document.getElementById('alter_download').style.display = 'block';
	document.getElementById('alter_download2').style.visibility = 'visible';
	document.getElementById('alter_download2').style.display = 'block';

		var work = document.getElementById('work').value;
		var gen  = document.getElementById('gen').value;
		var pid  = document.getElementById('program_id').value;

	if (work!=null || work!='' || gen!=null || gen!='' ) {
		var url2='download_static_MP3.php?work='+work+'&gen='+gen+'&pid='+pid;
		location.href = url2;
	}
	return false;

}

