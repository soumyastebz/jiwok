<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin- Categories Management
   Programmer	::> Deepa S
   Date		::> 01-01-2009
   
   DESCRIPTION::::>>>>
   This  code used to manage the training categories in the site  .
   Admin can add/edit/delete the categories 
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Paging.php');
	
	$heading = "Categories";
	$errMesg = "";

	
	$pageObj = 	new Paging();
	$genObj  =	new General();
	$dbObj	 =	new DbAction();
	
	//Sorting field decides here



	if($_REQUEST['field']){
		$field = "label_manager.".$_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "label_manager.label_name";
		$type = "DESC";
	}
	if(!$_REQUEST['langId'])
		$_REQUEST['langId'] = 1;

			//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
		 $cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{
		$searchQuery	=	" and label_manager.label_name like '%".$cleanData."%'";	}	
	}

	
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_add"){
		$confMsg = "Successfully Added";
	}
	if($_REQUEST['status'] == "success_update"){
		$confMsg = "Successfully Updated";
	}
	
	
	//Delete eventmasteristrators
	
	if($_REQUEST['action'] == "delete"){
		
		$query = "DELETE FROM label_manager WHERE label_type='CATEGORY' and labeltype_id=".$_REQUEST['catId'];
		$result = $GLOBALS['db']->query($query);
		$query = "DELETE FROM categories WHERE category_id=".$_REQUEST['catId'];
		$result = $GLOBALS['db']->query($query);				
		$confMsg = "Successfully Deleted";
	}	
	
	$query = "SELECT count(*) as max FROM categories,label_manager WHERE categories.category_parent = ".$_REQUEST['masterId']." and label_manager.label_type='CATEGORY' and categories.category_id =label_manager.labeltype_id and label_manager.language_id=".$_REQUEST['langId'];
    

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
		$result = $pageObj->_showCategoryPage($_REQUEST['langId'],$totalRecs,$_REQUEST['masterId'],$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
		$result = $pageObj->_showCategoryPage($_REQUEST['langId'],$totalRecs,$_REQUEST['masterId'],$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
		if($result->numRows() <= 0)
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
		$displayString = "Viewing $startNo to $endNo of $endNo ".$_REQUEST['heading'];
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs ".$_REQUEST['heading'];
		
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
	
	
	
	if($_REQUEST['masterId']!=0){
		$selectBase	="select category_parent as baseId from categories where category_id=".$_REQUEST['masterId'];
		
		$baseResult = $GLOBALS['db']->getAll($selectBase, DB_FETCHMODE_OBJECT);
		$baseId = $baseResult[0]->baseId;

	}else
		$baseId=0;
	
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
                      <TD vAlign=top width=564 bgColor=white> 
                       
			   <form name="frmadmin" action="list_categories.php" method="post">
                        
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
						
				   		<table height="50"  width="100%" class="topActions"><tr>
						<? if($genObj->_output($_REQUEST['keyword'])){ ?>
							<td valign="middle" width="50" ><a href="list_categories.php?masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&langId=<?=$_REQUEST['langId']?>&maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
						<? } ?>
						<td valign="middle" width="50"><a href="addedit_categories.php?action=add&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&langId=<?=$_REQUEST['langId']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add   </a></td>
<td ><? if(isset($_REQUEST['masterId'])!=0){ 


?><a href="list_categories.php?masterId=<?=$baseId;?>"><img src="images/add.gif" border="0" alt="List Categories">&nbsp;Base Categories </a><? } ?></td>
						<td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?=$genObj->_output($_REQUEST['keyword']);?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
						</tr></table>
					</TD>
					</TR>
					
				  </table>
                     <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
				    
	    			    	<tr> 
					   <td class="paragraph2" valign=top><?=$displayString?> <?=str_repeat('&nbsp',7); ?> Language
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="langId">
						<? foreach($siteLanguagesConfig as $key=>$data){ ?>
						<option value="<?=$key?>" <? if($_REQUEST['langId']==$key) echo"selected"; ?>><?=$data?></option>
						<? } ?>
						</select>
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
						<TD width="9%" align="center" >#</TD>
						<TD width="47%" ><?=$heading;?>&nbsp;Name
						
						<a href="list_categories.php?field=label_name&type=asc&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&masterId=<?=$_REQUEST['masterId']?>&langId=<?=$_REQUEST['langId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a>
						<a href="list_categories.php?field=label_name&type=desc&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$_REQUEST['langId']?>&pageNo=<?=$_REQUEST['pageNo']?>&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a>
						
						
						</TD>
						
						<TD width="10%" align="center" >Status</TD>
						<TD width="34%" align="center" >Action</TD>
					  </TR>
					  <?php if($errMsg != ""){?>
						  <TR class="listingTable"> 
							<TD align="center" colspan="5" ><font color="#FF0000"><?=$errMsg?></font> 
							</TD>
						  </TR>
					 <?php }
					   	
					   	$count =$startNo;
						while($result->fetchInto($row,DB_FETCHMODE_OBJECT)){



						$selectChild	="select count(category_id) as num from categories where category_parent=".$row->category_id;
		
						$countResult = $GLOBALS['db']->getAll($selectChild, DB_FETCHMODE_OBJECT);
						$countNum = $countResult[0]->num;


							
						?>
						    <tr class="listingTable">
						    	<TD align="center"><?=$count?></TD>
							<TD><?=$row->label_name?></TD>
							
							<TD align="center"><?php if($row->category_status == 1) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">";?></TD>
							<TD align="center">
<? if(($row->category_id == 5) || ($row->category_id == 6)) {?><a href = "list_categories.php?catId=<?=$row->category_id?>&masterId=<?=$row->category_id?>&langId=<?=$_REQUEST['langId']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&action=edit&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>" class="smallLink">Sub <?=$heading;?></a>&nbsp;
								
								|<? }?>
								<a href = "addedit_categories.php?catId=<?=$row->category_id?>&masterId=<?=$row->category_parent?>&langId=<?=$_REQUEST['langId']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&action=edit&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>" class="smallLink">Edit</a>&nbsp;
								
								|

								<? if($countNum==0 && ($row->category_parent !=0)){ ?>
								<a href = "list_categories.php?catId=<?=$row->category_id?>&masterId=<?=$row->category_parent?>&langId=<?=$_REQUEST['langId']?>&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>&action=delete" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected Record? If yes click Ok, if not click Cancel.')">Delete</a>
								<? }else{ ?>
								<a href = "#" class="smallLink" onClick=" alert(' Category contains subcategory/It may be root Category.So you can not delete  this!')">Delete</a>
								
								<? } ?>
</TD> 
								
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
						<a href="list_categories.php?pageNo=1&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$_REQUEST['langId']?>&field=<?=$_REQUEST['field']?>&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="list_categories.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$_REQUEST['langId']?>&field=<?=$_REQUEST['field']?>&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
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
							 <a href="list_categories.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&langId=<?=$_REQUEST['langId']?>&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="list_categories.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&langId=<?=$_REQUEST['langId']?>&masterId=<?=$_REQUEST['masterId']?>&heading=<?=$_REQUEST['heading']?>&keyword=<?=$genObj->_output($_REQUEST['keyword'])?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
					</tr>
					
					
                              	
				   </tbody>
			 	</table>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
				 <input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
				 <input type="hidden" name="heading" value="<?=$_REQUEST['heading']?>">
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