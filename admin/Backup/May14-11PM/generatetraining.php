<?php
/**************************************************************************** 
   Project Name		::> Jiwok 
   Module 			::> Admin-Newsletter Management
   Programmer		::> Sreejith E C
   Date				::> 09/2/2007, Friday
   
   DESCRIPTION::::>>>>
   This  code userd to list the all Feedbacks.
   Admin can view/delete the Feedbacks .. 
*****************************************************************************/
	
	include_once('includeconfig.php');
	ini_set('display_errors',1);
	error_reporting(E_ERROR | E_WARNING);
	assert_options(ASSERT_ACTIVE, 1);
	assert_options(ASSERT_WARNING, 1);
	assert_options(ASSERT_BAIL, 0);
	include("../includes/classes/class.GenerateTraining.php");
	if ($_REQUEST['langId'] != "") {
		$lanId	= $_REQUEST['langId'];
	} else {
		$lanId	= 1;
	}
	/*
	Take all the languages to an array.
	*/
	$languageArray	= $siteLanguagesConfig;
	
	/*
	 Instantiating the classes.
	*/
	$genTrainObj 	= new GenerateTraining($lanId, 'admin@jiwok.com');
	$objGen   		= new General();
	
	$heading		= "Generate Training";
	$errMsg			= '';
	$keyword		= '';
	$musicstyle		= '0000000';
	$musicstyle_check	= 0;
	if(isset($_REQUEST['musicstyles']) && sizeof($_REQUEST['musicstyles']>0)){
		$musicstyle_arr	= array(0,0,0,0,0,0,0);
		foreach($_REQUEST['musicstyles'] as $index){
			$musicstyle_arr[$index]	= 1;
		}
		$musicstyle	= implode('', $musicstyle_arr);
	}
	$musicstyle_check	= preg_match('/^[0,1]{7}$/', $musicstyle);
	
	if(isset($_REQUEST['workout_flex_id'])){
		$workout_flex_id	= urldecode(trim($_REQUEST['workout_flex_id']));
		if($musicstyle_check==1){
			if( $workout_flex_id!=''){
				$genTrainObj->insertOrUpdate($workout_flex_id, $musicstyle);
			}
		} else {
			$errMsg	.= 'Music style in wrong format';
		}
	} elseif(isset($_REQUEST['workout_flex_id_pat'])){
		$workout_flex_id_pat	= urldecode(trim($_REQUEST['workout_flex_id_pat']));
		if($musicstyle_check==1){
			if($workout_flex_id_pat!=''){
				$keyword	= $genTrainObj->searchAndUpdate($workout_flex_id_pat, $musicstyle);
				$keyword	= str_replace('%', '*', $keyword);
				unset($_REQUEST['keyword']);
			}
		} else {
			$errMsg	.= 'Music style in wrong format';
		}
	}
		
	//check whether the search keyword is existing
	if(isset($_REQUEST['keyword'])){
		$keyword	= trim($_REQUEST['keyword']);
	}
	$maxrows	= 10;
	if(isset($_REQUEST['maxrows'])){
		$maxrows	= trim($_REQUEST['maxrows']);
	}
	$pageNo		= 1;
	if(isset($_REQUEST['pageNo'])){
		$pageNo		= trim($_REQUEST['pageNo']);
	}
	//Confirmation message generates here
	
	if($_REQUEST['status'] == "success_add") {
		$confMsg	= "Successfully Added";
	}
	if($_REQUEST['status'] == "success_update") {
		$confMsg	= "Successfully Updated";
	}
	
	$totalRecs	= $genTrainObj->_getTotalCount($keyword);
	if($totalRecs <= 0) {
		$errMsg	.= "No Records";
	}
	
	##############################################################################################################
	/*                        Following Code is for doing paging                                                */
	##############################################################################################################
	assert($pageNo >= 1);
	assert($maxrows >= 2);
	if ($pageNo*$maxrows >= $totalRecs+$maxrows) {
		$pageNo	= 1;
	}
	$result	= $genTrainObj->_showPage($pageNo, $maxrows, $keyword);
	
	if (sizeof($result) <= 0) {
		$errMsg	= "No Records.";
	}
	
	if ($totalRecs <= $pageNo*$maxrows) {
		//For showing range of displayed records.
		if($totalRecs <= 0) {
			$startNo	= 0;
		} else {
			$startNo	= ($pageNo*$maxrows - $maxrows) + 1;
		}
		$endNo	= $totalRecs;
		$displayString	= "Viewing $startNo to $endNo of $endNo Workouts";
		
	} else {
		//For showing range of displayed records.
		if($totalRecs <= 0) {
			$startNo	= 0;
		} else {
			$startNo	= ($pageNo*$maxrows - $maxrows) + 1;
		}
		$endNo	= $pageNo*$maxrows;
		$displayString	= "Viewing $startNo to $endNo of $totalRecs feedbacks";		
	}
	//Paging
	$noOfPage	= @ceil($totalRecs/$maxrows); 
	if($pageNo == 1){
		$prev	= 1;
	} else {
		$prev	= $pageNo - 1;
	}
	if($pageNo == $noOfPage){
		$next	= $pageNo;
	} else {
		$next	= $pageNo + 1;
	}
	$keyword			= str_replace('"', "&quot;", $keyword);
