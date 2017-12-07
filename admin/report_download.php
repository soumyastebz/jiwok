<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Report
   Programmer	::> Raneesh A K 
   Date			::> 13/06/2007
   
   DESCRIPTION::::>>>>
   To generate the report for the users,download 
  
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.faq.php");
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
						 
	/*
	 Instantiating the classes.
	*/
	$objFaq      =   new Faq($lanId);
	$objGen      =	 new General();
	$objTrainer	 = 	 new Trainer($lanId);
	$objDb       =   new DbAction();
	
	$heading = "General Report";
	$countriesArray = $objTrainer->_getCountries();	
	
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
				$memberSearch = " AND user_master.user_doj = '".$today."'";
				$downloadSearch  = " AND download_date = '".$today."'";
				$subscribeSearch = " AND subscribed_date = '".$today."'";
				break;
			
			case 'yest':
				$lastDayDetails = getdate(strtotime('yesterday'));
				$yesterday		= date('Y-m-d',$lastDayDetails[0]);
				$memberSearch = " AND user_master.user_doj = '".$yesterday."'";
				$downloadSearch  = " AND download_date = '".$yesterday."'";
				$subscribeSearch = " AND subscribed_date = '".$yesterday."'";
				break;
				
			case 'last7':
				$sevenDayBeforeDetails = getdate(strtotime('-7 days'));
				$requiredDate		   = date('Y-m-d',$sevenDayBeforeDetails[0]);	
				$memberSearch = " AND user_master.user_doj BETWEEN '".$requiredDate."' AND '".$today."'";
				$downloadSearch  = " AND download_date BETWEEN '".$requiredDate."' AND '".$today."'";
				$subscribeSearch = " AND subscribed_date BETWEEN '".$requiredDate."' AND '".$today."'";
				break;
			
			case 'thismonth':	
				$thisMonth = $todayDetails['mon'];
				
				$memberSearch 	 = " AND MONTH(user_master.user_doj) = '".$thisMonth."'";
				$downloadSearch  = " AND MONTH(download_date) = '".$thisMonth."'";
				$subscribeSearch = " AND MONTH(subscribed_date) = '".$thisMonth."'";
				break;
				
			case 'lastmonth':	
				$lastMonthDetails = getdate(strtotime('last month'));
				$lasMonth		  = $lastMonthDetails['mon'];
				
				$memberSearch 	 = " AND MONTH(user_master.user_doj) = '".$lasMonth."'";
				$downloadSearch  = " AND MONTH(download_date) = '".$lasMonth."'";
				$subscribeSearch = " AND MONTH(subscribed_date) = '".$lasMonth."'";
				break;
				
			case 'alltime':	
				$memberSearch 	 = "";
				$downloadSearch  = "";
				$subscribeSearch = "";
				break;
		}
	
	} elseif($_POST['daterange'] == 2) { // No the second drop down was selected... :)
		$startDate = $_POST['frY'].'-'.$_POST['frM'].'-'.$_POST['frD'];
		$endDate   = $_POST['toY'].'-'.$_POST['toM'].'-'.$_POST['toD'];
		
		if($startDate > $endDate)
			$errorMsg[] = "Start date should be smaller than end date";
		
		if(count($errorMsg) == 0){
			if($startDate != $endDate){
				$memberSearch 	 = " AND user_master.user_doj BETWEEN '".$startDate."' AND '".$endDate."'";
				$downloadSearch  = " AND download_date BETWEEN '".$startDate."' AND '".$endDate."'";
				$subscribeSearch = " AND subscribed_date BETWEEN '".$startDate."' AND '".$endDate."'";
			}else{ // Then we don't need a BETWEEN clause :)
				$memberSearch = " AND user_master.user_doj = '".$startDate."'";
				$downloadSearch  = " AND download_date = '".$startDate."'";
				$subscribeSearch = " AND subscribed_date = '".$startDate."'";
			}			
		}
	}
	
	if(count($errorMsg) == 0){
		
		//*************************** Countrywice report for the users starts here ****************************
		$sql = "SELECT COUNT( user_country ) AS countCon , user_country,countries_name FROM user_master,countries   WHERE user_type = 1 AND countries.countries_id = user_country ".$memberSearch." GROUP BY user_country  ORDER BY COUNT( user_country ) DESC";
		$result=$objDb->_getList($sql);
		
		$totalRecs = $objDb->_isExist($sql);
		if($totalRecs <= 0)
			$errMsg = "No Records";
			
		//*************************** Countrywice report for the users Ends  here *****************************	
		//*************************** Following code for the downloading section starts here  *****************
			
		$sqlDownload = "SELECT programmaster_id, program_manager.program_name, count( report_download_id ) AS COUNT_PRM FROM report_download, program_manager WHERE download_type = 'DOWNLOAD' AND program_manager.programmaster_id = report_download.program_id AND program_manager.language_id =".$lanId." ".$downloadSearch." GROUP BY ( report_download	.program_id ) ORDER BY COUNT_PRM DESC";
		$result2=$objDb->_getList($sqlDownload);
		
		
		$totalRecsDownload = $objDb->_isExist($sqlDownload);
		if($totalRecsDownload <= 0)
			$errMsg2 = "No Records";
			
		//*************************** Following code for the downloading section Ends  here  *****************	
			
		//**************************** for generating the report for the podcast  *******************************
		
		$sqlPodcast = "SELECT programmaster_id, program_manager.program_name, count( report_download_id ) AS COUNT_PRM FROM report_download, program_manager WHERE download_type = 'PODCAST' AND program_manager.programmaster_id = report_download.program_id AND program_manager.language_id =
		".$lanId." ".$downloadSearch." GROUP BY ( report_download	.program_id ) ORDER BY COUNT_PRM DESC";
		$result3=$objDb->_getList($sqlPodcast);
		
		
		$totalRecsDownload = $objDb->_isExist($sqlPodcast);
		
		if($totalRecsDownload <= 0)
			$errMsg3 = "No Records";			
		
		//**************************** for generating the report for the podcast Ends Here *******************************
		
		//**************************** for generating the report for the Subscription  *******************************
		
		/*$sqlSubscribe = "SELECT programmaster_id, program_manager.program_name, count( programs_subscribed_id ) AS COUNT_PRM FROM programs_subscribed, program_manager WHERE  program_manager.programmaster_id = programs_subscribed.program_id AND program_manager.language_id =".$lanId." ".$subscribeSearch." GROUP BY ( programs_subscribed.program_id ) ORDER BY COUNT_PRM DESC";*/
		
		$sqlSubscribe = "SELECT program_detail.program_master_id , program_detail.program_title, count( programs_subscribed_id )  AS COUNT_PRM FROM programs_subscribed, program_detail WHERE  program_detail.program_master_id = programs_subscribed.program_id AND program_detail.language_id =".$lanId." ".$subscribeSearch." GROUP BY ( programs_subscribed.program_id ) ORDER BY COUNT_PRM DESC";
		
		$result4=$objDb->_getList($sqlSubscribe);
		
		$totalRecsDownload = $objDb->_isExist($sqlSubscribe);
		if($totalRecsDownload <= 0)
			$errMsg4 = "No Records";			
		
		//**************************** for generating the report for the Subscription ENds Here *************************
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
</script>
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
                       
			   <form name="reportFrm" action="report_download.php" method="post">
                        
				  <table width=553 height="158" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
				  <tr>
						<td height="4" colspan="4" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
				  <tr>
				    
				    <td colspan="2" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;					</td>
				    <td width="143" height="27" align="center" valign="bottom" class="sectionHeading">&nbsp;
					
			<? if($_POST['image_x']){?>
					
					<? if($_POST['daterange'] == 1){?>
						<a href="excel.php?daterange=1&dropdown1=<?=$_POST['dropdown1']?>">
					<? }elseif($_POST['daterange'] == 2){
					?>
						<a href="excel.php?daterange=2&startdate=<?=$startDate?>&enddate=<?=$endDate?>">
					<? }else{?>
					<a href="excel.php?daterange=0">
					<? }?>
					<img src="../images/sports/english/download.gif" style="float:right" border="0"></a>
					
			<? }?>					</td>
				  </tr>
				  
				  <tr style="padding-top:10px;">
				    
				    <td width="62" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="348" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="143" height="27" align="center" valign="bottom" class="sectionHeading">&nbsp;</td>
				  </tr>
				  <tr style="padding-top:10px;">
				    
				    <td width="62" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="348" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
				    <td width="143" height="31" align="center" valign="bottom" class="sectionHeading"><input name="image" type="image"  style="float:right;"  onClick="this.form.submit" src="../images/generate.jpg"></td>
				  </tr>
				  <tr >
					  <td  >&nbsp;Language</td>
					  <td colspan="2"  >:
					    <select name="langId" class="paragraph" onChange="reportFrm.submit();">
                            <?php
										while(list($key,$val) = each($languageArray)){
								?>
                            <option value="<?=$key;?>" <? if($key==$lanId) echo 'selected';?>>
                            <?=$val;?>
                            </option>
                            <?php
										}
								?>
                          </select>
                      </td>
					  </tr>
				  <tr>
				    
				    <td colspan="4"></td>
				    </tr>
					<tr> <td height="19" colspan="4" align="left" class="successAlert">Choose date range </td> 
					</tr>
					
					<tr>
					<?
					// Compute this month and last month
						$today = getdate();
						
						$thisMonth = $today['month'];
						
						$last = getdate(strtotime('last month'));
						
						$lastMonth = $last['month'];
						
						$dropDownArray = array('today' => 'Today','yest' => 'Yesterday','last7' => 'Last 7 days','thismonth' => 'This month - '.$thisMonth,'lastmonth' => 'Last month - '.$lastMonth,'alltime' => 'Alltime');
						
					?>
					  <td height="18" colspan="4" align="left" class="successAlert">
					    <input name="daterange" type="radio" value="1" <? if($_POST['daterange'] ==1 or $_POST['daterange'] == '') echo 'checked="checked"';?>>
					    <select name="dropdown1">
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
					 <!--     <option value="today">Today</option>
					      <option value="yest">Yesterday</option>
					      <option value="last7">Last 7 days</option>
					      <option value="thismonth">This month-<?=$thisMonth?></option>
					      <option value="lastmonth">Last month-<?=$lastMonth?></option>
					      <option value="alltime">Alltime</option>-->
					      </select>					  </td>
					  </tr>
					<tr>
					  <td height="18" colspan="4" align="left" class="successAlert"><input name="daterange" type="radio" value="2" <? if($_POST['daterange'] == 2) echo 'checked="checked"';?>>
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
						
                        </select></td>
					  </tr>
				  </table>
                     
				   <table width=553 border=0 cellpadding=0 cellspacing=0 class="paragraph2">
				      <?php 
						if(count($errorMsg) > 0){
					?>
				      <tr>
				        <td colspan="4" align="center"  class="successAlert"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					      </tr>
				      <?php } ?>
				    <tr>
				        <td colspan="4" align="center"  class="successAlert">&nbsp;</td>
					</tr>
				    </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
					<TR class="tableHeaderColor">
                        <TD  align="center"  colspan="4"><span class="sectionHeading">Member Report</span></TD>
                        
                      </TR>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="51%" align="center" >Country</TD>
                        <TD width="24%" align="center" >Total Members </TD>
                        <TD width="19%" align="center" >Percentage</TD>
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
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><? echo $val['countries_name'];?></TD>
                              <TD width="25%" height="19" align="center"><? echo $val['countCon']?></TD>
                              <TD width="18%" height="19" align="center"><? if($totalCount>0)
		 $percent = round((($val['countCon']/$totalCount) * 100),2)." %";
		else 
		 $percent = '0.00 %';
		 echo $percent;?></TD>
                            </tr>
							
                            <? }?>
							<tr   >
                              <TD width="6%" height="19" align="center">&nbsp;</TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><strong>Total</strong></TD>
                              <TD width="25%" height="19" align="center"><strong><?=$totalCount?></strong></TD>
                              <TD width="18%" height="19" align="center"><strong>100 % </strong></TD>
                            </tr>
                        </table></TD>
                      </tr>
                      <? }?>
                    </tbody>
                  </table>
				  </br>
				  <!--<TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD colspan="4" align="center" ><span class="sectionHeading">Downloads</span> </TD>
                        </TR>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="51%" align="center" >Program </TD>
                        <TD width="24%" align="center" >No Of Downloads </TD>
                        <TD width="19%" align="center" >Percentage</TD>
                      </TR>
                      <?php if($errMsg2 != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="4" ><font color="#FF0000">
                          <?=$errMsg2?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result2)>0){
					            $totalCount = 0;
					            foreach($result2 as $key =>$val){
								$totalCount += $val['COUNT_PRM'];
								}
					  ?>
                      <tr class="listingTable">
                        <TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <? foreach($result2 as $key =>$val){  ?>
                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $key+1;?></TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><? echo $val['program_name'];?></TD>
                              <TD width="25%" height="19" align="center" ><? echo $val['COUNT_PRM']?></TD>
                              <TD width="18%" height="19" align="center" ><? if($totalCount>0)
		 $percent = round((($val['COUNT_PRM']/$totalCount) * 100),2)." %";
		else 
		 $percent = '0.00 %';
		 echo $percent;?></TD>
                            </tr>
                            <? }?>
							<tr   >
                              <TD width="6%" height="19" align="center">&nbsp;</TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><strong>Total</strong></TD>
                              <TD width="25%" height="19" align="center" ><strong><?=$totalCount?></strong></TD>
                              <TD width="18%" height="19" align="center"><strong>100 % </strong></TD>
                            </tr>
                        </table></TD>
                      </tr>
                      <? }?>
                    </tbody>
                  </table>-->
				  </br>
				  <!--<TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD colspan="4" align="center" ><span class="sectionHeading">Podcast</span> </TD>
                        </TR>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="51%" align="center" >Program </TD>
                        <TD width="24%" align="center" >No Of Podcast </TD>
                        <TD width="19%" align="center" >Percentage</TD>
                      </TR>
                      <?php if($errMsg3 != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="4" ><font color="#FF0000">
                          <?=$errMsg3?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result3)>0){
					            $totalCount = 0;
					            foreach($result3 as $key =>$val){
								$totalCount += $val['COUNT_PRM'];
								}
					  ?>
                      <tr class="listingTable">
                        <TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <? foreach($result3 as $key =>$val){ 
												
							 ?>
                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $key+1;?></TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><? echo $val['program_name'];?></TD>
                              <TD width="25%" height="19" align="center"><? echo $val['COUNT_PRM']?></TD>
                              <TD width="18%" height="19" align="center"><? if($totalCount>0)
		 $percent = round((($val['COUNT_PRM']/$totalCount) * 100),2)." %";
		else 
		 $percent = '0.00 %';
		 echo $percent;?></TD>
                            </tr>
                            <? }?>
							<tr   >
                              <TD width="6%" height="19" align="center">&nbsp;</TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><strong>Total</strong></TD>
                              <TD width="25%" height="19" align="center"><strong><?=$totalCount?></strong></TD>
                              <TD width="18%" height="19" align="center"><strong>100 % </strong></TD>
                            </tr>
                        </table></TD>
                      </tr>
                      <? }?>
                    </tbody>
                  </table>-->
				  </br>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
                    <TBODY>
                      <TR class="tableHeaderColor">
                        <TD colspan="4" align="center" ><span class="sectionHeading">Subscription</span> </TD>
                        </TR>
                      <TR class="tableHeaderColor">
                        <TD width="6%" align="center" >#</TD>
                        <TD width="51%" align="center" >Program </TD>
                        <TD width="24%" align="center" >No Of Subscription </TD>
                        <TD width="19%" align="center" >Percentage</TD>
                      </TR>
                      <?php if($errMsg4 != ""){?>
                      <TR class="listingTable">
                        <TD align="center" colspan="4" ><font color="#FF0000">
                          <?=$errMsg4?>
                        </font> </TD>
                      </TR>
                      <? }?>
                      <? if(count($result4)>0){
					            $totalCount = 0;
					            foreach($result4 as $key =>$val){
								$totalCount += $val['COUNT_PRM'];
								}
					  ?>
                      <tr class="listingTable">
                        <TD align="center" colspan="4" class="tblbackgnd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <? foreach($result4 as $key =>$val){ 
												
							 ?>
                            <tr  <? if(($key%2) ==1){?> bgcolor="#FFFFFF" <? } ?> >
                              <TD width="6%" height="19" align="center"><? echo $key+1;?></TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><? echo stripslashes($val['program_title']);?></TD>
                              <TD width="25%" height="19" align="center"><? echo $val['COUNT_PRM']?></TD>
                              <TD width="18%" height="19" align="center"><? if($totalCount>0)
		 $percent = round((($val['COUNT_PRM']/$totalCount) * 100),2)." %";
		else 
		 $percent = '0.00 %';
		 echo $percent;?></TD>
                            </tr>
                            <? }?>
							<tr   >
                              <TD width="6%" height="19" align="center">&nbsp;</TD>
                              <TD width="51%" height="19" align="left" style="padding-left:10px;"><strong>Total</strong></TD>
                              <TD width="25%" height="19" align="center"><strong><?=$totalCount?></strong></TD>
                              <TD width="18%" height="19" align="center"><strong>100 % </strong></TD>
                            </tr>
                        </table></TD>
                      </tr>
                      <? }?>
                    </tbody>
                  </table>
				  <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
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
        <?php include_once("footer.php");?>
</body>
</html>