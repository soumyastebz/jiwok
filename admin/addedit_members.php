<?php

/**************************************************************************** 

   Project Name	::> Jiwok 

   Module 		::> Admin-Members add/edit delete management

   Programmer	::> 

   Date			::> 24/12/2008

   

   DESCRIPTION::::>>>>

   This  code used to add/edit Members  .

  

*****************************************************************************/

	include_once('includeconfig.php');

	include_once('../includes/classes/class.member.php');

	include_once('../includes/classes/class.General.php');

	include_once('../includes/arrays_registration.php');

	include_once('../includes/classes/class.DbAction.php');

	include_once('forumpass.php');



    

	$heading = "Members";

	$errorMsg	=	array();

	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 

	if($_REQUEST['langId']!=""){

	  	$lanId=$_REQUEST['langId'];

	}

	else{

	  	$lanId=1; 

	}

	//print_r($_POST);	

   	$objMember	= 	new Member(1);

	$objGen   	=	new General();

	$objAction	= 	new DbAction();

	$objPass 	= 	new ForumPass();



		

	// getall time zone

	$TimezoneArray = $objMember->_getTimezone();

	//getall jobs from database

	$jobArray		=	$objMember->_getAllJobs($lanId);

	//getall sports from database

	$sportArray		=	$objMember->_getAllSports($lanId);	

	/* Take all label name from label_manager table with menumaster_id = $genreMenuMasterId  */

	//$genreMenus	= $objMember->_getGenreMenus($siteMasterMenuConfig['GENRE_ID'],$lanId);

	 	

	/* Take all label name from label_manager table with menumaster_id = $userOptionMenuMasterId  */

	$optionMenus	= $objMember->_getOptionalMenus($siteMasterMenuConfig['USER_OPTIONAL_FIELDS'],$lanId);

	//print_r($optionMenus);

	

	/* Take voice preference from label_manager */

	$voicePrefer	= $objMember->_getOptionalMenus($siteMasterMenuConfig['VOICE'],$lanId);

     

    $weightUnits    = $objMember->_getOptionalMenus($siteMasterMenuConfig['WEIGHT'],$lanId);

    

    $heightUnits    = $objMember->_getOptionalMenus($siteMasterMenuConfig['HEIGHT'],$lanId);

	

	/* ******Following Code used to add/update member details ******* */

	if($_POST['add']||$_POST['update']){

	

	/* Validation for add and update*/

		if(trim($_REQUEST['user_fname'])=='')

			$errorMsg[]	=	"First name required";

			elseif(is_numeric(trim($_REQUEST['user_fname'])))

			$errorMsg[]	=	"First name does not valid";

			

		if(trim($_REQUEST['user_lname'])=='')

			$errorMsg[]	=	"Last name required";

			elseif(is_numeric(trim($_REQUEST['user_lname'])))

			$errorMsg[]	=	"Last name does not valid";

			

		if(trim($_POST['user_email']) == "")

			$errorMsg[] = "Email id required";

			else if(!$objGen->_validate_email($_POST['user_email']))

			$errorMsg[] = "Email id does not valid";

			else{

				if($_REQUEST['update']){

					if($objMember->_mailid_exist(trim($_POST['user_email']),$_REQUEST['userId']))

					$errorMsg[] = "Email already exist ";

				}else{

					if($objMember->_mailid_exist(trim($_POST['user_email']),''))

					$errorMsg[] = "Email already exist ";

				}

		}

		if(trim($_REQUEST['user_year'])==0)

			$errorMsg[]	=	"Date of year required";

		if(trim($_REQUEST['user_month'])==0)

			$errorMsg[]	=	"Date of month required";

		if(trim($_REQUEST['user_day'])==0)

			$errorMsg[]	=	"Day field required";

		/*if(trim($_REQUEST['user_address'])=='')

			$errorMsg[]	=	"Address required";

		if(!trim($_REQUEST['user_city']))

			$errorMsg[]	=	"City required";

		//if(!trim($_REQUEST['user_state']))

			//$errorMsg[]	=	"State required";

		if(trim($_REQUEST['user_country'])==''||trim($_REQUEST['user_country'])==0)

			$errorMsg[]	=	"Country required";

		if(trim($_REQUEST['user_timezone']) == '')

			$errorMsg[]	=	"Timezone required";

		if(trim($_REQUEST['user_zip'])==''||trim($_REQUEST['user_zip'])===0)

			$errorMsg[]	=	"Zip Code required";

			*/

		/*elseif(!is_numeric(trim($_REQUEST['user_zip'])))

			$errorMsg[] = "Zip code is not valid";*/

/*		if(trim($_POST['user_username']) == "")

			$errorMsg[] = "Username required";

		else{

				if($_REQUEST['update']){

					if($objMember->_username_exist(trim($_POST['user_username']),$_REQUEST['userId']))

					$errorMsg[] = "User login already exist ";

				}else{

					if($objMember->_username_exist(trim($_POST['user_username']),''))

					$errorMsg[] = "User login already exist ";

				}

		}

*/		

		if(trim($_POST['user_password']) == "")

			$errorMsg[] = "Password required";

		

	

		if(trim($_POST['conf_pwd']) == "")

			$errorMsg[] = "Confirmation Password required";

	

		if(trim($_POST['user_password']) && strlen(trim($_POST['user_password'])) < 2)	

			$errorMsg[] = "Password should have atleast two characters";

		

		if(trim($_POST['conf_pwd']) != trim($_POST['user_password']))	

			$errorMsg[] = "Password mismatch";

			

		if(trim($_POST['nike_login']) != '' || trim($_POST['nike_password']) != '' || trim($_POST['nike_conf_pwd']) != ''){

			

			if(trim($_POST['nike_login']) == "")

			$errorMsg[] = "Nike login required";

			

			if(trim($_POST['nike_password']) == "")

			$errorMsg[] = "Nike password required";

			

			if(trim($_POST['nike_password']) != trim($_POST['nike_conf_pwd']))	

			$errorMsg[] = "Nike password mismatch";

			

		}

		

		/*foreach($genreMenus as $key => $data){;

			if($_POST['genre_'.$key])

				$genreArray[]	=	$key;

			

		}*/

		//for sport list

		if($_POST['sport']!=""){

		$_POST['option_'.$siteMasterMenuConfig['SPORTSCAT']] =implode(',',$_POST['sport']);

		}



		if($_FILES['user_photo']['name'] != '')

		{

			$image_type = explode("/", $_FILES['user_photo']['type']);

			if($image_type[0] != "image") 

			{   $errorMsg[] =   "Invalid file format";  } 

			elseif($_FILES['user_photo']['size'] > 2097152)

			{   $errorMsg[] =   "You can only upload a maximum of 2 MB file.";  }



		}	

            if(!trim($_REQUEST['user_weight_value']))

                $errorMsg[] =   "Weight required";

             if(!trim($_REQUEST['user_height_value']))

                $errorMsg[] =   "Height required";   

			

	

		/* *****IF THERE IS NO ERROR ...START ADD/UPDATE PROCCESS***** */

		if(count($errorMsg)==0){

				$_POST['user_password']	= utf8_decode($_POST['user_password']);

				$sp = $_POST['sport'];

				///enter reff id for the user

				

				

				if(($_REQUEST['user_year']!=0 && $_REQUEST['user_month']!=0) && $_REQUEST['user_day'] !=0  ){

				$_POST['user_dob'] = $_REQUEST['user_day'].'/'.$_REQUEST['user_month'].'/'.$_REQUEST['user_year']; 

				unset($_POST['user_day']);

				unset($_POST['user_month']);

				unset($_POST['user_year']);

				}

				

				if($_POST['add']){

						//user reff id

						$_POST['user_reff_id'] = "REFF".uniqid();

						$_POST = $objGen->_clearElmtsWithoutTrim($_POST);

						//print_r($_POST);die;

                        if($_FILES['user_photo']['name'] != ""){

                            $fileName   = uniqid();

                            $extension  = end(explode(".",$_FILES['user_photo']['name']));

                            $nextUpload = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/',$fileName,190,236);

							//$nextUpload1 = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/thumb/',$fileName,87,106);

                            $fileName = $fileName.".".$extension;

                            $_POST['user_photo']    = $fileName;

                        }

						$_POST['user_alt_email']	= $_POST['user_email'];

						$objMember->_insertMember($_POST,$lanId,$sp);

						

						if($_REQUEST['user_language'] == 1) { $lang_forum = "en"; } else { $lang_forum = "fr"; }

						

						$fullname = trim($_REQUEST['user_fname'])." ".trim($_REQUEST['user_lname']);

						$ticket_pass = md5($_POST['user_password']); 

						$TicketArray = array();

						$TicketArray['client_name']		 	= $fullname;

						$TicketArray['email'] 				= trim($_POST['user_email']);

						$TicketArray['registered_on'] 		= date("Y-m-d H:i:s");

						$TicketArray['default_lang'] 		= $lang_forum;

						$TicketArray['preferred_zone'] 		= 0;

						$TicketArray['pass_word'] 			= $ticket_pass;

						$TicketArray['client_status'] 		= 'a';

						

						$ticket = $objAction->_insertRecord('ticket_clients', $TicketArray);

						unset($TicketArray);

												

						header("location:http://www.jiwok.com/jiwokv2/register.php?admin_add=1&user_email=".trim($_POST['user_email'])."&password=".$_POST['user_password']."&lang=".$lang_forum."&timezone=".$_REQUEST['user_fname']);

						exit;

						//header("Location:list_members.php?status=success_add&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);

				}

				

				

				if($_POST['update']){

					//print_r($_POST); die;

					$userId		=	$_REQUEST['userId'];

					$_POST		=	$objGen->_clearElmtsWithoutTrim($_POST);

                    if($_FILES['user_photo']['name'] != ""){

                            $fileName   = uniqid();

                            $extension  = end(explode(".",$_FILES['user_photo']['name']));

                            $nextUpload = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/',$fileName,190,236);

							 $nextUpload1 = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/thumb',$fileName,87,106);

                            $fileName = $fileName.".".$extension;

                            $_POST['user_photo']    = $fileName;

							if($_POST['user_photo'] !="" && is_file("../uploads/users/".$_POST['current_photo'])){

							unlink("../uploads/users/".$_POST['current_photo']);

							unlink("../uploads/users/thumb".$_POST['current_photo']);

							}

							

                    }

                    else{

                            $_POST['user_photo']    = $_POST['current_photo'];

                    }

					if($_REQUEST['adminstatus'] == 3) { $_POST['user_status'] = $_REQUEST['adminstatus']; }

					$_POST['user_alt_email']	= $_POST['user_email'];

					$objMember->_updateMember($userId,$_POST,$lanId,$sp);

					

					if($_REQUEST['user_language'] == 1) { $lang_forum = "en"; } else { $lang_forum = "fr"; }

					

					// Update the ticket table

					if($_SESSION['ticketId'] != '')

					{

					$fullname = trim($_REQUEST['user_fname'])." ".trim($_REQUEST['user_lname']);

					$ticket_pass = md5($_POST['user_password']); 

					$TicketArray = array();

					$TicketArray['client_name']		 	= $fullname;

					$TicketArray['email'] 				= trim($_POST['user_email']);

					$TicketArray['default_lang'] 		= $lang_forum;

					$TicketArray['pass_word'] 			= $ticket_pass;

					$ticket = $objAction->_updateRecord('ticket_clients', $TicketArray, 'client_id='.$_SESSION['ticketId']);

					unset($TicketArray);

					}

					// Update the forum table

					if($_SESSION['forumId'] != '')

					{

					$getpass = $objPass->phpbb_hash($_POST['user_password']);

					

					$ForumArray = array();

					$ForumArray['username']		 		= trim($_POST['user_email']);

					$ForumArray['username_clean']		= trim($_POST['user_email']);

					$ForumArray['user_email']	 		= trim($_POST['user_email']);

					$ForumArray['user_timezone']	 	= trim($_POST['user_timezone']);

					$ForumArray['user_lang'] 			= $lang_forum;

					$ForumArray['user_password'] 		= $getpass;

					// password

					$forum = $objAction->_updateRecord('forum_users', $ForumArray, 'user_id='.$_SESSION['forumId']);

					unset($ForumArray);

					}

					

					

					header("Location:list_members.php?status=success_update&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);

				}	

				

		}

		/* *****END OF EDD/UPDATE PROCCESS***** */

	}



//Retrieving Data from database

	if($_REQUEST['userId'] && !$_POST['user_status'])

	{

	$selectQuery	=	"select * from user_master where user_id=".$_REQUEST['userId'];

	$result 		= 	$GLOBALS['db']->getRow($selectQuery,DB_FETCHMODE_ASSOC);

	//For Nike Data

	//$selectQueryNike	=	"select * from nike where nike_userid=".$_REQUEST['userId'];
	$selectQueryNike 	= "SELECT SQL_SMALL_RESULT * FROM `nike` WHERE `nike_userid`=".$_REQUEST['userId']." LIMIT 1";

	$resultNike 		= 	$GLOBALS['db']->getRow($selectQueryNike,DB_FETCHMODE_ASSOC);

	

		while(list($key,$value) = each($result)){

			$_POST[$key] = $objGen->_output($value);

			if($key=='user_password'){

				$_POST['conf_pwd']		=	utf8_encode($objGen->_output($objGen->_decodeValue($value)));

				$_POST['user_password']	= 	utf8_encode($objGen->_output($objGen->_decodeValue($_POST['user_password'])));

			}

			if($key=='user_dob'){

				$userDob				=	$objGen->_output($value);

				$usrDob					=	explode("/", $userDob);

				$_POST['user_day']		=	$usrDob[0];

				$_POST['user_month']	=	$usrDob[1];

				$_POST['user_year']		=	$usrDob[2];

			}

					

		}

	if($_REQUEST['action'] == 'edit')

	{	

	

	$selectEmail	=	mysql_query("select user_email from user_master where user_id=".$_REQUEST['userId']);

	$EmailAdd		= 	mysql_fetch_assoc($selectEmail);

	

	// for ticket

	$selectTicketId = mysql_query("select client_id from ticket_clients where email='".$EmailAdd['user_email']."'");

	$resTicketId = mysql_fetch_assoc($selectTicketId);

	//if(!session_is_registered('ticketId')){ session_register('ticketId'); }

	$_SESSION['ticketId'] = $resTicketId['client_id'];

	

	// for forum

	$selectForumId = mysql_query("select user_id, user_type from forum_users where username='".$EmailAdd['user_email']."'");

	$resForumId = mysql_fetch_assoc($selectForumId);



	/*--session_register is not support in this php version---------
	if(!session_is_registered('forumId')){ session_register('forumId'); }

	if(!session_is_registered('usertype')){ session_register('usertype'); }
*/
	$_SESSION['forumId'] = $resForumId['user_id'];

	$_SESSION['usertype'] = $resForumId['user_type'];	

	}	

	

		//Nike data retieve

		while(list($key,$value) = each($resultNike)){

			$_POST[$key] = $objGen->_output($value);

			

			if($key =='nike_password') {

				 $_POST['nike_password']		=	$objGen->_output($objGen->_decodeValue($value));

				 $_POST['nike_conf_pwd']		= 	$objGen->_output($objGen->_decodeValue($value));

			}

		}

		

		/*$genreQuery	= "SELECT user_options.menu_id,user_options.menu_value FROM user_options,menus,menu_master WHERE user_options.usermaster_id =".$_REQUEST['userId']." and menus.menu_id=user_options.menu_id and menu_master. menumaster_id=".$siteMasterMenuConfig['GENRE_ID']." and  menu_master. menumaster_id=menus.menumaster_id";



		$res			= $GLOBALS['db']->getAll($genreQuery,DB_FETCHMODE_ASSOC);

		foreach($res as $k => $data){

				 $key 	= $data['menu_id'];

				 $value	= $data['menu_value'];

				 $_POST['genre_'.$key] = 1;

		}

		$genreQuery	= "SELECT user_options.menu_id,user_options.menu_value FROM user_options,menus,menu_master WHERE user_options.usermaster_id =".$_REQUEST['userId']." and menus.menu_id=user_options.menu_id and menu_master. menumaster_id=".$siteMasterMenuConfig['USER_OPTIONAL_FIELDS']." and  menu_master. menumaster_id=menus.menumaster_id";



		$res			= $GLOBALS['db']->getAll($genreQuery,DB_FETCHMODE_ASSOC);

		foreach($res as $k => $data){

				$key 	= $data['menu_id'];

				$_POST['option_'.$key] = $objGen->_output($data['menu_value']);

		}*/

    

	

	$currentImage   = $_POST['user_photo'];

	}

/* *************Decides wich should be selected *********8 */

	if($_REQUEST['userId']){

		if($_POST['user_status'] == 1){

			$act_status = "Checked";

		}else{

			$inact_status = "Checked";

		}

		

		if($_POST['user_discount_status'] == 1){

			$act_discount_status = "Checked";

		}else{

			$inact_discount_status = "Checked";

		}

		if($_POST['user_refferal_status'] == 1){

			$act_refferal_status = "Checked";

		}else{

			$inact_refferal_status = "Checked";

		}

		if($_POST['user_newsletter'] == 1){

			$act_user_newsletter = "Checked";

		}else{

			$inact_user_newsletter = "Checked";

		}

	}else{

		if($_POST['user_status'] == 1){

			$act_status = "Checked";

		}elseif($_POST['user_status'] == 2){

			$inact_status = "Checked";

		}else{

			$act_status = "Checked";

		}

		

		

		if($_POST['user_discount_status'] == 1){

			$act_discount_status = "Checked";

		}elseif($_POST['user_discount_status'] == 0){

			$inact_discount_status = "Checked";

		}else{

			$act_discount_status = "Checked";

		}

		

		if($_POST['user_refferal_status'] == 1){

			$act_discount_status = "Checked";

		}elseif($_POST['user_refferal_status'] == 0){

			$inact_refferal_status = "Checked";

		}else{

			$act_refferal_status = "Checked";

		}

		if($_POST['user_newsletter'] == 1){

			$act_user_newsletter = "Checked";

		}else{

			$inact_user_newsletter = "Checked";

		}

	}



	$countriesArray = $objMember->_getCountries();

    

    if($currentImage != ""){

        $imageDetails = getimagesize('../uploads/users/'.$currentImage);

    }

?>

<HTML><HEAD><TITLE><?=$admin_title?></TITLE>

<? include_once('metadata.php');?>

<script language="JavaScript">

function openNewWindow(URLtoOpen,windowName,width,height)

{

windowFeatures ="menubar=no,scrollbars=no,location=no,favorites=no,resizable=no,status=no,toolbar=no,directories=no";

var test = "'";

winLeft = (screen.width-width)/2;

winTop = (screen.height-(height+110))/2;

window.open(URLtoOpen,windowName,"width=" + width +",height=" + height + ",left=" + winLeft + ",top=" + winTop + test + windowFeatures + test);



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

                      <TD valign="top" width=564 bgColor=white> 

                       

			  			   <form name="frmMembers" action="addedit_members.php" method="post" enctype="multipart/form-data">

                      

				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>

				  <tr>

						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>

					</tr>

					<?php 

						if($errorMsg){ ?>

					<tr>

						<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>

					</tr>

					<?php } ?>



					<TR> 

					<TD align="left">

						

				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_members.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>

						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>

						</tr></table>

					</TD>

					</TR>

					<TR><TD colspan="2" align="right"><?php echo REQUIRED_MESSAGE;?></TD></TR>

				  </table>

                              

				  <TABLE  cellSpacing=0 cellPadding=2 width="100%">

				   <TBODY> 

				   <tr><td colspan="2">





<fieldset><legend>Personal Info</legend>



<table width="100%" align="center" cellspacing="2" cellpadding="2">

                     <tr height="10">

                       <td align="right" colspan="2">&nbsp;</td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">First 

                         Name<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><input type="text" name="user_fname" size="32" maxlength="100" value="<?=$_POST['user_fname']?>">                       </td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">Last 

                         Name<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><input type="text" name="user_lname" size="32" maxlength="100" value="<?=$_POST['user_lname']?>">                       </td>

                     </tr>

                     <tr>

                       <td align="right">Email Address<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><input type="text" name="user_email" size="32" maxlength="100" value="<?=$_POST['user_email']?>">                       </td>

                     </tr>

                     <tr>

                       <td align="right">I am a<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><input type="radio" name="user_gender" id="radio" value="0" <? if($_POST['user_gender'] == 0) echo "checked"?>>

    Male&nbsp;&nbsp;

    <input type="radio" name="user_gender" id="radio2" value="1" <? if($_POST['user_gender'] == 1) echo "checked"?>>

    Female </td>

                     </tr>

                     <tr>

                       <td align="right">Date of Birth<?php echo REQUIRED;?>:&nbsp; </td>

                       <td><select name="user_day" class="paragraph" style="width:65px; background-color:#F3F3F3;">

                           <option value="0">--Day--</option>

                           <?

								for($i=1; $i<=31; $i++){

									$string = "<option value={$i}";

									if($i == $_POST['user_day']){

										$string .= " selected";

									}

									$string .= ">{$i}</option>";

									echo $string;

								}

							

							?>

                         </select>

						  <select name="user_month" class="paragraph" style="width:80px; background-color:#F3F3F3;">

                           <option value="0">--Month--</option>

                           <?

								for($i=1; $i<=count($siteMonthList); $i++){

									$string = "<option value={$i}";

									if($i == $_POST['user_month']){

										$string .= " selected";

									}

									$string .= ">{$siteMonthList[$i]}</option>";

									echo $string;

								}

							

							?>

                         </select>

						 <select name="user_year" class="paragraph" style="width:80px; background-color:#F3F3F3;">

                           <option value="0">--Year--</option>

                           <?

								for($i=(date('Y')-100); $i<=date('Y'); $i++){

									$string = "<option value={$i}";

									if($i == $_POST['user_year']){

										$string .= " selected";

									}

									$string .= ">{$i}</option>";

									echo $string;

								}

							

							?>

                         </select>						                      </td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">Address:&nbsp; </td>

                       <td><textarea name="user_address" rows="3" cols="23"><?=$_POST['user_address']?></textarea>                                          </td>

                     </tr>

                     <tr>

                       <td align="right">City:&nbsp; </td>

                       <td><input type="text" name="user_city" id="user_city" size="32" value="<?=$_POST['user_city']?>">                       </td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">State:&nbsp; </td>

                       <td><input type="text" name="user_state" id="user_state" size="32" value="<?=$_POST['user_state']?>">                       </td>

                     </tr>

                     <tr>

                       <td width="40%" align="right">Country:&nbsp; </td>

                       <td>

                           <select name="user_country" id="user_country" class="paragraph" style="width:180px; background-color:#F3F3F3;">

                          		<option value="0">--Select--</option>

						  <? 

						  		while(list($code,$name) = each($countriesArray)){

									$string = "<option value={$code}";

									if($code == $_POST['user_country']){

										$string .= " selected";

									}

									$string .= ">{$name}</option>";

									print $string;

								}

						   ?>

						   </select></td>

                     </tr>

					 

                     <tr>

                       <td width="40%" align="right">Timezone:&nbsp; </td>

                       <td>

					 <select name="user_timezone" id="user_timezone"  class="paragraph" style="width:180px; background-color:#F3F3F3;">

				  <? 

					while(list($code,$name) = each($TimezoneArray))

					{

						$string = "<option value={$code}";

						if($code == $_POST['user_timezone']){

							$string .= " selected";

						}

						$string .= ">{$name}</option>";

						print $string;

					}

				   ?>

				 </select>

				 </td></tr>

					 <tr>

                       <td width="40%" align="right">Postal Code:&nbsp; </td>

                       <td>

                           <input name="user_zip" id="user_zip" value="<?=$_POST['user_zip']?>" class="paragraph" style="width:180px; background-color:#F3F3F3;">                          		</td>

                     </tr>

                     

                     <tr>

                       <td width="40%" align="right"> Prefered Language  <?php echo REQUIRED;?>:&nbsp; </td>

                       <td>

					<select name="user_language" id="user_language" class="paragraph" style="width:180px; background-color:#F3F3F3;" >



	

					   <?



						foreach($siteLanguagesConfig as $key=>$data){

					   		

					  ?>

					   <option value="<?=$key?>" <? if($_POST['user_language']==$key) print 'selected'; ?>><?=$data?></option>

					   <? } ?>  

					   </select>						 </td>

                     </tr>

					 <tr>

                       <td width="40%" align="right">Preferred Voice :&nbsp; </td>

                       <td>

					<select name="user_voice" id="user_voice" class="paragraph" style="width:180px; background-color:#F3F3F3;" >

<?				foreach($voicePrefer as $key=>$data){

					   		

					  ?>

					   <option value="<?=$key?>" <? if($_POST['user_voice']==$key) print 'selected'; ?>><?=$data?></option>

					   <? } ?>  

					   </select>						 </td>

                     </tr>

					<tr>

		      <td align="right">Member Photo </td>

		      <td>:

		         

		          <input type="file" name="user_photo">

		          <input type="hidden" name="current_photo" value="<?=$currentImage?>"/>

				  <? if($currentImage != ""){?>

				  <a href="javascript:void(0)" onClick="openNewWindow('../uploads/users/<?=$currentImage?>','windowname',<?=($imageDetails[0]+100);?>,<?=($imageDetails[1]+50);?>)">View</a>

				  <? }?>				  </td>

		      </tr>

			  <?php if($_REQUEST['userId']) {?>

			  <tr>

		      <td align="right">Referral Code </td>

		      <td>: <input type="text" name="user_reff_id " id="user_reff_id " size="32" value="<?=$_POST['user_reff_id']?>" disabled="disabled"></td>

		      </tr>

			  <? } ?>

                   </table>





</fieldset>

               </td>

				   </tr>

					<tr>

					  <td colspan="2" >



<fieldset><legend>Account Info</legend>

<table width="100%" align="center" cellpadding="2">

                        <!--<tr>

                          <td width="40%" align="right">Login<?php echo REQUIRED;?>:&nbsp; </td>

                          <td><input type="text" name="user_username" size="32" maxlength="100" value="<?=$_POST['user_username']?>">

                          </td>

                        </tr>-->

                        <tr>

                          <td width="40%" align="right">Password<?php echo REQUIRED;?>:&nbsp; </td>

                          <td><input type="password" name="user_password" size="32" maxlength="30" value="<?=$_POST['user_password']?>">

                          </td>

                        </tr>

                        <tr>

                          <td align="right">Confirm Password<?php echo REQUIRED;?>:&nbsp; </td>

                          <td><input type="password" name="conf_pwd" size="32" maxlength="30" value="<?=$_POST['conf_pwd']?>">

                          </td>

                        </tr>

                        

                      </table>





</fieldset>

</td>

					  </tr>

					 <tr>

					  <td colspan="2" >



<fieldset>

<legend>Nike Account Info</legend>

<table width="100%" align="center" cellpadding="2">

                        <tr>

                          <td width="40%" align="right">Nike Login:&nbsp; </td>

                          <td><input type="text" name="nike_login" size="32" maxlength="100" value="<?=$_POST['nike_login']?>">

                          </td>

                        </tr>

                        <tr>

                          <td width="40%" align="right">Nike Password:&nbsp; </td>

                          <td><input type="password" name="nike_password" size="32" maxlength="30" value="<?=$_POST['nike_password']?>">

                          </td>

                        </tr>

                        <tr>

                          <td align="right">Confirm Password:&nbsp; </td>

                          <td><input type="password" name="nike_conf_pwd" size="32" maxlength="30" value="<?=$_POST['nike_conf_pwd']?>">

                          </td>

                        </tr>

                        

                      </table>





</fieldset>

</td>

					  </tr> 

					<tr>

					  <td colspan="2" >







<fieldset><legend>Additional Info</legend>



<table width="100%" align="center" cellpadding="2">

                        

						 <?

							foreach($optionMenus as $key => $data){

					   ?>

                        <tr>

                          <td width="40%" align="right" valign="top"><?=$data?> :&nbsp; </td>

                          <td valign="top"><? if($key == $siteMasterMenuConfig['JOBCAT']){  ?>

                                        <select name="<?='option_'.$key;?>" class="paragraph">

                                            <option value="">--Select--</option>

                                            <? 	$string = '';

										foreach($jobArray as $w => $data){

												$string .=  '<option value="'.$w.'"';

												if($w == $_POST['option_'.$key])

													$string .= ' Selected';

												$string .= '>'.$data.'</option>';

										}

										echo $string;	

								?>

                                        </select>

                                        <? }

									elseif($key == $siteMasterMenuConfig['SPORTSCAT']){		

										$optionSport = explode(',',$_POST['option_'.$key]);

										

								?>

                                        <select multiple="multiple" name="sport[]" size="10" class="paragraph">

                                            <option value="">--Select--</option>

                                            <? 	$string = '';

										foreach($sportArray as $w => $data){

												$string .=  '<option value="'.$w.'"';

												if(in_array($w,$optionSport))

													$string .= ' Selected';

												$string .= '>'.$data.'</option>';

										}

										echo $string;	

								?>

                                        </select>

                                        <?

							  			}else{		

								?>

                                        <input name="<?='option_'.$key;?>" type="text"  value="<?=$_POST['option_'.$key]?>"/>

                                        <? }?>

                                </td>                

                        </tr>

						<?

							}

						?>

						<tr>

					        <td align="right">Weight<?php echo REQUIRED;?>:</td>

					        <td><input name="user_weight_value" type="text"  value="<?=stripslashes($_POST['user_weight_value'])?>"/>

					            <select name="user_weight_unit" class="paragraph">

                                    <?

									 	foreach($weightUnits as $key => $data){

									 ?>

                                    <option value="<?=$key?>" <? if($_POST['user_weight_unit'] == $key) echo 'selected';?>>

                                        <?=$data?>

                                        </option>

                                    <?

									 		}

									 ?>

                                </select></td>

					        </tr>

					    <tr>

					        <td align="right">Height<?php echo REQUIRED;?>:</td>

					        <td><input name="user_height_value" type="text"  value="<?=stripslashes($_POST['user_height_value'])?>"/>

					            <select name="user_height_unit" class="paragraph">

                                    <?

									 	foreach($heightUnits as $key => $data){

									 ?>

                                    <option value="<?=$key?>" <? if($_POST['user_height_unit'] == $key) echo 'selected';?>>

                                    <?=$data?>

                                    </option>

                                    <?

									 		}

									 ?>

                                </select></td>

					        </tr>

							<tr>

                      <td width="40%" align="right">Subscribe Newsletter<?php echo REQUIRED;?>:&nbsp;</td>

                      <td width="60%"><input type="radio" name="user_newsletter" id="active" value="1" <?php echo $act_user_newsletter ;?>>

    Yes

      <input type="radio" name="user_newsletter" id="inactive" value="0" <?php echo $inact_user_newsletter;?>>

    No </td>

					  </tr>

					<? if($_SESSION['usertype'] != 3){ ?>

					<tr>

                      <td width="40%" align="right">Discount Status<?php echo REQUIRED;?>:&nbsp;</td>

                      <td width="60%"><input type="radio" name="user_discount_status" id="active" value="1" <?php echo $act_discount_status ;?>>

    Active

      <input type="radio" name="user_discount_status" id="inactive" value="0" <?php echo $inact_discount_status;?>>

    Inactive </td>

					  </tr>

					  <tr>

                      <td width="40%" align="right">Refferal Code Status<?php echo REQUIRED;?>:&nbsp;</td>

                      <td width="60%"><input type="radio" name="user_refferal_status" id="active" value="1" <?php echo $act_refferal_status ;?>>

    Active

      <input type="radio" name="user_refferal_status" id="inactive" value="0" <?php echo $inact_refferal_status;?>>

    Inactive </td>

					  </tr>

					<tr>

                      <td width="40%" align="right"> Status<?php echo REQUIRED;?>:&nbsp;</td>

                      <td width="60%"><input type="radio" name="user_status" id="active" value="1" <?php echo $act_status;?>>

    Active

      <input type="radio" name="user_status" id="inactive" value="2" <?php echo $inact_status;?>>

    Inactive </td>

					  </tr>

					  <? }?>

</table>

</fieldset>

</td>

					  </tr>

					<tr>

					  <td colspan="2" > 



					<?php

					

						if(!$_REQUEST['userId']){

					?>

					<tr height="40">

						<td colspan="2" align="center" valign="bottom">

							<input type="submit" name="add" value="&nbsp;&nbsp;Add&nbsp;&nbsp;">												</td>

					</tr>

					<?php

						}else{

					?>

					<tr height="40">

						<td colspan="2" align="center">

							<input type="submit" name="update" value="Update">						</td>

					</tr>

					<?php

						}

					?>

				    </tbody>

			 	  </table>

			   

			   <input type="hidden" name="userId" value="<?=$_REQUEST['userId']?>">

			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">

			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">

			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">

			   <input type="hidden" name="field" value="<?=$_REQUEST['field']?>">

				<input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">

				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">

				<? if($_POST['user_status'] == 3) { ?>

				<input type="hidden" name="adminstatus" value="<?=$_POST['user_status']?>">

				<? }?>

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

    <TD valign="top" align=left class="topBarColor" colspan="3">&nbsp;</TD>

  </TR>

      </TABLE>

        <?php include_once("footer.php");?>

	</td></tr></table>	

</body>

</html>