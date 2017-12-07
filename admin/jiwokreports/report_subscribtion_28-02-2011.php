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
	//include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	
	
	$heading = "Subscriber Reports";
	$todayDetails = getdate();
	
	if($_POST['report_criteria'])
	{
		
	$frM = $_POST['frM'];
	$frY = $_POST['frY'];
	$toM = $_POST['toM'];
	$toY = $_POST['toY'];
	
	if($toM=='12')
	{
	$toM = '01';
	$toY = $toY+1;
	}
	else
	$toM  = $toM+1;
			
	$myFile = trim($_POST['report_criteria']);
	$fh = fopen($myFile, 'r');
	$reportQuery = fread($fh, filesize($myFile));
	fclose($fh);
	/* for to range, month will be taken as selected month+1. For example, if start range is 03-2001 and end range is  04-2001,
	then it is taken as 01-03-2001 and 01-05-2001.
	*/
	$reportQuery = sprintf($reportQuery,$frM,$frY,$toM,$toY);
	$result = mysql_query($reportQuery) or die(mysql_error());
	$fields = mysql_num_fields($result);
	$totalRows = mysql_num_rows($result);
	
	
	}

	?>	

<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>

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
              
              <form name="reportFrm" action="report_subscribtion.php" method="post" enctype="multipart/form-data">
                <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
                  <TR> 
                    <TD class=smalltext width="98%" valign="top">
                    
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
                        <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                        <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                        <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                      </tr>s
                          <tr> 
                            <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                            <td valign="top"> 
                            
                            
                            
                                <TABLE cellSpacing=0 cellPadding=0 border=0 align="center" width="100%">
                                    <TR> 
                                      <TD valign='top' bgColor='white'><table width=100% height="227" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        <tr>
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscription Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" class="sectionHeading" style="padding-right:20px;" ><font style="font-size:14px;background-color:#09F; color:#FFF; padding:3px;">EXPORT TO CSV</font></td>
                                        </tr>
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
                                           <select id="country_select" name="country_select" style="font-size:11px;" disabled >
                                            <option value="" <?php if($_REQUEST['country_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['country_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                         
                                          </td>
                                          <td width="203" align="left" valign="top" style="padding-left:10px;"><strong>Age</strong></td>
                                          <td valign="top" ><input type="text" name="user_fromage" id="user_fromage" size="5" value="<?=trim($_POST['user_fromage'])?>"  disabled/>&nbsp;- &nbsp;<input type="text" name="user_toage" id="user_toage" size="5" value="<?=trim($_POST['user_toage'])?>" disabled/></td>
                                        </tr>
                                        <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Gender</strong></td>
                                          <td style="padding-top:10px;"><select id="user_gender[]" name="user_gender[]" style="font-size:11px;" multiple disabled>
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
                                           <select id="language_select" name="language_select" style="font-size:11px;" disabled >
                                           <option value="" <?php if($_REQUEST['language_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['language_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>
                                         </td>
                                        </tr>
                                         <tr >
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Origin</strong></td>
                                          <td style="padding-top:10px;"><select id="user_origin" name="user_origin" style="font-size:11px;" disabled>
                                          <option value="" <?php if(trim($_POST['user_origin'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="FREE WORK" <?php if(trim($_POST['user_origin'])=="FREE WORK"){ echo 'selected'; } ?>>First Workout Free Try</option>
                                          <option value="DISCOUNT" <?php if(trim($_POST['user_origin'])=="DISCOUNT"){ echo 'selected'; } ?>>1 Euro Discount</option>
                                   		  <option value="GIFTCODE" <?php if(trim($_POST['user_origin'])=="GIFTCODE"){ echo 'selected'; } ?>>By Gift Code</option>
                                          <option value="NORMAL" <?php if(trim($_POST['user_origin'])=="NORMAL"){ echo 'selected'; } ?>>7.90 Euro Transaction</option>
                                          </select></td>
                                          <td align="left" valign="middle" style="padding-left:10px;padding-top:10px;"><strong>Discount Code</strong></td>
                                           <td style="padding-top:10px;"><input type="text" name="code" id="code" size="5" value="<?=trim($_POST['code']);?>" /></td>
                                        </tr>
                                        <tr>
                                          <td width="174" align="left" valign="top" style="padding-left:20px;padding-top:10px;">&nbsp;<strong>Brand</strong>&nbsp;&nbsp;</td>
                                          <td width="292"  style="padding-top:10px;" valign="top">
                                          <span style="vertical-align:top"><select id="brand_select" name="brand_select" style="font-size:11px;"  disabled>
                                            <option value="" <?php if($_REQUEST['brand_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['brand_select']=='1') {?> selected <?php }?>>Select</option>
                                               </select></span>
                                               </td>
                                          
                                        </tr>
                                        <tr>
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Program Yes/No</strong></td>
                                          <td style="padding-top:10px;"><select id="user_program" name="user_program" style="font-size:11px;" disabled>
                                          	<option value="" <?php if($_POST['user_program']=='') {?> selected <?php }?>>--Select--</option>
                                            <option value="Yes" <?php if($_POST['user_program']=='Yes') {?> selected <?php }?>>Yes</option>
                                            <option value="No" <?php if($_POST['user_program']=='No') {?> selected <?php }?>>No</option>
                                          </select></td>
                                          <td align="left" valign="top" style="padding-left:10px; padding-top:10px;"><strong>Subscriber Type</strong></td>
                                         <td width="238"  style="padding-top:10px;" valign="top"><select id="user_type" name="user_type" style="font-size:11px;" disabled>
                                          	<option value="" <?php if($_POST['user_type']=='') {?> selected <?php }?>>All</option>
                                            <option value="free" <?php if($_POST['user_type']=='free') {?> selected <?php }?>>Free Subscribers</option>
                                            <option value="paid" <?php if($_POST['user_type']=='paid') {?> selected <?php }?>>Paid Subscribers</option>
                                            <option value="new" <?php if($_POST['user_type']=='new') {?> selected <?php }?>>New Subscribers</option>
                                            <option value="permanent" <?php if($_POST['user_type']=='permanent') {?> selected <?php }?>>Permanent Subscribers</option>
                                          </select></td>
                                        </tr>
                                       <tr><td colspan="8" height="30">&nbsp;</td>
                                       </tr> 
                                        
                                        <tr >
                                        <td width="174" style="padding-left:20px; padding-top:30px;"><strong>Filter Date</strong></td>
                                         
                                                  <td style="padding-top:30px;" colspan='4'>
                                                 
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
                                                for($i=$todayDetails['year']-6;$i<=$todayDetails['year'];$i++){
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
                                                for($i=$todayDetails['year']-6;$i<=$todayDetails['year'];$i++){
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
                                               </td>
                                        </tr>
                                                                                
                                     <tr>
                                          <td height="4" colspan="8" align="left" valign="bottom" class="sectionHeading" style="font-size:13px; color:#36C; padding-left:20px; padding-top:20px;"><strong>Report Criteria</strong>&nbsp;&nbsp;<select id="report_criteria" name="report_criteria" style="font-size:12px;">
                                          <option value="Query1.txt" <?php if($_POST['report_criteria']=='Query1.txt') echo 'selected';?>>Subscribers Free and Paid</option>
                                          <option value="Query2.txt" <?php if($_POST['report_criteria']=='Query2.txt') echo 'selected';?>>Subscribers Origin Reports</option>
                                          <option value="3">Report on Subscribers Status: Plan Wise</option>
                                   		  <option value="4">Report on Subscribers  doing a Test: 1Eur or First Workout </option>
                                          <option value="5">Report on Subscribers using a Gift Code</option>
                                          <option value="6">1 Euro transaction only</option>
                                          <option value="7">Report on 1Euro to 7.9Euro next month</option>
                                          <option value="8">Report on Avg. Monthly Subscriptions</option>
                                          <option value="9">Report on Duration (Monthly) Wise Sub (paid)</option>
                                          <option value="10">Report on Training Program Ranking</option>
                                          <option value="11">Report on New Subscriber/ New Registers</option>
                                          <option value="12">Report on Exisintg Subscriber/ Exisintg Registers</option>
                                          <option value="13">Report on Total Revenue</option>
                                           </select>
                                           <input type="submit" name="filter" value="Show Reports"/>
                                           </td>
                                        
                                        </tr>   
                                       
                                        <tr>
                                          <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                          <td height="2" colspan="4" align="left"></td>
                                        </tr>
                                      </table>
                                        <br/>
                                        <?php if($totalRows>0) { ?>
                                  <TABLE cellSpacing=1 cellPadding=2 width="553">
                                    <TBODY>
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Subscription Reports</td>
                                        </tr>
                                      
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                  
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
                                 <?php }?>
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
			
		?></BODY>
</HTML>
