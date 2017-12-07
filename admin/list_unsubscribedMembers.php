<?php
   ob_start();
    //project    : Jiwok
	//Module     : Listing the req for unsubscribe 
	//Programmer : Ajith 
	//Date       : 14-04-2009
	//########################################
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Contents.php'); ##not need
	include_once('../includes/classes/class.member.php');
	include_once('../includes/classes/class.DbAction.php');
	
	
	
	
	$dbObj	 =	new DbAction();
	$memObj = 	new Member($lanId);
	$genObj   =	new General();	
	
	$heading = "Unsubscribed Users";
	$errMesg = "";
	$confMsg = "";
	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 
	if($_REQUEST['langId']!="")
	  $lanId=$_REQUEST['langId'];
	else
	  $lanId=1;  
		
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "CAST(unsubscribe_date AS DATE)";
		$type = "DESC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
		$cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{
		$exp_keywords=explode(" ",$cleanData);
		//$exp_keywords	=trim($cleanData);	
		if(count($exp_keywords) > 1)
		{
		$searchQuery	=	" and  (user_fname like '%".$exp_keywords[0]."%' OR user_lname like '%".$exp_keywords[1]."%' OR user_email like '%".$exp_keywords[0]."%' OR user_email like '%".$exp_keywords[1]."%')";	
		}
		else
		{
		$searchQuery	=	" and  (user_fname like '%".$cleanData."%' OR user_lname like '%".$cleanData."%' OR user_email like '%".$cleanData."%')";	
		}	
	}
}	
	
	
	$query = "SELECT count(*) as max FROM user_master WHERE user_unsubscribed = 2 ";
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
		$result = $memObj->_showUnsubscribedUsers($_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
	$_REQUEST['pageNo'] = 1;
		$result=	$memObj->_showUnsubscribedUsers($_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
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
<script language="javascript">
var success=0; cRef=""; cRefType=""; cPage="";
var L10qstr,L10pc,L10ref,L10a,L10pg; L10pg=document.URL.toString(); L10ref=document.referrer;
if(top.document.location==document.referrer || (document.referrer == "" && top.document.location != "")) {L10ref=top.document.referrer;}
L10qStr = "pg="+escape(L10pg)+"&ref="+escape(L10ref)+"&os="+escape(navigator.userAgent)+"&nn="+escape(navigator.appName)+"&nv="+escape(navigator.appVersion)+"&nl="+escape(navigator.language)+"&sl="+escape(navigator.systemLanguage)+"&sa="+success+"&cR="+escape(cRef)+"&cRT="+escape(cRefType)+"&cPg="+escape(cPage);
if(navigator.appVersion.substring(0,1) > "3") { L10d = new Date(); L10qStr = L10qStr+"&cd="+screen.colorDepth+"&sx="+screen.width+"&sy="+screen.height+"&tz="+L10d.getTimezoneOffset();}
<!-- The L10 Hit Counter logo and links must not be removed or altered -->
</script>
<script  language="javascript" type="text/javascript">
function checkAllMembers(member) {
	var theForm = member.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'chkdeleteAll'){
	  theForm[z].checked = member.checked;
	  }
     }
    }
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />
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
                       
			   <form name="frmadmin" action=" list_unsubscribedMembers.php" method="post">
                        
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
							<td valign="middle" width="50"><a href=" list_unsubscribedMembers.php?maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
						<? } ?>
						<td valign="middle"><a href="list_req_unsubscribe.php"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Request for unsubscribe membership </a></td>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword']);?>">&nbsp;<input type="submit" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
				    
	    			    	<tr> 
					   <td width="260" valign=top class="paragraph2"><?=$displayString?>					   </td>
							
						
					   
					   <td width="293" align=right class="paragraph2"><?=$heading;?> per page: 
			
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
                           <td width="7%" align="center" >#</td>
                           <td width="28%" align="left">&nbsp; Member Name <a href=" list_unsubscribedMembers.php?field=user_fname&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href=" list_unsubscribedMembers.php?field=user_fname&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a> </td>
                           <td width="27%" align="center">Unsubscribed Date <a href=" list_unsubscribedMembers.php?field=CAST(unsubscribe_date AS DATE)&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href=" list_unsubscribedMembers.php?field=CAST(unsubscribe_date AS DATE)&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a>						   </td>
                         <td width="21%" align="center" >Action</td>
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
                          <td align="center"><?=$count?></td>
                           <td  align="left">&nbsp; <?=$genObj->_output($row->user_fname)?> <?=$genObj->_output($row->user_lname)?></td>
                           <td  align="center"><?=($row->unsubscribe_date);?></td>
                           <td align="center"> <a href = "view_req_member.php?userId=<?=$row->user_id?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=view&pageName=2" class="smallLink">View</a>&nbsp;       
                            </td>
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
						<td align="left" colspan = "3" class="leftmenu" style="padding-left:5px; padding-top:5px;"></td>
						<?php if($noOfPage > 1) { ?>
						<td width="297" colspan = "3" align="right" class="leftmenu" style="padding-left:5px; padding-top:5px;">
						<a href=" list_unsubscribedMembers.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href=" list_unsubscribedMembers.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
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
							 <a href=" list_unsubscribedMembers.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href=" list_unsubscribedMembers.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
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
</body>
</html>