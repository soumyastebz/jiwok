<?php

/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> Admin-Report

   Programmer	::>  

   Date			::> 

   

   DESCRIPTION::::>>>>

   To generate the report for the campaigns.

  

*****************************************************************************/

	include_once('includeconfig.php');

	include_once('../includes/classes/class.trainer.php');

	

	error_reporting(0);

	

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

	$objTrainer	 = 	 new Trainer($lanId);

	$objDb       =   new DbAction();

	

	$heading = "Campaign Report";

	$countriesArray = $objTrainer->_getCountries();	

	

	if(isset($_REQUEST['param'])){

		extractParams($_REQUEST['param']);

	}

	$param	= '';

	//for generating the month and year specified report for the members and the download

	$todayCalendar = getdate();



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

	 //***********for polishadmin
	if($page_name!= "")
	{
		$joinquery	=	" and user_master.user_language =5";
	}
	else
	{
		$joinquery	=	"";
	}
	//**************************

	

	$sqlStart = "SELECT user_master.user_id,user_master.user_fname,user_master.user_lname,campaign_reports.jiwok_code AS jiwok_code,  campaign_reports.date AS date_used, campaign_reports.no_of_months AS free_period, COUNT(campaign_reports.user_id) AS count,campaign_manage.camp_name";

	$fromSql  = " FROM user_master,campaign_manage RIGHT JOIN campaign_reports ON ( campaign_manage.camp_id = campaign_reports.jiwok_code )";

	$whereSql = " WHERE user_master.user_id = campaign_reports.user_id";

	//Total num of jiwok users

	$sqlTotNum = "SELECT DISTINCT (user_master.user_id) AS cnt from user_master,campaign_reports WHERE user_master.user_id = campaign_reports.user_id  ".$joinquery;

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

				$whereSql .= " AND campaign_reports.date = '".$today."'";

				

				break;

			

			case 'yest':

				$lastDayDetails = getdate(strtotime('yesterday'));

				$yesterday		= date('Y-m-d',$lastDayDetails[0]);

				$whereSql .= " AND campaign_reports.date = '".$yesterday."'";

				

				break;

				

			case 'last7':

				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));

				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);	

				$whereSql .= " AND campaign_reports.date BETWEEN '".$requiredDate."' AND '".$today."'";

				

				break;

			

			case 'thismonth':	

				$thisMonth = $todayDetails['mon'];

				$thisYear  = $todayDetails['year'];

				$whereSql 	 .= " AND MONTH(campaign_reports.date) = '".$thisMonth."'  AND YEAR(campaign_reports.date) = '".$thisYear."'";

				

				break;

				

			case 'lastmonth':	

				$lastMonthDetails = getdate(strtotime('last month'));

				$lasMonth		  = $lastMonthDetails['mon'];

				$lasYear		  = $lastMonthDetails['year'];

				$whereSql 	 .= " AND MONTH(campaign_reports.date) = '".$lasMonth."'  AND YEAR(campaign_reports.date) = '".$lasYear."'";

				

				break;

				

				case 'last3month':	

				$last3MonthDetails = getdate(strtotime('-3 month'));

				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);	

				$whereSql .= " AND campaign_reports.date BETWEEN '".$requiredDate."' AND '".$today."'";

				

				break;

				

				case 'last6month':	

				$last6MonthDetails = getdate(strtotime('-6 month'));

				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);	

				$whereSql .= " AND campaign_reports.date BETWEEN '".$requiredDate."' AND '".$today."'";

				

				break;

				

				case 'lastyear':	

				$lastyear = getdate(strtotime('last year'));

				$lasYear  = $lastyear['year'];	

				$whereSql .= " AND YEAR(campaign_reports.date) = '".$lasYear."'";

				

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

				$whereSql 	 .= " AND campaign_reports.date BETWEEN '".$startDate."' AND '".$endDate."'";

				

			}else{ // Then we don't need a BETWEEN clause :)

				$whereSql .= " AND campaign_reports.date = '".$startDate."'";

				

			}			

		}

	}

	

	if(isset($_POST['report']) && $_POST['report']!='all'){

		$param	.=	'&report='.$_POST['report'];

	}

	

	if($_POST['report'] == 'country' && $_POST['user_country']!="" && $_POST['user_country']!="0"){

		$param	.=	'&user_country='.$_POST['user_country'];

		$whereSql .= " AND user_master.user_country = ". $_POST['user_country']." ";

	}

	

	

		

	//*************************** Countrywice report for the users starts here ****************************



	$sql = $sqlStart.$fromSql.$whereSql.$joinquery." GROUP BY user_master.user_id ORDER BY user_master.user_fname ASC  ";

	//print $sql;

	 $totalRecs = $objDb->_isExist($sql);

	

	

	//***************************report section ends here *****************************	

	

	##############################################################################################################

	/*                        Following Code is for doing paging                                                */

	##############################################################################################################

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

		$result=$objDb->_getList($sql);

		/*echo count($result);*/

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

		

 if($totalRecs > 0){

	

	//for pop up display

	$result=$objDb->_getList($sql);

		for($i=0;$i<sizeof($result); $i++){

			$user_ids_arr[]	= $result[$i]['user_id'];

		}

		$user_ids	= implode(',', $user_ids_arr);

		$sql1	=	"SELECT  campaign_reports.user_id,campaign_reports.date as date_used, campaign_reports.no_of_months as free_period, campaign_reports.jiwok_code FROM campaign_reports WHERE campaign_reports.user_id IN ($user_ids) ";

		$rs		=	$GLOBALS['db']->query($sql1);

		

		while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){

			$programs[$row['user_id']][]	= $row;

		}

		$rs->free();

	$resultNum=$objDb->_isExist($sqlTotNum);

	//percentage of members

	$memPercentage=round(($totalRecs/$resultNum)*100,2);

	}

	

	if($totalRecs <= 0)

		$errMsg = "No Records";

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

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<link href="./js/jscalendar/calendar-blue.css" rel="stylesheet" type="text/css" media="all">

