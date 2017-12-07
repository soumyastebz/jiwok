<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
error_reporting(1);
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	
	$reportObj = new Report();
	
	$countryArray   = $reportObj->_getCountries();
	$brandArray		= $reportObj->getAllBrandName();
	$discArray      = $reportObj->getDiscountCodesUsed();
	
	$heading = "Subscription Reports";
	$todayDetails = getdate();	
	
	$_POST['report_criteria']  =1;	
		
	if($_POST['grouping']!='')
	{
		 $grouping = trim($_POST['grouping']);
		if($grouping=='brand_name')
		{
			if($_REQUEST['export_to']== 2)
				$fileNameArray[1] = 'QueryExport1.1.txt';
			else	
				$fileNameArray[1] = 'Query1.1.txt';		
		}
		if($grouping=='Country')
		{
			if($_REQUEST['export_to']== 2)
				$fileNameArray[1] = 'QueryExport1.2.txt';
			else	
				$fileNameArray[1] = 'Query1.2.txt';	
		}
		if($grouping=='Gender')
		{
			if($_REQUEST['export_to']== 2)
				$fileNameArray[1] = 'QueryExport1.3.txt';
			else	
				$fileNameArray[1] = 'Query1.3.txt';				
		}
		if($grouping=='Age')
		{
			if($_REQUEST['export_to']== 2)
				$fileNameArray[1] = 'QueryExport1.4.txt';
			else	
				$fileNameArray[1] = 'Query1.4.txt';	
		}			
		if($grouping=='origin')
		{
			if($_REQUEST['export_to']== 2)
				$fileNameArray[1] = 'QueryExport1.5.txt';
			else	
				$fileNameArray[1] = 'Query1.5.txt';				
		}
	
	}
	else
	{
		$fileNameArray = array('1'=>'Query1.txt','2'=>'Query2.txt','3'=>'Query3.txt','4'=>'Query4.txt','5'=>'Query5.txt','6'=>'Query6.txt',
							  '7'=>'Query7.txt','8'=>'Query8.txt','9'=>'Query9.txt','10'=>'Query10.txt','11'=>'Query11.txt','12'=>'Query12.txt','13'=>'Query13.txt');
	}
	if($_REQUEST['export_to']== 2)
		$fileNameArray[1] = 'QueryExport1.txt';
	
	$reportTitleArray =  array('1'=>'Subscription Reports','2'=>'Subscribers Origin Reports','3'=>'Subscribers Reports : Plan Wise','4'=>'Subscribers Doing Test : 1 Euro or First Workout','5'=>'Subscriber Reports Using Gift Code','6'=>'1 Euro Transaction Only',
							  '7'=>'1 Euro to 7.9 Euro Next Month','8'=>'Average Monthly Subscribed','9'=>'Paid Subscribers Reports','10'=>'Training Program Most Subscribed','11'=>'New Subscriber/New Register Reports','12'=>'Existing Subscriber/Existing Register Reports','13'=>'Revenue Reports');
	$reportNameArray =  array('1'=>'Subscription-Reports','2'=>'Origin-Reports','3'=>'Plan-Wise','4'=>'1Euro-FirstWorkout','5'=>'Gift-Code','6'=>'1Euro-Trans',
							  '7'=>'1Euro-7.9','8'=>'Average-Subscribed','9'=>'Paid-Subscribers','10'=>'Training-Program','11'=>'New-Subscriber-Register','12'=>'Existing-Subscriber-Register','13'=>'Revenue-Reports');
	
	$detail_month = '-1 '.'month';
	$lastMonthsDetails = getdate(strtotime($detail_month));
	
	
	if($_POST['report_criteria'])
	{
	$report_criteria = trim($_POST['report_criteria']);
	if($_POST['frD']!='' &&$_POST['frM']!='' && $_POST['frY']!='' &&  $_POST['toD']!='' &&  $_POST['toM']!='' &&  $_POST['toY']!='')
	{	
	$frD = $_POST['frD'];
	$frM = $_POST['frM'];
	$frY = $_POST['frY'];
	$toD = $_POST['toD'];
	$toM = $_POST['toM'];
	$toY = $_POST['toY'];	
	
	/*if($toM=='12')
	{
	$toM = '01';
	$toY = $toY+1;
	}
	else
	$toM  = $toM+1;*/
	}
	else
	{		
		$frD = "01";
		$frM = date('m');
		$frY = date('Y');
		//$toD = date("d",mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));		
		$toD =	date('t', strtotime('now'));
		$toM =  date('m');
		$toY = date('Y');
		/*if(strlen($lastMonthsDetails['mon'])==1)
		$frM = "0".$lastMonthsDetails['mon'];
		else
		$frM = $lastMonthsDetails['mon'];
		
	    $frY = $lastMonthsDetails['year'];
	    $toM = date('m');
	    $toY = date('Y');*/
		/*$frM 	= "01";
		$toM = date('m');
		$frY 	= "2005";
		$toY = date('Y');
		$all = 1;*/
	}
	
	if($_POST['country_select'] == '1'){
		if($_POST['user_country_1']!="")
		{
			$countryCondn = $_POST['user_country_1'];
		}
		elseif($_POST['user_country']!="")
		{
		$user_country = implode(',',$_POST['user_country']);
		$countryCondn = " AND user_country IN(". $user_country.") ";
		}
	}
		/*if($_POST['grouping']=="brand_name")
	{
	echo  $_POST['user_gender_1'];
	die;
	}	*/
	if($_POST['user_gender_1']!="")
	{
		$genderCondn = $_POST['user_gender_1'];
	}
	elseif($_POST['user_gender']!=""){
		$user_gender = implode(',',$_POST['user_gender']);
		$genderCondn = " AND user_gender IN(". $user_gender.") ";
		
	}

	/*if($_POST['user_origin']!=""){
		$originCondn = " AND origin='".trim($_POST['user_origin'])."'";
		if(trim($_POST['user_origin'])=='1')
		{
			if(trim($_POST['code'])!="")
			{
				$discCondn = " AND discount_code='".trim($_POST['code'])."'";
			}
		}
	
	}*/
	if($_POST['origin_select'] == '1'){
		if($_POST['user_origin_1']!="")
		{
			$originCondn = stripslashes($_POST['user_origin_1']);
		}
		elseif($_POST['user_origin']!="")
		{		
		$user_origin = implode(',',$_POST['user_origin']);		
		$originCondn = " AND origin IN(". $user_origin.") ";			
		}	
	}
	
	
	
	
	
	if(!$_POST['user_fromage']  && !$_POST['user_toage'])
	{
		$_POST['user_fromage']  = 1;
		$_POST['user_toage'] = 99;
	}
	$fromage = trim(stripslashes($_POST['user_fromage']));
	$toage   = trim(stripslashes($_POST['user_toage']));
	
	if(trim(stripslashes($_POST['user_fromage']))!=""){
		$ageCondn1 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' )))))>=".$fromage;
	}
	if(trim(stripslashes($_POST['user_toage']))!=""){
		$ageCondn2 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' )))))<=".$toage;
	}
	if(isset($ageCondn1) && isset($ageCondn2))
	{
		$ageCondn1 = " AND FLOOR(extract(YEAR FROM from_days(DATEDIFF(curdate(), STR_TO_DATE( a.user_dob, '%d/%m/%Y' ))))) BETWEEN ".$fromage." AND ".$toage;
		$ageCondn2 = "";
	}
	
	/*if(trim(stripslashes($_POST['user_program']))!=""){
		if(trim(stripslashes($_POST['user_program']))=='Yes')
		  { $programCondn = " AND training=1 "; }
		else
		  { $programCondn = " AND training=0"; }
	}*/
	if($_POST['program_select'] == '1'){
		if($_POST['user_program_1']!="")
		{
			$programCondn = stripslashes($_POST['user_program_1']);
		}
		elseif($_POST['user_program']!="")
		{		
		$user_program = implode(',',$_POST['user_program']);		
		$programCondn = " AND training IN(". $user_program.") ";			
		}	
	}	
	
	//print_r($_POST['user_brand']);
	if($_POST['brand_select'] == '1'){
		if($_POST['user_brand_1']!="")
		{
			$brandCondn = stripslashes($_POST['user_brand_1']);
		}
		elseif($_POST['user_brand']!="")
		{		
		$user_brand = implode(',',$_POST['user_brand']);
		$brandCondn = "  AND (";
		$conditionArray = array();
		foreach ($_POST['user_brand'] as $value) {
			if(trim($value)=='0')
				{ 
					$conditionArray[]= " b.brand_master_id IS NULL ";
				}
				else{  
		    	 $conditionArray[]= " b.brand_master_id=".$value; 
				}
			  
		}
		$brandCondn.= implode(' OR',$conditionArray);
		$brandCondn .= " ) ";		
		}	
	}
	
	if($_POST['type_select'] == '1'){
		if($_POST['user_type_1']!="")
		{
			$typeCondn = stripslashes($_POST['user_type_1']);
		}
		elseif($_POST['user_type']!="")
		{		
		$user_type = implode(',',$_POST['user_type']);		
		$typeCondn = " AND type IN(". $user_type.") ";			
		}	
	}	
	/*if(trim(stripslashes($_POST['user_type']))!=""){
		
			$typeCondn = " AND type='".trim($_POST['user_type'])."'";
	}*/
			
	$myFile1 = trim($_POST['report_criteria']);
	$myFile = $fileNameArray[$myFile1];
	$fh = fopen($myFile, 'r');
	$reportQuery = fread($fh, filesize($myFile));
	fclose($fh);
	/* for to range, month will be taken as selected month+1. For example, if start range is 03-2001 and end range is  04-2001,
	then it is taken as 01-03-2001 and 01-05-2001.
	*/
	//$reportQuery = sprintf($reportQuery,$frM,$frY,$toM,$toY,$countryCondn);
	
	$reportQuery = sprintf($reportQuery,$frD,$frM,$frY,$toD,$toM,$toY,$countryCondn,$genderCondn,$originCondn,$discCondn,$ageCondn1,$ageCondn2,$brandCondn,$typeCondn,$programCondn);	
	$qry = trim($reportQuery);	
	$result = mysql_query(trim($qry)) or die(mysql_error());
	
	//echo $reportQuery; die;
	$fields = mysql_num_fields($result);
	
	$totalRows = mysql_num_rows($result);
	
	if($_REQUEST['export_to']!='')
	{
		
		$res = $result;		
	   $reportFile =$reportNameArray[$report_criteria].time()."-".date('d-m-Y').".csv";	
	  // fetch a row and write the column names out to the file
		$row1 = mysql_fetch_assoc($res);
		$line = "";
		$comma = "";
		foreach($row1 as $name => $value) {
    	$line .= $comma . '"' . str_replace('"', '""', $name) . '"';
    	$comma = ",";
		}
		$line .= "\n";
		// remove the result pointer back to the start
		mysql_data_seek($res, 0);

		// and loop through the actual data
		while($row1 = mysql_fetch_assoc($res)) {
   
    		$comma = "";
    		foreach($row1 as $value) {
        	$line .= $comma . '"' . str_replace('"', '""', $value) . '"';
        	$comma = ",";
    		}
    		$line .= "\n";
    		
   
		}

		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=\"$reportFile\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $line;exit;
		
	}
	
	}


