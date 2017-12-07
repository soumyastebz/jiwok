<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-News add/edit delete management
   Programmer	::> Raneesh
   Date		::> 01-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit news  .
  
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.Languages.php');
    
	$heading = "Languages";
	$errorMsg	=	array();
   

	$lanObj = 	new Language();
	$genObj   =	new General();
	$dbObj    =	new DbAction();
	$lanObj->_getLanguageArray();
	$languageArray=$siteLanguagesConfig;
	    //validation strts herefor feilds 
		if($_POST['add']||$_POST['update']){
					
				if(trim($_POST['languageName'])=='')
					$errorMsg[] = "Language Name is empty";
					
				if($_FILES['languageFlag']['type'] == '' && $_POST['add']){
					$errorMsg[]	= "Select an image to upload";
				}
				elseif(!$genObj->_checkUploadImage($_FILES['languageFlag']['type']) && $_POST['add']){
						$errorMsg[]	= "Image format not supported";
				}
			    //form validation ends here	
				
					
		
			if(count($errorMsg)==0){
			        //after successful validaation checking the existence in the database		
					
					if($_POST['add']){
					    //checking whethere the title exist or not
					     	$query = "SELECT language_name  FROM languages WHERE language_name ='".addslashes($_POST['languageName'])."'";	
									$result = $GLOBALS['db']->query($query);
	
							$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
							if(DB::isError($result)) 
								echo $result->getDebugInfo();
							else if(count($result) >= 1)
								$errorMsg[] = "Language name already exist";
						
                   			
					}	
			
					if($_POST['update']){
					
					        $lanId=$_POST['lanId'];
					    
			           
							 
							$query = "SELECT language_name FROM languages WHERE language_id !=".$lanId." AND language_name='".addslashes($_POST['languageName'])."'";	
									$result = $GLOBALS['db']->query($query);
	
							$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_ASSOC);
							if(DB::isError($result)) 
								echo $result->getDebugInfo();
							else if(count($result) >= 1)
								$errorMsg[] = "language name already exist";
							
						
						

					}
			
			}
			
           
			if(count($errorMsg)==0){
				//Inserting the data in to the database			
				if($_POST['add']){
						
						$languageName			=$_POST['languageName'];	
						$lanObj->_insertLanguage($languageName);
						$imageIdQuery 			= "SELECT MAX(language_id) as imageId FROM languages";
						$res		  					= $GLOBALS['db']->query($imageIdQuery);
						while ($res->fetchInto($row)){
   							$imageId	= $row[0];
						}
						$ext 							= end(explode(".",$_FILES['languageFlag']['name']));
						$imageName		 		= $imageId.".".$ext;
						
						$genObj->_upload($_FILES['languageFlag']['tmp_name'],"../images/flags/".$imageName);
						
						$query 	= "UPDATE languages SET language_flag = '".$imageName."' WHERE language_id = {$imageId}";
						$res		=	$GLOBALS['db']->query($query);
						header("Location:list_languages.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
								
				}//end of adding
						
						
				if($_POST['update']){
								
											
						$languageName=$_POST['languageName'];
						$lanId=$_POST['lanId'];
						
						$lanObj->_updateLanguage($languageName,$lanId);
						if($_FILES['languageFlag']['tmp_name'] != ""){
							list($name,$ext) = explode(".",$_FILES['languageFlag']['name']);
							$imageName		 = $lanId.".".$ext;
							$genObj->_upload($_FILES['languageFlag']['tmp_name'],"../images/flags/".$imageName);				
							$query = "UPDATE languages SET language_flag = '".$imageName."' WHERE language_id = {$lanId}";
							$res	=	$GLOBALS['db']->query($query);
						}
												
						header("Location:list_languages.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
						
				}
		}
		
			
	} 
	//end of add/edit

//if edit following will execute on loading
	
if($_REQUEST['lanId'] && !$_POST['update']){
$lanId=$_REQUEST['lanId'];
$languageDetails	= $lanObj->_getLanguageDetails($lanId);	
$languageName 		= $genObj->_output($languageDetails['language_name']);
$languageFlagPath	= "../images/flags/".$languageDetails['language_flag'];
}


		
	
	
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
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
                       
			  			   <form action="addedit_languages.php" method="post" enctype="multipart/form-data" name="frmlanguages">
                      
				  <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
				  <tr>
						<td height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
					</tr>
					<?php 
						if($errorMsg){ ?>
					<tr>
						<td align="center"><? print $genObj->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
					</tr>
					<?php } ?>

					<TR> 
					<TD align="left">
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_languages.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
						</tr></table>
					</TD>
					</TR>
					<TR><TD colspan="2" align="right"><?php echo REQUIRED_MESSAGE;?></TD></TR>
				  </table>
                              
				  <TABLE  cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
				   <tr><td colspan="2">
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
				             
					<tr>
						<td colspan="3" align="left" style="padding-left:20px;" class="sectionHeading"></td>
						</tr>
					<tr >
					  <td colspan="3" align="center" >&nbsp;</td>
					  </tr>
					<tr>
					  <td width="33%" height="24" align="right" valign="top">&nbsp;</td>
					  <td width="13%" align="left" valign="top">&nbsp;</td>
					  <td width="54%" align="left" valign="top"><? if(isset($_REQUEST['lanId'])) {?><img src="<?=$languageFlagPath?>"><? }?></td>
					</tr>
					<tr>
					  <td height="24" align="right" valign="top">&nbsp;</td>
					  <td colspan="2" align="left" valign="top">&nbsp;</td>
					  </tr>
					<tr>
					  <td height="24" align="right" valign="top">Language<?php echo REQUIRED;?> :&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="text" name="languageName" size="30" maxlength="100" value="<?php if($_POST['languageName']) echo $_POST['languageName']; else echo $languageName;  ?>"></td>
					</tr>
					<tr>
					  <td height="24" align="right" valign="top">Flag image<?php echo REQUIRED;?>:&nbsp;</td>
					  <td colspan="2" align="left" valign="top"><input type="file" name="languageFlag">
                                                                <input type="hidden" name="currentFlag" value="<?=$languageDetails['language_flag']?>">
                      </td>
					  </tr>
					</table>
				   
				  
				  
				   </td></tr>
					
					
					
					<tr>
						<td width="40%" height="38" align="right" >&nbsp;						</td>
						<td >&nbsp;</td>
					</tr>
					<?php
					
						if(!$_REQUEST['lanId']){
					?>
					<tr height="40">
						<td colspan="2" align="center" valign="bottom">
							<input type="submit" name="add" value="&nbsp;&nbsp;Add&nbsp;&nbsp;">
									</td>
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
			   <input type="hidden" name="lanId" value="<?=$_REQUEST['lanId']?>"> 
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
			   <input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                        <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
						<input type="hidden" name="newsId" value="<?=$_REQUEST['newsId']?>">
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
</body>
</html>