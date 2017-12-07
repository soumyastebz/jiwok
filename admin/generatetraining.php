<?php

/**************************************************************************** 

   Project Name		::> Jiwok 

   Module 			::> 

   Programmer		::> sree

   Date				::> 

   

   DESCRIPTION::::>>>>

   To add workouts for generation

   Add workouts from workout_master to program_queue with selected music style

*****************************************************************************/

	

	include_once('includeconfig.php');

	error_reporting(E_ERROR | E_WARNING);

	ini_set('display_errors', 1);

	include("../includes/classes/class.GenerateTraining.php");

	if ($_REQUEST['langId'] != "") {

		$lanId	= $_REQUEST['langId'];

	} else {

		$lanId	= 1;

	}
	$return_url	= 'generatetraining.php?';
	/*

	Take all the languages to an array.

	*/

	$languageArray	= $siteLanguagesConfig;

	

	/*

	 Instantiating the classes.

	*/

	$genTrainObj 	= new GenerateTraining($lanId);

	$genTrainObj->GENRE_ID	= $siteMasterMenuConfig['GENRE_ID'];

	$objGen   		= new General();

	

	$heading		= "Generate Training";

	$errMsg			= '';

	$keyword		= '';

	$musicstyle_check	= 0;

	$no_of_genres		= 0;

	$musicstyle		= '';

	$musicstyles_options	= $genTrainObj->getMusicstyleGenres($lanId);

	

	$no_of_genres	= sizeof($musicstyles_options);

	$musicstyle		= str_pad($musicstyle, $no_of_genres, '0'); // create a musicstyle of format 000..

	$musicstyle_reg_exp	= '/^[0,1]{'.$no_of_genres.'}$/';

	

	

	if(isset($_REQUEST['workout_flex_id']) || isset($_REQUEST['workout_flex_id_pat'])){

		if(isset($_REQUEST['musicstyles']) && sizeof($_REQUEST['musicstyles']>0)){

			$musicstyle_arr	= array_fill(0, $no_of_genres, 0);

			foreach($_REQUEST['musicstyles'] as $index){

				$musicstyle_arr[$index]	= 1;

			}

			$musicstyle	= implode('', $musicstyle_arr);

		}

		$musicstyle_check	= preg_match($musicstyle_reg_exp, $musicstyle); // check pattern of musicstyle

		if (!isset($_REQUEST['user_id'])) {

			$errMsg	.= 'Please select a user<br />';

		} else {

			$user_id	= $_REQUEST['user_id'];

		}

		if ($musicstyle_check!=1) {

			$errMsg	.= 'Music style in wrong format';

		} 

		if(isset($_REQUEST['workout_flex_id_pat'])){

			$limit_params	= array();

			$part_after_last_comma	= $genTrainObj->getLimitPartFromPattern($_REQUEST['workout_flex_id_pat']);

			if ($part_after_last_comma != '') {

				if ($genTrainObj->checkLimitPartFromPattern($part_after_last_comma) === false) {

					$errMsg	.= 'Wrong Limit format (use ", min - max"): '.$part_after_last_comma;

				} else {

					$limit_params	= $genTrainObj->getLimitParams($part_after_last_comma);

					if (sizeof($limit_params)!=2 || $limit_params['offset'] < 0) {

						$errMsg	.= 'Wrong Limit format (Use ", min - max"): '.$part_after_last_comma;

					} 

				}

			}

		}

	}

	if ($errMsg!='') {

	} elseif(isset($_REQUEST['workout_flex_id'])){

	// Add or update 1 workout_flex_id to program_queue

		$workout_flex_id	= urldecode(trim($_REQUEST['workout_flex_id']));

		if( $workout_flex_id!=''){

			$genTrainObj->insertOrUpdate($workout_flex_id, $musicstyle, $user_id);
			header('Location:'.$return_url.'status=success_add');
			exit;
		}

	} elseif(isset($_REQUEST['workout_flex_id_pat'])){

	// Add or update workouts matching pattern 'workout_flex_id_pat', to program_queue

		$workout_flex_id_pat	= urldecode(trim($_REQUEST['workout_flex_id_pat']));

		if($workout_flex_id_pat!=''){

			$keyword	= $genTrainObj->searchAndUpdate($workout_flex_id_pat, $musicstyle, $user_id, $limit_params);

			$keyword	= str_replace('%', '*', $keyword);

			unset($_REQUEST['keyword']);

		}

	}



	$users_arr	= $genTrainObj->getAdminUsers();

	//check whether the search keyword is existing

	if(isset($_REQUEST['keyword'])){

		$keyword	= trim($_REQUEST['keyword']);

	}

	$maxrows	= 10;

	if(isset($_REQUEST['maxrows']) && $_REQUEST['maxrows']!=''){

		$maxrows	= trim($_REQUEST['maxrows']);

	}

	$pageNo		= 1;

	if(isset($_REQUEST['pageNo']) && $_REQUEST['pageNo']!=''){

		$pageNo		= trim($_REQUEST['pageNo']);

	}

	$sort		= 'asc';

	if(isset($_REQUEST['sort'])){

		$sort	= $_REQUEST['sort']=='desc' ? 'desc' : 'asc';

	}

	//Confirmation message generates here

	

	if($_REQUEST['status'] == "success_add") {

		$confMsg	= "Successfully Added";

	}

	if($_REQUEST['status'] == "success_update") {

		$confMsg	= "Successfully Updated";

	}

	if (isset($_REQUEST['num_added_workouts'])) {

		$confMsg	.= ' '.$_REQUEST['num_added_workouts'].' Workouts to Queue';

	}

	

	$totalRecs	= $genTrainObj->_getTotalCount($keyword);

	if($totalRecs <= 0) {

		$errMsg	.= "No Records";

	}

	