//	$musicstyles_options	= array('Pop rock', 'Rnb', 'house - electro', 'Rap', 'Techno - rave', 'Funk -disco - soul', 'World music');
	$musicstyles_options	= $genTrainObj->getMusicstyleGenres();
		
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
					<?php if($errormsgg != ""){?>
					<tr> <td align="center" class="successAlert"><?=$errormsgg?></td> </tr>
					<?php }?>
					<tr><td>
                    <table height="50"  width="100%"class="listingTable">
                        <TR class="tableHeaderColor">
						<TD colspan="2" >Workout Flex id pattern</TD>						
						<TD width="17%"  align="center" >Music Style</TD>
						<TD width="16%" align="center" >Action</TD>
					   	</TR>
                        <tr>
                        	<form name="patternfrm" action="generatetraining.php" method="post">
							<td colspan="2" valign="middle" ><input type="text" name="workout_flex_id_pat" size="55" /></td>
                            <TD align="center">
                            <select name="musicstyles[]" id="musicstyles" multiple="multiple" title="music style" size="7" >
<?php 	foreach($musicstyles_options as $key=>$value){ ?>
								<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php 	} ?>
                            </select></TD>
                            <td valign="middle" > <input type="submit" name="update" value="Generate" /></td>
                            </form>
						</tr>
                        </table>
                    </td></tr>                    
                    <TR> 
                    <TD align="left">
				   		<table height="50"  width="100%"class="topActions"><tr>
						<?  if($keyword!=''){ ?>
							<td valign="middle" width="50"><a href="generatetraining.php?maxrows=<? echo $maxrows; ?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>
						<? }else{ ?>
							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>
						<? } ?>
						<td valign="middle" width="50">&nbsp;</td>
						<td valign="middle" class="extraLabels"  align="right"><form name="searchfrm" action="generatetraining.php" method="post">Keyword&nbsp;<input name="keyword" type="text" size="10" value="<?php echo $keyword; ?>">&nbsp;<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search">
                        <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" /></form></td>
						</tr></table>
					</TD>
					</TR>
				  </table>
                  <table cellspacing=0 cellpadding=0 width="553" border=0 class="topColor">
                      <tbody>
	    			    	<tr> 
					   <td  valign=top class="paragraph2"><?=$displayString?>
					   </td>
					   <td align=right class="paragraph2">
                       <form name="maxrowsfrm" action="generatetraining.php" method="post"><?=$heading;?> per page: 			
						<select class="paragraph"  size=1 onChange="this.form.submit()" name="maxrows">
						<? foreach($siteRowListConfig as $key=>$data){ ?>
						  <option value="<?php echo $key; ?>" <?php if($maxrows == $key) echo"selected"; ?>><?php echo $data; ?></option>
						 <? } ?>
						</select>
                        <input name="keyword" type="hidden" value="<?php echo $keyword; ?>" />
                        </form>
					   </td>
				    </tr>
                   </tbody>
                  </table>
				  <TABLE class="listTableStyle" cellSpacing="1" cellPadding="2" width="553">
				   <TBODY> 
					   <TR class="tableHeaderColor">
						<TD width="7%" align="center" >#</TD>
						<TD width="38%" >Workout Id</TD>						
						<TD width="17%"  align="center" >Music Style</TD>
						<TD width="16%" align="center" >Action</TD>
					   </TR>
