<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::>  
   Date			::> 28/01/09
   
   DESCRIPTION::::>>>>
   To generate the report for the users.
  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
		
	//error_reporting(0);
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	/*
	Take all the languages to an array.
	*/
	
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$objGen      =	 new General();
	$objReport	 = 	 new Report($lanId);
	$objDb       =   new DbAction();
	
	$heading = "Register Reports";
	$countriesArray = $objReport->_getCountries();
	
	if(isset($_REQUEST['param'])){
		extractParams($_REQUEST['param']);
	}
	$param	= '';	
	//for generating the month and year specified report for the members and the download
	$todayCalendar = getdate();

	//for generating the month and year specified report for the members and the download
	$today = getdate();
	
	if($_POST['year']){
	 $currentYear =  $_POST['year'];
	 $param	.=	'&year='.$_POST['year']; }
	else
     $currentYear = date('Y');	 
	
	if($_POST['month']){
	 $currentMonth =  $_POST['month'];
	 $param	.=	'&month='.$_POST['month'];}
	else
	 $currentMonth = date('m');
	 
	
	$sqlStart = "SELECT user_master.user_fname,user_master.user_lname,user_master.user_alt_email AS user_email,user_master.user_doj,now(),DATE_FORMAT(user_master.user_doj,'%d %M %y') AS date ";
	$fromSql  = " FROM user_master ";
	$whereSql = " WHERE user_master.user_type=1 ";
	//Total num of jiwok users
	$sqlTotNum = "SELECT count(*) as count from user_master WHERE user_master.user_type=1 ";
	
	// Computing the date range...
	/**
	*    Computing the date range
	*/
	// If the first drop down was selected..
	if($_POST['daterange'] == 1){
		$param	.=	'&daterange='.$_POST['daterange'];
		if(isset($_POST['dropdown1'])){
			$param	.=	'&dropdown1='.$_POST['dropdown1'];
		}
		
		// get today's details
		$todayDetails = getdate();
		$today = date('Y-m-d');
		
		switch($_POST['dropdown1']){
			case 'today':
				$whereSql .= " AND user_master.user_doj = '".$today."'";
				
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				$whereSql .= " AND user_master.user_doj = '".$yesterday."'";
				
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);	
				$whereSql .= " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				$thisYear  = $todayDetails['year'];
				$whereSql 	 .= " AND MONTH(user_master.user_doj) = '".$thisMonth."'  AND YEAR(user_master.user_doj) = '".$thisYear."'";
				
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				$lasYear		  = $lastMonthDetails['year'];
				$whereSql 	 .= " AND MONTH(user_master.user_doj) = '".$lasMonth."'  AND YEAR(user_master.user_doj) = '".$lasYear."'";
				
				break;
				
				case 'last3month':	
				$last3MonthDetails = getdate(strtotime('-3 month'));
				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);	
				$whereSql .= " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'last6month':	
				$last6MonthDetails = getdate(strtotime('-6 month'));
				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);	
				$whereSql .= " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'lastyear':	
				$lastyear = getdate(strtotime('last year'));
				$lasYear  = $lastyear['year'];	
				$whereSql .= " AND YEAR(user_master.user_doj) = '".$lasYear."'";
				
				break;
				
			case 'alltime':	
				$whereSql 	 .= "";
				
				break;
		}
	
	} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
		$param	.=	'&daterange='.$_POST['daterange'];
		$param	.=	'&frY='.$_POST['frY'].'&frM='.$_POST['frM'].'&frD='.$_POST['frD'];
		$param	.=	'&toY='.$_POST['toY'].'&toM='.$_POST['toM'].'&toD='.$_POST['toD'];
		
		$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
		$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
		
		if($startDate > $endDate)
			$errorMsg[] = "Start date should be smaller than end date";
		
		if(count($errorMsg) == 0){
			if($startDate != $endDate){
				$whereSql 	 .= " AND user_master.user_doj BETWEEN '".$startDate."' AND '".$endDate."'";
				
			}else{ // Then we don't need a BETWEEN clause :)
				$whereSql .= " AND user_master.user_doj = '".$startDate."'";
				
			}			
		}
	}
	
	if(isset($_POST['report']) && $_POST['report']!='all'){
		$param	.=	'&report='.$_POST['report'];
	}
	
	if($_POST['report'] == 'country' && $_POST['user_country']!="" && $_POST['user_country']!="0"){
		$param	.=	'&user_country='.$_POST['user_country'];
		$whereSql .= " AND UM.user_country = ". $_POST['user_country']." ";
	}
	
	
	if($_POST['report'] == 'act' || $_POST['report'] == 'inac'){
	    if($_POST['report'] == 'act')
		   $chkCondition = '1';
		if($_POST['report'] == 'inac')
		  $chkCondition = '2';
		
		 $whereSql .= " AND user_master.user_status = ".$chkCondition; 
	
	}	
		
	//*************************** Countrywice report for the users starts here ****************************
	$sql = $sqlStart.$fromSql.$whereSql." GROUP BY user_master.user_id ORDER BY user_master.user_doj DESC";
	$sql_count	= 'SELECT COUNT(*) as count '.$fromSql.$whereSql;
	//$selected_record_count	= $objDb->_getList($sql_count);
	//$totalRecs = $selected_record_count[0]['count'];
	//***************************report section ends here *****************************	
	
	
