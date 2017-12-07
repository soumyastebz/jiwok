<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::> soumya 
   Date			::> 14/02/14
   
   DESCRIPTION::::>>>>
   To generate the report for the New gift code pop up payment.
  
*****************************************************************************/
	include_once('includeconfig.php');
	
	include_once('../includes/classes/class.newpayment.php');
	include_once('../includes/classes/class.trainer.php');
	include_once('../includes/classes/class.GiftCodeCampaign.php');
	
//	error_reporting(0);

	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
	
	
	$selected	=	'selected="selected"';
	/*
	Take all the languages to an array.
	*/
	
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$paymentClass	=	new newPayment();
	$objGen      =	 new General();
	$objTrainer	 = 	 new Trainer($lanId);
	$objDb       =   new DbAction();
	$objgiftpay	 =	 new GiftCodeCampaign();
	
	$heading = "Giftcode payment Report";
	$countriesArray = 	$objTrainer->_getCountries();
	$listcampaigns	=	$objgiftpay->listCampaigns();
	$tot_members	=	$objgiftpay->getTotCampMembers();
	$tot_payments	=	$objgiftpay->getTotPayments();
	//print_r($listcampaigns);exit;
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
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' )  = '".$today."'";		
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) = '".$yesterday."'";
				
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);
				
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				$thisYear  = $todayDetails['year'];
				
				$whereSql 	 .= " AND MONTH(STR_TO_DATE( p.payment_date, '%Y-%m-%d' )) = '".$thisMonth."'  AND YEAR(STR_TO_DATE( p.payment_date, '%Y-%m-%d' )) = '".$thisYear."'";
				
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				$lasYear		  = $lastMonthDetails['year'];
				
				$whereSql 	 .= " AND MONTH(STR_TO_DATE( p.payment_date, '%Y-%m-%d' )) = '".$lasMonth."'  AND YEAR(STR_TO_DATE( p.payment_date, '%Y-%m-%d' )) = '".$lasYear."'";
				
				break;
				
				case 'last3month':	
				$last3MonthDetails = getdate(strtotime('-3 month'));
				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);
				
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'last6month':	
				$last6MonthDetails = getdate(strtotime('-6 month'));
				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);
				
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'lastyear':	
				$lastyear = getdate(strtotime('last year'));
				$lasYear  = $lastyear['year'];
				
				$whereSql .= " AND YEAR(STR_TO_DATE( p.payment_date, '%Y-%m-%d' )) = '".$lasYear."'";
				
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
				
				$whereSql 	 .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) BETWEEN '".$startDate."' AND '".$endDate."'";	
				
			}else{ // Then we don't need a BETWEEN clause :)
				
				$whereSql .= " AND STR_TO_DATE( p.payment_date, '%Y-%m-%d' ) = '".$startDate."'";	
			}			
		}
		$whereSql_2 .= $whereSql;
	}
	
	if(isset($_POST['report']) && $_POST['report']!='all'){
		$param	.=	'&report='.$_POST['report'];
	}
	
	if($_POST['report'] == 'Paid' ){ 
		$param			.=	'&user_paid='.$_POST['Paid'];
		//$wherepaidcamp	 =	"  JOIN payment AS p ON p.payment_id = g.camp_payment_id ";
		$wherepaidSql   .=   " AND g.paid_status = 1 ";
		
	}
	
	if(($_POST['report_camp']) && ($_POST['report_camp'] != "all")){ 
		$param	.=	'&report_camp='.$_POST['report_camp'];
		$wherepaidSql .= " AND g.camp_id =  ".$_POST['report_camp'];
	}
	
	
	$payment_qry	= '';
	
		
