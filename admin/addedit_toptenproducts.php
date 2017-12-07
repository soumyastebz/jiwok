<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Top Ten Products Management
   Programmer	::> Ganga
   Date			::> 06/05/2011
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit top ten products with title,image,rank and web link.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.top_products.php");
	
	/*
	 Instantiating the classes.
	*/
	$lanId 		= $_REQUEST['langId'];
	$masterId	=	$_REQUEST['masterId'];

	$objProducts = new Products($lanId);
	$objGen  	 = new General();
		
	$heading = "Entertainments";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		if(trim($_POST['rank'])=='')
			$errorMsg[] = "Rank required";
		elseif(trim($_POST['rank'] > 10))
			$errorMsg[] = "Cannot assign rank value greater than 10";
		elseif($objProducts->_isProductsRankExists($objGen->_clean_data(trim($_POST['rank'])),$masterId)){
			$errorMsg[] = "Rank already assigned";
		}
		while(list($key,$value) = each($languageArray)){
			if(trim($_POST['title_'.$key])=='')
				$errorMsg[] = "Title required for {$value}";
			if(trim($_POST['web_link_'.$key])=='')
				$errorMsg[] = "Web link required for {$value}";
			if($_FILES['program_image_'.$key]['name'] == '' and trim($_POST['current_image_'.$key]) == '')
				$errorMsg[] = "Picture required for {$value}";
		}
		if($_POST['add'])	{
			reset($languageArray);
			
				// check whether homepage is already existing while adding
				while(list($key,$value) = each($languageArray)){ 
				$check	= $objProducts->_isProductsExists($objGen->_clean_data($_POST['title_'.$key]));
				if($check) 
					$errorMsg[] = "Products already exists";
			
				}
		}
			
			
	if($_POST['add']){
		//check admin already exists or not
		
		if(count($errorMsg)==0)	{
			
			$rank  			= 	trim($_POST['rank']);
			$insArr			=	array('rank' => $rank,'status' => 1);
			$nextId 		= 	$objProducts->_insertMaster($insArr);
		
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
				
				$rank  		= 	trim($_POST['rank']);
			    $title  	= 	$_POST['title_'.$key];
				$web_link  	= 	$_POST['web_link_'.$key];
				
				if($_FILES['program_image_'.$key]['name'] != ""){
					if($_POST['current_image_'.$key] <> "" && is_file("../uploads/products/".$_POST['current_image_'.$key])){
						unlink("../uploads/products/".$_POST['current_image_'.$key]);
					}
					
					$fileName   = uniqid();
					$extension  = end(explode(".",$_FILES['program_image_'.$key]['name']));
					$nextUpload = $objGen->_fileUploadWithImageResize('program_image_'.$key,'../uploads/products/',$fileName,172,144);
					$fileName 	= $fileName.".".$extension;
				}else
					$fileName = $_REQUEST['current_image_'.$key];
				$elmts		= 	array( "rank" => $rank,
									   "master_id" => $nextId,
									   "title" => $title,
									   "program_image" => $fileName,
									   "web_link" => $web_link,
									   "lang_id" => $key,
									   "status" => 1);
				$result 	=  	$objProducts->_insertProducts($elmts,count($languageArray));
			}
				
			
			header("Location:list_toptenproducts.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
	}
	//On clicking update button
	
	if($_POST['update']){
	//echo '<pre>',print_r($_REQUEST);exit;
				
		if(count($errorMsg)==0)	{
							
			$rank  			= 	trim($_POST['rank']);
			$masterId		=	$_REQUEST['masterId'];
			$elmtsMaster	= 	array("rank" => $rank,"status" => 1);
			$result 		= 	$objProducts->_updateProductsMaster($masterId,$elmtsMaster);
			
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
			
				if($_FILES['program_image_'.$key]['name'] != ""){
					$fileName   = uniqid();
					$extension  = end(explode(".",$_FILES['program_image_'.$key]['name']));
					$nextUpload = $objGen->_fileUploadWithImageResize('program_image_'.$key,'../uploads/products/',$fileName,172,144);
                    $fileName = $fileName.".".$extension;
                }else
					$fileName = $_REQUEST['current_image_'.$key];

				$query = "SELECT  count(*) as dataCount FROM top_ten_products WHERE master_id =".$masterId." AND lang_id=".$key.""; 
				$result 	= $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
				$coundRecs 	= $result[0]->dataCount;
				if(DB::isError($result)) $result->getDebugInfo();
				
				$title  	= 	$_POST['title_'.$key];
				$web_link  	= 	$_POST['web_link_'.$key];
				$elmts		= 	array( "rank" => $rank,
									   "title" => $title,
									   "program_image" => $fileName,
									   "web_link" => $web_link,
									   "lang_id" => $key,
									   "status" => 1);
				
				if($coundRecs >= 1){
					$result = $objProducts->_updateProducts($masterId,$key,$elmts);
				}
				else{
					$result = $objProducts->_insertOneProducts($masterId,$key,$elmts);
				}
				
			}
			header("Location:list_toptenproducts.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
			
		}
	}
}	
	
	
	//if edit following will execute on loading
	if($_REQUEST['masterId']){
		//Some security check here
		$result 			= 	$objProducts->_getAllById($_REQUEST['masterId']);
		$rank				=	$result[0]['rank'];
		$title				=	$result[0]['title'];
		$program_image		=	$result[0]['program_image'];
		$status				=	$result[0]['status'];
		$web_link			=	$result[0]['web_link'];
		$lang_id			=	$result[0]['lang_id'];
	}
