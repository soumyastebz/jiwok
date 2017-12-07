<?php
include_once('includeconfig.php');
include_once('../includes/classes/Referrel/class.referal.php');

$heading = $_REQUEST['heading'];
$errorMsg	=	array();

$genObj   =	new General();
$dbObj    =	new DbAction();
$referral    =	new referal();


if($_REQUEST["userId"]!=""){
	$userId	=	$_REQUEST["userId"];	
}else{
	
}

?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
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
          <TD valign="top" colspan="2"><!---Contents Start Here----->
		  
		  
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
                       
						  
				<form name="faqform" action="" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
						  <table cellSpacing=0 cellPadding=4 width=561 border=0>
                          <tbody> 
                          <TR> 
                            <TD valign="top">
								   <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
								  <tr>
										<td colspan="2" height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
									</tr>
									<?php 
										if($errorMsg){ ?>
									<tr>
										<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } ?>
				
									<TR> 
									<TD align="left">
										
									</td><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</tr>
								  </table>
                 	    
						
						
						
						
						
					          
				  
				</td>
                          </tr>
						  <tr><td>
						  
						 <table class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <tbody> 
					<tr height="30px">
						<td width="50%" align="right">Number of Users Registred by Referral :&nbsp;</td>
						<td><?php echo $referral->getUserCount($userId); ?></td>
					</tr>
                    <tr height="30px">
						<td width="50%" align="right">Number of Paid Users &nbsp;</td>
						<td><?php echo $referral->getUserCount($userId,"1,2"); ?></td>
					</tr>
                     <tr height="30px">
						<td width="50%" align="right">Number of Non Paid Users &nbsp;</td>
						<td><?php echo $referral->getUserCount($userId,"0"); ?></td>
					</tr>
                     <tr height="30px">
						<td width="50%" align="right">Number of Months gained :&nbsp;</td>
						<td><?php echo $referral->monthsGained($userId); ?></td>
					</tr>
                    
                      <tr height="30px">
						<td width="50%" align="right">Total FB posts :&nbsp;</td>
						<td><?php echo $referral->getShareCount(1,$userId); ?></td>
					</tr>
                    
                    <tr height="30px">
						<td width="50%" align="right">Total Twitter posts :&nbsp;</td>
						<td><?php echo $referral->getShareCount(2,$userId); ?></td>
					</tr>
                    <tr height="30px">
						<td width="50%" align="right">Total Invitation Mails :&nbsp;</td>
						<td><?php echo $referral->getMailDataUser($userId); ?></td>
					</tr>
					
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="update" value="&nbsp;Update&nbsp;"></td>
					</tr>
				    </tbody>
			 	  </table>
						
						  
						  
						  </td></tr>
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
</td></tr></table>		
</body>
</html>