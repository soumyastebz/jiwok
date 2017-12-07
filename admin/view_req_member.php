<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Newsletter Management
   Programmer	::> Sreejith E C
   Date			::> 2/2/2007
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit newsletter.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.member.php');
	include_once('../includes/classes/class.DbAction.php');
	
	
	
	
	$dbObj	 =	new DbAction();
	$memObj = 	new Member($lanId);
	$objGen   =	new General();
	
	$heading = "User Details";
	
  
	$errorMsg	=	array();
	
	//if edit following will execute on loading
	if($_REQUEST['userId'] and count($errorMsg)==0){
		
		$result = $memObj->_getOneUser($_REQUEST['userId']);
		
	}
	
	
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
                       
						  
				<form name="feedbackform" action="view_feedback.php" method="post">
						  <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                          <TBODY> 
                          <TR> 
                            <TD>
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
				
									<TR> 
									<TD align="left">
										
										<table width="98" height="50" class="topActions">
										<?php if($_REQUEST['pageName'] == '1')
										$return		= 	"list_req_unsubscribe.php";
										else
										$return		= 	"list_unsubscribedMembers.php";
										?>
										  <tr><td valign="middle" width="103"><a href="<?=$return;?>?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;Back to List   </a></td>
										
										</tr></table>
									</TD>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="80%" align="center">
				   <TBODY> 
				   
				    	<TR>
					    <td width="40%" align="left" style="padding-top:15px;"><strong>User Name :</strong>&nbsp;</td>
					    <td style="padding-top:15px;"><?=$objGen->_output($result['user_fname'])." ".$objGen->_output($result['user_lname'])?></td>
					</tr>
					<tr>
						<td width="40%" align="left"> <strong>E-mail :</strong>&nbsp;</td>
						<td><?=$objGen->_output($result['user_alt_email'])?>
						</td>
					</tr>
                    <tr>
						<td width="40%" align="left"><strong>  Date Of Request:</strong>&nbsp;</td>
						<td><?=$objGen->_modifier_date_format($result['user_req_unsubscribe'])?></td>
					</tr>
					<?php if($_REQUEST['pageName'] == '2'){?>
					 <tr>
						<td width="40%" align="left"><strong>  Unsubscribed Date:</strong>&nbsp;</td>
						<td><?=($result['unsubscribe_date'])?></td>
					</tr>   
					<? }?>                 
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId"     value="<?=$_REQUEST['langId']?>">   
				<input type="hidden" name="pageNo"	   value="<?=$_REQUEST['pageNo']?>">
			    <input type="hidden" name="maxrows"    value="<?=$_REQUEST['maxrows']?>">
			    <input type="hidden" name="type"       value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" 	   value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword"    value="<?=$_REQUEST['keyword']?>">
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
