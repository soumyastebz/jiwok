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
    include_once ("../stripe_code/config.php");
	$heading 	= "Refund";
	$errorMsg	=	array();   
	if(!$_REQUEST['id'])
		die('Invalid operation');	
	$genObj   	=	new General();
	$dbObj    	=	new DbAction();
	$payObj		= 	new newPayment();
	
	$languageArray=$siteLanguagesConfig;
	
	//Fetching the datas from payment cancel table for refund
    $sqlQry		=	"SELECT sp.*,p.payment_currency FROM `stripe_transaction` sp left join payment p on p.payment_id=sp.payment_id where sp.id='".$_REQUEST['id']."'";
	$resQry		=	$payObj->dbSelectOne($sqlQry);
	$payDetails	=	$payObj	->unserializeArray(base64_decode($resQry['details']));
	
	$currency=$resQry['payment_currency'];
	$amount=$resQry['balance'];
	$i					=	0.1;
	$availableRefunds	=	"";
	if(!$_POST['refund'])
	{
		while($i	<=	$amount)
		{
			if($i	==	0.1)
				$availableRefunds	=	"<a class='click' onclick='fillAmount(".$i.")' href='#'>".$i."</a>";
			else
				$availableRefunds	=	$availableRefunds."<div class='commaClick'>,</div> <a class='click' onclick='fillAmount(".$i.")' href='#'>".$i."</a>";			
			$i	=	round(($i+0.1),2);				
		}
	}	
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
			 $stripe_amount = (trim($_POST['amount']))*100;
			$dbQuery	 =  "select trans_refrns_id,balance from stripe_transaction where id=".$_REQUEST['id'];
			//$dbQuery	 =  "SELECT sp.*,p.payment_currency,p.payment_amount FROM `stripe_transaction` sp left join payment p on p.payment_id=sp.payment_id  where sp.id='".$_REQUEST['id']."'";
			 $res		 =	$payObj->dbSelectOne($dbQuery);
			 $re = \Stripe\Refund::create(array(
                  "charge" => $res['trans_refrns_id'],
                   "amount" => $stripe_amount
                  ));
                  
            if($re->id)
			{ 
				
				
											$amountrefund = ($re->amount)/100;
											$balance = $res['balance'] - $amountrefund;
											$payment  = $_REQUEST['id'];
											$dbValues	=	"balance='".$balance."'";
											$dbCond		=	" id='".$payment."'";
											$dbTable	=	"stripe_transaction";
											$payObj->dbUpdate($dbTable,$dbValues,$dbCond);
				
						$dtls			=	base64_encode(serialize($payDetails));
						$dbFields2		= "	user_id, payment_id, details,status, trans_refrns_id";
						$dbValues2		=  "'".$resQry['user_id']."','".$resQry['payment_id']."','".$dtls."','REFUND','".$re->id."'";						
						$payObj->dbInsertSingle('stripe_transaction',$dbFields2,$dbValues2);								
				        header("Location:newPayment_revice_stripe.php?status=success_Refunded&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);				
			}
			else{  		
				$errorMsg[]	=	$payObj->errorMessages($response);
						//header("Location:refund.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		    }	
		}
		
			
	} 
	//end of refund
			
	
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
</HEAD>
<BODY  class="bodyStyle">
<style type="text/css">
.popup{

	width:431px;

}

.popup .inner{

	padding:20px 35px 0;

	background:#22b3d2;

	position:relative;

}

.popup .inner h2{

	color:#FFF;

	font:bold 14px Arial, Helvetica;

	border-bottom:1px solid #fff;

	padding-bottom:10px;

	margin:0;

}



.popup .inner table.content {

	padding:20px 0 0;

	color:#fff;/*005e7a*/

	font-weight:bold;

}

.popup .inner table.content tr td{

	vertical-align:middle;

	/*padding-bottom:10px;*/

	*padding:0;

	*margin:0;

}

.popup .inner table.content tr td input{

	margin:0;

}



.popupAuto{

	width:560px;

	padding:20px 35px 0;

	background:#22b3d2;

	 -moz-border-radius: 1em;

  	border-radius: 1em;

	behavior: url(./PIE.htc);

	-webkit-border-radius:3px;

}

