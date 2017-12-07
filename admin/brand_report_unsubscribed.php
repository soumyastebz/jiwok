<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::>  
   Date			::> 17/03/09
   
   DESCRIPTION::::>>>>
   To generate the report for the Training Program subscriptions.
*****************************************************************************/
	include_once('includeconfig.php');
	include_once("../includes/classes/class.faq.php");
	include_once('../includes/classes/class.trainer.php');
	include_once('../includes/classes/class.brand.php');
	ini_set('display_error', 1);
	error_reporting(E_ERROR | E_WARNING);
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
	$objFaq      =   new Faq($lanId);
	$objGen      =	 new General();
	$objTrainer	 = 	 new Trainer($lanId);
	$objDb       =   new DbAction();
	$objBrand    =   new BrandVersion();
	
	$heading = "Unsubscribed Members Report";
	$countriesArray = $objTrainer->_getCountries();
	$brandArray=$objBrand->getAllBrandName();
	
	if(isset($_REQUEST['param'])){
		extractParams($_REQUEST['param']);
	}
	$param	= '';
	//for generating the month and year specified report for the members and the download
	//$today = getdate();
	$todayDetails	= getdate();
	if($_POST['year']){
	 $currentYear =  $_POST['year'];
	 $param	.=	'&year='.$_POST['year'];
	}
	else
     $currentYear = date('Y');	 
	
	if($_POST['month']){
	 $currentMonth =  $_POST['month'];
	 $param	.=	'&month='.$_POST['month'];
	}
	else
	 $currentMonth = date('m');
	 
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
		//$todayDetails = getdate();
		$today = date('Y-m-d');
		
		switch($_POST['dropdown1']){
			case 'today':
				$whereSql .= " AND UM.user_req_unsubscribe = '".$today."'";
				
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				$whereSql .= " AND UM.user_req_unsubscribe = '".$yesterday."'";
				
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);	
				$whereSql .= " AND UM.user_req_unsubscribe BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				$thisYear  = $todayDetails['year'];
				$whereSql 	 .= " AND MONTH(UM.user_req_unsubscribe) = '".$thisMonth."'  AND YEAR(UM.user_req_unsubscribe) = '".$thisYear."'";
				
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				$lasYear		  = $lastMonthDetails['year'];
				$whereSql 	 .= " AND MONTH(UM.user_req_unsubscribe) = '".$lasMonth."'  AND YEAR(UM.user_req_unsubscribe) = '".$lasYear."'";
				
				break;
				
				case 'last3month':	
				$last3MonthDetails = getdate(strtotime('-3 month'));
				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);	
				$whereSql .= " AND UM.user_req_unsubscribe BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'last6month':	
				$last6MonthDetails = getdate(strtotime('-6 month'));
				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);	
				$whereSql .= " AND UM.user_req_unsubscribe BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'lastyear':	
				$lastyear = getdate(strtotime('last year'));
				$lasYear  = $lastyear['year'];	
				$whereSql .= " AND YEAR(UM.user_req_unsubscribe) = '".$lasYear."'";
				
				break;
				
			case 'alltime':	
				$whereSql 	 .= "";
				
				break;
		}
		$whereSql_2 .= $whereSql;
	
	} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
		$param	.=	'&daterange='.$_POST['daterange'];
		$param	.=	'&frY='.$_POST['frY'].'&frM='.$_POST['frM'].'&frD='.$_POST['frD'];
		$param	.=	'&toY='.$_POST['toY'].'&toM='.$_POST['toM'].'&toD='.$_POST['toD'];
		
		$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
		$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
		
		if($startDate > $endDate){
			$errorMsg[] = "Start date should be smaller than end date";
			//unset($_POST['frY'], $_POST['frM'], $_POST['frD'], $_POST['toY'], $_POST['toM'], $_POST['toD']);
			
		}
		if(count($errorMsg) == 0){
			if($startDate != $endDate){
				$whereSql 	 .= " AND UM.user_req_unsubscribe BETWEEN '".$startDate."' AND '".$endDate."'";
				
			}else{ // Then we don't need a BETWEEN clause :)
				$whereSql .= " AND UM.user_req_unsubscribe = '".$startDate."'";
			}			
		}
		$whereSql_2 .= $whereSql;
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
		 $whereSql .= " AND UM.user_status = ".$chkCondition; 
	}
	
	if($_POST['report'] == 'unsub'){
		 $whereSql .= " AND UM.user_unsubscribed = 2"; 
	}
	$whereSqlBrand="";
	if($_REQUEST['brand']!='')
	{
		$whereSqlBrand .= " AND brand_master.brand_master_id = ".$_REQUEST['brand']; 
	}
	$query	= "SELECT count(*) AS total	FROM
				user_master AS UM
				WHERE UM.user_unsubscribed > 0 ";
	$totals = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	$query	= "SELECT count(*) AS total_condition FROM user_master AS UM 
	RIGHT JOIN brand_user ON ( UM.user_id = brand_user.user_id )
LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
				WHERE UM.user_unsubscribed > 0 
				$whereSql $whereSqlBrand";
				//die($query);
	$total_condition	= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	if($total_condition['total_condition']>0){
		$cent_unit	= round((100/$total_condition['total_condition']), 3);
		$sql = "SELECT brand_master.brand_name,UM.user_fname, UM.user_lname, UM.user_id, UM.user_alt_email as user_email, 
					DATE_FORMAT(UM.user_req_unsubscribe, '%D %M %Y') AS user_req_unsubscribe, 
					DATE_FORMAT(UM.user_doj, '%D %M %Y') AS user_doj, 
					UM.user_unsubscribed, UM.unsubscribe_date, 
					DATEDIFF(UM.user_req_unsubscribe, UM.user_doj) AS period
				FROM user_master AS UM 
				RIGHT JOIN brand_user ON ( UM.user_id = brand_user.user_id )
LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
				WHERE 0 < UM.user_unsubscribed 
				$whereSql $whereSqlBrand 
				ORDER BY UM.user_req_unsubscribe DESC";
	//*************************** Countrywice report for the users starts here ****************************
	//$sql = $sqlStart.$fromSql.$whereSql." GROUP BY user_master.user_id ORDER BY user_master.user_doj DESC";
		$paging_query	= "SELECT count(*) FROM user_master AS UM 
						LEFT JOIN brand_user ON ( UM.user_id = brand_user.user_id )
						LEFT JOIN brand_master ON ( brand_master.brand_master_id = brand_user.brand_master_id )
							WHERE UM.user_unsubscribed > 0
							$whereSql $whereSqlBrand ";
		$totalRecs		= $GLOBALS['db']->getOne($paging_query);
//////////////////////////////// PAGINATION //////////////////////////////////
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
			$result=$objDb->_getList($sql);
			//echo count($result);
			if(count($result) <= 0)
				$errMsg = "No Records.";
		}
		//echo $sql;	
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
	if($total_condition['total_condition']<=0){
		$errMsg = "No Records";
		$subscribe_percent	= 0;
	}
	else{
		$subscribe_percent	=	round((($total_condition['total_condition']/$totals['total'])*100), 3);
	}
	//*************************** Countrywice report for the users Ends  here *****************************	
	
	//echo($_POST['dropdown1']);
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
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<link href="./js/jscalendar/calendar-blue.css" rel="stylesheet" type="text/css" media="all">
<script language="javascript" src="./js/jscalendar/calendar.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-en.js"></script>
<script language="javascript" src="./js/jscalendar/calendar-setup.js"></script>
<script language="javascript">
//for submiting the form
function change() {
	var $data = document.getElementById("report").value; 
	if($data == 'country'){
		document.getElementById("countryDisplay").style.display="block";
		return false;
	}
	document.reportFrm.submit();
}
function chkValue() {
   	var $value = document.getElementById("dropdown1").value; 
	if($value == 'selectperiod'){
		document.getElementById("daterange2").checked=true;
		document.getElementById("periodDisplay").style.display="block";
	}else{
		document.reportFrm.submit();	
	}
}
//chkValue
</script>
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
                      <TD vAlign=top width=564 bgColor=white> 
                       
			   
                        
				  <table width=553 height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
				  <tr>
						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
				  
				  <tr>
				    <td colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;					</td>
				    <td width="117" height="27" align="center" valign="bottom" class="sectionHeading"><a href="excel_unsub.php?inf=<? echo base64_encode($sql);?>"><img src="../images/sports/english/download.gif" style="float:right" border="0"></a></td>
				  </tr>
				    
				  <tr style="padding-top:10px;">
				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="19" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
			  
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">Choose date range </span></td>
				    </tr>
				  <tr style="padding-top:10px;">
				  <td  colspan="3" align="left" valign="bottom">
				  <div id="dateDisplay">
				  <table><tr> 
					<td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
				      <input name="daterange" id="daterange1" type="radio" value="1" <? if($_POST['daterange'] ==1 or $_POST['daterange'] == '') echo 'checked="checked"';?> >
                      <select name="dropdown1" id="dropdown1" onChange="chkValue();">
                        <?
							$str = '';
							foreach($dropDownArray as $key => $value){
								$str .= '<option value="'.$key.'"';
								if($_POST['dropdown1'] == $key)
									$str .= ' selected = "selected"';
								$str .= '>'.$value.'</option>';
							}
							echo $str;
						 
						?>
                      </select>
				    </span></td></tr></table>
					</div>
					</td>
					</tr>
				  <tr style="padding-top:10px;">
				   <td  colspan="3" align="left" valign="bottom">
				  <div id="periodDisplay" <? if($_POST['daterange'] == 2){?>style="display:block" <? }else{?>style="display:none"<? } ?>>
				  <table width="548">
				    <tr>  
					<td height="21" colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">
				      <input name="daterange" id="daterange2" type="radio" value="2" <? if($_POST['daterange'] == 2) echo 'checked="checked"';?>>
                      <select name="frD">
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
                      <select name="frM">
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
                      <select name="frY">
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
<select name="toD">
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
<select name="toM">
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
<select name="toY">
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
					<td width="109"><input name="image" type="image"  style="float:right;"  onClick="this.form.submit" src="../images/sports/english/generate.gif"></td>
					</tr></table></div>
					
				    </tr>
					<? if(count($errorMg) >0){?>
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
				    </tr>
					<? }?>
				  <tr style="padding-top:10px;">
				    <td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    </tr>
				  
				  <tr style="padding-top:10px;">
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Search For </td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">Brands
					<select name="brand" onChange="document.reportFrm.submit();">
						<option value="">Select Brand</option>
						<?php if($brandArray)
						{
							foreach($brandArray as $brandRow)
							{?>
								<option <?php if($brandRow['brand_master_id']==$_REQUEST['brand']){?> selected="selected"<?php }?> value="<?php echo $brandRow['brand_master_id'];?>"><?php echo $brandRow['brand_name'];?></option>
							<?php
							}
						}?>
					</select></td>
				  </tr>
				  <tr style="padding-top:10px;">
				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">
					<select id="report" name="report" onChange="change();">
					   <option value="all" <? if(!isset($_POST['report']) || $_REQUEST['report']=='all') echo 'selected="selected"'?> >Whole</option>
					   <option value="country" <? if($_REQUEST['report']=='country') echo 'selected="selected"'?>>Per country</option>
					   <option value="inac" <? if($_REQUEST['report']=='inac') echo 'selected="selected"'?>>Inactive Members</option>
					   <option value="act" <? if($_REQUEST['report']=='act') echo 'selected="selected"'?>>Active Members</option>
                       <option value="unsub" <? if($_REQUEST['report']=='unsub') echo 'selected="selected"'?>>Fully Unsubscribed</option>
				    </select>
					</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <? //if($_REQUEST['report']=='country') {?>
				  <tr style="padding-top:10px;">
				    <td align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><div id="countryDisplay" <? if($_REQUEST['report']=='country') {?>style="display:block" <? }else{?>style="display:none"<? } ?> ><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr><td height="19" class="sectionHeading"> Country</td>
					</tr>
                      <tr>
                        					
				    <td width="247" height="30" align="left" valign="middle" class="sectionHeading" ><select id="user_country" name="user_country"  onChange="this.form.submit();">
                                    <option value="0">--Select--</option>
                          <? 
                                while(list($code,$name) = each($countriesArray)){
                                    $string = "<option value={$code}";
                                    if($code == $_REQUEST['user_country']){
                                        $string .= " selected";
                                    }
                                    $string .= ">{$name}</option>";
                                    print $string;
                                }
                           ?>
                                </select></td>
                      </tr>
					  
                    </table></div></td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="7" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <? //}?>
				  <tr><td colspan="4"></td></tr>
					<?php if($confMsg != ""){?>
					<tr> <td height="18" colspan="4" align="center" class="successAlert"><?=$confMsg?></td> 
					</tr>
					<?php }
						if(count($errorMsg) > 0){
					?>			<tr>
						<td colspan="4" align="center"  class="successAlert"><?=$errorMsg[0]?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD height="2" colspan="4" align="left"></TD>
					</TR>
				  </table><br/>
                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR >
                        <TD width="50%" align="left" class="sectionHeading">Percentage of Unsubscribed :</TD>
                        <TD width="50%" align="left" class="sectionHeading"><? echo $subscribe_percent;?>% </TD>
                      </TR>
                      </tbody>
                  </table><br/>   
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor" >
                        <TD width="6%" align="center" >#</TD>
                        <TD width="34%" align="center" >Member Name </TD>
                        <TD width="24%" align="center" >Date of Joining </TD>
                        <TD width="21%" align="center" >Date of Unsubscription</TD>
                        <TD width="15%" align="center" >Brand</TD>
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="5" ><font color="#FF0000">
                          <?=$errMsg?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result)>0){
					            $totalCount = 0;
					            foreach($result as $key =>$val){
								$totalCount += $val['countCon'];
								}
					  ?>
                      <tr class="listingTable">
                        <TD align="center" colspan="5" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="34%" height="19" align="left" style="padding-left:10px;"><a href="javascript:;" onMouseOver="myFunction('<?=$val['user_id']?>')" onClick="return overlib(INARRAY, <?=$val['user_id']?>, CAPTION, '<?php
							  echo addslashes($val['user_fname'])." ".addslashes($val['user_lname']).' - ';
							  if($val['user_unsubscribed']==1){
							  	echo 'Requested for unsubscription';
							  } elseif($val['user_unsubscribed']==2){
							  	echo 'Fully unsubscribed';
							  }
							  ?>');" ><? echo $val['user_fname']." ".$val['user_lname'];?></a></TD>
                              <TD width="24%" height="19" align="center" style="padding-left:10px;"><? echo $val['user_doj']?></TD>
                              <TD width="22%" height="19" align="center" style="padding-left:10px;"><? echo $val['user_req_unsubscribe']?></TD>
                              <TD width="14%" align="center" style="padding-left:10px;"><? echo $val['brand_name']?></TD>
                            </tr>
                            <? 
					$count++;
				}
					?></table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="brand_report_unsubscribed.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?><?php if($_REQUEST['brand']!=''){?>&brand=<?=$_REQUEST['brand']?><?php }?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="brand_report_unsubscribed.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?><?php if($_REQUEST['brand']!=''){?>&brand=<?=$_REQUEST['brand']?><?php }?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
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
							 <a href="brand_report_unsubscribed.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?><?php if($_REQUEST['brand']!=''){?>&brand=<?=$_REQUEST['brand']?><?php }?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="brand_report_unsubscribed.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?><?php if($_REQUEST['brand']!=''){?>&brand=<?=$_REQUEST['brand']?><?php }?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td><?php } ?>
						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
					</tr>
				   </tbody>
			 	</table>
						<!-- PAGING END-->						</TD>
                      </tr>
                      <? }?>
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
        <?php 
			include_once("footer.php");
			for($i=0, $max=sizeof($result);$i<$max;$i++){
		?><div id="<?=$result[$i]['user_id']?>" class="hidden_div" >
			<div class="popup_box">
				<table width="100%">
					<tr>
						<td width="50%" class="boldC">Name</td>
                        <td width="50%"><? echo $result[$i]['user_fname'].' '.$result[$i]['user_lname']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
						<td width="50%" class="boldC">Email</td>
                        <td width="50%"><? echo $result[$i]['user_email']; ?></td>
                    </tr>
                    <tr>
                        <td width="50%" class="boldC">Date Joined</td>
                        <td width="50%"><? echo $result[$i]['user_doj']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td width="50%" class="boldC">Date Unsubscribed</td>
                        <td width="50%"><? echo $result[$i]['user_req_unsubscribe']; ?></td>
                    </tr>
                    <tr> 
                        <td width="50%" class="boldC">Membership Period</td>
                        <td width="50%"><? echo $result[$i]['period'].' days'; ?></td>
					</tr>
                    <tr> 
                        <td width="50%" class="boldC">Unsubscribed Fully By Admin</td>
                        <td width="50%"><?php if($result[$i]['user_unsubscribed']==2){ ?><img src="images/y.gif" alt="yes" border="0"><?php } elseif($result[$i]['user_unsubscribed']==1) { ?><img src="images/n.gif" alt="no" border="0"><?php } ?></td>
					</tr>
					<tr>
					<td class="tblbackgnd" colspan="2">
					
					</td>
				</tr>
				</table>
			</div>
		</div>
		<? 
			}
		?></body>
</html>