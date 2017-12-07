<?php
/**************************************************************************** 
   Project Name		::> Jiwok 
   Module 			::> Admin-Trainers add/edit delete management
   Programmer		::> Vijay
   Date				::> 06-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit Trainers  .
  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.trainer.php');
	include_once('../includes/classes/class.General.php');
	include_once('../includes/arrays_registration.php');

    
	$heading = "Trainers";
	$errorMsg	=	array();
	
	//setiing the default languge as english other vice the languge will be the selected one fromm the dropdrown 
	if($_REQUEST['langId']!=""){
	  	$lanId=$_REQUEST['langId'];
	}
	else{
	  	$lanId=1; 
	}
		
   	$objTrainer	= 	new Trainer(1);
	$objGen   	=	new General();
		
		
	/* Take all label name from label_manager table with menumaster_id = $genreMenuMasterId  */
	$genreMenus	= $objTrainer->_getGenreMenus($siteMasterMenuConfig['GENRE_ID'],$lanId);
	$voiceMenus	= $objTrainer->_getOptionalMenus($siteMasterMenuConfig['VOICE'],$lanId);	
	/* Take all label name from label_manager table with menumaster_id = $userOptionMenuMasterId  */
	$optionMenus	= $objTrainer->_getOptionalMenus($siteMasterMenuConfig['USER_OPTIONAL_FIELDS'],$lanId);
	
	$weightUnits	= $objTrainer->_getOptionalMenus($siteMasterMenuConfig['WEIGHT'],$lanId);
	
	$heightUnits	= $objTrainer->_getOptionalMenus($siteMasterMenuConfig['HEIGHT'],$lanId);
	
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
					if($objTrainer->_mailid_exist(trim($_POST['user_email']),$_REQUEST['userId']))
					$errorMsg[] = "Email already exist ";
				}else{
					if($objTrainer->_mailid_exist(trim($_POST['user_email']),''))
					$errorMsg[] = "Email already exist ";
				}
		}
		if(trim($_REQUEST['user_age_day'])==0 || trim($_REQUEST['user_age_month'])==0 || trim($_REQUEST['user_age_year'])==0)
			$errorMsg[]	=	"Date of birth required";
		if(trim($_REQUEST['user_address'])=='')
			$errorMsg[]	=	"Address required";
		if(!trim($_REQUEST['user_city']))
			$errorMsg[]	=	"City required";
		if(!trim($_REQUEST['user_state']))
			$errorMsg[]	=	"State required";
		if(trim($_REQUEST['user_country'])==''||trim($_REQUEST['user_country'])==0)
			$errorMsg[]	=	"Country required";
		if(trim($_REQUEST['user_zip'])==''||trim($_REQUEST['user_zip'])===0)
			$errorMsg[]	=	"Zip Code required";
		elseif(!is_numeric(trim($_REQUEST['user_zip'])))
			$errorMsg[] = "Zip code is not valid";
		if(trim($_POST['user_username']) == "")
			$errorMsg[] = "Username required";
		else{
				if($_REQUEST['update']){
					if($objTrainer->_username_exist(trim($_POST['user_username']),$_REQUEST['userId']))
					$errorMsg[] = "User login already exist ";
				}else{
					if($objTrainer->_username_exist(trim($_POST['user_username']),''))
					$errorMsg[] = "User login already exist ";
				}
		}
		

		if(trim($_POST['user_password']) == "")
			$errorMsg[] = "Password required";
	
	
		if(trim($_POST['conf_pwd']) == "")
			$errorMsg[] = "Confirmation Password required";
	
		
		if(trim($_POST['conf_pwd']) != trim($_POST['user_password']))	
			$errorMsg[] = "Password mismatch";

		foreach($genreMenus as $key => $data){;
			if($_POST['genre_'.$key])
				$genreArray[]	=	$key;
			
		}


		if(count($genreArray) < GENRE_MIN_REQUIRED){
			$errorMsg[] = "Select atleast ".GENRE_MIN_REQUIRED." genres";
		}
		
		if($_FILES['user_photo']['name'] != ""){
					if(!$objGen->_checkUploadImage($_FILES['user_photo']['type'])){
							$errorMsg[]	= "Image format is not valid";
					}
			}	
			
	   if(!trim($_REQUEST['user_weight_value']))
                $errorMsg[] =   "Weight required";
             if(!trim($_REQUEST['user_height_value']))
                $errorMsg[] =   "Height required";   
		/* *****IF THERE IS NO ERROR ...START ADD/UPDATE PROCCESS***** */
		if(count($errorMsg)==0){
				$_POST['user_dob'] = $_REQUEST['user_age_day'].'/'.$_REQUEST['user_age_month'].'/'.$_REQUEST['user_age_year'];
				unset($_POST['user_age_year']);
				unset($_POST['user_age_month']);
				unset($_POST['user_age_day']);

				$sp = $_POST['sport'];
				
				if($_POST['add']){
						$_POST = $objGen->_clearElmtsWithoutTrim($_POST);
						if($_FILES['user_photo']['name'] != ""){
							$fileName	= uniqid();
							$extension	= end(explode(".",$_FILES['user_photo']['name']));
							$nextUpload = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/',$fileName,115,40);
							$fileName = $fileName.".".$extension;
							$_POST['user_photo']	= $fileName;
					    }
						$objTrainer->_insertTrainer($_POST,$lanId,$sp);
			
						header("Location:list_trainers.php?status=success_add&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']); 
				}
				
				
				if($_POST['update']){
					$userId		=	$_REQUEST['userId'];
					$_POST		=	$objGen->_clearElmtsWithoutTrim($_POST);
					if($_FILES['user_photo']['name'] != ""){
							$fileName	= uniqid();
							$extension	= end(explode(".",$_FILES['user_photo']['name']));
							$nextUpload = $objGen->_fileUploadWithImageResize('user_photo','../uploads/users/',$fileName,115,40);
							$fileName = $fileName.".".$extension;
							$_POST['user_photo']	= $fileName;
							if($_POST['user_photo'] !="" && is_file("../uploads/users/".$_POST['current_photo'])){
							unlink("../uploads/users/".$_POST['current_photo']);
							}
					}
					else{
							$_POST['user_photo']	= $_POST['current_photo'];
					}
				
					$objTrainer->_updateTrainer($userId,$_POST,$lanId,$flag,$sp);
					header("Location:list_trainers.php?status=success_update&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
				}	
				
		}
		/* *****END OF EDD/UPDATE PROCCESS***** */
	}

//Retrieving Data from database
	if($_REQUEST['userId'] && !$_POST['user_status'])
	{
	$selectQuery	=	"select * from user_master where user_id=".$_REQUEST['userId'];
	$result 		= 	$GLOBALS['db']->getRow($selectQuery,DB_FETCHMODE_ASSOC);
		while(list($key,$value) = each($result)){
			$_POST[$key] = $objGen->_output($value);
			$dob= explode("/",$_POST['user_dob']);
			$_POST['user_age_year']  = $dob[2];
			$_POST['user_age_month'] = $dob[1];
			$_POST['user_age_day'] 	 = $dob[0];
			if($key=='user_password'){
				$_POST['conf_pwd']		=	$objGen->_output($objGen->_decodeValue($value));
				$_POST['user_password']	= 	$objGen->_output($objGen->_decodeValue($_POST['user_password']));
			}
					
		}
	
	
		$genreQuery	= "SELECT user_options.menu_id,user_options.menu_value FROM user_options,menus,menu_master WHERE user_options.usermaster_id =".$_REQUEST['userId']." and menus.menu_id=user_options.menu_id and menu_master. menumaster_id=".$siteMasterMenuConfig['GENRE_ID']." and  menu_master. menumaster_id=menus.menumaster_id";

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
		}
		$currentImage	= $_POST['user_photo'];
	}
