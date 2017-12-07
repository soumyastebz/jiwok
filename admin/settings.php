<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Site general Settings
   Programmer	::> Ajith
   Date			::> 29-12-2008
   
   DISCRIPTION::::>>>>
   This  code used to set diffrenet kinds of site values.
*****************************************************************************/
	include_once('includeconfig.php');
	
	$heading = "General Settings";
	$heading2 = "SMTP Settings";
	
	$errorMsg	=	array();
	$confirmMsg = '';
	
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	/*
	 Instantiating the classes.
	*/
	$objGen   =	new General();
	
	
	/* ***** The Following code will execute while any of the setting table value  update ********* */
	if($_POST['contactEmailUpdate']||$_POST['returnEmailUpdate']||$_POST['bounceEmailUpdate'] || $_POST['maxCountUpdate'] || $_POST['freeDaysUpdate'] || $_POST['memFee'] || $_POST['reusable_memFee'] || $_POST['reusable_memFeedollar'] || $_POST['memShipUnsubscribeUpdate'] || $_POST['dis_limit_update']){
			
			
			
			/* ***** Code for generate update query contact mail id in settings table ********* */
			if($_POST['contactEmailUpdate']){
			
				if(trim($_POST['contact_email'])=='')
					$errorMsg[]	=	"Contact email required";
				elseif(!$objGen->_validate_email($_POST['contact_email']))
					$errorMsg[]	=	"Contact email does not valid";
				else
					$updateSettings	=	"update settings set contact_email='".$objGen->_clean_data($_POST['contact_email'])."'";
			}
			
			/* ***** Code for generate update query return mail id in settings table ********* */
			if($_POST['returnEmailUpdate']){
			
				if(trim($_POST['return_email'])=='')
					$errorMsg[]	=	"Return email required";
				elseif(!$objGen->_validate_email($_POST['return_email']))
					$errorMsg[]	=	"Return email does not valid";
				else
					$updateSettings	=	"update settings set return_email='".$objGen->_clean_data($_POST['return_email'])."'";
			}
			/* ***** Code for generate update query bounce mail id in settings table ********* */
			if($_POST['bounceEmailUpdate']){
			
				if(trim($_POST['bounce_email'])=='')
					$errorMsg[]	=	"Bounce email required";
				elseif(!$objGen->_validate_email($_POST['bounce_email']))
					$errorMsg[]	=	"Bounce email does not valid";
				else
					$updateSettings	=	"update settings set bounce_email='".$objGen->_clean_data($_POST['bounce_email'])."'"; 
			}
            if($_POST['maxCountUpdate']){
            
                if(trim($_POST['program_max_count'])=='')
                    $errorMsg[] =   "Program subscription maximum number required";
                elseif(!is_numeric(trim($_POST['program_max_count'])))
                    $errorMsg[] =   "Enter a number for maximum program subscription";
                else
                    $updateSettings =   "update settings set program_max_count=".$objGen->_clean_data($_POST['program_max_count']);
            }
			if($_POST['freeDaysUpdate']){
            
                if(trim($_POST['freeDays'])=='')
                    $errorMsg[] =   "No. of free days required";
                elseif(!is_numeric(trim($_POST['freeDays'])))
                    $errorMsg[] =   "Enter a number for free days";
                else
                    $updateSettings =   "update settings set free_days=".$objGen->_clean_data($_POST['freeDays']);
            }
			
			if($_POST['memFeeUpdate']){
            
                if(trim($_POST['memFee'])=='')
                    $errorMsg[] =   "Membership Fee required";
                
                else
                   $updateSettings =   "update settings set membership_fee=".$objGen->_clean_data($_POST['memFee']); 
            }
			if($_POST['memFeeUpdatedollar']){
            
                if(trim($_POST['memFeedollar'])=='')
                    $errorMsg[] =   "Membership Fee required";
                
                else
                   $updateSettings =   "update settings set membership_feedollar=".$objGen->_clean_data($_POST['memFeedollar']); 
            }

			if($_POST['reusable_memFeeUpdate']){
            
                if(trim($_POST['reusable_memFee'])=='')
                    $errorMsg[] =   "Membership Fee required";
                
                else
                   $updateSettings =   "update settings set reusable_membership_fee=".$objGen->_clean_data($_POST['reusable_memFee']); 
            }
			if($_POST['reusable_memFeeUpdatedollar']){
            
                if(trim($_POST['reusable_memFeedollar'])=='')
                    $errorMsg[] =   "Membership Fee required";
                
                else
                   $updateSettings =   "update settings set reusable_membership_feedollar=".$objGen->_clean_data($_POST['reusable_memFeedollar']); 
            }
			
			if($_POST['memShipUnsubscribeUpdate']){
            
                if(trim($_POST['membership_unsubscribeperiod'])=='')
                    $errorMsg[] =   "Membership Unsubscribe Period required";
                
                else
                   $updateSettings =   "update settings set membership_unsubscribeperiod=".$objGen->_clean_data($_POST['membership_unsubscribeperiod']); 
            }
			
			if($_POST['dis_limit_update']){
            
                if(trim($_POST['dis_limit'])=='')
                    $errorMsg[] =   "Maximum period for a discount is required";
                elseif(!is_numeric(trim($_POST['dis_limit'])))
                    $errorMsg[] =   "Enter a number for Maximum discount period ";
                else
                   $updateSettings =   "update settings set dis_limit=".$objGen->_clean_data($_POST['dis_limit']); 
            }
			////////////SMTP DETAILS
			if($_POST['smtp_host_update']){
            
                if(trim($_POST['smtp_host'])=='')
                    $errorMsg[] =   "Enter SMTP Host Name";
                else
                   $updateSettings =   "update settings set smtp_host='".$objGen->_clean_data($_POST['smtp_host'])."'"; 
            }
			
			if($_POST['smtp_username_update']){
            
                if(trim($_POST['smtp_username'])=='')
                    $errorMsg[] =   "Enter SMTP User Name";
                else
                   $updateSettings =   "update settings set smtp_username='".$objGen->_clean_data($_POST['smtp_username'])."'"; 
            }
			
			if($_POST['smtp_password_update']){
			
            
                if(trim($_POST['smtp_password'])=='')
                    $errorMsg[] =   "Enter SMTP Password";
                else
                 $updateSettings =   "update settings set smtp_password='".$objGen->_clean_data($_POST['smtp_password'])."'"; 
				  
            }
			
			if($_POST['smtp_port_update']){
            
                if(trim($_POST['smtp_port'])=='')
                    $errorMsg[] =   "Enter SMTP Port";
             elseif(!is_numeric(trim($_POST['smtp_port'])))
                    $errorMsg[] =   "Enter a number for SMTP port ";
                else
                   $updateSettings =   "update settings set smtp_port='".$objGen->_clean_data($_POST['smtp_port'])."'"; 
            }
			
			/* ***** Code for execute update query ********* */
			if(count($errorMsg)==0){
				$GLOBALS['db']->query($updateSettings);
				$confirmMsg	=	'Settings Updated Successfully';
			}
			
	}// end of the setting update settings code
	
	
	/* *** Code for retrive the existing data settings values*****/
	if(!$_POST['contactEmailUpdate']&&!$_POST['returnEmailUpdate']&&!$_POST['bounceEmailUpdate']&&!$_POST['maxCountUpdate']&&!$_POST['freeDaysUpdate']&&!$_POST['memFeeUpdate']&&!$_POST['reusable_memFeeUpdate']&&!$_POST['reusable_memFeeUpdatedollar']&&!$_POST['memShipUnsubscribeUpdate']){
	
		$selectSettings	=	"select * from settings";
		$result			=	$GLOBALS['db']->getAll($selectSettings,DB_FETCHMODE_ASSOC);
		
		foreach($result as $key=>$data){
			$_POST['contact_email']					=	$objGen->_output($data['contact_email']);
			$_POST['return_email']					=	$objGen->_output($data['return_email']);
			$_POST['bounce_email']					=	$objGen->_output($data['bounce_email']);
            $_POST['program_max_count']  			=   $objGen->_output($data['program_max_count']);
			$_POST['freeDays'] 						=   $objGen->_output($data['free_days']);
			$_POST['memFee']  						=   $objGen->_output($data['membership_fee']);
			$_POST['memFeedollar']  				=   $objGen->_output($data['membership_feedollar']);
			$_POST['reusable_memFee']  				=   $objGen->_output($data['reusable_membership_fee']);
			$_POST['reusable_memFeedollar']  		=   $objGen->_output($data['reusable_membership_feedollar']);
			$_POST['membership_unsubscribeperiod']  =   $objGen->_output($data['membership_unsubscribeperiod']);
			$_POST['dis_limit']  =   $objGen->_output($data['dis_limit']);
			$_POST['smtp_host']  =   $objGen->_output($data['smtp_host']);
			$_POST['smtp_username']  =   $objGen->_output($data['smtp_username']);
			$_POST['smtp_password']  =   $objGen->_output($data['smtp_password']);
			$_POST['smtp_port']  =   $objGen->_output($data['smtp_port']);
		}
		
	}// end of the  existting data retrival
	

