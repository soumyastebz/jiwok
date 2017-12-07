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
                                          <td height="6" colspan="4" align="center" valign="bottom" class="sectionHeading" style="font-size:16px; padding-top:10px;">Subscriber Reports</td>
                                        </tr>
                                        <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" class="sectionHeading" style="padding-right:20px;" ><font style="font-size:14px;background-color:#09F; color:#FFF; padding:3px;">EXPORT TO CSV</font></td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td  colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                        <tr style="padding-top:10px;">
                                          <td height="21" colspan="6" align="left" valign="bottom" class="sectionHeading" style="padding-left:10px;">&nbsp;</td>
                                        </tr>
                                                                                
                                     <tr>
                                          <td height="4" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:13px; color:#36C; padding-left:20px; padding-top:20px; padding-bottom:20px;"><strong>Filter By Parameters</strong></td>
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
                                    <tr>
                                          <td height="6" colspan="4" align="left" valign="bottom" class="sectionHeading" style="font-size:14px; padding-top:10px;">Subscriber Search Results </td>
                                        </tr>
                                      
                                      </tbody>
                                  </table><br/>   
                                  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="100%" border="0">
                                 
                                      <TR class="tableHeaderColor" >
                                        <TD width="21%" align="center" >Brand</TD>
                                        <TD width="21%" align="center" >Country</TD>
                                        <TD width="21%" align="center" >Status</TD>
                                        <TD width="21%" align="center" >Origin</TD>
                                        <TD width="21%" align="center" >Count</TD>
                                       </TR>
                                     
                                                          
                        			   <tr class="listingTable1">
                                  	   <TD height="19" align="left" style="padding-left:2px;">Jiwok</TD>
                                       <TD height="19" align="left" style="padding-left:2px;">France</TD>
                                       <TD height="19" align="left" style="padding-left:2px;">paid</TD>
                                       <TD height="19" align="left" style="padding-left:2px;">free work out</TD>
                                       <TD height="19" align="left" style="padding-left:2px;">1000</TD>
                                       
                                      </tr>
                                     
                                  </table>
                                 
                                  <table cellspacing=0 cellpadding=0 width='800' border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="right" colspan = "8" class="leftmenu">Space for pagination</td>
						
					</tr>
				   </tbody>
			 	</table>     
               
                                             
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
