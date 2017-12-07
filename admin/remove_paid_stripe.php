<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Testimonials Management
   Programmer	::> soumya and shilpa
   Date			::> 27/03/2013
   
   DESCRIPTION::::>>>>
   This  code used to unsubscribe users from paybox
   Admin delete the users from paybox
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Contents.php');
	include_once('../includes/classes/Payment/class.payment.php');
	 include_once ("../stripe_code/config.php");	
	 include_once('../includes/classes/Payment/class.stripePayment.php');
	/*ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);*/
	$xmlPath="../xml/english/page.xml";	
	/* Instantiating the classes.*/
	
	$objGen  		=	new General();
	$paymentObj		= 	new paymentClass();
	$parObj 		=   new Contents('removePaidUser.php');
	$returnError	=	$parObj->_getTagcontents($xmlPath,'registrationUser','messages');
	$dataError		=	$returnError['errorMessage'];
	$heading 		= 	"Unsubscription from Paybox";
	if($_REQUEST['submit'])
	{
		$emailAddr	=	$_REQUEST['email'];	
		$errorMsg 	= 	0;
		$err1		=	0;
		$err2		=	0;
		$alertmsg	=	"";

		if(trim($emailAddr) == '')
		{
			$err1 =1;			
			$errorMsg = 1;
			
			
		}
		else
		{
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$", trim($_POST['email'])))
			{
				
				$errorMsg = 1;
				$err2 =1;							
			}
		}
			if($errorMsg == 0)
			{	
				        $dbQuery	 =  "select payment_email,user_id,id from stripe_payment where payment_email='".$_REQUEST['email']."' and status='ACTIVE' order by id desc";
				        $res		 =	$GLOBALS['db']->getAll($dbQuery,DB_FETCHMODE_ASSOC);
						$userid      = $res[0]['user_id'];
						$useridnew   = $res[0]['id'];
					if($res[0]['payment_email'])
					{
						 $useredetails		 =	"SELECT customer_id,subsciption_id FROM stripe_payment where user_id=".$userid." order by id desc";
		                 $useredetails1		 =	$GLOBALS['db']->getAll($useredetails,DB_FETCHMODE_ASSOC);
					
						  $cu = \Stripe\Customer::retrieve($useredetails1[0]['customer_id']);
	                      $resq = $cu->subscriptions->retrieve($useredetails1[0]['subsciption_id'])->cancel();
	                  
				        if($resq->status =='canceled') {
														
														 $date  = date('Y-m-d');
													     $sql   ="UPDATE stripe_payment SET status='UNSUBSCRIBED',unsubscribed_date=$date WHERE id=$useridnew";
													     $rslt  =  mysql_query($sql);
														if($rslt == 1)
														{
															$sql1 ="UPDATE stripe_auto_renewal SET status='UNSUBSCRIBED' WHERE pp_id=$useridnew";
													        $rslt1 =  mysql_query($sql1); 
														}
														
														$alertmsg	=	'User succesfully unsubscribed'; 
													  }
													else  if($resq->status !='canceled')
													{
													$alertmsg	=	'Transaction not completed';
													}
					
					}
					else
					{
						$alertmsg	=	'User is not existing';
					}
			}
		}
		
		
	
	 
?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<BODY class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6"> 
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
                    <?php if($alertmsg != ""){?>

					<tr> <td align="center" class="successAlert"><?=$alertmsg?></td> </tr>

					<?php }?>

                      <TD vAlign=top width=564 bgColor=white> 
                       
			   <form name="frmunsubscribe" action="" method="post">
                        
				                      
				  <TABLE class="" cellSpacing=1 cellPadding=2 width="553">
				   <TBODY> 
					    <tr>

						<td height="50" align="right" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>
					  
						    <tr class="listingTable" >
						    	<TD align="center">Enter email address<span style="color:#F00">*</span></TD>
								<TD><input type="text" name="email" ></TD>
                            </tr>
                            <tr class="listingTable">
                            <TD>&nbsp;</TD>  <TD>  <span style="color:#F00">  <?php
								 if($errorMsg != 0) 
								 {
									if($err1!=0)
									{
										$err1 =$parObj->_getLabenames($dataError,'noemail','name');
										echo $err1;
									}
									elseif($err2!=0)
									{
										$err2 =$parObj->_getLabenames($dataError,'emailerr','name');
										echo $err2;
									}
									 
								 }
								 ?></span> 
                               </TD>
							</tr>
							<tr class="listingTable">
						    	
								<TD align="right"><input type="submit" name="submit" value="Remove" ></TD>
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
    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
  </TR>
      </TABLE>
        <?php include_once("footer.php");?>
</TD></TR></TABLE>		
</body>
</html>
