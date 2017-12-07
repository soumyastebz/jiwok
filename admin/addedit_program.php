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
	include("../includes/classes/class.homeprogram.php");
	
	/*
	 Instantiating the classes.
	*/
	$lanId = $_REQUEST['langId'];
	
	$objService = new Service($lanId);
	$objGen   =	new General();
	
	$heading = "Home program's";
$langval	=	$_POST["lang"];
if($langval == "")
{
		if($_REQUEST["action"]	==	'edit')
	{
		$langval	=	$_REQUEST['langId'];
	}
	else
	{
		$langval	= "2";
	}	
}
	$fid	=	$_REQUEST["fid"];
$getAllTrainCats	= $objService->selectPrograms($langval);

$combo	=	$objService->get_combo_arr('category',$getAllTrainCats,'flex_id','program_title',$fid,$params="");
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){

		/*reset($languageArray);
		while(list($key,$value) = each($languageArray)){
				if(trim($_POST['service_content_'.$key])=='')
					$errorMsg[] = "Text required for {$value}";
		}
		*/
		if($_POST['add'])	{
			/*reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether Content is already existing while adding
				$check	= $objService->_isServiceExists($objGen->_clean_data($_REQUEST['service_content'.$key]));
				if($check) 
					$errorMsg[] = "Text already exists for {$value}";*/
			$check	= $objService->_isServiceExists($objGen->_clean_data($_REQUEST['category'],$langval));
				if($check) 
					$errorMsg[] = "Category already exists for {$value}";
			}				
				
		}
			
	/*	if($_POST['update'])	{
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				// check whether content is already exixting while updating
				$check = false;
				$check	= $objService->_isServiceExists($objGen->_clean_data($_REQUEST['service_content'.$key]),$_REQUEST['slideId']);
				if($check) 
						$errorMsg[] = "Title already exists";
			}			
				echo $check;
		}*/
	
	if($_POST['add']){
		//check admin already exists or not
			
		if(count($errorMsg)==0)	{
			$image	= $objService->getImage($_POST['category'],$langval);
			if($image['program_image']  != ""){
                            $fileName   = uniqid();
                            $extension  = end(explode(".",$image['program_image']));	
							$nn	=	explode(".",$image['program_image']);
                            $nextUpload = $objGen->_fileUploadWithImageResizeHomePgm($image['program_image'],$extension,'../uploads/homepgm/',$fileName,110,70);
                            $fileName = $fileName.".".$extension;
                           $_POST['user_image']    = $fileName;
           }
			$nextId = $objService->_insertMaster($_POST['category'],$_POST['service_status'],$_POST['user_image']);
			header("Location:list_program.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
		}
	}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
		//	reset($languageArray);
		//	while(list($key,$value) = each($languageArray)){
					 $query = "SELECT  count(*) as dataCount FROM home_program WHERE manager_id =".$_REQUEST['slideId']." AND language_id=".$langval."";
$result = $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);

					$coundRecs = $result[0]->dataCount;
					
					if(DB::isError($result)) 
						$result->getDebugInfo();
						if($coundRecs >= 1){
							$image	= $objService->getImage($_POST['category'],$langval);
							if($image['program_image']  != ""){
                            $fileName   = uniqid();
                            $extension  = end(explode(".",$image['program_image']));	
							$nn	=	explode(".",$image['program_image']);
                            $nextUpload = $objGen->_fileUploadWithImageResizeHomePgm($image['program_image'],$extension,'../uploads/homepgm/',$fileName,110,70);
                            $fileName = $fileName.".".$extension;
                           $_POST['user_image']    = $fileName;
						   }
							$category	= $_POST['category'];
							$status	= $_POST['service_status'];
							$elmts	= array("flex_id" => $category,"language_id" => $status,"image"=> $_POST['user_image']);
							$result = $objService->_updateService($_REQUEST['slideId'],$status,$elmts);
						}
						else
						{
							$image	= $objService->getImage($_POST['category'],$langval);
							if($image['program_image']  != ""){
                            $fileName   = uniqid();
                            $extension  = end(explode(".",$image['program_image']));	
							$nn	=	explode(".",$image['program_image']);
                            $nextUpload = $objGen->_fileUploadWithImageResizeHomePgm($image['program_image'],$extension,'../uploads/homepgm/',$fileName,110,70);
                            $fileName = $fileName.".".$extension;
                           $_POST['user_image']    = $fileName;
						   }
						    $category	= $_POST['category'];
							$status	= $_POST['service_status'];
							$result = $objService->_insertOneService($_REQUEST['slideId'],$status,$title);
						}
				
		//	}
			//print_r($title);
			
			header("Location:list_program.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".$_REQUEST['keyword']);
			
		}
	}
//}	
	//if edit following will execute on loading
	if($_REQUEST['slideId'] and count($errorMsg)==0){
		//Some security check here
		$result = $objService->_getAllById($_REQUEST['slideId']);
		
	}
		
?>
<HTML><HEAD><TITLE><?=$admin_title?></TITLE>
<? include_once('metadata.php');?>
<script language="javascript" src="js/mask.js"></script>
<script type="text/javascript" language="javascript">
function fun(id)
{
	document.getElementById("lang").value = id;
	document.serviceform.submit();
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
                       
						  
				<form name="serviceform" action="addedit_program.php" method="post" onSubmit="return formChecking()">
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
										
										<table height="50" class="topActions"><tr><td valign="middle" width="50"><a href="list_program.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=$_REQUEST['keyword']?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List   </a></td>
										<td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
										</tr></table>
									</TD><td align="right"><?php echo REQUIRED_MESSAGE;?></td>
									</TR>
									
								  </table>
                              
				  <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
				   <TBODY> 
					 <tr>
					  <td colspan="2" align="right">&nbsp;
				    <fieldset ><legend><?php echo $val; ?></legend>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr height="30px">
						<td width="30%" align="right"> Language:&nbsp;</td>
						<td>
						<input type="radio" name="service_status" id="active" value="1" <?php if($langval == 1) echo "checked";?>  onClick="fun(this.value);"><label for="active">English</label>
						<input type="radio" name="service_status" id="inactive" value="2" <?php if($langval == 2) echo "checked";?> onClick="fun(this.value);"><label for="inactive" >French</label></td>
					</tr> 
						<tr><td width="21%" align="right">
						  Category<?php echo REQUIRED;?>:&nbsp;						</td>
						<td width="79%">
							
							<?=$combo;	?>
						<input type="hidden" value="" name="lang" id="lang">
</td>
					</tr>
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
					  </tr>					
					  </table>
					  </fieldset>
					
					</td></tr>
					
					<?php 	if(!$_REQUEST['slideId']){ 	?>
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