?>
	
<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>
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

<? include_once('metadata.php');?>
<LINK href="images/sortnav.css" type='text/css' rel='stylesheet'/>
</HEAD>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" align="center" border="1px" bordercolor="#E6E6E6"> 
  		<TR>
    		<TD vAlign=top align=left bgColor=#ffffff><? include("header.php");?></TD>
  		</TR>
  		<TR height="5">
    		<TD vAlign=top align=left class="topBarColor">&nbsp;</TD>
  		</TR>
  		<TR>
    		<TD vAlign="top" align="left" height="340"> 
      			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        		<TR> 
          			<TD vAlign=top align=left width="175" rowSpan="2" > 
                	<TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
                  	<TR> 
                    	<TD valign="top">
                        	<TABLE cellSpacing=0 cellPadding=2 width=175 border=0>
                            <TBODY> 
                            <TR valign="top"> 
                              <TD valign="top"><? include ('leftmenu.php');?></TD>
                            </TR>
                            </TBODY> 
                          </TABLE>
                    	</TD>
                   </TR>
                </TABLE>
          </TD>
          <TD vAlign=top align=left width=0></TD>
         
        </TR>
        
            <TR> 
              <TD valign="top" width="1067"><!---Contents Start Here----->
              
             
                <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
                  <TR> 
                    <TD class=smalltext width="98%" valign="top">
                    
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
                        <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                        <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                      </tr>
                          <tr> 
                            <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                            <td valign="top"> 
                            
                            
                            
                                <TABLE cellSpacing=0 cellPadding=0 border=0 align="center" width="100%">
                                <tr>
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscription Reports</td>
                                 </tr>
                                 <?php if($_POST['report_criteria']!='') { ?>
                                  <form name="exportFrm" action="report_subscribtion.php" method="post" enctype="multipart/form-data">
                                 <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" style="padding-right:20px;">Export
                                           <select id="export_to" name="export_to" style="font-size:11px;">
                                            <option value="" <?php if($_REQUEST['export_to']=='') {?> selected <?php }?>>Select</option>
                                            <option value="1" <?php if($_REQUEST['export_to']=='1') {?> selected <?php }?>>Export to CSV</option>
											<option value="2" <?php if($_REQUEST['export_to']=='2') {?> selected <?php }?>>Export Details</option>
                                          </select>
                                          <input type="hidden" name="report_criteria" id="report_criteria" value="<?=$_POST['report_criteria']?>"/>
										  <input type="hidden" name="frD" id="frD" value="<?=$_POST['frD']?>"/>
                                           <input type="hidden" name="frM" id="frM" value="<?=$_POST['frM']?>"/>
                                            <input type="hidden" name="frY" id="frY" value="<?=$_POST['frY']?>"/>
											<input type="hidden" name="toD" id="toD" value="<?=$_POST['toD']?>"/>
                                             <input type="hidden" name="toM" id="toM" value="<?=$_POST['toM']?>"/>
                                              <input type="hidden" name="toY" id="toY" value="<?=$_POST['toY']?>"/>
                                              
                                               
                                              
                                           <input type="hidden" name="country_select" id="country_select" value="<?=trim($_POST['country_select'])?>"/>
                                           <input type="hidden" name="user_country" id="user_country" value="<?=trim($_POST['user_country'])?>"/>
                                          
                                           <input type="hidden" name="user_gender" id="user_gender" value="<?=trim($_POST['user_gender'])?>"/>
                                          
                                           <input type="hidden" name="user_origin" id="user_origin" value="<?=trim($_POST['user_origin'])?>"/>
                                           <input type="hidden" name="code" id="code" value="<?=trim(stripslashes($_POST['code']))?>"/>
                                           <input type="hidden" name="user_fromage" id="user_fromage" value="<?=trim(stripslashes($_POST['user_fromage']))?>"/>
                                           <input type="hidden" name="user_toage" id="user_toage" value="<?=trim(stripslashes($_POST['user_toage']))?>"/>                                                                                    
                                           <input type="hidden" name="brand_select" id="brand_select" value="<?=trim($_POST['brand_select'])?>"/>
										   <input type="hidden" name="user_brand" id="user_brand" value="<?=trim($_POST['user_brand'])?>"/>
										   <!--From-->
										   <input type="hidden" name="origin_select" id="origin_select" value="<?=trim($_POST['origin_select'])?>"/>
										   <input type="hidden" name="type_select" id="type_select" value="<?=trim($_POST['type_select'])?>"/>
										   <input type="hidden" name="program_select" id="program_select" value="<?=trim($_POST['program_select'])?>"/>
										   <input type="hidden" name="user_origin" id="user_origin" value="<?=trim($_POST['user_origin'])?>"/>
                                           <input type="hidden" name="user_type" id="user_type" value="<?=trim($_POST['user_type'])?>"/>
										   <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
										   <!--To-->                                           
                                           
                                            <input type="hidden" name="user_country_1" id="user_country_1" value="<?=trim($countryCondn)?>"/>
                                             <input type="hidden" name="user_gender_1" id="user_gender_1" value="<?=trim($genderCondn)?>"/> 
                                              <input type="hidden" name="user_brand_1" id="user_brand_1" value="<?=$brandCondn?>"/>  
                                             <input type="hidden" name="grouping" id="grouping" value="<?=trim($_POST['grouping'])?>"/>
											 <!--From-->
											 <input type="hidden" name="user_origin_1" id="user_origin_1" value="<?=$originCondn?>"/>
											 <input type="hidden" name="user_type_1" id="user_type_1" value="<?=$typeCondn?>"/>
											 <input type="hidden" name="user_program_1" id="user_program_1" value="<?=$programCondn?>"/>
    										<!--To-->	
                                          <input name="export" type="submit" value="export" />
                                           </td>
                                        </tr>
                                        </form>
                                   <?php } ?>     
                                    <TR> 
                                      <TD valign='top' bgColor='white'>
                                      <form name="reportFrm" action="report_subscribtion.php" method="post" enctype="multipart/form-data" onSubmit="return dateValidation();">
                                      <table width=100% height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        
                                       
                                        
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td height="4" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:13px; color:#36C; padding-left:20px; padding-top:20px; padding-bottom:20px;"><strong>Filter By Parameters</strong></td>
                                        </tr>   
                                       
                                        <tr >
                                          <td align="left" valign="top" style="padding-left:20px;">&nbsp;<strong>Country</strong>&nbsp;&nbsp;</td>
                                          <td >
                                          <span style="vertical-align:top">
                                           <select id="country_select" name="country_select" style="font-size:11px;" onChange="javascript:selectCountry(this.value);">
                                            <option value="" <?php if($_REQUEST['country_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['country_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                          <span id="countrydiv" style="display:none"><select id="user_country[]" name="user_country[]" style="font-size:11px; width:212px;"  multiple size="5" >
                                            <? 
                                while(list($code,$name) = each($countryArray)){
                                    $string = "<option value={$code}";
									foreach ($_REQUEST['user_country'] as $value) {
    									if($code == $value){
                                        $string .= " selected";
                                    }
										}
                                    
                                    $string .= ">{$name}</option>";
                                    print $string;
                                }
                           ?>
                                          </select>
                                          </span>
                                          </td>
                                          <td width="161" align="left" valign="top" style="padding-left:10px;"><strong>Age</strong></td>
                                          <td valign="top" ><input type="text" name="user_fromage" id="user_fromage" size="5" value="<?=trim($_POST['user_fromage'])?>" />&nbsp;- &nbsp;<input type="text" name="user_toage" id="user_toage" size="5" value="<?=trim($_POST['user_toage'])?>"/></td>
                                        </tr>
                                        <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Gender</strong></td>
                                          <td style="padding-top:10px;"><select id="user_gender[]" name="user_gender[]" style="font-size:11px;" multiple>
                                            <option value="0"
                                            
                                            <?php 
											
											foreach ($_POST['user_gender'] as $value) {
    											if($value == '0'){
                                        		echo " selected";
                                    		} }
											?>

                                            >Male</option>
                                            <option value="1"
                                            <?php foreach ($_POST['user_gender'] as $value) {
    											if($value == '1'){
                                        		echo " selected";
                                    		} }
											?>
                                            >Female</option>
                                          </select></td>                                          
                                        </tr>
                                         <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Origin</strong></td>
                                          <td style="padding-top:10px;">
										   <span style="vertical-align:top">
                                          <select id="origin_select" name="origin_select" style="font-size:11px;" onChange="javascript:selectOrigin(this.value);">
                                            <option value="" <?php if($_REQUEST['origin_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['origin_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                               <span id='origindiv' style="display:none">
                                          <select id="user_origin[]" name="user_origin[]"  style="font-size:11px;width:212px;" multiple size="5">
										  <option value="3" <?php foreach ($_POST['user_origin'] as $value) {
												if('3'==$value){?> 
                                            selected="selected"
											<?php } } ?>>First Workout Free Try</option>
                                          <option value="1" <?php foreach ($_POST['user_origin'] as $value) {
												if('1'==$value){?> 
                                            selected="selected"
											<?php } } ?>>1 Euro Discount</option>
                                   		  <option value="2" <?php  foreach ($_POST['user_origin'] as $value) {
												if('2'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Gift Code</option>
                                          <option value="4" <?php  foreach ($_POST['user_origin'] as $value) {
												if('4'==$value){?> 
                                            selected="selected"
											<?php } } ?>>7.9/9.9 Transaction</option>
										  
										  
										  
										  
                                          
                                          </select></span>
										  <!--<select id="user_origin" name="user_origin[]" style="font-size:11px;" multiple size="5">
                                          <option value="" <?php if(trim($_POST['user_origin'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="3" <?php if(trim($_POST['user_origin'])=="3"){ echo 'selected'; } ?>>First Workout Free Try</option>
                                          <option value="1" <?php if(trim($_POST['user_origin'])=="1"){ echo 'selected'; } ?>>1 Euro Discount</option>
                                   		  <option value="2" <?php if(trim($_POST['user_origin'])=="2"){ echo 'selected'; } ?>>Gift Code</option>
                                          <option value="4" <?php if(trim($_POST['user_origin'])=="4"){ echo 'selected'; } ?>>7.9/9.9 Transaction</option>
                                          </select>--></td>
                                          <!--<td align="left" valign="middle" style="padding-left:10px;padding-top:10px;"><strong>Discount Code</strong></td>
                                           <td style="padding-top:10px;">
                                            <select id="code" name="code"  style="font-size:11px;" >
                                            <option <?php if($_POST['code']==''){?> selected="selected"<?php } ?>
                                            value=''>Select</option>
                                            <?php if($discArray)
											{
											 while(list($code,$name) = each($discArray)){
											?>
											<option <?php if($code==$_POST['code']){?> selected="selected"<?php } ?> 
                                            value="<?php echo $code;?>"><?php echo $name;?></option>
										<?php
										}
										}?>
                                          </select>
                                           
                                           </td>-->
                                        </tr>
                                        <tr>
                                          <td width="139" align="left" valign="top" style="padding-left:20px;padding-top:10px;">&nbsp;<strong>Brand</strong>&nbsp;&nbsp;</td>
                                          <td width="176"  style="padding-top:10px;" valign="top">
                                          <span style="vertical-align:top">
                                          <select id="brand_select" name="brand_select" style="font-size:11px;" onChange="javascript:selectBrand(this.value);">
                                            <option value="" <?php if($_REQUEST['brand_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['brand_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                               <span id='branddiv' style="display:none">
                                          <select id="user_brand[]" name="user_brand[]"  style="font-size:11px;width:212px;" multiple size="5">
                                            <option 
                                            <?php foreach ($_POST['user_brand'] as $value) {
												if('0'==$value){?> 
                                            selected="selected"
											<?php } }
											?>
                                            value="0">Jiwok</option>
                                            <?php if($brandArray)
											{
											 while(list($code,$name) = each($brandArray)){
											?>
											<option <?php 
											foreach ($_POST['user_brand'] as $value) {
											if($code==$value){?> 
                                            selected="selected"
											<?php } }?> 
                                            value="<?php echo $code;?>"><?php echo $name;?></option>
										<?php
										}
										}?>
                                          </select></span></td>
                                        </tr>
                                        <tr>
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Program Yes/No</strong></td>
                                          <td style="padding-top:10px;"> <span style="vertical-align:top">
                                          <select id="program_select" name="program_select" style="font-size:11px;" onChange="javascript:selectProgram(this.value);">
                                            <option value="" <?php if($_REQUEST['program_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['program_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                               <span id='programdiv' style="display:none">
                                          <select id="user_program[]" name="user_program[]"  style="font-size:11px;width:212px;" multiple size="3">
										  <option value="1" <?php foreach ($_POST['user_program'] as $value) {
												if('1'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Yes</option>
                                          <option value="0" <?php foreach ($_POST['user_program'] as $value) {
												if('0'==$value){?> 
                                            selected="selected"
											<?php } } ?>>No</option>                                   		  
                                          </select></span>
										  <!--<select id="user_program" name="user_program" style="font-size:11px;" >
                                          	<option value="" <?php if($_POST['user_program']=='') {?> selected <?php }?>>--Select--</option>
                                            <option value="1" <?php if($_POST['user_program']=='1') {?> selected <?php }?>>Yes</option>
                                            <option value="0" <?php if($_POST['user_program']=='0') {?> selected <?php }?>>No</option>
                                          </select>--></td>
                                          <td align="left" valign="top" style="padding-left:10px; padding-top:10px;"><strong>Subscription Type</strong></td>
                                         <td width="271"  style="padding-top:10px;" valign="top">  <span style="vertical-align:top">
                                          <select id="type_select" name="type_select" style="font-size:11px;" onChange="javascript:selectType(this.value);">
                                            <option value="" <?php if($_REQUEST['type_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['type_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                               <span id='typediv' style="display:none">
                                          <select id="user_type[]" name="user_type[]"  style="font-size:11px;width:212px;" multiple size="5">
										  <option value="1" <?php foreach ($_POST['user_type'] as $value) {
												if('1'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Registered Not Tested</option>
                                          <option value="2" <?php foreach ($_POST['user_type'] as $value) {
												if('2'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Registered and Tested</option>
                                   		  <option value="3" <?php  foreach ($_POST['user_type'] as $value) {
												if('3'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Subscriber</option>
                                          <option value="4" <?php  foreach ($_POST['user_type'] as $value) {
												if('4'==$value){?> 
                                            selected="selected"
											<?php } } ?>>Ex-Subscriber</option>
                                          </select></span>
										 <!--<select id="user_type" name="user_type" style="font-size:11px;" >
                                          	<option value="" <?php if($_POST['user_type']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_POST['user_type']=='1') {?> selected <?php }?>>Registered Not Tested</option>
                                            <option value="2" <?php if($_POST['user_type']=='2') {?> selected <?php }?>>Registered and Tested</option>
                                            <option value="3" <?php if($_POST['user_type']=='3') {?> selected <?php }?>>Subscriber</option>
                                            <option value="4" <?php if($_POST['user_type']=='4') {?> selected <?php }?>>Ex-Subscriber</option>
                                          </select>--></td>
                                        </tr>
                                       <tr><td colspan="8" height="30">&nbsp;</td>
                                       </tr> 
                                        
                                        <tr >
                                        <td width="139" style="padding-left:20px; padding-top:30px;"><strong>Filter Date</strong></td>
                                         
                                                  <td colspan='3' style="padding-top:30px;">
                                                 <select name="frD" id="frD" style="font-size:11px;">
                                                    <?
                                                $str = "<option value=''>All</option>";
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['frD'] == $day_value)
                                                        $str .= ' selected = "selected"';
													else
													{
														if((!$_POST['frD']) && ($i ==$frD))
														$str .= ' selected = "selected"';
													}
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
												 
												 
                                                  <select name="frM" id="frM" style="font-size:11px;">
                                                    <?
                                                $mArray = array(
															""		=> "All",
                                                            "01" 	=> "January",
                                                            "02" 	=> "February",
                                                            "03" 	=> "March",
                                                            "04" 	=> "April",
                                                            "05" 	=> "May",
                                                            "06" 	=> "June", 
                                                            "07" 	=> "July", 
                                                            "08" 	=> "August", 
                                                            "09" 	=> "September", 
                                                            "10" 	=> "October", 
                                                            "11" 	=> "Novemer", 
                                                            "12" 	=> "December"
                                                          );
                                                $str = '';
												foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['frM'] == $key)
                                                        $str .= ' selected = "selected"';
													else
													{
														if((!$_POST['frM']) && ($key == $frM))													
														$str .= ' selected = "selected"';
													}	
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                                    
                                            ?>
                                                  </select>
                                                  <select name="frY" id="frY" style="font-size:11px;">
                                                    <?
                                                $str = "<option value=''>All</option>";
                                                for($i=$todayDetails['year']-6;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if(!$_POST['frY']  && $i == $frY)
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
														$str .= 'selected="selected"';
														//if(($_POST['frY'] == $i) && ($_POST['frY'] !="" ))                                                        
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  -
												  <select name="toD" id="toD" style="font-size:11px;">
                                                    <?
                                                $str = "<option value=''>All</option>";
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['toD'] == $day_value)
                                                        $str .= ' selected = "selected"';
													else
													{
														if((!$_POST['toD']) && ($i == $toD))
														$str .= ' selected = "selected"';
													}
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="toM" id="toM" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['toM'] == $key)
                                                        $str .= ' selected = "selected"';
													else
													{
														if((!$_POST['toM']) && ($key == $toM))
														$str .= ' selected = "selected"';
													}		
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="toY" id="toY" style="font-size:11px;">
                                                    <?
                                                 $str = "<option value=''>All</option>";
                                                for($i=$todayDetails['year']-6;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if(!$_POST['toY']  &&  $i == $toY)
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['toY'] == $i)
													$str .= 'selected="selected"';
													//if(($_POST['toY'] == $i) && ($_POST['toY'] !="" ))                                                        
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                               </td>
                                        </tr>
                                                                                
                                                                                                                  
                                        <tr>
                                          <td colspan="4" style="padding-top:10px;" align="center"> <input type="submit" name="filter" value="Show Reports"/></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                      </table>
                                      
                                      </form>
                                        <br/>
                                        
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;"><?=$reportTitleArray[$report_criteria]?></td>
                                        </tr>
                                      
                                      </tbody>
                                  </table><br/><?php if($totalRows>0) { ?>   							  
								  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                  <form name="grpFrm" action="report_subscribtion.php" method="post" enctype="multipart/form-data">
                                  <tr >
                                         
                                          <td style="padding-top:10px; color:#FFF" colspan="8" align="right"><strong>Group By : </strong><select id="grouping" name="grouping" style="font-size:11px;" onChange="this.form.submit();" >
                                          <option value="" <?php if(trim($_POST['grouping'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="brand_name" <?php if(trim($_POST['grouping'])=="brand_name"){ echo 'selected'; } ?>>Brand </option>
                                          <option value="Country" <?php if(trim($_POST['grouping'])=="Country"){ echo 'selected'; } ?>>Country</option>
                                   		  <option value="Gender" <?php if(trim($_POST['grouping'])=="Gender"){ echo 'selected'; } ?>>Gender</option>
                                          <option value="Age" <?php if(trim($_POST['grouping'])=="Age"){ echo 'selected'; } ?>>Age</option>
                                          <option value="origin" <?php if(trim($_POST['grouping'])=="4"){ echo 'selected'; } ?>>Origin</option>
                                          </select>
                                           <input type="hidden" name="report_criteria" id="report_criteria" value="<?=$_POST['report_criteria']?>"/>
										   <input type="hidden" name="frD" id="frD" value="<?=$_POST['frD']?>"/>
                                           <input type="hidden" name="frM" id="frM" value="<?=$_POST['frM']?>"/>
                                            <input type="hidden" name="frY" id="frY" value="<?=$_POST['frY']?>"/>
											<input type="hidden" name="toD" id="toD" value="<?=$_POST['toD']?>"/>
                                             <input type="hidden" name="toM" id="toM" value="<?=$_POST['toM']?>"/>
                                              <input type="hidden" name="toY" id="toY" value="<?=$_POST['toY']?>"/>
                                              
                                               
                                              
                                           <input type="hidden" name="country_select" id="country_select" value="<?=trim($_POST['country_select'])?>"/>
                                           <input type="hidden" name="user_country" id="user_country" value="<?=trim($_POST['user_country'])?>"/>
                                          
                                           <input type="hidden" name="user_gender" id="user_gender" value="<?=trim($_POST['user_gender'])?>"/>
                                          
                                           <input type="hidden" name="user_origin" id="user_origin" value="<?=trim($_POST['user_origin'])?>"/>
                                           <input type="hidden" name="code" id="code" value="<?=trim(stripslashes($_POST['code']))?>"/>
                                           <input type="hidden" name="user_fromage" id="user_fromage" value="<?=trim(stripslashes($_POST['user_fromage']))?>"/>
                                           <input type="hidden" name="user_toage" id="user_toage" value="<?=trim(stripslashes($_POST['user_toage']))?>"/>
                                           <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
                                           <input type="hidden" name="brand_select" id="brand_select" value="<?=trim($_POST['brand_select'])?>"/>
										   
                                           <input type="hidden" name="user_brand" id="user_brand" value="<?=trim($_POST['user_brand'])?>"/>
                                          
										  <input type="hidden" name="origin_select" id="origin_select" value="<?=trim($_POST['origin_select'])?>"/>
                                           <input type="hidden" name="user_type" id="user_type" value="<?=trim($_POST['user_type'])?>"/>
										   <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
                                           
                                            <input type="hidden" name="user_country_1" id="user_country_1" value="<?=$countryCondn?>"/>
                                             <input type="hidden" name="user_gender_1" id="user_gender_1" value="<?=$genderCondn?>"/> 
                                              <input type="hidden" name="user_brand_1" id="user_brand_1" value="<?=$brandCondn?>"/>  
											  
											  <input type="hidden" name="user_origin_1" id="user_origin_1" value="<?=$originCondn?>"/>
											  <input type="hidden" name="user_type_1" id="user_type_1" value="<?=$typeCondn?>"/>
											  <input type="hidden" name="user_program_1" id="user_program_1" value="<?=$programCondn?>"/>
											  
                                          </td>
                                          </tr>
                                          </form>
                                  
                                      <TR class="tableHeaderColor" >
                                        <?php for ($i = 0; $i < $fields; $i++) { 
										$fname=mysql_field_name($result, $i);
										?>
                                        <TD width="18%" align="center" ><?=$fname?></TD>
                                        <?php } ?>
                                       </TR>
                                     
                                        <?php
										while($row = mysql_fetch_array($result,MYSQL_ASSOC))
										{
										?> 
                                        <tr class="listingTable1">
                                        <?php for ($i = 0; $i < $fields; $i++) { 
										$fname=mysql_field_name($result, $i);
										?>                 
                        			   <TD height="19" align="left" style="padding-left:2px;"><?=stripslashes($row[$fname])?></TD>
                                        <?php }?>                                    
                                      </tr>
                                      
                                      
                                      <?php } ?>
                                     
                                  </table>
                                 <?php }
								 else
								 {
								 ?>
                                 <TABLE cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                    <tr><td><strong><center>No Records Found</center></strong></td></tr>
                                    </TBODY>
                                    </TABLE><?php } ?>
                                 
                                  <!--<table cellspacing=0 cellpadding=0 width='800' border=0 class="topColor">
                                <tbody>		
								<tr>
									<td align="right" colspan = "8" class="leftmenu">Space for pagination</td>
								</tr>
				   				</tbody>
			 					</table> -->    
               					<br/></TD>
                                    </TR>
                                  </TABLE>
                              </td>
                            <td background="images/side2.jpg">&nbsp;</td>
                          </tr>
                      <tr> 
                        <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                        <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
                      </tr>
                    </table>
    
                    </TD>
                  </TR>
                </TABLE>
       
              </TD>
            </TR>
		 <TR height="2">
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
      </td>
      </tr>
      </table>
        <?php 
			include_once("footer.php");
			
		?></BODY>
</HTML>
<?php
if($_POST['country_select'] == '1' && $_POST['user_country']!=""){
		echo "<script language='javascript' type='text/javascript'>selectCountry(1);</script>";
}
if($_POST['language_select'] == '1' && $_POST['user_language']!=""){
		echo "<script language='javascript' type='text/javascript'>selectLanguage(1);</script>";
}
if($_POST['brand_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectBrand(1);</script>";
}
if($_POST['origin_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectOrigin(1);</script>";
}
if($_POST['type_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectType(1);</script>";
}
if($_POST['program_select'] == '1'){
		echo "<script language='javascript' type='text/javascript'>selectProgram(1);</script>";
}
?>

