<?php 
    //project    : Jiwok
	//Module     : New payment cancellation and refund
	//Programmer : Dileep.E 
	//Date       : 10.06.2011
	//########################################	
	error_reporting(E_ALL);
	ini_set('display_errors',1); 
	include_once('includeconfig.php');	
	include_once('../includes/classes/class.newpayment.php');	
	include_once('../includes/classes/class.Languages.php');
	include_once('../includes/classes/Payment/class.stripePayment_test.php');
    include_once ("../stripe_code/config_test.php");	
   
	$heading = "New payment Cancell/Refund";
	$errMesg = "";
	$confMsg = "";
	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 
	if($_REQUEST['langId']!="")
	  $lanId=$_REQUEST['langId'];
	else
	  $lanId=1;  
	
	$payObj = 	new newPayment();
	$genObj   =	new General();
	$lanObj = 	new Language();
	//~ $stripe	=	new stripePayment(); 
    $lanObj->_getLanguageArray();
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_Cancelled"){
		$confMsg = "Successfully Cancelled";
	}
	if($_REQUEST['status'] == "success_Refunded"){
		$confMsg = "Successfully Refunded";
	}
	
	
	
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "PC.join_date";
		$type = "ASC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
				 $cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{ 
		$searchQuery	=	" AND UM.user_email  like '%".$cleanData."%'";	}	
	}	
	//Delete the recordes 
	//All the records under a particular language should be deleted 
	
	if($_REQUEST['action'] == "cancel"){ 
		 
		  $dbQuery	 =  "select user_id,trans_refrns_id from stripe_transaction where id=".$_REQUEST['id'];		  
			 $res		 =	$payObj->dbSelectOne($dbQuery); 
			try{
				
		     $re = \Stripe\Refund::create(array(
                  "charge" => $res['trans_refrns_id']	
                  ));
		   
			}catch (Exception $e) {
							// Something else happened, completely unrelated to Stripe
							$body = $e->getJsonBody();
    							$err  = $body['error'];								
							   $paymentDetails	=	base64_encode(json_encode($err));
								$temparray = array();
								$temparray['user_id'] = $res['user_id']	;
								$temparray['data'] = $paymentDetails;
								$chkNewEntry = $this->_insertRecord("stripe_transaction_errors",$temparray);
								return 0;
						}
		
		   if($re->id)
		   {
			   	$sqlQry		    	=	"SELECT * FROM `stripe_transaction` where id='".$_REQUEST['id']."'";
				$resQry				=	$payObj->dbSelectOne($sqlQry);
				$dbValues	=	"payment_status=0";
				$dbCond		=	"payment_id='".$resQry['payment_id']."'";
				$dbTable	=	"payment";
				$payObj->dbUpdate($dbTable,$dbValues,$dbCond); 
				
				$dbValues	=	"status='CANCELLED'";
				$dbCond		=	"id='".$resQry['id']."'";
				$dbTable	=	"stripe_transaction";
				$payObj->dbUpdate($dbTable,$dbValues,$dbCond); 
				
				//Check the user is a polish user. If yes skip following steps 
				//cause polish users having one time payment no auto renewal
				$userQry	=	"SELECT * FROM `user_master` where user_id='".$resQry['user_id']."'";
				$resUserQry	=	$payObj->dbSelectOne($userQry);//echo"ffff";exit;
				if($resUserQry['user_language']	!=	5)
				{		
					$sqlQryPr  	=	"SELECT * FROM `stripe_auto_renewal` where user_id='".$resQry['user_id']."' AND status='VALID'";
					$resQryPr	=	$payObj->dbSelectOne($sqlQryPr);
					
					$sqlQry		=	"SELECT customer_id,subsciption_id FROM stripe_payment where id=".$resQryPr['pp_id']." order by id desc";
					$resQry	=	$payObj->dbSelectOne($sqlQry);
					$cu = \Stripe\Customer::retrieve($resQry['customer_id']);
					$resq = $cu->subscriptions->retrieve($resQry['subsciption_id'])->cancel();
					 if($resq->status =='canceled')
									 {
										  $id = $resQryPr['pp_id'];
											$dbValues	=	"status='UNSUBSCRIBED'";
											$dbCond		=	" id='".$id."'";
											$dbTable	=	"stripe_payment";
										
											$payObj->dbUpdate($dbTable,$dbValues,$dbCond);
											
										 $ids = $resQryPr['id'];
											$dbValues	=	"status='UNSUBSCRIBED'";
											$dbCond		=	"id='".$ids."'";
											$dbTable	=	"stripe_auto_renewal";
											$payObj->dbUpdate($dbTable,$dbValues,$dbCond);  
											
										   
									 }
					
					
				}
				
				//~ else if($resUserQry['user_language']	==	5)
				//~ {
					//~ $dbValues	=	"status='CANCELLED'";
					//~ $dbCond		=	"id='".$resQry['id']."'";
					//~ $dbTable	=	"stripe_transaction";
					//~ $payObj->dbUpdate($dbTable,$dbValues,$dbCond); 
				//~ }
				
				$confMsg = "Successfully Cancelled";
					//$confMsg = "Successfully Cancelled";header("location:newPayment_revice_stripe.php");	exit;			 
		   }
		   else
		   {
			   //Transaction for cancellation
			   $errMsg	= $payObj->errorMessages($re);
		   }
		
	}	
	echo "kk";exit;
	//***********for polishadmin
	if($page_name!= "")
	{
		
		$joinquery	=	" and UM.user_language =5";
	}
	else
	{
		$joinquery	=	"";
	}
