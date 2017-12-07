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
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
				 
	/*
	 Instantiating the classes.
	*/
	$objGen      =	 new General();
	$objReport	 = 	 new Report($lanId);
	$objDb       =   new DbAction();
	
	$heading = "Subscriber Reports";
	
	$countriesArray = $objReport->_getCountries();
	$brandArray=$objReport->getAllBrandName();
	$languages = $lanObj->_getLanguageArray();
	
	$todayDetails	= getdate();
	$today = date('Y-m-d');
	$weekArray = array('1'=>7,'2'=>14,'3'=>21,'4'=>28);
	$whereSql = '';
	$whereCondition = '';
	$user_type = '';
	if(isset($_REQUEST['param'])){
		extractParams($_REQUEST['param']);
	}
	$param	= '';
	
	$orderby	=	$_REQUEST['orderby'];
	$sortby		=	$_REQUEST['sortby'];
//===========Code for sorting =============
	if($orderby == ""){
		$orderby_sql	=	"asc";
		$orderby	=	"";
	}
	else if($orderby == "asc"){
		$orderby		=	"asc";
		$orderby_sql	=	"desc";
		$orderImage	=	"<img src='images/down_order2.png' border='0' />";
	}
	else{
		$orderby		=	"desc";
		$orderby_sql	=	"asc";
		$orderImage	=	"<img src='images/up_order2.png' border='0' />";
	}
	if($sortby == ""){
		if($_POST['user_type']!='' && ($_POST['user_type']=='new' || $_POST['user_type']=='permanent'))
		{
			$sort_sql		=	"order by user_doj desc";
		}
		else
		{ $sort_sql		=	"order by user_fname ".$orderby_sql;
		$sort_sqlt		=	"order by user_fname ".$orderby_sql;
		  
		}
		$orderImage		=	"";
	}
	
	if($sortby == "brand"){
		 $sort_sqlt		=	"order by brand_name ".$orderby_sql;
	}
	if($sortby == "name"){
		 $sort_sqlt		=	"order by user_fname ".$orderby_sql;
	}
	if($sortby == "email"){
		 $sort_sqlt		=	"order by user_email ".$orderby_sql;
	}
	if($sortby == "country"){
		 $sort_sqlt		=	"order by user_country ".$orderby_sql;
	}
	if($sortby == "origin"){
		 $sort_sqlt		=	"order by user_origin ".$orderby_sql;
	}
	if($sortby == "sex"){
		 $sort_sqlt		=	"order by user_gender ".$orderby_sql;
	}
	if($sortby == "age"){
		 $sort_sqlt		=	"order by age ".$orderby_sql;
	}
	
	if($_POST['country_select'] == '1' && $_POST['user_country']!=""){
		$user_country = implode(',',$_POST['user_country']);
		$whereSql .= " AND user_master.user_country IN(". $user_country.") ";
		
	}
	if($_POST['user_gender']!=""){
		$user_gender = implode(',',$_POST['user_gender']);
		$whereSql .= " AND user_master.user_gender IN(". $user_gender.") ";
		
	}
	if($_POST['language_select'] == '1' && $_POST['user_language']!=""){
		$user_language = implode(',',$_POST['user_language']);
		$whereSql .= " AND user_master.user_language IN(". $user_language.") ";
		
	}
	if($_POST['user_origin']!=""){
		if(trim($_POST['user_origin'])=="1 Euro Pay a Second Month At 7.90 Euro")
		$whereCondition .= " AND user_origin='1 Euro Origin' ";
		else
		$whereCondition .= " AND user_origin='".trim($_POST['user_origin'])."'";
		if(trim($_POST['user_origin'])=="1 Euro Origin")
		{
			if(trim($_POST['code'])!="")
			{
				$whereCondition .= " AND origin_discountcode='".trim($_POST['code'])."'";
			}
		}
		if(trim($_POST['user_origin'])=="By Gift Code")
		{
			if(trim($_POST['code'])!="")
			{
				$whereCondition .= " AND origin_giftcode='".trim($_POST['code'])."'";
			}
		}
		
	}
	$fromage = trim(stripslashes($_POST['user_fromage']));
	$toage   = trim(stripslashes($_POST['user_toage']));
	
	if(trim(stripslashes($_POST['user_fromage']))!=""){
		$whereSql .= " AND age>=".$fromage;
	}
	if(trim(stripslashes($_POST['user_toage']))!=""){
		$whereSql .= " AND age<=".$toage;
	}
	
	if($_POST['brand_select'] == '1' && $_POST['user_brand']!=""){
		$user_brand = implode(',',$_POST['user_brand']);
		$whereSql .= " AND (";
		foreach ($_POST['user_brand'] as $value) {
			if(trim($value)=='0')
				{ $whereSql .= " brand_user.brand_master_id IS NULL "; }
		    else
			   { $whereSql .= " OR brand_user.brand_master_id=".$value; }
		}
		$whereSql .= " ) ";
	}
	
	if(trim(stripslashes($_POST['user_type']))!=""){
		$user_type1 = trim($_POST['user_type']);
		if($user_type1=='free' || $user_type1=='paid')
		{
			$user_type = $user_type1;
		}
		else
		{ $user_type = '';}
	}
	
	/*-------------------- coding for date filter starts here-------------------------------*/
	switch($_POST['daterange'])
	{
			case '1':
				$week_month = trim($_POST['rM']);
				$week_year  = trim($_POST['rY']);
				$week		= trim($_POST['rW']);
				$weekStartDate = $week_year."-".$week_month."-01";
				$numDaysInMonth = date("t", strtotime($week_year."-".$week_month."-01"));
				if($week=='5')
				{ 
					$daysLeft = $numDaysInMonth - 28;
					if($daysLeft>0)
					  $weekEndDay = $daysLeft+28;
					$weekEndDate  = $week_year."-".$week_month."-".$weekEndDay ; 
				}
				else
				{
					$weekEndDate  = $week_year."-".$week_month."-".$weekArray[$week] ; 
				}
				if($user_type!='' && $user_type=='new')
				  $whereSql .= " AND user_master.user_doj BETWEEN '".$weekStartDate."' AND '".$weekEndDate."'" ;
				elseif($user_type!='' && $user_type=='permanent')
				  $whereSql .= " AND user_master.user_doj < '".$weekStartDate."'" ;
				else
				  $whereSql .= " AND user_master.user_doj BETWEEN '".$weekStartDate."' AND '".$weekEndDate."'" ;

				break;
				
			case '2':
				$from_month = trim($_POST['frM']);
				$from_year  = trim($_POST['frY']);
				$from_day	= trim($_POST['frD']);
				$to_month   = trim($_POST['toM']);
				$to_year    = trim($_POST['toY']);
				$to_day	    = trim($_POST['toD']);
				$rangeStartDate  = $from_year."-".$from_month."-".$from_day ; 
				$rangeEndDate    = $to_year."-".$to_month."-".$to_day ; 
				if($user_type!='' && $user_type=='new')
				 $whereSql .= " AND user_master.user_doj BETWEEN '".$rangeStartDate."' AND '".$rangeEndDate."'" ;
				elseif($user_type!='' && $user_type=='permanent')
				 $whereSql .= " AND user_master.user_doj < '".$rangeStartDate."'" ;
				else
				 $whereSql .= " AND user_master.user_doj BETWEEN '".$rangeStartDate."' AND '".$rangeEndDate."'" ;

				break;
				
			case '3':
				$num_months = trim($_POST['num_months']);
				$detail_month = '-'.$num_months.' '.'month';
				$lastMonthsDetails = getdate(strtotime($detail_month));
				$requiredDate	   = date('Y-m-d',$lastMonthsDetails[0]);	
				if($user_type!='' && $user_type=='new')
				$whereSql .= " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				elseif($user_type!='' && $user_type=='permanent')
				$whereSql .= " AND user_master.user_doj < '".$requiredDate."'";
				else
				$whereSql .= " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				break;
				
	}
	
	
	
	/*--------------------coding for date  filter ends here ---------------------------------*/
	
	$reports1 = $objReport->getReportOfSubscribers($user_type,$whereSql,$sort_sql,'','');
	
	if(count($reports1)>0)
	{ 
	   createTempTable();
		truncateTempTable();
		foreach($reports1 as $report)
		{ 
		
			$user_id			= $report['user_id'];
			$brand_name 		= $report['brand_name'];
			$user_fname 		= $report['user_fname']; 
			$user_lname 		= $report['user_lname']; 
			$user_email 		= $report['user_email'];
			$user_country  		= $report['user_country'];
			$user_gender		= $report['user_gender'];	
			$user_age			= $report['age'];
			$user_language  	= $report['user_language'];
			$giftCodeDate   	= $objReport->checkGiftCodeTransaction($user_id);
			$discDate 			= $objReport->checkPaymentTransactionforDiscount($user_id);
			$originDiscountCode = $objReport->getOneEuroOriginDiscountCode($user_id);
			$originGiftCode = $objReport->getOriginGiftCode($user_id);
			$normalPaymentDate 	= $objReport->checkNormalPaymentTransaction($user_id);
			$freeWorkoutDownloadDate = $objReport->getFreeWorkoutOrigin($user_id);
			$origin = $objReport->compareOriginDates($discDate,$giftCodeDate,$normalPaymentDate,$freeWorkoutDownloadDate);
			$programCheck = $objReport->_getUserTrainingProgram($user_id);
			$insertQuery = "INSERT INTO reports_temp(user_id, user_email, user_language,user_country,user_fname,user_lname,brand_name,user_gender,age,user_origin,origin_discountcode,origin_giftcode,user_program)
VALUES ('$user_id', '$user_email', '$user_language','$user_country','$user_fname','$user_lname','$brand_name','$user_gender','$user_age','$origin','$originDiscountCode','$originGiftCode','$programCheck')";
			$res	=	$GLOBALS['db']->query($insertQuery);
		}
	   //////////////////////////////// PAGINATION //////////////////////////////////
	   $reports2 = $objReport->selectRecordsFromTemp($whereCondition,$sort_sqlt,'','');
	   $totalRecs = count($reports2);
		$param	= substr($param, 1);
		$param	= base64_encode($param);
		if(!$_REQUEST['maxrows'])
			$_REQUEST['maxrows'] = $_POST['maxrows'];
		if($_REQUEST['pageNo']){
			
			if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
				$_REQUEST['pageNo'] = 1;
			}
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			
			$reports = $objReport->selectRecordsFromTemp($whereCondition,$sort_sqlt,$fromLimit,$toLimit);
		}
		else{
		/***********************Selects Records at initial stage***********************************************/
			$_REQUEST['pageNo'] = 1;
		
			//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			$reports = $objReport->selectRecordsFromTemp($whereCondition,$sort_sql,$fromLimit,$toLimit);
			
		}
			
		if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows']){
			//For showing range of displayed records.
			if($totalRecs <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $totalRecs;
			$displayString = "Viewing $startNo to $endNo of $endNo Homepage";
			
		}
		else{
			//For showing range of displayed records.
			if($totalRecs <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
			$displayString = "Viewing $startNo to $endNo of $totalRecs homepage";
			
		}
		//Pagin 
		$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows']); 
		if($_REQUEST['pageNo'] == 1){
			$prev = 1;
		}
		else
			$prev = $_REQUEST['pageNo']-1;
		if($_REQUEST['pageNo'] == $noOfPage){
			$next = $_REQUEST['pageNo'];
		}
		else
			$next = $_REQUEST['pageNo']+1;
	////////////////////////////////////Pagination ends here/////////////////////////////////////////
	    
		
	}
	
	
	
	  function createTempTable(){
		
		$query	=	"CREATE TABLE IF NOT EXISTS `reports_temp` (
		             `temp_user_id` BIGINT(255) NOT NULL AUTO_INCREMENT,
					 `user_id` BIGINT(18) NOT NULL ,
					 `user_email` VARCHAR(256) DEFAULT NULL,
					 `user_language` INT(11) DEFAULT 1,
					 `user_country` INT(5) DEFAULT 0,
					 `user_fname` VARCHAR(255) DEFAULT NULL,
					 `user_lname` VARCHAR(255) DEFAULT NULL,
					 `brand_name` VARCHAR(255) DEFAULT NULL,
					  `user_gender` tinyint(1)  DEFAULT 0,
					  `age` INT( 4 ) DEFAULT NULL,
					  `user_origin` VARCHAR( 255 ) DEFAULT NULL,
					  `origin_discountcode` VARCHAR( 255 ) DEFAULT NULL,
					  `origin_giftcode` VARCHAR( 255 ) DEFAULT NULL,
					  `user_program` VARCHAR( 10 ) DEFAULT NULL,
					PRIMARY KEY ( `temp_user_id`)
					) ENGINE = MEMORY DEFAULT CHARSET = latin1;";
					
		$res	=	$GLOBALS['db']->query($query);
		if(DB::isError($res)) {
			return(false);
		}
	  }
	   function truncateTempTable(){
		
		$query	=	" TRUNCATE TABLE reports_temp;";
					
		$res	=	$GLOBALS['db']->query($query);
		if(DB::isError($res)) {
			return(false);
		}
	  }
	  function extractParams($param){
	$en_scode=base64_decode($param);
	$strVal=explode("&",$en_scode);
	//print_r($strVal);
	for($i=0, $strVal_size=sizeof($strVal);$i<$strVal_size;$i++){	
		$seperateValues=explode("=",$strVal[$i]);
		if(!isset($_REQUEST[$seperateValues[0]])){
			$_REQUEST[$seperateValues[0]]	= $seperateValues[1];
		}
		if(!isset($_POST[$seperateValues[0]])){
			$_POST[$seperateValues[0]]	= $seperateValues[1];
		}
	}
}
		//$reports = $objReport->selectRecordsFromTemp($sort_sql,$fromLimit,$toLimit);
	?>	