/* *************Decides wich should be selected *********8 */
	
	if($_REQUEST['userId']){
		if($_POST['user_status'] == 1){
			$act_status   = "Checked";
		}else{
			$inact_status = "Checked";
		}
	}else{
		if($_POST['user_status'] == 1){
			$act_status = "Checked";
		}elseif($_POST['user_status'] == 2){
			$inact_status = "Checked";
		}else{
			$act_status   = "Checked";
		}
	}

	$countriesArray = $objTrainer->_getCountries();
	
	//getting the size of the imaGE TO RESIZE IN POP
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
<TABLE cellspacing='0' cellpadding='0' width="779" align="center" border="1px" bordercolor="#E6E6E6">
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
                       
			  			   <form action="addedit_trainers.php" method="post" enctype="multipart/form-data" name="frmTrainers">
                      
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
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_trainers.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
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
                       <td>
					   <select name="user_age_day" class="paragraph" style="width:60px; background-color:#F3F3F3;">
                           <option value="0">--Day--</option>
                            <?
								for($i=1; $i<=31; $i++){
									$string = "<option value={$i}";
									if($i == $_POST['user_age_day']){
										$string .= " selected";
									}
									$string .= ">{$i}</option>";
									echo $string;
								}
							
							?>
                         </select> 
					    <select name="user_age_month" class="paragraph" style="width:80px; background-color:#F3F3F3;">
                           <option value="0">--Month--</option>
                           <?
								for($i=1; $i<=count($siteMonthList); $i++){
									$string = "<option value={$i}";
									if($i == $_POST['user_age_month']){
										$string .= " selected";
									}
									$string .= ">{$siteMonthList[$i]}</option>";
									echo $string;
								}
							
							?>
                         </select> 
					   <select name="user_age_year" class="paragraph" style="width:80px; background-color:#F3F3F3;">
                           <option value="0">--Year--</option>
                           <?
								for($i=1900; $i<=date('Y'); $i++){
									$string = "<option value={$i}";
									if($i == $_POST['user_age_year']){
										$string .= " selected";
									}
									$string .= ">{$i}</option>";
									echo $string;
								}
							
							?>
                         </select>                       </td>
                     </tr>
                     <tr>
                       <td width="40%" align="right">Address<?php echo REQUIRED;?>:&nbsp; </td>
                       <td><textarea name="user_address" rows="3" cols="23"><?=$_POST['user_address']?></textarea>                                          </td>
                     </tr>
                     <tr>
                       <td align="right">City<?php echo REQUIRED;?>:&nbsp; </td>
                       <td><input type="text" name="user_city" id="user_city" size="32" value="<?=$_POST['user_city']?>">                       </td>
                     </tr>
                     <tr>
                       <td width="40%" align="right">State<?php echo REQUIRED;?>:&nbsp; </td>
                       <td><input type="text" name="user_state" id="user_state" size="32" value="<?=$_POST['user_state']?>">                       </td>
                     </tr>
                     <tr>
                       <td width="40%" align="right">Country<?php echo REQUIRED;?>:&nbsp; </td>
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
                       <td width="40%" align="right">Postal Code<?php echo REQUIRED;?>:&nbsp; </td>
                       <td>
                           <input name="user_zip" id="user_zip" value="<?=$_POST['user_zip']?>" class="paragraph" style="width:180px; background-color:#F3F3F3;">
                          		</td>
                     </tr>
                     <tr>
                       <td width="40%" align="right">Prefered Language <?php echo REQUIRED;?>:&nbsp; </td>
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
                       <td width="40%" align="right">Prefered Voice <?php echo REQUIRED;?>:&nbsp; </td>
                       <td>
					<select name="user_voice" id="user_voice" class="paragraph" style="width:180px; background-color:#F3F3F3;" >
					
	
					   <?

						foreach($voiceMenus as $key=>$data){
					   		
					  ?>
					   <option value="<?=$key?>" <? if($_POST['user_voice']==$key) print 'selected'; ?>><?=$data?></option>
					   <? } ?>  
					   <option value="" <? if($_POST['user_voice']=='') print 'selected'; ?>>Male/Female</option>	
					   </select>
					   					 </td>
                     </tr>
                     <tr>
                       <td width="40%" align="right">&nbsp;</td>
                       <td><font color="#990000">Select at least two genre</font></td>
                     </tr>
		    <tr>
                          <td align="right">Music Like<?php echo REQUIRED;?>:&nbsp; </td>
                          <td>


