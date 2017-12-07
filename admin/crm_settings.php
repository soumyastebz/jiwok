<?php

	include_once('includeconfig.php');
	
	$heading = "CRM Settings";
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
	
	if($_POST['w_days_since_last_login'] || $_POST['x_days_after_discount_expired'] || $_POST['x_logs_after_discount_time'] || $_POST['z_days_after_unsubscribe']){
			/* ***** Code for generate update query contact mail id in settings table ********* */
			if($_POST['w_days_since_last_login']){
			
				if(trim($_POST['w_days_since_last_login'])=='')
					$errorMsg[]	=	"No of days required";
				elseif(!ctype_digit($_POST['w_days_since_last_login']))
					$errorMsg[]	=	"Enter a number";
				else
					$updateSettings	=	"update crm_settings set value ='".$objGen->_clean_data($_POST['w_days_since_last_login'])."' where name='w_days_since_last_login'";
			}
			
			/* ***** Code for generate update query return mail id in settings table ********* */
			if($_POST['x_days_after_discount_expired']){
			
				if(trim($_POST['x_days_after_discount_expired'])=='')
					$errorMsg[]	=	"No of days required";
				elseif(!ctype_digit($_POST['x_days_after_discount_expired']))
					$errorMsg[]	=	"Enter a number";
				else
					$updateSettings	=	"update crm_settings set value ='".$objGen->_clean_data($_POST['x_days_after_discount_expired'])."' where name = 'x_days_after_discount_expired'";
			}
			/* ***** Code for generate update query bounce mail id in settings table ********* */
			if($_POST['x_logs_after_discount_time']){
			
				if(trim($_POST['x_logs_after_discount_time'])=='')
					$errorMsg[]	=	"No of days required";
				elseif(!ctype_digit($_POST['x_logs_after_discount_time']))
					$errorMsg[]	=	"Enter a number";
				else
					$updateSettings	=	"update crm_settings set value ='".$objGen->_clean_data($_POST['x_logs_after_discount_time'])."' where name = 'x_logs_after_discount_time'";
			}
            if($_POST['z_days_after_unsubscribe']){
            
                if(trim($_POST['z_days_after_unsubscribe'])=='')
                    $errorMsg[] =   "No of logs required";
                elseif(!ctype_digit(trim($_POST['z_days_after_unsubscribe'])))
                    $errorMsg[] =   "Enter a number";
                else
                    $updateSettings =   "update crm_settings set value = '".$objGen->_clean_data($_POST['z_days_after_unsubscribe'])."' where name='z_days_after_unsubscribe'";
            }
			
			/* ***** Code for execute update query ********* */
			if(count($errorMsg)==0){
				$GLOBALS['db']->query($updateSettings);
				$confirmMsg	=	'CRM Settings Updated Successfully';
			}
	}		

	/* *** Code for retrive the existing data settings values*****/
//if(!$_POST['w_days_since_last_login']&&!$_POST['x_days_after_discount_expired']&&!$_POST['x_logs_after_discount_time']&&!$_POST['z_days_after_unsubscribe']){
	
		$selectSettings	=	"select * from crm_settings";
		$result			=	$GLOBALS['db']->getAll($selectSettings,DB_FETCHMODE_ASSOC);
		
		//print_r($result);
		foreach($result as $key=>$data){
/*			$_POST['w_days_since_last_login']		=	$objGen->_output($data['w_days_since_last_login']);
			$_POST['x_days_after_discount_expired']	=	$objGen->_output($data['x_days_after_discount_expired']);
			$_POST['x_logs_after_discount_time']	=	$objGen->_output($data['x_logs_after_discount_time']);
            $_POST['z_days_after_unsubscribe']		=   $objGen->_output($data['z_days_after_unsubscribe']);*/
			$_POST[$data['name']]		=   $objGen->_output($data['value']);
		}
		
//	}// end of the  existting data retrival
	

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
				   	<tr class="tableHeaderColor">
				   	  <td colspan="3">&nbsp;No of days user has not come, after which message is sent </td>
				   	</tr>
					<tr class="listingTable">
						<form name="frmsettings" action="crm_settings.php" method="post">
						<td width="174"  >No of days:&nbsp;</td>
						<td width="260">
							<input type="text" name="w_days_since_last_login" size="25" maxlength="30" value="<?=$_POST['w_days_since_last_login']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="update" value="Update"></td>
						</form>						
					</tr>
				    </tbody>
			 	  </table>
				  <br>
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor">
				   	  <td colspan="3">&nbsp;No of days after the discount period, after which message is shown &amp; email is sent </td>
				   	</tr>
					<tr class="listingTable">
					<form name="frmsettings" action="crm_settings.php" method="post">
						<td width="176"  >No of days :&nbsp;						</td>
						<td width="258">
							<input type="text" name="x_days_after_discount_expired" size="25" maxlength="30" value="<?=$_POST['x_days_after_discount_expired']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="update" value="Update"></td>
						</form>
					</tr>
					
				    </tbody>
			 	  </table>
				  <br>
				  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor">
				   	  <td colspan="3">&nbsp;No of logins after the discount period, after which message is shown &amp; email is sent </td>
				   	</tr>
					<tr class="listingTable">
					<form name="frmsettings" action="crm_settings.php" method="post">
						<td width="176"  >No of logins  :&nbsp;						</td>
						<td width="258">
							<input type="text" name="x_logs_after_discount_time" size="25" maxlength="30" value="<?=$_POST['x_logs_after_discount_time']?>">
							</td><td align="center" class="listTableStyle" width="56">
							<input type="submit" name="update" value="Update"></td>
					</form>
					</tr>					
				    </tbody>
			 	  </table>
                  <br>
                  <TABLE  class="listTableStyle" cellSpacing=1 cellPadding=2 width="90%" align="center">
                   <TBODY> 
                    <tr class="tableHeaderColor">
                      <td colspan="3">&nbsp;No of days after user has unsubscribed, after which email is sent</td>
                    </tr>
                    <tr class="listingTable">
					<form name="frmsettings" action="crm_settings.php" method="post">
                        <td width="176"  >No of  days :&nbsp;                       </td>
                        <td width="258">
                            <input type="text" name="z_days_after_unsubscribe" size="25" maxlength="30" value="<?=$_POST['z_days_after_unsubscribe']?>">
                            </td><td align="center" class="listTableStyle" width="56">
                            <input type="submit" name="update" value="Update"></td>
			        </form>
                    </tr>
                    
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

          </TD>
        </TR>
		 <TR height="2">
    <TD valign="top" align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
        <?php include_once("footer.php");?>
</body>
</html>