/*    Following Code is for doing paging      */



	if ($pageNo*$maxrows >= $totalRecs+$maxrows) {

		$pageNo	= 1;

	}

	$result	= $genTrainObj->_showPage($pageNo, $maxrows, $keyword, $sort);

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

		$displayString	= "Viewing $startNo to $endNo of $totalRecs workouts";		

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

				  <table class="paragraph2" cellspacing=0 cellpadding=0 width="100%" border=0>

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

						<TD>Workout Flex id pattern</TD>

                        <td>Username</td>

						<TD width="17%"  align="center" >Music Style</TD>

						<TD width="16%" align="center" >Action</TD>

					   	</TR>

                        <tr>

                        	<form name="patternfrm" action="generatetraining_confirm.php" method="post">

							<td valign="middle" ><input type="text" name="workout_flex_id_pat" size="55" /></td>

                            <TD align="center">

                            <select name="user_id" id="user_id">

                            	<?php 

								foreach($users_arr as $user_row){

								?><option value="<?php echo $user_row['user_id']; ?>"><?php echo $user_row['user_email'];?></option><?php

								}

								?>

                            </select>

                            </TD>

                            <TD align="center">

                            <select name="musicstyles[]" id="musicstyles" multiple="multiple" title="music style" size="7" >

<?php 	foreach($musicstyles_options as $key=>$value){ ?>

								<option value="<?php echo $key; ?>"><?php echo $value; ?></option>

<?php 	} ?>

                            </select></TD>

                            <td valign="middle" >

							<input type="submit" name="update" value="Generate" />

							<input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />

							<input name="keyword" type="hidden" value="<?php echo $keyword; ?>" />

							<input type="hidden" name="pageNo" value="<?php echo $pageNo; ?>" />

							<input name="sort" type="hidden" value="<?php echo $sort; ?>" />

							</td>

                            </form>

						</tr>

                        </table>

                    </td></tr>                    

                    <TR> 

                    <TD align="left">

				   		<table height="50"  width="100%"class="topActions"><tr>

						<?  if($keyword!=''){ ?>

							<td valign="middle" width="50"><a href="generatetraining.php?maxrows=<?php echo $maxrows; ?>&sort=<?php echo $sort; ?>"><img src="images/list.gif" alt="Listing Record">&nbsp;List </a></td>

						<? }else{ ?>

							<td valign="middle" width="50" class="noneAnchor"><img src="images/list.gif" alt="Listing Record">&nbsp;List </td>

						<? } ?>

						<td valign="middle" width="50">&nbsp;</td>

						<td valign="middle" class="extraLabels"  align="right">

						<form name="searchfrm" action="generatetraining.php" method="post">Keyword&nbsp;

						<input name="keyword" type="text" size="10" value="<?php echo $keyword; ?>">&nbsp;

						<input type="button" name="search" onClick="javascript:this.form.submit();" value="Search">

                        <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />

						<input name="sort" type="hidden" value="<?php echo $sort; ?>" />

						</form></td>

						</tr></table>

					</TD>

					</TR>

				  </table>

                  <table cellspacing=0 cellpadding=0 width="100%" border=0 class="topColor">

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

						<input name="sort" type="hidden" value="<?php echo $sort; ?>" />

                        </form>

					   </td>

				    </tr>

                   </tbody>

                  </table>

				  <TABLE class="listTableStyle" cellSpacing="1" cellPadding="2" width="100%">

				   <TBODY> 

					   <TR class="tableHeaderColor">

						<TD width="7%" align="center" ><a href="generatetraining.php?pageNo=<?php echo $pageNo; ?>&maxrows=<?php echo $maxrows;?>&sort=<?php echo($sort=='desc'?'asc':'desc');?>&keyword=<?php echo $keyword;?>" title="Sort by <?php echo $sort;?>"><img src="images/<?php echo($sort=='desc'?'down.gif':'up.gif');?>" /></a></TD>

						<TD width="25%" >Workout Id</TD>

                        <TD width="13%"  align="center" >Music Styles in Queue</TD>

						<TD width="17%"  align="center" >Music Style</TD>

                        <td align="center">Username</td>

						<TD width="16%" align="center" >Action</TD>

					   </TR>