//////////////////////////////// PAGINATION //////////////////////////////////
/*=========================================================================*/
	if($tot_members > 0)
	{
		$list_members	="SELECT UM.user_id,UM.user_fname, UM.user_lname,UM.user_email,c.camp_name,c.camp_price,g.paid_status,
						  p.payment_date, p.payment_expdate 
						  FROM gift_user_campaign AS g 
						  LEFT JOIN gift_pay_campaign AS c ON c.id = g.camp_id 
						  INNER JOIN  user_master AS UM ON UM.user_id =g.user_id 
							LEFT JOIN payment AS p ON p.payment_id = g.camp_payment_id 		 
						  WHERE 1 $wherepaidSql $whereSql_2 ORDER BY g.id DESC";
	//die($list_members);
	/*============================================================================*/
		$param	= substr($param, 1);
		$param	= base64_encode($param);
		if(!$_REQUEST['maxrows'])
			$_REQUEST['maxrows'] = $_POST['maxrows'];
		if($_REQUEST['pageNo']){
			if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $tot_members+$_REQUEST['maxrows']){
				$_REQUEST['pageNo'] = 1;
			}
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			
			$list_members.= " LIMIT {$fromLimit}, {$toLimit} ";
			$result=$objDb->_getList($list_members);
		}
		else{
		/***********************Selects Records at initial stage***********************************************/
			$_REQUEST['pageNo'] = 1;
		
			//$result = $objHomepage->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
			$fromLimit = $_REQUEST['maxrows']*($_REQUEST['pageNo'] - 1);
			$toLimit = $_REQUEST['maxrows'];
			
			$list_members.= " LIMIT {$fromLimit}, {$toLimit} ";
			$result=$objDb->_getList($list_members);
			
			/*echo count($result);*/
			if(count($result) <= 0)
				$errMsg = "No Records.";
		}		
		if($tot_members <= $_REQUEST['pageNo']*$_REQUEST['maxrows']){
			//For showing range of displayed records.
			if($tot_members <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $tot_members;
			$displayString = "Viewing $startNo to $endNo of $endNo Homepage";
			
		}
		else{
			//For showing range of displayed records.
			if($tot_members <= 0)
				$startNo = 0;
			else
				$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
			$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
			$displayString = "Viewing $startNo to $endNo of $tot_members homepage";
			
		}
		//Pagin 
		$noOfPage = @ceil($tot_members/$_REQUEST['maxrows']); 		
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
#navigation {
	background: #FFF;
	color: #000;
	font: 62.5% "Lucida Grande", Verdana, Geneva, Helvetica, sans-serif;
	margin: 0;
	padding: 0;
	background: #AFD5E0 url("../images/bg-nav.gif") repeat-x;
	border: 1px solid #979797;
	border-width: 1px 0;
	font-size: 13px;
	margin-top: 1em;
	padding-top: .6em;
}

#navigation ul, #navigation ul li {
	list-style: none;
	margin: 0;
	padding: 0;
}

#navigation ul {
	padding: 5px 0;
	text-align: center;
}

#navigation ul li {
	display: inline;
	margin-right: .75em;
}

#navigation ul li.last {
	margin-right: 0;
}

#navigation ul li a {
	background: url("../images/tab-right.gif") no-repeat 100% 0;
	color: #06C;
	padding: 5px 0;
	text-decoration: none;
}

#navigation ul li a span {
	background: url("../images/tab-left.gif") no-repeat;
	padding: 5px 1em;
}

#navigation ul li a:hover span {
	color: #69C;
	text-decoration: underline;
}

