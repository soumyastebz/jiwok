<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::>Jiwok-Report
   Programmer	::> Deepa S 
   Date			::> 27/Jan/2011
   DESCRIPTION::::>>>> Jiwok Reports section. This index page  displays the report summary of all sections  - All users, Register, Subscriber, Ex-subscriber,1 euro transactions, gift code transactions
  
*****************************************************************************/
error_reporting(1);
session_start();
	include_once('includeconfig.php');
	include_once("includes/classes/class.report.php");
	$admin_title = "JIWOK REPORTS";
	
	$reportObj = new Report();
	
	$countryArray   = $reportObj->_getCountries();
	$brandArray		= $reportObj->getAllBrandName();
	$discArray      = $reportObj->getDiscountCodesUsed();
	
	$heading = "Duration Wise Subscriber Report";
	$todayDetails = getdate();
	$brand_master_id = $_SESSION['brand_master_id'];
	
	$brandCondn.= " AND b.brand_master_id=".$brand_master_id; 
	
	$_POST['report_criteria']  =9;
	$fileNameArray = array('1'=>'Query1.txt','2'=>'Query2.txt','3'=>'Query3.txt','4'=>'Query4.txt','5'=>'Query5.txt','6'=>'Query6.txt',
							  '7'=>'Query7.txt','8'=>'Query8.txt','9'=>'Query9.txt','10'=>'Query10.txt','11'=>'Query11.txt','12'=>'Query12.txt','13'=>'Query13.txt');
	
	
	$reportTitleArray =  array('1'=>'Subscription Reports','2'=>'Subscribers Origin Reports','3'=>'Subscribers Reports : Plan Wise','4'=>'Subscribers Doing Test : 1 Euro or First Workout','5'=>'Subscriber Reports Using Gift Code','6'=>'1 Euro Transaction Only',
							  '7'=>'1 Euro to 7.9 Euro Next Month','8'=>'Average Of Month Subscribing','9'=>'Duration Wise Subscriber Report','10'=>'Training Program Most Subscribed','11'=>'New Subscriber/New Register Reports','12'=>'Existing Subscriber/Existing Register Reports','13'=>'Revenue Reports');
	$reportNameArray =  array('1'=>'Subscription-Reports','2'=>'Origin-Reports','3'=>'Plan-Wise','4'=>'1Euro-FirstWorkout','5'=>'Gift-Code','6'=>'1Euro-Trans',
							  '7'=>'1Euro-7.9','8'=>'Average-Subscribed','9'=>'Paid-Subscribers','10'=>'Training-Program','11'=>'New-Subscriber-Register','12'=>'Existing-Subscriber-Register','13'=>'Revenue-Reports');
	
	
	if($_POST['report_criteria'])
	{
	include("condition.php");
	if($_POST['num_months']!="")
	{
		$monthCondn = " AND num_months='".trim($_POST['num_months'])."'";
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
	$reportQuery = sprintf($reportQuery,$monthCondn,$countryCondn,$genderCondn,$originCondn,$discCondn,$ageCondn1,$ageCondn2,$brandCondn,$typeCondn,$programCondn,$languageCondn);
	$reportQuery = "SELECT Brand,num_months,SUM(Count) AS Count FROM(".$reportQuery.") AS t5 GROUP BY t5.num_months ORDER BY t5.Brand";
	$qry = trim($reportQuery);
	$result = mysql_query(trim($qry)) or die(mysql_error());
	
	//echo $reportQuery; die;
	$fields = mysql_num_fields($result);
	
	$totalRows = mysql_num_rows($result);
	
	$data = "data.addColumn('string', 'Task');data.addColumn('number', 'Hours per Day');data.addRows(".$totalRows.");";
	$i = 0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC))
	{
		$data .= "data.setValue(".$i.", 0, '".$row['num_months']." Months'); data.setValue(".$i.", 1, ".$row['Count'].");";
		$i++;
	}
	
	}


?>
	
<HTML><HEAD>
<TITLE>JIWOK REPORTS</TITLE>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
        var data = new google.visualization.DataTable();
       <?php echo $data;?>
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 775, height: 625});
      }
 
</script>
<? include_once('javascript.php');?>
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
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Durationwise Subscriber Reports</td>
                                 </tr>                                     
                                    <TR> 
                                      <TD valign='top' bgColor='white'>
                                      <form name="reportFrm" action="report_monthwise_chart.php" method="post" enctype="multipart/form-data">
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
                                          <!--<span style="vertical-align:top">
                                           <select id="country_select" name="country_select" style="font-size:11px;" onChange="javascript:selectCountry(this.value);">
                                            <option value="" <?php if($_REQUEST['country_select']=='') {?> selected <?php }?>>All</option>
                                            <option value="1" <?php if($_REQUEST['country_select']=='1') {?> selected <?php }?>>Select</option>
                                          </select></span>-->
                                          <span id="countrydiv"><select id="user_country" name="user_country" style="font-size:11px; width:212px;">
                                            <? 
                                while(list($code,$name) = each($countryArray)){
                                    $string = "<option value={$code}";									
    									if($code == $cntry){
                                        $string .= " selected";                                   
										}                                    
                                    $string .= ">{$name}</option>";
                                    print $string;
                                }
                           ?>
                                          </select>
                                          </span>
                                          </td>
                                          <td width="32" align="left" valign="top" style="padding-left:10px;"><strong>Age</strong></td>
                                          <td width="266" valign="top" ><input type="text" name="user_fromage" id="user_fromage" size="5" value="<?=trim($_POST['user_fromage'])?>" />&nbsp;- &nbsp;<input type="text" name="user_toage" id="user_toage" size="5" value="<?=trim($_POST['user_toage'])?>"/></td>
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
										  <!--<select id="user_origin" name="user_origin" style="font-size:11px;" >
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
										  <!-- <select id="user_program" name="user_program" style="font-size:11px;" >
                                          	<option value="" <?php if($_POST['user_program']=='') {?> selected <?php }?>>--Select--</option>
                                            <option value="1" <?php if($_POST['user_program']=='1') {?> selected <?php }?>>Yes</option>
                                            <option value="0" <?php if($_POST['user_program']=='0') {?> selected <?php }?>>No</option>
                                          </select>--></td>
                                         
                                        </tr>
                                       
                                        <tr >
                                        <td width="182" style="padding-left:20px; padding-top:30px;"><strong>Select No. Of Months</strong></td>
                                         
                                                  <td colspan='3' style="padding-top:30px;">
                                                 
                                                  <select name="num_months" style="font-size:11px;">
                                                  <option value="" <?php if($_POST['num_months'] == "") { ?>selected<?php }?>>Select</option>
                                                    <?
                                                $str = '';
                                                for($i=1;$i<=12;$i++){
                                                    $str .= '<option value="'.$i.'"';
                                                     if($_POST['num_months'] == $i)
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
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Google Chart</td>
                                        </tr>
                                      
                                      </tbody>
                                  </table><br/>   <?php if($totalRows>0) { ?> 
                                  <div id="chart_div"></div>
                                 <?php }
								 else
								 {
								 ?>
                                 <TABLE cellSpacing=1 cellPadding=2 width="100%">
                                    <TBODY>
                                    <tr><td><strong><center>No Records Found to Draw Chart</center></strong></td></tr>
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
include("functioncall.php");
?>
