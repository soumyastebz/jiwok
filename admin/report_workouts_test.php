<?php
$start	=  microtime();
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::>  
   Date			::> 28/01/09
   
   DESCRIPTION::::>>>>
   To generate the report for the Training Program subscriptions.
  
*****************************************************************************/
	include_once('includeconfig.php');
//	include_once('test123.php');
	include_once("../includes/classes/class.faq.php");
	include_once('../includes/classes/class.trainer.php');
	
//	error_reporting(0);
	
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
	
	$heading = "Generation Report";
	$countriesArray = $objTrainer->_getCountries();	
	
	//for generating the month and year specified report for the members and the download
	$today = getdate();
	if($_POST['year'])
	 $currentYear =  $_POST['year'];
	else
     $currentYear = date('Y');	 
	
	if($_POST['month'])
	 $currentMonth =  $_POST['month'];
	else
	 $currentMonth = date('m');
	 
	// Computing the date range...
	/**
	*    Computing the date range
	*/
	// If the first drop down was selected..
	if($_POST['daterange'] == 1){
		
		// get today's details
		$todayDetails = getdate();
		$today = date('Y-m-d');
		
		switch($_POST['dropdown1']){
			case 'today':
				$whereSql .= " AND DATE(PQ.invoke_time) = '".$today."'";
				
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				$whereSql .= " AND DATE(PQ.invoke_time) = '".$yesterday."'";
				
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);	
				$whereSql .= " AND DATE(PQ.invoke_time) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				$thisYear  = $todayDetails['year'];
				$whereSql 	 .= " AND MONTH(PQ.invoke_time) = '".$thisMonth."'  AND YEAR(PQ.invoke_time) = '".$thisYear."'";
				
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				$lasYear		  = $lastMonthDetails['year'];
				$whereSql 	 .= " AND MONTH(PQ.invoke_time) = '".$lasMonth."'  AND YEAR(PQ.invoke_time) = '".$lasYear."'";
				
				break;
				
				case 'last3month':	
				$last3MonthDetails = getdate(strtotime('-3 month'));
				$requiredDate	   = date('Y-m-d',$last3MonthDetails[0]);	
				$whereSql .= " AND DATE(PQ.invoke_time) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'last6month':	
				$last6MonthDetails = getdate(strtotime('-6 month'));
				$requiredDate	   = date('Y-m-d',$last6MonthDetails[0]);	
				$whereSql .= " AND DATE(PQ.invoke_time) BETWEEN '".$requiredDate."' AND '".$today."'";
				
				break;
				
				case 'lastyear':	
				$lastyear = getdate(strtotime('last year'));
				$lasYear  = $lastyear['year'];	
				$whereSql .= " AND YEAR(PQ.invoke_time) = '".$lasYear."'";
				
				break;
				
			case 'alltime':	
				$whereSql 	 .= "";
				
				break;
		}
		$wheresql_2 .= $wheresql;
	
	} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
		$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
		$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
		
		if($startDate > $endDate)
			$errorMsg[] = "Start date should be smaller than end date";
		
		if(count($errorMsg) == 0){
			if($startDate != $endDate){
				$whereSql 	 .= " AND DATE(PQ.invoke_time) BETWEEN '".$startDate."' AND '".$endDate."'";

				
			}else{ // Then we don't need a BETWEEN clause :)
				$whereSql .= " AND DATE(PQ.invoke_time) = '".$startDate."'";
			}			
		}
		$wheresql_2 .= $wheresql;
	}
	
	
	
	if($_POST['report'] == 'country' && $_POST['user_country']!="")
		$whereSql .= " AND UM.user_country = ". $_POST['user_country']." ";
	
	
	if($_POST['report'] == 'act' || $_POST['report'] == 'inac'){
	    if($_POST['report'] == 'act')
		   $chkCondition = '1';
		if($_POST['report'] == 'inac')
		  $chkCondition = '2';
		
		 $whereSql .= " AND UM.user_status = ".$chkCondition; 
	
	}
	
		
	$query	= "SELECT count(*) AS total	FROM program_queue";
	$totals = $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	$query	= "SELECT count(*) AS total_condition	FROM program_queue AS PQ
				INNER JOIN user_master AS UM ON UM.user_id = PQ.user_id
				$whereSql";
				//die($query);
	$total_condition	= $GLOBALS['db']->getRow($query, DB_FETCHMODE_ASSOC);
	if($total_condition['total_condition']>0){
		$cent_unit	= round((100/$total_condition['total_condition']), 4);
		$sql = "SELECT UM.user_fname, UM.user_lname, PQ.user_id, 
					COUNT(PQ.user_id) AS count, 
					($cent_unit * COUNT(PQ.user_id)) AS percent
					FROM program_queue AS PQ 
					INNER JOIN user_master AS UM ON UM.user_id = PQ.user_id
					$whereSql
					GROUP BY PQ.user_id ORDER BY COUNT(PQ.user_id) DESC";
					//die($sql);
	//$fromSql  = " FROM user_master ";
	//$whereSql = " WHERE user_master.user_type=1 ";
		
	//*************************** Countrywice report for the users starts here ****************************
	//$sql = $sqlStart.$fromSql.$whereSql." GROUP BY user_master.user_id ORDER BY user_master.user_doj DESC";
		$result=$objDb->_getList($sql);
		for($i=0, $max=sizeof($result); $i<$max; $i++){
			$user_ids_arr[]	= $result[$i]['user_id'];
//			$language_arr[$result[$i]['user_id']]	= $result[$i]['user_language']
		}
		$user_ids	= implode(',', $user_ids_arr);
		$sql	=	"SELECT PQ.user_id, PQ.workout_flex_id, WD.workout_title, DATE_FORMAT(PQ.invoke_time, '%D %M %Y') AS invoke_time
					FROM program_queue AS PQ 
					LEFT JOIN workout_details AS WD ON WD.workout_flex_id = PQ.workout_flex_id
					WHERE PQ.user_id IN ($user_ids)
					$wheresql_2
					GROUP BY PQ.invoke_time";
//		die($sql);
//print_r($GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC));
		$rs		=	$GLOBALS['db']->query($sql);
		while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){
	//		$programs	= $GLOBALS['db']->getAll($sql, DB_FETCHMODE_ASSOC);
//print_r($row);
//exit;
			$programs[$row['user_id']][]	= $row;
		}
		$rs->free();