.popupAuto h2{

	color:#FFF;

	font:bold 14px Arial, Helvetica;

	border-bottom:1px solid #fff;

	padding-bottom:10px;

	margin:0;

}
.click
{
	float:left;
	background-color:faae26;
	color:#000;
	width:25px;
	margin-top:3px;
}
.commaClick
{float:left;}



/***************************/
</style>
<!--<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>-->
<script type="text/javascript" src="../js/jquery-2.1.0.min.js"></script>
<script type="application/javascript">

var popupStatusGeneral = 0;





function showPopup(popupId,backGroundId){

	popupId	=	"#"+popupId;

	if(backGroundId!=""){

		backGroundId	=	"#"+backGroundId;

	}

	

	centerPopupGeneral(popupId,backGroundId);

	loadPopupGeneral(popupId,backGroundId);

	

}





//loading popup with jQuery magic!

function loadPopupGeneral(popupId,backGroundId){

	//loads popup only if it is disabled

	if(popupStatusGeneral==0){

		if(backGroundId!=""){

			$(backGroundId).css({

				"opacity": "0.7"

			});

			$(backGroundId).fadeIn("slow");

		}

		$(popupId).fadeIn("slow");

		popupStatusGeneral = 1;

	}

}



//disabling popup with jQuery magic!

function disablePopupGeneral(popupId,backGroundId){

	popupId	=	"#"+popupId;

	//disables popup only if it is enabled

	if(popupStatusGeneral==1){

		if(backGroundId!=""){

			$(backGroundId).fadeOut("slow");

		}

		$(popupId).fadeOut("slow");

		popupStatusGeneral = 0;

	}

}



//centering popup

function centerPopupGeneral(popupId,backGroundId){

	//request data for centering

	var windowWidth = document.documentElement.clientWidth;

	var windowHeight = document.documentElement.clientHeight;

	var popupHeight = $(popupId).height();

	var popupWidth = $(popupId).width();

	//centering

	

	//alert(popupId);

	$(popupId).css({

		"position": "absolute",

		"left": windowWidth/2-popupWidth/2

	});

	//only need force for IE6

	

	/*$("#backgroundPopup").css({

		"height": windowHeight

	});*/

	

}





var msgTxt		=	"<?php echo $availableRefunds; ?>";
$(document).ready(function(){
	
if(msgTxt!=""){
	
				//document.getElementById("paymantCmnAlertMsg").style.display="block";
				document.getElementById("alertMsgCmnPayment").innerHTML	=	msgTxt;
				showPopup("paymantCmnAlertMsg","");
		}
		$("#okIdCmnPaymentAlert").click(function(){
			disablePopupGeneral("paymantCmnAlertMsg","");
		});
		//Press Escape event!
		$(document).keypress(function(e){
			if(e.keyCode==27 && popupStatus==1){
				disablePopupGeneral("paymantCmnAlertMsg","");
			}
		});
	});	
function fillAmount(val)	
{
	document.getElementById("amount").value=val;	
	disablePopupGeneral("paymantCmnAlertMsg","");
}
</script>
<div class="popup" id="paymantCmnAlertMsg" style="display:none;position:absolute;z-index:100000; top:100px;">
  			<div><img src="../images/pop-top.png" alt="jiwok" /></div>
  			<div class="inner">
    			<table width="100%" border="0" cellspacing="2" cellpadding="0" class="content">
      			<tr>
        			<td align="center" style="text-align:left"><div id="alertMsgCmnPayment"></div><br/><br/></td>
      			</tr>
      			<tr>
        			<td align="center"><!--<a id="okIdCmnPaymentAlert"><input class="bu_03"  name="renewSubscriptionIdBtn" type="button" value="Ok" /></a>--></td>
      			</tr>
    			</table>
    			<div class="clear"></div>
  			</div>
  			<div><img src="../images/pop-btm.png" alt="jiwok" /></div>
		</div>
        
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
                       
			  			   <form action="refund_stripe.php" method="post" enctype="multipart/form-data" name="frmlanguages">
                      
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
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="newPayment_revice_stripe.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
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
					  <td colspan="2" align="left" valign="top"><input type="text" name="amount" id="amount" size="6" value="<?php echo $_POST['amount'];?>" readonly="readonly"  onclick="showPopup('paymantCmnAlertMsg','');"></td>
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
      
      </TD></TR></TABLE>
      
        <?php include_once("footer.php");?> 
        

</body>
</html>
