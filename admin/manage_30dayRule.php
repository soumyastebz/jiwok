<?php
    //project    : Jiwok
	//Module     : New payment Unsubscription from paybox
	//Programmer : Dileep.E 
	//Date       : 02.07.2011
	//########################################	
	include_once('includeconfig.php');	
	include_once('../includes/classes/class.newpayment.php');	
	include_once('../includes/classes/class.Languages.php');
	//require_once '../includes/classes/Payment/class.payment.php';			
	$heading = "Manage 30 day rule";
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
    $lanObj->_getLanguageArray();
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_Unsubscribed"){
		$confMsg = "Successfully Unsubscribed";
	}	
	
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "MT.status";
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
	
	if($_REQUEST['action'] != "" && $_REQUEST['id']!=""){
			   //cancel code here
		   //echo $_REQUEST['id'];die;
		if($_REQUEST['action']	==	'Suspend')
		{
			$payObj->suspendFrom30dayRule($_REQUEST['id']); 
			$confMsg = "Successfully Suspended from 30 day rule";	  
		}
		else if($_REQUEST['action']	==	'Cancel')
		{
			$payObj->cancelSuspendRule($_REQUEST['id']);
			$confMsg = "Successfully Cancelled the suspension";	 
		}
		else if($_REQUEST['action']	==	'Update')	
		{
			$payObj->updateSuspendDate($_REQUEST['id']);
			$confMsg = "Successfully Updated the date";		
		}
	}	
	//***********for polishadmin
	if($page_name!= "")
	{
		
		$joinquery	=	" where UM.user_language =5";
	}
	else
	{
		$joinquery	=	"";
	}
	//**************************		
	$query	=	"SELECT count(*) as max,MT.*,UM.user_email FROM user_master UM LEFT JOIN manage_30dayRule MT on UM.user_id = MT.user_id" .$joinquery;	
	
	
	//$query = "SELECT count(*) as max FROM payment_transactions PC WHERE (PC.status = 'PAID' OR PC.status = 'REFUND')";
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
		$result = $payObj->_showPage30datRule($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery,$page_name);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo'] = 1;
		$result=	$payObj->_showPage30datRule($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery,$page_name);
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
function suspend(params,textid,from)
{	
	var suDate 	=	document.frmadmin["suspend_date_" + textid].value;
	if(from	==	1)
		var result	=	confirm('Are you sure that you want to Suspend this user from 30 day rule?.If yes click Ok, if not click Cancel.');
	else
	{
		if(suDate	!=	"")
			var result	=	confirm('Are you sure that you want to Update the date?.If yes click Ok, if not click Cancel.');	
		else
		{
			alert('Please enter date');
			return false;
		}
	}
	if(result	== true)
	{
		var keyStr = "ABCDEFGHIJKLMNOP" +
	               "QRSTUVWXYZabcdef" +
	               "ghijklmnopqrstuv" +
	               "wxyz0123456789+/" +
	               "=";
		var output = "";
     	var chr1, chr2, chr3 = "";
     	var enc1, enc2, enc3, enc4 = "";
     	var i = 0;

     	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
     	var base64test = /[^A-Za-z0-9\+\/\=]/g;
    	if (base64test.exec(params)) {
        alert("There were invalid base64 characters in the input text.\n" +
              "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
              "Expect errors in decoding.");
     	}
     	input = params.replace(/[^A-Za-z0-9\+\/\=]/g, "");
     	do {
        	enc1 = keyStr.indexOf(input.charAt(i++));
        	enc2 = keyStr.indexOf(input.charAt(i++));
        	enc3 = keyStr.indexOf(input.charAt(i++));
        	enc4 = keyStr.indexOf(input.charAt(i++));

        	chr1 = (enc1 << 2) | (enc2 >> 4);
        	chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        	chr3 = ((enc3 & 3) << 6) | enc4;

        	output = output + String.fromCharCode(chr1);

        	if (enc3 != 64) {
           		output = output + String.fromCharCode(chr2);
        	}
       		if (enc4 != 64) {
           		output = output + String.fromCharCode(chr3);
       		}
        	chr1 = chr2 = chr3 = "";
        	enc1 = enc2 = enc3 = enc4 = "";

     	} while (i < input.length);

      var decoded	=	unescape(output);
		
		
		
		
		window.location = "manage_30dayRule.php"+decoded+"&susDate="+suDate;
	}
	else
	{	
		return false;
	}
}
function updateSuspend(textid)
{
	var suDate 	=	document.frmadmin["suspend_date_" + textid].value;
	if(suDate	!=	"")
		var result	=	confirm('Are you sure that you want to Update the date?.If yes click Ok, if not click Cancel.');	
	else
	{
		alert('Please enter date');
		return false;
	}
	if(result	!= true)
		return false;	
}
</script>
<script language="javascript" src="js/mask.js"></script>
<SCRIPT language="JavaScript1.2" src="../includes/js/tooltip.js" type="text/javascript"></SCRIPT>
<script language="JavaScript" src="../calendar/tigra/calendar_us.js"></script>
<link rel="stylesheet" href="../calendar/tigra/calendar.css">
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
                       
			   <form name="frmadmin" action="manage_30dayRule.php" method="post">
                        
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
                           <td width="34%" >Email <a href="manage_30dayRule.php?field=UM.user_email &type=asc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="manage_30dayRule.php?field=UM.user_email &type=desc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a> </td>
                           <td width="13%" >Brand</td>
                           <td width="13%" >Join date</td>
                           <td width="10%" align="center">Status</td>
                           <td width="22%" align="center" >Action</td>
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
                           <td height="98" align="center"><?=$count?></td>
                           <td><?=$genObj->_output($row->user_email);?></td>
                           <td><?php if($genObj->_output($row->brand_name)) echo $genObj->_output($row->brand_name); else echo "Jiwok";?></td>
                           <td align="center"><?=$genObj->_output($row->user_doj);?></td>
                           <td align="center"><?php 
						   	if($genObj->_output($row->status)	== 0)
						   		echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\" title=\"Active\">";
							else if($row->valid_date	<	date('m/d/Y')	&&	($row->valid_date	!=	""))
								echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\" title=\"Suspend Expired\">";
							else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\" title=\"Suspended\">";
									
																											  
						   /*if(($genObj->_output($row->status)	< 1)	&&	(($row->valid_date	<	date('m/d/Y')) || ($row->valid_date==""))) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\" title=\"Active\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\" title=\"Expired\">";*/?></td>                           
                           <td align="center">&nbsp;<?php if($genObj->_output($row->status) < 1){?>
                           <input type="text" name="suspend_date_<?=$row->user?>" size="10" maxlength="100"  value="" readonly>
                           &nbsp;
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmadmin',
									// input name
									'controlname': 'suspend_date_<?=$row->user?>'
									});
							</script>&nbsp;
                            <span class="tooltip" onMouseOver="tooltip('Suspend up to this date');" onMouseOut="exit();">[?]</span><!--<a href = "manage_30dayRule.php?id=<?=$row->user?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$lanId?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=Suspend" class="smallLink" onClick="return confirm('Are you sure that you want to Suspend this user from 30 day rule?.If yes click Ok, if not click Cancel.')" title="Suspend from 3o day rule"><strong>Suspend</strong></a>-->
                            <?php
							$val	=	base64_encode("?id=".$row->user."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&langId=".$lanId."&keyword=".$genObj->_output($_REQUEST['keyword'])."&action=Suspend");
							?>
							<a href = "#" class="smallLink" onClick="suspend('<?=$val?>','<?=$row->user?>','1')" title="Suspend from 3o day rule"><strong>Suspend</strong></a><?php }
						   else
						   {
							   $val	=	base64_encode("?id=".$row->user."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&langId=".$lanId."&keyword=".$genObj->_output($_REQUEST['keyword'])."&action=Update");
							   ?>
                             <a href = "manage_30dayRule.php?id=<?=$row->user?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$lanId?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=Cancel" class="smallLink" onClick="return confirm('Are you sure that you want to Cancel this user from 30 day suspend rule?.If yes click Ok, if not click Cancel.')" title="Cancel Suspend"><strong>Cancel</strong></a> / <a href = "#" class="smallLink" onClick="suspend('<?=$val?>','<?=$row->user?>','2')" title="Update Suspend date"><strong>Update</strong></a>
                             <input type="text" name="suspend_date_<?=$row->user?>" size="10" maxlength="100"  value="<?=$row->valid_date?>" readonly>
                             <script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmadmin',
									// input name
									'controlname': 'suspend_date_<?=$row->user?>'
									});
							</script> 
                             <?php }
						   ?>                             </td>
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
                    <?php if($noOfPage > 1) {?>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="manage_30dayRule.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_30dayRule.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							echo "noOfPage".$noOfPage;
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
							 <a href="manage_30dayRule.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_30dayRule.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td>
                            <?php }?>
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