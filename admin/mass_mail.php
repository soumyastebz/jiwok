<?php
/**************************************************************************** 
   Project Name	::> Jiwok
   Module 		::> Admin- Mass Mail  Management
   Programmer	::> Sreejith E C
   Date			::> 07.02.2007
   
   DESCRIPTION::::>>>>
   This  code used for Mass Mail Functionality  .
*****************************************************************************/
	error_reporting(1);
	include_once('includeconfig.php');
	include_once('../includes/classes/class.massmail.php');
	include_once('../includes/classes/class.member.php');
	
	$heading 		= 'Mailing System';
	$errorMsg		= array();
		
	$objMassmail	= new Massmail(1);
	$objMember		= new Member(1);
	$objGen			= new General();
	
	$newsletterList = $objMassmail->_getAllNewsletters();
	$countriesArray = $objMember->_getCountries();
	$ageGroupArray  = $objMassmail->_getAgeGroup();
	$genreArray	    = $objMassmail->_getGenre();
	
	if($_REQUEST['Send'])
	{
			//compose all recipient type
			$type	 =	$_REQUEST['recepient_type'];
			switch($type)	{
				case 1:
					$recipient_list		=	$objMassmail->_getAllMembers();
					break;
				case 2:
					$recipient_list		=	$objMassmail->_getAllTrainers();
					break;
				case 3:
					$recipient_list		=	$objMassmail->_getAllUsers();
					break;
				case 4:
					$recipient_list		=	$objMassmail->_extractUsers($_POST);				
					break;	
			}
			//print_r($recipient_list);
			// compose bulk mail content 
			if($_REQUEST['mail_type']=='LETTER'){
				$letter_id	=	$_REQUEST['newsletter'];
				$query		=	"select * from newsletter_manager where newslettermaster_id=".$letter_id;
				$res 		=	$GLOBALS['db']->getAll($query,DB_FETCHMODE_ASSOC);
				
				foreach($res as $row){
					$title					=	$objGen->_output($row['manager_title']);
					$body					=	$objGen->_output($row['manager_body']);
					$content[$row['language_id']]		=	"<table><tr><td align='left'>$title</td></tr>"."<tr><td align='center'>$body</td></tr></table>";
					
				}
			
			}
			else{
				$content[1]	=	$_REQUEST['msg'];
			}
			if(!count($content)){
				$content[1]	=	"<div align='center'><br><font color='red'>No message </font></div>";
			}
			$invalidId	=	array();// to store invalid mail ids while checking
			if(count($recipient_list[0]) == 0)
					$errMsg	= "No mail id to send message";
			elseif($_REQUEST['mail_type']=='CUSTOM' && trim($_REQUEST['msg'])==''){
					$errMsg	= "No message to send";
			}
			else{
            
			  	foreach($recipient_list as $id)	{
			   		if(!$objGen->_validate_email($id['user_email'])){  //check whether mail id is valid - If not valid , it store to array
						$invalidId[]=$id['user_email'];
					}
				}
				if(count($invalidId)==0){ 
					
					$displayMes='START_PROCESS';
					
					/* Send mail to all recipients */
					if($displayMes=='START_PROCESS'){
						$batch=0;
						foreach($recipient_list as $mailid){
							if($_REQUEST['mail_type'] != 'LETTER' || !count($content)){
								$content[$mailid['user_language']] = $content[1];
							} 
							$mailArray		 =	$objMassmail->_fetchSettingsEmail();
							
							$objMassmail->_sendAuthenticationMail_plain($mailid['user_email'],$mailArray['CONTACT_MAIL'],$mailArray['RETURN_MAIL'],$mailArray['BOUNCE_MAIL'],"Jiwok Newsletter",$content[$mailid['user_language']],$configData['SITE_URL']);
							
							$batch++;
							if($batch==25){
								sleep(1);
								$batch=0;
							
							}
						}
						$displayMes	= 'END_PROCESS';
						$errMsg="Mail sending  operation is completed";
					}
							
				}//end if
			}// end else
	}// end  send
	
?>
<html><head><title><?=$admin_title?></title>
<? include_once('metadata.php');?>
<script language="javascript">
function send()	{
		var frm=document.mailform;
		var count=0;
		for(i=0;i<=4;i++)
		{
				if (document.mailform.recepient_type[i].checked)
				{
					
					var count=count+1;
				}				
		}
		
		if(count==0)
		{alert ("Please select any type");
		return false;
		}
	}
