<?php
	include_once('includeconfig.php');
	include("../includes/classes/class.coach_master.php");
	
	/*
	 Instantiating the classes.
	*/
	if($_REQUEST['langId'] != ""){
		$lanId = $_REQUEST['langId'];
	}
	else{
		$lanId = 1;
	}

	//echo '<pre>',print_r($_REQUEST);
	$langval  = $lanId;
	$objCoach = new CoachMaster($lanId);
	$objGen   =	new General();
	
	$heading  = "Coaches";
	/*
	Take all the languages to an array.
	*/
	$languageArray=$siteLanguagesConfig;
	reset($languageArray);
	

	$errorMsg	=	array();
		
	if($_POST['add']||$_POST['update']){
	
		reset($languageArray);
		if(trim($_POST['coach_name'])=='')
					$errorMsg[] = "Coach name required";
		if(trim($_FILES['coach_image']['name'] != "")){
		$returnValue	=	$objGen->_checkUploadImage($_FILES['coach_image']['type'] );
			if(!$returnValue){
			$errorMsg[] = "Please upload images only";
			}
		}			
						
		if($_POST['add'])	{
			reset($languageArray);
			
				// check whether question is already existing while adding
				$check	= $objCoach->_isCoachExists($objGen->_clean_data($_REQUEST['coach_name']));
				if($check) 
					$errorMsg[] = "User name already exists";
			
				
		}
			
		if($_POST['update'])	{
			reset($languageArray);
			
				// check whether question is already exixting while updating
				$check = false;
				$check	= $objCoach->_isCoachExists($objGen->_clean_data($_REQUEST['coach_name']),$_REQUEST['coach_id']);
				if($check) 
						$errorMsg[] = "User name already exists";
		}
	
	if($_POST['add']){}
	//On clicking update button
	
	if($_POST['update']){
			
		if(count($errorMsg)==0)	{
							
			//Image updation
			if($_FILES['coach_image']['name'] != ""){
                           
						   $fileName   = uniqid();
                            $extension  = end(explode(".",$_FILES['coach_image']['name']));
                            $nextUpload = $objGen->_fileUploadWithImageResize('coach_image','../uploads/coaches/',$fileName,85,65);
                            $fileName = $fileName.".".$extension;
                            $_POST['coach_image']    = $fileName;
							if($_POST['coach_image'] !="" && is_file("../uploads/coaches/".$_POST['current_image'])){
							unlink("../uploads/coaches/".$_POST['current_photo']);
							}
                    }
            else{
                            $_POST['coach_image']    = $_POST['current_image'];
								
                }
			// data updation
			$coach_id			=	$_REQUEST['coach_id'];
			$userName			=	$_POST['coach_name'];
			$userImage			=	$_POST['coach_image'];
			$profile			= 	$_POST['coach_profile'];
			$language			=	$lanId;
			$status				=	$_POST['status'];
					
			$elmtsMaster		= array("coach_name" => $userName,"coach_image" => $userImage,"coach_profile" => $profile,"status" => $status,"coach_language" => $language,"status" => $status);
			$result 			= $objCoach->_updateCoachMaster($_REQUEST['coach_id'],$elmtsMaster);
			
			header("Location:list_caoches.php?status=success_update&langId=".$_REQUEST['langId']."&pageNo=".$_REQUEST['pageNo']."&maxrows=".$_REQUEST['maxrows']."&type=".$_REQUEST['type']."&field=".$_REQUEST['field']."&keyword=".stripslashes(stripslashes($_REQUEST['keyword'])));
			
		}
	}
	$currentImage			= $_POST['current_image'];
}	
	//if edit following will execute on loading
	if($_REQUEST['coach_id'] and count($errorMsg)==0){
		//Some security check here
		$result = $objCoach->_getAllCoachById($_REQUEST['coach_id']);
		}
// for getting home page title
	$getCoachQry = "SELECT DISTINCT(program_author) FROM program_master ORDER BY program_author ASC" ;
	$coachesList = $GLOBALS['db']->getAll($getCoachQry, DB_FETCHMODE_OBJECT);

