<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 	::> Admin-vocal add/edit delete management
   Programmer	::> Raneesh
   Date		::> 01-02-2007
   
   DESCRIPTION::::>>>>
   This  code used to add/edit vocals
*****************************************************************************/
	include_once('includeconfig.php');
	include_once('../includes/classes/class.vocals.php');
    $heading = "Vocals";
	$errorMsg	=	array();
	if($_REQUEST['langId'])
     $langId= $_REQUEST['langId'];
	else
	 $langId=1;
	 
    $vocalId=$siteMasterMenuConfig['VOCALS_ID'];//from config
	$vocalObj = 	new Vocals($langId,$vocalId);//from config
	$languageArray=$siteLanguagesConfig;
	
	$genObj   =	new General();
	$dbObj    =	new DbAction();
	
    //to replace the name exceptions in the console
	$pattern = array('/\s/','/\(/','/\)/','/\[/','/\]/','/\{/','/\}/');
  	$replace = array('\ ','\(','\)','\[','\]','\{','\}');   
       //validation strts herefor feilds 
		if($_POST['add']){
							
			
			foreach($languageArray as $key=>$val){
			
			    if(trim($_POST['vocalName_'.$key])=='')
			        $errorMsg[] = "No Vocal Name For ".$val;
				if($_POST['mp3file_'.$key] == "")
			        $errorMsg[] = "No MP3 file For ".$val;
				else{
					$ext =strtolower(end(explode('.',$_POST['mp3file_'.$key])));
					if($ext != 'mp3')
					   $errorMsg[] = "Invalid file format ".$ext." for".$val;
				
				
				}	
				
			    
			}		
			
		//form validation ends here	
		}
		if($_POST['update']){
          
			
			   
	       foreach($languageArray as $key=>$val){
		        //validating the name feild 
		        if(trim($_POST['vocalName_'.$key])=='')
			        $errorMsg[] = "No Vocal Name For ".$val;
		   }	
			
		}
			
         
		if(count($errorMsg)==0){
		       
				if($_POST['add']){
					    
						
						
					$isertId=$dbObj->_getId('vocal_relation','vocal_id ');
					$baseName = uniqid();//base name to store		
					foreach($languageArray as $key=>$val){		
						
							$direcrtryNameMp3       = '../uploadvocal/mp3/'.$val;//directry name for the mp3
							$direcrtryNameMp3Upload	= "../".$vocalPathConfig[$key];//mp3 folder to store the mp3 files 					
							$fName = $isertId.".~".$baseName.".mp3";
												
							
							/*to store the mp3 in the vocal folder 
							the process involved in this are, To relocate the files in the $direcrtryNameMp3 to the 
							$direcrtryNameMp3Upload and for the wave files $direcrtryNameWav to the $direcrtryNameWavUpload*/
							$fileMp3    = $direcrtryNameMp3."/".$_POST['mp3file_'.$key];
						    $newfileMp3 = $direcrtryNameMp3Upload.$fName;
							echo "Src=".$fileMp3." & Dest=".$newfileMp3;
							$copyflag = false;
							if (copy($fileMp3, $newfileMp3)){
								 $copyflag = true;//on successful copy of the mp3 file 
							}
							else{
							$errorMsg[] = "Error in copying the mp3 file for".$val;
							}
							
							
							if(count($errorMsg) == 0){
							    
								//inserting into the database 
								//the table coresponding to the add process are vocal realtion and vocal manager
								$element['vocal_id'] = $isertId;	
								//adding to the vocal relation table 
								$dbObj->_insertRecord('vocal_relation',$element);
								
								//adding to the vocal manager table
								$isertIdManager=$dbObj->_getId('vocal_manager','vocal_manager_id ');
								//inserting array
								$vocalManager['vocal_manager_id'] =$isertIdManager;
								$vocalManager['vocal_id']         =$isertId;
								$vocalManager['language_id']      =$key;
								$vocalManager['vocal_name']       =$_POST['vocalName_'.$key];	
								$vocalManager['vocal_file']      =$fName;
								
								$dbObj->_insertRecord('vocal_manager',$vocalManager);
								
							}
					    }
					
				    if($copyflag)
					    header("Location:list_vocals.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
							
				}//end of adding
						
						
				if($_POST['update']){
				    
					foreach($languageArray as $key=>$val){
                        
						
						//the following code will search for the new language that hase been added to the site 
						//most recently
						//the querry that search for the the vocal with the specified menuid and the languageid
						
						     $elementId= $_POST['elementId'];//menuid for the vocal
						     $languageId=$key;
											
						
						    
							 $element[' vocal_name']	 = $_POST['vocalName_'.$key];
							 $updateCondition = "vocal_id=".$elementId." AND language_id=".$languageId;
							 $dbObj->_updateRecord('vocal_manager',$element,$updateCondition);
							
						
						
					}					
					header("Location:list_vocals.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
				}
		 }
		
			
	 
	//end of add/edit

//if edit following will execute on loading
	
if($_REQUEST['elementId'] && !$_POST['update']){
$elementId= $_REQUEST['elementId'];
$sql = "SELECT vocal_name,language_id,vocal_file FROM vocal_manager WHERE vocal_id=".$elementId;
$vocalData = $dbObj->_getList($sql);
foreach($vocalData as $key => $val){
  $_POST['vocalName_'.$val['language_id']]= $val['vocal_name'];
  $_POST['vocal_file_'.$val['language_id']] = "../".$vocalPathConfig[$val['language_id']].$val['vocal_file'];
}

$langId= $_REQUEST['langId'];
   
}
if($_POST['update']){
$elementId= $_REQUEST['elementId'];
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
                       
			  			   <form name="frmVocal" enctype="multipart/form-data" action="addedit_vocals.php" method="post" >
                      
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
						
				   		<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_vocals.php?action=add&pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxforeach($languageArray as $key=>$val){rows']?>&field=<?=$_REQUEST['field']?>&type=<?=$_REQUEST['type']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
						<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
						</tr></table>
					</TD>
					</TR>
					<TR><TD colspan="2" align="center" class="ValidationSummary"></TD></TR>
				  </table>
                   <TABLE  cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
				   <tr><td colspan="2">
				   
				   
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
				             
					<tr>
						<td colspan="2" align="left" style="padding-left:20px;" class="news_header"></td>
						</tr>
					 <?php  $k=0;
					if(count($languageArray)>0){
						foreach($languageArray as $key => $val){ 
						 
					?>
					<tr>
					  <td colspan="2" align="right" >&nbsp;</td>
					  </tr>
					<tr>
					  <td height="24" align="right" valign="top" class="news_header">Vocal Name<?php echo REQUIRED;?> :&nbsp;</td>
					  <td align="left" valign="top"><input type="text" name="vocalName_<?=$key?>" value="<?=$genObj->_output($_POST['vocalName_'.$key])?>">&nbsp;<a href="<?=$_POST['vocal_file_'.$key]?>">Link to file</a></td>
					</tr>
					<? /*Selecting the files for the mp3 list*/
					$direcrtryName = '../uploadvocal/mp3/'.$val;
					$filePathArray = $genObj->_dirList($direcrtryName);
					?>
					<?php
					
						if(!$_REQUEST['elementId']){ 
					?>
					<tr> 
						<td width="40%" height="100%" align="right" valign="top"  class="news_header">Vocal file ( MP3) &nbsp;<?echo $val;?><?php echo REQUIRED;?> :&nbsp;						</td>
						<td align="left" valign="top"><!--<input type="file" name="vocalFile_<?=$key?>"   size="30" >--><select name="mp3file_<?=$key?>" ><option value="">Select One</option>
						<? if(count($filePathArray>0)){ foreach($filePathArray as $keymp3=>$valmp3){?><option value="<?=$valmp3?>" <? if($_POST['mp3file_'.$key] == $valmp3) {?>selected="selected"<? }?>><?=$valmp3?></option><? }}?></select>
						<td align="left" valign="top"><!--<input type="file" name="vocalFile_<?=$key?>"   size="30" >--></td>
					</tr>
					<?php }}}?>	  
					</table>

				   
				 
				   </td></tr>
					
					
					
					
					<?php
					
						if(!$_REQUEST['elementId']){
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
				  
			   <input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>"> 
			   <input type="hidden" name="elementId" value="<?=$_REQUEST['elementId']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
			   <input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                        <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
						<input type="hidden" name="elementId" value="<?=$_REQUEST['elementId']?>">
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