<?php if($errMsg != ""){?>

					   <TR class="listingTable"> 

						<TD align="center" colspan="6" ><font color="#FF0000"><?=$errMsg?></font></TD>

					   </TR>

<?php }					   	

	$count = $startNo;

	foreach($result as $key=>$row) {

?>

                       <tr class="listingTable"><form name="generatefrm" action="generatetraining.php" method="post">

						   	<TD align="center"><?php echo $count; ?></TD>

							<TD><?php echo $row['workout_flex_id']; ?></TD>

                            <td align="center">

                            <select name="present_musicstyles" title="musicstyles in queue" >

<?php 	foreach($row['present_musicstyles'] as $index=>$present_musicstyle){ ?>

								<option value="<?php echo $index; ?>" ><?php echo $present_musicstyle; ?></option>

<?php 	} ?>

                            </select></td>

							<TD align="center" >

                            <select name="musicstyles[]" multiple="multiple" title="music style" size="7" >

<?php 	foreach($musicstyles_options as $key=>$value){ ?>

								<option value="<?php echo $key; ?>" <?php 

									if($row['musicstyle'][$key]==1) echo('selected="selected"'); 

								?> ><?php echo $value; ?></option>

<?php 	} ?>

                            </select></TD>

                            <td align="center"><select name="user_id" id="user_id">

                            	<?php 

								foreach($users_arr as $user_row){

								?><option value="<?php echo $user_row['user_id']; ?>"><?php echo $user_row['user_email'];?></option><?php

								}

								?>

                            </select></td>

                            <TD align="center" ><input type="hidden" name="workout_flex_id" value="<?php echo(urlencode($row['workout_flex_id'])); ?>">

                            <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />

                            <input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />

                            <input type="hidden" name="pageNo" value="<?php echo $pageNo; ?>" />

							<input name="sort" type="hidden" value="<?php echo $sort; ?>" />

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

				<table cellspacing=0 cellpadding=0 width="100%" border=0 class="topColor">

                   <tbody>		

					<tr>

						<td align="left" colspan = "6" class="leftmenu">

						<a href="generatetraining.php?pageNo=1&maxrows=<?=$maxrows?>&sort=<?php echo $sort; ?>&keyword=<?=$keyword?>">

						<img src="images/mail-arrowfg.gif" border=0 width="20" height="20" align="absmiddle" alt="First Page"></a>

						<a href="generatetraining.php?pageNo=<?=$prev?>&maxrows=<?=$maxrows?>&sort=<?php echo $sort; ?>&keyword=<?=$keyword?>">

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

							 <a href="generatetraining.php?pageNo=<?=$next?>&maxrows=<?=$maxrows?>&sort=<?php echo $sort; ?>&keyword=<?=$keyword?>">

							<img src="images/mail-arrowrg.gif" border=0 width="20" height="20" align="absmiddle" alt="Next Page"></a>

							<a href="generatetraining.php?pageNo=<?=$noOfPage?>&maxrows=<?=$maxrows?>&sort=<?php echo $sort; ?>&keyword=<?=$keyword?>">

							<img src="images/mail-arrowlastg.gif" border=0 width="20" height="20" align="absmiddle" alt="Last Page"></a>

						</td>

					</tr>     	

				   </tbody>

			 	</table>

				<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />

                <input type="hidden" name="maxrows" value="<?php echo $maxrows; ?>" />

				<input name="sort" type="hidden" value="<?php echo $sort; ?>" />

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

