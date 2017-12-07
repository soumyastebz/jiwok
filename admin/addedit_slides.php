<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-FAQ Management
   Programmer	::> NISHA
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit service
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.slide.php");
	//include_once('../FCKeditor/fckeditor.php');
	
	/*
	 Instantiating the classes.
	*/
	$lanId = $_REQUEST['langId'];
	
	$objService = new Service($lanId);
	$objGen   =	new General();
	
	$heading = "Slide's";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){

		reset($languageArray);
		while(list($key,$value) = each($languageArray)){
if($_REQUEST['slideId'] != 1)
{
				if(trim($_POST['service_content_'.$key])=='')
					$errorMsg[] = "Title required for {$value}";
					
				if(trim($_POST['service_description_'.$key])=='')
					$errorMsg[] = "Description required for {$value}";	
}	
else
{
		if(trim($_POST['service_content_'.$key])=='')
					$errorMsg[] = "Title required for {$value}";
}		
		}
if(trim($_POST['links'])=='')
					$errorMsg[] = "Link required";
						
		if($_POST['add'])	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether Content is already existing while adding
				$check	= $objService->_isServiceExists($objGen->_clean_data($_REQUEST['service_content'.$key]));
				if($check) 
					$errorMsg[] = "Title already exists for {$value}";
			}				
				
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether content is already exixting while updating
				$check = false;
				$check	= $objService->_isServiceExists($objGen->_clean_data($_REQUEST['service_content'.$key]),$_REQUEST['slideId']);
				if($check) 
						$errorMsg[] = "Title already exists";
			}			
				echo $check;
		}
	
	if($_POST['add']){
		//check admin already exists or not
			
		if(count($errorMsg)==0)	{
			$nextId = $objService->_insertMaster($_POST['links']);
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				$title	= $_POST['service_content_'.$key];
				$body	= $_POST['service_description_'.$key];
				$link	= $_POST['link'];
				$elmts	= array("slide_content" => $title,"slide_description" => $body,"service_link" => $link,"language_id" => $key);
				
				$result =  $objService->_insertService($elmts,count($languageArray));
			}
			header("Location:list_slides.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
					 $query = "SELECT  count(*) as dataCount FROM slide_manager WHERE slidemaster_id =".$_REQUEST['slideId']." AND language_id=".$key."";
$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

					$coundRecs = $result[0]->dataCount;
					
					if(DB::isError($result)) 
						echo $result->getDebugInfo();
						if($coundRecs >= 1){
							$title	= $_POST['service_content_'.$key];
							$body	= $_POST['service_description_'.$key];
							$link	= $_POST['links'];
							$elmts	= array("slide_content" => $title,"slide_description" => $body,"slide_link" => $link);
							$result = $objService->_updateService($_REQUEST['slideId'],$key,$elmts);
						}
						else
						{
						    $title	= $_POST['service_content_'.$key];
							$body	= $_POST['service_description_'.$key];
							$result = $objService->_insertOneService($_REQUEST['slideId'],$key,$title,$body);
						}
				
			}
			//print_r($title);
			
			header("Location:list_slides.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
		}
	}
}	
	//if edit following will execute on loading
	if($_REQUEST['slideId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objService->_getAllById($_REQUEST['slideId']);
		
	}
		
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript" src="js/mask.js"></script>
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
                       
						  
				<form name="serviceform" action="addedit_slides.php" method="post" onSubmit="return formChecking()">
						  <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                          <TBODY> 
                          <TR> 
                            <TD valign="top">
								   <table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
								  <tr>
										<td colspan="2" height="50" align="center" valign="bottom" class="sectionHeading"><?=$heading;?></td>
									</tr>
									<?php 
										if($errorMsg){ ?>
									<tr>
										<td align="center"><? print $objGen->_adminmessage_box($errorMsg,$mode='error',$url='normal') ?></td>
									</tr>
									<?php } ?>
				
									<TR> 
									<TD align="left">
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_slides.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
                                        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					<script src="../ckeditornew/ckeditor/ckeditor.js"></script>
                    <script src="../ckeditornew/ckeditor/adapters/jquery.js"></script> 
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     <? 
					 	$n	= 0;
					 	reset($languageArray);
						while(list($key,$val) = each($languageArray)){
							if(count($result) != 0){
								$_POST['service_content_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['slide_content'])));
								$_POST['service_description_'.$key]  =  stripslashes(stripslashes(stripslashes($result[$n]['slide_description'])));
								$_POST['service_status']  =  $result[$n]['slide_link'];
							}
					?> 
					<fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php if($_REQUEST['slideId'] != 1)
{
?>
						<tr><td width="21%" align="right">
						  Title<?php echo REQUIRED;?>:&nbsp;						</td>
						<td width="79%">
							
							<textarea name="service_content_<?=$key?>" cols="50" rows="1" ><?=$objGen->_output($_POST['service_content_'.$key])?></textarea></td>
					</tr>
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					<tr>
						<td width="21%" align="right">Description<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="service_description_<?=$key?>" cols="50" rows="5" ><?=$objGen->_output($_POST['service_description_'.$key])?></textarea>	</td>
					</tr>
<?php } else { ?>

<tr><td>
						  Title<?php echo REQUIRED;?>:&nbsp;						</td>
						
							
							
					 <td width="90%">
                     <script>
					CKEDITOR.disableAutoInline = true;
					$( document ).ready( function() {
							$( '#service_content_<?php echo $key; ?>' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
					});
							
					</script>
									<?
						 			/*$oFCKeditor = new FCKeditor('service_content_'.$key) ;	

									$oFCKeditor->BasePath = '../FCKeditor/' ;

									$oFCKeditor->Width	= '100%' ;

									$oFCKeditor->Height	= '400' ;

									if ( isset($_GET['Toolbar']) )

									$oFCKeditor->ToolbarSet = $_GET['Toolbar'] ;

									$oFCKeditor->Value = $objGen->_output($_POST['service_content_'.$key]);

									$oFCKeditor->create();*/

								?>
                                <textarea name="service_content_<?php echo $key; ?>" id="service_content_<?php echo $key; ?>" ><?php echo $_POST['service_content_'.$key]; ?></textarea></td></tr>
<?php } ?>
					  </table>
					  </fieldset>
					<? 
						$n++;
						}
					?>
					</td></tr>
					<tr height="30px">
						<td width="30%" align="right"> Link<?php echo REQUIRED;?>:&nbsp;</td>
						<td>
						<input type="text" name="links"  size="51" value="<?=$objGen->_output($_POST['service_status']); ?>">
						</td>
					</tr>

					<?php	if(!$_REQUEST['slideId']){ 	?>
					<tr >
						<td colspan="2" align="center">
							<input type="submit" name="add" value="&nbsp;Add&nbsp;"></td>
					</tr>
					<?php	}else{	?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="update" value="&nbsp;Update&nbsp;"></td>
					</tr>
					<?php	}	?>
				    </tbody>
			 	  </table>
				</TD>
                          </TR>
                          </TBODY>
                        </TABLE>
				<input type="hidden" name="langId" value="<?=$_REQUEST['langId']?>">  
				<input type="hidden" name="slideId" value="<?=$_REQUEST['slideId']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword" value="<?=$_REQUEST['keyword']?>">
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