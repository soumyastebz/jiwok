<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> New payment refund
   Programmer	::> Dileep.E
   Date			::> 14.06.2011
   
   DESCRIPTION::::>>>>
   This  code used to edit the payment done through the paybox.
  
*****************************************************************************/
	include_once('includeconfig.php');	
	include_once('../includes/classes/class.newpayment.php');	
    
	$heading 	= "Refund";
	$errorMsg	=	array();   
	if(!$_REQUEST['id'])
		die('Invalid operation');	
	$genObj   	=	new General();
	$dbObj    	=	new DbAction();
	$payObj		= 	new newPayment();
	
	$languageArray=$siteLanguagesConfig;
	
	//Fetching the datas from payment cancel table for refund
	$sqlQry		=	"SELECT * FROM `payment_transactions` where id='".$_REQUEST['id']."'";
	$resQry		=	$payObj->dbSelectOne($sqlQry);	
	$payDetails	=	$payObj	->unserializeArray(base64_decode($resQry['details']));
	//echo "<pre/>";
	//print_r($resQry);
	//For display
	if($payDetails['DEVISE']	==	'840')
		$currency	=	'Dollar';
	else
		$currency	=	'Euro';	
	$amount 		= 	$payDetails['MONTANT']/100;
	
	//validation strts herefor feilds 
	if($_POST['refund'])
	{
		if(trim($_POST['amount'])=='')
			$errorMsg[] = "Amount is empty";
		if(!is_numeric($_POST['amount']) && trim($_POST['amount'])!='')
		{
			$errorMsg[] = "Amount must be a number";
		}
		//form validation ends here			
		if(count($errorMsg)==0)
		{			
			//
			$payDetails['MONTANT']		=	$_REQUEST['amount']	*	100;
			$payDetails['TYPE']			=	'00014';
			$payDetails['NUMQUESTION']	=	rand(strtotime(date('Y-m-d h:i:s')),10);	
			$response	=	$payObj->refundPayment($payDetails);
			if($response	== '00000')
			{
						//$resQry
						$dtls			=	base64_encode(serialize($payDetails));
						$dbFields2		= "	user_id, payment_id, details,status, trans_refrns_id";
						$dbValues2		=  "'".$resQry['user_id']."','".$resQry['payment_id']."','".$dtls."','REFUND','".$payDetails['REFERENCE']."'";						
						$payObj->dbInsertSingle('payment_transactions',$dbFields2,$dbValues2);								
				header("Location:newPayment_revice.php?status=success_Refunded&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);				
			}
			else
				$errorMsg[]	=	$payObj->errorMessages($response);
						//header("Location:refund.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
		}
		
			
	} 
	//end of refund
			
	
	
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
                       
			  			   <form action="refund.php" method="post" enctype="multipart/form-data" name="frmlanguages">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){ ?>
					<tr>
						<td align="center"><? print $genObj->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>

					<TR> 
					<TD align="left">
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="newPayment_revice.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Refund </td>
						</tr></table>
					</TD>
					</TR>
					<TR><TD colspan="2" align="right"><?php echo REQUIRED_MESSAGE;?></TD></TR>
				  </table>
                              
				  <TABLE  cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
				   <tr><td colspan="2">
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
				             
					<tr>
						<td colspan="3" align="left" style="padding-left:20px;" class="sectionHeading"></td>
						</tr>
					<tr >
					  <td colspan="3" align="center" >&nbsp;</td>
					  </tr>
					
					<tr>
					  <td width="45%" height="24" align="right" valign="top">&nbsp;</td>
					  <td width="55%" colspan="2" align="left" valign="top">&nbsp;</td>
					  </tr>
                    <tr>
					  <td height="24" align="right" valign="top">Actual Amount :&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><strong><?php echo $amount."&nbsp;".$currency;?></strong></td>
					</tr>  
					
					<tr>
					  <td height="24" align="right" valign="top">Amount<?php echo REQUIRED;?>:&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="text" name="amount" id="amount" size="6" value="<?php echo $_POST['amount'];?>"></td>
					</tr>
					</table>
				   
				  
				  
				   </td></tr>
					
					
					
					<tr>
						<td width="40%" height="38" align="right" >&nbsp;						</td>
						<td >&nbsp;</td>
					</tr>					
					<tr height="40">
						<td colspan="2" align="center" valign="bottom">
							<input type="submit" name="refund" value="&nbsp;&nbsp;Refund&nbsp;&nbsp;">
									</td>
					</tr>					
				    </tbody>
			 	  </table>
			   <input type="hidden" name="lanId" value="<?=$_REQUEST['lanId']?>"> 
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
			   <input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                        <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
                        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">						
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