<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>
<script language="javascript" type="text/javascript">
function selectCountry(myvar)
{
	var myVar = myvar;
	if(myVar=='')
	 document.getElementById('countrydiv').style.display='none';
	else  document.getElementById('countrydiv').style.display='block';
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
              
              <form name="reportFrm" action="report_subscriber.php" method="post" enctype="multipart/form-data">
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
                                    <TR> 
                                      <TD valign='top' bgColor='white'><table width=100% height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        <tr>
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscriber Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" class="sectionHeading" style="padding-right:20px;" ><font style="font-size:14px;background-color:#09F; color:#FFF; padding:3px;">EXPORT TO CSV</font></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
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
                                while(list($code,$name) = each($countriesArray)){
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
                                          <td width="120" align="left" valign="top" style="padding-left:10px;"><strong>Age</strong></td>
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
                                          <td align="left" valign="middle" style="padding-left:10px;padding-top:10px;"><strong>Language</strong></td>
                                          <td style="padding-top:10px;">
                                          <span style="vertical-align:top">
                                           <select id="language_select" name="language_select" style="font-size:11px;" onChange="javascript:selectLanguage(this.value);">
                                           <option value="" <?php if($_REQUEST['language_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['language_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                          <span id="languagediv" style="display:none">
                                          <select id="user_language[]" name="user_language[]" style="font-size:11px;" multiple >
                                           <? 
                                while(list($code,$name) = each($languages)){
                                    $string = "<option value={$code}";
                                    foreach ($_REQUEST['user_language'] as $value) {
    									if($code == $value){
                                        $string .= " selected";
                                    }
										}
                                    
                                    $string .= ">{$name}</option>";
                                    print $string;
                                	}
                           		?>
                                      </select></span></td>
                                        </tr>
                                         <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Origin</strong></td>
                                          <td style="padding-top:10px;"><select id="user_origin" name="user_origin" style="font-size:11px;">
                                          <option value="" <?php if(trim($_POST['user_origin'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="Free Workout Try" <?php if(trim($_POST['user_origin'])=="Free Workout Try"){ echo 'selected'; } ?>>First Workout Free Try</option>
                                          <option value="1 Euro Origin" <?php if(trim($_POST['user_origin'])=="1 Euro Origin"){ echo 'selected'; } ?>>1 Euro Discount</option>
                                   		  <option value="1 Euro Pay a Second Month At 7.90 Euro" <?php if(trim($_POST['user_origin'])=="1 Euro Pay a Second Month At 7.90 Euro"){ echo 'selected'; } ?>>1 Euro Pay a Second Month At 7.90 Euro</option>
                                          <option value="By Gift Code" <?php if(trim($_POST['user_origin'])=="By Gift Code"){ echo 'selected'; } ?>>By Gift Code</option>
                                          <option value="7.9 Euro Transaction" <?php if(trim($_POST['user_origin'])=="7.9 Euro Transaction"){ echo 'selected'; } ?>>7.90 Euro Transaction</option>
                                           </select></td>
                                          <td align="left" valign="middle" style="padding-left:10px;padding-top:10px;"><strong>Discount/Gift Code</strong></td>
                                           <td style="padding-top:10px;"><input type="text" name="code" id="code" size="5" value="<?=trim($_POST['code']);?>" /></td>
                                        </tr>
                                        <tr>
                                          <td width="92" align="left" valign="top" style="padding-left:20px;padding-top:10px;">&nbsp;<strong>Brand</strong>&nbsp;&nbsp;</td>
                                          <td width="255"  style="padding-top:10px;" valign="top">
                                          <span style="vertical-align:top"><select id="brand_select" name="brand_select" style="font-size:11px;" onChange="javascript:selectBrand(this.value);">
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
												foreach($brandArray as $brandRow)
											{?>
											<option <?php 
											foreach ($_POST['user_brand'] as $value) {
											if($brandRow['brand_master_id']==$value){?> 
                                            selected="selected"
											<?php } }?> 
                                            value="<?php echo $brandRow['brand_master_id'];?>"><?php echo $brandRow['brand_name'];?></option>
										<?php
										}
										}?>
                                          </select></span></td>
                                          <!--<td width="116" align="left" valign="top" style="padding-left:10px;padding-top:10px;"><strong>Sports</strong>&nbsp;&nbsp;</td>
                                          <td width="244"  style="padding-top:10px;" valign="top">
                                          
                                           <span style="vertical-align:top"><select id="sports_select" name="sports_select" style="font-size:11px;" onChange="javascript:selectSports(this);">
                                            <option value="">All</option>
                                            <? 
											foreach ($languages as $key => $value)
											{
                                    		?>
                                            <option value=<?=$key?>><?=$value?></option>
                                            <?php
                                			}
                          					?>
                                            </select></span>
                                               <span id='sportsdiv' style="display:none"></span></td>-->
                                        </tr>
                                        <tr>
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Program Yes/No</strong></td>
                                          <td style="padding-top:10px;"><select id="user_program" name="user_program" style="font-size:11px;">
                                          	<option value="" <?php if($_POST['user_program']=='') {?> selected <?php }?>>--Select--</option>
                                            <option value="Yes" <?php if($_POST['user_program']=='Yes') {?> selected <?php }?>>Yes</option>
                                            <option value="No" <?php if($_POST['user_program']=='No') {?> selected <?php }?>>No</option>
                                          </select></td>
                                          <td align="left" valign="top" style="padding-left:10px; padding-top:10px;"><strong>Subscriber Type</strong></td>
                                         <td width="178"  style="padding-top:10px;" valign="top"><select id="user_type" name="user_type" style="font-size:11px;">
                                          	<option value="" <?php if($_POST['user_type']=='') {?> selected <?php }?>>All</option>
                                            <option value="free" <?php if($_POST['user_type']=='free') {?> selected <?php }?>>Free Subscribers</option>
                                            <option value="paid" <?php if($_POST['user_type']=='paid') {?> selected <?php }?>>Paid Subscribers</option>
                                            <option value="new" <?php if($_POST['user_type']=='new') {?> selected <?php }?>>New Subscribers</option>
                                            <option value="permanent" <?php if($_POST['user_type']=='permanent') {?> selected <?php }?>>Permanent Subscribers</option>
                                          </select></td>
                                        </tr>
                                        
                                        
                                        
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px; padding-top:20px;"><span class="successAlert" >Filter by date</span></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="dateDisplay">
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
                                                  <span class="successAlert"><input name="daterange" id="daterange" type="radio" value="1" <?php if($_POST['daterange']=='1') {?> checked <?php }?>>
                                                  Week</span>
                                                </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select name="rM" style="font-size:11px;">
                                                    <?
                                                $mArray = array(
                                                            "01" => "January",
                                                            "02" => "February",
                                                            "03" => "March",
                                                            "04" => "April",
                                                            "05" => "May",
                                                            "06" => "June", 
                                                            "07" => "July", 
                                                            "08" => "August", 
                                                            "09" => "September", 
                                                            "10" => "October", 
                                                            "11" => "Novemer", 
                                                            "12" => "December"
                                                          );
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['rM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                                    
                                            ?>
                                                  </select>
                                                  <select name="rY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayDetails['year']-5;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['rY'] == "" and $i == $todayDetails['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['rY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select id="rW" name="rW"  style="font-size:11px;" >
                                                    <option value=""<?php if($_POST['rW']=='') {?> selected <?php }?>>--Number of weeks--</option>
                                                    <option value="1" <?php if($_POST['rW']=='1') {?> selected <?php }?>>1</option>
                                                    <option value="2" <?php if($_POST['rW']=='2') {?> selected <?php }?>>2</option>
                                                    <option value="3" <?php if($_POST['rW']=='3') {?> selected <?php }?>>3</option>
                                                    <option value="4" <?php if($_POST['rW']=='4') {?> selected <?php }?>>4</option>
                                                    <option value="5" <?php if($_POST['rW']=='5') {?> selected <?php }?>>5</option>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="periodDisplay" style="display:block" >
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
                                                  <input name="daterange" id="daterange" type="radio" value="2" <?php if($_POST['daterange']=='2') {?> checked <?php }?>>
                                                  Choose Range
                                                  </span>
                                                </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select name="frD" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['frD'] == $day_value)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="frM" style="font-size:11px;">
                                                    <?
                                                $mArray = array(
                                                            "01" => "January",
                                                            "02" => "February",
                                                            "03" => "March",
                                                            "04" => "April",
                                                            "05" => "May",
                                                            "06" => "June", 
                                                            "07" => "July", 
                                                            "08" => "August", 
                                                            "09" => "September", 
                                                            "10" => "October", 
                                                            "11" => "Novemer", 
                                                            "12" => "December"
                                                          );
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['frM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                                    
                                            ?>
                                                  </select>
                                                  <select name="frY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayDetails['year']-5;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['frY'] == "" and $i == $todayDetails['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  -
                                                  <select name="toD" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=1;$i<32;$i++){
                                                    $day_value	= $i;
                                                    if(strlen($day_value)==1){
                                                        $day_value	= '0'.$day_value;
                                                    }
                                                    $str .= '<option value="'.$day_value.'"';
                                                    if($_POST['toD'] == $day_value)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$day_value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="toM" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                foreach($mArray as $key => $value){
                                                    $str .= '<option value="'.$key.'"';
                                                    if($_POST['toM'] == $key)
                                                        $str .= ' selected = "selected"';
                                                    $str .= '>'.$value.'</option>';
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                  <select name="toY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayDetails['year']-5;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['toY'] == "" and $i == $todayDetails['year'])
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['toY'] == $i)
                                                        $str .= 'selected="selected"';
                                                    $str .= '>'.$i.'</option>';	
                                                }
                                                echo $str;
                                            ?>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="periodDisplay" style="display:block" >
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
                                                  <input name="daterange" id="daterange" type="radio" value="3" <?php if($_POST['daterange']=='3') {?> checked <?php }?>>
                                                  Number of months
                                                  </span>
                                                </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select id="num_months" name="num_months" style="font-size:11px;" >
                                                    <option value="" <?php if($_POST['num_months']=='') {?> selected <?php }?>>--months--</option>
                                                     <option value="0" <?php if($_POST['num_months']=='0') {?> selected <?php }?>>this month</option>
                                                    <option value="1" <?php if($_POST['num_months']=='1') {?> selected <?php }?>>last 1 month</option>
                                                    <option value="2" <?php if($_POST['num_months']=='2') {?> selected <?php }?>>last 2 months</option>
                                                    <option value="3" <?php if($_POST['num_months']=='3') {?> selected <?php }?>>last 3 months</option>
                                                    <option value="4" <?php if($_POST['num_months']=='4') {?> selected <?php }?>>last 4 months</option>
                                                    <option value="5" <?php if($_POST['num_months']=='5') {?> selected <?php }?>>last 5 months</option>
                                                    <option value="6" <?php if($_POST['num_months']=='6') {?> selected <?php }?>>last 6 months</option>
                                                    <option value="7" <?php if($_POST['num_months']=='7') {?> selected <?php }?>>last 7 months</option>
                                                    <option value="8" <?php if($_POST['num_months']=='8') {?> selected <?php }?>>last 8 months</option>
                                                    <option value="9" <?php if($_POST['num_months']=='9') {?> selected <?php }?>>last 9 months</option>
                                                    <option value="10" <?php if($_POST['num_months']=='10') {?> selected <?php }?>>last 10 months</option>
                                                    <option value="11" <?php if($_POST['num_months']=='11') {?> selected <?php }?>>last 11 months</option>
                                                    <option value="12" <?php if($_POST['num_months']=='12') {?> selected <?php }?>>last 12 months</option>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </tr>
                                        
                                        <tr style="padding-top:20px;">
                                          <td align="center" valign="bottom" style="padding-left:40px;padding-top:20px; font-weight:bold;" colspan="6"><input type="submit" name="Search" value="Filter Records" style="font-weight:bold; cursor:pointer"/></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                      </table>
                                        <br/>
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Subscriber Search Results </td>
                                        </tr>
                                      <TR >
                                        <TD width="37%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;">Total Number of Subscribers :</TD>
                                        <TD width="63%" align="left" class="sectionHeading" style="font-size:12px; padding-top:10px;"><?=count($reports2);?></TD>
                                      </TR>
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                 
                                      <TR class="tableHeaderColor" >
                                      <TD width="13%" align="center" ><a href="report_subscriber.php?sortby=brand&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Brand</a><?=$cat_orderImage?></TD>
                                        <TD width="20%" align="center" ><a href="report_subscriber.php?sortby=name&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">User Name</a><?=$cat_orderImage?></TD>
                                         <TD width="21%" align="center" ><a href="report_subscriber.php?sortby=email&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Email</a><?=$cat_orderImage?></TD>
                                         <TD width="18%" align="center" ><a href="report_subscriber.php?sortby=country&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Country</a><?=$cat_orderImage?></TD>
                                         <TD width="17%" align="center" ><a href="report_subscriber.php?sortby=origin&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Origin</a><?=$cat_orderImage?></TD>
                                          <TD width="5%" align="center" ><a href="report_subscriber.php?sortby=sex&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Sex</a><?=$cat_orderImage?></TD>
                                          <TD width="6%" align="center" ><a href="report_subscriber.php?sortby=age&orderby=<?=$orderby_sql?>&maxrows=<?=$maxrows;?>&display=<?=base64_encode($display);?>" class="tbl_header">Age</a><?=$cat_orderImage?></TD>
                                      </TR>
                                     
                         <?php if(count($reports)>0) { 
								foreach($reports as $report)
								{ 
									$user_id	= trim(stripslashes($report['user_id']));
									$brand_name = trim(stripslashes($report['brand_name']));
									$brand_name = ($brand_name!='')? $brand_name : 'Jiwok' ;
									$user_name 	= trim(stripslashes($report['user_fname'])).' '.trim(stripslashes($report['user_lname']));  
									$user_email = trim(stripslashes($report['user_email']));
									$country    = $objReport->_getCountryName(trim(stripslashes($report['user_country'])));
									$sex		= trim(stripslashes($report['user_gender']));	
									$sex		= ($sex=='0')? 'Male' : 'Female' ;
									//$objReport->checkOneEuroOrigin($userid);
									/*$giftCodeDate = $objReport->checkGiftCodeTransaction($user_id);
									$discDate = $objReport->checkPaymentTransactionforDiscount($user_id);
									$normalPaymentDate = $objReport->checkNormalPaymentTransaction($user_id);
									$freeWorkoutDownloadDate = $objReport->getFreeWorkoutOrigin($user_id);
									$origin = $objReport->compareOriginDates($discDate,$giftCodeDate,$normalPaymentDate,$freeWorkoutDownloadDate);*/
									
									   ?>                   
                                    
                        			   <tr class="listingTable1">
                                  	   <TD height="19" align="left" style="padding-left:10px;"><?=$brand_name?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=$user_name?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=$user_email?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=$country?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=trim(stripslashes($report['user_origin']))?></TD>
                                       <TD height="19" align="center" style="padding-left:2px;"><?=$sex?></TD>
                                       <TD height="19" align="left" style="padding-left:2px;"><?=trim(stripslashes($report['age']))?></TD>
                                      </tr>
                                      <?php 
									   }
									  } else {?>
                                      <tr class="listingTable1">
                                  	   <TD height="19" align="left" style=" text-align:center" colspan="8">No Records Found!</TD>
                                      <?php }?>
                                     
                                  </table>
                                  <?php if(count($reports2)>0) {?>
                                  <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_subscriber.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_subscriber.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="javascript:document.reportFrm.submit();">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="report_subscriber.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_subscriber.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="document.reportFrm.submit();" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>
					</td>
					</tr>
				   </tbody>
			 	</table>     
                <?php }?>
                                             
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
            </form>
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
			
		?></body>
</html>
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
?>
