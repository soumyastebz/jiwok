<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Site general Settings
   Programmer	::> Ajith
   Date			::> 06-03-2008
   
   DISCRIPTION::::>>>>
   This  code used to manage discount section
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.discount.php');
	include_once('../includes/classes/class.member.php');
	
	$genObj   	=	new General();
	$objDisc   	=	new Discount();
	
	$heading = "Discount Manage";
	$errorMsg	=	array();
	$confirmMsg = '';
	
	/* Take all the languages to an array.	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
						 
	if($_REQUEST['langId']!="")
	  $lanId=$_REQUEST['langId'];
	else
	  $lanId=1;  
	if(!isset($_REQUEST['defaultlist']))
	$_REQUEST['defaultlist']	=	1;
	
	if($_REQUEST['default_submit'] && $_POST['manageDiscount'] != 1){
		if($_POST['signd_disc_percentage'] == '' || $_POST['signd_disc_percentage'] == 0){
		$errorMsg[]	=	"Please specify the percentage for signed users";
		}
		//if($_POST['signd_disc_no'] == '' || $_POST['signd_disc_no'] == 0){
		//$errorMsg[]	=	"Please specify the number of users";
		//}
		if($_POST['new_disc_percentage'] == '' || $_POST['new_disc_percentage'] == 0){
		$errorMsg[]	=	"Please specify the percentage for new users";
		}
		if($_POST['new_disc_no'] == '' || $_POST['new_disc_no'] == 0){
		$errorMsg[]	=	"Please specify the number of month(s)";
		}
		
		if(count($errorMsg) == 0){
		$_POST 				= 	$genObj->_clearElmtsWithoutTrim($_POST);
		$discDetailsUpdate 	= 	$objDisc->_updatesDiscount($_POST);
		$confirmMsg			=	"Discount details updated successfully";
		}
	}
	
	//update users discount details
	if($_REQUEST['default_submit'] && $_POST['manageDiscount'] == 1){
		
		if($_POST['signd_disc_percentage'] == '' || $_POST['signd_disc_percentage'] == 0){
		$errorMsg[]	=	"Please specify the percentage for signed users";
		}
		//if($_POST['signd_disc_no'] == '' || $_POST['signd_disc_no'] == 0){
		//$errorMsg[]	=	"Please specify the number of users";
		//}
		if($_POST['new_disc_percentage'] == '' || $_POST['new_disc_percentage'] == 0){
		$errorMsg[]	=	"Please specify the percentage for new users";
		}
		if($_POST['new_disc_no'] == '' || $_POST['new_disc_no'] == 0){
		$errorMsg[]	=	"Please specify the number of month(s)";
		}
		
		if(count($errorMsg) == 0){
			$_POST 		= 	$genObj->_clearElmtsWithoutTrim($_POST);
			$chkdReff	=	explode(',',$_POST['chkedit']);
			for($i=0;$i<count($chkdReff);$i++){
			$reffCount = $objDisc->_isExists($chkdReff[$i]);
			
				if($reffCount[0]['cnt'] > 0){
					$_POST['reff_id']	=	$genObj->_clean_data($chkdReff[$i]);
					$discDetailsUpdate 	= 	$objDisc->_updatesDiscount($_POST);	
					
				}else{
					
					$_POST['reff_id']	=	$genObj->_clean_data($chkdReff[$i]);
					$insEle 			= 	$objDisc->_insDiscElemt($_POST);	
					}
			}
			
			$confirmMsg="Discount details updated successfully";
			$_POST['defaultlist'] 	=	0;
			$_POST['userlist']		=	1;
		}
	}
	
	if(!$_REQUEST['default_submit'] && $_POST['manageDiscount'] != 1){
		//get discount details for general
		$discDetails = $objDisc->_getDiscountDetail('gen');
	}
	
	if(!$_REQUEST['default_submit'] && $_POST['manageDiscount'] == 1 ){
	
		if(count($_POST['chkeditArray']) == 0){
			$errorMsg[]	=	"Please select atleast one user ";
			$_POST['defaultlist'] 	=	0;
			$_POST['userlist']		=	1;
			
		}else{
		$_POST['chkedit']		=	implode(',',$_POST['chkeditArray']);
		}
		
		if(count($errorMsg) == 0){
		
			if(count($_POST['chkeditArray']) == 1){
				//check whether reff id exists
				$reffCount = $objDisc->_isExists($_POST['chkeditArray'][0]);
				if($reffCount[0]['cnt'] > 0){
					//get discount details for the user
					$discDetails = $objDisc->_getDiscountDetail($_POST['chkeditArray'][0]);
					}else{
					//get discount details for the user
					$discDetails = $objDisc->_getDiscountDetail('gen'); 
					}
				}else{
				//get discount details for the user
				$discDetails = $objDisc->_getDiscountDetail('gen');
				}
			}
	}
	
	//display field values
	for($i=0;$i<count($discDetails);$i++){
		  if($discDetails[$i]['user_type'] == 'signed'){
			$_POST['signd_disc_percentage']		=	$genObj->_output($discDetails[$i]['discount_percentage']);
			//$_POST['signd_disc_no']				=	$genObj->_output($discDetails[$i]['discount_no_count']);
		 }elseif($discDetails[$i]['user_type']== 'new'){
			$_POST['new_disc_percentage']		=	$genObj->_output($discDetails[$i]['discount_percentage']);
			$_POST['new_disc_no']				=	$genObj->_output($discDetails[$i]['discount_no_count']);
			}
		 $_POST['reff_id']						=	$genObj->_output($discDetails[$i]['reff_id']);	
		}
		
	
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "user_fname";
		$type = "ASC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
		$cleanData	=	str_replace("'",'\\\\\\\\\\\\\'',trim($_REQUEST['keyword']));
		 $searchQuery	=	" and  (user_fname like '%".$cleanData."%' OR user_lname like '%".$cleanData."%' )";		
	}
	
	$query = "SELECT count(*) as max FROM user_master WHERE  user_refferal_status = 1 " ;
	
	if($searchQuery)
		$query .= $searchQuery;
	//print  $query;
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
		$result = $objDisc->_getUserDetails($_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo'] = 1;
		$result=	$objDisc->_getUserDetails($_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
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
		//For showing range of displayed records.
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
<script language="javascript" src="js/mask.js"></script>
<script type="text/javascript" language="javascript">
function setlist(){

document.frmlist.userlist.value =1;
document.frmlist.defaultlist.value =0;
document.frmlist.chkedit.value ='';
document.frmlist.manageDiscount.value ='';
document.frmlist.submit();
}
function setDefault(){
document.frmsettings.userlist.value =0;
document.frmsettings.defaultlist.value =1;
document.frmsettings.chkedit.value ='';
document.frmsettings.manageDiscount.value ='';
document.frmsettings.submit();

}
function changeToDefault(){
document.frmlist.userlist.value =0;
document.frmlist.defaultlist.value =1;
document.frmlist.manageDiscount.value =1;
document.frmlist.submit();

}function checkAllMembers(member) {
	var theForm = member.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'chkeditAll'){
	  theForm[z].checked = member.checked;
	  }
     }
    }

</script>
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
                      <TD valign="top" width=600 bgColor=white> 
                       
			  	
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=600 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){ ?>
					<tr>
						<td align="center"><? print $genObj->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
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
                              
				  <TABLE  class="listTableStyle" cellSpacing="1" cellPadding="2" width="95%" align="center">
				   <TBODY> 
				   	<tr class="tableHeaderColor">
					
							<td width="73" valign="middle"><a href="#" onClick="setDefault();"><img src="images/add.gif" border="0" alt="Listing Record">&nbsp;Default</a></td>
							<td width="450" valign="middle"><a href="#" onClick="setlist();"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;User Settings </a></td>
							
					</tr>
					<tr class="listingTable">
						<td  colspan="2">
							<table>
								<tr>
								<td width="564">
								<form name="frmsettings" action="" method="post">
								<?php if($_REQUEST['defaultlist'] == 1) {?>
								<div id="defaultcase" style="display:block">
									<fieldset>
									<legend>Default Settings </legend>
								<table width="564" border="0">
								  	  <tr>
											<td width="83">Signed User </td>
											<td width="410">:&nbsp;<input type="text" name="signd_disc_percentage" size="3" maxlength="3" value="<?=$_POST['signd_disc_percentage'];?>">&nbsp;% discount per month by each new user(s) </td>
										  </tr>
										  <tr>
											<td>New User </td>
											<td>:&nbsp;<input type="text" name="new_disc_percentage" size="3" maxlength="3" value="<?=$_POST['new_disc_percentage'];?>">&nbsp;% discount for next&nbsp;<input type="text" name="new_disc_no" size="3" value="<?=$_POST['new_disc_no'];?>">&nbsp;month(s)</td>
										  </tr>
										  <tr>
											<td>&nbsp;</td>
											<td><input type="submit" name="default_submit" value="Update">&nbsp;</td>
										  </tr>
										</table>
										<input type="hidden" name="reff_id" value="<?=$_POST['reff_id'];?>">
										
										</fieldset>
									</div>
									<? } ?>
									<input type="hidden" name="userlist" value="<?=$_POST['userlist'];?>">
									<input type="hidden" name="defaultlist" value="<?=$_POST['defaultlist'];?>">
									<input type="hidden" name="manageDiscount" value="<?=$_POST['manageDiscount'];?>">
									<input type="hidden" name="chkedit" value="<?=$_POST['chkedit'];?>">
									</form>
								</td>
								</tr>
								<tr>
								<td width="511">
								<form name="frmlist" method="post" action="" >
								<?php if($_REQUEST['userlist'] == 1) {?>
								<div id="userlist" style="display:block">
									<fieldset>
									<legend>List of Users </legend>
									
									<table width="564" border="0">
										  <tr>
											<td>
												<TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                    <TR> 
                      <TD vAlign=top width=564 bgColor=white> 
                       
			 	 <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  	<TR> 
					<TD align="left">
				   		<table height="50" width="100%" class="topActions"><tr>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword'])?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
				  </table>
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
	    			    	<tr> 
					   <td width="361" valign=top class="paragraph2"><?=$displayString?></td>
					   <td width="192" align=right class="paragraph2"><?=$heading;?> per page: 
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
                           <td width="5%" align="center" ><input type="checkbox" name="chkeditAll[]" value="" onClick="return checkAllMembers(this);"></td>
						   <td width="7%" align="center" >#</td>
                           <td width="26%" align="left">&nbsp; Member Name <a href="manage_discount.php?field=user_fname&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="manage_discount.php?field=user_fname&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0"><img src="images/down.gif" border="0" alt="Descending Sort"></a> </td>
                          <td width="26%" align="left">&nbsp; Referral Code </td> 
                         </tr>
                         <?php if($errMsg != ""){?>
                         <tr class="listingTable">
                           <td align="center" colspan="7" ><font color="#FF0000">
                             <?=$errMsg?>
                           </font> </td>
                         </tr>
                         <?php }
					   	
					   	$count = $startNo;
						while($result->fetchInto($row,DB_FETCHMODE_OBJECT)){
						
						?>
                         <tr class="listingTable">
                           <td width="5%" align="center" ><input type="checkbox" name="chkeditArray[]" value="<?=$row->user_reff_id?>"></td>
                           <td align="center"><?=$count?></td>
                           <td  align="left">&nbsp; <?=$genObj->_output($row->user_fname)?> <?=$genObj->_output($row->user_lname)?></td>
						    <td  align="left">&nbsp; <?=$genObj->_output($row->user_reff_id)?> </td>
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
						<td align="left" colspan = "3" class="leftmenu" style="padding-left:5px; padding-top:5px;"><span class="leftmenu" style="padding-left:5px; padding-top:5px;">
						  <input type="button" name="manage_discount" value="Manage Discount" style="cursor:pointer;" onClick="changeToDefault();">
						</span></td>
						<td width="297" colspan = "3" align="right" class="leftmenu" style="padding-left:5px; padding-top:5px;">
						<a href="manage_discount.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="manage_discount.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0">
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
							 <a href="manage_discount.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="manage_discount.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&userlist=1&defaultlist=0">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>						</td>
					</tr>
				   </tbody>
			 	</table>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
				
                      </TD>
                    </TR>
                  </TABLE>
											</td>
										  </tr>
										  
										</table> 
										
										</fieldset>
									</div>
									<? } ?>
									<input type="hidden" name="userlist" value="<?=$_POST['userlist'];?>">
									<input type="hidden" name="defaultlist" value="<?=$_POST['defaultlist'];?>">
									<input type="hidden" name="manageDiscount" value="<?=$_POST['manageDiscount'];?>">
									<input type="hidden" name="chkedit" value="<?=$_POST['chkedit'];?>">
									</form>
								</td>
								</tr>
							</table>
						</td>
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