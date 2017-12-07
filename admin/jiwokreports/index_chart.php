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
	
		
	$heading = "JIWOK REPORTS";
	
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
                                          <td height="6" colspan="4" align="center" valign="middle"  class="sectionHeading" style="font-size:16px; padding:5px;">JIWOK GRAPHICAL REPORTS</td>
                                 </tr>
                                  
                                    <TR> 
                                      <TD valign='top' bgColor='white'>
                                      
                                      <table width=100% height="156" border=0 cellpadding=0 cellspacing=0 class="paragraph2">
                                        
                                       
                                        
                                       
                                       
                                       
                                        <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:20px;">&nbsp;<strong><a href="report_subscription_chart.php" style="text-decoration:underline; color:#09F;">Subscription Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                         
                              <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_origin_chart.php" style="text-decoration:underline; color:#09F;">Subscribers Origin Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                           <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_planwise_chart.php" style="text-decoration:underline; color:#09F;">Subscribers Reports : Plan Wise</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                         <!--<tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_testplan_chart.php" style="text-decoration:underline; color:#09F;">Subscribers Doing Test : 1 Euro or First Workout</a></strong>&nbsp;&nbsp;</td>
                                        </tr>-->
                                        <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_gifcode_chart.php" style="text-decoration:underline; color:#09F;">Subscriber Reports Using Gift Code</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                         <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_oneeuro_chart.php" style="text-decoration:underline; color:#09F;">1 Euro Transaction Only</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                          <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_paymentchange_chart.php" style="text-decoration:underline; color:#09F;">1 Euro to 7.9 Euro Next Month</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                         <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_avg_month_sub_chart.php" style="text-decoration:underline; color:#09F;">Average Of Month Subscribing</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_monthwise_chart.php" style="text-decoration:underline; color:#09F;">Duration Wise Subscriber Report</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_training_chart.php" style="text-decoration:underline; color:#09F;">Training Program Most Subscribed</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                         <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_register_chart.php" style="text-decoration:underline; color:#09F;">New Register/Existing Register Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_new_ext_subscriber_chart.php" style="text-decoration:underline; color:#09F;">New Subscriber/Existing Subscriber Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>
                                          <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_revenue_chart.php" style="text-decoration:underline; color:#09F;">Revenue Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>   
                                       <tr >
                                          <td colspan="8" align="left" valign="middle" style="padding-left:20px;padding-top:15px;">&nbsp;<strong><a href="report_oxylane_chart.php" style="text-decoration:underline; color:#09F;">Oxylane Reports</a></strong>&nbsp;&nbsp;</td>
                                        </tr>     
                                       
                                                                                
                                      
                                      </table>
                                    
                                        <br/>
                                        
                               
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