<?php if($errMsg != ""){?>
					   <TR class="listingTable"> 
						<TD align="center" colspan="5" ><font color="#FF0000"><?=$errMsg?></font></TD>
					   </TR>
<?php }					   	
	$count = $startNo;
	foreach($result as $key=>$row) {
?>
						
                       <tr class="listingTable"><form name="generatefrm" action="generatetraining.php" method="post">
						   	<TD align="center"><?php echo $count; ?></TD>
							<TD><?php echo $row['workout_flex_id']; ?></TD>
							<TD align="center" >
                            <select name="musicstyles[]" multiple="multiple" title="music style" size="7" >
<?php 	foreach($musicstyles_options as $key=>$value){ ?>
								<option value="<?php echo $key; ?>" <?php 
									if($row['musicstyle'][$key]==1) echo('selected="selected"'); 
								?> ><?php echo $value; ?></option>
<?php 	} ?>
                            </select></TD>
                            <TD align="center" ><input type="hidden" name="workout_flex_id" value="<?php echo(urlencode($row['workout_flex_id'])); ?>">
                            <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />
                            <input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
                            <input type="hidden" name="pageNo" value="<?php echo $pageNo; ?>" />
                            <input type="submit" name="generate" value="Generate"></TD>
						</form></tr>
                        
<?php
		$count++;
	}
?>
					</tbody>
			 	</table>
<?php if($noOfPage > 1) {  ?>
                <form name="pagingfrm" action="generatetraining.php" method="post">
				<table cellspacing=0 cellpadding=0 width=553 border=0 class="topColor">
                   <tbody>		
					<tr>
						<td align="left" colspan = "6" class="leftmenu">
						<a href="generatetraining.php?pageNo=1&maxrows=<?=$maxrows?>&keyword=<?=$keyword?>">
						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>
						<a href="generatetraining.php?pageNo=<?=$prev?>&maxrows=<?=$maxrows?>&keyword=<?=$keyword?>">
						<img src="images/mail-arrowlg.gif" border=0 width="20" height="20" align="absmiddle" alt="Previous Page"></a>[Page 
						<select name="pageNo" class="paragraph"  onChange="form.submit()">
							<?php
							if($noOfPage){
								for($i = 1; $i <= $noOfPage; $i++){
							?>
								<option value="<?=$i?>" <? if($i==$pageNo) echo "selected";?>><?=$i?></option>
							<?php
								}
							} else {
								echo "<option value=\"\">0</option>";
							}
							?>
						</select>
							 of <?=$noOfPage?>]
							 <a href="generatetraining.php?pageNo=<?=$next?>&maxrows=<?=$maxrows?>&keyword=<?=$keyword?>">
							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>
							<a href="generatetraining.php?pageNo=<?=$noOfPage?>&maxrows=<?=$maxrows?>&keyword=<?=$keyword?>">
							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>
						</td>
					</tr>     	
				   </tbody>
			 	</table>
				<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
                <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />                
                </form>
<?php }?>
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