//print_r($programs);
//exit;


	}	
//	print_r($programs);
//	exit;
	if($total_condition['total_condition']<=0) $errMsg = "No Records";
	else{
		$subscribe_percent	=	($total_condition['total_condition']/$totals['total'])*100;
	}
	//*************************** Countrywice report for the users Ends  here *****************************	
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
chkValue
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
								$str .= '<option value="'.$i.'"';
								if($_POST['frD'] == $i)
									$str .= ' selected = "selected"';
								$str .= '>'.$i.'</option>';
							}
							echo $str;
						?>
                      </select>
                      <select name="frM">
                        <?
							$mArray = array(
					      				"1" => "January",
										"2" => "February",
										"3" => "March",
										"4" => "April",
										"5" => "May",
										"6" => "June", 
										"7" => "July", 
										"8" => "August", 
										"9" => "September", 
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
							for($i=$today['year']-5;$i<=$today['year'];$i++){
								$str .= '<option value="'.$i.'"';
								if($_POST['frY'] == "" and $i == $today['year'])
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
								$str .= '<option value="'.$i.'"';
								if($_POST['toD'] == $i)
									$str .= ' selected = "selected"';
								$str .= '>'.$i.'</option>';
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
							for($i=$today['year']-5;$i<=$today['year'];$i++){
								$str .= '<option value="'.$i.'"';
								if($_POST['toY'] == "" and $i == $today['year'])
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
					   <option value="subc" <? if($_REQUEST['report']=='subc') echo 'selected="selected"'?>>Not Subscribed Yet</option>
					   <option value="down" <? if($_REQUEST['report']=='down') echo 'selected="selected"'?>>Not Downloaded Yet</option>
					   <option value="inac" <? if($_REQUEST['report']=='inac') echo 'selected="selected"'?>>Inactive Members</option>
					   <option value="act" <? if($_REQUEST['report']=='act') echo 'selected="selected"'?>>Active Members</option>
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
						<td colspan="4" align="center"  class="successAlert"><?=$errorMsg?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD height="2" colspan="4" align="left">					</TD>
					</TR>
				  </table><br/>
                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR >
                        <TD width="50%" align="left" class="sectionHeading">Percentage of Generations :</TD>
                        <TD width="50%" align="left" class="sectionHeading"><? echo $subscribe_percent;?>% </TD>
                      </TR>
                      </tbody>
                  </table><br/>   
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="37%" align="center" >Member Name </TD>
                        <TD width="32%" align="center" > No of Generations </TD>
                        <TD width="25%" align="center" >Percentage</TD>
                      </TR>
                      <?php if($errMsg != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="4" ><font color="#FF0000">
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
                        <TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <? foreach($result as $key =>$val){  ?>
                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $key+1;?></TD>
                              <TD width="37%" height="19" align="left" style="padding-left:10px;"><a href="javascript:;" onMouseOver="myFunction('<?=$val['user_id']?>')" onClick="return overlib(INARRAY, <?=$val['user_id']?>, myFunction('<?=$val['user_id']?>'), CAPTION, '<? echo htmlspecialchars($val['user_fname'])." ".htmlspecialchars($val['user_lname']).' - '.$val['count'];?>');" ><? echo $val['user_fname']." ".$val['user_lname'];?></a></TD>
                              <TD width="32%" height="19" align="center" style="padding-left:10px;"><? echo $val['count']?></TD>
                              <TD width="25%" height="19" align="center" style="padding-left:10px;"><? echo $val['percent']?></TD>
                            </tr>
							
                            <? }?>
							
                        </table></TD>
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
        <?php include_once("footer.php");
		for($i=0, $max=sizeof($result);$i<$max;$i++){
		?>
		<div id="<?=$result[$i]['user_id']?>" style="visibility:hidden" >
			<div class="popup_box">
				<table width="100%">
					<tr>
						<td class="boldC">Title</td>
						<td class="boldC">Workout Date</td>
					</tr>
				<?php
				for($j=0, $max2=sizeof($result[$i]['user_id']); $j<$max2; $j++){
					for($k=0, $max3=sizeof($programs[$result[$i]['user_id']]); $k<$max3; $k++){
				?>
						<tr>
							<td><?=$programs[$result[$i]['user_id']][$k]['workout_title']?></td>
							<td><?=$programs[$result[$i]['user_id']][$k]['invoke_time']?></td>
						</tr>
				<?
					}
				} ?>
				</table>
			</div>
		</div>
		<? } 
		$end	=  microtime();
		echo($end - $start);
		?>		
</body>
</html>