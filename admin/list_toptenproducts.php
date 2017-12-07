<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Product list Management
   Programmer	::> Ganga
   Date			::> 07/05/2011
   
   DESCRIPTION::::>>>>
   This  code userd to list the all products .
   Admin can add/edit/delete the produccts .. 
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.top_products.php");
	
	error_reporting(0);
	
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}
		
	/*
	Take all the languages to an array.
	*/
	$languageArray = $siteLanguagesConfig;
	reset($languageArray);
					 
	/*
	 Instantiating the classes.
	*/
	
	$objProducts	 = new Products($lanId);
	$objGen  		 =	new General();
	
	
	$heading = "Entertainments";
		
	//Sorting field decides here
	if($_REQUEST['field']){
		$field = $_REQUEST['field'];
		$type = $_REQUEST['type'];
	}else{
		$field = "id";
		$type = "ASC";
	}
	
	//check whether the search keyword is existing
	if(trim($_REQUEST['keyword'])){
				 $cleanData	=	str_replace("'",'\\\\\\\\\'',trim($_REQUEST['keyword']));
		$cleanData	=	str_replace("%"," ",trim($cleanData));
		if(preg_match('/["%","$","#","^","!"]/',trim($_REQUEST['keyword']))){
		$errMsg = "Special characters are not allowed";
		}else{ 
				 $searchQuery	= " and (title like '%".$cleanData."%')";}
	}	
	
	
	
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_add"){
		$confMsg = "Successfully Added";
	}
	if($_REQUEST['status'] == "success_update"){
		$confMsg = "Successfully Updated";
	}
	
	
	//Delete testimonial
	if($_REQUEST['action'] == "delete"){
		$id		 = $_REQUEST['masterId'];
		$result	 = $objProducts->_deleteProducts($id);
		$confMsg = "Successfully Deleted";
	}	
	$totalRecs = $objProducts->_getTotalCount($searchQuery,$lanId);
	
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
		$result =  $objProducts->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	}
	else{
	/***********************Selects Records at initial stage***********************************************/
		$_REQUEST['pageNo'] = 1;
	
		$result = $objProducts->_showPage($totalRecs,$_REQUEST['pageNo'],$_REQUEST['maxrows'],$field,$type,$searchQuery);
	
		/*echo count($result);*/
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
		$displayString = "Viewing $startNo to $endNo of $endNo Products";
		
	}
	else
	{
		//For showing range of displayed records.
		if($totalRecs <= 0)
			$startNo = 0;
		else
			$startNo = $_REQUEST['pageNo']*$_REQUEST['maxrows']-$_REQUEST['maxrows']+1;
		$endNo = $_REQUEST['pageNo']*$_REQUEST['maxrows'];
		$displayString = "Viewing $startNo to $endNo of $totalRecs homepage";
		
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
	
	
		
?>
<HTML>
<HEAD>
<TITLE>
<?=$admin_title?>
</TITLE>
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
    <TD vAlign="top" align="left" height="340"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR>
          <TD vAlign=top align=left width="175" rowSpan="2" ><TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR>
                <TD valign="top"><TABLE cellSpacing=0 cellPadding=2 width=175 border=0>
                    <TBODY>
                      <TR valign="top">
                        <TD valign="top"><? include ('leftmenu.php');?></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
            </TABLE></TD>
          <TD vAlign=top align=left width=0></TD>
        </TR>
        <TR>
          <TD valign="top" width="1067"><!---Contents Start Here----->
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR>
                <TD class=smalltext width="98%" valign="top"><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                      <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                      <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                    </tr>
                    <tr>
                      <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                      <td valign="top"><TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                          <TR>
                            <TD vAlign=top width=564 bgColor=white><form name="frmhomepage" action="list_toptenproducts.php" method="post">
                                <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
                                  <tr>
                                    <td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
                                  </tr>
                                  <?php if($confMsg != ""){?>
                                  <tr>
                                    <td align="center" class="successAlert"><?=$confMsg?></td>
                                  </tr>
                                  <?php }
										if($errorMsg != ""){
									?>
                                  <tr>
                                    <td align="center"  class="successAlert"><?=$errorMsg?></td>
                                  </tr>
                                  <?php } ?>
                                  <TR>
                                    <TD align="left"><table height="50"  width="100%"class="topActions">
                                        <tr>
                                          <?  if($objGen->_output($_REQUEST['keyword'])){ ?>
                                          <td valign="middle" width="50"><a href="list_toptenproducts.php?maxrows=<?=$_REQUEST['maxrows']?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
                                          <? }else{ ?>
                                          <td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
                                          <? } ?>
                                          <td valign="middle" width="50"><a href="addedit_toptenproducts.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </a></td>
                                          <td valign="middle" class="extraLabels"  align="right">Keyword&nbsp;
                                            <input name="keyword" type="text" size="10" value="<?=$objGen->_output(stripslashes($_REQUEST['keyword']));?>">
                                            &nbsp;
                                            <input type="button" name="search" onClick="javascript:this.form.submit();" value="Search"></td>
                                        </tr>
                                      </table></TD>
                                  </TR>
                                </table>
                                <table  cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                                  <tbody>
                                    <tr>
                                      <td width="204" valign=top class="paragraph2"><?=$displayString?>
                                      </td>
                                      <td width="166" valign=top class="paragraph2">Language:
                                        <select name="langId" class="paragraph" onChange="this.form.submit()">
                                          <?
									$string = "";
									while (list ($key, $val) = each ($languageArray)) {
											$string .= "<option value={$key}";
											if($key == $lanId){
												$string .= " selected";
											}
											$string	.= ">{$val}</option>";
   									}
									echo $string;
								?>
                                        </select></td>
                                      <td width="183" align=right class="paragraph2"><?=$heading;?>
                                        per page:
                                        <select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
                                          <? foreach($siteRowListConfig as $key=>$data){ ?>
                                          <option value="<?=$key?>" <? if($_REQUEST['maxrows']==$key) echo"selected"; ?>>
                                          <?=$data;?>
                                          </option>
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
                                      <TD width="40%" >Products&nbsp;Title <a href="list_toptenproducts.php?field=title&type=asc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="list_toptenproducts.php?field=title&type=desc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a> </TD>
                                      <TD width="15%" align="center" >Rank <a href="list_toptenproducts.php?field=rank&type=asc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"><img src="images/up.gif" border="0" alt="Ascending Sort">&nbsp;</a> <a href="list_toptenproducts.php?field=rank&type=desc&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&pageNo=<?=$_REQUEST['pageNo']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"><img src="images/down.gif" border="0" alt="Descending Sort"></a></TD>
                                      <TD width="24%" align="center" >Action</TD>
                                    </TR>
                                    <?php if($errMsg != "" && count($result) == 0){?>
                                    <TR class="listingTable">
                                      <TD align="center" colspan="4" ><font color="#FF0000">
                                        <?=$errMsg?>
                                        </font> </TD>
                                    </TR>
                                    <?php }
					    $count = $startNo;		
						if($result)				
						foreach($result as $row){
							
						?>
                                    <tr class="listingTable">
                                      <TD align="center"><?=$count?></TD>
                                      <TD><?=stripslashes(stripslashes(stripslashes($row['title'])));?></TD>
                                      <?php /*?><TD align="center"><?php if($row['homepage_status'] == 1) echo "<img src=\"images/y.gif\" width=\"14\" height=\"14\">"; else echo "<img src=\"images/n.gif\" width=\"14\" height=\"14\">";?></TD><?php */?> 
									  <TD><?=stripslashes(stripslashes(stripslashes($row['rank'])));?></TD>
                                      <TD align="center"><a href = "addedit_toptenproducts.php?masterId=<?=$row['master_id']?>&pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>&action=edit" class="smallLink">Edit</a>&nbsp;
                                        | <a href = "list_toptenproducts.php?masterId=<?=$row['master_id']?>&pageNo=<?=$_REQUEST['pageNo']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>&action=delete" class="smallLink" onClick="return confirm('Are you sure that you want to delete the selected Record? If yes click Ok, if not click Cancel.')">Delete</a></TD>
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
                                      <td align="left" colspan = "6" class="leftmenu"><a href="list_toptenproducts.php?pageNo=1&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"> <img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a> <a href="list_toptenproducts.php?pageNo=<?=$prev?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"> <img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page
                                        <select name="pageNo" class="paragraph"  onChange="form.submit()">
                                          <?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
                                          <option value="<?=$i?>" <? if($i==$_REQUEST['pageNo']) echo "selected";?>>
                                          <?=$i?>
                                          </option>
                                          <?php
								}
							}
							else{
								echo "<option value=\"\">0</option>";
							}
							?>
                                        </select>
                                        of
                                        <?=$noOfPage?>
                                        ] <a href="list_toptenproducts.php?pageNo=<?=$next?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&field=<?=$_REQUEST['field']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"> <img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a> <a href="list_toptenproducts.php?pageNo=<?=$noOfPage?>&type=<?=$_REQUEST['type']?>&langId=<?=$lanId?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&keyword=<?=$objGen->_output(stripslashes($_REQUEST['keyword']))?>"> <img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <?php } ?>
                                <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
                                <input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                              </form></TD>
                          </TR>
                        </TABLE></td>
                      <td background="images/side2.jpg">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                      <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                      <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
                    </tr>
                  </table></TD>
              </TR>
            </TABLE></TD>
        </TR>
        <TR height="2">
          <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>
        </TR>
      </TABLE>
      <?php include_once("footer.php");?>
    </TD>
  </TR>
</TABLE>
</body>
</html>