function show(){
		document.getElementById("customList").style.display = "block";
}
function hide(){
		document.getElementById("customList").style.display = "none";
}
function check(){
	if(mailform.recepient_type[3].checked == true){
		show();
	}
	else{
		hide();
	}
}
</script>
<body  class="bodyStyle" onLoad="check()">
<table cellSpacing=0 cellPadding=0 width="779" align="center" border="1px" bordercolor="#E6E6E6">
  <tr>
    <td vAlign=top align=left bgColor=#ffffff><? include("header.php");?></td>
  </tr>
  <tr height="5">
    <td vAlign=top align=left class="topBarColor">&nbsp;</td>
  </tr>
  <tr>
    <td vAlign="top" align="left" height="340"> 
      <table cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <tr> 
          <td vAlign=top align=left width="175" rowSpan="2"> 
            <table cellSpacing="0" cellPadding="0" width="175" border=0>
              <tr> 
                <td valign="top">
				 <table cellSpacing=0 cellPadding=2 width=175  border=0>
                    <tbody> 
                    <tr valign="top"> 
                      <td valign="top"><? include ('leftmenu.php');?></td>
                    </tr>
                    
                    </tbody> 
                  </table>
				</td>
              </tr>
            </table>
          </td>
          <td vAlign=top align=left width=0></td>
         
        </tr>
        <tr> 
          <td valign="top" width="1067"><!---Contents Start Here----->
		  
		  
            <table cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <tr> 
                <td class=smalltext width="98%" valign="top">
				
				  <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
                <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
              </tr>
              <tr> 
                <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                <td valign="top"> 
				
				
				
				<table cellSpacing=0 cellPadding=0 border=0 align="center">
                    <tr> 
                      <td vAlign=top width=564 bgColor=white> 
                       
			  			   <form name="mailform" action="mass_mail.php" method="post">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0 >
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"></td>
					</tr>
					<?php 
						if($errorMsg){
					?>
					<tr>
						<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>
					
					<tr> 
					<td align="left"><fieldset>
                                    <legend>Mass Mailer</legend>
                                    <table cellSpacing=0 cellPadding=0 border=0 align="center">
                      <tr>
                                        <td vAlign=top align=right width=21>&nbsp;</td>
                                        <td width=10>&nbsp;</td>
                      </tr>
                      <tr>
                        <td width=21  bgColor=white></td>
                        <td vAlign=top width="95%" bgColor=white align="center"><!-- contents start here -->
                            <br>
                          <br>
                            <?
					 if($errMsg)
						print "<div align='center'><font class='successAlert'>$errMsg</font></div>";
						elseif(count($invalidId)>0){?>
                            <div align='center' style="height:50; width:600; overflow:auto;">
                              <table>
                                <tr>
                                  <td class="alertHead">Wrong Mail Id List</td>
                                </tr>
                                <tr>
                                  <td><? 
							$carrId=1;
							$countId=1;
                            if(count($invalidId) != 0){
							foreach($invalidId as $arrId) {
										if($carrId!=1){ echo ",&nbsp;";}
										echo $countId.".&nbsp;".$arrId;
										 $carrId++; $countId++;
										 if($carrId>5){ echo "<br>";$carrId=1;}
							} } ?></td>
                                </tr>
                              </table>
                            </div>
                          <? }?>
                            <table cellpadding="0" cellspacing="0" width="90%" align="center">
                              <tr>
                                <td>
								<fieldset>
                                  <legend>Mail Content
                                    <?=REQUIRED;?>
                                  </legend>
                                  <br>
                                                <table cellpadding="5" cellspacing="0" width="97%" align="center">
                                                  <tr> 
                                                    <td width="35%"><input type="radio" name="mail_type" value='LETTER' <? if(!$_REQUEST['mail_type']||$_REQUEST['mail_type']=="LETTER") echo 'checked';?>>
                                                      Newsletter</td>
                                                    <td width="65%"><select name="newsletter" class="paragraph">
                                                        <? foreach($newsletterList as $row){	?>
                                                        <option value="<?=$row['newsletter_id'];?>" <? if($_REQUEST['newsletter']==$row['newsletter_id']) echo 'selected';?>> <?=$objGen->_output($row['manager_title']);?> </option>
                                                        <? }	?>
                                                      </select></td>
                                                  </tr>
                                                  <tr> 
                                                    <td valign="top"><input type="radio" name="mail_type" value="CUSTOM" <? if($_REQUEST['mail_type']=='CUSTOM') echo 'checked';?>>
                                                      Custom Message</td>
                                                    <td><textarea cols="35" rows="4" name="msg"><?=$_REQUEST['msg']?></textarea></td>
                                                  </tr>
                                                </table>
                                  </fieldset>
                                    <br>
									<fieldset><legend>Recipients
									<?=REQUIRED;?>
									</legend>
									                  <table width="100%">
                                                        <tr> 
                                                          <td width="25%" height="40"> 
                                                            <label> 
                                                            <input type="radio" id="recepient_type" name="recepient_type" <? if($_REQUEST['recepient_type']==1) echo "checked";?> value="1" onClick="hide()">
                                                            Members</label> </td>
                                                               <td width="20%"> <input type="radio" id="recepient_type" name="recepient_type" <? if($_REQUEST['recepient_type']==3) echo "checked";?> value="3" onClick="hide()">
                                                            All </td>
							<td width="30%"> <input type="radio" id="recepient_type" name="recepient_type" <? if($_REQUEST['recepient_type']==4) echo "checked";?> value="4" onClick="show()">
                                                            Extract Tool </td>
                                                        </tr>
							<tr>
							   <td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3">
									<div id="customList" style="display:block">
										<table width="100%" border="0">
											<tr> 
                                                         					<td colspan="2"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Extract Tool</strong></td>
                                                        				</tr>
<tr>
                                                            					<td width="46%" align="right">User Type</td>
                                                            					<td width="54%">
													<select name="user_type" id="country" class="paragraph" style="width:180px; background-color:#F3F3F3;">
                                                              						<option value="0">--Select--</option>
                                                              						<? 
														while(list($type,$key) = each($siteUsersConfig)){
															$string = "<option value={$key}";
															if($key == $_POST['user_type']){
																$string .= " selected";
															}
															$string .= ">";
															if($key == 1) $string .= 'Member'; 
															$string .= "</option>";
															print $string;
															}		
													?>
                                                           						</select>
												</td>
                                                          				</tr>
														   <tr>
                                                            <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;
															<select name="type_country" class="paragraph" style="width:60px; background-color:#F3F3F3;">
																<option value="AND" <? if($_POST['type_country'] == "AND") echo "selected";?>>AND</option>
																<option value="OR" <? if($_POST['type_country'] == "OR") echo "selected";?>>OR</option>
                                                             </select>
															 </td>
                                                            </tr>
                                                          				<tr>
                                                            					<td width="46%" align="right">Country</td>
                                                            					<td width="54%">
													<select name="country" id="country" class="paragraph" style="width:180px; background-color:#F3F3F3;">
                                                              						<option value="0">--Select--</option>
                                                              						<? 
														while(list($code,$name) = each($countriesArray)){
															$string = "<option value={$code}";
															if($code == $_POST['country']){
																$string .= " selected";
															}
															$string .= ">{$name}</option>";
															print $string;
															}		
													?>
                                                           						</select>
												</td>
                                                          				</tr>
														   <tr>
                                                            <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;
															<select name="country_age" class="paragraph" style="width:60px; background-color:#F3F3F3;">
																<option value="AND" <? if($_POST['country_age'] == "AND") echo "selected";?>>AND</option>
																<option value="OR" <? if($_POST['country_age'] == "OR") echo "selected";?>>OR</option>
                                                             </select>
															 </td>
                                                            </tr>
                                                         <tr>
                                                            <td align="right">Age</td>
                                                            <td>
															<select name="age" style="background-color:#F3F3F3;" class="paragraph">
																  <option value="0">--Select--</option>
                                                              <? 
																	foreach($ageGroupArray as $data){
																		$string = "<option value={$data['label_name']}";
																		if($data['label_name'] == $_POST['age']){
																			$string .= " selected";
																		}
																		$string .= ">{$data['label_name']}</option>";
																		print $string;
																	}
															   ?>
															</select></td>
                                                          </tr>
														   <tr>
                                                            <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;
															<select name="age_genre" class="paragraph" style="width:60px; background-color:#F3F3F3;">
																<option value="AND" <? if($_POST['age_genre'] == "AND") echo "selected";?>>AND</option>
																<option value="OR" <? if($_POST['age_genre'] == "OR") echo "selected";?>>OR</option>
                                                             </select>
															</td>
                                                            </tr>
															<tr>
                                                            <td align="right">Music Like</td>
                                                            <td>
															<select name="genre" class="paragraph" style="background-color:#F3F3F3;">
																  <option value="0">--Select--</option>
                                                              <? 
																	foreach($genreArray as $data){
																		$string = "<option value={$data['labeltype_id']}";
																		if($data['labeltype_id'] == $_POST['genre']){
																			$string .= " selected";
																		}
																		$string .= ">{$data['label_name']}</option>";
																		print $string;
																	}
															   ?>
															</select>
															</td>
                                                          </tr>
														</table>
														</div>
														</td>
														</tr>
                                                      </table>
									</fieldset>
												
                                              </td>
                              </tr>
                            </table>
							<br>
                            <div align="center">
                              <input type="submit" name="Send" value="Send" >
                              
                            </div>
                          <!-- contents  end here-->
                        </td>
                                        <td width=10 background="images/box-rtmid.gif">&nbsp;</td>
                      </tr>
                      <tr>
                                        <td width=21>&nbsp;</td>
                                        <td width=564 background="images/box-bttmtrim.gif">&nbsp;</td>                <td width=10>&nbsp;</td>
                      </tr>
                    </table></fieldset></td>
					</tr>
					
				  </table><br>
			  			   </form>
                      </td>
                    </tr>
                  </table>
				  
				  
				  
				  
				  </td>
                <td background="images/side2.jpg">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
              </tr>
            </table>

                </td>
              </tr>
            </table>

          </td>
        </tr>
		 <tr height="2">
    <td vAlign=top align=left class="topBarColor" colspan="3">&nbsp;</td>
  </tr>
      </table>
        <?php include_once("footer.php");?>
</body>
</html>
