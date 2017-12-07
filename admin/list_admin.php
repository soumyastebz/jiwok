<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	    ::> Admin- Listing Management
   Programmer	::> Ajith
   Date		    ::> 22-12-2007
   
   DESCRIPTION::::>>>>
   This  code used to manage the administrators to the site  .
   Super Admin can add/edit/delete the adminis ..  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Admin.php');
	
	
	$heading = "Administrators";
	$errMesg = "";
	$confMsg = "";
	
	$adminObj = 	new Admin();
	$genObj   =		new General();

	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_add"){
		$confMsg = "Successfully Added";
	}
	if($_REQUEST['status'] == "success_update"){
		$confMsg = "Successfully Updated";
	}
	if($_REQUEST['status'] == "unauth"){
		$errorMsg = "You have no privilege to access this functionality";
	}
	
	
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "admin_name";
		$type = "ASC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
		$cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{
		$searchQuery	=	" where (admin_name like '%".($cleanData)."%' or admin_email like '%".trim($cleanData)."%') ";}	
	}

	//Delete administrators
	
	if($_REQUEST['action'] == "delete"){
		if($_SESSION['adm_id'] == 1){
			if($_REQUEST['adminId'] != 1){
				$query = "DELETE FROM admin WHERE admin_id=".$_REQUEST['adminId'];
				$GLOBALS['db']->query($query);			
				$confMsg = "Successfully Deleted";
			}else{
				$errorMsg= "Cannot delete super admin";
			}
		}else{
			$errorMsg= "No right to delete";
		}
	}	
if(count($errMsg)== 0){	

		$query = "SELECT count(*) as max FROM admin";
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
			$result = $adminObj->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		}
		else{
		/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
			$result=	$adminObj->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
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
	}	
	
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
<form name="frmadmin" action="list_admin.php" method="post">
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
							<td valign="middle" width="50"><a href="list_admin.php?maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
						<? } ?>
						<td valign="middle"><a href="addedit_admin.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add   </a></td>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword']);?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                  
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
				    
	    			    	<tr> 
					   <td class="paragraph2" valign=top><?=$displayString?>
					   </td>
							
						
					   <td class="paragraph2" align=right><?=$heading;?> per page: 
			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>><?=$data;?></option>
						 <? } ?>
						</select>
					</td>
				    </tr>	

                                </tbody>
                              </table>
				  <TABLE class="listTableStyle" cellSpacing=1 cellPadding=2 width="553">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="10%" align="center" >#</TD>
						<TD width="26%" >Admin Login
						
						<a href="list_admin.php?field=admin_name&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a>
						<a href="list_admin.php?field=admin_name&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a>
						
						
						</TD>
						<TD width="36%" >Admin Email</TD>
						<TD width="9%" align="center" >Status</TD>
						<TD width="19%" align="center" >Action</TD>
					  </TR>
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="5" ><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
					 <?php }
					   	
					   	$count = $startNo;

					
						while($result->fetchInto($row,DB_FETCHMODE_OBJECT)){

							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
							<TD><?=$genObj->_output($row->admin_name);?></TD>
							<TD><?=$genObj->_output($row->admin_email);?></TD>
							<TD align="center"><?php if($row->admin_status == 1) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">";?></TD>
							<TD align="center">
								<a href = "addedit_admin.php?adminId=<?=$row->admin_id?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=edit" class="smallLink">Edit</a>&nbsp;
								<?php if($row->admin_id != 1){
								?>
								|
								<a href = "list_admin.php?adminId=<?=$row->admin_id?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=delete" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected Record? If yes click Ok, if not click Cancel.')">Delete</a></TD> 
								<?php
								
								}
								?>
						    </tr>
						<?php
						$count++;
						}
						?>
					</tbody>
			 	</table>
				<?php if($noOfPage > 1) { ?>
				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                                <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="list_admin.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="list_admin.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
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
							 <a href="list_admin.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="list_admin.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
					</tr>
					
					
                              	
				   </tbody>
			 	</table>
				<?php }?>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
				
                      </TD>
                    </TR>
                  </TABLE></td>
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
	  </TD></TR>
</TABLE></form>
        <?php include_once("footer.php");?>
</body>
</html>