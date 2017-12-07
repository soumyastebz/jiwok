<?php

/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-feedback Management
   Programmer	::> Soumya
   Date			::> 1/7/15
   
   DESCRIPTION::::>>>>
   This  code userd to view feedback,mail backk to users.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.feedback.php");
	include_once ("../includes/classes/class.sendgrid.php");
	
	/*
	 Instantiating the classes.
	*/
	$objFeedback = new Feedback($lanId);
	$objGen   =	new General();
	$sendg = new sendgrid();
	
	$heading = "Feedback";
	$heading2 = "Answer Mail";
	
  
	$errorMsg	=	array();
	
	//if edit following will execute on loading
	if($_REQUEST['feedbackId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objFeedback->_getOne($_REQUEST['feedbackId']);
		
	}
	if($_POST['update']){
		
		if(trim($_POST['displayTitle'])=='')

				$errorMsg[] = "Display Title required";

				

		if(trim($_POST['content'])=='')

				$errorMsg[] = "Description required";

		

		if(count($errorMsg)==0)	{ 
		
		$username 	=	$objGen->_output($result['user_fname'])." ".$objGen->_output($result['user_lname']);
		$usermail	=	$objGen->_output($result['user_email']);
		$emailTo 	= array($usermail => $username);
		$from       = array('coach@jiwok.com' => 'Jiwok Coach');
		$msg		=	trim($_POST['content']);
		$subject	=	trim($_POST['displayTitle']);
		//$emailTo 	= array('soumya.reubro@gmail.com' => 'soumya');
		$mailrespponse	=$sendg->send($subject,$from,$emailTo,$msg);
		

		// check whether title is already exixting while updating

		

		

			if($mailrespponse)
			{				

					$updateMsg				=	"Mail sent successfully";
					$_POST['displayTitle']	= "";
					$_POST['content']  		=	"";

			}

			

		}			

	
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
										<td align="center"><? //print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } ?>
				
									<TR> 
									<TD align="left">
										
										<table width="98" height="50" class="topActions">
										  <tr><td valign="middle" width="103"><a href="list_feedbacks.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;Back to List   </a></td>
										
										</tr></table>
									</TD>
                                    
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="80%" align="center">
				   <TBODY> 
				   
				    	<TR>
					    <td width="40%" align="left" style="padding-top:15px;"><strong>Training Program:</strong>&nbsp;</td>
					    <td style="padding-top:15px;"><?=$objGen->_output($result['program_title'])?></td>
					</tr>
					<tr>
						<td width="40%" align="left"> <strong>Submitted by:</strong>&nbsp;</td>
						<td><?=$objGen->_output($result['user_fname'])." ".$objGen->_output($result['user_lname'])?>
						  <
						<?=$objGen->_output($result['user_email'])?>
						>
						</td>
					</tr>
                    <tr>
						<td width="40%" align="left"><strong> Submitted Date:</strong>&nbsp;</td>
						<td><?=$objGen->_modifier_date_format($result['feedback_datetime'])?></td>
					</tr>
					
                    <tr>
                        <td width="40%" align="left" height="20">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
					<tr>
					   <td width="40%" align="left" valign="top"><strong>Comment:</strong>&nbsp;</td>
					   <td><?=$objGen->_output($result['feedback_desc'])?><td>
					</tr>
					
                    
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId"     value="<?=$_REQUEST['langId']?>">   
				<input type="hidden" name="feedbackId" value="<?=$_REQUEST['feedbackId']?>">
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
            <!--=======================-->
            
            <TABLE cellSpacing=0 cellPadding=30 width="100%" align=center border=0>
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
                       
						  
				<form name="feedbackform" action="" method="post">
						  <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                          <TBODY> 
                          <TR> 
                            <TD>
								   <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
								  <tr>
										<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading2;?></td>
									</tr>
									<?php 
										if($errorMsg){ ?>
									<tr>
										<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } 
                                    if($updateMsg){ ?>

					<tr>

						<td align="center" class="successAlert"><?=$updateMsg;?></td>

					</tr>

					<?php } ?>
				
									<TR> 
									<TD align="left">
										
										<table width="98" height="50" class="topActions">
										  <tr><td valign="middle" width="103"><a href="list_feedbacks.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;Back to List   </a></td>
										
										</tr></table>
									</TD>                                    
									</TR>
									 <td align="right"><?php echo REQUIRED_MESSAGE;?></td>
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="80%" align="center">
				   <TBODY> 
				   
				    	<TR>
					    <td width="40%" align="left" style="padding-top:15px;"><strong>Display Title</strong>&nbsp;<?php echo REQUIRED;?>:&nbsp;</td>
					    <td style="padding-top:15px;"><input type="text" name="displayTitle" size="55" value="<?=$_POST['displayTitle'] ?>" >
</td>
					</tr>
					<tr>
						<td width="40%" align="left"> <strong>Description</strong>&nbsp;<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea rows="5"  cols="55"  name="content"><?php echo $_POST['content']?></textarea>
						</td>
					</tr>
                    
                    <tr>
                        <td width="40%" align="left" height="20">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
					<tr>
					 <td colspan="3" align="center"><input name="update" type="submit" value="&nbsp;Send&nbsp;"></td>
					</tr>
					
                    
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId"     value="<?=$_REQUEST['langId']?>">   
				<input type="hidden" name="feedbackId" value="<?=$_REQUEST['feedbackId']?>">
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
            <!--=======================-->

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