// getting image details
if($currentImage != ""){
        $imageDetails = getimagesize('../uploads/coaches/'.$currentImage);
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
function createAjaxFn()

	{

  		var xmlHttp;

		try

  		{

  			// Firefox, Opera 8.0+, Safari

  			xmlHttp=new XMLHttpRequest();

  		}

		catch (e)

  		{

  			// Internet Explorer

  		try

    	{

    		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

    	}

  		catch (e)

    	{

		try

      	{

      		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

      	}

    	catch (e)

      	{

      		alert("Your browser does not support AJAX!");

      		return false;

      	}

    }

  }

	return xmlHttp;



}

function fun(id)
{
		xmlHttp2=createAjaxFn();
		
		if (xmlHttp2==null)
		{
		alert ("Browser does not support HTTP Request");
		return;
		}
		url	=	"getlang.php";
		url=url+"?id="+id;
		xmlHttp2.onreadystatechange=function(){
		if (xmlHttp2.readyState==4)
		{
		document.getElementById('test').innerHTML=xmlHttp2.responseText;
		}
		}
		xmlHttp2.open("GET",url,true);
		xmlHttp2.send(null);
		

	/*if(id == 1)
	{
		document.getElementById("lanname").value = 1;
	}
	else
	{
		document.getElementById("lanname").value = 2;
	}*/
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
                <TD  width="98%" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="9" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
                      <td width="561" height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
                      <td width="18" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
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
                                                  <td valign="middle" width="50"><a href="list_caoches.php?pageNo=<?=$_REQUEST['pageNo']?>&maxrows=<?=$_REQUEST['maxrows']?>&keyword=<?=stripslashes(stripslashes($_REQUEST['keyword']))?>"><img src="images/list.gif" border="0" alt="Listing Record">&nbsp;List </a></td>
                                                  <?php /*?><td valign="middle" class="noneAnchor"><img src="images/add.gif" border="0" alt="Add Record">&nbsp;Add </td><?php */?>
                                                </tr>
                                              </table></TD>
                                            <td align="right"><?php echo REQUIRED_MESSAGE;?></td>
                                          </TR>
                                        </table>
                                        <TABLE class="paragraph2" cellSpacing=0 border="0" cellPadding=2 width="100%">
                                          <TBODY>
                                            <?php
											//echo '<pre>',print_r($result);
											$n	= 0;
											if(count($result) != 0)
											{
											
												$_POST['coach_name'] 			= $result[$n]['coach_name'];
												$_POST['status'] 				= $result[$n]['status'];
												$_POST['coach_profile']			= $result[$n]['coach_profile'];
												$_POST['brand_name']			= $result[$n]['brandname'];
												$currentImage   				= $result[$n]['coach_image'];
											}
											?>
                                            <tr height="30px">
                                              <td width="40%" align="right"> Coaches:&nbsp;</td>
                                              <td><select name="coach_name" id="coach_name" style="width:150px; height:20px;">
                                                  <option value="0" <?php if($_POST['program_author']== "") echo "selected";?> >Select</option>
                                                  <?php 
												  for($i=0;$i<count($coachesList);$i++)
												  	{ 
												  ?>
                                                  <option value="<?=$coachesList[$i]->program_author;?>" <?php if($coachesList[$i]->program_author==$_POST['coach_name']) echo "selected";?> >
                                                  <?=$coachesList[$i]->program_author;?>
                                                  </option>
                                                  <?php 
												  	}
												  ?>
                                                </select>
                                              </td>
                                            </tr>
                                            <tr height="30px">
                                              <td width="40%" align="right"> Coach Image:&nbsp;</td>
                                              <td><input type="file" name="coach_image">
                                                <input type="hidden" name="current_image" value="<?=$currentImage?>"/>
                                                <? if($currentImage != ""){?>
                                                <a href="#" onClick="openNewWindow('../uploads/coaches/<?=$currentImage?>','windowname',<?=($imageDetails[0]+100);?>,<?=($imageDetails[1]+50);?>)">View</a>
                                                <? }?>[ 85 x 65 ]
                                              </td>
                                            </tr>
                                            <?php /*?><tr height="30px">
                                              <td width="40%" align="right">Status:&nbsp;</td>
                                                <td><input type="radio" name="status" id="active" value="1" <?php if($_POST['status'] == 1) echo "checked";?>>
                                                <label for="active">Yes</label>
                                                <input type="radio" name="status" id="inactive" value="0" <?php if($_POST['status'] == 0) echo "checked";?>>
                                                <label for="inactive">No</label></td>
                                            </tr><?php */?>
                                            <tr>
                                              <td colspan="2" align="right">&nbsp;
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="21%" align="right" valign="top">Profile:&nbsp;</td>
                                                    <td><textarea name="coach_profile" cols="50" rows="5" ><?=$objGen->_output($_POST['coach_profile'])?>
</textarea>
                                                    </td>
                                                  </tr>
                                                </table>
                                              </td>
                                            </tr>
                                            <?php 	if(!$_REQUEST['coach_id']){ 	?>
                                            <tr >
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
                                <input type="hidden" name="coach_id" value="<?=$_REQUEST['coach_id']?>">
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
                      <td width="9" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
                      <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
                      <td width="18" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
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