/*\*//*/
#navigation ul li a {
	display: inline-block;
	white-space: nowrap;
	width: 1px;
}

#navigation ul {
	padding-bottom: 0;
	margin-bottom: -1px;
}
/**/
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
				    <td width="117" height="27" align="center" valign="bottom" class="sectionHeading"><a href="excel_gift_payment.php?inf=<? echo base64_encode($list_members);?>&whereSql=<? echo base64_encode($whereSql_2);?>"><img src="../images/sports/english/download.gif" style="float:right" border="0"></a></td>
				  </tr>
				    
				  <tr style="padding-top:10px;">
				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="19" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
			  
				  
				  <tr style="padding-top:10px;">
				  <td  colspan="3" align="left" valign="bottom">
				  <div id="dateDisplay" <? if($_POST['report'] == 'Paid' ){?>style="display:block" <? }else{?>style="display:none"<? } ?> >
                  
				  <table><td height="21" colspan="3" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert">Choose date range </span></td><tr> 
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
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Search For </td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"></td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <tr style="padding-top:10px;">
                   
				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Users			:	
					<select id="report" name="report" onChange="this.form.submit();" style="font-size:10px; width:100px; margin-left:56px;">
					   <option value="all" <? if(!isset($_POST['report']) || $_REQUEST['report']=='all') echo 'selected="selected"'?> >Whole</option>
					   <option value="Paid" <? if($_REQUEST['report']=='Paid') echo 'selected="selected"'?>>Paid Users</option>
					   
				    </select>
					</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
                  <tr style="padding-top:10px;">
                   
				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">Campaigns	:	
					<select id="report_camp" name="report_camp" onChange="this.form.submit();"  style="font-size:10px; width:100px; margin-left:20px;">
					   <option value="all" <? if(!isset($_POST['report_camp']) || $_REQUEST['report_camp']=='all') echo 'selected="selected"'?> >Whole</option>
                       <? 
                                while(list($code,$name) = each($listcampaigns)){
                                    $string = "<option value={$name['id']}";
                                    if($name['id'] == $_REQUEST['report_camp']){
                                        $string .= " selected";
                                    }
                                    $string .= ">{$name['camp_name']}</option>";
                                    print $string;
                                }
                           ?>
				    </select>
					</td>
				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				 
                  <tr style="padding-top:10px;">
				    <td height="21" align="right" valign="bottom" class="sectionHeading" style="padding-left:10px;"><span class="successAlert"><strong>Total Members</strong> </span></td>
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;"><?php echo $tot_members ; ?></td>
				    <td height="21" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    </tr>     
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
                  <?php
				  if(count($tot_payments)>0)
				  {
					  ?> 
                  	<fieldset>
						<legend>Payment details </legend>
                        <table width="90%" align="center" cellpadding="3" >
						<tr>
							<td width="28%" align="left"><strong>Total Payments</strong></td>
                            <td width="72%"><? $tot_payments=$objgiftpay->getTotPayments("",$whereSql_2);echo $tot_payments;?></td>
                       	</tr>
                        
                        <tr>
                          <td colspan="2" align="left">
                          	<fieldset>
								<legend>Payment details per Campaign </legend>
                                <table width="90%" align="center" cellpadding="3" >
                                <tr>
                                    <td width="50%" align="Center"><strong>Campaign</strong></td>
                                    <td width="25%" align="Center"><strong>Total Payments</strong></td>
                                    <td width="25%" align="Center"><strong>Total Amount</strong></td>
                                </tr>
                                <?php foreach( $listcampaigns	as  $campid => $listcampaign)
                                {?>
                                <tr>
                                    <td align="center"><?=$listcampaign['camp_name']."(".$listcampaign['camp_price']." Euro)";?></td>
                                    <td align="center"><?php $percamp_toPayment = $objgiftpay->getTotPayments($listcampaign['id'],$whereSql_2); echo $percamp_toPayment;?></td>
                                    <td align="center"><?php $totalpaymentamount =$objgiftpay->getTotPaymentAmount($listcampaign['id'],$whereSql_2);echo $totalpaymentamount;
									?></td>
                                </tr>
                                <?php }?>											                      
                                </table>
							</fieldset>
                           </td>
                          </tr>											                      
                        </table>
					</fieldset><br/>
                  <?php }?>                 
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="30%" align="center" >Name </TD>
                        <TD width="30%" align="center" >Email </TD>
                        <TD width="20%" align="center" >Campaign </TD>
                        <TD width="8%" align="center" >Amount</TD>
                        <TD width="6%" align="center" >Status</TD>
                        </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="6" ><font color="#FF0000">
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
                        <TD align="center" colspan="6" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <?
				$count = $startNo; 
				foreach($result as $key =>$val){ 
					$val['user_fname'] = htmlspecialchars(stripslashes($val['user_fname']));
					$val['user_lname'] = htmlspecialchars(stripslashes($val['user_lname']));
			?><tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $count;?></TD>
                              <TD width="30%" height="19" align="left" style="padding-left:10px;"><a href="javascript:;" onMouseOver="myFunction('<?=$val['user_id'].$key?>')" onClick="return overlib(INARRAY, <?=$val['user_id'].$key?>, CAPTION, '<? echo addslashes($val['user_fname'])." ".addslashes($val['user_lname']);?>');" ><? echo $val['user_fname']." ".$val['user_lname'];?></a></TD>
                               <TD width="30%" height="19" align="left" style="padding-left:10px;"><?php echo $val['user_email'];?></TD>
                              <TD width="20%" height="19" align="center" style="padding-left:10px;"><?php echo $val['camp_name'];?></TD>
                              <TD width="8%" height="19" align="center" style="padding-left:10px;"><?php echo $val['camp_price'];?></TD>
                              <TD width="6%" height="19" align="center" style="padding-left:10px;"><?php echo $val['paid_status'];?></TD>
                              </tr>
							
                            <? 
					$count++;
				}
			?>							
                        </table>
						<!-- PAGING START-->
						<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr><?php if($noOfPage > 1) { ?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="report_giftcode_payment.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="report_giftcode_payment.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
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
							 <a href="report_giftcode_payment.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="report_giftcode_payment.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">
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
                      <? }
					  else{
					  ?>
					  <tr class="listingTable"><TD align="center" colspan="6" >No Records</TD></tr><? } ?>
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
				//$trans_user_id	=	explode(",",$result[$i]['trans_refrns_id']);
				//$trans_user_id	= explode(',', $result[$i]['trans_refrns_id']);
				//unset($trans_user_id[0]);
				//$reffer_id		= implode(',',$trans_user_id);				
		?><div id="<?=$result[$i]['user_id'].$i?>" class="hidden_div" >
			<div class="popup_box">
				<table width="100%">
					<tr>
						<td class="boldC" align="center">User details</td>
					</tr>
                    <tr>
					<td class="tblbackgnd">
					<table width="100%" cellspacing="0" cellpadding="1">
					  <tr bgcolor="#FFFFFF">
						<td width="27%">Email : </td>
						<td width="73%"><?php echo $result[$i]['user_email'];?></td>
					  </tr>
                      <tr bgcolor="#FFFFFF">
						<td width="27%">Paid Date : </td>
						<td width="73%"><?php echo $result[$i]['payment_date'];?></td>
					  </tr>  
                      <tr>
						<td width="27%">Expire Date : </td>
						<td width="73%"><?php echo $result[$i]['payment_expdate'];?></td>
					  </tr>
                                           
					</table>
					</td>
				</tr>                    
				</table>
			</div>
		</div>
		<? 
			}
		?></body>
</html>