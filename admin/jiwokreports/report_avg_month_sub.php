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
	
	$heading = "Average Of Month Subscribing";
	$todayDetails = getdate();	
	
	$_POST['report_criteria']  =8;
	if($_POST['grouping']!='')
	{
		 $grouping = trim($_POST['grouping']);
		if($grouping=='brand_name')
			$fileNameArray[8] = 'Query8.1.txt';
		if($grouping=='Country')
			$fileNameArray[8] = 'Query8.2.txt';
		if($grouping=='Gender')
			$fileNameArray[8] = 'Query8.3.txt';
		if($grouping=='Age')
			$fileNameArray[8] = 'Query8.4.txt';
		if($grouping=='origin')
			$fileNameArray[8] = 'Query8.5.txt';	
	
	}
	else
	{
	$fileNameArray = array('1'=>'Query1.txt','2'=>'Query2.txt','3'=>'Query3.txt','4'=>'Query4.txt','5'=>'Query5.txt','6'=>'Query6.txt',
							  '7'=>'Query7.txt','8'=>'Query8.txt','9'=>'Query9.txt','10'=>'Query10.txt','11'=>'Query11.txt','12'=>'Query12.txt','13'=>'Query13.txt');
	}
	if($_REQUEST['export_to']== 2)
		$fileNameArray[8] = 'QueryExport8.txt';
	$reportTitleArray =  array('1'=>'Subscription Reports','2'=>'Subscribers Origin Reports','3'=>'Subscribers Reports : Plan Wise','4'=>'Subscribers Doing Test : 1 Euro or First Workout','5'=>'Subscriber Reports Using Gift Code','6'=>'1 Euro Transaction Only',
							  '7'=>'1 Euro to 7.9 Euro Next Month','8'=>'Average Of Month Subscribing','9'=>'Paid Subscribers Reports','10'=>'Training Program Most Subscribed','11'=>'New Subscriber/New Register Reports','12'=>'Existing Subscriber/Existing Register Reports','13'=>'Revenue Reports');
	$reportNameArray =  array('1'=>'Subscription-Reports','2'=>'Origin-Reports','3'=>'Plan-Wise','4'=>'1Euro-FirstWorkout','5'=>'Gift-Code','6'=>'1Euro-Trans',
							  '7'=>'1Euro-7.9','8'=>'Average-Subscribed','9'=>'Paid-Subscribers','10'=>'Training-Program','11'=>'New-Subscriber-Register','12'=>'Existing-Subscriber-Register','13'=>'Revenue-Reports');
	
	
	if($_POST['report_criteria'])
	{
	include_once('condition.php');
	if($_POST['frY'])
	{
	$frY = $_POST['frY'];
	if($frY==date('Y'))
	{ $diff_value = date('m'); } else {$diff_value = 12; }
	}
	else
	{
		$frY = '2009';
		$diff_value = 12;
	}
			
	$myFile1 = trim($_POST['report_criteria']);
	$myFile = $fileNameArray[$myFile1];
	$fh = fopen($myFile, 'r');
	$reportQuery = fread($fh, filesize($myFile));
	fclose($fh);
	/* for to range, month will be taken as selected month+1. For example, if start range is 03-2001 and end range is  04-2001,
	then it is taken as 01-03-2001 and 01-05-2001.
	*/
	//$reportQuery = sprintf($reportQuery,$frM,$frY,$toM,$toY,$countryCondn);
	$reportQuery = sprintf($reportQuery,$diff_value,$frY,$countryCondn,$genderCondn,$originCondn,$discCondn,$ageCondn1,$ageCondn2,$brandCondn,$typeCondn,$programCondn,$languageCondn);	
	$qry = trim($reportQuery);	
	//echo $qry;
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
<? include_once('javascripts.php');?>
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
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Average Month Subscription Reports</td>
                                 </tr>
                                  <?php if($_POST['report_criteria']!='')  include_once('exportform.php');?>  
                                    <TR> 
                                      <TD valign='top' bgColor='white'>
                                      <form name="reportFrm" action="report_avg_month_sub.php" method="post" enctype="multipart/form-data">
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
                                          <td width="82" align="left" valign="top" style="padding-left:10px;"><strong>Age</strong></td>
                                          <td width="191" valign="top" ><input type="text" name="user_fromage" id="user_fromage" size="5" value="<?=trim($_POST['user_fromage'])?>" />&nbsp;- &nbsp;<input type="text" name="user_toage" id="user_toage" size="5" value="<?=trim($_POST['user_toage'])?>"/></td>
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
                                        <tr>
                                          <td width="113" align="left" valign="top" style="padding-left:20px;padding-top:10px;">&nbsp;<strong>Brand</strong>&nbsp;&nbsp;</td>
                                          <td width="257"  style="padding-top:10px;" valign="top">
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
                                          </select></span>											 <!-- <select id="user_origin" name="user_origin" style="font-size:11px;" >
                                          <option value="" <?php if(trim($_POST['user_origin'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="3" <?php if(trim($_POST['user_origin'])=="3"){ echo 'selected'; } ?>>First Workout Free Try</option>
                                          <option value="1" <?php if(trim($_POST['user_origin'])=="1"){ echo 'selected'; } ?>>1 Euro Discount</option>
                                   		  <option value="2" <?php if(trim($_POST['user_origin'])=="2"){ echo 'selected'; } ?>>Gift Code</option>
                                          <option value="4" <?php if(trim($_POST['user_origin'])=="4"){ echo 'selected'; } ?>>7.9/9.9 Transaction</option>
                                          </select>--></td><td width="161" align="left" valign="top" style="padding-left:10px;"><strong>Language</strong></td>
                                          <td valign="top" ><select id="user_language[]" name="user_language[]" style="font-size:11px;" multiple>
                                            <option value="1"
                                            
                                            <?php 
											
											foreach ($_POST['user_language'] as $value) {
    											if($value == '1'){
                                        		echo " selected";
                                    		} }
											?>

                                            >English</option>
                                            <option value="2"
                                            <?php foreach ($_POST['user_language'] as $value) {
    											if($value == '2'){
                                        		echo " selected";
                                    		} }
											?>
                                            >French</option>
                                          </select></td>
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
                                          <td align="left" valign="middle" style="padding-left:20px; padding-top:10px;">&nbsp;<strong>Program Yes/No</strong></td>
                                          <td style="padding-top:10px;">
										<span style="vertical-align:top">
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
                                         
                                        </tr>
                                       
                                        <tr >
                                        <td width="113" style="padding-left:20px; padding-top:30px;"><strong>Select Year</strong></td>
                                         
                                                  <td colspan='3' style="padding-top:30px;">
                                                 
                                                  <select name="frY" style="font-size:11px;">
                                                    <?
                                                $str = '';
                                                for($i=$todayDetails['year']-6;$i<=$todayDetails['year'];$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                    if($_POST['frY'] == "" and $i == '2009')
                                                        $str .= 'selected="selected"';
                                                    elseif($_POST['frY'] == $i)
                                                        $str .= 'selected="selected"';
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
                                  </table><br/>  <?php if($totalRows>0) { ?> 
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                   <? include_once('groupform.php');?>
                                  
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
<? include_once('functioncall.php');?>
