<?php

/**************************************************************************** 

   Project Name		::> Jiwok 

   Module 			::> 

   Programmer		::> 

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

	/*

	Take all the languages to an array.

	*/

	$languageArray	= $siteLanguagesConfig;

	

	/*

	 Instantiating the classes.

	*/

	$genTrainObj 	= new GenerateTraining($lanId);

	$genTrainObj->GENRE_ID	= $siteMasterMenuConfig['GENRE_ID'];

	//$objGen   		= new General();

	

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

	

	

	if(isset($_REQUEST['musicstyles']) && sizeof($_REQUEST['musicstyles']>0)){

		$musicstyle_arr	= array_fill(0, $no_of_genres, 0);

		foreach($_REQUEST['musicstyles'] as $index){

			$musicstyle_arr[$index]	= 1;

		}

		$musicstyle	= implode('', $musicstyle_arr);

	} elseif (isset($_REQUEST['musicstyle'])) {

		$musicstyle	= $_REQUEST['musicstyle'];

	} else {

		$errMsg	.= 'Please Select Genres<br />';

	}

	$musicstyle_check	= preg_match($musicstyle_reg_exp, $musicstyle); // check pattern of musicstyle

	if ($musicstyle_check!=1) {

		$errMsg	.= 'Music style in wrong format<br />';

	} 

	if (!isset($_REQUEST['user_id'])) {

		$errMsg	.= 'Please select a user<br />';

	} else {

		$user_id	= $_REQUEST['user_id'];

	}



	if($_REQUEST['workout_flex_id_pat'] != ''){

		$limit_params	= array();

		$part_after_last_comma	= $genTrainObj->getLimitPartFromPattern($_REQUEST['workout_flex_id_pat']);	// Limit pattern

		if ($part_after_last_comma != '') {

			if ($genTrainObj->checkLimitPartFromPattern($part_after_last_comma) === false) {

				$errMsg	.= 'Wrong Limit format (use ", min - max"): '.$part_after_last_comma.'<br />';

			} else {

				$limit_params	= $genTrainObj->getLimitParams($part_after_last_comma);

				if (sizeof($limit_params)!=2 || $limit_params['offset'] < 0) {

					$errMsg	.= 'Wrong Limit format (Use ", min - max"): '.$part_after_last_comma.'<br/>';

				} 

			}

		}

	} else {

		$errMsg	.= 'No Workout flex id pattern given<br />';

	}

	

	$return_url	= 'generatetraining.php?';

	if($_REQUEST['maxrows']!='' ){

		$return_url	.= 'maxrows='.$_REQUEST['maxrows'].'&';

	}

	if($_REQUEST['pageNo']!='' ){

		$return_url	.= 'pageNo='.$_REQUEST['pageNo'].'&';

	}

	if($_REQUEST['sort'] != ''){

		$return_url	.= 'sort='.$_REQUEST['sort'].'&';

	}

	

	if ($errMsg=='')  {		

	// Add or update workouts matching pattern 'workout_flex_id_pat', to program_queue

		$workout_flex_id_pat	= urldecode(trim($_REQUEST['workout_flex_id_pat']));

		if($workout_flex_id_pat!=''){

			if (isset($_REQUEST['confirm']) && $_REQUEST['confirm']=='Yes') {

				$affected_rows	= $genTrainObj->addWorkoutsSatisfyingGivenPattern($workout_flex_id_pat, $musicstyle, $user_id);

				
				$affected_rows	= (int) $affected_rows;

				header('Location:'.$return_url.'status=success_add&num_added_workouts='.$affected_rows.'&keyword='.$workout_flex_id_pat);

				exit;

			} else {

				$matching_workouts	= $genTrainObj->getWorkoutsSatisfyingGivenPattern($workout_flex_id_pat, $musicstyle, $user_id);

			}

		}

	}



	$users_arr	= $genTrainObj->getAdminUsers();

	

	if($_REQUEST['keyword']!='' ){

		$return_url	.= 'keyword='.$_REQUEST['keyword'];

	}

		

?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>

<script type="text/javascript">

<!--

function redirect(){

    window.location = "<?php echo $return_url; ?>";

}

//-->

</script>

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

						if($errMsg != ""){

					?>

					<tr>

						<td align="center"  class="successAlert"><?php echo $errMsg; ?></td>

					</tr>

					<tr>

						<td align="center" ><a href="<?php echo $return_url; ?>">Return To Listing</a></td>

					</tr>

					<?php } else {?>

					<tr><td>&nbsp;</td>

					</tr>

				  <tr>

					<td>Selected Genres: <?php echo $matching_workouts['genre_list']; ?></td>

				  </tr>

  				  <tr>

					<td>Music Style: <?php echo $matching_workouts['musicstyle']; ?></td>

				  </tr>

                    <TR> 

                    <TD align="left"><table width="100%" border="0" cellspacing="2" cellpadding="2">

                      <tr>

                        <th scope="col">Workouts that will be sent to queue are ...</th>

                      </tr>

                      <tr>

                        <td><?php 

						if(sizeof($matching_workouts['workouts'])>0) { ?>

							<table width="100%" border="0" cellspacing="1" cellpadding="1">

							  <tr>

								<th scope="col">&nbsp;</th>

							  </tr>

							  <?php for ($i=0, $max=sizeof($matching_workouts['workouts']); $i<$max; $i++) { ?>

							  <tr>

								<td><?php echo (($i+1).'. '.$matching_workouts['workouts'][$i]['workout_flex_id']); ?></td>

							  </tr>

							  <?php } ?>

							  <tr>

							  	<td>Add Workouts to Queue ? <form name="confirmFrm" method="post">

								<input type="submit" name="confirm" id="confirm" value="Yes">

								<input type="button" name="no" id="no" value="No" onClick="redirect();">

								<input type="hidden" name="workout_flex_id_pat" value="<?php if (isset($_REQUEST['workout_flex_id_pat'])) echo($_REQUEST['workout_flex_id_pat']); ?>" />

								<input type="hidden" name="user_id" value="<?php if (isset($_REQUEST['user_id'])) echo($_REQUEST['user_id']); ?>" />

								<input type="hidden" name="musicstyle" value="<?php echo($musicstyle); ?>" />

								<input type="hidden" name="pageNo" value="<?php if (isset($_REQUEST['pageNo'])) echo($_REQUEST['pageNo']); ?>" />

								<input type="hidden" name="keyword" value="<?php if (isset($_REQUEST['keyword'])) echo($_REQUEST['keyword']); ?>" />

								<input type="hidden" name="maxrows" value="<?php if (isset($_REQUEST['maxrows'])) echo($_REQUEST['maxrows']); ?>" />

								<input type="hidden" name="sort" value="<?php if (isset($_REQUEST['sort'])) echo($_REQUEST['sort']); ?>" />

								</form>

								</td>

							  </tr>

							</table><?php 

						} else {?>No Matching Workouts Found: <a href="<?php echo $return_url; ?>">Return To Listing</a><?php } ?></td>

                      </tr>

                    </table></TD>

					</TR>

					<?php } ?>

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

    <TD vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

      </TABLE>

        <?php include_once("footer.php");?>

</TD></TR></TABLE>

</body>

</html>