<script language="javascript" src="./js/jscalendar/calendar.js"></script>

<script language="javascript" src="./js/jscalendar/calendar-en.js"></script>

<script language="javascript" src="./js/jscalendar/calendar-setup.js"></script>

<script language="javascript">

//for submiting the form

function change() {

    document.reportFrm.submit();

	var $data = document.getElementById("report").value; 

		if($data == 'country'){

		 document.getElementById("countryDisplay").style.display="block";

		}

	



}

function chkValue() {

   	var $value = document.getElementById("dropdown1").value; 

	if($value == 'selectperiod'){

	document.getElementById("daterange2").checked=true;

	document.getElementById("periodDisplay").style.display="block";

	}	

    else

	document.reportFrm.submit();

}



</script>

<script type="text/javascript" src="js/overlib421/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>

<script type="text/javascript">

//ol_closeclick	= 1;

function myFunction(id){

	ol_texts[id] = 	document.getElementById(id).innerHTML.toString();

}

ol_sticky		= 1;

ol_closeclick	= 1;

ol_bgcolor		= '#000000';

ol_width		= 400;

//ol_relx			= 50;

//ol_rely			= 50;

</script>

<style type="text/css">

.popup_box {background-color:#ffffff; font-size:12px; font: tahoma; font-weight: bold; width:100%}

td.boldC {font-weight: bold}

.hidden_div {visibility:hidden; height:0;}

</style>



<? include_once('metadata.php');?>

<BODY class="bodyStyle">

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

                       

			   <form name="reportFrm" action="#" method="post">

                        

				  <table width=553 height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">

				  <tr>

						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>

				    

				  <tr>

				    <td colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;					</td>

				    <td width="117" height="27" align="center" valign="bottom" class="sectionHeading"><a href="excel_campaign.php?inf=<? echo base64_encode($sql);?>"><img src="../images/sports/english/download.gif" style="float:right" border="0"></a></td>

				  </tr>

					

				  <tr style="padding-top:10px;">

				    

				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="19" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <!--<tr style="padding-top:10px;">

				    

				    <td width="191" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="16" align="center" valign="bottom" class="sectionHeading"><input name="image" type="image"  style="float:right;"  onClick="this.form.submit" src="../images/sports/english/generate.jpg"></td>

				  </tr>-->

				  

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

							for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){

								$str .= '<option value="'.$i.'"';

								if($_POST['frY'] == "" and $i == $todayCalendar['year'])

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

							for($i=$todayCalendar['year']-5;$i<=$todayCalendar['year'];$i++){

								$str .= '<option value="'.$i.'"';

								if($_POST['toY'] == "" and $i == $todayCalendar['year'])

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

				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <tr style="padding-top:10px;">

				    <td height="24" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">

					<select id="report" name="report" onChange="change();">

					   <option value="all" <? if($_REQUEST['report']=='all') echo 'selected="selected"'?> >Whole</option>

					   <option value="country" <? if($_REQUEST['report']=='country') echo 'selected="selected"'?>>Per country</option>

					   <!--<option value="jiwok_code" <? if($_REQUEST['report']=='jiwok') echo 'selected="selected"'?>>Jiwok code</option>

					   <option value="camp_name" <? if($_REQUEST['report']=='camp_name') echo 'selected="selected"'?>>Campaign_name</option>-->

				       </select>	</td>

				    <td width="147" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>

				    <td width="117" height="4" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>

				  </tr>

				  <? if($_REQUEST['report']=='country') {?>

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

				  <? }?>

				  <tr>

				    

				    <td colspan="4"></td>

				    </tr>

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

					<TD height="2" colspan="4" align="left">					</TD>

					</TR>

				  </table><br/>

                  <TABLE cellSpacing=1 cellPadding=2 width="553">

                    <TBODY>

                      <TR >

                        <TD width="34%" align="left" class="sectionHeading">Percentage of members :</TD>

                        <TD width="66%" align="left" class="sectionHeading"><? echo $memPercentage;?>% </TD>

                      </TR>

                      </tbody>

                  </table><br/>   

				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">

                    <TBODY>

                      <TR class="tableHeaderColor">

                        <TD width="4%" align="center" >#</TD>

                        <TD width="20%" align="center" >Member Name </TD>

						<TD width="26%" align="center" >Jiwok Code Used </TD>

                        <TD width="20%" align="center" >Date  </TD>

                        <TD width="20%" align="center" >Free Period </TD>

						<TD width="22%" align="center" >Campaign Name </TD>

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

							foreach($result as $key =>$val){  ?>

                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >

                              <TD width="4%" height="19" align="center"><? echo $count;?></TD>

                              <TD width="20%" height="19" align="left" style="padding-left:10px;"><a href="javascript:;" onMouseOver="myFunction('<?=$val['user_id']?>')" onClick="return overlib(INARRAY, <?=$val['user_id']?>, myFunction('<?=$val['user_id']?>'), CAPTION, '<? echo addslashes(htmlspecialchars($objGen->_output($val['user_fname'])))." ".addslashes(htmlspecialchars($objGen->_output($val['user_lname']))).' - '.$val['count'];?>');" ><? echo $objGen->_output($val['user_fname'])." ".$objGen->_output($val['user_lname']);?></a></TD>

                               <TD width="20%" height="19" align="center" style="padding-left:10px;"><? echo $val['jiwok_code']?></TD>

							  <TD width="22%" height="19" align="center" style="padding-left:10px;"><? echo $val['date_used']?></TD>

                              <TD width="12%" height="19" align="center" style="padding-left:10px;"><? echo $val['free_period']?> months</TD>

							  <TD width="22%" height="19" align="center" style="padding-left:10px;"><? echo $val['camp_name']?></TD>

                            </tr>

							

                            <?php

						$count++;

						}

						?>

							

                        </table>

						

				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">

                                <tbody>		

					<tr><?php if($noOfPage > 1) { ?>

						<td align="left" colspan = "6" class="leftmenu">

						<a href="report_campaign.php?pageNo=1&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>

						<a href="report_campaign.php?pageNo=<?=$prev?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

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

							 <a href="report_campaign.php?pageNo=<?=$next?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>

							<a href="report_campaign.php?pageNo=<?=$noOfPage?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&param=<?=$param?>">

							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>

						</td><?php } ?>

						<td width="233" align=right class="paragraph2"><?=$heading;?> per page: 

			

						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">

						<? foreach($siteRowListConfig as $key=>$data){ ?>

						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>

						 <? } ?>

						</select>

					</td>

					</tr>

				   </tbody>

			 	</table>

						</TD>

                      </tr>

                      <? }?>

                    </tbody>

                  </table>

				  

				</form>

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



          </TD>

        </TR>

		 <TR height="2">

    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

      </TABLE>

        <?php include_once("footer.php");

		for($i=0, $max=sizeof($result);$i<$max;$i++){

		?><div id="<?=$result[$i]['user_id']?>" class="hidden_div" >

			<div class="popup_box">

				<table width="100%">

					<tr>

						<td width="34%" class="boldC">Jiwok Code Used</td>

						<td width="37%" class="boldC">Date</td>

						<td width="29%" class="boldC">Free Period</td>

					</tr>

				<?php

				for($j=0, $max2=sizeof($result[$i]['user_id']); $j<$max2; $j++){

					for($k=0, $max3=sizeof($programs[$result[$i]['user_id']]); $k<$max3; $k++){

				?><tr>

					<td width="34%"><?=$programs[$result[$i]['user_id']][$k]['jiwok_code']?></td>

					<td width="37%"><?=$programs[$result[$i]['user_id']][$k]['date_used']?></td>

					<td width="29%"><?=$programs[$result[$i]['user_id']][$k]['free_period']?> months</td>

				</tr>

				<?

					}

				} 

				?></table>

			</div>

		</div>

		<? } ?>

</body>

</html>