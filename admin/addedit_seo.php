<?php
/**************************************************************************** 
   Project Name	::> Jiwok 
   Module 		::> Admin-Top Ten seo Management
   Programmer	::> Ganga
   Date			::> 06/05/2011
   
   DESCRIPTION::::>>>>
   This  code userd to add/edit top ten products with title,image,rank and web link.
   
*****************************************************************************/
	include_once('includeconfig.php');
	include("../includes/classes/class.seo.php");
	
	/*
	 Instantiating the classes.
	*/
	$lanId 		= $_REQUEST['langId'];
	$masterId	=	$_REQUEST['masterId'];

	$objseo 	= new seo($lanId);
	$objGen  	= new General();
		
	$heading = "Manage SEO";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	
	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		if(trim($_POST['name'])=='')
			$errorMsg[] = "Page name required";
		if(trim($_POST['link'])=='')
			$errorMsg[] = "Page link required";
		while(list($key,$value) = each($languageArray)){
			if(trim($_POST['meta_title_'.$key])=='')
				$errorMsg[] = "Title required for {$value}";
			if(trim($_POST['meta_keyword_'.$key])=='')
				$errorMsg[] = "Keyword for {$value}";
			if(trim($_POST['meta_description_'.$key])=='')
				$errorMsg[] = "Description required for {$value}";
		}
		if($_POST['add'])	{
				// check whether homepage is already existing while adding
				//$check	= $objseo->_isseoExists($objGen->_clean_data($_POST['name']));
				if($check) 
					$errorMsg[] = "Meta tag already exists";
			
		}
			
			
	if($_POST['add']){
		//check admin already exists or not
		
		if(count($errorMsg)==0)	{
			
			$name  			= 	trim($_POST['name']);
			$link  			= 	trim($_POST['link']);
			$insArr			=	array('name' => $name,'link' => $link);
			$nextId 		= 	$objseo->_insertMaster($insArr);
		
			reset($languageArray);
			while(list($key,$value) = each($languageArray))
			{
				
				$title  	= 	$_POST['meta_title_'.$key];
				$keyword  	= 	$_POST['meta_keyword_'.$key];
				$description= 	$_POST['meta_description_'.$key];
				
				$elmts		= 	array( "meta_title" => $title,
									   "master_id" => $nextId,
									   "meta_keyword" => $keyword,
									   "meta_description" => $description,
									   "lang_id" => $key);
				$result 	=  	$objseo->_insertseo($elmts,count($languageArray));
			}
				
			
			header("Location:list_seo.php?status=success_add&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
		}
	}
	//On clicking update button
	
	if($_POST['update']){
	//echo '<pre>',print_r($_REQUEST);exit;
				
		if(count($errorMsg)==0)	{
							
			$name  			= 	trim($_POST['name']);
			$link  			= 	trim($_POST['link']);
			$masterId		=	$_REQUEST['masterId'];
			$elmtsMaster	= 	array("name" => $name,"link" => $link);
			$result 		= 	$objseo->_updateseoMaster($masterId,$elmtsMaster);
			
			reset($languageArray);
			while(list($key,$value) = each($languageArray)){
			
				$query = "SELECT  count(*) as dataCount FROM seo WHERE master_id =".$masterId." AND lang_id=".$key.""; 
				$result 	= $GLOBALS['db']->getAll($query, DB_FETCHMODE_OBJECT);
				$coundRecs 	= $result[0]->dataCount;
				if(DB::isError($result)) $result->getDebugInfo();
				
				$title  	= 	$_POST['meta_title_'.$key];
				$keyword  	= 	$_POST['meta_keyword_'.$key];
				$description= 	$_POST['meta_description_'.$key];
				
				$elmts		= 	array( "meta_title" => $title,
									   "meta_keyword" => $keyword,
									   "meta_description" => $description,
									   "lang_id" => $key);
				
				if($coundRecs >= 1){
					$result = $objseo->_updateseo($masterId,$key,$elmts);
				}
				else{
					$result = $objseo->_insertOneseo($masterId,$key,$elmts);
				}
				
			}
			header("Location:list_seo.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
			
		}
	}
}
	
	
	//if edit following will execute on loading
	if($_REQUEST['masterId']){
		//Some security check here
		$result 			= 	$objseo->_getAllById($_REQUEST['masterId']);
		//echo '<pre>',print_r($result);
		$name				=	$result[0]['name'];
		$link				=	$result[0]['link'];
	}

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
                            <TD valign="top" width=564 bgColor=white><form name="faqform" action="" method="post" onSubmit="return formChecking()" enctype="multipart/form-data">
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
                                                  <td valign="middle" width="50"><a href="list_seo.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List </a></td>
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
											if(count($result) != 0)
											{
												$_POST['name'] 	=	$name;
												$_POST['link'] 	=	$link;
											}
											?>
                                            <tr height="30px">
                                              <td width="33%" align="right">Page name<?php echo REQUIRED;?></td>
                                              <td width="67%"><input type="text" name="name" id="name" value="<?=$objGen->_output($_POST['name']);?>" style="width:200px;"/>
                                              </td>
                                            </tr>
	                                            <tr height="30px">
                                              <td width="33%" align="right">Link<?php echo REQUIRED;?></td>
                                              <td width="67%"><input type="text" name="link" id="link" value="<?=$objGen->_output($_POST['link']);?>" style="width:200px;"/>
                                              </td>
                                            </tr>
					<?php					 	
						reset($languageArray);
						while(list($key,$val) = each($languageArray)){
							if(count($result) != 0)
							{
								if($_REQUEST['action'] == "edit")
								{
									$_POST['meta_title_'.$key] 			= ((stripslashes($result[$n]['meta_title'])));
									$_POST['meta_keyword_'.$key] 		= ((stripslashes($result[$n]['meta_keyword'])));
									$_POST['meta_description_'.$key] 	= ((stripslashes($result[$n]['meta_description'])));
								}
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
                                                    <td><input type="text" name="meta_title_<?=$key?>" value="<?=$objGen->_output($_POST['meta_title_'.$key])?>" style="width:300px;" /></td>
                                                  </tr>
                                                  <tr height="30px">
                                                    <td width="33%" align="right">Meta keyword<?php echo REQUIRED;?>:&nbsp;</td>
                                                    <td width="67%"><input type="text" name="meta_keyword_<?=$key?>" id="meta_keyword_<?=$key?>" value="<?=$objGen->_output($_POST['meta_keyword_'.$key])?>" style="width:380px;" /></td>
                                                  </tr>
                                                  <tr height="30px">
                                                    <td width="33%" align="right" valign="top">Meta Description<?php echo REQUIRED;?>:&nbsp;</td>
														<td width="67%"><textarea name="meta_description_<?=$key?>" id="meta_description_<?=$key?>" style="width:380px; height:100px;" /><?=$objGen->_output($_POST['meta_description_'.$key])?></textarea></td>
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
                      <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
                    </tr>
                  </table></TD>
              </TR>
            </TABLE></TD>
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