/*                        Following Code is for doing paging 	*/
	$param	= substr($param, 1);
	$param	= base64_encode($param);
	
	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		//$result =  $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
		$toLimit = $_REQUEST['maxrows'];
		
		$sql.= " LIMIT {$fromLimit}, {$toLimit} ";
		$result=$objDb->_getList($sql);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
	
		//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
		$toLimit = $_REQUEST['maxrows'];
		
		$sql.= " LIMIT {$fromLimit}, {$toLimit} ";
		//$result=$objDb->_getList($sql);
		
		if(count($result) <= 0)
			$errMsg = "No Records.";
		}
		
	if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows'])
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $totalRecs;
		$displayString = "Viewing $startNo to $endNo of $endNo Homepage";
		
	}
	else
	{
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
	
	
	
	if($totalRecs <= 0)
		$errMsg = "No Records";
		
	//$resultNum=$objDb->_getList($sqlTotNum);
	//percentage of members
	//$memPercentage=round(($totalRecs/$resultNum[0]['count'])*100,2);
	//*************************** Countrywice report for the users Ends  here *****************************	
	
	if($memPercentage == '')
	$memPercentage=0;
	
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
?>	

<HTML><HEAD><TITLE>Jiwok Reports</TITLE>

<script type="text/javascript" src="js/overlib421/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<script type="text/javascript">
//ol_closeclick	= 1;
function myFunction(id){
	ol_texts[id] = 	document.getElementById(id).innerHTML.toString();
}
ol_sticky		= 1;
ol_closeclick	= 1;
ol_fgcolor		= '#000000';
ol_bgcolor		= '#000000';
ol_width		= 400;
ol_closecolor	= '#FFFFFF';
</script>
<style type="text/css">
.popup_box {background-color:#ffffff; font-size:12px; font: tahoma; font-weight: bold; width:100%}
td.boldC {font-weight: bold}
.hidden_div {visibility:hidden; height:0;}
</style>
<? include_once('metadata.php');?>
<BODY class="bodyStyle">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6"> 
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
              
              <form name="reportFrm" action="#" method="post">
                <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
                  <TR> 
                    <TD class=smalltext width="98%" valign="top">
                    
                          <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
                        <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                        <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                      </tr>
                          <tr> 
                            <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                            <td valign="top"> 
                            
                            
                            
                                <TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                                    <TR> 
                                      <TD valign='top' width='564' bgColor='white'><table width=553 height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        <tr>
                                          <td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading">Register Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="center" valign="bottom" class="sectionHeading"><img src="images/download.gif" style="float:right" border="0"></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td width="89"  height="24" align="left" valign="bottom" style="padding-left:10px;"><strong>Select&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                                          <td width="179"  style="padding-top:5px; font-size:20px;"><select id="report_users" name="report_users" style="font-size:11px;">
                                            <option value="" selected>All</option>
                                            <option value="country">New Subscribers</option>
                                            <option value="country">Permanent Subscribers</option>
                                            <option value="country">Free Subscribers</option>
                                            <option value="country">Paid Subscribers</option>
                                          </select></td>
                                          <td width="78"  align="left" valign="bottom" style="padding-left:10px;"><strong>Brand</strong>&nbsp;&nbsp;&nbsp;</td>
                                          <td width="207"  style="padding-top:5px;"><select id="user_country" name="user_country"  style="font-size:11px;">
                                            <option value="0" selected>--Select--</option>
                                            <option value="0">All</option>
                                            <option value="0">Site Jiwok</option>
                                            <option value="0">Kalenji</option>
                                            <option value="0">Marathon</option>
                                            <option value="0">Nabaji</option>
                                          </select></td>
                                        </tr>
                                        <tr style="padding-top:20px;">
                                          <td align="left" valign="bottom" style="padding-left:10px;padding-top:20px;"><strong>Country</strong>&nbsp;&nbsp;</td>
                                          <td style="padding-top:20px;"><select id="user_country" name="user_country" style="font-size:11px;" >
                                            <option value="0" selected>--Select--</option>
                                            <option value="0">India</option>
                                            <option value="0">France</option>
                                            <option value="0">Pakistan</option>
                                            <option value="0">Newzeland</option>
                                            <option value="0">Afghanistan</option>
                                          </select></td>
                                          <td align="left" valign="bottom" style="padding-left:10px;"><strong>Age</strong></td>
                                          <td style="padding-top:20px;"><input type="text" name="age" size="5" />&nbsp;- &nbsp;<input type="text" name="age" size="5" /></td>
                                        </tr>
                                        <tr style="padding-top:20px;">
                                          <td align="left" valign="bottom" style="padding-left:10px;padding-top:20px;"><strong>Sex</strong></td>
                                          <td style="padding-top:20px;"><select id="user_country" name="user_country" style="font-size:11px;" >
                                            <option value="0" selected>--Select--</option>
                                            <option value="0">Male</option>
                                            <option value="0">Female</option>
                                          </select></td>
                                          <td align="left" valign="bottom" style="padding-left:10px;padding-top:20px;"><strong>Language</strong></td>
                                          <td style="padding-top:20px;"><select id="user_country" name="user_country" style="font-size:11px;" >
                                            <option value="0" selected>--Select--</option>
                                            <option value="0">English</option>
                                            <option value="0">French</option>
                                          </select></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px; padding-top:20px;"><span class="successAlert" >Filter by date</span></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom"><div id="dateDisplay">
                                            <table width="100%">
                                              <tr>
                                                <td width="29%" height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
                                                  <span class="successAlert"><input name="daterange" id="daterange1" type="radio" value="1"  >
                                                  Week</span>
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
                                                  <select id="user_country" name="user_country"  style="font-size:11px;">
                                                    <option value="0" selected>--week--</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
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
                                                  <input name="daterange" id="daterange2" type="radio" value="2" >
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
                                                  <input name="daterange" id="daterange2" type="radio" value="2" >
                                                  Number of months
                                                  </span>
                                                  </td>
                                                  <td width="71%"><span class="successAlert">
                                                  <select id="user_country" name="user_country" style="font-size:11px;" >
                                                    <option value="0" selected>--months--</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="5">6</option>
                                                    <option value="5">7</option>
                                                    <option value="5">8</option>
                                                    <option value="5">9</option>
                                                    <option value="5">10</option>
                                                    <option value="5">11</option>
                                                    <option value="5">12</option>
                                                  </select>
                                                </span></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </tr>
                                        <tr style="padding-top:20px;">
                                          <td align="left" valign="bottom" style="padding-left:10px;padding-top:20px;" colspan="6"><strong>Transaction</strong>&nbsp;&nbsp;
                                            <select id="user_country" name="user_country" style="font-size:11px;" >
                                              <option value="0" selected>--Select--</option>
                                              <option value="0">1 Euro pay</option>
                                              <option value="0">7.9 Euro</option>
                                              <option value="0">from 1 Euro pay a second month at 7.90 Euro</option>
                                            </select></td>
                                        </tr>
                                        <tr style="padding-top:20px;">
                                          <td align="center" valign="bottom" style="padding-left:40px;padding-top:20px;" colspan="6"><input type="submit" name="submit" value="Search"/></td>
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
                                      <TR >
                                        <TD width="50%" align="left" class="sectionHeading">Percentage of Subscriptions :</TD>
                                        <TD width="50%" align="left" class="sectionHeading">10% </TD>
                                      </TR>
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                      <TR class="tableHeaderColor">
                                        <TD width="3%" align="center" >#</TD>
                                        <TD width="19%" align="center" >Brand</TD>
                                        <TD width="32%" align="center" >Member Name </TD>
                                        <TD width="25%" align="center" >Country</TD>
                                        <TD width="11%" align="center" >Sex</TD>
                                        <TD width="10%" align="center" >Age</TD>
                                       </TR>
                                     
                                                          
                                     <tr class="listingTable">
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        					<tr>
                                        
                                       <TD width="6%" height="19" align="center">1</TD>
                                       <TD width="16%"  height="19"  align="left" style="padding-left:10px;">Kalenji</TD>
                                      <TD width="32%"  height="19" align="left" style="padding-left:10px;">Denis Dhakeir</TD>
                                       <TD width="25%"  height="19"  align="center" style="padding-left:10px;">France</TD>
                                       <TD width="11%"  height="19" align="center" style="padding-left:10px;">Male</TD>
                                      <TD width="10%"  height="19" align="center" style="padding-left:10px;">25</TD>
                                      </tr>
                                            <tr>
                                              <TD width="6%" height="19" align="center">1</TD>
                                              <TD  height="19" align="left" style="padding-left:10px;">Marathon</TD>
                                              <TD height="19" align="left" style="padding-left:10px;">Fred</TD>
                                              <TD  height="19" align="center" style="padding-left:10px;">France</TD>
                                              <TD height="19" align="center" style="padding-left:10px;">Male</TD>
                                              <TD  height="19" align="center" style="padding-left:10px;">30</TD>
                                            </tr>
                                            
                                       </table>
                                        <!-- PAGING START-->
                                        <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                                <tbody>		
                                    <tr>
                                        <td align="left" colspan = "6" class="leftmenu">
                                        <a href="#">
                                        <img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
                                        <a href="#">
                                        <img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
                                        <select name="pageNo" class="paragraph"  onChange="form.submit()">
                                            <?php
                                                for($i = 1; $i <= 5; $i++){
                                            ?>
                                                <option value="<?=$i?>"><?=$i?></option>
                                            <?php
                                                }
                                            
                                            ?>
                                        </select>
                                             of 10]
                                             <a href="#">
                                            <img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
                                            <a href="#">
                                            <img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
                                        </td>
                                        <td width="233" align=right class="paragraph2">Subscription report per page: 
                            
                                        <select class="paragraph"  size=1 name="maxrows">
                                          <option value="0" >10</option>
                                         <option value="0" >20</option>
                                         <option value="0" >30</option>
                                        </select>
                                    </td>
                                    </tr>
                                   </tbody>
                                </table>
                                        <!-- PAGING END-->
                                      </TD>
                                      </tr>                      
                                    </tbody>
                                  </table>
                                     </TD>
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