<table>
							<tr>
							<?php
								$count=0;
								foreach($genreMenus as $key=>$value){
								$count++;
							?>
								<td>
								<input type="checkbox" name="genre_<?=$key?>" id ="genre_<?=$key?>" size="30" value = "<?=$key;?>" <?php if($_POST['genre_'.$key]) echo "Checked"?>>								</td><td width="166">
								<?=$value;?>
								</td>
							<?php
								if($count % 2 == 0)
									echo "</tr><tr>";
								}
							?>
							</table>                          </td>
                        </tr>
		    <tr>
		      <td align="right">Trainer Photo </td>
		      <td>:
		         
		          <input type="file" name="user_photo">
		          <input type="hidden" name="current_photo" value="<?=$currentImage?>"/>
				  <? if($currentImage != ""){?>
				  <a href="#" onClick="openNewWindow('../uploads/users/<?=$currentImage?>','windowname',<?=($imageDetails[0]+100);?>,<?=($imageDetails[1]+50);?>)">View</a>
				  <? }?>
				  </td>
		      </tr>
                   </table>


</fieldset>



				    
				   </td>
				   </tr>
					<tr>
					  <td colspan="2" >

<fieldset><legend>Account Info</legend>
<table width="100%" align="center" cellpadding="2">
                        <tr>
                          <td width="40%" align="right">Username<?php echo REQUIRED;?>:&nbsp; </td>
                          <td><input type="text" name="user_username" size="32" maxlength="100" value="<?=$_POST['user_username']?>">
                          </td>
                        </tr>
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
												$string .=  '<option value="'.$data.'"';
												if($data == $_POST['option_'.$key])
													$string .= ' Selected';
												$string .= '>'.$data.'</option>';
										}
										echo $string;	
								?>
                                        </select>
                                        <? }
									elseif($key == $siteMasterMenuConfig['SPORTSCAT']){		
										$optionSport = explode('#',$_POST['option_'.$key]);
										
								?>
                                        <select multiple="multiple" name="sport[]" size="10" class="paragraph">
                                            <option value="">--Select--</option>
                                            <? 	$string = '';
										foreach($sportArray as $w => $data){
												$string .=  '<option value="'.$data.'"';
												if(in_array($data,$optionSport))
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
					        <td align="right">Permission Status </td>
					        <td width="60%"><input type="radio" name="permission_status" id="active" value="1" <? if($_POST['permission_status'] == 1) echo "Checked"?>>
					        Yes
					            <input type="radio" name="permission_status" id="permission_status" value="0" <? if($_POST['permission_status'] != 1) echo "Checked"?>>
					            No </td>
					    </tr>
					    <tr>
                      <td width="40%" align="right"> Status<?php echo REQUIRED;?>:&nbsp;</td>
                      <td width="60%"><input type="radio" name="user_status" id="active" value="1" <?php echo $act_status;?>>
    Active
      <input type="radio" name="user_status" id="inactive" value="2" <?php echo $inact_status;?>>
    Inactive </td>
					  </tr>
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
							<input type="submit" name="add" value="&nbsp;&nbsp;Add&nbsp;&nbsp;" style="cursor:pointer;">												</td>
					</tr>
					<?php
						}else{
					?>
					<tr height="40">
						<td colspan="2" align="center">
							<input type="submit" name="update" value="Update" style="cursor:pointer;">						</td>
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
		</td>
		</tr>
		</table>
</body>
</html>