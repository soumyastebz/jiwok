<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-testimonials Management
   Programmer	::> Ajith
   Date			::> 04/02/2009
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit testimonials.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.homepage.php");
	
	/*
	 Instantiating the classes.
	*/
	$lanId = $_REQUEST['langId'];

	$objHomepage = new Homepage($lanId);
	$objGen  	 = new General();
		
	$heading = "Homepage";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		if(trim($_POST['homepage_domain'])=='')
					$errorMsg[] = "Domain required";
		while(list($key,$value) = each($languageArray)){
				if(trim($_POST['homepage_title_'.$key])=='')
					$errorMsg[] = "Title required for {$value}";
					if(trim($_POST['homepage_content_'.$key])=='')
					$errorMsg[] = "Content required for {$value}";
					
					if(trim($_POST['homepage_keyword_'.$key])=='')
					$errorMsg[] = "Keyword required for {$value}";
					
					
				}
		if($_POST['add'])	{
			reset($languageArray);
			
				// check whether homepage is already existing while adding
				while(list($key,$value) = each($languageArray)){ 
				$check	= $objHomepage->_isHomepageExists($objGen->_clean_data($_POST['homepage_title_'.$key]));
				if($check) 
					$errorMsg[] = "Homepage already exists";
			
				}
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			
				// check whether question is already exixting while updating
				//$check = false;
				//$check	= $objHomepage->_isHomepageExists($objGen->_clean_data($_REQUEST['homepage_title']),$_REQUEST['masterId']);
				//if($check) 
						//$errorMsg[] = "Homepage already exists";
		}
	//Image updation
			if($_FILES['homepage_image']['name'] != ""){
                           
						   $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['homepage_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithImageResize('homepage_image','../uploads/homepage/',$fileName,1000,310);
                            $fileName = $fileName.".".$extension;
                            $_POST['homepage_image']    = $fileName;
							if($_POST['homepage_image'] !="" && is_file("../uploads/homepage/".$_POST['homepage_current_image'])){
							unlink("../uploads/homepage/".$_POST['homepage_current_image']);
							}
                    }
            else{
                            $_POST['homepage_image']    = $_POST['homepage_current_image'];
                }
			//wizard Image updation
			if($_FILES['wizard_image']['name'] != ""){
                           
						   $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['wizard_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithOutImageResize('wizard_image','../uploads/homepage/',$fileName);
                            $fileName = $fileName.".".$extension;
                            $_POST['wizard_image']    = $fileName;
							if($_POST['wizard_image'] !="" && is_file("../uploads/homepage/".$_POST['wizard_current_image'])){
							unlink("../uploads/homepage/".$_POST['wizard_current_image']);
							}
                    }
            else{
                            $_POST['wizard_image']    = $_POST['wizard_current_image'];
                }
				//Services Image updation
			if($_FILES['service_image']['name'] != ""){
                           
						   $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['service_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithOutImageResize('service_image','../uploads/homepage/',$fileName);
                            $fileName = $fileName.".".$extension;
                            $_POST['service_image']    = $fileName;
							if($_POST['service_image'] !="" && is_file("../uploads/homepage/".$_POST['current_image'])){
							unlink("../uploads/homepage/".$_POST['service_current_image']);
							}
           }else{
                            $_POST['service_image']    = $_POST['service_current_image'];
                }	
			// data updation
			$masterId				=	$_REQUEST['masterId'];
			$homepagedomain			=	$_POST['homepage_domain'];
			$currentHomeImage		=	$_POST['homepage_image'];
			$currentWizardImage		=	$_POST['wizard_image'];
			$currentServiceImage	=	$_POST['service_image'];
			$homepageStatus			=	$_POST['homepage_status'];
			
	if($_POST['add']){
		//check admin already exists or not
		
		if(count($errorMsg)==0)	{
		
			$insArr				=	array('homepage_domain' => $homepagedomain,'homepage_image' => $homepageimage,'wizard_image' => $wizardimage,'service_image' => $serviceimage,'homepage_status' => $homepageStatus);
			
			$nextId = $objHomepage->_insertMaster($insArr);
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
			    $title  	= $_POST['homepage_title_'.$key];
				$content	= $_POST['homepage_content_'.$key];
				$keyword	= $_POST['homepage_keyword_'.$key];
				
				$elmts	= array("homepage_title" => $title,"homepage_content" => $content,"homepage_keyword" => $keyword,"master_id" => $nextId,"lang_id" => $key);
				
				$result =  $objHomepage->_insertHomepage($elmts,count($languageArray));
			}
			
			header("Location:list_homepage.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
							
						
			$elmtsMaster		= array("homepage_domain" => $homepagedomain,"homepage_image" => $currentHomeImage,"wizard_image" => $currentWizardImage,"service_image" => $currentServiceImage,"homepage_status" => $homepageStatus);
			
			$result = $objHomepage->_updateHomepageMaster($_REQUEST['masterId'],$elmtsMaster);
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
					 $query = "SELECT  count(*) as dataCount FROM homepage WHERE master_id =".$masterId." AND lang_id=".$key.""; 
					$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
					$coundRecs = $result[0]->dataCount;
					
					if(DB::isError($result)) 
						 $result->getDebugInfo();
						if($coundRecs >= 1){
							  $title  	= $_POST['homepage_title_'.$key];
				              $content	= $_POST['homepage_content_'.$key];
				              $keyword	= $_POST['homepage_keyword_'.$key];
							$elmts	= array("homepage_title" => $title,"homepage_content" => $content,"homepage_keyword" => $keyword);
							
							$result = $objHomepage->_updateHomepage($masterId,$key,$elmts);
						}
						else
						{
						       $title  = $_POST['homepage_title_'.$key];
				              $content	= $_POST['homepage_content_'.$key];
				              $keyword	= $_POST['homepage_keyword_'.$key];
							$result = $objHomepage->_insertOneHomepage($masterId,$key,$title,$content,$keyword);
						}
				
			}
			header("Location:list_homepage.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
			
		}
	}
}	
	//if edit following will execute on loading
	if($_REQUEST['masterId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objHomepage->_getAllById($_REQUEST['masterId']);
		//print_r($result);
		$currentHomeImage			=	$result[0]['homepage_image'];
		$currentWizardImage			=	$result[0]['wizard_image'];
		$currentServiceImage		=	$result[0]['service_image'];
		}
// for getting home page title
	$queryHome = "SELECT homepage_id,homepage_title FROM homepage WHERE 1 ORDER BY homepage_title ASC" ;
	$resultHome = $GLOBALS['db']->getAll($queryHome, DB_FETCHMODE_OBJECT);

// getting image details
if($currentHomeImage != ""){
        $imageDetailsHomePage = getimagesize('../uploads/homepage/'.$currentHomeImage);
    }
// getting image details
if($currentWizardImage != ""){
        $imageDetailsWizard = getimagesize('../uploads/homepage/'.$currentWizardImage);
    }
	// getting image details
if($currentServiceImage != ""){
        $imageDetailsService = getimagesize('../uploads/homepage/'.$currentServiceImage);
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
                       
						  
				<form name="faqform" action="addedit_homepage.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_homepage.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                 	 
					          
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					<?php
					$n	= 0;
					if(count($result) != 0){
						$_POST['homepage_domain'] 			= $result[$n]['homepage_domain'];
						$_POST['homepage_status'] 			= $result[$n]['homepage_status'];
						$currentImage   					= $result[$n]['homepage_image'];
					}
					?> 
					 <tr height="30px">
						<td width="33%" align="right"> Homepage Domain<?php echo REQUIRED;?>:&nbsp;</td>
						<td width="67%">
						<input type="text" name="homepage_domain" id="homepage_domain" value="<?=$objGen->_output($_POST['homepage_domain']);?>"/>
						</td>
					</tr>
					
					<tr height="30px">
						<td width="33%" align="right"> Homepage Image:&nbsp;<br>
						  (Size should be 1000*310)&nbsp;</td>
						<td>
						<input type="file" name="homepage_image">
		          		<input type="hidden" name="homepage_current_image" value="<?=$currentHomeImage?>"/>
						  <? if($currentHomeImage != ""){?>
						  <a href="#" onClick="openNewWindow('../uploads/homepage/<?=$currentHomeImage?>','windowname',<?=($imageDetailsHomePage[0]+100);?>,<?=($imageDetailsHomePage[1]+50);?>)">View</a>
						  <? }?>
						</td>
					</tr>
					<tr height="30px">
						<td width="33%" align="right">Search Wizard Image:&nbsp;<br>
						  (Size should be 187*80)&nbsp;</td>
						<td>
						<input type="file" name="wizard_image">
		          		<input type="hidden" name="wizard_current_image" value="<?=$currentWizardImage?>"/>
						  <? if($currentWizardImage != ""){?>
						  <a href="#" onClick="openNewWindow('../uploads/homepage/<?=$currentWizardImage?>','windowname',<?=($imageDetailsWizard[0]+100);?>,<?=($imageDetailsWizard[1]+50);?>)">View</a>
						  <? }?>
						</td>
					</tr>
					<tr height="30px">
						<td width="33%" align="right"> Service Image:&nbsp;<br>
						  (Size should be 1000*436)&nbsp;</td>
						<td>
						<input type="file" name="service_image">
		          		<input type="hidden" name="service_current_image" value="<?=$currentServiceImage?>"/>
						  <? if($currentServiceImage != ""){?>
						  <a href="#" onClick="openNewWindow('../uploads/homepage/<?=$currentServiceImage?>','windowname',<?=($imageDetailsService[0]+100);?>,<?=($imageDetailsService[1]+50);?>)">View</a>
						  <? }?>
						</td>
					</tr>
					<? 
					 	
					 	reset($languageArray);
						while(list($key,$val) = each($languageArray)){
							if(count($result) != 0){
							
							    $_POST['homepage_title_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['homepage_title'])));
								$_POST['homepage_keyword_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['homepage_keyword'])));
								$_POST['homepage_content_'.$key] = stripslashes(stripslashes(stripslashes($result[$n]['homepage_content'])));
								}
					?>   
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				     
					<fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
						
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>
					  <tr>
						<td width="21%" align="right">Title<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="homepage_title_<?=$key?>" cols="50" rows="5"><?=$objGen->_output($_POST['homepage_title_'.$key])?></textarea>	</td>
					</tr>
					<tr>
						<td width="21%" align="right">Keyword<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="homepage_keyword_<?=$key?>" cols="50" rows="5"><?=$objGen->_output($_POST['homepage_keyword_'.$key])?></textarea>	</td>
					</tr>
					<tr>
						<td width="21%" align="right">Description<?php echo REQUIRED;?>:&nbsp;</td>
						<td><textarea name="homepage_content_<?=$key?>" cols="50" rows="5"><?=$objGen->_output($_POST['homepage_content_'.$key])?></textarea>	</td>
					</tr>
					  </table>
					  </fieldset>
					<? 
						$n++;
						}
					?>
					</td></tr>
					<tr height="30px">
						<td width="33%" align="right"> Status:&nbsp;</td>
						<td>
						<input type="radio" name="homepage_status" id="active" value="1" <?php if($_POST['homepage_status'] == 1) echo "checked";?>><label for="active">Active</label>
						<input type="radio" name="homepage_status" id="inactive" value="0" <?php if($_POST['homepage_status'] == 0) echo "checked";?>><label for="inactive">Inactive</label></td>
					</tr>
					<?php 	if(!$_REQUEST['masterId']){ 	?>
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
				<input type="hidden" name="masterId" value="<?=$_REQUEST['masterId']?>">
			   <input type="hidden" name="pageNo" value="<?=$_REQUEST['pageNo']?>">
			   <input type="hidden" name="maxrows" value="<?=$_REQUEST['maxrows']?>">
			   <input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
				<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
                <input type="hidden" name="keyword" value="<?=stripslashes(stripslashes($_REQUEST['keyword']))?>">
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