// for getting home page title
	$queryHome = "SELECT homepage_id,homepage_title FROM homepage WHERE 1 ORDER BY homepage_title ASC" ;
	$resultHome = $GLOBALS['db']->getAll($queryHome, DB_FETCHMODE_OBJECT);

// getting image details
if($program_image != ""){
        $program_image = getimagesize('../uploads/products/'.$program_image);
    }
	//echo '<pre>',print_r($program_image);
?>
<HTML>
<HEAD>
<TITLE>
<?=$admin_title?>
</TITLE>
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
    <TD align="left" valign="top"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="middleTableBg">
        <TR>
          <TD  valign="top" align=left width="175" rowSpan="2" ><TABLE cellSpacing="0" cellPadding="0" width="175" border=0>
              <TR>
                <TD valign="top"><TABLE cellSpacing=0 cellPadding=2 width=175  border=0>
                    <TBODY>
                      <TR valign="top">
                        <TD valign="top"><? include ('leftmenu.php');?></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
            </TABLE></TD>
          <TD valign="top" align=left width=0></TD>
        </TR>
        <TR>
          <TD valign="top" width="1067"><!---Contents Start Here----->
            <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
              <TR>
                <TD  width="98%" valign="top"><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                      <td width="543" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                      <td width="11" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
                    </tr>
                    <tr>
                      <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
                      <td valign="top"><TABLE cellSpacing=0 cellPadding=0 border=0 align="center">
                          <TR>
                            <TD valign="top" width=564 bgColor=white><form name="faqform" action="addedit_toptenproducts.php" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
                                <TABLE cellSpacing=0 cellPadding=4 width=561 border=0>
                                  <TBODY>
                                    <TR>
                                      <TD valign="top"><table class="paragraph2" cellspacing=0 cellpadding=0 width=553 border=0>
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
                                            <TD align="left"><table height="50" class="topActions">
                                                <tr>
                                                  <td valign="middle" width="50"><a href="list_toptenproducts.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List </a></td>
                                                  <td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td>
                                                </tr>
                                              </table></TD>
                                            <td align="right"><?php echo REQUIRED_MESSAGE;?></td>
                                          </TR>
                                        </table>
                                        <TABLE class="paragraph2" cellSpacing=0 cellPadding=2 width="100%">
                                          <TBODY>
                                            <?php
											$n	= 0;
											if(count($result) != 0){
												$_POST['rank'] 			=$rank;

											}
											?>
                                            <tr height="30px">
                                              <td width="33%" align="right">Rank<?php echo REQUIRED;?></td>
                                              <td width="67%"><input type="text" name="rank" id="rank" maxlength="2" value="<?=$objGen->_output($_POST['rank']);?>"/>
                                              </td>
                                            </tr>
                                            <?php					 	
											reset($languageArray);
											while(list($key,$val) = each($languageArray)){
												if(count($result) != 0){
												if($_REQUEST['action'] == "edit")
												
												$_POST['title_'.$key] 			= ((stripslashes($result[$n]['title'])));
												$_POST['rank_'.$key] 			= ((stripslashes($result[$n]['rank'])));
												$_POST['program_image_'.$key] 	= ((stripslashes($result[$n]['program_image'])));
												$_POST['web_link_'.$key] 		= ((stripslashes($result[$n]['web_link'])));
												
												}
											?>
                                            <tr>
                                              <td colspan="2" align="right">&nbsp;
                                                <fieldset>
                                                <legend><?php echo $val; ?></legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td colspan="2" align="center">&nbsp;</td>
                                                  </tr>
												  <tr>
                                                    <td width="21%" align="right">Title<?php echo REQUIRED;?>:&nbsp;</td>
                                                    <td><input type="text" name="title_<?=$key?>" value="<?=$objGen->_output($_POST['title_'.$key])?>" style="width:300px;" /></td>
                                                  </tr>
                                                  <tr height="30px">
                                                    <td width="33%" align="right">Web Link<?php echo REQUIRED;?>:&nbsp;</td>
                                                    <td width="67%"><input type="text" name="web_link_<?=$key?>" id="web_link_<?=$key?>" value="<?=$objGen->_output($_POST['web_link_'.$key])?>" style="width:380px;" /></td>
                                                  </tr>
                                                  <tr height="30px">
                                                    <td width="33%" align="right">Picture:&nbsp;&nbsp;[172x144]</td>
                                                      <td><input type="file" name="program_image_<?=$key?>">
													  <input type="hidden" name="current_image_<?=$key?>" value="<?=$result[0]['program_image'];?>"/>
                                                      <? if($_POST['program_image_'.$key]  != ""){?>
                                                      <a href="#" onClick="openNewWindow('../uploads/products/<?=$_POST['program_image_'.$key] ?>','windowname',<?=($program_image[0]+100);?>,<?=($program_image[1]+50);?>)">View</a>
                                                      <? }?>
                                                    </td>
                                                  </tr>
                                                  
                                                </table>
                                                </fieldset>
                                                <?php
													$n++;
													}
												?>
                                              </td>
                                            </tr>
                                            <?php /*?><tr height="30px">
                                              <td width="33%" align="right"> Status:&nbsp;</td>
                                              <td><input type="radio" name="homepage_status" id="active" value="1" <?php if($_POST['homepage_status'] == 1) echo "checked";?>>
                                                <label for="active">Active</label>
                                                <input type="radio" name="homepage_status" id="inactive" value="0" <?php if($_POST['homepage_status'] == 0) echo "checked";?>>
                                                <label for="inactive">Inactive</label></td>
                                            </tr><?php */?>
                                            <?php 	if(!$_REQUEST['masterId']){ ?>
                                            <tr>
                                              <td colspan="2" align="center"><input type="submit" name="add" value="&nbsp;Add&nbsp;"></td>
                                            </tr>
                                            <?php	}else{	?>
                                            <tr>
                                              <td colspan="2" align="center"><input type="submit" name="update" value="&nbsp;Update&nbsp;"></td>
                                            </tr>
                                            <?php	}	?>
                                          </tbody>
                                        </table></TD>
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
                              </form></TD>
                          </TR>
                        </TABLE></td>
                      <td background="images/side2.jpg">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                      <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                   