?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript" src="js/mask.js"></script>
</HEAD>
<BODY  class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6">
  <TR>
    <TD valign="top" align=left bgColor=#ffffff><? include("header.php");?></TD>
  </TR>
  <TR height="5">
    <TD valign="top" align=left class="topBarColor">&nbsp;</TD>
  </TR>
  
  <TR>
    <TD align="left" valign="top"> 
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR> 
          <TD  valign="top" align=left width="175" rowSpan="2" > 
            <TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR> 
                <TD valign="top">
				 <TABLE cellSpacing=0 cellPadding=2 width=175  border=0>
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
          <TD valign="top" align=left width=0></TD>
         
        </TR>
        <TR> 
          <TD valign="top" width="1067"><!---Contents Start Here----->
		  
		  
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR> 
                <TD  width="98%" valign="top">
				
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
                      <TD valign="top" width=564 bgColor=white> 
                       
			  			   <form name="frmsettings" action="settings.php" method="post">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){ ?>
					<tr>
						<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>
					
					<?php 
						if($confirmMsg){ ?>
					<tr>
						<td align="center"  class="successAlert"><?=$confirmMsg;?></td>
					</tr>
					<?php } ?>

					<TR height="20"><TD align="left">&nbsp;</TD></TR>
					
				  </table>
                              
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor"><td colspan="3">&nbsp;Set the Contact Email address</td></tr>
					<tr class="listingTable">
						<td width="174"  >Contact Email Id :&nbsp;						</td>
						<td width="260">
							<input type="text" name="contact_email" size="25" maxlength="30" value="<?=$_POST['contact_email']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="contactEmailUpdate" value="Update"></td>
						
					</tr>
					
				    </tbody>
			 	  </table>
				  <br>
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor"><td colspan="3">&nbsp;Set the Return Email address</td></tr>
					<tr class="listingTable">
						<td width="176"  >Return Email Id :&nbsp;						</td>
						<td width="258">
							<input type="text" name="return_email" size="25" maxlength="30" value="<?=$_POST['return_email']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="returnEmailUpdate" value="Update"></td>
						
					</tr>
					
				    </tbody>
			 	  </table>
				  <br>
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor"><td colspan="3">&nbsp;Set the Bounce Email address</td></tr>
					<tr class="listingTable">
						<td width="176"  >Bounce Email Id :&nbsp;						</td>
						<td width="258">
							<input type="text" name="bounce_email" size="25" maxlength="30" value="<?=$_POST['bounce_email']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="bounceEmailUpdate" value="Update"></td>
						
					</tr>
					
				    </tbody>
			 	  </table>
                  <br>
                  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set the Maximum program subscription</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Maximum Program Subscription :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="program_max_count" size="25" maxlength="30" value="<?=$_POST['program_max_count']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="maxCountUpdate" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>
				  <br>
                  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set User Free Days on Jiwok</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >User Free Days :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="freeDays" size="25" maxlength="30" value="<?=$_POST['freeDays']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="freeDaysUpdate" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>
				  <br>
                  <table  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <tbody> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Membership Fee  in Euro</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Membership Fee :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="memFee" size="25" maxlength="30" value="<?=$_POST['memFee']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="memFeeUpdate" value="Update"></td>
                    </tr>
                    </tbody>
                  </table>
					<br>
 				   <table  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <tbody> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Membership Fee in Dollar</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Membership Fee :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="memFeedollar" size="25" maxlength="30" value="<?=$_POST['memFeedollar']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="memFeeUpdatedollar" value="Update"></td>
                    </tr>
                    </tbody>
                  </table>
                  
                  <!--
				  <br>
				  <table  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <tbody> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Reusable Membership Fee in Euro</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Membership Fee :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="reusable_memFee" size="25" maxlength="30" value="<?=$_POST['reusable_memFee']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="reusable_memFeeUpdate" value="Update"></td>
                    </tr>
                    </tbody>
                  </table>
					<br>
 				   <table  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <tbody> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Reusable Membership Fee in Dollar</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Membership Fee :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="reusable_memFeedollar" size="25" maxlength="30" value="<?=$_POST['reusable_memFeedollar']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="reusable_memFeeUpdatedollar" value="Update"></td>
                    </tr>
                    </tbody>
                  </table>
                  -->
				  <br>
				  
				  
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Membership Unsubscribe Period</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Membership Unsubscribe Period :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="membership_unsubscribeperiod" size="25" maxlength="2" value="<?=$_POST['membership_unsubscribeperiod']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="memShipUnsubscribeUpdate" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>
				  <br>
				   <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set Maximum period for a discount</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >Months (1 month = 30 days) :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="dis_limit" size="25" maxlength="30" value="<?=$_POST['dis_limit']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="dis_limit_update" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>        
						 <br>
						 <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3" align="center">&nbsp; <b><?=$heading2?></b></td></tr>
                    </tbody>
                  </table>
				  <br>
				   <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set SMTP Host</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >SMTP Host :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="smtp_host" size="25" maxlength="30" value="<?=$_POST['smtp_host']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="smtp_host_update" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table> 
				  <br>
				   <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set SMTP Username</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >SMTP Username :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="smtp_username" size="25" maxlength="30" value="<?=$_POST['smtp_username']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="smtp_username_update" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>   
				  
				  <br>
				   <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set SMTP Password</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >SMTP Password :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="smtp_password" size="25" maxlength="30" value="<?=$_POST['smtp_password']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="smtp_password_update" value="Update"></td>
                        
                    </tr>
                    
                    </tbody>
                  </table>   
				  
				  <br>
				   <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor"><td colspan="3">&nbsp;Set SMTP Port</td></tr>
                    <tr class="listingTable">
                        <td width="176"  >SMTP Port :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="smtp_port" size="25" maxlength="30" value="<?=$_POST['smtp_port']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="smtp_port_update" value="Update"></td>
                        
                    </tr>
                    
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
    <TD valign="top" align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
        <?php include_once("footer.php");?>
</body>
</html>