//**************************
  $query = "SELECT count(*) as max,PC.*,PD.payment_currency,PD.payment_amount,UM.user_email FROM stripe_transaction PC LEFT JOIN payment PD ON PC.payment_id = PD.payment_id LEFT JOIN user_master UM on UM.user_id = PD.payment_userid WHERE PC.status = 'PAID' ".$joinquery. " AND PD.payment_status = 1 and (PD.version='stripe' or PD.version='polishstripe' or  PD.version = 'mobile_stripe') and PC.balance !='0'";
	//$query = "SELECT count(*) as max FROM payment_transactions ";
	if($searchQuery)
		$query .= $searchQuery;

	$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
	$totalRecs = $result[0]->max;

	if($totalRecs <= 0)
		$errMsg = "No Records";

	##############################################################################################################
	/*                        Following Code is for doing paging                                                */
	##############################################################################################################

	if(!$_REQUEST['maxrows'])
		$_REQUEST['maxrows'] = $_POST['maxrows'];
	if($_REQUEST['pageNo']){ 
		if($_REQUEST['pageNo']*$_REQUEST['maxrows'] >= $totalRecs+$_REQUEST['maxrows']){
			$_REQUEST['pageNo'] = 1;
		}
		$result = $payObj->_showPagestripe($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery,$page_name);
	}
	else{ 
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo'] = 1;
		$result=	$payObj->_showPagestripe($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery,$page_name);//echo "<pre>";print_r($result);exit;
		if(count($result) <= 0)
			$errMsg = "No Records.";
	}

		
	if($totalRecs <= $_REQUEST['pageNo']*$_REQUEST['maxrows'])
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $totalRecs;
		$displayString = "Viewing $startNo to $endNo of $endNo ".$heading;
		
	}
	else
	{
		//For showing range of displayed records.news
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs ".$heading;
		
	}
	//Pagin 

	
	$noOfPage = @ceil($totalRecs/$_REQUEST['maxrows']); 
	if($_REQUEST['pageNo'] == 1){
		$prev = 1;
	}
	else
		$prev = $_REQUEST['pageNo']-1;
	if($_REQUEST['pageNo'] == $noOfPage){
		$next = $_REQUEST['pageNo'];
	}
	else
		$next = $_REQUEST['pageNo']+1;
	$languageArray=$siteLanguagesConfig;
	
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript">
var success=0; cRef=""; cRefType=""; cPage="";
var L10qstr,L10pc,L10ref,L10a,L10pg; L10pg=document.URL.toString(); L10ref=document.referrer;
if(top.document.location==document.referrer || (document.referrer == "" && top.document.location != "")) {L10ref=top.document.referrer;}
L10qStr = "pg="+escape(L10pg)+"&ref="+escape(L10ref)+"&os="+escape(navigator.userAgent)+"&nn="+escape(navigator.appName)+"&nv="+escape(navigator.appVersion)+"&nl="+escape(navigator.language)+"&sl="+escape(navigator.systemLanguage)+"&sa="+success+"&cR="+escape(cRef)+"&cRT="+escape(cRefType)+"&cPg="+escape(cPage);
if(navigator.appVersion.substring(0,1) > "3") { L10d = new Date(); L10qStr = L10qStr+"&cd="+screen.colorDepth+"&sx="+screen.width+"&sy="+screen.height+"&tz="+L10d.getTimezoneOffset();}
<!-- The L10 Hit Counter logo and links must not be removed or altered -->
</script>
</HEAD>
<BODY class="bodyStyle">
<TABLE cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6" >
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
            <TABLE cellSpacing="0" cellPadding="0" width="175"  border=0>
              <TR> 
                <TD valign="top">
				 <TABLE cellSpacing=0 cellPadding=2 width=175 
                  border=0>
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
          <TD valign="top" width="1067" ><!---Contents Start Here----->
		  
		  
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
                      <TD vAlign=top width=564 bgColor=white> 
                       
			   <form name="frmadmin" action="newPayment_revice_stripe.php" method="post">
                        
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php if($confMsg != ""){?>
					<tr> <td align="center" class="successAlert"><?=$confMsg?></td> </tr>
					<?php }
						if($errorMsg != ""){
					?>
					<tr>
						<td align="center"  class="successAlert"><?=$errorMsg?></td>
					</tr>
					<?php } ?>
					
					<TR> 
					<TD align="left">
						
				   		<table height="50" width="100%" class="topActions"><tr>
						<? if($genObj->_output($_REQUEST['keyword'])){ ?>
							<td valign="middle" width="50"></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"></td>
						<? } ?>
						<td valign="middle"></td>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword']);?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
				    
	    			    	<tr> 
					   <td width="225" valign=top class="paragraph2"><?=$displayString?>					   </td>
							
						
					   <td width="58" valign=top class="paragraph2">&nbsp;</td>
					   <td width="98" valign=top class="paragraph2">&nbsp;</td>
					   <td width="172" align=right class="paragraph2"><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>					</td>
				    </tr>	
                                </tbody>
                              </table>
				     <table class="listTableStyle" cellspacing=1 cellpadding=2 width="553">
                       <tbody>
                         <tr class="tableHeaderColor">
                           <td width="8%" align="center" >#</td>
                           <td width="34%" >Email <a href="newPayment_revice_stripe.php?field=UM.user_email &type=asc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="newPayment_revice_stripe.php?field=UM.user_email &type=desc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a> </td>
                           <td width="13%" >Amount</td>
                           <td width="10%" >Currency</td>
                           <td width="16%" align="center">Date</td>
                           <td width="19%" align="center" >Action</td>
                         </tr>
                         <?php if($errMsg != ""){?>
                         <tr class="listingTable">
                           <td align="center" colspan="8" ><font color="#FF0000">
                             <?=$errMsg?>
                           </font> </td>
                         </tr>
                         <?php }
					
					   	$count = $startNo;
						while($result->fetchInto($row,DB_FETCHMODE_OBJECT)){
						?>
                         <tr class="listingTable">
                           <td align="center"><?=$count?></td>
                           <td><?=$genObj->_output($row->user_email);?></td>
                           <td><?=$genObj->_output($row->balance);?></td>
                           <td><?=$genObj->_output($row->payment_currency);?></td>
                           <td align="center"><?=$genObj->_output($row->join_date);?></td>
                             <td align="center"><a href = "refund_stripe.php?id=<?=$row->id?>&pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>" class="smallLink" onClick="return confirm('Are you sure that you want to Refund the transaction?.If yes click Ok, if not click Cancel.')">Refund</a>&nbsp;
                               
                               | <a href = "newPayment_revice_stripe.php?id=<?=$row->id?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$lanId?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=cancel" class="smallLink" onClick="return confirm('Are you sure that you want to Cancel the transaction? The Cancellation of this transaction will results in the payment records.If yes click Ok, if not click Cancel.')">Cancel</a></td>
                           
                         </tr>
                         <?php
						$count++;
						}  
						?>
                       </tbody>
                     </table>
				     <table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="newPayment_revice_stripe.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="newPayment_revice_stripe.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>><?=$i?></option>
							<?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="newPayment_revice_stripe.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="newPayment_revice_stripe.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td>
					</tr>
				   </tbody>
			 	</table>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
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
